<?php

namespace bydls\pays\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Events\PayStarted;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\Gateways\Wechat;
use bydls\Utils\Collection;

class RedpackGateway extends Gateway
{
    /**普通红包
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   13:37
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['wxappid'] = $payload['appid'];

        if ('cli' !== php_sapi_name()) {
            $payload['client_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        }

        if (Wechat::MODE_SERVICE === $this->mode) {
            $payload['msgappid'] = $payload['appid'];
        }

        unset($payload['appid'], $payload['trade_type'],
              $payload['notify_url'], $payload['spbill_create_ip']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new PayStarted('Wechat', 'Redpack', $endpoint, $payload));

        return Support::requestApi(
            'mmpaymkttransfers/sendredpack',
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
