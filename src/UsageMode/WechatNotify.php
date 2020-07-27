<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/6/29   15:41
 */

namespace bydls\pays\UsageMode;

use bydls\pays\Pay\Pay;
use bydls\pays\Config;

class WechatNotify
{

    public function wxPcNotify()
    {
        $pay = Pay::wechat(Config::Wechat()->wx_notify());
        $data = null;
        try {
            $data = $pay->verify(); // 是的，验签就这么简单！
        } catch (\Exception $e) {
            // $e->getMessage();
        }
        //返回
        return $data;
    }

    /**微信支付成功
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \bydls\pays\Pay\Exceptions\InvalidArgumentException
     * @author: hbh
     * @Time: 2020/7/27   8:49
     */
    public function success()
    {
        return Pay::wechat(Config::Wechat()->wx_config())->success();
    }
}