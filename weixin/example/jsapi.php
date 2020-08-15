<?php 
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/
ini_set('date.timezone','Asia/Shanghai');

require_once("../../config.php");

require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once "WxPay.Config.php";
require_once 'log.php';

	$fee = trim( $_REQUEST['fee'] );
	$order_id = trim( $_REQUEST['order_id'] );
	$picurl = trim( $_REQUEST['picurl'] );
	
	$order_id = str_replace("'","a",$order_id);
	$order_id = str_replace("\"","a",$order_id);
	$order_id = str_replace(";","a",$order_id);
	$order_id = str_replace("*","a",$order_id);
	$order_id = str_replace(",","a",$order_id);
	$order_id = str_replace(" ","a",$order_id);
	$order_id = str_replace("(","a",$order_id);
	$order_id = str_replace(")","a",$order_id);
	$order_id = str_replace("[","a",$order_id);
	$order_id = str_replace("]","a",$order_id);
	$order_id = str_replace("{","a",$order_id);
	$order_id = str_replace("}","a",$order_id);
	
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
	
	$sql = sprintf("insert into order_temp (order_id, picurl, time) values ('%s', '%s', '%s' ); ", $order_id, $picurl, date("YmdHis")  );
	$result = mysqli_query($con, $sql);
	if ($result == FALSE)
	{
		die('failed!' );
	}
	
	mysqli_close($con);

//①、获取用户openid
try{

	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();
	
	$subject = "图片支付";
    if ( isset($config_8tupian['subjectname']) )
    {
    	$subject = $config_8tupian['subjectname'];
    }

	
	//②、统一下单
	$input = new WxPayUnifiedOrder();
	$input->SetBody($subject);
	$input->SetAttach("test");
	$input->SetOut_trade_no($order_id);
	$input->SetTotal_fee($fee);
	$input->SetTime_start(date("YmdHis"));
	$input->SetTime_expire(date("YmdHis", time() + 600));
	$input->SetGoods_tag("test");
	$input->SetNotify_url("http://工程公网访问地址/weixin/example/notify.php");
	$input->SetTrade_type("JSAPI");
	$input->SetOpenid($openId);
	$config = new WxPayConfig();
	$order = WxPayApi::unifiedOrder($config, $input);

	$jsApiParameters = $tools->GetJsApiParameters($order);

	//获取共享收货地址js函数参数
	$editAddress = $tools->GetEditAddressParameters();
} catch(Exception $e) {
	Log::ERROR(json_encode($e));
}
//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				
				if (res.err_msg == "get_brand_wcpay_request:ok")
				{
					window.location.href = "success.php" + "<?php echo '?orderid=' . $order_id . '&fee=' . $fee; ?>" ;
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
	<script type="text/javascript">
	//获取共享地址
	function editAddress()
	{
		WeixinJSBridge.invoke(
			'editAddress',
			<?php echo $editAddress; ?>,
			function(res){
				var value1 = res.proviceFirstStageName;
				var value2 = res.addressCitySecondStageName;
				var value3 = res.addressCountiesThirdStageName;
				var value4 = res.addressDetailInfo;
				var tel = res.telNumber;
				
				alert(value1 + value2 + value3 + value4 + ":" + tel);
			}
		);
	}
	/*
	window.onload = function(){
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', editAddress); 
		        document.attachEvent('onWeixinJSBridgeReady', editAddress);
		    }
		}else{
			editAddress();
		}
	};
	*/
	</script>
</head>
<body>
	<script type="text/javascript">
	callpay();
	</script>
</body>
</html>