<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	$name = $login[1];
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
	<title>上传图片</title>
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
</head>
<body>
<?php include "menu_m.htm"; ?>
<div class="main-content">
	<div class="panel-content" style="margin-top: 50px;">
	<div class="main-content-area" style="margin-top: -10px;">	
              <div class="row">
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                           <span><font color="#000000"><a href="add_1.php">平台处理</a></font></span>
                            <h4><a href="add_1.php"><button>模式一</button></a></h4>
                            <i class="fa fa-area-chart blue-bg"></i>
                            <h5><font color="#000000">用户上传1张图片，平台自动对图片进行模糊处理，比较简单</font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <span><font color="#000000"><a href="add_2.php">自行设计</a></font></span>
                            <h4><a href="add_2.php"><button>模式二</button></a></h4>
                            <i class="fa fa-clock-o red-bg"></i>
                            <h5><font color="#000000">需要用户自行上传2张图片，分别对应打赏前的图像和打赏后的图像</font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6" >
                    <div class="widget" >
                        <div class="quick-report-widget">
                            <span><font color="#000000"><a href="add_3.php">跳转网页</a></font></span>
                            <h4 class="number"><a href="add_3.php"><button>模式三</button></a></h4>
                            <i class="fa fa-usd green-bg"></i>
                            <h5><font color="#000000">与前两种模式不同，图片打赏后将跳转到一个指定网页或者显示一段文字</font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
            </div>		  
	</div>
	</div>
</div>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>