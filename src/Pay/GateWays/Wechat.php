<?php
/**
 * @Desc:微信支付
 * @author: hbh
 * @Time: 2020/7/11   14:38
 */

namespace bydls\pays\Pay\GateWays;


use bydls\pays\Pay\Contracts\GatewayApplicationInterface;
use bydls\pays\Pay\Contracts\GatewayInterface;
use bydls\pays\Pay\Events\Events;
use bydls\pays\Pay\Events\MethodCalled;
use bydls\pays\Pay\Events\PayStarting;
use bydls\pays\Pay\Events\NotifyReceived;
use bydls\pays\Pay\Events\SignFailed;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidGatewayException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\GateWays\Wechat\Support;
use bydls\Utils\CodeUtil;
use bydls\Support\Str;
use bydls\Support\Collection;
use bydls\pays\Log\Log;
use bydls\pays\Pay\Config\Config;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method Response         app(array $config)          APP 支付
 * @method Collection       groupRedpack(array $config) 分裂红包
 * @method Collection       mini(array $config)      小程序支付
 * @method Collection       mp(array $config)           公众号支付
 * @method Collection       pos(array $config)          刷卡支付
 * @method Collection       redpack(array $config)      普通红包
 * @method Collection       scan(array $config)         扫码支付
 * @method Collection       transfer(array $config)     企业付款
 * @method RedirectResponse wap(array $config)          H5 支付
 */

class Wechat implements GatewayApplicationInterface
{
    /**
     * 普通模式.
     */
    const MODE_NORMAL = 'normal';

    /**
     * 沙箱模式.
     */
    const MODE_DEV = 'dev';

    /**
     * 香港钱包 API.
     */
    const MODE_HK = 'hk';

    /**
     * 境外 API.
     */
    const MODE_US = 'us';

    /**
     * 服务商模式.
     */
    const MODE_SERVICE = 'service';

    /**
     * Const url.
     */
    const URL = [
        self::MODE_NORMAL => 'https://api.mch.weixin.qq.com/',
        self::MODE_DEV => 'https://api.mch.weixin.qq.com/sandboxnew/',
        self::MODE_HK => 'https://apihk.mch.weixin.qq.com/',
        self::MODE_SERVICE => 'https://api.mch.weixin.qq.com/',
        self::MODE_US => 'https://apius.mch.weixin.qq.com/',
    ];


    protected $payload;


    protected $gateway;


    public function __construct(Config $config)
    {
        $this->gateway = Support::create($config)->getBaseUri();
        $this->payload = [
            'appid' => $config->get('app_id', ''),
            'mch_id' => $config->get('mch_id', ''),
            'nonce_str' => CodeUtil::random(),
            'notify_url' => $config->get('notify_url', ''),
            'sign' => '',
            'trade_type' => '',
            'spbill_create_ip' => Request::createFromGlobals()->getClientIp(),
        ];

        if ($config->get('mode', self::MODE_NORMAL) === static::MODE_SERVICE) {
            $this->payload = array_merge($this->payload, [
                'sub_mch_id' => $config->get('sub_mch_id'),
                'sub_appid' => $config->get('sub_app_id', ''),
            ]);
        }
    }

    public function __call($method, $params)
    {
        return self::pay($method, ...$params);
    }

    /**微信的各种支付
     * @param String $gateway
     * @param array $params
     * @return \bydls\pays\Pay\Contracts\Collection|Response
     * @throws InvalidGatewayException
     * @author: hbh
     * @Time: 2020/7/14   11:32
     */
    public function pay($gateway, $params = [])
    {
        Events::dispatch(new PayStarting('Wechat', $gateway, $params));

        $this->payload = array_merge($this->payload, $params);

        $gateway = get_class($this).'\\'.Str::studlyCap($gateway).'Gateway';

        if (class_exists($gateway)) {
            return $this->makePay($gateway);
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] Not Exists");
    }

