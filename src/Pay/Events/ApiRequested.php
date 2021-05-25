<?php

namespace bydls\pays\Pay\Events\Events;

class ApiRequested extends Event
{
    /**
     * Endpoint.
     *
     * @var string
     */
    public $endpoint;

    /**
     * Result.
     *
     * @var array
     */
    public $result;

    /**请求 API 之后的事件
     * ApiRequested constructor.
     * @param string $driver    //支付结构
     * @param string $gateway   //网关
     * @param string $endpoint //支付的 url endpoint
     * @param array $result     //返回的数据
     */
    public function __construct(string $driver, string $gateway, string $endpoint, array $result)
    {
        $this->endpoint = $endpoint;
        $this->result = $result;

        parent::__construct($driver, $gateway);
    }
}
