<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$name = trim( $_REQUEST["name"] );
    $name= g_xyReplacestring( $name );
	$email = trim( $_REQUEST["email"] );
	$email= g_xyReplacestring( $email );
	
	if ( $name == "" && $email == "")
	{
		die(0);
	}
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{ 
		echo "0";
		die(0);
	} 
	
	if ($name != "")
	{
		$result = mysqli_query($con, "select * from sellerinformation where Name ='$name';" );
	}
	
	if ($email != "")
	{
		$result = mysqli_query($con, "select * from sellerinformation where Email ='$email';" );
	}
	
	if ( mysqli_num_rows($result) == 0)
	{
		echo "0";
	}
	else
	{
		echo "1";
	}
	
	mysqli_close($con);

?>