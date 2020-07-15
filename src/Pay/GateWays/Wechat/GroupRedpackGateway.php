<?php

namespace bydls\pays\Pay\Gateways\Wechat;

use bydls\pays\Pay\Events;
use bydls\pays\Pay\Events\PayStarted;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\Gateways\Wechat;
use bydls\Utils\Collection;

class GroupRedpackGateway extends Gateway
{

    /**裂变红包
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['wxappid'] = $payload['appid'];
        $payload['amt_type'] = 'ALL_RAND';

        if (Wechat::MODE_SERVICE === $this->mode) {
            $payload['msgappid'] = $payload['appid'];
        }

        unset($payload['appid'], $payload['trade_type'],
              $payload['notify_url'], $payload['spbill_create_ip']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new PayStarted('Wechat', 'Group Redpack', $endpoint, $payload));

        return Support::requestApi(
            'mmpaymkttransfers/sendgroupredpack',
            $payload,
            true
        );
    }

    /**
     * Get trade type config.
     *
     */
    protected function getTradeType(): string
    {
        return '';
    }
}
