<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   10:15
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;

class AliOrder
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
        $pay = Pay::ali(AliConfig::ali_config())->find($this->trade_no);
        return $pay;
    }

}