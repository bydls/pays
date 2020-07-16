<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/15   8:39
 */

namespace bydls\pays\Pay\GateWays;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use bydls\pays\Pay\Contracts\GatewayApplicationInterface;
use bydls\pays\Pay\Contracts\GatewayInterface;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidConfigException;
use bydls\pays\Pay\Exceptions\InvalidGatewayException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\Gateways\Ali\Support;
use bydls\pays\Pay\Config\Config;
use bydls\Utils\Collection;

use bydls\Utils\Str;

/**
 * @method Response   app(array $config)      APP 支付
 * @method Collection pos(array $config)      刷卡支付
 * @method Collection scan(array $config)     扫码支付
 * @method Collection transfer(array $config) 帐户转账
 * @method Response   wap(array $config)      手机网站支付
 * @method Response   web(array $config)      电脑支付
 * @method Collection mini(array $config)     小程序支付
 */
class Ali implements GatewayApplicationInterface
{
    /**
     * Const mode_normal.
     */
    const MODE_NORMAL = 'normal';

    /**
     * Const mode_dev.
     */
    const MODE_DEV = 'dev';

    /**
     * Const mode_service.
     */
    const MODE_SERVICE = 'service';

    /**
     * Const url.
     */
    const URL = [
        self::MODE_NORMAL => 'https://openapi.alipay.com/gateway.do?charset=utf-8',
        self::MODE_DEV => 'https://openapi.alipaydev.com/gateway.do?charset=utf-8',
    ];

    /**
     * Alipay payload.
     *
     * @var array
     */
    protected $payload;

    /**
     * Alipay gateway.
     *
     * @var string
     */
    protected $gateway;

    /**
     * extends.
     *
     * @var array
     */
    protected $extends;

    /**
     * Bootstrap.
     *
     *
     * @throws \Exception
     */
    public function __construct(Config $config)
    {
        $this->gateway = Support::create($config)->getBaseUri();
        $this->payload = [
            'app_id' => $config->get('app_id'),
            'method' => '',
            'format' => 'JSON',
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'version' => '1.0',
            'return_url' => $config->get('return_url'),
            'notify_url' => $config->get('notify_url'),
            'timestamp' => date('Y-m-d H:i:s'),
            'sign' => '',
            'biz_content' => '',
            'app_auth_token' => $config->get('app_auth_token'),
        ];

        if ($config->get('app_cert_public_key') && $config->get('alipay_root_cert')) {
            $this->payload['app_cert_sn'] = Support::getCertSN($config->get('app_cert_public_key'));
            $this->payload['alipay_root_cert_sn'] = Support::getRootCertSN($config->get('alipay_root_cert'));
        }
    }

    /**
     * @param string $method
     * @param array  $params
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws InvalidGatewayException
     * @throws InvalidSignException
     *
     * @return Response|Collection
     */
    public function __call($method, $params)
    {
        if (isset($this->extends[$method])) {
            return $this->makeExtend($method, ...$params);
        }

        return $this->pay($method, ...$params);
    }

    /**
     * Pay an order.
     *
     * @param string $gateway
     * @param array  $params
     *
     * @throws InvalidGatewayException
     *
     * @return Response|Collection
     */
    public function pay($gateway, $params = [])
    {
        Events::dispatch(new Events\PayStarting('Ali', $gateway, $params));

        $this->payload['return_url'] = $params['return_url'] ?? $this->payload['return_url'];
        $this->payload['notify_url'] = $params['notify_url'] ?? $this->payload['notify_url'];

        unset($params['return_url'], $params['notify_url']);

        $this->payload['biz_content'] = json_encode($params);

        $gateway = get_class($this).'\\'.Str::studlyCap($gateway).'Gateway';

        if (class_exists($gateway)) {
            return $this->makePay($gateway);
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] not exists");
    }
    /**
     * Make pay gateway.
     *
     *
     * @throws InvalidGatewayException
     *
     * @return Response|Collection
     */
    protected function makePay(string $gateway)
    {
        $app = new $gateway();

        if ($app instanceof GatewayInterface) {
            return $app->pay($this->gateway, array_filter($this->payload, function ($value) {
                return '' !== $value && !is_null($value);
            }));
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] Must Be An Instance Of GatewayInterface");
    }

    /**
     * makeExtend.
     *
     *
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     */
    protected function makeExtend(string $method, array ...$params): Collection
    {
        $params = count($params) >= 1 ? $params[0] : $params;

        $function = $this->extends[$method];

        $customize = $function($this->payload, $params);

        if (!is_array($customize) && !($customize instanceof Collection)) {
            throw new InvalidArgumentException('Return Type Must Be Array Or Collection');
        }

        Events::dispatch(new Events\MethodCalled('Ali', 'extend - '.$method, $this->gateway, is_array($customize) ? $customize : $customize->toArray()));

        if (is_array($customize)) {
            $this->payload = $customize;
            $this->payload['sign'] = Support::generateSign($this->payload);

            return Support::requestApi($this->payload);
        }

        return $customize;
    }

