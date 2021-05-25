<?php

namespace bydls\pays\Pay\GateWays\Ali;

use Symfony\Component\HttpFoundation\Response;
use bydls\pays\Pay\Events\Events;
use bydls\pays\Pay\Exceptions;
use bydls\pays\Pay\GateWays\Ali;
class AppGateway extends Gateway
{

    /**App支付
     * @param string $endpoint
     * @param array $payload
     * @return Response
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidConfigException
     * @author: hbh
     * @Time: 2020/7/15   10:09
     */
    public function pay($endpoint, array $payload): Response
    {
        $payload['method'] = 'ali.trade.app.pay';

        $biz_array = json_decode($payload['biz_content'], true);
        $biz_array['product_code']=$this->getProductCode();
        if ((Ali::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['biz_content'] = json_encode($biz_array);
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Ali', 'App', $endpoint, $payload));

        return new Response(http_build_query($payload));
    }

    protected function getProductCode(): string
    {
        return 'QUICK_MSECURITY_PAY';
    }
}
