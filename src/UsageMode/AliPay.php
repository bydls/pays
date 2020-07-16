<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   10:10
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Log\Log;
use bydls\pays\Pay\Pay;

class AliPay
{
    //要支付的金额 单位：元
    private $total_amount;

    // 订单标题
    private $subject;

    //交易订单号
    private $trade_no;


    public function __construct($total_amount,$trade_no,$subject)
    {
        $this->total_amount=$total_amount;
        $this->trade_no=$trade_no;
        $this->subject=$subject;
    }


    /**PC场景下单并支付
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @author: hbh
     * @Time: 2020/7/16   15:29
     */
    public function pc()
    {
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_amount' =>  $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(AliConfig::ali_pc_pay())->web($order);
        return $pay->send();
    }

    /**手机浏览器支付
     * @return \Symfony\Component\HttpFoundation\Response
     * @author: hbh
     * @Time: 2020/7/16   16:29
     */
    public function h5()
    {
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_amount' =>  $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(AliConfig::ali_h5_pay())->wap($order);
        return $pay->send();
    }

    /**APP 支付
     * @return \Symfony\Component\HttpFoundation\Response
     * @author: hbh
     * @Time: 2020/7/16   16:29
     */
    public function app()
    {
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_amount' =>  $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(AliConfig::ali_h5_pay())->app($order);
        return $pay->send();
    }

    /**获取支付宝支付二维码
     * @return String  二维码链接
     * @author: hbh
     * @Time: 2020/7/16   16:31
     */
    public function scan()
    {
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_amount' =>  $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(AliConfig::ali_h5_pay())->app($order);
        Log::info('【支付宝扫码支付获取支付二维吗】', $pay->all());
        if($pay->code=='10000'&&$pay->msg=='Success'){
            return $pay->code_url;
        }
        return '';
    }

    /**小程序支付
     * @return bool
     * @author: hbh
     * @Time: 2020/7/16   16:40
     */
    public function mini(){
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_amount' =>  $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(AliConfig::ali_h5_pay())->mini($order);
        if($pay->code=='10000'&&$pay->msg=='Success'){
            return true;
        }
        return false;
    }
}