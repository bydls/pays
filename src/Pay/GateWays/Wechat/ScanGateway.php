<?php

namespace bydls\pays\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\Request;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Events\PayStarted;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\Utils\Collection;

class ScanGateway extends Gateway
{
    /**扫码支付
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   13:45
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        $payload['trade_type'] = $this->getTradeType();

        Events::dispatch(new PayStarted('Wechat', 'Scan', $endpoint, $payload));

        return $this->preOrder($payload);
    }

    /**
     * Get trade type config.
     *
     */
    protected function getTradeType(): string
    {
        return 'NATIVE';
    }
}
