<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/14   13:52
 */

namespace bydls\pays\Pay\Listeners;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use bydls\pays\Log\Log;
use bydls\pays\Pay\Events;

class Listeners implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            Events\PayStarting::class => ['writePayStartingLog', 256],
            Events\PayStarted::class => ['writePayStartedLog', 256],
            Events\ApiRequesting::class => ['writeApiRequestingLog', 256],
            Events\ApiRequested::class => ['writeApiRequestedLog', 256],
            Events\SignFailed::class => ['writeSignFailedLog', 256],
            Events\NotifyReceived::class => ['writeNotifyReceivedLog', 256],
            Events\MethodCalled::class => ['writeMethodCalledLog', 256],
        ];
    }


    /**监听 支付开始事件
     * @param Events\PayStarting $event
     */
    public function writePayStartingLog(Events\PayStarting $event)
    {
        Log::debug("Starting To {$event->driver}", [$event->gateway, $event->params]);
    }

    /**支付结束后监听
     * @param Events\PayStarted $event
     * @author: hbh
     * @Time: 2020/7/14   14:15
     */
    public function writePayStartedLog(Events\PayStarted $event)
    {
        Log::info("{$event->driver} {$event->gateway} Has Started", [$event->endpoint, $event->payload]);
    }

    /**Api 请求前抛出事件 监听
     * @param Events\ApiRequesting $event
     * @author: hbh
     * @Time: 2020/7/14   14:16
     */
    public function writeApiRequestingLog(Events\ApiRequesting $event)
    {
        Log::debug("Requesting To {$event->driver} Api", [$event->endpoint, $event->payload]);
    }

    /**请求 API 之后的事件 监听
     * @param Events\ApiRequested $event
     * @author: hbh
     * @Time: 2020/7/14   14:17
     */
    public function writeApiRequestedLog(Events\ApiRequested $event)
    {
        Log::debug("Result Of {$event->driver} Api", $event->result);
    }

    /**签名异常 监听
     * @param Events\SignFailed $event
     * @author: hbh
     * @Time: 2020/7/14   14:18
     */
    public function writeSignFailedLog(Events\SignFailed $event)
    {
        Log::warning("{$event->driver} Sign Verify FAILED", $event->data);
    }

    /**第三方异步请求监听
     * @param Events\NotifyReceived $event
     * @author: hbh
     * @Time: 2020/7/14   14:18
     */
    public function writeNotifyReceivedLog(Events\NotifyReceived $event)
    {
        Log::info("Received {$event->driver} Request", $event->data);
    }

    /**调用 接口类型 监听
     * @param Events\MethodCalled $event
     * @author: hbh
     * @Time: 2020/7/14   14:19
     */
    public function writeMethodCalledLog(Events\MethodCalled $event)
    {
        Log::info("{$event->driver} {$event->gateway} Method Has Called", [$event->endpoint, $event->payload]);
    }
}
