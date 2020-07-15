<?php

namespace bydls\pay\Pay\Gateways\Ali;

use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Gateways\Ali\Gateway;

class RefundGateway extends Gateway
{
    /**退款查询
     * @param $order
     * @return array
     * @author: hbh
     * @Time: 2020/7/15   9:54
     */
    public function find(String $order): array
    {
        return [
            'method' => 'alipay.trade.fastpay.refund.query',
            'biz_content' => json_encode(['out_trade_no' => $order]),
        ];
    }
    /**退款没有支付
     * @param string $endpoint
     * @param array $payload
     * @return \bydls\Utils\Collection|void
     * @throws InvalidArgumentException
     * @author: hbh
     * @Time: 2020/7/14   13:39
     */
    public function pay($endpoint, array $payload)
    {
        throw new InvalidArgumentException('Not Support Refund In Pay');
    }

}
