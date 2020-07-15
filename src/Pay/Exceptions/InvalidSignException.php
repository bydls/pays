<?php

namespace bydls\pays\Pay\Exceptions;

class InvalidSignException extends Exception
{
    /**签名异常
     * InvalidSignException constructor.
     * @param String $message
     */
    public function __construct(String $message,$raw = [])
    {
        parent::__construct('INVALID_SIGN: '.$message, self::INVALID_SIGN,$raw = []);
    }
}
