<?php
require_once "config.php";

session_start();
$logged=$_SESSION['logged'];

if ($logged != 1)
{
	header('Location: login.html');
}
ini_set('date.timezone','Asia/Shanghai');
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
  <script type="text/javascript">
function Check()
{
	if(form.sellerid.time1=="")
	{
		alert("时间不能为空!");
		return false;
	}
	
	if(form.money.time2=="")
	{
		alert("时间不能为空!");
		return false;
	}

	return true;

}
</script>
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
		<h3 class="panel-title">查询条件</h3>
	</div>
	<ul class="list-group">
		<li class="list-group-item">

<form id="form" method="post" action="./yonghu1.php" onsubmit="return Check();">
<p align = center><br>
<input type="radio" name="radio" value="1"  checked>注册时间
<input type="radio" name="radio" value="2">登录时间<br><br>
 从 <input type="datetime-local" value="2017-12-13T00:00:00" id="time1" name="time1"/>
到<input type="datetime-local" value="2017-12-14T00:00:00" id="time2" name="time2"/>
<input type="submit" value="查询">
</p>
</form>

</li>
	</ul>
</div>
    </div>
  </div>
<script type="text/javascript">
var format = "";

//构造符合datetime-local格式的当前日期
function getFormat(){
format = "";
var nTime = new Date();
format += nTime.getFullYear()+"-";
format += (nTime.getMonth()+1)<10?"0"+(nTime.getMonth()+1):(nTime.getMonth()+1);
format += "-";
format += nTime.getDate()<10?"0"+(nTime.getDate()):(nTime.getDate());
format += "T";
//format += nTime.getHours()<10?"0"+(nTime.getHours()):(nTime.getHours());
//format += ":";
//format += nTime.getMinutes()<10?"0"+(nTime.getMinutes()):(nTime.getMinutes());
//format += ":00";
}

	getFormat();
　　document.getElementById("time1").value = format + "00:00";//赋初始值
	document.getElementById("time2").value = format + "23:59";//赋初始值

</script>
</body>
</html>