<?php
/* *
 * 配置文件
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//xorpay平台的配置信息：
$xorpay_config['aid'] = "1000";

//app secret, 在个人中心配置页面查看
$xorpay_config['secret'] = "69600cda3e3345ee9eb90123b7277685";

//回调通知地址
$xorpay_config['notify_url'] = "http://你的网站域名/xorpay_notify.php";  

?>