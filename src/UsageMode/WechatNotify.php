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
            Log::info('【微信支付回调数据】', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }
        //返回
        return $data;
    }
}