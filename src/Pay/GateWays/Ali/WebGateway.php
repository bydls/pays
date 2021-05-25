<?php

namespace bydls\pays\Pay\GateWays\Ali;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use bydls\pays\Pay\Events\Events;
use bydls\pays\Pay\Exceptions;
use bydls\pays\Pay\GateWays\Ali;

class WebGateway extends Gateway
{
    /**PC端电脑网站支付
     * @param string $endpoint
     * @param array $payload
     * @return Response
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidConfigException
     * @author: hbh
     * @Time: 2020/7/15   10:17
     */
    public function pay($endpoint, array $payload): Response
    {
        $biz_array = json_decode($payload['biz_content'], true);
        $biz_array['product_code'] = $this->getProductCode();

        $method = $biz_array['http_method'] ?? 'POST';

        unset($biz_array['http_method']);
        if ((Ali::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['method'] = $this->getMethod();
        $payload['biz_content'] = json_encode($biz_array);
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Ali', 'Web/Wap', $endpoint, $payload));

        return $this->buildPayHtml($endpoint, $payload, $method);
    }


    /**查询支付订单
     * @param String $order
     * @return array
     * @author: hbh
     * @Time: 2020/7/15   11:13
     */
    public function find(String $order): array
    {
        return [
            'method' => 'alipay.trade.query',
            'biz_content' => json_encode(['out_trade_no' => $order]),
        ];
    }

    /**
     * @param $endpoint
     * @param $payload
     * @param string $method
     * @return Response
     * @author: hbh
     * @Time: 2020/7/15   11:14
     */
    protected function buildPayHtml($endpoint, $payload, $method = 'POST'): Response
    {
        if ('GET' === strtoupper($method)) {
            return new RedirectResponse($endpoint.'&'.http_build_query($payload));
        }

        $sHtml = "<form id='alipay_submit' name='alipay_submit' action='".$endpoint."' method='".$method."'>";
        foreach ($payload as $key => $val) {
            $val = str_replace("'", '&apos;', $val);
            $sHtml .= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml .= "<input type='submit' value='ok' style='display:none;'></form>";
        $sHtml .= "<script>document.forms['alipay_submit'].submit();</script>";

        return new Response($sHtml);
    }

    /**PC和H5 的结构相同,只是参数不同
     * @return string
     * @author: hbh
     * @Time: 2020/7/15   11:15
     */
    protected function getMethod(): string
    {
        return 'alipay.trade.page.pay';
    }

    /**PC和H5 的结构相同,只是参数不同
     * @return string
     * @author: hbh
     * @Time: 2020/7/15   11:15
     */
    protected function getProductCode(): string
    {
        return 'FAST_INSTANT_TRADE_PAY';
    }
}
