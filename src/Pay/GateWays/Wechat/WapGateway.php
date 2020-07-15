<?php

namespace bydls\pays\Pay\Gateways\Wechat;

use Symfony\Component\HttpFoundation\RedirectResponse;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Events\PayStarted;
use bydls\pays\Pay\Exceptions\GatewayException;
use bydls\pays\Pay\Exceptions\InvalidArgumentException;
use bydls\pays\Pay\Exceptions\InvalidSignException;

class WapGateway extends Gateway
{

    /**手机web支付  H5
     * @param string $endpoint
     * @param array $payload
     * @return RedirectResponse
     * @throws GatewayException
     * @throws InvalidArgumentException
     * @throws InvalidSignException
     * @throws \bydls\pays\Pay\Exceptions\ResultException
     * @author: hbh
     * @Time: 2020/7/14   13:48
     */
    public function pay($endpoint, array $payload): RedirectResponse
    {
        $payload['trade_type'] = $this->getTradeType();

        Events::dispatch(new PayStarted('Wechat', 'Wap', $endpoint, $payload));

        $mweb_url = $this->preOrder($payload)->get('mweb_url');

        $url = is_null(Support::getInstance()->return_url) ? $mweb_url : $mweb_url.
                        '&redirect_url='.urlencode(Support::getInstance()->return_url);

        return new RedirectResponse($url);
    }

    /**
     * Get trade type config.
     */
    protected function getTradeType(): string
    {
        return 'MWEB';
    }
}
