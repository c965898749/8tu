<?php

require_once "config.php";
header("Content-type: text/html;charset=utf-8");
if ($admin_config['name'] == $_POST['username'] && $admin_config['password'] == $_POST['password'])
{
	session_start();
	$_SESSION['logged'] = 1;
	header('Location: index.php');
}
else
{
	die("登录失败");
}
?>
