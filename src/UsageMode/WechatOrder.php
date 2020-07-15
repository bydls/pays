<?php
/**
 * @Desc:微信订单类方法：查询，撤单，退款等
 * @author: hbh
 * @Time: 2020/6/30   9:22
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;

class WechatOrder
{

    //交易订单号
    private $trade_no;


    public function __construct($trade_no)
    {
        $this->trade_no=$trade_no;
    }

    /**查询订单
     * @return \bydls\Utils\Collection  订单信息
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     */
    public function getWxPCPayResult()
    {
        $pay = Pay::wechat(WechatConfig::wx_config())->find($this->trade_no);
        return $pay;
    }

}