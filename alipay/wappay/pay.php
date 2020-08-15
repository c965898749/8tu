<?php
/* *
 * 功能：支付宝手机网站支付接口(alipay.trade.wap.pay)接口调试入口页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 请确保项目文件有可写权限，不然打印不了日志。
 */

header("Content-type: text/html; charset=utf-8");

require_once("../../config.php");
ini_set('date.timezone','Asia/Shanghai');

require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'service/AlipayTradeService.php';
require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'buildermodel/AlipayTradeWapPayContentBuilder.php';
require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';
if (!empty($_REQUEST['order_id'])&& trim($_REQUEST['order_id'])!="")
{
    //商户订单号，商户网站订单系统中唯一订单号，必填
    $out_trade_no = trim($_REQUEST['order_id']);

    //订单名称，必填
    $subject = "图片支付";
    if ( isset($config_8tupian['subjectname']) )
    {
    	$subject = $config_8tupian['subjectname'];
    }

    //付款金额，必填
    $total_amount = $_REQUEST['fee'] / 100;
	
	$picurl = trim($_REQUEST['picurl']);
	
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

    //超时时间
    $timeout_express="1m";

    $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
    $payRequestBuilder->setBody($body);
    $payRequestBuilder->setSubject($subject);
    $payRequestBuilder->setOutTradeNo($out_trade_no);
    $payRequestBuilder->setTotalAmount($total_amount);
    $payRequestBuilder->setTimeExpress($timeout_express);

    $payResponse = new AlipayTradeService($config);
    $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);

    return ;
}
?>