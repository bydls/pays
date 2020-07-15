<?php
/**
 * @Desc: 支付类方法 需要 $total_fee,$trade_no,$body 三个参数
 * @author: hbh
 * @Time: 2020/7/14   17:43
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Log\Logger;
use bydls\pays\Pay\Pay;


class WechatPay
{
    //要支付的金额
    private $total_fee;

    private $body='充值';

    //交易订单号
    private $trade_no;

    //平台类型
    private $app_id;

    //员工ID/企业ID/服务商ID
    private $user_id;


    //订单类型
    private $trade_no_type;

    //订单类型
    private $trade_no_prefix;

    public function __construct($total_fee,$trade_no,$body)
    {
        $this->total_fee=$total_fee*100;
        $this->trade_no=$trade_no;
        $this->body=$body;
    }



    /**获取微信支付二维码
     * @return mixed  二维码链接
     * @author: hbh
     * @Time: 2020/6/30   9:01
     */
    public function getWxScanUrl()
    {
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_fee' =>  $this->total_fee, // **单位：分**
            'body' => $this->body,
        ];
        $pay = Pay::wechat(WechatConfig::wx_pc_pay())->scan($order);
        Logger::info('【微信扫码支付获取支付二维吗】', $pay->all());
        if($pay->return_code=='SUCCESS'&&$pay->return_msg=='OK'){
            return $pay->code_url;
        }
        return '';
    }

}