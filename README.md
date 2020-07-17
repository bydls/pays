支付类包 现只支持 支付宝和微信

安装:

composer require bydls/pays

配置文件：

     src/AliConfig.php  支付宝的各种配置
     
     src/WechatConfig.php  微信的各种配置


目前支持的方法：src/Pay.php 文件中的 

        调用：
        
        use bydls/pays;
        
        支付宝web端支付：pays::ali_web_pay('订单号', 0.01, '充值');
             
