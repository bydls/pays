<?php

namespace bydls\pays\Pay\Gateways\Ali;

use bydls\pays\Pay\Events;
use bydls\pays\Pay\Exceptions;
use bydls\pays\Pay\Gateways\Ali;
use bydls\Utils\Collection;

class PosGateway extends Gateway
{
    /**刷卡支付 pos机支付
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws Exceptions\GatewayException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidConfigException
     * @throws Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   10:10
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['method'] = 'alipay.trade.pay';
        $biz_array = json_decode($payload['biz_content'], true);
        $biz_array['product_code']=$this->getProductCode();
        if ((Ali::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['biz_content'] = json_encode(array_merge($biz_array, ['scene' => 'bar_code']));
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Ali', 'Pos', $endpoint, $payload));

        return Support::requestApi($payload);
    }
    protected function getProductCode(): string
    {
        return 'FACE_TO_FACE_PAYMENT';
    }
}
