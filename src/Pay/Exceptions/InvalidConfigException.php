<?php

namespace bydls\pays\Pay\Exceptions;

class InvalidConfigException extends Exception
{
    /**配置参数无效
     * InvalidConfigException constructor.
     * @param String $message
     */
    public function __construct(String $message)
    {
        parent::__construct('INVALID_CONFIG: '.$message, self::INVALID_CONFIG);
    }
}
