<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$username = trim( $_REQUEST["username"] );
    $username = g_xyReplacestring( $username );//获取用户名
    $password=md5($_REQUEST["password"]);//获取密码
		
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{ 
		$errortext = "Could not connect: " . mysqli_connect_error(); 
		header("location:warning.php?status=1&title=$errortext&time=10");
		die(0);
	}
	
	$result=mysqli_query($con, "select * from sellerinformation where Name ='{$username}' and Password = '{$password}';" );

	$row = mysqli_fetch_array($result);
	if($row == NULL)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=用户名或密码错误&time=3");
		die(0);
	}
	
	$sellerid = $row['SellerID'];
	
	$clientip =  $_SERVER['REMOTE_ADDR'];
	$lastlandtime = date('Y-m-d H:i:s');
	
	//更新数据
	$result=mysqli_query($con, "update sellerinformation set LastLandIP = '$clientip' , LastLandTime = '$lastlandtime' where Sellerid=$sellerid ;" );
	
	if ($row['warning'] != 0)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=账号被关闭！如有疑问，请联系客服！&time=30");
		die(0);
	}
	
	$_SESSION['user_id'] = $sellerid ;
	
	mysqli_free_result($result);
	mysqli_close($con);
	
	header("location:warning.php?status=0&title=登录成功&time=1&url=index.php");

?>