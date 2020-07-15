<?php

namespace bydls\pays\Pay\Exceptions;

class InvalidGatewayException extends Exception
{
    /**网关参数无效
     * InvalidGatewayException constructor.
     * @param String $message
     */
    public function __construct(String $message)
    {
        parent::__construct('INVALID_GATEWAY: '.$message, self::INVALID_GATEWAY);
    }
}
