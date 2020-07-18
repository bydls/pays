<?php
/**
 * @Desc:支付类方法
 * @author: hbh
 * @Time: 2020/7/17   11:49
 */

namespace bydls\pays;


use bydls\Utils\Collection;
use bydls\pays\UsageMode\AliPay;
use bydls\pays\UsageMode\AliRefund;
use bydls\pays\UsageMode\AliOrder;
use bydls\pays\UsageMode\WechatPay;
use bydls\pays\UsageMode\WechatRefund;
use bydls\pays\UsageMode\WechatOrder;
use Symfony\Component\HttpFoundation\Response;

class Pay
{
    /**PC场景下单并支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return Response
     * @author: hbh
     * @Time: 2020/7/17   13:46
     */
    public static function ali_web_pay(String $out_trade_no, float $total_amount, String $subject): Response
    {
        $pay = new AliPay($out_trade_no, $total_amount, $subject);
        return $pay->web();
    }

    /**手机浏览器支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return Response
     * @author: hbh
     * @Time: 2020/7/17   13:47
     */
    public static function ali_h5_pay(String $out_trade_no, float $total_amount, String $subject): Response
    {
        $pay = new AliPay($out_trade_no, $total_amount, $subject);
        return $pay->wap();
    }

    /**APP 内部支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return Response
     * @author: hbh
     * @Time: 2020/7/17   13:48
     */
    public static function ali_app_pay(String $out_trade_no, float $total_amount, String $subject): Response
    {
        $pay = new AliPay($out_trade_no, $total_amount, $subject);
        return $pay->app();
    }

    /**获取支付宝支付二维码链接
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return String
     * @author: hbh
     * @Time: 2020/7/17   13:49
     */
    public static function ali_scan_pay(String $out_trade_no, float $total_amount, String $subject): String
    {
        $pay = new AliPay($out_trade_no, $total_amount, $subject);
        return $pay->scan();
    }

    /**小程序支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return Collection 支付结果  集合
     * @author: hbh
     * @Time: 2020/7/17   13:53
     */
    public static function ali_mini_pay(String $out_trade_no, float $total_amount, String $subject): Collection
    {
        $pay = new AliPay($out_trade_no, $total_amount, $subject);
        return $pay->mini();
    }


    /**退款
     * @param String $out_out_trade_no 原订单号
     * @param float $refund_amount 要退款的金额不能大于原订单金额
     * @param String $out_request_no 退款订单号 同一笔交易多次退款需要保证唯一
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:16
     */
    public static function ali_refund(String $out_trade_no, float $refund_amount, String $out_request_no): Collection
    {
        $pay = new AliRefund($out_trade_no, $refund_amount, $out_request_no);
        return $pay->refund();
    }

    /**订单查询
     * @param String $out_trade_no 要查询的订单号
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:23
     */
    public static function ali_find(String $out_trade_no):Collection
    {
        $pay = new AliOrder($out_trade_no);
        return $pay->find();
    }


    /**退款订单查询
     * @param String $out_trade_no  原订单号
     * @param String $out_request_no    退款订单号
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:28
     */
    public static function ali_find_refund(String $out_trade_no,String $out_request_no):Collection
    {
        $pay = new AliRefund($out_trade_no, 0, $out_request_no);
        return $pay->findRefund();
    }

    /**撤单
     * @param String $out_trade_no
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:31
     */
    public static function ali_cancel(String $out_trade_no):Collection
    {
        $pay = new AliOrder($out_trade_no);
        return $pay->cancel();
    }

    /**关闭订单
     * @param String $out_trade_no
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:32
     */
    public static function ali_close(String $out_trade_no):Collection
    {
        $pay = new AliOrder($out_trade_no);
        return $pay->close();
    }


    /**手机浏览器 微信支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return Response
     * @author: hbh
     * @Time: 2020/7/17   13:47
     */
    public static function wx_h5_pay(String $out_trade_no, float $total_amount, String $subject): Response
    {
        $pay = new WechatPay($out_trade_no, $total_amount, $subject);
        return $pay->wap();
    }

    /**APP 内部 微信支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return Response
     * @author: hbh
     * @Time: 2020/7/17   13:48
     */
    public static function wx_app_pay(String $out_trade_no, float $total_amount, String $subject): Response
    {
        $pay = new WechatPay($out_trade_no, $total_amount, $subject);
        return $pay->app();
    }

    /**获取微信付二维码链接
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @return String
     * @author: hbh
     * @Time: 2020/7/17   13:49
     */
    public static function wx_scan_pay(String $out_trade_no, float $total_amount, String $subject): String
    {
        $pay = new WechatPay($out_trade_no, $total_amount, $subject);
        return $pay->scan();
    }

    /**微信小程序支付
     * @param String $out_trade_no 交易订单号
     * @param float $total_amount 要支付的金额 单位：元
     * @param String $subject 订单标题
     * @param String $openid
     * @return Collection 支付结果  集合
     * @author: hbh
     * @Time: 2020/7/17   13:53
     */
    public static function wx_mini_pay(String $out_trade_no, float $total_amount, String $subject,String $openid): Collection
    {
        $pay = new WechatPay($out_trade_no, $total_amount, $subject);
        return $pay->mini($openid);
    }


    /**微信 退款
     * @param String $out_trade_no  原订单号
     * @param float $refund_amount  要退款的金额不能大于原订单金额
     * @param String $out_request_no 退款订单号 同一笔交易多次退款需要保证唯一
     * @param float $total_fee  原订单总金额
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:41
     */
    public static function wx_refund(String $out_trade_no, float $refund_amount, String $out_request_no,float $total_fee): Collection
    {
        $pay = new WechatRefund($out_trade_no, $refund_amount, $out_request_no,$total_fee);
        return $pay->refund();
    }

    /**微信订单查询
     * @param String $out_trade_no 要查询的订单号
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:23
     */
    public static function wx_find(String $out_trade_no):Collection
    {
        $pay = new WechatOrder($out_trade_no);
        return $pay->find();
    }


    /**退款订单查询
     * @param String $out_trade_no  原订单号
     * @param String $out_request_no    退款订单号
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:28
     */
    public static function wx_find_refund(String $out_trade_no,String $out_request_no):Collection
    {
        $pay = new WechatRefund($out_trade_no, 0, $out_request_no,0);
        return $pay->findRefund();
    }

    /**撤单
     * @param String $out_trade_no
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:31
     */
    public static function wx_cancel(String $out_trade_no):Collection
    {
        $pay = new WechatOrder($out_trade_no);
        return $pay->cancel();
    }

    /**关闭订单
     * @param String $out_trade_no
     * @return Collection
     * @author: hbh
     * @Time: 2020/7/17   14:32
     */
    public static function wx_close(String $out_trade_no):Collection
    {
        $pay = new WechatOrder($out_trade_no);
        return $pay->close();
    }
}