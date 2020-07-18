<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/18   16:10
 */

namespace bydls\pays;


use bydls\pays\UsageMode\AliConfig;
use bydls\pays\UsageMode\WechatConfig;
use bydls\Utils\Str;

/**
 * @method static AliConfig Ali() 支付宝
 * @method static WechatConfig Wechat() 微信
 */
class Config
{

    public static function __callStatic($method)
    {
        $config = __NAMESPACE__ . '\\config\\' . Str::studlyCap($method).'Config';

        if (class_exists($config)) {
            return new ($config);
        }

        return new ('bydls\\pays\\UsageMode\\'.Str::studlyCap($method).'Config');

    }

}