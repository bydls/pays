<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/14   18:02
 */

namespace bydls\pays\UsageMode;


class WechatConfig
{


    public static function wx_config()
    {
        return [
            'appid' => WECHAT_APPID, // APP APPID
            'app_id' => WECHAT_APP_ID, // 公众号 APPID
            'miniapp_id' => WECHAT_MINIAPP_ID, // 小程序 APPID
            'mch_id' => WECHAT_MCH_ID,
            'key' => WECHAT_KEY,
            'notify_url' => PAY_NOTIF_URL, //异步回调地址
            'cert_client' => './cert/apiclient_cert.pem', // optional，退款等情况时用到
            'cert_key' => './cert/apiclient_key.pem',// optional，退款等情况时用到
//            'log' => [ // optional
//                'file' => '../storage/logs/pay/wx/wechat.log',
//                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
//                'type' => 'daily', // optional, 可选 daily.
//                'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
//            ],
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
    public static function wx_scan_pay()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_scan/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = PAY_NOTIF_URL;
        return $config;
    }

    /**微信PC端的支付回调配置
     * @return array
     * @author: hbh
     * @Time: 2020/6/29   15:27
     */
    public static function wx_notify()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_notify/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }

    /**微信h5端的支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/16   17:32
     */
    public static function wx_h5_pay()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_h5/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = PAY_NOTIF_URL;
        return $config;
    }

    /**微信 APP的支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/17   15:17
     */
    public static function wx_app_pay()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_app/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = PAY_NOTIF_URL;
        return $config;
    }

    /**小程序支付
     * @return array
     * @author: hbh
     * @Time: 2020/7/17   15:19
     */
    public static function wx_mini_pay()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_mini/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = PAY_NOTIF_URL;
        return $config;
    }

    /**微信公众号支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/17   15:20
     */
    public static function wx_mp_pay()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_mp/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = PAY_NOTIF_URL;
        return $config;
    }

    /**微信红包配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/17   10:25
     */
    public static function wx_redpack_pay()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_redpack/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }

    /**微信退款配置
     * @return mixed
     * @author: hbh
     * @Time: 2020/7/17   10:43
     */
    public static function wx_refund()
    {
        $config = self::wx_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/wx_refund/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }
}