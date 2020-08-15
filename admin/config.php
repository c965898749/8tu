<?php
require_once "../config.php";

function g_xyReplacestring($q)
{
	$q = str_replace("'","a",$q);
	$q = str_replace("\"","a",$q);
	$q = str_replace("\\","a",$q);
	$q = str_replace("/","a",$q);
	$q = str_replace(";","a",$q);
	$q = str_replace("%","a",$q);
	$q = str_replace("#","a",$q);
	$q = str_replace("(","a",$q);
	$q = str_replace(")","a",$q);
	$q = str_replace("*","a",$q);
	$q = str_replace("&","a",$q);
	$q = str_replace("|","a",$q);
	$q = str_replace("<","a",$q);
	$q = str_replace(">","a",$q);
	$q = str_replace(",","a",$q);
	$q = str_replace("$","a",$q);
	$q = str_replace("^","a",$q);
	$q = str_replace("{","a",$q);
	$q = str_replace("}","a",$q);
	$q = str_replace("[","a",$q);
	$q = str_replace("]","a",$q);
	return $q;
}

function g_xyReplacestring2($q)
{
	$q = str_replace("'","a",$q);
	$q = str_replace("\"","a",$q);
	$q = str_replace(";","a",$q);
	$q = str_replace("*","a",$q);
	$q = str_replace(",","a",$q);
	$q = str_replace(" ","a",$q);
	$q = str_replace("(","a",$q);
	$q = str_replace(")","a",$q);
	$q = str_replace("[","a",$q);
	$q = str_replace("]","a",$q);
	$q = str_replace("{","a",$q);
	$q = str_replace("}","a",$q);
	return $q;
}

?>