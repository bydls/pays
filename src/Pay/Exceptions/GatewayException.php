<?php

namespace bydls\pays\Pay\Exceptions;

class GatewayException extends Exception
{

    /**网关信息异常
     * GatewayException constructor.
     * @param String $message
     * @param array $raw
     */
    public function __construct(String $message,$raw = [])
    {
        parent::__construct('ERROR_GATEWAY: '.$message, self::ERROR_GATEWAY,$raw = []);
    }
}
