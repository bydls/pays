<?php

namespace bydls\pays\Pay\GateWays\Ali;

use bydls\pays\Pay\Events\Events;
use bydls\pays\Pay\Exceptions;
use bydls\pays\Pay\GateWays\Ali;
use bydls\Support\Collection;

class MiniGateway extends Gateway
{
    /**小程序支付
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
        $payload['method'] = 'alipay.trade.create';
        $biz_array = json_decode($payload['biz_content'], true);
        if (empty($biz_array['buyer_id'])) {
            throw new InvalidArgumentException('buyer_id required');
        }
        if ((Ali::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['biz_content'] = json_encode($biz_array);
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Ali', 'Mini', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
