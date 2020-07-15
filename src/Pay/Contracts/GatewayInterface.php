<?php
/**
 * @Desc:
 * @author: hbh
 * @Time: 2020/7/14   11:38
 */

namespace bydls\pays\Pay\Contracts;


use Symfony\Component\HttpFoundation\Response;
use bydls\Utils\Collection;

interface GatewayInterface
{
    /**支付
     * @param $endpoint
     * @param array $payload
     * @return mixed
     * @author: hbh
     * @Time: 2020/7/14   11:39
     */
    public function pay($endpoint, array $payload);
}