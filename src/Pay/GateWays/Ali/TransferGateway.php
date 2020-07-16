<?php

namespace bydls\pays\Pay\Gateways\Ali;

use bydls\pays\Pay\Gateways\Ali;
use bydls\pays\Pay\Events;
use bydls\pays\Pay\Exceptions;
use bydls\Utils\Collection;

class TransferGateway extends Gateway
{

    /**单笔转账 这个有点繁琐
     * @param string $endpoint
     * @param array $payload
     * @return Collection
     * @throws Exceptions\GatewayException
     * @throws Exceptions\InvalidArgumentException
     * @throws Exceptions\InvalidConfigException
     * @throws Exceptions\InvalidSignException
     * @author: hbh
     * @Time: 2020/7/15   11:22
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['method'] = 'alipay.fund.trans.uni.transfer';
        $biz_array = json_decode($payload['biz_content'], true);
        $biz_array['product_code']=$this->getProductCode();
        if ((Ali::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['biz_content'] = json_encode($biz_array);
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Ali', 'Transfer', $endpoint, $payload));

        return Support::requestApi($payload);
    }

    /**查询转载记录
     * @param String $order
     * @return array
     * @author: hbh
     * @Time: 2020/7/15   10:13
     */
    public function find(String $order): array
    {
        return [
            'method' => 'alipay.fund.trans.order.query',
            'biz_content' => json_encode(['out_biz_no' => $order]),
        ];
    }

    protected function getProductCode(): string
    {
        return 'QUICK_MSECURITY_PAY';
    }
}
