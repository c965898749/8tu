<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	$name = $login[1];

	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{ 
		$errortext = "Could not connect: " . mysqli_connect_error(); 
		header("location:warning.php?status=1&title=$errortext&time=10");
		die(0);
	}
	
	$result1 = mysqli_query($con, "select * from jiesuanbiao where Sellerid ='{$sellerid}' ;" );
	$row1 = mysqli_fetch_array($result1);
	if($row1 == NULL)
	{
		mysqli_free_result($result);
		mysqli_free_result($result1);
		mysqli_close($con);
		header("location:warning.php?status=1&title=系统原因，数据不全b&time=3&url=login.html");
		die(0);
	}

	$explode  = explode("tg.php", $_SERVER["REQUEST_URI"] );
	$tgurl ='http://'.$_SERVER['SERVER_NAME'] . $explode[0] ."register.html?u=" . $sellerid;
	
	mysqli_free_result($result1);
	mysqli_close($con);
	ob_clean();
?>
<!DOCTYPE html>
<!-- www.8tupian.com -->
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Cache-Control" content="no-transform" /> 
	<meta http-equiv="Cache-Control" content="no-siteapp" /> 
	<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=yes" />
	<title>下线推广</title>
	<link href="./css/bootstrap/bootstrap.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="./js/jquery.min.js"></script>
	<script src="./js/bootcss/layer.min.js"></script>
	<style type="text/css">
		#left{width: 10%; float: left; height: auto; min-height: 800px;border-right: 3px solid #000; min-width: 170px;}
		#right{width: 79%; height: 100%; float: right}
		.menu{list-style: none; line-height: 30px;}
		a {text-decoration: none; color: #333}
		.top_user {float: right;width: 200px;margin-right: 40px;margin-top: 10px;}
		.show{color:red;}
	</style>
<style type="text/css"> 
@media(max-width:760px)
{  
	body {width:100%;}
	.divPC{display:none}
	.divMobile{display:block}	
	.divMobile{width:100%}  
	.divMobile img{max-width:90%} 
}
@media(min-width:760px)
{
	.divPC{display:block}
	.divMobile{display:none}
}
</style>
</head>
<body>
<div class="divPC">
<!-- head -->
<?php include "head.htm"; ?>
<!-- content -->
<div>
	<!-- left -->
	<div id="left">
<?php include "menu.htm"; ?>
	</div>
	<!-- right -->
	<div id="right">
		<style type="text/css">
    label{width:20%;text-align: right; margin-right:20px;}
    .input_text{width: 250px; display: inline;}
</style>
<form action="zhuanru.php" method="post" >
    <table class="table">
        <caption><h3>下线推广</h3>（注：推广提成是<?php  echo $web_config['ticheng']*100 ?>%（永久性）。当你的下线进行结算时，结算服务费的<?php echo   $web_config['ticheng'] * 100 ?>%会自动计入您的提成金额中。当有效推广人数大于5人，提成金额大于50元时，就可以将提成金额转入账户余额，然后在个人中心里，进行结算。）</caption>
        <tbody>
        <tr>
            <td><label>推广链接：</label><span class="input_text"><?php echo $tgurl; ?></span></td>
        </tr>
		<tr>
            <td><label>下线人数：</label><span class="input_text"><?php echo $row1['tgnum']; ?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="javascript:chakan();">查看</button></td>
        </tr>
		<tr>
            <td><label>提成金额：</label><span class="input_text"><?php echo $row1['tgmoney']/100; ?>&nbsp;&nbsp;元</span></td>
        </tr>

        <tr>
            <td><button type="submit" class="btn btn-primary" style="margin-left: 25%;">转入账户余额</button></td>
        </tr>
        </tbody>
    </table>
</form>
	</div>
</div>
</div>
<div class="divMobile">
<?php include "menu_m.htm"; ?>
<div class="main-content">
	<div class="panel-content" style="margin-top: 50px;">
	<div class="main-content-area" style="margin-top: -10px;">	
		        <div class="col-md-6">
                  <div class="widget">
                      
                        <style type="text/css">
                            .list-group {
                              margin-bottom: 0px;
                            }
                        </style>
                      <div class="support-ticket-sec" style="padding:0 0 0 0;">
                        <input class="form-control" type="text" value="下线推广" style="border-radius: 0px" readonly="readonly">
                        <div class="list-group">
                          <div class="list-group-item">
                            <span class="pull-right"></span>
                            <em class="fa fa-fw fa-volume-up mr"></em>（注：推广提成是<?php  echo $web_config['ticheng'] * 100 ?>%（永久性）。当你的下线进行结算时，结算服务费的<?php  echo $web_config['ticheng'] * 100 ?>%会自动计入您的提成金额中。当有效推广人数大于5人，提成金额大于50元时，就可以将提成金额转入账户余额，然后在个人中心里，进行结算。）&nbsp;</div>
                          <div class="list-group-item">
                            <span class="pull-right"></span>
                            <em class="fa fa-fw fa-volume-up mr"></em>
							<b>推广链接：</b><br><br><?php echo $tgurl; ?>
							</div>
                          <div class="list-group-item">
                            <span class="pull-right"></span>
                            <em class="fa fa-fw fa-volume-up mr"></em><b>下线人数：</b><?php echo $row1['tgnum']; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button type="button" onclick="javascript:chakan();">查看</button>
							</div>
							<div class="list-group-item">
                            <span class="pull-right"></span>
                            <em class="fa fa-fw fa-volume-up mr"></em>
							<b>提成金额：</b><?php echo $row1['tgmoney']/100; ?>&nbsp;&nbsp;元
							</div>
							<div class="list-group-item">
								<span class="pull-right"></span>
								<em class="fa fa-fw fa-volume-up mr"></em>
								<form action="zhuanru.php" method="post" >
								<button type="submit" class="btn btn-primary" style="margin-left: 25%;">转入账户余额</button>
								</form>	
							</div>							
						</div>
                      </div>     				  
					</div>
              </div>			  					
	</div>
	</div>
</div>
</div>
<script type="text/javascript">
	function chakan()
	{
		window.open("tgchakan.php");
		return ;
		var width = 700;
		var height
		var mq = window.matchMedia("(max-width: 760px)");
		if (mq.matches)
		{
			width = document.body.clientWidth - 50;
			height = width;
		}
		var widthpx = width + "px";
		var heightpx = height + "px";
		layer.open({
//		  area: [widthpx, heightpx ],
		  type: 2, 
		  content: 'tgchakan.php' 
		  , btn: ['关闭']
		,yes: function (index, layero)
		{
			layer.close(index);
		}
		});
	}

</script>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>