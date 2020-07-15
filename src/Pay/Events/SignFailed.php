<?php

namespace bydls\pays\Pay\Events;

class SignFailed extends Event
{
    /**
     * Received data.
     *
     * @var array
     */
    public $data;

    /**签名异常
     * SignFailed constructor.
     * @param string $driver
     * @param string $gateway
     * @param array $data
     */
    public function __construct(string $driver, string $gateway, array $data)
    {
        $this->data = $data;

        parent::__construct($driver, $gateway);
    }
}
