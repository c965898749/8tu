<?php
require_once "config.php";

session_start();

ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	$name = $login[1];
	$user = $login[2];

	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con))
	{
		$errortext = "Could not connect: " . mysqli_connect_error();
		header("location:warning.php?status=1&title=$errortext&time=10");
		die(0);
	}

	$pagesize = 10;

	$result1 = mysqli_query($con, "select * from pictureinformation where Sellerid ='{$sellerid}' and ifDelete = 0 order by ID desc;" );
	$zongshu = mysqli_num_rows($result1);
	mysqli_free_result($result1);

	if (  $zongshu % $pagesize == 0)
	{
		$pagecount = $zongshu / $pagesize;
	}
	else
	{
		$pagecount = ( $zongshu - ( $zongshu % $pagesize) ) / $pagesize + 1;
	}

	$page = $_GET["page"];
	if ( $page == "" )
	{
		$page = 1;
	}

	$startnum = ($page - 1) * $pagesize;

	$result1 = mysqli_query($con, "select * from pictureinformation where Sellerid ='{$sellerid}' and ifDelete = 0 order by ID desc limit $startnum, $pagesize ;" );
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
	<title>图片管理</title>
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
<div id="beizhu_kuang" style='position:absolute; z-index:999;top:50%;left:50%;color:#000; background:#FFF;border:1px solid #000;width:350px;height:200px;display:none;'>
备注信息：<br><br>
&nbsp;&nbsp;<textarea id="beizhu_id" name="beizhu_name" style="width:300px;height:80px;" cols=40 rows=44 data-id="0"></textarea>
<br><br>
&nbsp;&nbsp;<input type="button" value="确定" class="queding">
</div>

<div id="fenzu_kuang" style='position:absolute; z-index:999;top:50%;left:50%;color:#000; background:#FFF;border:1px solid #000;width:150px;height:100px;display:none;'>
<br>分组数字：
<input type="text"  style="width:50px;" id="fenzu_id" name="fenzu_name" data-id="0";>
<br><br>
&nbsp;&nbsp;<input type="button" value="确定" class="fenzu_queding" >
</div>

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
    input:focus {
        border: 2px solid #009900;
    }
</style>
<table class="table table-striped">
   <tbody>
        <tr>
            <td>序号</td>
            <td>价格<br>(元)</td>
            <td style="width: 90px;">网络地址<br>(<a href="#" id="example1" rel="popover" data-content="将图片的“网络地址”复制，然后发布到网上。<br>可以直接发布网址或者在网页上以超级链接的形式发布。<br>对于前两种模式生成的图片，也可以将图片地址嵌入到html网页的img标签中" data-original-title=""><font color="blue">如何使用？</font></a>)
			</td>
			<td>操作</td>
			<td style="width: 90px;">上传时间</td>
            <td>付款<br>次数</td>
            <td style="width: 90px;">最后一次<br>付款时间</td>
			<td>备注信息</td>
        </tr>
<?php
	while($row1 = mysqli_fetch_array($result1) )
	{
?>
   <tr>
			<td><?php echo $row1['PictureID']; ?></td>
           <td><?php echo $row1['Price']/100; ?></td>
           <td>
		   <?php echo $row1['PicUrl']; ?>
		   </td>
		   <td>
           <button type="button" onclick="javascript:xychakan('<?php echo $row1['PicUrl'];  ?>');">预览</button>
           <button type="button" onclick="javascript:dow('<?php echo $row1['PicUrl'];  ?>');">下载模板</button>
		   <button type="button" onclick="javascript:xydelete(<?php echo $row1['PictureID']; ?> , <?php echo $row1['Price']; ?>, '<?php echo $row1['PicUrl'];  ?>');">删除</button>
		   </td>
		   <td><?php echo $row1['add_time'] ; ?></td>
           <td><?php echo $row1['PayNum']; ?></td>
           <td><?php echo $row1['LastPayTime']; ?></td>
		   <td>
           <input type="text" name="beizhu" value="<?php echo $row1['beizhu']; ?>" style="width: 120px;"  class="beizhu" data-id="<?php echo $row1['ID']; ?>" id="<?php echo $row1['ID']; ?>_beizhu" style="outline:none " readonly >
           </td>
       </tr>
<?php
	}
?>
   </tbody>
</table>

