<?php
require_once("../config.php");
require_once 'f2fpay/config/config.php';
require_once 'f2fpay/service/AlipayTradeService.php';
ini_set('date.timezone','Asia/Shanghai');

error_reporting(0);

$arr=$_POST;
$alipaySevice = new AlipayTradeService($config); 
$result = $alipaySevice->check($arr);

if($result)   //验证成功
{
	//商户订单号
	$out_trade_no = $_POST['out_trade_no'];

	//支付宝交易号
	$trade_no = $_POST['trade_no'];

	//交易状态
	$trade_status = $_POST['trade_status'];
	
	//订单金额
	$price = $_POST['total_amount'];

	if($_POST['trade_status'] == 'TRADE_FINISHED') 
	{
	}
	else if ($_POST['trade_status'] == 'TRADE_SUCCESS') 
	{
			$orderid = $out_trade_no;
			$fee = $price * 100;
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
	
	            echo "success";
	        }
			else
			{
				echo "fail";
			}
				  
			curl_close($ch);
			
			die(0);
	}
	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
}
else 
{
    //验证失败
    echo "fail";	//请不要修改或删除

}
?>

