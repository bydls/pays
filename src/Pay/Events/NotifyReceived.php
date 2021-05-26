<?php

namespace bydls\pays\Pay\Events;

class NotifyReceived extends Event
{
    /**
     * Received data.
     *
     * @var array
     */
    public $data;

    /**第三方异步请求
     * NotifyReceived constructor.
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