<nav style='width: 88%;text-align: center;float: left;'>
<ul class="pagination">
<?php
	if ($page <= 0)
	{
		$page = 1;
	}

	if ($pagecount > 1)
	{
		if ($page - 5 > 1 )
		{
			echo "<li><a class=\"first\" href=\"?page=1\">1...</a></li>";
		}

		if ($page > 1)
		{
			$prepage = $page - 1;
			echo  "<li><a class=\"prev\" href=\"?page={$prepage}\"><<</a></li>";
		}

		$i = $page - 5;
		while(1)
		{
			if ($i < 1)
			{
				$i ++;
				continue;
			}

			if ($i > $page + 5 || $i > $pagecount )
			{
				break;
			}

			if ($i == $page)
			{
				echo "<li class=\"active\"><span class=\"current\">{$i}</span></li>";
			}
			else
			{
				echo "<li><a class=\"num\" href=\"?page={$i}\">{$i}</a></li>";
			}

			$i ++;
		}

		if ($page < $pagecount)
		{
			$nextpage = $page + 1;
			echo "<li><a class=\"next\" href=\"?page={$nextpage}\">>></a></li>";
		}

		if ($page + 5 < $pagecount )
		{
			echo "<li><a class=\"end\" href=\"?page={$pagecount}\">{$pagecount}</a></li>";
		}
	}
?>
</ul>
</nav>
	</div>

</div>
</div>
<div class="divMobile">
<?php include "menu_m.htm"; ?>
<div class="main-content">
    <div class="panel-content" style="margin-top: 50px;">
        <div class="main-content-area" style="margin-top: -10px;">

<div class="row">
  <div class="col-sm-12">
   <div class="widget">
        <div class="widget-title">
             <h3>图片管理</h3>
             <span><?php echo $zongshu; ?>张</span>
        </div>
        <div class="task-graph panel-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                          <th>序号</th>
                          <th>价格</th>
                          <th>图片</th>
                          <th>备注</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
	$result1 = mysqli_query($con, "select * from pictureinformation where Sellerid ='{$sellerid}' and ifDelete = 0 order by ID desc limit $startnum, $pagesize ;" );

	while($row1 = mysqli_fetch_array($result1) )
	{
?>
                       <tr>

						  <input type="hidden" id="<?php echo $row1['ID']; ?>_pictureid_m" value="<?php echo $row1['PictureID']; ?>" />
						  <input type="hidden" id="<?php echo $row1['ID']; ?>_picurl_m" value="<?php echo $row1['PicUrl']; ?>" />
						  <input type="hidden" id="<?php echo $row1['ID']; ?>_add_time_m" value="<?php echo $row1['add_time']; ?>" />
						  <input type="hidden" id="<?php echo $row1['ID']; ?>_paysuccessnum_m" value="<?php echo $row1['PayNum']; ?>" />
						  <input type="hidden" id="<?php echo $row1['ID']; ?>_lastpaytime_m" value="<?php echo $row1['LastPayTime']; ?>" />
						  <input type="hidden" id="<?php echo $row1['ID']; ?>_date_m" value="<?php echo date('Y-m-d H:i:s'); ?>" />

                          <td><span class="label label-default" ><?php echo $row1['PictureID']; ?></span></td>
                          <td><?php echo $row1['Price']/100; ?></td>
                          <td>
						  <span class="label label-success" onclick="chakan_m(<?php echo $row1['ID']; ?>, <?php echo $row1['Price']/100; ?>);">查看</span>
                          </td>
                          <td onclick="beizhu_m(<?php echo $row1['ID']; ?>);" >
						  <input type="text" name="beizhu_m" id="<?php echo $row1['ID']; ?>_beizhu_m"  value="<?php echo $row1['beizhu']; ?>"  size="10"  style="outline:none " readonly >
                          </td>

						</tr>
<?php
	}

	mysqli_free_result($result1);
	mysqli_close($con);
?>

                     </tbody>
                </table>
            </div>

<ul class="pagination">
<?php
	if ($page <= 0)
	{
		$page = 1;
	}

	if ($pagecount > 1)
	{
		if ($page - 1 > 1 )
		{
			echo "<li><a class=\"first\" href=\"?page=1\">1...</a></li>";
		}

		if ($page > 1)
		{
			$prepage = $page - 1;
			echo  "<li><a class=\"prev\" href=\"?page={$prepage}\"><<</a></li>";
		}

		$i = $page - 1;
		while(1)
		{
			if ($i < 1)
			{
				$i ++;
				continue;
			}

			if ($i > $page + 1 || $i > $pagecount )
			{
				break;
			}

			if ($i == $page)
			{
				echo "<li class=\"active\"><span class=\"current\">{$i}</span></li>";
			}
			else
			{
				echo "<li><a class=\"num\" href=\"?page={$i}\">{$i}</a></li>";
			}

			$i ++;
		}

		if ($page < $pagecount)
		{
			$nextpage = $page + 1;
			echo "<li><a class=\"next\" href=\"?page={$nextpage}\">>></a></li>";
		}

		if ($page + 1 < $pagecount )
		{
			echo "<li><a class=\"end\" href=\"?page={$pagecount}\">{$pagecount}</a></li>";
		}
	}
?>
</ul>


			</div>
    </div>