    protected function makePay($gateway)
    {
        $app = new $gateway();

        if ($app instanceof GatewayInterface) {
            return $app->pay($this->gateway, array_filter($this->payload, function ($value) {
                return '' !== $value && !is_null($value);
            }));
        }

        throw new InvalidGatewayException("Pay Gateway [{$gateway}] Must Be An Instance Of GatewayInterface");
    }



    /**查询订单
     * @param String $order
     * @param string $type
     * @return Collection
     * @throws GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   11:22
     */
    public function find($order, string $type = 'wap'): Collection
    {
        if ('wap' != $type) {
            unset($this->payload['spbill_create_ip']);
        }

        $gateway = get_class($this).'\\'.Str::studlyCap($type).'Gateway';

        if (!class_exists($gateway) || !is_callable([new $gateway(), 'find'])) {
            throw new GatewayException("{$gateway} Done Not Exist Or Done Not Has FIND Method");
        }

        $config = call_user_func([new $gateway(), 'find'], $order);

        $this->payload = Support::filterPayload($this->payload, $config['order']);

        Events::dispatch(new MethodCalled('Wechat', 'Find', $this->gateway, $this->payload));

        return Support::requestApi(
            $config['endpoint'],
            $this->payload,
            $config['cert']
        );
    }

    /**退款
     * @param array $order
     * @return Collection
     * @throws GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   11:22
     */
    public function refund(array $order): Collection
    {
        $this->payload = Support::filterPayload($this->payload, $order, true);

        Events::dispatch(new MethodCalled('Wechat', 'Refund', $this->gateway, $this->payload));

        return Support::requestApi(
            'secapi/pay/refund',
            $this->payload,
            true
        );
    }

    /**撤单
     * @param String $order
     * @return Collection
     * @throws GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   11:24
     */
    public function cancel(String $order): Collection
    {
        unset($this->payload['spbill_create_ip']);

        $this->payload = Support::filterPayload($this->payload, $order);

        Events::dispatch(new MethodCalled('Wechat', 'Cancel', $this->gateway, $this->payload));

        return Support::requestApi(
            'secapi/pay/reverse',
            $this->payload,
            true
        );
    }

    /**关闭订单
     * @param String $order
     * @return Collection
     * @throws GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   11:25
     */
    public function close($order): Collection
    {
        unset($this->payload['spbill_create_ip']);

        $this->payload = Support::filterPayload($this->payload, $order);

        Events::dispatch(new MethodCalled('Wechat', 'Close', $this->gateway, $this->payload));

        return Support::requestApi('pay/closeorder', $this->payload);
    }

    /**返回结果验签
     * @param null $content
     * @param bool $refund
     * @return Collection
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @author: hbh
     * @Time: 2020/7/14   11:28
     */
    public function verify($content = null, bool $refund = false): Collection
    {
        $content = $content ?? Request::createFromGlobals()->getContent();

        Events::dispatch(new NotifyReceived('Wechat', '', [$content]));

        $data = Support::fromXml($content);
        if ($refund) {
            $decrypt_data = Support::decryptRefundContents($data['req_info']);
            $data = array_merge(Support::fromXml($decrypt_data), $data);
        }

        Log::debug('Resolved The Received Wechat Request Data', $data);

        if ($refund || Support::generateSign($data) === $data['sign']) {
            return new Collection($data);
        }

        Events::dispatch(new SignFailed('Wechat', '', $data));

        throw new InvalidSignException('Wechat Sign Verify FAILED', $data);
    }

    /**
     * @return Response
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @author: hbh
     * @Time: 2020/7/14   11:26
     */
    public function success(): Response
    {
        Events::dispatch(new MethodCalled('Wechat', 'Success', $this->gateway));

        return new Response(
            Support::toXml(['return_code' => 'SUCCESS', 'return_msg' => 'OK']),
            200,
            ['Content-Type' => 'application/xml']
        );
    }
}
