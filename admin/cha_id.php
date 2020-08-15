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
  
  <form id="form" method="post" action="" >&nbsp;&nbsp;
	用户名:<input type="text" name="name" id="name" size="10" >&nbsp;&nbsp;
联系QQ:<input type="text" name="qq" id="qq" size="10">&nbsp;&nbsp;
email:<input type="text" name="email" id="email" size="10">&nbsp;&nbsp;
<input type="submit" class="btn btn-primary" value="查询">
</form>


      <div class="panel panel-primary">
        <div class="panel-body">
		<table class="table table-striped">
          <thead><tr><th>用户ID</th><th>用户名</th><th>联系QQ</th><th>Email</th><th>手机号</th><th>注册时间</th><th>登录时间</th><th>注册IP</th><th>登录IP</th><th>账户状态</th></tr></thead>
          <tbody>
<?php

	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{
		die('Could not connect: ' . mysqli_connect_error() );
	}

	$name = $_POST['name'];
	$contactqq = $_POST['qq'];
	$email = $_POST['email'];
	
	$name = g_xyReplacestring($name);
	$contactqq = g_xyReplacestring($contactqq);
	$email = g_xyReplacestring($email);
	
	$sql = "select  * from sellerinformation where 1 = 1 ";
	
	if ( $name == NULL || $name== '' )
	{
		
	}
	else
	{
		$sql = $sql . "and Name = '$name' ";
	}
	
	if ( $contactqq == NULL || $contactqq== '' )
	{
		
	}
	else
	{
		$sql = $sql . "and ContactQQ = '$contactqq' ";
	}
	
	if ( $email == NULL || $email== '' )
	{
		
	}
	else
	{
		$sql = $sql . "and Email = '$email' ";
	}
	
	$sql = $sql . "order by RegisterTime desc limit 10;" ;
	
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
			"<td>" . $row['RegisterTime'] . "</td>" .
			"<td>" . $row['LastLandTime'] . "</td>" .
			"<td>" . $row['RegisterIP'] . "</td>" .
			"<td>" . $row['LastLandIP'] . "</td>" .
			"<td>" . $row['warning'] . "</td>" ;
			
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