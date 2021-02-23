<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/11   8:39
 */

namespace bydls\pays\Pay;

use bydls\pays\Pay\Contracts\GatewayApplicationInterface;
use bydls\Support\Str;
use bydls\pays\Pay\GateWays\Ali;
use bydls\pays\Pay\GateWays\Wechat;
use bydls\pays\Pay\Exceptions;
use bydls\pays\Pay\Listeners\Listeners;
use bydls\pays\Log\Logger;
use bydls\pays\Log\Log;
use bydls\pays\Pay\Config\Config;

/**
 * @method static Ali ali(array $config) 支付宝
 * @method static Wechat wechat(array $config) 微信
 */
class Pay
{

    protected $config;

    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->registerLogService();
        $this->registerEventService();
    }

    /**
     * @param $method
     * @param $params
     * @return GatewayApplicationInterface
     * @throws Exceptions\InvalidGatewayException
     */
    public static function __callStatic($method, $params)
    {
        $app = new self(...$params);
        return $app->create($method);
    }

    /**
     * @param $method
     * @return GatewayApplicationInterface
     * @throws Exceptions\InvalidGatewayException
     */
    protected function create($method)
    {
        $gateway = __NAMESPACE__ . '\\GateWays\\' . Str::studlyCap($method);

        if (class_exists($gateway)) {
            return new $gateway($this->config);
        }

        throw new Exceptions\InvalidGatewayException("Gateway [{$method}] Not Exists");
    }


    /**
     *Register log service
     */
    protected function registerLogService()
    {
        $config = $this->config->get('log');
        $config['identify'] = $config['identify'] ?? 'bydls.pay';

        $logger = new Logger();
        $logger->setConfig($config);

       Log::setInstance($logger);
    }

    /**
     * Register event service
     */
    protected function registerEventService()
    {
        Events::setDispatcher(Events::createDispatcher());

        Events::addSubscriber(new Listeners());
    }
}
