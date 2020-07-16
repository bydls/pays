<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   10:14
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;
use bydls\pays\Log\Log;

class AliNotify
{

    public function aliPcNotify()
    {
        $pay = Pay::Ali(AliConfig::ali_pc_notify());
        $data=null;
        try{
            $data = $pay->verify(); // 获取验签后的结果
            Log::info('【支付宝回调数据】', $data->all());

            //根据返回的结果处理自己的业务



           // $pay->success();  //更新完自己的数据后 返回成功，让支付宝不用再请求了
        } catch (\Exception $e) {
            // $e->getMessage();
        }
        //返回
        return $data;
    }
}