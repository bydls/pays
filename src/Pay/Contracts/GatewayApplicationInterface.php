<?php

namespace bydls\pays\Pay\Contracts;

use Symfony\Component\HttpFoundation\Response;


interface GatewayApplicationInterface
{
    /**支付
     * @param String $gateway
     * @param array $params
     * @return Collection|Response
     * @author: hbh
     * @Time: 2020/7/11   14:29
     */
    public function pay(String $gateway,array $params);

    /**订单查询
     * @param String $order //订单号
     * @param string $type
     * @return Collection|Response
     * @author: hbh
     * @Time: 2020/7/11   14:31
     */
    public function find(String $order, string $type);

    /**退款
     * @param array $order
     * @return  Collection|Response
     * @author: hbh
     * @Time: 2020/7/13   17:10
     */
    public function refund(array $order);

    /**撤销订单接口
     * @param String $order
     * @return mixed
     * @author: hbh
     * @Time: 2020/7/13   17:11
     */
    public function cancel(String $order);

    /**关闭交易
     * @param String $order
     * @return mixed
     * @author: hbh
     * @Time: 2020/7/13   17:16
     */
    public function close(String $order);

    /**验证返回的结果
     * @param  $content //第三方返回的
     * @param bool $refund
     * @return mixed
     * @author: hbh
     * @Time: 2020/7/13   17:21
     */
    public function verify($content, bool $refund);

    /**成功返回
     * @return mixed
     * @author: hbh
     * @Time: 2020/7/13   18:51
     */
    public function success();
}
