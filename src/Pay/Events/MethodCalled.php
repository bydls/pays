<?php

namespace bydls\pays\Pay\Events\Events;

class MethodCalled extends Event
{
    /**
     * endpoint.
     *
     * @var string
     */
    public $endpoint;

    /**
     * payload.
     *
     * @var array
     */
    public $payload;

    /**调用 接口类型时触发,（例如，查询订单，退款，取消订单时抛出）
     * @param string $driver    //支付机构
     * @param string $gateway   //调用方法
     * @param string $endpoint  //支付的 url endpoint
     * @param array $payload    //数据参数
     */
    public function __construct(string $driver, string $gateway, string $endpoint, array $payload = [])
    {
        $this->endpoint = $endpoint;
        $this->payload = $payload;

        parent::__construct($driver, $gateway);
    }
}
