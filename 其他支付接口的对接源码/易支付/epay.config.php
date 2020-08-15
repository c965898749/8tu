<?php
/* *
 * 配置文件
 */
 
//↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
//商户ID
$alipay_config['partner']		= '1000';

//商户KEY
$alipay_config['key']			= 'NrN4SVDY4IIdRvvdI04sOiIM4K0OC4sN';

//签名方式 不需修改
$alipay_config['sign_type']    = strtoupper('MD5');

//字符编码格式 目前支持 gbk 或 utf-8
$alipay_config['input_charset']= strtolower('utf-8');

//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$alipay_config['transport']    = 'http';

//支付API地址
$alipay_config['apiurl']    = 'http://API地址/';

//回调通知地址
$alipay_config['notify_url']  = "http://你的网站域名/epay_notify.php";
$alipay_config['return_url']  = "http://你的网站域名/epay_return.php";

?>