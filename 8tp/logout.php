<?php

$_SESSION=array();

setCookie("PHPSESSID","",time()-1,"/");
session_destroy();
	
header('Location: index.php');

?>
