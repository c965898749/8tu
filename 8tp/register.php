<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$name = trim($_REQUEST["username"]);
    $name = g_xyReplacestring( $name );
	
	if ( $name == "")
	{
		header("location:warning.php?status=1&title=没有输入用户名&time=3");
		die(0);
	}
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{ 
		$errortext = "Could not connect: " . mysqli_connect_error(); 
		header("location:warning.php?status=1&title={$errortext}&time=10");
		die(0);
	} 
		
	$result = mysqli_query($con, "select * from sellerinformation where Name ='$name';" );
	if ( mysqli_num_rows($result) != 0)
	{
		mysqli_close($con);
		header("location:warning.php?status=1&title=用户名已使用，请重新输入！&time=3");
		die(0);
	}
	
	$password = md5($_REQUEST["password"]);
	$registerip = $_SERVER['REMOTE_ADDR'];
	$registertime = date('Y-m-d H:i:s');
	$fromid = g_xyReplacestring( $_REQUEST["fromid"] );
	$contactqq = g_xyReplacestring( $_REQUEST["contactqq"] );

	$result = mysqli_query($con, "insert into sellerinformation(Name, ContactQQ, Password, RegisterIP, RegisterTime, fromid) values ('$name', '$contactqq', '$password', '$registerip', '$registertime', '$fromid' );" );
	
	if ($result == FALSE)
	{
		mysqli_close($con);
		header("location:warning.php?status=1&title=系统原因，注册失败1！&time=3");
		die(0);
	}
	
	$result = mysqli_query($con, "select SellerID from sellerinformation where Name = '$name' and Password = '$password'; ");
	if ($result == FALSE)
	{
		mysqli_close($con);
		header("location:warning.php?status=1&title=系统原因，注册失败2！&time=3");
		die(0);
	}
	
	$row = mysqli_fetch_array($result);
	if ($row == FALSE)
	{
		mysqli_close($con);
		header("location:warning.php?status=1&title=系统原因，注册失败3！&time=3");
		die(0);
	}
	
	$result1 = mysqli_query($con, "insert into jiesuanbiao(SellerID, Balance) values ({$row['SellerID']} , 0);" );
	if ($result1 == FALSE)
	{
		mysqli_close($con);
		header("location:warning.php?status=1&title=系统原因，注册失败4！&time=3");
		die(0);
	}
	
	//下线推广模块
	if ( is_numeric($fromid) && $web_config['iftg'] == 1 )
	{
		$result = mysqli_query($con, "select tgnum from jiesuanbiao where SellerID = $fromid ;" );
		$row = mysqli_fetch_array($result);
		if($row != NULL)
		{									
			$tgnum = $row['tgnum'];
			$tgnum = $tgnum + 1;
					
			mysqli_query($con, "update jiesuanbiao set tgnum='{$tgnum}'  where SellerID = '{$fromid}';");
		}
	}
	
	mysqli_close($con);
	
	header("location:warning.php?status=0&title=注册成功！&time=1&url=login.html");

?>