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
	<title>模式一 上传图片</title>
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
<br>
<form class="form-horizontal" role="form" name="form1" method="post" enctype="multipart/form-data" style="margin-top: 30px;" >
	<table width="804" height="200" border="0">
	  <tr>
		<td width="125"><div align=right style="margin-right: 10px;">图片价格</div><br></td>
		<td colspan="2"><input type="text" id="price_id"  placeholder="最小金额为0.01" onfocus="javascript:f(1);"; onblur="javascript:f(2);"; name="price">&nbsp;元&nbsp;&nbsp;
		 <a href="#" id="example" rel="popover"><font color="green">价格？</font></a>
                    <span id="price_text" ></span> 
					<br>（提示：价格一旦设定，则无法修改）
		</td>
	  </tr>
	  <tr>
		<td><div align=right style="margin-right: 10px;">选择图片：</div></td>
		<td width="300">                    
			<input type="file" id="upfile_id" name="upfile" accept="image/gif,image/jpeg,image/png, image/jpg" >
                    <span id="upfile_text" style="color: red"></span>
		</td>
	  </tr>
	  <tr>
		<td colspan="2">
		    <div align = center id="xuyao1" style="display:block;">
               <input type="button" class="btn btn-primary" onclick="javascript:chkForm(this);" value="上传">
            </div>
			<div align = center id="xuyao2" style="display:none;">
               正在上传 &nbsp;&nbsp;<img src="./images/shangchuan.gif" alt="上传中">
            </div>
		</td>
	  </tr>
	</table>
</form>
	</div>
</div>
</div>
<div class="divMobile">
<?php include "menu_m.htm"; ?>
<div class="main-content">
    <div class="panel-content" style="margin-top: 50px;">
	<form  name="form2" method="post" enctype="multipart/form-data" >
	<font color=#FF0000><b>上传的图片大小不能超过2M</b></font>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:layer.alert('<h4>图片太大，如何压缩？</h4><br>最简单的办法，将图片发到微信上（不要发送原图），然后再从微信上把图片保存下来');" target="_blank"><font color="green">图片太大，如何压缩？</font></a><br><br>
	<table border="0" height="200">
	  <tr>
		<td>图片价格：</td>
		<td colspan="2"><input type="text" size="13" id="price_id_m"  placeholder="最小金额为0.01" onfocus="javascript:f_m(1);"; onblur="javascript:f_m(2);"; name="price">&nbsp;&nbsp;元&nbsp;&nbsp;<br><span id="price_text_m" ></span> 
		</td>
	  </tr>
	  <tr>
		<td>选择图片：</td>
		<td>                    
			<input type="file" id="upfile_id_m" name="upfile" accept="image/gif,image/jpeg,image/png, image/jpg" >
                    <span id="upfile_text_m" style="color: red"></span>
		</td>
	  </tr>
	  <tr>
		<td colspan="2">
		    <div align = center id="xuyao1_m" style="display:block;">
               <input type="button" class="btn btn-primary" onclick="javascript:chkForm_m(this);" value="上传">
            </div>
			<div align = center id="xuyao2_m" style="display:none;">
               正在上传 &nbsp;&nbsp;<img src="./images/shangchuan.gif" alt="上传中">
            </div>
		</td>
	  </tr>
	</table>
	</form>
				<div>
                    
					<font color=#0000FF>
					<br>
					价格：需要打赏的金额，可以自行设定。如0.2元，就输入“0.2”，6.6元就输入“6.6”，最小金额为0.01元。
					</font>
					<br><br>
					<font color=#FF0000>
					提示：价格一旦设定，则无法修改
					</font>
                    
                </div>

	</div>
</div>
</div>
<script type="text/javascript">
function f(data){
	if(data==1){
		//不能以数字开头，1个汉字为2个字符
		document.getElementById("price_text").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入您的图片价格";
		document.getElementById("price_text").style.color = "#ff0000";
		document.getElementById("price_text").style.fontSize= "16px";
	}else{
		var valueData = document.getElementById("price_id").value;
		if(valueData.length==0){
			//失去光标，用户名不能为空
			document.getElementById("price_text").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+"请输入您的图片价格";
			document.getElementById("price_text").style.color = "#ff0000";
			document.getElementById("price_text").style.fontSize= "16px";
		}else{
			//var regObj =/^(?=.*[1-9])\d+(\.\d{1,2})?$/;
			var regObj =/^(?!0+$)(?!0*\.0*$)\d{1,8}(\.\d{1,2})?$/;
			var boolData = regObj.test(document.getElementById("price_id").value);
			if(!boolData){
				document.getElementById("price_text").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入正确的图片价格";
				document.getElementById("price_text").style.color = "#ff0000";
				document.getElementById("price_text").style.fontSize= "16px";
				document.getElementById("price_id").value = "";
			}else{
				document.getElementById("price_text").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK";
				document.getElementById("price_text").style.color = "#00ff00";
				document.getElementById("price_text").style.fontSize= "16px";
			}

		}
	}
}
	
