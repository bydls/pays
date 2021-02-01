<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   15:56
 */

namespace bydls\pays\UsageMode;

use bydls\pays\Pay\Pay;
use bydls\pays\Config;

class AliRefund
{
    //需要退款的金额，该金额不能大于订单金额,单位为元，支持两位小数
    private $refund_amount;

    // 退款标识符 标识一次退款请求，同一笔交易多次退款需要保证唯一
    private $out_request_no;

    //交易订单号
    private $out_trade_no;


    public function __construct($out_trade_no, $refund_amount, $out_request_no)
    {
        $this->refund_amount = $refund_amount;
        $this->out_trade_no = $out_trade_no;
        $this->out_request_no = $out_request_no;
    }


    /**支付宝退款
     * @return \bydls\Support\Collection|mixed|null
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidConfigException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/17   14:16
     */
    public function refund()
    {
        $order = [
            'out_trade_no' => $this->out_trade_no,
            'refund_amount' => $this->refund_amount, // **单位：元
            'out_request_no' => $this->out_request_no,
        ];
        $pay = Pay::Ali(Config::Ali()->ali_refund())->refund($order);
        if ($pay->code == '10000') {
            return $pay;
        }
        return $pay->msg ?? null;
    }

    /**退款查询
     * @return mixed|string
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidConfigException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/16   16:12
     */
    public function findRefund()
    {
        $order = [
            'out_trade_no' => $this->out_trade_no,
            'out_request_no' => $this->out_request_no,
        ];
        $pay = Pay::Ali(Config::Ali()->ali_refund())->find($order, 'refund');
        if ($pay->code == '10000') {
            return $pay;
        }
        return $pay->msg ?? null;
    }
}