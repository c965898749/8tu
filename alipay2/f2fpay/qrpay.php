<?php 
header("Content-type: text/html; charset=utf-8");
require_once("../../config.php");
ini_set('date.timezone','Asia/Shanghai');

error_reporting(0);

require_once 'model/builder/AlipayTradePrecreateContentBuilder.php';
require_once 'service/AlipayTradeService.php';

if (!empty($_REQUEST['order_id'])&& trim($_REQUEST['order_id'])!=""){

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

	$timeExpress = "5m";

	$appAuthToken = "";//根据真实值填写

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
	

	// 创建请求builder，设置请求参数
	$qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
	$qrPayRequestBuilder->setOutTradeNo($out_trade_no);
	$qrPayRequestBuilder->setTotalAmount($total_amount);
	$qrPayRequestBuilder->setTimeExpress($timeExpress);
	$qrPayRequestBuilder->setSubject($subject);
	$qrPayRequestBuilder->setBody($body);

	$qrPayRequestBuilder->setAppAuthToken($appAuthToken);

	$qrPay = new AlipayTradeService($config);
	$qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

	switch ($qrPayResult->getTradeStatus()){
		case "SUCCESS":
//			echo "支付宝创建订单二维码成功:"."<br>---------------------------------------<br>";
			$response = $qrPayResult->getResponse();
//			echo $response->qr_code ;
			?>
			<script language="javascript">
window.location.href = "<?php echo $response->qr_code ; ?>";
</script>

<?php
			break;
		case "FAILED":
			echo "支付宝创建订单二维码失败!!!"."<br>--------------------------<br>";
			if(!empty($qrPayResult->getResponse())){
				print_r($qrPayResult->getResponse());
			}
			break;
		case "UNKNOWN":
			echo "系统异常，状态未知!!!"."<br>--------------------------<br>";
			if(!empty($qrPayResult->getResponse())){
				print_r($qrPayResult->getResponse());
			}
			break;
		default:
			echo "不支持的返回状态，创建订单二维码返回异常!!!";
			break;
	}
	return ;
}

?>