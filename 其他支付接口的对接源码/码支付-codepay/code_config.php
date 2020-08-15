<?php
/* *
 * 配置文件
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//码支付的配置信息：
$codepay['id'] = "10000";  //这里改成码支付ID
$codepay['key'] = "BFKwH12365PFsUsLSyAiLler4SAmdYy5";  //这是您的通讯密钥

$codepay['notify_url'] = "http://你的网站域名/code_notify.php";  //异步通知地址
$codepay['return_url'] = "";  //同步通知地址 可留空


?>