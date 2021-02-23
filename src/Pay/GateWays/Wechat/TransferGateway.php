<?php

namespace bydls\pays\Pay\GateWays\Wechat;

use Symfony\Component\HttpFoundation\Request;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Events\PayStarted;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\GateWays\Wechat;
use bydls\Support\Collection;

class TransferGateway extends Gateway
{
    /**企业转账
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   13:46
     */
    public function pay($endpoint, array $payload): Collection
    {
        if (Wechat::MODE_SERVICE === $this->mode) {
            unset($payload['sub_mch_id'], $payload['sub_appid']);
        }

        $type = Support::getTypeName($payload['type'] ?? '');

        $payload['mch_appid'] = Support::getInstance()->getConfig($type, '');
        $payload['mchid'] = $payload['mch_id'];

        if ('cli' !== php_sapi_name() && !isset($payload['spbill_create_ip'])) {
            $payload['spbill_create_ip'] = Request::createFromGlobals()->server->get('SERVER_ADDR');
        }

        unset($payload['appid'], $payload['mch_id'], $payload['trade_type'],
            $payload['notify_url'], $payload['type']);

        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new PayStarted('Wechat', 'Transfer', $endpoint, $payload));

        return Support::requestApi(
            'mmpaymkttransfers/promotion/transfers',
            $payload,
            true
        );
    }

    /**查询企业转账
     * @param $order
     * @return array
     */
    public function find($order): array
    {
        return [
            'endpoint' => 'mmpaymkttransfers/gettransferinfo',
            'order' => is_array($order) ? $order : ['partner_trade_no' => $order],
            'cert' => true,
        ];
    }

    /**
     * Get trade type config.
     */
    protected function getTradeType(): string
    {
        return '';
    }
}
