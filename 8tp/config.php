<?php
/* *
 * 配置文件
 */

require_once "../config.php";

	//基础配置信息
	$web_config['qq'] = "c965898749";   //客服QQ
	$web_config['email'] = "965898749@qq.com";  //客服邮箱

	$web_config['price_max'] = 99999; //最大图片价格 单位 元
	$web_config['price_min'] = 0.01;  //最小图片价格 单位 元

	$web_config['iftg'] = 1; //是否开启下线推广的功能。1表示开启，0表示关闭
	$web_config['ticheng'] = 0.3;  //下线推广的提成比例

	$web_config['pic_sum'] = 800;  //每个用户最多上传的图片数量
	$web_config['file_size'] = 2097152;  //2M 上传图片 的大小限制



//全局函数，不用修改
function g_sign($data, $key)
{
    ksort($data);
    $sign = md5(urldecode(http_build_query($data)).'&key='.$key);
    return $sign;
}

function g_putmessage($status, $info)
{
    $myObj['status'] = $status;
	$myObj['info'] = $info;
	$myJSON = json_encode($myObj);
	ob_clean();
	echo $myJSON;
	die(0);
}

//返回 sellerid
function g_iflogin()
{
	global $data_config;

	$sellerid = g_xyReplacestring( $_SESSION['user_id'] );
	$name = $sellerid;
	if ($sellerid == "")
	{
		header("location:warning.php?status=1&title=需要登录&time=3&url=login.html");
		die(0);
	}
	else
	{
		$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
		if (mysqli_connect_errno($con))
		{
			$errortext = "Could not connect: " . mysqli_connect_error();
			header("location:warning.php?status=1&title=$errortext&time=10");
			die(0);
		}

		$result=mysqli_query($con, "select * from sellerinformation where SellerID ='{$sellerid}' ;" );
		$row = mysqli_fetch_array($result);
		if($row == NULL)
		{
			mysqli_free_result($result);
			mysqli_close($con);
			header("location:warning.php?status=1&title=需要登录&time=3&url=login.html");
			die(0);
		}

		if ( $row['warning'] != 0)
		{
			mysqli_free_result($result);
			mysqli_close($con);
			header("location:warning.php?status=1&title=需要登录&time=3&url=login.html");
			die(0);
		}

		$name = $row['Name'];

		//更新登录信息
		$xy_client_ip = $_SERVER['REMOTE_ADDR'];
        $xy_lastlandtime = date('Y-m-d H:i:s');
		mysqli_query($con, "update sellerinformation set LastLandIP = '$xy_client_ip' , LastLandTime = '$xy_lastlandtime' where SellerID=$sellerid ;" );

		mysqli_free_result($result);
		mysqli_close($con);
	}

	return array($sellerid, $name);
}

function g_xyReplacestring($q)
{
	$q = str_replace("'","a",$q);
	$q = str_replace("\"","a",$q);
	$q = str_replace("\\","a",$q);
	$q = str_replace("/","a",$q);
	$q = str_replace(";","a",$q);
	$q = str_replace("%","a",$q);
	$q = str_replace("#","a",$q);
	$q = str_replace("(","a",$q);
	$q = str_replace(")","a",$q);
	$q = str_replace("*","a",$q);
	$q = str_replace("&","a",$q);
	$q = str_replace("|","a",$q);
	$q = str_replace("<","a",$q);
	$q = str_replace(">","a",$q);
	$q = str_replace(",","a",$q);
	$q = str_replace("$","a",$q);
	$q = str_replace("^","a",$q);
	$q = str_replace("{","a",$q);
	$q = str_replace("}","a",$q);
	$q = str_replace("[","a",$q);
	$q = str_replace("]","a",$q);
	return $q;
}

//ob_start();

?>
