<?php
/**
 * @Desc:红包
 * @author: hbh
 * @Time: 2020/7/16   17:39
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;
use bydls\pays\Config;

class WechatRedpack
{

    /**
     * @var //具体参数解释详见微信官方文档： https://pay.weixin.qq.com/wiki/doc/api/tools/cash_coupon.php?chapter=13_4&index=3
     */

    //交易订单号
    private $mch_billno;
    //付款金额，单位分
    private $total_amount;
    //商户名称	红包发送者名称
    private $send_name;
    //接受者用户openid
    private $re_openid;
    //红包祝福语
    private $wishing;
    //活动名称
    private $act_name;
    //活动名称
    private $remark;


    public function __construct($mch_billno, $total_amount, $send_name, $re_openid, $wishing, $act_name, $remark, $total_num)
    {
        $this->mch_billno = $mch_billno;
        $this->total_amount = $total_amount * 100;
        $this->send_name = $send_name;
        $this->re_openid = $re_openid;
        $this->wishing = $wishing;
        $this->act_name = $act_name;
        $this->remark = $remark;
        $this->total_num = $total_num;

    }

    /**商户红包
     * @return \bydls\Support\Collection
     * @author: hbh
     * @Time: 2020/7/17   10:36
     */
    public function redpack()
    {
        $order = [
            'mch_billno' => $this->mch_billno,
            'send_name' => $this->send_name,
            'total_amount' => $this->total_amount,
            're_openid' => $this->re_openid,
            'total_num' => $this->total_num,
            'wishing' => $this->wishing,
            'act_name' => $this->act_name,
            'remark' => $this->total_num,
        ];
        $result = Pay::wechat(Config::Wechat()->wx_redpack_pay())->redpack($order);
        return $result;
    }

    /**裂变红包
     * @return \bydls\Support\Collection
     * @author: hbh
     * @Time: 2020/7/17   10:36
     */
    public function groupRedpack()
    {
        $order = [
            'mch_billno' => $this->mch_billno,
            'send_name' => $this->send_name,
            'total_amount' => $this->total_amount,
            're_openid' => $this->re_openid,
            'total_num' => $this->total_num,
            'wishing' => $this->wishing,
            'act_name' => $this->act_name,
            'remark' => $this->total_num,
        ];
        $result = Pay::wechat(Config::Wechat()->wx_redpack_pay())->groupRedpack($order);
        return $result;
    }
}