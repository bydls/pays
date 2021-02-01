<?php

namespace bydls\pays\Pay\Gateways\Ali;

use bydls\pays\Pay\Contracts\GatewayInterface;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\Support\Collection;

abstract class Gateway implements GatewayInterface
{
    /**
     * Mode.
     *
     * @var string
     */
    protected $mode;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->mode = Support::getInstance()->mode;
    }

    /**
     * Pay an order.
     *
     * @param string $endpoint
     *
     * @return Collection
     */
    abstract public function pay($endpoint, array $payload);
}
