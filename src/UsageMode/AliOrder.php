<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   10:15
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;
use bydls\pays\config\AliConfig;
class AliOrder
{

    //交易订单号
    private $trade_no;


    public function __construct($trade_no)
    {
        $this->trade_no=$trade_no;
    }


    /**查询订单
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidConfigException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/16   15:32
     */
    public function find()
    {
        $pay = Pay::ali(AliConfig::ali_config())->find($this->trade_no);
        return $pay;
    }

    /**撤单
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidConfigException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/16   15:43
     */
    public function cancel()
    {
        $pay = Pay::ali(AliConfig::ali_config())->cancel($this->trade_no);
        return $pay;
    }

    /**关闭交易
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidConfigException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/16   16:06
     */
    public function close()
    {
        $pay = Pay::ali(AliConfig::ali_config())->close($this->trade_no);
        return $pay;
    }

    /**转账结果查询
     * @return \bydls\Utils\Collection
     * @throws \bydls\pays\Pay\Exceptions\GatewayException
     * @throws \bydls\pays\Pay\Exceptions\InvalidConfigException
     * @throws \bydls\pays\Pay\Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/16   16:13
     */
    public function findTransfer()
    {
        $pay = Pay::ali(AliConfig::ali_config())->find($this->trade_no,'transfer');
        return $pay;
    }
}