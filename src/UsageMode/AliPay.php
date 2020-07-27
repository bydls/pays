<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   10:10
 */

namespace bydls\pays\UsageMode;

use bydls\pays\Pay\Pay;
use bydls\pays\Config;

class AliPay
{
    //要支付的金额 单位：元
    private $total_amount;

    // 订单标题
    private $subject;

    //交易订单号
    private $trade_no;

    //同步回调地址 默认走配置
    private $return_url;

    public function __construct($trade_no, $total_amount, $subject,$return_url)
    {
        $this->total_amount = $total_amount;
        $this->trade_no = $trade_no;
        $this->subject = $subject;
        $this->return_url = $return_url;
    }


    /**PC场景下单并支付
     * @return string|\Symfony\Component\HttpFoundation\Response
     * @author: hbh
     * @Time: 2020/7/16   15:29
     */
    public function web()
    {
        $order = [
            'out_trade_no' => $this->trade_no,
            'total_amount' => $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $config=Config::Ali()->ali_pc_pay();
        if($this->return_url) $config['return_url']=$this->return_url;
        $pay = Pay::Ali(Config::Ali()->ali_pc_pay())->web($order);
        return $pay->send();
    }

    /**手机浏览器支付
     * @return \Symfony\Component\HttpFoundation\Response
     * @author: hbh
     * @Time: 2020/7/16   16:29
     */
    public function wap()
    {
        $order = [
            'out_trade_no' => $this->trade_no,
            'total_amount' => $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $config=Config::Ali()->ali_pc_pay();
        if($this->return_url) $config['return_url']=$this->return_url;
        $pay = Pay::Ali(Config::Ali()->ali_h5_pay())->wap($order);
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
            'out_trade_no' => $this->trade_no,
            'total_amount' => $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(Config::Ali()->ali_app_pay())->app($order);
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
            'out_trade_no' => $this->trade_no,
            'total_amount' => $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(Config::Ali()->ali_scan_pay())->scan($order);
        if ($pay->code == '10000') {
            return $pay->code_url;
        }
        return '';
    }

    /**小程序支付
     * @return \bydls\Utils\Collection|mixed|null
     * @author: hbh
     * @Time: 2020/7/17   13:55
     */
    public function mini()
    {
        $order = [
            'out_trade_no' => $this->trade_no,
            'total_amount' => $this->total_amount, // **单位：元
            'subject' => $this->subject,
        ];
        $pay = Pay::Ali(Config::Ali()->ali_mini_pay())->mini($order);
        if ($pay->code == '10000') {
            return $pay;
        }
        return $pay->msg ?? null;
    }
}