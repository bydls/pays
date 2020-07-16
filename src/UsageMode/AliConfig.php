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
            'app_id' => '2021001178671284',
            'notify_url' => 'https://'.env('API_DOMAIN').'/api/pay/ali/notify',
            'return_url' => 'https://'.env('API_DOMAIN'),
            'ali_public_key' => '这是支付宝的公钥',
            'private_key' => '这是自己的项目私钥',
            'log' => [ // optional
                'file' => '../storage/logs/pay/ali/ali.log',
                'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
                'type' => 'daily', // optional, 可选 daily.
                'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
            ],
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
    public static function  ali_pc_pay(){
        $config=self::ali_config();
        $config['log']=[
            'file' => '../storage/logs/pay/ali_pc/pay.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }

    /**支付宝PC端的支付回调配置
     * @return array
     * @author: hbh
     * @Time: 2020/6/29   15:27
     */
    public static function  ali_pc_notify(){
        $config=self::ali_config();
        $config['log']=[
            'file' => '../storage/logs/pay/ali_pc/notify.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        return $config;
    }

    /**支付宝H5支付配置
     * @return array
     * @author: hbh
     * @Time: 2020/7/16   16:27
     */
    public static function  ali_h5_pay(){
        $config=self::ali_config();
        $config['log']=[
            'file' => '../storage/logs/pay/ali_h5/notify.log',
            'level' => 'info', // 建议生产环境等级调整为 info，开发环境为 debug
            'type' => 'daily', // optional, 可选 daily.
            'max_file' => 31, // optional, 当 type 为 daily 时有效，默认 30 天
        ];
        $config['notify_url']='异步回调地址';
        $config['return_url']='同步回调地址';
        return $config;
    }
}