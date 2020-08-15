<?php
require_once("config.php");
ini_set('date.timezone','Asia/Shanghai');

	//获取参数
	//获取参数
	$fee = trim( $_REQUEST['fee'] );
	$order_id = trim( $_REQUEST['orderid'] );
	$picurl = trim( $_REQUEST['picurl'] );
	$out_trade_no = trim( $_REQUEST['orderid'] );
	
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
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
	$type = "wxpay";
	
	if (stripos($user_agent, "MicroMessenger") == TRUE )
	{
		$type = "wxpay";
	}
	
	if (stripos($user_agent, "AlipayClient") == TRUE)
	{
		$type = "alipay";
	}
	
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>支付</title>
	<style type="text/css">
a:link,a:visited{
 text-decoration:none;
}
#xyfooter{
	position: fixed;
	bottom: 20px;
}
</style>
</head>
<body>
    <br/>
	<form name=alipayment action='epayapi.php' method=post target="_blank">
    支付<span style="color:#f00"><?php echo $fee/100; ?></span>元。
	<br><br>
	<input type="hidden" id="WIDout_trade_no" name="WIDout_trade_no"  value="<?php echo $order_id; ?>" />
	<input type="hidden" id="WIDsubject" name="WIDsubject"  value="扫码支付vip" />
	<input type="hidden" id="type" name="type"  value="<?php echo $type; ?>" />
	<input type="hidden" id="WIDtotal_fee" name="WIDtotal_fee" value="<?php echo $fee/100; ?>" />
	<div align="center">
		<button style="width:100px; height:35px; border-radius: 5px;background-color:#3CA2F0; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="submit" >立即支付</button>
	</div>
	</form>
	<br><br>
<?php
	if ( strlen( $config_8tupian['contactqq'] ) > 3 )
	{
		echo "<div align=\"center\">" . "客服QQ：". $config_8tupian['contactqq'] . "</div>";
	}
?>
</body>
</html>