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
  
  <SCRIPT type="text/JavaScript">
function del(a, b, c){
if(confirm("确定完成结算吗?" + "\r\n收款账号：" + a + "\r\n收款人：" + b + "\r\n结算金额：" + c + "元")){
return true;
}else{
return false;
}
}
</SCRIPT>
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
        <div class="panel-heading"><h3 class="panel-title">结算列表</h3></div>
        <div class="panel-body">
		<table class="table table-striped">
          <thead><tr><th>用户ID</th><th>支付宝账号</th><th>支付宝姓名</th><th>结算金额</th><th>提交时间</th><th>操作</th></thead>
          <tbody>
				<?php

$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
if (mysqli_connect_errno($con))
{
	die('Could not connect: ' . mysqli_connect_error());
}

$result = mysqli_query($con, "select * from jiesuanbiao where IfJieSuan = 1 order by TiJiaoJieSuanTime;"); //要加上 oder by 提交结算的时间，先提交的先结算
if ($result == FALSE)
{
	die(0);
}

while($row = mysqli_fetch_array($result))
  {
	  	  $result1 = mysqli_query("select * from sellerinformation where sellerid= $row[SellerID] ; ");
		  $row1 = mysqli_fetch_array($result1);
		  
		  if ($row1['warning'] > 0 )
		  {
			  continue;
		  }
		  echo "<tr>";
  echo "<td>". "<a href=\"id_cha.php?sellerid={$row['SellerID']}\">" . $row['SellerID'] . "</a></td>";
          echo "<td>" . $row['zfb_account'] . "</td>";
          echo "<td>".$row['zfb_payee'] . "</td>";
	  
	   $fee = $row['Jiesuanbalance'] / 100;
      echo "<td>" . $fee . "</td>";
	  echo "<td>" . $row['TiJiaoJieSuanTime'] . "</td>";
	  $a = $row['zfb_account'];
	  $b = $row['zfb_payee'];
	  $c = $fee;
      echo "<td>" . "<a href='./update.php?id=$row[SellerID]' onclick='return del(\"$a\", \"$b\", \"$c\" )' class=\"btn btn-xs btn-info\">完成</a>" . "</td>" ;
	  echo "</tr>";
  }

mysqli_close($con);

?>	  
		</tbody>
        </table>
        </div>
		<div class="panel-footer">
          <span class="glyphicon glyphicon-info-sign"></span> 结算列表中的金额，没有扣除服务费，结算时请自行扣除<br/>
        </div>
      </div>
    </div>
  </div>