<?php
require_once("config.php");
require_once("code_config.php");
ini_set('date.timezone','Asia/Shanghai');

ksort($_POST); //排序post参数
reset($_POST); //内部指针指向数组中的第一个元素
$codepay_key=$codepay['key']; //这是您的密钥
$sign = '';//初始化
foreach ($_POST AS $key => $val) { //遍历POST参数
    if ($val == '' || $key == 'sign') continue; //跳过这些不签名
    if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
    $sign .= "$key=$val"; //拼接为url参数形式
}
if (!$_POST['pay_no'] || md5($sign . $codepay_key) != $_POST['sign']) { //不合法的数据
    exit('fail');  //返回失败 继续补单
} else { //合法的数据
    //业务处理
    $pay_id = $_POST['pay_id']; //需要充值的ID 或订单号 或用户名
    $money = (float)$_POST['money']; //实际付款金额
    $price = (float)$_POST['price']; //订单的原价
    $param = $_POST['param']; //自定义参数
    $pay_no = $_POST['pay_no']; //流水号
	
	$orderid = $pay_id;
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

			exit('success'); //返回成功 不要删除哦	
	}
	else
	{
		exit('fail');
	}
		  
	curl_close($ch);
}