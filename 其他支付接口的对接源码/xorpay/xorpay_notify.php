<?php
require_once("xorpay_config.php");
require_once("config.php");
ini_set('date.timezone','Asia/Shanghai');

# 签名函数
function sign_xorpay($data_arr) {
    return md5(join('',$data_arr));
};

$sign = sign_xorpay(array($_POST['aoid'], $_POST['order_id'], $_POST['pay_price'], $_POST['pay_time'], $xorpay_config['secret'] ) );

# 对比签名
if($sign == $_POST['sign']) 
{
    # 签名验证成功，更新数据
	
	$orderid = $_POST['order_id'];
	$fee = $_POST['pay_price'] * 100;
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
			echo "success";
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

		echo "ok";	
	}
	else
	{
		echo "failed";
	}
		  
	curl_close($ch);
} else {
    # 签名验证错误

    header("HTTP/1.0 405 Method Not Allowed");
    exit();
};

?>
