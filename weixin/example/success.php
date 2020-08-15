<?php
ini_set('date.timezone','Asia/Shanghai');

require_once("../../config.php");

$user_agent = $_SERVER['HTTP_USER_AGENT'];
$refer = $_SERVER['HTTP_REFERER'];
$domain = $_SERVER['SERVER_NAME'];
if (stripos($user_agent, "MicroMessenger") == FALSE || stripos($refer, $domain) == FALSE)
{
	die ("failed");
}

	$fee = trim( $_REQUEST['fee'] );
	$orderid = trim( $_REQUEST['orderid'] );
	
		global $config_8tupian;
		
		$postdata = array(
			'orderid'  => $orderid,
			'fee'      => $fee 
		);

		$sign =  sign($postdata, trim( $config_8tupian['key_id'] ) );
		
		$url = sprintf("%s?orderid=%s&fee=%d&sign=%s", $config_8tupian['api_url'], $orderid, $fee , $sign);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		
		global $data_config;
		
        if($response == "success") 
		{
			//回写数据库						
			$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
			if (mysqli_connect_errno($con)) 
			{
				die( mysqli_connect_error() );
			}
			
			$sql = sprintf("select * from order_temp where order_id='%s' order by time desc", $orderid  );
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			$picurl = $row['picurl'];
			$order_temp_id = $row['id'];
			$ifsuccess = $row['ifsuccess'];
			if ($ifsuccess > 0)
			{
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	</head>
	<body>
<p align=center><br><br>支付成功<br><br>回到原网页刷新</p>
    </body>
</html>
<?php
				curl_close($ch);
				die(0);
			}
			
			mysqli_free_result($result);
			
			$sql = sprintf("select * from pictureinformation where picurl = '%s'; ", $picurl );
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			$sellerid = $row['SellerID'];
			$picid = $row['ID'];
			
			mysqli_free_result($result);
			
			$sql = sprintf("update order_temp set ifsuccess = ifsuccess + 1  where id = %d;", $order_temp_id );
			mysqli_query($con, $sql);
			
			$sql = sprintf("update pictureinformation set paynum = paynum + 1, lastpaytime = '%s'  where id = %d;", date("YmdHis"), $picid  );
			mysqli_query($con, $sql);
		
			$sql = sprintf("update jiesuanbiao set balance = balance + %d  where sellerid = %d;", $fee, $sellerid );
			mysqli_query($con, $sql);
			
			mysqli_close($con);
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	</head>
	<body>
<p align=center><br><br>支付成功<br><br>回到原网页刷新</p>
    </body>
</html>
<?php
    }
		else
		{
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	</head>
	<body>
<p align=center><br><br>出现错误</p>
    </body>
</html>
<?php
		}
		
		curl_close($ch);

?>
