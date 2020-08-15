<?php

require_once "config.php";

session_start();
$logged=$_SESSION['logged'];

if ($logged != 1)
{
	header('Location: login.html');
}

ini_set('date.timezone','Asia/Shanghai');
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con))
	{
		die('Could not connect: ' . mysqli_connect_error());
	}
	
	$result = mysqli_query($con, "select count(*) as allnum from  sellerinformation ;");
	$row = mysqli_fetch_array($result);
	$sellernum = $row['allnum'] ;
	mysqli_free_result($result);
	
	$result = mysqli_query($con, "select count(*) as allnum from  pictureinformation ;");
	$row = mysqli_fetch_array($result);
	$picturenum = $row['allnum'] ;
	mysqli_free_result($result);
	
	$starttime = date('Y-m-d 0:0:0', time());
	$endtime = date('Y-m-d 23:59:59', time());
	$result = mysqli_query($con, "select count(*) as allnum from  order_temp where ifsuccess > 0 and time > '$starttime' and time < '$endtime';");
	$row = mysqli_fetch_array($result);
	$ordernum = $row['allnum'] ;
	mysqli_free_result($result);
	
	$result = mysqli_query($con, "select count(*) as allnum from  jiesuanbiao where IfJieSuan = 1;");
	$row = mysqli_fetch_array($result);
	$jiesuannum = $row['allnum'] ;
	mysqli_free_result($result);
	
	mysqli_close($con);
	
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
        <div class="panel-heading"><h3 class="panel-title">后台管理首页</h3></div>
          <ul class="list-group">
			<li class="list-group-item"><span class="glyphicon glyphicon-tint"></span> <b>用户数量：</b><?php echo $sellernum ?>&nbsp;&nbsp;<a href="yonghu.php" class="btn btn-xs btn-primary">查看</a></li>
			<li class="list-group-item"><span class="glyphicon glyphicon-tint"></span> <b>图片数量：</b><?php echo $picturenum ?>&nbsp;&nbsp;<a href="tupian.php" class="btn btn-xs btn-primary">查看</a></li>
			<li class="list-group-item"><span class="glyphicon glyphicon-stats"></span> <b>今日订单数：</b><?php echo $ordernum ?>&nbsp;&nbsp;<a href="order.php" class="btn btn-xs btn-primary">查看</a></li>
            <li class="list-group-item"><span class="glyphicon glyphicon-time"></span> <b>结算列表：</b><?php echo $jiesuannum ?>&nbsp;&nbsp;<a href="jiesuan.php" class="btn btn-xs btn-primary">查看</a></li>
          </ul>
      </div>
<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">服务器信息</h3>
	</div>
	<ul class="list-group">
		<li class="list-group-item">
			<b>PHP 版本：</b><?php echo phpversion();?></li>
	</ul>
</div>
    </div>
  </div>