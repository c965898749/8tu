<?php
require_once "config.php";

session_start();
ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{ 
		g_putmessage(1, "系统错误1");
	}
	
	$result = mysqli_query($con, "select * from jiesuanbiao where Sellerid ='{$sellerid}' ;" );
	$row = mysqli_fetch_array($result);
	if($row == NULL)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		
		g_putmessage(1, "系统错误2");
	}
	
	if ( $row['IfJieSuan'] == 1)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		
		g_putmessage(1, "您的提现已提交，请耐心等待！");
	}
	
	if ( $row['Balance'] < 1000)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		
		g_putmessage(1, "您的余额不足！");
	}
	
	$money = $row['Balance'] % 100;
	$time = date('Y-m-d H:i:s');
	$jiesuanbalance = $row['Balance'] - $money;
	$result1 = mysqli_query($con, "update jiesuanbiao set IfJieSuan=1, LastJieSuanTime=TiJiaoJieSuanTime, TiJiaoJieSuanTime='$time', Jiesuanbalance=$jiesuanbalance,  Balance=$money where Sellerid=$sellerid ;" );
	if ($result1 == FALSE)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		
		g_putmessage(1, "系统错误3");
	}
	
	//上线的推广提成增加
	$result1 = mysqli_query($con, "select fromid from sellerinformation where Sellerid ={$sellerid} ;" );
	$row1 = mysqli_fetch_array($result1);
	if ($row1 == NULL)
	{
		mysqli_free_result($result);
		mysqli_free_result($result1);
		mysqli_close($con);
		
		g_putmessage(1, "系统错误4");
	}
	
	if ( $row1['fromid'] != 0 && is_numeric($row1['fromid']) && $web_config['iftg'] == 1 )
	{
		$fromid = $row1['fromid'];
		$result2 = mysqli_query($con, "select tgmoney from jiesuanbiao where Sellerid ={$fromid} ;" );
		$row2 = mysqli_fetch_array($result2);
		if($row2 != NULL)
		{
			$tgmoney = $row2['tgmoney'];		
			$tgmoney = $tgmoney + $jiesuanbalance * 0.01 * $row['fuwufei'] * $web_config['ticheng'] ;

			//更新数据
			$result2 = mysqli_query($con, "update jiesuanbiao set tgmoney=$tgmoney where SellerID = $fromid;" );
		}
	}
	
	mysqli_free_result($result);
	mysqli_free_result($result1);
	mysqli_free_result($result2);
	mysqli_close($con);
		
	g_putmessage(0, "您的提现申请已成功提交！");

?>