<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{ 
		$errortext = "Could not connect: " . mysqli_connect_error(); 
		header("location:warning.php?status=1&title=$errortext&time=10");
		die(0);
	}
	
	$result=mysqli_query($con, "select * from jiesuanbiao where Sellerid ='{$sellerid}' ;" );
	$row = mysqli_fetch_array($result);
	if($row == NULL)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=需要登录&time=3&url=login.html");
		die(0);
	}

	if ($row['tgnum'] < 5)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=转入失败：有效推广人数要大于5人！&time=3");
		die(0);
	}
	
	if ($row['tgmoney'] < 5000 )
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=转入失败：提成金额要大于50元才能转入账户余额！&time=3");
		die(0);
	}
	
	$balance = $row['Balance'] + $row['tgmoney'];
	
	$result=mysqli_query($con, "update jiesuanbiao set tgmoney=0, Balance=$balance where SellerID = $sellerid ;" );
	
	mysqli_free_result($result);
	mysqli_close($con);
	header("location:warning.php?status=0&title=转入成功: 请到个人中心的账户余额中查看&time=3");
	die (0);
?>