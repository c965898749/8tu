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
	<title>模式三 上传图片</title>
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
	<table width="804" height="240" border="0">
	  <tr>
		<td width="125"><div align=right style="margin-right: 10px;">价格：</div><br></td>
		<td colspan="2"><input type="text" id="price_id"  placeholder="最小金额为0.01" onfocus="f(1)"; onblur="f(2)"; name="price">&nbsp;元&nbsp;&nbsp;
		 <a href="#" id="example" rel="popover"><font color="green">价格？</font></a>
                    <span id="price_text" ></span> <!--<br>（价格可以输入小数，如：0.2（元），6.6（元）等等，最小金额为0.01元。）--><br>（提示：价格一旦设定，则无法修改）
		</td>
	  </tr>
	  <tr>
		<td><div align=right style="margin-right: 10px;">图片：</div></td>
		<td width="500">                    
			<input type="file" id="upfile1_id" name="upfile1" accept="image/gif,image/jpeg,image/png, image/jpg" >
                    <span id="upfile1_text" style="color: red"></span>
		</td>
		<td width="108" rowspan="2">
		
		</td>
	  </tr>
	  <tr height="100">
		<td><div align=right style="margin-right: 10px;">网址或文字：</div></td>
		<td colspan="2">
			<textarea id="upfile2_id" name="upfile2" style="width:300px;height:80px;" cols=40 rows=4></textarea>
			<span id="upfile2_text" style="color: red"></span><br>（以&nbsp;http&nbsp;开头的网址，支付后会自动跳转。其他类的文字，则直接显示）
		</td>
	  </tr>
	  <tr>
	  	<td>
		</td>
		<td colspan="2" >
		    <div align = left id=xuyao1 style="display:block;">
               <input type="button" class="btn btn-primary" onclick="javascript:changeUrl();" value="上传">
            </div>
			<div align = left id=xuyao2 style="display:none;">
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
	<table border="0" >
	  <tr>
		<td>价格：</td>
		<td colspan="2"><input type="text" size="13" id="price_id_m"  placeholder="最小金额为0.01" onfocus="javascript:f_m(1);"; onblur="javascript:f_m(2);"; name="price">&nbsp;&nbsp;元&nbsp;&nbsp;<br><span id="price_text_m" ></span>
		</td>
	  </tr>
	  <tr>
		<td>图片：</td>
		<td><br>
			<input type="file"  id="upfile1_id_m" name="upfile1" accept="image/gif,image/jpeg,image/png, image/jpg" >
                    <span id="upfile1_text_m" style="color: red"></span>
		</td>
	  </tr>
	  <tr>
		<td>网址或文字：</td>
		<td><br><br>
			<textarea id="upfile2_id_m" name="upfile2" style="width:200px;height:80px;" cols=400 rows=4></textarea>
			<span id="upfile2_text_m" style="color: red"></span><br>（以&nbsp;http&nbsp;开头的网址，支付后会自动跳转。其他类的文字，则直接显示）
		</td>
	  </tr>
	  <tr>
		<td colspan="2"><br>
		    <div align = center id="xuyao1_m" style="display:block;">
               <input type="button" class="btn btn-primary" onclick="javascript:changeUrl_m();" value="上传">
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
					<br><br>
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
	function changeUrl()
    {    
		if ( chkForm2(document.form1 ) == false)
		{
			return false;
		}
		
        //定义表单要提交的URL，这里可以根据逻辑动态更改，甚至可以采用<%= url %>这种方式    
        var url="addfile.php?type=3" ;
        //将表单的action的URL更改为我们希望提交的URL    
        document.form1.action=url;
		
		document.getElementById("xuyao1").style.display='none';
		document.getElementById("xuyao2").style.display='block';
		
        //利用Javascript提交表单    
        document.form1.submit();
    }
	
	function changeUrl_m()
    {    
		if ( chkForm2(document.form2 ) == false)
		{
			return false;
		}
		
        //定义表单要提交的URL，这里可以根据逻辑动态更改，甚至可以采用<%= url %>这种方式    
        var url="addfile.php?type=3" ;
        //将表单的action的URL更改为我们希望提交的URL    
        document.form2.action=url;
		
		document.getElementById("xuyao1_m").style.display='none';
		document.getElementById("xuyao2_m").style.display='block';
		
        //利用Javascript提交表单    
        document.form2.submit();
    }
	
    function f(data){
        if(data==1){
            //不能以数字开头，1个汉字为2个字符
            document.getElementById("price_text").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入您的图片价格";
            document.getElementById("price_text").style.color = "#ff0000";
            document.getElementById("price_text").style.fontSize= "16px";
        }else{
            var valueData = document.getElementById("price_id").value;
            if(valueData.length==0){
                //失去光标，用户名不能为空
                document.getElementById("price_text").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+"请输入您的图片价格";
                document.getElementById("price_text").style.color = "#ff0000";
                document.getElementById("price_text").style.fontSize= "16px";
            }else{
                //var regObj =/^(?=.*[1-9])\d+(\.\d{1,2})?$/;
                var regObj =/^(?!0+$)(?!0*\.0*$)\d{1,8}(\.\d{1,2})?$/;
                var boolData = regObj.test(document.getElementById("price_id").value);
                if(!boolData){
                    document.getElementById("price_text").innerHTML ="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请输入正确的图片价格";
                    document.getElementById("price_text").style.color = "#ff0000";
                    document.getElementById("price_text").style.fontSize= "16px";
                    document.getElementById("price_id").value = "";
                }else{
                    document.getElementById("price_text").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;OK";
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
	
	function LTrim(str){ 
	  var i; 
	  for(i=0;i<str.length;i++){
		if(str.charAt(i)!=" ") 
		  break; 
	  } 
	  str = str.substring(i,str.length); 
	  return str; 
	}


    //检测
    function chkForm2(obj){
        //价格
        if(obj.price.value==""){
            $('#price_text').html('');
            obj.price.focus();
            return false;}
        //图片
        if(obj.upfile1.value==""){
            $('#upfile1_text').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;请选择上传图片');
            obj.upfile1.focus();
            return false;}
		if(obj.upfile2.value==""){
            $('#upfile2_text').html('请输入跳转的网址或要显示的文字');
            obj.upfile2.focus();
            return false;}
			
		//历史原因，判断的有点重复了
		
		var str1 = obj.upfile2.value;
		str1 = LTrim(str1);

		if (str1.length >= 200)
		{
			$('#upfile2_text').html('文字太多了，超过限制！');
			obj.upfile2.focus();
            return false;
		}
		/*
		if (str1.length <= 5 || checkcheckURL(str1) == false || str1.indexOf("码") != -1 )
		{
			$('#upfile2_text').html('请输入正确的网络地址');
            obj.upfile2.focus();
            return false;
		}
		var str2 =  str1.substring(0, 5);
		if(str2.toLowerCase().indexOf("http")==-1)
		{
            $('#upfile2_text').html('请输入正确的网络地址');
            obj.upfile2.focus();
            return false;
		}
		*/
		if (checkfiletype(obj.upfile1.value) == false)
		{
			return false;
		}
		
//		obj.upfile2.value = encodeURI( LTrim(obj.upfile2.value) );
        return true;
    }
	
	//检测是否为合法的URL格式
	function checkcheckURL(URL)
	{
		var str=URL;
		//判断URL地址的正则表达式为:http(s)?://([\w-]+\.)+[\w-]+(/[\w- ./?%&=]*)?
		//下面的代码中应用了转义字符"\"输出一个字符"/"
		var Expression=/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;
		var objExp=new RegExp(Expression);
		if(objExp.test(str)==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	} 

    //检测图片格式
    function checkfiletype(value){
        var file = value;
        if(!/\.(gif|jpg|jpeg|png|gif|jpg|png)$/i.test(file)){
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