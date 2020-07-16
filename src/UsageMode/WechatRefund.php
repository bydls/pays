<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   16:15
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Log\Log;
use bydls\pays\Pay\Pay;

class WechatRefund
{
    //需要退款的金额，该金额不能大于订单金额,单位为分
    private $refund_fee;

    //订单总额
    private $total_fee;

    // 退款标识符 标识一次退款请求，同一笔交易多次退款需要保证唯一
    private $out_request_no;

    //交易订单号
    private $out_out_trade_no;


    public function __construct($out_out_trade_no,$out_request_no,$total_fee,$refund_fee)
    {
        $this->refund_fee=$refund_fee;
        $this->out_out_trade_no=$out_out_trade_no;
        $this->out_request_no=$out_request_no;
        $this->total_fee=$total_fee;
    }





}