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
            'return_url' => '这是同步地址',
            'ali_public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDc+X1zlRoPcJAlDIYiXatC7xF0RwGazm2UO60PQq/iV8PBQp43E+Uh+sGbBysBKAnMrM5qhzdQjD7K1riT4wpKbYiXvI2egznzT/WShVKthpb6Cz4IaQcobOsm/iorHSDGQl41JIVdNqb3YcHJuwqf9m6gvv2j4hs7usPzFTioNwIDAQAB',
            'private_key' => 'MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCsPpDfKoEZIgbvrxvo4+qel14QvJ++jx6HqDaJVPqmiopyD28oiumVjy7lKTYVon1CybObcRY8etdPkxDD8sPMNlAjZbxPsE3FJaZbkqsWLy5cKYnc6I2uQPcxPDiy4Qfzzj9Y/yCMruVB+zv9x4PbpWxAM30LpASJQiPxrjhJmNhVE2JAl9QYgLGL+suEwQRCAfMUXws/CnzIJ5hm/264LDRwo1G9/ESWlMOq+uPbn6x1xbVmXXc0fwfvAGRm1btzvvRnq9EQn4zP62lMw+XePqWaWuVYmq4Of94bBd608XrGFryQFQMm0/wSWh7y1J6VmkRHAgjYH++r2K+jd8oHAgMBAAECggEAdlD7/vAZggQns5kbmNyGm63qhaCsMp/pHtJjs+5VxvS/qR4FA1p4na2fTvcCGdZmKOyYnZwO5DiVkim5iIBIEwzz+3y04CCBK31zSs/zCega9l/qPX7ZY+toYb+2StndNpJkXWPTHhA9lxdW3DqizsxREcDMkqR2ITr3qdo8/AAIs7YfNlCHAEJMvejdC+p59H/GfqRNYPBdVyF8tDp0FFQjJzBXzyMwEKUkvliBmJyalrf5dJ9NtiKXlRR0Makd1LXwdZArFDH5V7PPvl/r4Xbg3JKn6tH/e4uWQl7b8PhThuPBkPMz+s0qrvyMqmYhB1xGP5JX4/Qe4JZOvGEOoQKBgQDxpjF3Hjdd1Ggp56xn/r1akPiGl1UbnNiLAZg6L96G1SqxM18sG2Aw8BSjlLO+15UftkdBn64aJfDyrXWgmyiHSIbDUDlrRYz9ekBq/dCu3ZLEFdRsyKPJYVugsespKOYnH4kD7HoPugyowsbnByRuPb7+yyuK+RRG0c7pzBIDvwKBgQC2eTUhk1B/c3sM6YDP7b2pT4kgzBbvgFx3pDJhptRuMzs/APfccKcdAvJT0LwhRIPQ0hYCyoNzFwmnnrYZ2EVKG9gdjiJiIQZ+207laU3Bc9IvIbeoSZN0lZqxc6zRuxrqqEw7SmxlHJdA9B2tmBRA9l6CY2S3ixDIv+VQaK4ruQKBgQCAdI4a+i8Ia98rV4IojhvuRt41/cA6O3hI/IIFfGjYV/yn0d0nvy21FgqCoouiSUt2Xhkm41FTiRO6jUbYJ2K193Hb5YuAYKqAHDqeJWqHajWYktonMckPRffFRo7xhFJEPdlCArUoIwiTtWaybPOKvrwHCc2NxLZopyNM2TRCVQKBgFtf4t1HWb4Y4/uZSmnVEW6hZ3fG+40/aA55aMlBs4rjmL16DKSUvPpoKVUTZ8H4/1EoguEu7BhL5wfLEEFs7XWo79YNAObGxuvrglybGNbQ2uXDKqbZAUAWUnqeBGKaIWZ0lIf0Qsd3Q77A/8OYLjxBox4EC8FloCgHABv974nxAoGABayVI3eOwvbHcOpZw2r1C0aacmdXCiHpl3+kw0n+cy5X4QfRVQ+OFKZW1EiaUB/JLwX00M1GZqTAiGLtJZ8Z3q7Smmj3Z9tu7oPOEGEuTVOVFD8mMxCV8CtW37uDQgy1k9jWdOfUmAiJJeKW2geBYeqcmprD0vxq3fMy8S6biR0=',
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
    /**微信PC端的支付配置
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

    /**微信PC端的支付回调配置
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
}