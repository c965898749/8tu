<?php
require_once("config.php");
require_once("code_config.php");
ini_set('date.timezone','Asia/Shanghai');

	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
	$type = 1;
	
	if (stripos($user_agent, "MicroMessenger") == TRUE )
	{
		$type = 3;
	}
	
	if (stripos($user_agent, "AlipayClient") == TRUE)
	{
		$type = 1;
	}
	
	$out_trade_no = trim( $_REQUEST['orderid']);
	$picurl = trim( $_REQUEST['picurl'] );
	
	$out_trade_no = str_replace("'","a",$out_trade_no);
	$out_trade_no = str_replace("\"","a",$out_trade_no);
	$out_trade_no = str_replace(";","a",$out_trade_no);
	$out_trade_no = str_replace("*","a",$out_trade_no);
	$out_trade_no = str_replace(",","a",$out_trade_no);
	$out_trade_no = str_replace(" ","a",$out_trade_no);
	$out_trade_no = str_replace("(","a",$out_trade_no);
	$out_trade_no = str_replace(")","a",$out_trade_no);
	$out_trade_no = str_replace("[","a",$out_trade_no);
	$out_trade_no = str_replace("]","a",$out_trade_no);
	$out_trade_no = str_replace("{","a",$out_trade_no);
	$out_trade_no = str_replace("}","a",$out_trade_no);
	
	$picurl = str_replace("'","a",$picurl);
	$picurl = str_replace("\"","a",$picurl);
	$picurl = str_replace(";","a",$picurl);
	$picurl = str_replace("*","a",$picurl);
	$picurl = str_replace(",","a",$picurl);
	$picurl = str_replace(" ","a",$picurl);
	$picurl = str_replace("(","a",$picurl);
	$picurl = str_replace(")","a",$picurl);
	$picurl = str_replace("[","a",$picurl);
	$picurl = str_replace("]","a",$picurl);
	$picurl = str_replace("{","a",$picurl);
	$picurl = str_replace("}","a",$picurl);
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{
		die('Could not connect: ' . mysqli_connect_error() );
	}
	$sql = sprintf("insert into order_temp (order_id, picurl, time, ifsuccess) values ('%s', '%s', '%s', 0 ); ", $out_trade_no, $picurl, date("YmdHis")  );
	$result = mysqli_query($con, $sql);
	if ($result == FALSE)
	{
		die('failed!' );
	}
	
	mysqli_close($con);

$data = array(
    "id" => $codepay['id'],//你的码支付ID
    "pay_id" => $_REQUEST['orderid'] , //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
    "type" => $type,//1支付宝支付 3微信支付 2QQ钱包
    "price" => $_REQUEST['fee'] / 100,//金额
    "param" => "",//自定义参数
	"page" => 2,
    "notify_url"=>$codepay['notify_url'],//通知地址
    "return_url"=>$codepay['return_url'],//跳转地址
); //构造需要传递的参数

ksort($data); //重新排序$data数组
reset($data); //内部指针指向数组中的第一个元素

$sign = ''; //初始化需要签名的字符为空
$urls = ''; //初始化URL参数为空

foreach ($data AS $key => $val) { //遍历需要传递的参数
    if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
    if ($sign != '') { //后面追加&拼接URL
        $sign .= "&";
        $urls .= "&";
    }
    $sign .= "$key=$val"; //拼接为url参数形式
    $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

}
$query = $urls . '&sign=' . md5($sign .$codepay['key']); //创建订单所需的参数
$url = "http://api2.xiuxiu888.com/creat_order/?{$query}"; //支付页面

header("Location:{$url}"); //跳转到支付页面

?>