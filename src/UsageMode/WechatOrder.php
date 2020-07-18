<?php
/**
 * @Desc:微信订单类方法：查询，撤单，退款等
 * @author: hbh
 * @Time: 2020/6/30   9:22
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;
use bydls\pays\WechatConfig;

class WechatOrder
{

    //交易订单号
    private $trade_no;


    public function __construct($trade_no)
    {
        $this->trade_no = $trade_no;
    }


    /**查询订单
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/17   11:45
     */
    public function find()
    {
        $pay = Pay::wechat(WechatConfig::wx_config())->find($this->trade_no);
        return $pay;
    }

    /**撤单
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/17   11:46
     */
    public function cancel()
    {
        $pay = Pay::wechat(WechatConfig::wx_config())->cancel($this->trade_no);
        return $pay;
    }


    /**关闭交易
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/17   11:47
     */
    public function close()
    {
        $pay = Pay::wechat(WechatConfig::wx_config())->close($this->trade_no);
        return $pay;
    }

    /**转账结果查询
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/17   11:47
     */
    public function findTransfer()
    {
        $pay = Pay::wechat(WechatConfig::wx_config())->find($this->trade_no, 'transfer');
        return $pay;
    }
}