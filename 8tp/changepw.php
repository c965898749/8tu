<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	$name = $login[1];
	$user = $login[2];

if ( $_POST["password"] &&  $_POST["password1"] && $_POST["password2"])
{
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con))
	{
		$errortext = "Could not connect: " . mysqli_connect_error();
		header("location:warning.php?status=1&title=$errortext&time=10");
		die(0);
	}

	//修改密码
	$password = $_REQUEST["password"];
	$password1 = $_REQUEST["password1"];
	$password2 = $_REQUEST["password2"];

	$result=mysqli_query($con, "select * from sellerinformation where SellerID ='{$sellerid}' ;" );
	$row = mysqli_fetch_array($result);
	if ($row['Password'] != md5(trim($password)) )
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=操作失败！原密码不正确!&time=3");
		die(0);
	}

    $password3=$password1;
	$password1 = md5(trim($password1));
	$password2 = md5(trim($password2));
	if ( $password1 != $password2 )
	{
		mysqli_free_result($result);
		mysqli_close($con);
		header("location:warning.php?status=1&title=两次输入的密码不一致!&time=3");
		die(0);
	}

	mysqli_query($con, "update sellerinformation set password='{$password1}' where SellerID=$sellerid ;");
	mysqli_query($con, "update user set userpassword='{$password3}' where username ='{$row['Name']} ';");

	mysqli_free_result($result);
	mysqli_close($con);
	header("location:warning.php?status=0&title=操作成功&time=1&url=information.php");
	die(0);
}

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
	<title>修改密码</title>
	<link href="./css/bootstrap/bootstrap.css" type="text/css" rel="stylesheet">
	<script type="text/javascript" src="./js/jquery.min.js"></script>
	<script src="./js/bootcss/layer.min.js"></script>
    <link rel="stylesheet" href="./css/font/cheatin.css">
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
<form action="changepw.php" method="post" onsubmit="return chkForm(this);">
    <table class="table">
        <caption><h3>修改密码</h3></caption>
        <tbody>
        <tr>
            <td><label>原密码：</label>
                <input class="form-control input_text" type="password" placeholder="请输入原密码" name="password"   onfocus="checkPw(1)" onblur="checkPw(2)" id="pw1">

                <span id="pw1info"></span>
            </td>
        </tr>
        <tr>
            <td><label>新密码：</label>
                <input class="form-control input_text" type="password" placeholder="请输入新密码" name="password1"  onfocus="checkPw_x(2)" onblur="checkPw_x(3)"  id="pw3">
                <span id="pw3info"></span>
            </td>
        </tr>
        <tr>
            <td><label>再次输入新密码：</label>
                <input class="form-control input_text" type="password" placeholder="请再次输入新密码" name="password2" value="" id="pw2">
                <span id="pw2info"></span>
            </td>
        </tr>
        <tr>
            <td>
                <button type="submit" class="btn btn-primary" style="margin-left: 25%;">修改</button></td>
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
             <form id="changepwd-form" class="" role="form" data-toggle="validator" method="POST"action="changepw.php" onsubmit="return chkForm_m(this);">
                      <div class="row">
                          <div class="col-sm-12">
                              <div class="form-group">
                                  <b>旧密码</b>
                                   <input type="password" class="form-control" id="oldpassword" name="password" value="" data-rule="required" placeholder="旧密码">
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <b>新密码</b>
                                  <input type="password" class="form-control" id="newpassword" name="password1" value="" data-rule="required" placeholder="新密码" />
                              </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group">
                                  <b>重复密码</b>
                                  <input type="password" class="form-control" id="renewpassword" name="password2" value="" data-rule="required" placeholder="确认新密码" />
                              </div>
                          </div>
                      </div>
                  <div class="text-right">
                    <button class="btn btn-info" type="submit">修改密码</button>
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
    function checkPw(data){
        var strData = document.getElementById("pw1").value;
        if(data==1){
            //请填写密码
            document.getElementById("pw1info").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入您的原密码";
            document.getElementById("pw1info").style.color = "#ff0000";
            document.getElementById("pw1info").style.fontSize= "16px";
        }else{
            if(strData.length==0){
                document.getElementById("pw1info").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原密码不能为空";
                document.getElementById("pw1info").style.color = "#ff0000";
                document.getElementById("pw1info").style.fontSize= "16px";
            }else{
                if(strData.length<6||strData.length>16){
                    document.getElementById("pw1info").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入符合规格的密码";
                    document.getElementById("pw1info").style.color = "#ff0000";
                    document.getElementById("pw1info").style.fontSize= "16px";
                    document.getElementById("pw1").value = "";
                }
                else{
                    document.getElementById("pw1info").innerHTML = "";

                }
            }

        }
    }
    //新密码
    function checkPw_x(data){
        var strData = document.getElementById("pw3").value;
        if(data==2){
            //请填写密码
            document.getElementById("pw3info").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请填写最少6最多16位数的密码";
            document.getElementById("pw3info").style.color = "#ff0000";
            document.getElementById("pw3info").style.fontSize= "16px";
        }else{
            if(strData.length==0){
                document.getElementById("pw3info").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;密码不能为空";
                document.getElementById("pw3info").style.color = "#ff0000";
                document.getElementById("pw3info").style.fontSize= "16px";
            }else{
                //弱
                var objData1 = /^\d{6,16}$|^[A-Za-z]{6,16}$|^[#\?\.\$@%]{6,16}$/;
                //中
                var objData2 = /^[A-Za-z0-9]{6,16}$|^[A-Za-z#\?\.\$@%]{6,16}$|^[0-9#\?\.\$@%,]{6,16}$/;
                //强
                //var objData2 = /^[A-Za-z0-9#\?\.\$@%]{6,16}$/;

                var boolData = objData1.test(strData);
                if(objData1.test(strData)){
                    document.getElementById("pw3info").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK,密码强度为弱";
                    document.getElementById("pw3info").style.color = "#00ff00";
                    document.getElementById("pw3info").style.fontSize= "16px";
                }else if(objData2.test(strData)){
                    document.getElementById("pw3info").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK,密码强度一般";
                    document.getElementById("pw3info").style.color = "#00ff00";
                    document.getElementById("pw3info").style.fontSize= "16px";
                }else if(strData.length<6||strData.length>16){
                    document.getElementById("pw3info").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入符合规格的密码";
                    document.getElementById("pw3info").style.color = "#ff0000";
                    document.getElementById("pw3info").style.fontSize= "16px";
                    document.getElementById("pw3").value = "";
                }
                else{
                    document.getElementById("pw3info").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK，密码强度为强";
                    document.getElementById("pw3info").style.color = "#00ff00";
                    document.getElementById("pw3info").style.fontSize= "16px";
                }
            }
        }
    }
    //确认密码
    $(function(){
        $("#pw2").blur(function(){
            //alert("");
            var pw1 = $("#pw3").val();
            var pw2 = $("#pw2").val();
            if(pw2!=pw1){
                $('#pw2').val("");
                $('#pw2info').html("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;两次输入密码不一致").css("color","#f00");
            }else{
                $('#pw2info').html("");
            }
        });
    })

    //检测密码
    function chkForm(obj){
        //
        if(obj.password1.value==""){
            $('#pw3info').html('');
            obj.password1.focus();
            return false;}

        if(obj.password1.value!=obj.password2.value){
            $('#pw2info').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;两次输入密码不一致');
            obj.password2.focus();
            return false;}

    }

	//检测密码 m
	function chkForm_m(obj)
	{
		if (obj.password.value=="" || obj.password.value.length < 6)
		{
			alert("请输入旧密码，密码最少6位数");
			return false;
		}

		if (obj.password1.value=="" || obj.password1.value.length < 6 )
		{
			alert("请输入新密码，密码最少六位数");
			return false;
		}

		if (obj.password2.value=="" || obj.password2.value.length < 6 )
		{
			alert("请再次输入新密码，密码最少六位数");
			return false;
		}

        if(obj.password1.value!=obj.password2.value)
		{
            alert("两次输入的密码不一致");
            return false;
		}
    }
</script>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>
