#此文件夹只是使用demo,跟整体包功能无关，请根据自己的实际场景自由组合使用

以 支付宝的使用示例：

AliConfig：请求的配置参数

AliNotify:对回调数据的验证

AliOrder: 只需要一个订单号为参数的订单处理，如 交易查询、关闭交易、撤单等

AliPay:需要三个参数：$total_amount（支付金额）,$trade_no（订单号）,$subject（标题） 进行各种支付

AliRefund:需要三个参数：$refund_amount(退款金额),$out_out_trade_no（订单号）,$out_request_no（同一笔交易多次退款需要保证唯一） 进行各种支付