    /**返回结果验签
     * @param null $data
     * @param bool $refund
     * @return Collection
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   11:35
     */
    public function verify($data = null, bool $refund = false): Collection
    {
        if (is_null($data)) {
            $request = Request::createFromGlobals();

            $data = $request->request->count() > 0 ? $request->request->all() : $request->query->all();
        }

        if (isset($data['fund_bill_list'])) {
            $data['fund_bill_list'] = htmlspecialchars_decode($data['fund_bill_list']);
        }

        Events::dispatch(new Events\NotifyReceived('Ali', '', $data));

        if (Support::verifySign($data)) {
            return new Collection($data);
        }

        Events::dispatch(new Events\SignFailed('Ali', '', $data));

        throw new InvalidSignException('Ali Sign Verify FAILED', $data);
    }


    /**查询
     * @param String $order
     * @param string $type
     * @return Collection
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   11:52
     */
    public function find(String $order, string $type = 'wap'): Collection
    {
        $gateway = get_class($this).'\\'.Str::studlyCap($type).'Gateway';

        if (!class_exists($gateway) || !is_callable([new $gateway(), 'find'])) {
            throw new GatewayException("{$gateway} Done Not Exist Or Done Not Has FIND Method");
        }

        $config = call_user_func([new $gateway(), 'find'], $order);

        $this->payload['method'] = $config['method'];
        $this->payload['biz_content'] = $config['biz_content'];
        $this->payload['sign'] = Support::generateSign($this->payload);

        Events::dispatch(new Events\MethodCalled('Ali', 'Find', $this->gateway, $this->payload));

        return Support::requestApi($this->payload);
    }


    /**退款
     * @param array $order
     * @return Collection
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @author: hbh
     * @Time: 2020/7/16   15:48
     */
    public function refund(array $order): Collection
    {
        $this->payload['method'] = 'alipay.trade.refund';
        $this->payload['biz_content'] = json_encode( $order);
        $this->payload['sign'] = Support::generateSign($this->payload);

        Events::dispatch(new Events\MethodCalled('Ali', 'Refund', $this->gateway, $this->payload));

        return Support::requestApi($this->payload);
    }

    /**撤单 如果此订单用户支付失败，支付宝系统会将此订单关闭；如果用户支付成功，支付宝系统会将此订单资金退还给用户
     * @param String $order
     * @return Collection
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   11:40
     */
    public function cancel(String $order): Collection
    {
        $this->payload['method'] = 'alipay.trade.cancel';
        $this->payload['biz_content'] = json_encode(['out_trade_no' => $order]);
        $this->payload['sign'] = Support::generateSign($this->payload);

        Events::dispatch(new Events\MethodCalled('Ali', 'Cancel', $this->gateway, $this->payload));

        return Support::requestApi($this->payload);
    }

    /**关闭订单
     * @param String $order
     * @return Collection
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   11:41
     */
    public function close(String $order): Collection
    {
        $this->payload['method'] = 'alipay.trade.close';
        $this->payload['biz_content'] = json_encode(['out_trade_no' => $order]);
        $this->payload['sign'] = Support::generateSign($this->payload);

        Events::dispatch(new Events\MethodCalled('Ali', 'Close', $this->gateway, $this->payload));

        return Support::requestApi($this->payload);
    }

    /**
     * @param  $bill //2020-06-01
     * @return string
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   11:42
     */
    public function download($bill): string
    {
        $this->payload['method'] = 'alipay.data.dataservice.bill.downloadurl.query';
        $this->payload['biz_content'] = json_encode(['bill_type' => 'trade', 'bill_date' => $bill]);
        $this->payload['sign'] = Support::generateSign($this->payload);

        Events::dispatch(new Events\MethodCalled('Ali', 'Download', $this->gateway, $this->payload));

        $result = Support::requestApi($this->payload);

        return ($result instanceof Collection) ? $result->get('bill_download_url') : '';
    }

    /**
     * @return Response
     * @author: hbh
     * @Time: 2020/7/15   11:47
     */
    public function success(): Response
    {
        Events::dispatch(new Events\MethodCalled('Ali', 'Success', $this->gateway));

        return new Response('success');
    }

    /**
     * extend.
     *
     *
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @throws InvalidArgumentException
     */
    public function extend(string $method, callable $function, bool $now = true): ?Collection
    {
        if (!$now && !method_exists($this, $method)) {
            $this->extends[$method] = $function;

            return null;
        }

        $customize = $function($this->payload);

        if (!is_array($customize) && !($customize instanceof Collection)) {
            throw new InvalidArgumentException('Return Type Must Be Array Or Collection');
        }

        Events::dispatch(new Events\MethodCalled('Ali', 'extend', $this->gateway, $customize));

        if (is_array($customize)) {
            $this->payload = $customize;
            $this->payload['sign'] = Support::generateSign($this->payload);

            return Support::requestApi($this->payload);
        }

        return $customize;
    }


}