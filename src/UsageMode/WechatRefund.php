<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   16:15
 */

namespace bydls\pays\UsageMode;

use bydls\pays\Pay\Pay;
use bydls\pays\Config;

class WechatRefund
{
    //需要退款的金额，该金额不能大于订单金额,单位为分
    private $refund_fee;

    //订单总额 单位分
    private $total_fee;

    // 退款单号 标识一次退款请求，同一退款单号多次请求只退一笔
    private $out_refund_no;

    //交易订单号
    private $out_trade_no;


    public function __construct($out_trade_no, $refund_fee, $out_refund_no, $total_fee)
    {
        $this->refund_fee = $refund_fee* 100;
        $this->out_trade_no = $out_trade_no;
        $this->out_refund_no = $out_refund_no;
        $this->total_fee = $total_fee* 100;
    }


    /**微信退款
     * @return \bydls\Utils\Collection|mixed|null
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/17   10:50
     */
    public function refund()
    {
        $order = [
            'out_trade_no' => $this->out_trade_no,
            'refund_fee' => $this->refund_fee, // **单位：元
            'out_refund_no' => $this->out_refund_no,
            'total_fee' => $this->total_fee,
        ];
        $pay = Pay::Wechat(Config::Wechat()->wx_refund())->refund($order);
        if ($pay->return_code == 'SUCCESS' && $pay->return_msg == 'OK') {
            return $pay;
        }
        return $pay->err_code_des ?? null;
    }


    /**退款查询
     * @return \bydls\Utils\Collection|mixed|null
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/17   11:06
     */
    public function findRefund()
    {
        $order = [
            'out_trade_no' => $this->out_trade_no,
            'out_refund_no' => $this->out_refund_no,
        ];
        $pay = Pay::Wechat(Config::Wechat()->wx_refund())->find($order, 'refund');
        if ($pay->return_code == 'SUCCESS' && $pay->return_msg == 'OK') {
            return $pay;
        }
        return $pay->err_code_des ?? null;
    }


}