function f_m(data){
	if(data==1){
		//不能以数字开头，1个汉字为2个字符
		document.getElementById("price_text_m").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入您的图片价格";
		document.getElementById("price_text_m").style.color = "#ff0000";
		document.getElementById("price_text_m").style.fontSize= "16px";
	}else{
		var valueData = document.getElementById("price_id_m").value;
		if(valueData.length==0){
			//失去光标，用户名不能为空
			document.getElementById("price_text_m").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+"请输入您的图片价格";
			document.getElementById("price_text_m").style.color = "#ff0000";
			document.getElementById("price_text_m").style.fontSize= "16px";
		}else{
			//var regObj =/^(?=.*[1-9])\d+(\.\d{1,2})?$/;
			var regObj =/^(?!0+$)(?!0*\.0*$)\d{1,8}(\.\d{1,2})?$/;
			var boolData = regObj.test(document.getElementById("price_id_m").value);
			if(!boolData){
				document.getElementById("price_text_m").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入正确的图片价格";
				document.getElementById("price_text_m").style.color = "#ff0000";
				document.getElementById("price_text_m").style.fontSize= "16px";
				document.getElementById("price_id_m").value = "";
			}else{
				document.getElementById("price_text_m").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK";
				document.getElementById("price_text_m").style.color = "#00ff00";
				document.getElementById("price_text_m").style.fontSize= "16px";
			}

		}
	}
}

//检测
function chkForm(obj)
{
	$value = document.getElementById("price_id").value;
	//价格
	if($value=="")
	{
		alert("请先设定图片价格！");
		$('#price_text').html('');
		document.getElementById("price_id").focus();
		return false;
	}
	
	if(document.form1.upfile.value=="")
	{
		$('#upfile_text').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请选择上传图片');
		obj.upfile.focus();
		return false;
	}
		
	if (checkfiletype("upfile_id") == false)
	{
		return false;
	}
	
	//定义表单要提交的URL，这里可以根据逻辑动态更改，甚至可以采用<%= url %>这种方式    
	var url="addfile.php?type=1" ;
	//将表单的action的URL更改为我们希望提交的URL    
	document.form1.action=url;
	
	document.getElementById("xuyao1").style.display='none';
	document.getElementById("xuyao2").style.display='block';
	
	//利用Javascript提交表单    
	document.form1.submit();
	
	return true;
}

function chkForm_m(obj)
{
	$value = document.getElementById("price_id_m").value;
	//价格
	if($value=="")
	{
		alert("请先设定图片价格！");
		$('#price_text_m').html('');
		document.getElementById("price_id_m").focus();
		return false;
	}
	
	if(document.form2.upfile.value=="")
	{
		$('#upfile_text_m').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请选择上传图片');
		obj.upfile.focus();
		return false;
	}
		
	if (checkfiletype("upfile_id_m") == false)
	{
		return false;
	}
	
	//定义表单要提交的URL，这里可以根据逻辑动态更改，甚至可以采用<%= url %>这种方式    
	var url="addfile.php?type=1" ;
	//将表单的action的URL更改为我们希望提交的URL    
	document.form2.action=url;
	
	document.getElementById("xuyao1_m").style.display='none';
	document.getElementById("xuyao2_m").style.display='block';
	
	//利用Javascript提交表单    
	document.form2.submit();
	
	return true;
}

	function checkfiletype(id)
	{
        var file = document.getElementById(id).value;
        if(!/\.(gif|jpg|jpeg|png|gif|jpg|png)$/i.test(file))
		{
            alert("图片类型必须是 gif,jpg,png 中的一种");
            return false;
        }
		
		return true;
    }
	
	$(function (){ 
	$("#example").popover({ content: "价格是需要打赏的金额，可以自行设定。如0.2元，就输入“0.2”，6.6元就输入“6.6”，最小金额为0.01元。"});
	});
	
</script>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>