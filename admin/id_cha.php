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
  <SCRIPT type="text/JavaScript">
function del()
{
	if( confirm("确定删除吗?") )
	{
		return true;
	}
	else
	{
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
  <form id="form" method="post" action="" >&nbsp;&nbsp;
	<label>用户ID:</label>
&nbsp;&nbsp;<input type="text" name="sellerid" id="sellerid" size="10" >&nbsp;&nbsp;
	<input type="hidden" name="warning_post" id="warning_post"  >
<input type="submit" class="btn btn-primary" value="查询">
</form>

<div id="warningdlg" style='position:absolute; z-index:999;top:50%;left:50%;color:#000; background:#FFF;border:1px solid #000;width:200px;height:150px;display:none;'>
0:正常  1:封号：<br><br>
&nbsp;&nbsp;<input type="text" id="warning" size="10" >
<br><br>
&nbsp;&nbsp;<input type="button" value="确定"  onclick="javascript:queding();">
&nbsp;&nbsp;<input type="button" value="取消" onclick="javascript:cancel();">
</div>

<div class="panel panel-info">
	<div class="panel-heading">
		<h3 class="panel-title">用户信息</h3>
	</div>
	<ul class="list-group">
<?php

	$sellerid = $_REQUEST['sellerid'];
	$sellerid = g_xyReplacestring ($sellerid);
	if ( $sellerid == NULL || $sellerid== '' )
	{
		die("ID 为空 ！");
	}
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con))
	{
		die('Could not connect: ' . mysqli_connect_error());
	}
	
	$warning_post = $_POST['warning_post'];
	$warning_post = g_xyReplacestring ($warning_post);
	if ( $warning_post == NULL || $warning_post== '' )
	{
		
	}
	else
	{
		$res = mysqli_query($con, "update sellerinformation set warning = $warning_post WHERE SellerID = $sellerid ; ");
	}

	$result = mysqli_query($con, "select * from sellerinformation where Sellerid = $sellerid; " );
	if ($result == TRUE)
	{
		while($row = mysqli_fetch_array($result))
		{ 
			echo "<li class=\"list-group-item\">";
			echo "<b>用户ID: </b>" . $row['SellerID'] . "&nbsp;&nbsp;" .
			"<b>用户名: </b>" . $row['Name'] . "&nbsp;&nbsp;" .
			"<b>联系QQ: </b>" . $row['ContactQQ'] . "&nbsp;&nbsp;" .
			"<b>Email: </b>" . $row['Email'] . "&nbsp;&nbsp;" .
			"<b>手机号: </b>" . $row['Phone'] . "&nbsp;&nbsp;" .
			"<b>上线用户: </b>" . $row['fromid'] . "&nbsp;&nbsp;" .
			"<br>" .
			"<b>注册时间: </b>" . $row['RegisterTime'] . "&nbsp;&nbsp;" .
			"<b>登录时间: </b>" . $row['LastLandTime'] . "&nbsp;&nbsp;" .
			"<b>注册IP: </b>" . $row['RegisterIP'] . "&nbsp;&nbsp;" .
			"<b>登录IP: </b>" . $row['LastLandIP'] . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" .
			"<b>是否封号: </b>" . $row['warning'] . "&nbsp;&nbsp;（0：正常 &nbsp;&nbsp; 1：封号）" ;
			
			echo "<button type='button' onclick='javascript:warning();' class=\"btn btn-success\">修改</button>";
			echo "</li>";
			
			$warning = $row['warning'];
		}
		mysqli_free_result($result);
	}
	
	$result = mysqli_query($con, "select * from jiesuanbiao where Sellerid = $sellerid; " );
	if ($result == TRUE)
	{
		while($row = mysqli_fetch_array($result))
		{ 
			echo "<li class=\"list-group-item\">";
			echo "<b>余额: </b>" . $row['Balance'] . "&nbsp;&nbsp;" .
			"<b>是否结算: </b>" . $row['IfJieSuan'] . "&nbsp;&nbsp;（1表示已提交结算）" . "&nbsp;&nbsp; " .
			"<b>下线人数: </b>" . $row['tgnum'] . "&nbsp;&nbsp; " .
			"<b>下线提成: </b>" . $row['tgmoney'] . "&nbsp;&nbsp; " .
			"<br>" .
			"<b>提交结算时间: </b>" . $row['TiJiaoJieSuanTime'] . "&nbsp;&nbsp; " .
			"<b>上一次结算时间: </b>" . $row['LastJieSuanTime'] . "&nbsp;&nbsp;" .
			"<b>结算次数: </b>" . $row['JieSuanNum'] . "&nbsp;&nbsp;" .
			"<b>结算总金额: </b>" . $row['JieSuanSum'] . "&nbsp;&nbsp;" .
			"<b>上一次结算金额: </b>" . $row['LastJieSuanBalance'] . "&nbsp;&nbsp;" .
			"<b>结算金额: </b>" . $row['Jiesuanbalance']/100 . "元&nbsp;&nbsp;（不含服务费）" .
			"<br>" .
			"<b>支付宝账号: </b>" . $row['zfb_account'] . "&nbsp;&nbsp;" .
			"<b>支付宝姓名: </b>" . $row['zfb_payee'] . "&nbsp;&nbsp;" ;
			
			echo "</li>";
		}
		mysqli_free_result($result);
	}
?>
	</ul>
</div>

            <table class="table table-bordered table-striped">
		    <thead><tr><th width="400">图片地址</th><th>图片价格</th><th>文件名</th><th>付款次数</th><th>付款时间</th><th>上传时间</th><th>备注信息</th><th>操作</th></thead>
            <tbody>
<?php	
	
	$result = mysqli_query($con, "select * from pictureinformation where Sellerid = $sellerid order by LastPayTime desc limit 50;" );
	if ($result == TRUE)
	{
		while($row = mysqli_fetch_array($result))
		{ 
			if ( $row['ifdelete'] == 0)
			{
				$price = $row['Price']/100;
				echo "<tr>";
				echo 
				"<td>" . "<a href=\"{$row['PicUrl']}\" target=_blank>" .$row['PicUrl'] . "</a></td>" .
				"<td>" . $price . "</td>" .
				"<td>" . $row['SellerID']. "_" . $row['PictureID'] . "_" . $price . "</td>" .
				"<td>" . $row['PayNum'] . "</td>" .
				"<td> " . $row['LastPayTime'] . "</td>" .
				"<td>" . $row['add_time'] . "</td>"  .
				"<td>" . $row['beizhu'] . "</td>"  .
				"<td><a href=\"del.php?picurl=" . $row['PicUrl'] . "&filename=" . $row['SellerID']. "_" . $row['PictureID'] . "_" . $price . "\" target=_blank onclick='return del()' class=\"btn btn-success\">删除图片</a></td>";
				echo "</tr>";
			}
			
		}
		mysqli_free_result($result);
	}

	mysqli_close($con);
	
?>
			</tbody>
          </table>
 <script type="text/javascript">
	document.getElementById( "sellerid").value=<?php echo $sellerid; ?>;
	function warning()
	{
		document.getElementById("warning").value = <?php echo $warning; ?>;
		document.getElementById("warningdlg").style.display="block";
	}
	
	function queding()
	{	
		document.getElementById("warning_post").value = document.getElementById("warning").value;
		document.getElementById("form").submit();
	}
	
	function cancel()
	{
		document.getElementById("warningdlg").style.display="none";
	}
</script>
</body>
</html>