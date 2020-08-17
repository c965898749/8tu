<?php
$picurl = trim( $_REQUEST["picurl"] );
$id="<html>
<head>
<meta http-equiv=\"refresh\"content=\"0;url={$picurl}\">
</head>
</html>";
header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Content-Disposition:attachment;filename=解压密码.html");
header("Expires: 0");
header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
header("Pragma:public");
echo $id;
?>
