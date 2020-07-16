<?php
/**
 * @Desc:红包
 * @author: hbh
 * @Time: 2020/7/16   17:39
 */

namespace bydls\pays\UsageMode;


class WechatRedpack
{
    //要支付的金额
    private $total_fee;

    private $body='充值';

    //交易订单号
    private $trade_no;
}