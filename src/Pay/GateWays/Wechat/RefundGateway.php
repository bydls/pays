<?php

namespace bydls\pays\Pay\Gateways\Wechat;

use bydls\pays\Pay\Exceptions\InvalidArgumentException;

class RefundGateway extends Gateway
{
    /**退款查询
     * @param $order
     * @return array
     * @author: hbh
     * @Time: 2020/7/14   13:38
     */
    public function find($order): array
    {
        return [
            'endpoint' => 'pay/refundquery',
            'order' => is_array($order) ? $order : ['out_trade_no' => $order],
            'cert' => false,
        ];
    }


    /**退款没有支付
     * @param string $endpoint
     * @param array $payload
     * @return \bydls\Support\Collection|void
     * @throws InvalidArgumentException
     * @author: hbh
     * @Time: 2020/7/14   13:39
     */
    public function pay($endpoint, array $payload)
    {
        throw new InvalidArgumentException('Not Support Refund In Pay');
    }

    /**
     * Get trade type config.
     *
     *
     * @throws InvalidArgumentException
     */
    protected function getTradeType()
    {
        throw new InvalidArgumentException('Not Support Refund In Pay');
    }
}
