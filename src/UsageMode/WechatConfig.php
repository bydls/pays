<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/14   18:02
 */

namespace bydls\pays\UsageMode;


class WechatConfig
{


    public static function wx_config(){
        return [
            'appid' => 'wx66942e166283a4b9000', // APP APPID
            'app_id' => 'wx3a8ee318e6ee9a3d000', // 公众号 APPID
            'miniapp_id' => 'wx66942e166283a4b9000', // 小程序 APPID
            'mch_id' => '1558987901000',
            // 'key' => '15f8e9899767f5ddf787e0f82a3c9874',
            'key' => '6e56d25d77d49d573fcf8d0d0c7dbff4000',
            'notify_url' => 'https://'.env('API_DOMAIN').'/api/pay/wxscan/notify', //平台那边配置的
            'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
            'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
            'log' => [ // optional
                'file' => '../storage/logs/pay/wx/wechat.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'daily', // optional, 可选 daily.
                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
            'mode' => 'normal', // optional, dev/hk;当为 `hk` 时，为香港 gateway。
        ];
    }

    /**微信PC端的支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/6/29   15:27
     */
    public static function  wx_pc_pay(){
        $config=self::wx_config();
        $config['log']=[
            'file' => '../storage/logs/pay/wx/pay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }

    /**微信PC端的支付回调配置
     * @return array
     * @author: hbh
     * @Time: 2020/6/29   15:27
     */
    public static function  wx_pc_notify(){
        $config=self::wx_config();
        $config['log']=[
            'file' => '../storage/logs/pay/wx/notify.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }
}