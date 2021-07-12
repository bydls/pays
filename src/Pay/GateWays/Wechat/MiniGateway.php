<?php

namespace bydls\pays\Pay\GateWays\Wechat;

use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\GateWays\Wechat;
use bydls\Support\Collection;

class MiniGateway extends MpGateway
{
    /**小程序支付
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   11:51
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['appid'] = Support::getInstance()->miniapp_id;

        if (Wechat::MODE_SERVICE === $this->mode) {
            $payload['sub_appid'] = Support::getInstance()->sub_miniapp_id;
            $this->payRequestUseSubAppId = true;
        }

        return parent::pay($endpoint, $payload);
    }
}
