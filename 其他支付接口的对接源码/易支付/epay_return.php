<?php
require_once("config.php");
require_once("epay.config.php");
require_once("epay_lib/epay_notify.class.php");

ini_set('date.timezone','Asia/Shanghai');
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号
	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号
	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];

	//支付方式
	$type = $_GET['type'];
	
	$price = $_GET['money'];


    if($_GET['trade_status'] == 'TRADE_SUCCESS') 
	{
		$orderid = $out_trade_no;
		$fee = $price  * 100;
		$postdata = array(
			'orderid'      => $orderid,
			'fee'      => $fee 
		);

		$sign =  sign($postdata, trim( $config_8tupian['key_id']) );
		
		$url = sprintf("%s?orderid=%s&fee=%d&sign=%s", $config_8tupian['api_url'], $orderid, $fee , $sign);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
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
						echo "<p align=center><br><br>支付成功<br><br>回到原网页刷新</p>";
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
		
          echo "<p align=center><br><br>支付成功<br><br>回到原网页刷新</p>";	
        }
		else
		{
			echo "fail！失败";
		}
			  
		curl_close($ch);
    }
    else 
	{
      echo "trade_status=".$_GET['trade_status'];
    }

//	echo "验证成功<br />";

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
}
?>
        <title>易支付</title>
	</head>
    <body>
    </body>
</html>