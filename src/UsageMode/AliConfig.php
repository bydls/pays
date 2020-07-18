<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/15   14:53
 */

namespace bydls\pays\UsageMode;



class AliConfig
{
    public static function ali_config()
    {
        return [
            'app_id' => ALI_APP_ID,
            'notify_url' => 'https://' . API_DOMAIN. '/api/pay/ali/notify',
            'return_url' => 'https://' . ALI_RETURN_URL,
            'ali_public_key' =>ALI_PUBLIC_KEY,// '这是支付宝的公钥'
            'private_key' => ALI_PRIVATE_KEY,//这是自己的项目私钥',
//            'log' => [ // optional
//                'file' => '../storage/logs/pay/ali/ali.log',
//                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
//                'type' => 'daily', // optional, 可选 daily.
//                'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
//            ],
            'http' => [ // optional
                'timeout' => 5.0,
                'connect_timeout' => 5.0,
                // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
            ],
            'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
        ];
    }

    /**支付宝PC端的支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/6/29   15:27
     */
    public static function ali_pc_pay()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_pc/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }

    /**支付宝支付回调配置
     * @return array
     * @author: hbh
     * @Time: 2020/6/29   15:27
     */
    public static function ali_notify()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_notify/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = '异步回调地址';
        $config['return_url'] = '同步回调地址';
        return $config;
    }

    /**支付宝H5支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/16   16:27
     */
    public static function ali_h5_pay()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_h5/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = '异步回调地址';
        $config['return_url'] = '同步回调地址';
        return $config;
    }

    /**支付宝 APP支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/17   15:15
     */
    public static function ali_app_pay()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_app/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = '异步回调地址';
        return $config;
    }
    /**支付宝小程序支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/16   16:27
     */
    public static function ali_mini_pay()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_mini/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = '异步回调地址';
        return $config;
    }

    /**支付宝扫码配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/17   15:12
     */
    public static function ali_scan_pay()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_scan/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url'] = '异步回调地址';
        return $config;
    }

    /**支付宝退款配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/16   16:27
     */
    public static function ali_refund()
    {
        $config = self::ali_config();
        $config['log'] = [
            'file' => '../storage/logs/pay/ali_refund/.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }
}