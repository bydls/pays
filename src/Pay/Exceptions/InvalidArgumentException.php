<?php

namespace bydls\pays\Pay\Exceptions;

class InvalidArgumentException extends Exception
{
    /**参数无效
     * InvalidArgumentException constructor.
     * @param String $message
     */
    public function __construct(String $message)
    {
        parent::__construct('INVALID_ARGUMENT: '.$message, self::INVALID_ARGUMENT);
    }
}
