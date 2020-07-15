<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/15   14:53
 */

namespace bydls\pays\UsageMode;


class AliConifg
{
    public static function ali_config()
    {
        return [
            'appid' => 'wxb3fxxxxxxxxxxx', // APP APPID
            'app_id' => 'wxb3fxxxxxxxxxxx', // 公众号 APPID
            'miniapp_id' => 'wxb3fxxxxxxxxxxx', // 小程序 APPID
            'mch_id' => '145776xxxx',
            'key' => 'mF2suE9sU6Mk1CxxxxIxxxxx',
            'notify_url' => 'http://yanda.net.cn',
            'cert_client' => './cert/apiclient_cert.pem', // optional, 退款，红包等情况时需要用到
            'cert_key' => './cert/apiclient_key.pem',// optional, 退款，红包等情况时需要用到
            'log' => [ // optional
                'file' => './logs/wechat.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'single', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
            // 'mode' => 'dev',
        ];;
    }
}