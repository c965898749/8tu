<?php
error_reporting(0);

$domain = $_SERVER['SERVER_NAME'];

function getIp()
{
	if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
	{
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else if(!empty($_SERVER["REMOTE_ADDR"]))
	{
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else
	{
		$cip = '';
	}
	preg_match("/[\d\.]{7,15}/", $cip, $cips);
	$cip = isset($cips[0]) ? $cips[0] : 'unknown';
	unset($cips);

	return $cip;
}

function analysis( $fuhao , $buffer)
{
	$pos = strpos($buffer, $fuhao);
	$pos1 = strpos($buffer, "\r\n", $pos );
	return substr($buffer, $pos + strlen($fuhao), $pos1 - $pos - strlen($fuhao));
}

 echo getIp();
?>