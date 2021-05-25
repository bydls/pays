<?php

namespace bydls\pays\Pay\Events\Events;

class ApiRequesting extends Event
{
    /**
     * Endpoint.
     *
     * @var string
     */
    public $endpoint;

    /**
     * Payload.
     *
     * @var array
     */
    public $payload;

    /**Api 请求前抛出事件
     * ApiRequesting constructor.
     * @param string $driver
     * @param string $gateway
     * @param string $endpoint
     * @param array $payload
     */
    public function __construct(string $driver, string $gateway, string $endpoint, array $payload)
    {
        $this->endpoint = $endpoint;
        $this->payload = $payload;

        parent::__construct($driver, $gateway);
    }
}
