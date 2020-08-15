<?php
/* *
 * 配置文件
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//ini_set("display_errors","Off");

//八图片平台配置信息：
$config_8tupian['pid'] = "18625";   //pid
$config_8tupian['key_id'] = "fe64201fca8010101124298a1e014c8c";   //key
$config_8tupian['api_url'] = "http://web.8tupian.com/api/notify.php";   //通知地址，不用修改


//数据库信息配置
$data_config['DB_HOST'] = '192.168.1.3';   // 数据库地址
$data_config['DB_NAME'] = 'yimem';  // 数据库名
$data_config['DB_USER'] = 'root';   // 用户名
$data_config['DB_PWD'] = 'root';  // 密码


//后台管理的账号和密码，建议修改。后台管理的网址：http://你的域名/admin/
$admin_config['name'] = 'admin';
$admin_config['password'] = '123456';


//手机支付页面显示的客服QQ，留空则不显示。建议填写（写到双引号里面）
$config_8tupian['contactqq'] = "965898749";

//支付宝账单里显示的商品说明，可以修改（比如改成你的产品名称或者联系方式等）
$config_8tupian['subjectname'] = "客观qq965898749";


//全局函数，不用修改
function sign($data, $key) 
{
    ksort($data);
    $sign = md5(urldecode(http_build_query($data)).'&key='.$key);
    return $sign;
}

?>