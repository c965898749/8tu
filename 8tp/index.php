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

	$result=mysqli_query($con, "select * from sellerinformation where Sellerid ='{$sellerid}' ;" );
	$row = mysqli_fetch_array($result);
	if($row == NULL)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=需要登录&time=3&url=login.html");
		die(0);
	}

	$result1 = mysqli_query($con, "select * from jiesuanbiao where Sellerid ='{$sellerid}' ;" );
	$row1 = mysqli_fetch_array($result1);
	if($row1 == NULL)
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=系统原因，数据不全&time=3&url=login.html");
		die(0);
	}

	mysqli_free_result($result);
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
	<title>个人中心</title>
    <link href="./css/bootstrap/bootstrap.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="./js/jquery.min.js"></script>
	<script src="./js/bootcss/layer.min.js"></script>
	<script src="./js/bootcss/jquery.cookie.min.js"></script>
    <link rel="stylesheet" href="./8tupian_css/font/cheatin.css">
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
    <div id="left" class="divPC">
<?php include "menu.htm"; ?>
    </div>
    <!-- right -->
    <div id="right">
      <style type="text/css">
        label{width:20%;text-align: left; margin-right:20px;}
          .input_text{width: 250px; display: inline;}
      </style>
		<table class="table" style="margin-top: 30px;">
			<tbody>
			<tr>
				<td><label>用户名：</label><span class="input_text"><?php echo $row['Name']; ?></span></td>
			</tr>
			<tr>
				<td><label>邮箱：</label><span class="input_text"><?php echo $row['Email']; ?></span></td>
			</tr>
			<tr>
				<td><label>联系QQ：</label><span class="input_text"><?php echo $row['ContactQQ']; ?></span></td>
			</tr>
			<tr>
				<td><label>手机号码：</label><span class="input_text"><?php echo $row['Phone']; ?></span></td>
			</tr>
			<tr>
				<td><label>收款方式：</label><span class="input_text">
				<?php
					if ($row1['ShouKuanType'] == 0)
					{
						echo "（左边，\"修改资料\"中设置）";
					}
					else
					{
						echo "支付宝";
					}
				?>
				</span></td>
			</tr>
			<tr>
				<td><label>收款账号：</label><span class="input_text">
					<?php
							if ($row1['ShouKuanType'] != 0 )
							{
								echo $row1['zfb_account'];
							}
							else
							{
								echo "（左边，\"修改资料\"中设置）";
							}
					?></span></td>
			</tr>
			<?php
				if ($web_config['iftg'] == 1 )
				{
			?>
<!--			<tr>-->
<!--				<td><label>下线推广(<a href="tg.php"><font color="red">点击进入</font></a>)：</label><span class="input_text">下线人数:&nbsp;--><?php //echo $row1['tgnum']; ?><!--&nbsp;&nbsp;推广提成:&nbsp;--><?php //echo $row1['tgmoney']/100; ?><!--元&nbsp;&nbsp;</span></td>-->
<!--			</tr>-->
			<?php
				}
			?>
			<tr>
				<td><label>账户余额：</label><span class="input_text">
				<?php echo $row1['Balance']/100; ?>&nbsp;&nbsp;元</span>
				</td>
			</tr>
				<tr>
				<td><label>结算中：</label><font color="red"><span class="input_text">
				<?php echo $row1['Jiesuanbalance']/100; ?></span></font>
				</td>
			</tr>
			<tr>
				<td><label>结算：</label><button class="btn btn-info" data-money="<?php echo $row1['Balance']; ?>" data-type="<?php echo $row1['IfJieSuan']; ?>"  data-shoukuantype="<?php echo $row1['ShouKuanType']; ?>" data-bank_account="<?php echo $row1['bank_account']; ?>" data-zfb_account="<?php echo $row1['zfb_account']; ?>" type="submit">
				<?php
					if ($row1['IfJieSuan'] == 1)
					{
						echo "结算中";
					}
					else
					{
						echo "立即结算";
					}
				?>
				</button>
				</td>
			</tr>
			<tr>
				<td><font color=#ADADAD>
				提交结算时间：<?php echo $row1['TiJiaoJieSuanTime']; ?> &nbsp;&nbsp;
				上一次结算金额：<?php echo $row1['LastJieSuanBalance']/100; ?> 元&nbsp;&nbsp;
				历史总结算次数：<?php echo $row1['JieSuanNum']; ?>  &nbsp;&nbsp;
				历史总结算金额：<?php echo $row1['JieSuanSum']/100; ?></font>
				</td>
			</tr>
			</tbody>
		</table>
		<div id="top_tishi" align=center style="font-size:35px;font-weight:900;"></div>
    </div>
