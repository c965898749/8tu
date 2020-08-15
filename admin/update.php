<?php

require_once "config.php";

session_start();
$logged=$_SESSION['logged'];

if ($logged != 1)
{
	header('Location: login.html');
}

header("Content-type: text/html;charset=utf-8");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>平台管理中心</title>
  <meta name="keywords" content=""/>
  <meta name="description" content=""/>
  <link href="//lib.baomitu.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
  <script src="//lib.baomitu.com/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <!--[if lt IE 9]>
    <script src="//lib.baomitu.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//lib.baomitu.com/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>  <nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">导航按钮</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="./">平台管理中心</a>
      </div><!-- /.navbar-header -->
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
		   <li>
            <a href="./"><span class="glyphicon glyphicon-home"></span> 平台首页</a>
          </li>
          <li>
            <a href="id_cha.php"><span class="glyphicon glyphicon-user"></span>查询用户</a>
          </li>
		   <li>
            <a href="cha_id.php"><span class="glyphicon glyphicon-user"></span>搜索用户</a>
          </li>
		  <li><a href="./logout.php"><span class="glyphicon glyphicon-log-out"></span> 退出登录</a></li>

        </ul>
      </div><!-- /.navbar-collapse -->
    </div><!-- /.container -->
  </nav><!-- /.navbar -->
  <div class="container" style="padding-top:70px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
      <div class="panel panel-primary">
<div class="panel panel-info">
	<div class="panel-heading">
		
	</div>
	<ul class="list-group">
		<li class="list-group-item">
<?php
$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
if (mysqli_connect_errno($con))
{
	die('Could not connect: ' . mysqli_connect_error());
}

// some code

echo "<b>用户ID：</b>";
echo $id = $_GET['id'];
$id = g_xyReplacestring($id);
 
echo "&nbsp;&nbsp;</li>";

$res = mysqli_query($con, "UPDATE jiesuanbiao SET LastJieSuanBalance = Jiesuanbalance
WHERE SellerID = $id ");

$res = mysqli_query($con, "UPDATE jiesuanbiao SET JieSuanNum = JieSuanNum + 1
WHERE SellerID = $id ");

$res = mysqli_query($con, "UPDATE jiesuanbiao SET JieSuanSum = JieSuanSum + Jiesuanbalance
WHERE SellerID = $id ");

$res = mysqli_query($con, "UPDATE jiesuanbiao SET IfJieSuan = '0',Jiesuanbalance='0'
WHERE SellerID = $id ");

echo "<li class=\"list-group-item\"><b>status:</b>&nbsp;&nbsp;";
var_dump($res);
echo "</li>";

mysqli_close($con);

if($res){
	echo "<li class=\"list-group-item\"><b>结算状态：</b>&nbsp;&nbsp;已结算</li>";
	echo "<li class=\"list-group-item\">&nbsp;&nbsp;<a href='./jiesuan.php' class=\"btn btn-xs btn-primary\">点击返回</a></li>";
}
?>
		
	</ul>
</div>
    </div>
  </div>