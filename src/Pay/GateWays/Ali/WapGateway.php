<?php

namespace bydls\pays\Pay\GateWays\Ali;

class WapGateway extends WebGateway
{
    /**PC和H5 的结构相同,只是参数不同
     * @return string
     * @author: hbh
     * @Time: 2020/7/15   11:17
     */
    protected function getMethod(): string
    {
        return 'alipay.trade.wap.pay';
    }

    /**PC和H5 的结构相同,只是参数不同
     * @return string
     * @author: hbh
     * @Time: 2020/7/15   11:17
     */
    protected function getProductCode(): string
    {
        return 'QUICK_WAP_WAY';
    }
}
