<?php
/**
 * @Desc: 支付类方法 需要 $total_fee,$trade_no,$body 三个参数
 * @author: hbh
 * @Time: 2020/7/14   17:43
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Log\Log;
use bydls\pays\Pay\Pay;
use bydls\Utils\Str;


class WechatPay
{
    //要支付的金额
    private $total_fee;

    private $body='充值';

    //交易订单号
    private $trade_no;


    public function __construct($total_fee,$trade_no,$body)
    {
        $this->total_fee=$total_fee*100;
        $this->trade_no=$trade_no;
        $this->body=$body;
    }



    /**获取微信支付二维码
     * @return String  二维码链接
     * @author: hbh
     * @Time: 2020/6/30   9:01
     */
    public function scan()
    {
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_fee' =>  $this->total_fee, // **单位：分**
            'body' => $this->body,
        ];
        $pay = Pay::wechat(WechatConfig::wx_pc_pay())->scan($order);
        Log::info('【微信扫码支付获取支付二维吗】', $pay->all());
        if($pay->return_code=='SUCCESS'&&$pay->return_msg=='OK'){
            return $pay->code_url;
        }
        return '';
    }

    /**公众号支付
     * @return \bydls\Utils\Collection|null
     * @author: hbh
     * @Time: 2020/7/16   16:58
     */

    /**公众号支付
     * @param $openid
     * @return \bydls\Utils\Collection|null
     * @author: hbh
     * @Time: 2020/7/16   17:14
     */
    public function  mp($openid){
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_fee' =>  $this->total_fee, // **单位：分**
            'body' => $this->body,
            'openid' => $openid,
        ];
        $pay = Pay::wechat(WechatConfig::wx_h5_pay())->mp($order);
        if($pay->return_code=='SUCCESS'&&$pay->return_msg=='OK'){
            return $pay;
        }
        return null;
    }

    /**小程序支付
     * @param $openid
     * @return \bydls\Utils\Collection|null
     * @author: hbh
     * @Time: 2020/7/16   17:18
     */
    public function  mini($openid){
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_fee' =>  $this->total_fee, // **单位：分**
            'body' => $this->body,
            'openid' => $openid,
        ];
        $pay = Pay::wechat(WechatConfig::wx_h5_pay())->mini($order);
        if($pay->return_code=='SUCCESS'&&$pay->return_msg=='OK'){
            return $pay;
        }
        return null;
    }
    /**手机浏览器支付
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @author: hbh
     * @Time: 2020/7/16   17:16
     */
    public function  h5(){
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_fee' =>  $this->total_fee, // **单位：分**
            'body' => $this->body,
        ];
        $pay = Pay::wechat(WechatConfig::wx_h5_pay())->wap($order);

        return $pay->send();
    }

    /**APP 支付
     * @return \Symfony\Component\HttpFoundation\Response
     * @author: hbh
     * @Time: 2020/7/16   17:17
     */
    public function  app(){
        $order = [
            'out_trade_no' =>$this->trade_no,
            'total_fee' =>  $this->total_fee, // **单位：分**
            'body' => $this->body,
        ];
        $pay = Pay::wechat(WechatConfig::wx_h5_pay())->app($order);

        return $pay->send();
    }
}