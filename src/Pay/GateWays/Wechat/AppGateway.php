<?php

namespace bydls\pays\Pay\GateWays\Wechat;

use bydls\Utils\CodeUtil;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Events\PayStarted;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;
use bydls\pays\Pay\GateWays\Wechat;

class AppGateway extends Gateway
{
    /**
     * App支付
     *@param string $endpoint
     * @param array $payload
     * @return Response
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   11:44
     */
    public function pay($endpoint, array $payload): Response
    {
        $payload['appid'] = Support::getInstance()->appid;
        $payload['trade_type'] = $this->getTradeType();

        if (Wechat::MODE_SERVICE === $this->mode) {
            $payload['sub_appid'] = Support::getInstance()->sub_appid;
        }

        $pay_request = [
            'appid' => Wechat::MODE_SERVICE === $this->mode ? $payload['sub_appid'] : $payload['appid'],
            'partnerid' => Wechat::MODE_SERVICE === $this->mode ? $payload['sub_mch_id'] : $payload['mch_id'],
            'prepayid' => $this->preOrder($payload)->get('prepay_id'),
            'timestamp' => strval(time()),
            'noncestr' => CodeUtil::random(),
            'package' => 'Sign=WXPay',
        ];
        $pay_request['sign'] = Support::generateSign($pay_request);

        Events::dispatch(new PayStarted('Wechat', 'App', $endpoint, $pay_request));

        return new JsonResponse($pay_request);
    }

    /**
     * Get trade type config.
     *
     */
    protected function getTradeType(): string
    {
        return 'APP';
    }
}
