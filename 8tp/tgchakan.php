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
  
	$result=mysqli_query($con, "select tgnum from jiesuanbiao where Sellerid ='{$sellerid}' ;" );
	$row = mysqli_fetch_array($result);
	echo "&nbsp;&nbsp;<b>下线用户：". $row['tgnum']. "</b><br><br>";
	mysqli_free_result($result);

	$result = mysqli_query($con, "select sellerid, RegisterTime, Name from  sellerinformation where fromid = '$sellerid' order by sellerinformation.RegisterTime desc limit 500;");

	while($row = mysqli_fetch_array($result))
	{
	  echo "&nbsp;&nbsp;用户名: ".$row['Name'];
	  echo "&nbsp;&nbsp;&nbsp;&nbsp;注册时间：".$row['RegisterTime'];
	  
	  $result1 = mysqli_query($con,"select balance from jiesuanbiao where sellerid= {$row['sellerid']} limit 1 ; ");
	  
	  $row1 = mysqli_fetch_array($result1);
	  
	  echo "&nbsp;&nbsp;&nbsp;&nbsp;余额: ".$row1['balance'] / 100;
	  echo "&nbsp;元";
	  
	  echo "<br />";
	}

	mysqli_free_result($result);
	mysqli_free_result($result1);
	mysqli_close($con);

?>