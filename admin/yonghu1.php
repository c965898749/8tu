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
      <div class="panel panel-primary">
        <div class="panel-heading"><h3 class="panel-title">用户列表</h3></div>
        <div class="panel-body">
		<table class="table table-striped">
          <thead><tr><th>用户ID</th><th>用户名</th><th>联系QQ</th><th>Email</th><th>手机号</th><th>注册时间</th><th>登录时间</th><th>注册IP</th><th>登录IP</th><th>上线用户</th><th>账户状态</th></tr></thead>
          <tbody>
<?php
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con))
	{
		die('Could not connect: ' . mysqli_connect_error());
	}

	$time1 = $_POST['time1'];
	$time2 = $_POST['time2'];
	$time1 = g_xyReplacestring2($time1);
	$time2 = g_xyReplacestring2($time2);
	
	$time1 = str_replace("T", " ", $time1);
	$time2 = str_replace("T", " ", $time2);
	
	$type = $_POST['radio'];
	
	if ($type == 1)
	{
		$sql = "select  * from sellerinformation where RegisterTime > '$time1' and RegisterTime < '$time2' order by RegisterTime desc;";
	}
	else
	{
		$sql = "select  * from sellerinformation where LastLandTime > '$time1' and LastLandTime < '$time2' order by LastLandTime desc;";
	}
	
	$result = mysqli_query( $con, $sql );
	if ($result == TRUE)
	{
		while($row = mysqli_fetch_array($result))
		{ 
			echo "<tr>";
			echo "<td><a href=\"id_cha.php?sellerid={$row['SellerID']}\" class=\"btn btn-xs btn-primary\" target=_blank>" . $row['SellerID'] . "</a></td>" .
			"<td>" . $row['Name'] . "</td>" .
			"<td>" . $row['ContactQQ'] . "</td>" .
			"<td>" . $row['Email'] . "</td>" .
			"<td>" . $row['Phone'] . "</td>" .
			"<td> " . $row['RegisterTime'] . "</td>" .
			"<td>" . $row['LastLandTime'] . "</td>" .
			"<td>" . $row['RegisterIP'] . "</td>" .
			"<td>" . $row['LastLandIP'] . "</td>" .
			"<td>" . $row['fromid'] . "</td>" .
			"<td> " . $row['warning'] . "</td>" ;		
			echo "</tr>";
			
		}
	
		mysqli_free_result($result);
	}
	

	mysqli_close($con);
	
?> 
		</tbody>
        </table>
        </div>
      </div>
  </div>