</div>
        </div>


    </div>
</div>
</div>
</div>

<div style="display:none;" id="beizhu_dlg_m">
<textarea id="beizhu_txt_m" cols=40 rows=44 style="margin-left:10px;margin-right:10px;margin-top:10px;height:80px;"></textarea>
</div>

<div id="chakan_dlg_m" style="margin-left:10px;margin-right:10px;margin-top:10px;display:none;" >
<input type="hidden" id="price_m" value="" />
序号:&nbsp;&nbsp;
<span id="id_m"></span><br><br>
网络地址:&nbsp;&nbsp;<span class="label label-warning" onclick="javascript:m_copy();";>点击复制</span></td>
<br>
<span id="picurl_m"></span><br><br>
上传时间:<br>
<span id="add_time_m"></span><br><br>
付款次数:&nbsp;&nbsp;
<span id="paysuccessnum_m"></span><br><br>
最后付款时间:<br>
<span id="lastpaytime_m"></span><br><br>
数据更新时间:<br>
<b><span id="date_m"></span></b><br><br>
<div style="font-size:20px">
<span class="label label-success" onclick="javascript:m_chakan();";>查看</span>&nbsp;&nbsp;&nbsp;&nbsp;
<span class="label label-success" onclick="javascript:m_share();";>分享</span>&nbsp;&nbsp;&nbsp;&nbsp;
<span class="label label-success" onclick="javascript:m_delete();";>删除</span>&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<br><br>
</div>

<script type="text/javascript">
function chakan_m(id, price)
{
	document.getElementById("id_m").innerHTML = document.getElementById(id + "_pictureid_m").value;
	document.getElementById("price_m").value = price;

	document.getElementById("picurl_m").innerHTML = document.getElementById(id + "_picurl_m").value;

	document.getElementById("add_time_m").innerHTML = document.getElementById(id + "_add_time_m").value;
	document.getElementById("paysuccessnum_m").innerHTML = document.getElementById(id + "_paysuccessnum_m").value;
	document.getElementById("lastpaytime_m").innerHTML = document.getElementById(id + "_lastpaytime_m").value;
//	document.getElementById("fenzu_m").innerHTML = document.getElementById(id + "_group_id_m").value;
	document.getElementById("date_m").innerHTML = document.getElementById(id + "_date_m").value;

	layer.open({
		type:1,
			  title: '图片信息：'
			, btnAlign: 'r'
			, content: $('#chakan_dlg_m')
			, closeBtn: 0
			, btn: ['关闭']
			,yes: function (index, layero)
			{
				layer.close(index);
			}
		});
}

function m_copy()
{
	const copyStr = document.getElementById("picurl_m").innerHTML;

	const input = document.createElement('input');
	input.setAttribute('readonly', 'readonly');
	input.setAttribute('value', document.getElementById("picurl_m").innerHTML);
	document.body.appendChild(input);

	var u = navigator.userAgent;
	if (!!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/) )
	{
		//ios
		input.setSelectionRange(0, 9999);
	}
	else
	{
		input.select();
	}

	if (document.execCommand('copy'))
	{
		layer.msg("复制成功！(如果失败，请手动复制)");
	}
	else
	{
		layer.msg("复制失败，请手动复制");
	}

	document.body.removeChild(input);
}

function m_chakan()
{
	layer.alert("请复制图片的网络地址，然后粘贴到浏览器里查看");
	/*
	var ifchuli = 0;
	if (document.getElementById("ifchuli_m").value == 1 || document.getElementById("ifchuli2_m").value == 1)
	{
		ifchuli = 1;
	}

	xychakan(ifchuli , document.getElementById("picurl_m").innerHTML);
	*/
}

function m_share()
{
	layer.alert("请复制图片的网络地址，通过网络地址进行分享。<br><br><b>注意！！要分享网址，不能分享图片，否则扫码支付无效！！");
}

