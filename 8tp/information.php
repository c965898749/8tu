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

if ( $_POST["name"] )
{
	//修改资料
	$post_email = g_xyReplacestring( $_REQUEST["email"] );
	$post_contactqq = g_xyReplacestring( $_REQUEST["contactqq"] );
	$post_phone = g_xyReplacestring( $_REQUEST["phone"] );	
	
	$post_shoukuantype = g_xyReplacestring( $_REQUEST["shoukuantype"]);
	
	$post_zfb_payee = g_xyReplacestring( $_REQUEST["zfb_payee"]);
	$post_zfb_account = g_xyReplacestring( $_REQUEST["zfb_account"]);
	
	mysqli_query($con, "update sellerinformation set Email = '$post_email' , ContactQQ = '$post_contactqq', Phone='$post_phone'  where Sellerid=$sellerid ;" );
	mysqli_query($con, "update jiesuanbiao set ShouKuanType = '$post_shoukuantype' , zfb_account='$post_zfb_account', zfb_payee='$post_zfb_payee'  where Sellerid=$sellerid ;" );
			
	mysqli_close($con);
	header("location:warning.php?status=0&title=修改成功&time=1&url=index.php");
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
		header("location:warning.php?status=1&title=系统原因，数据不全b&time=3&url=login.html");
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
	<title>修改资料</title>
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
<form action="information.php" method="post" onsubmit="return CheckEditInfo();">
    <table class="table">
        <caption><h3>修改个人资料</h3></caption>
        <tbody>
        <tr>
            <td><label>用户名：</label><input class="form-control input_text" type="text" placeholder="用户名" name="name" value="<?php echo $row['Name']; ?>" id="username" readonly="readonly">&nbsp;&nbsp;&nbsp;<a href="changepw.php"  class=""><font color="red">修改密码</font></a></td>
        </tr>
        <tr>
            <td><label>邮箱：</label><input class="form-control input_text" type="text" placeholder="Email" name="email"  value="<?php echo $row['Email']; ?>" id="email"></td>
        </tr>
		<tr>
            <td><label>联系QQ：</label><input class="form-control input_text" type="text" placeholder="QQ" name="contactqq"  value="<?php echo $row['ContactQQ']; ?>" id="contactqq"></td>
        </tr>
        <tr>
            <td><label>手机号：</label><input class="form-control input_text" type="text" placeholder="手机号" name="phone" value="<?php echo $row['Phone']; ?>" id="phone"></td>
        </tr>
            <tr>
                <td>
                    <label>选择收款的方式：</label>
                    <select name="shoukuantype" class="select form-control" style="width: 200px; display: inline;" id="select">
                        <option value="0" selected>请选择</option>
                        <option value="1" <?php echo $row1['ShouKuanType'] == 1 ? 'selected' : '' ; ?> >支付宝</option>
                       <!-- <option value="2" >微信</option>
                        <option value="3"  disabled="disabled">银行卡</option>-->
                    </select>
                </td>
            </tr>
		<tr class="zfb" style="display: none">
            <td><label>支付宝账号：</label><input class="form-control input_text" type="text" placeholder="支付宝账号" name="zfb_account" id="zfb_account" value="<?php echo $row1['zfb_account']; ?>"></td>
        </tr>
        <tr class="zfb"  style="display: none">
            <td><label>支付宝真实姓名：</label><input class="form-control input_text" type="text" placeholder="支付宝用户名" name="zfb_payee" id="zfb_payee" value="<?php echo $row1['zfb_payee']; ?>"></td>
        </tr>
        <tr>
		<tr class="bank" style="display: none">
                <td><label>收款账号：</label><input class="form-control input_text" type="text" placeholder="收款账号" name="bank_account" id="bank_account" value="<?php echo $row1['bank_account']; ?>"></td>
        </tr>
        <tr class="bank" style="display: none">
            <td><label>收款人：</label><input class="form-control input_text" type="text" placeholder="收款人" name="bank_payee" value="<?php echo $row1['bank_payee']; ?>" id="bank_payee" ></td>
        </tr>
        <tr>
            <td><button type="submit" class="btn btn-primary" style="margin-left: 25%;"  >修改</button></td>
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
            
<div class="row">
  <div class="col-md-12">

    <div class="widget white no-padding">
      <div class="widget-tabs">
           <ul class="nav nav-tabs">
           </ul>
           <div class="tab-content">
              <form id="profile-form" class="" role="form" data-toggle="validator" method="POST" action="information.php" onsubmit="return CheckEditInfo_m();">
                      <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <b>用户名：</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="changepw.php"  class=""><font color="red">修改密码</font></a>
                                  <input id="username_m" data-rule="required" class="form-control" name="name" type="text" value="<?php echo $row['Name']; ?>" readonly="readonly">
                              </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <b>邮箱：</b>
                                  <input id="email_m" data-rule="required" class="form-control" name="email" type="text" value="<?php echo $row['Email']; ?>" placeholder="Email">
                              </div>
                          </div>
                         <div class="col-sm-12">
                              <div class="form-group">
                                  <b>联系QQ：</b>
                                  <input id="contactqq_m" data-rule="required" class="form-control" name="contactqq" type="text" value="<?php echo $row['ContactQQ']; ?>" placeholder="QQ">
                              </div>
                          </div>
						  <div class="col-sm-12">
                              <div class="form-group">
                                  <b>手机号：</b>
                                  <input id="phone_m" data-rule="required" class="form-control" name="phone" type="text" value="<?php echo $row['Phone']; ?>" placeholder="手机号">
                              </div>
                          </div>
						  <div class="col-sm-12">
                              <div class="form-group">
                                  <b>收款方式：</b>
									<select id="m_select" data-rule="required" class="form-control" name="shoukuantype" onchange="javascript:m_select_change();">
										<option value="0" selected>请选择</option>
										<option value="1" <?php echo $row1['ShouKuanType'] == 1 ? 'selected' : '' ; ?> >支付宝</option>
									</select>
                              </div>
                          </div>
						  <div class="col-sm-12" style="display: none" id="m_account">
                              <div class="form-group">
                                  <b>支付宝账号：</b>
                                  <input id="zfb_account_m" data-rule="required" class="form-control" name="zfb_account" type="text" value="<?php echo $row1['zfb_account']; ?>" placeholder="支付宝账号">
                              </div>
                          </div>
						  <div class="col-sm-12" style="display: none" id="m_payee" >
                              <div class="form-group">
                                  <b>支付宝真实姓名：</b>
                                  <input id="zfb_payee_m" data-rule="required" class="form-control" name="zfb_payee" type="text" value="<?php echo $row1['zfb_payee']; ?>" placeholder="支付宝真实姓名">
                              </div>
                          </div>
                      </div>
                  <div class="text-right">
                    <button class="btn btn-info" type="submit">修改</button>
                  </div>
                  <div class="col-md-12">

                                                            </div>
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
<script type="text/javascript" src="./js/member.js"></script>
<script type="text/javascript">
    $("#select").change(function(){
        var selectedValue=$(this).val();

        if(selectedValue==0){
            $(".zfb").hide();
        }
        if(selectedValue==1){
            $(".zfb").show();
        }
    });
	
function m_select_change()
{
	var selectedValue = document.getElementById("m_select").value;
	if(selectedValue==0)
	{
		document.getElementById("m_account").style.display="none";
		document.getElementById("m_payee").style.display="none";
	}
	
	if(selectedValue==1)
	{
		document.getElementById("m_account").style.display="block";
		document.getElementById("m_payee").style.display="block";
	}
}

	
//	window.onload=function()
//	{
		if( <?php echo $row1['ShouKuanType']; ?> == 1){
            $(".zfb").show();
			
			document.getElementById("m_account").style.display="block";
            document.getElementById("m_payee").style.display="block";
        }
//	} 

</script>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>