<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/11   8:39
 */

namespace bydls\pays\Pay;

use bydls\pays\Pay\Contracts\GatewayApplicationInterface;
use bydls\Utils\Str;
use bydls\pays\Pay\Gateways\Ali;
use bydls\pays\Pay\Gateways\Wechat;
use bydls\pays\Pay\Exceptions;
use bydls\pays\Pay\Listeners\LogSubscriber;
use bydls\pays\Log\Logger;


/**
 * @method static Ali ali(array $config) 支付宝
 * @method static Wechat wechat(array $config) 微信
 */
class Pay
{

    protected $config;

    public function __construct(array $config)
    {
        $this->config = $config;
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
        $gateway = __NAMESPACE__ . '\\Gateways\\' . Str::studlyCap($method);

        if (class_exists($gateway)) {
            return self::make($gateway);
        }

        throw new Exceptions\InvalidGatewayException("Gateway [{$method}] Not Exists");
    }

    /**
     * @param $gateway
     * @return GatewayApplicationInterface
     * @throws Exceptions\InvalidGatewayException
     */
    protected function make($gateway): GatewayApplicationInterface
    {
        $app = new $gateway($this->config);

        if ($app instanceof GatewayApplicationInterface) {
            return $app;
        }

        throw new Exceptions\InvalidGatewayException("Gateway [{$gateway}] Must Be An Instance Of GatewayApplicationInterface");
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

        Logger::setLogger($logger);
    }

    /**
     * Register event service
     */
    protected function registerEventService()
    {
        Events::setDispatcher(Events::createDispatcher());

        Events::addSubscriber(new LogSubscriber());
    }
}