function m_delete()
{
	xydelete(document.getElementById("id_m").innerHTML , document.getElementById("price_m").value * 100, document.getElementById("picurl_m").innerHTML );
}

	function beizhu_m(id)
	{
		value = document.getElementById(id + "_beizhu_m").value;
		document.getElementById("beizhu_txt_m").value = value;
		layer.open({
			type:1
			, title: '备注信息：'
			, btnAlign: 'r'
			, content: $('#beizhu_dlg_m')
			, closeBtn: 0
			, btn: ['确定']
			,yes: function (index, layero)
			{
				var beizhu = document.getElementById("beizhu_txt_m").value;

				if (beizhu == value || beizhu.length > 200 || id == "0")
				{
					//不用变
				}
				else
				{
					$.post("manage1.php?type=2", {id: id, beizhu: beizhu}, function (data)
					{
						var obj = JSON.parse(data);
						if (obj.status == 1)
						{
							document.getElementById(id + "_beizhu_m").value = beizhu;
						}
						else
						{
							window.parent.layer.msg(obj.info);
						}
					})
				}

				layer.close(index);
			}
		});

	}
    function dow(u){

        location.href='aaaa.php?picurl='+u;
        // $.get("aaaa.php",{picurl:u}, function(data)
        // {
        //
        // })
    }

	function xychakan(url)
	{
		layer.alert("提示：不能将图片或者网页保存到本地再发布，那样扫码支付无效。<br><br>正确的做法是：复制图片的网络地址，然后通过这个网络地址在网上发布或者点击下载模板发布。",
		function (index) {
			layer.close(index);
			window.open(url);
		});
	}

	function xydelete(g, p, u)
	{
		if (confirm("确定删除吗？"))
		{
			$.post("manage1.php?type=1",{pictureid:g, price:p, picurl:u}, function(data)
			{
				var obj = JSON.parse(data);
				if(obj.status == 1)
				{
					alert(obj.info);
					window.location.reload();
				}
				else
				{
					alert(obj.info);
				}
			})
        }
        else
		{
 //           alert("点击了取消");
        }

	}

function getTop(e)
{
	var offset=e.offsetTop;
	if(e.offsetParent!=null) offset+=getTop(e.offsetParent);
	return offset;
}
function getLeft(e){
var offset=e.offsetLeft;
if(e.offsetParent!=null) offset+=getLeft(e.offsetParent);
return offset;
}

    $(function(){
     $(".tijiao").click(function(){

	 	var fenzu_id = $(this).attr("data-id") + "_group_id";
		document.getElementById("fenzu_id").value = document.getElementById(fenzu_id).value;
		document.getElementById("fenzu_id").setAttribute("data-id", $(this).attr("data-id") );

		document.getElementById("fenzu_kuang").style.left =  getLeft( document.getElementById(fenzu_id) ) - 150
		 + "px" ;
		document.getElementById("fenzu_kuang").style.top = getTop( document.getElementById(fenzu_id) ) + "px";

		document.getElementById("fenzu_kuang").style.display="block";
     });

$(".fenzu_queding").click(function()
{
	var id = document.getElementById("fenzu_id").getAttribute("data-id");
	var fenzu_id = id + "_group_id";
	var fenzu = document.getElementById("fenzu_id").value;
	 if (fenzu == null || fenzu.length > 12  || id == "0")
	 {
		document.getElementById("fenzu_kuang").style.display="none";
		return ;
	 }

	 if (fenzu == document.getElementById(fenzu_id).value)
	 {
		document.getElementById("fenzu_kuang").style.display="none";
		return ;
	 }

	$.post("manage1.php?type=3", {id: id, fenzu: fenzu}, function (data) {
		var obj = JSON.parse(data);
		if (obj.status == 1)
		{
			document.getElementById(fenzu_id).value = fenzu;
		}
		else
		{
			alert(obj.info);
		}
	})

	document.getElementById("fenzu_kuang").style.display="none";
});
	 $(".beizhu").click(function(){
		var beizhu_id = $(this).attr("data-id") + "_beizhu";
		document.getElementById("beizhu_id").value = document.getElementById(beizhu_id).value;
		document.getElementById("beizhu_id").setAttribute("data-id", $(this).attr("data-id") );

		document.getElementById("beizhu_kuang").style.left =  getLeft( document.getElementById(beizhu_id) )
		- 350 + "px" ;
		document.getElementById("beizhu_kuang").style.top = getTop( document.getElementById(beizhu_id) ) + "px";

		document.getElementById("beizhu_kuang").style.display="block";

     });

$(".queding").click(function()
{
	var id = document.getElementById("beizhu_id").getAttribute("data-id");
	var beizhu_id = id + "_beizhu";
	var beizhu = document.getElementById("beizhu_id").value;
	if (beizhu == null || beizhu.length > 200  || id == "0")
	{
		document.getElementById("beizhu_kuang").style.display="none";
		return ;
	}

	if (beizhu == document.getElementById(beizhu_id).value)
	{
		document.getElementById("beizhu_kuang").style.display="none";
		return ;
	}

	$.post("manage1.php?type=2", {id: id, beizhu: beizhu}, function (data)
	{
		var obj = JSON.parse(data);
		if (obj.status == 1)
		{
			document.getElementById(beizhu_id).value = beizhu;
		}
		else
		{
			alert(obj.info);
		}
	})

	document.getElementById("beizhu_kuang").style.display="none";
});


		$("#example").popover({placement:'left'/*,trigger: 'focus'*/});
		$("#example1").popover({placement:'left'/*,trigger: 'focus'*/});
    });
</script>
<?php include "foot.htm"; ?>
</body>
<!-- www.8tupian.com -->
</html>
