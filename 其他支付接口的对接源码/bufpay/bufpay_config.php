<?php
/* *
 * 配置文件
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//paysapi的配置信息：
$bufpay_config['aid'] = "1000";  //bufpay 的 aid 在应用配置中查看
$bufpay_config['secret'] = "effced5f3ce74431ad41236e1c735b4b";  //bufpay的app secret 在应用配置中查看

$bufpay_config['notify_url'] = "http://你的网站域名/bufpay_notify.php";  //异步通知地址

$bufpay_config['return_url'] = "http://你的网站域名/bufpay_return.php";  //同步通知地址

?>