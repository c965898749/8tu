<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>易支付</title>
</head>
<?php

require_once("epay.config.php");
require_once("epay_lib/epay_submit.class.php");

/**************************请求参数**************************/

        $notify_url = $alipay_config['notify_url'];

        $return_url = $alipay_config['return_url'];


        $out_trade_no = $_POST['WIDout_trade_no'];
        //商户网站订单系统中唯一订单号，必填
		//支付方式
        $type = $_POST['type'];
        //商品名称
        $name = $_POST['WIDsubject'];
		//付款金额
        $money = $_POST['WIDtotal_fee'];
		//站点名称
        $sitename = '易支付_扫码支付';
        //必填        //订单描述
/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
		"pid" => trim($alipay_config['partner']),
		"type" => $type,
		"notify_url"	=> $notify_url,
		"return_url"	=> $return_url,
		"out_trade_no"	=> $out_trade_no,
		"name"	=> $name,
		"money"	=> $money,
		"sitename"	=> $sitename
);

//建立请求
$alipaySubmit = new AlipaySubmit($alipay_config);
$html_text = $alipaySubmit->buildRequestForm($parameter);
echo $html_text;

?>
</body>
</html>