</div>
</div>
<div class="divMobile">
<?php include "menu_m.htm"; ?>
<div class="main-content">
    <div class="panel-content" style="margin-top: 50px;">
	<div class="main-content-area" style="margin-top: -10px;">
		    <div class="row">
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget" >
                            <h5><font color="#000000">用户名：<h5><?php echo $row['Name']; ?></h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">邮箱：<h5><?php echo $row['Email']; ?></h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">联系QQ：<h5><?php echo $row['ContactQQ']; ?></h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">手机号码：<h5><?php echo $row['Phone']; ?></h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">收款方式：<h5>
							<?php
								if ($row1['ShouKuanType'] == 0)
								{
									echo "（\"修改资料\"中设置）";
								}
								else
								{
									echo "支付宝";
								}
							?>
							</h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">收款账号：<h5>
							<?php
								if ($row1['ShouKuanType'] != 0 )
								{
									echo $row1['zfb_account'];
								}
								else
								{
									echo "（\"修改资料\"中设置）";
								}
							?>
							</h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<?php
					if ($web_config['iftg'] == 1 )
					{
				?>
<!--				<div class="col-md-3 col-sm-6">-->
<!--                    <div class="widget">-->
<!--                        <div class="quick-report-widget">-->
<!--                            <h5><font color="#000000">下线推广(<a href="tg.php"><font color="red"><b>点击进入</b></font></a>)：-->
<!--							<h5>-->
<!--							下线人数:&nbsp;--><?php //echo $row1['tgnum']; ?><!--&nbsp;&nbsp;推广提成:&nbsp;--><?php //echo $row1['tgmoney']/100; ?><!--元&nbsp;&nbsp;-->
<!--							</h5></font></h5>-->
<!--                        </div>-->
<!--                    </div><!-- Widget -->-->
<!--                </div>-->
				<?php
					}
				?>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">账户余额：<h5><?php echo $row1['Balance']/100; ?>&nbsp;&nbsp;元</h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">结算中：<h5><font color="red"><?php echo $row1['Jiesuanbalance']/100; ?></font>&nbsp;&nbsp;元</h5></font></h5>

						</div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <div class="quick-report-widget">
                            <h5><font color="#000000">
							<h5><button class="btn btn-info" data-money="<?php echo $row1['Balance']; ?>" data-type="<?php echo $row1['IfJieSuan']; ?>"  data-shoukuantype="<?php echo $row1['ShouKuanType']; ?>" data-bank_account="<?php echo $row1['bank_account']; ?>" data-zfb_account="<?php echo $row1['zfb_account']; ?>" type="submit">
							<?php
								if ($row1['IfJieSuan'] == 1)
								{
									echo "结算中";
								}
								else
								{
									echo "立即结算";
								}
							?>
							</button>
							</h5></font></h5>
                        </div>
                    </div><!-- Widget -->
                </div>
				<div class="col-md-3 col-sm-6">
                    <div class="widget">
						<font color=#ADADAD>
						结算记录：<br>

						提交结算时间：<?php echo $row1['TiJiaoJieSuanTime']; ?> <br>
						上一次结算金额：<?php echo $row1['LastJieSuanBalance']/100; ?> 元<br>
						历史总结算次数：<?php echo $row1['JieSuanNum']; ?><br>
						历史总结算金额：<?php echo $row1['JieSuanSum']/100; ?><br>
						</font>
                    </div><!-- Widget -->
                </div>

            </div>
	</div>
	</div>
</div>
</div>
<script type="text/javascript">
   $('.btn-info').on('click',function(){
        var type = $(this).data('type');
        var amount = $(this).data('money');
        var zfb_account =  $(this).data('zfb_account');
        var bank_account =  $(this).data('bank_account');
		var xuyaotype = $(this).data('shoukuantype');
        if(type == 1){
            return false;
        }
		if(xuyaotype == 0)
		{
		    alert('请绑定收款账户！');
            return false;
		}
        if(zfb_account == '' && xuyaotype == 1){
            alert('请绑定收款账户！');
            return false;
        }
		if(bank_account == '' && xuyaotype == 3){
            alert('请绑定收款账户！');
            return false;
        }
        if(amount < 1000){
            alert('您的余额不足！当余额大于10元时，就可以申请结算了！');
            return false;
        }

        $.post("cash.php",function(data){
			var obj = JSON.parse(data);
			if(obj.status==0)
            {
                window.location.reload();
            }
            alert(obj.info);
        });
    });
</script>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>
