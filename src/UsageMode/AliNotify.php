<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/16   10:14
 */

namespace bydls\pays\UsageMode;


use bydls\pays\Pay\Pay;

class AliNotify
{

    public function wxPcNotify()
    {
        $pay = Pay::Ali(AliConfig::ali_pc_notify());
        $data=null;
        try{
            $data = $pay->verify(); // 是的，验签就这么简单！
        } catch (\Exception $e) {
            // $e->getMessage();
        }
        //返回
        return $data;
    }
}