<?php
require_once "config.php";

session_start();
ini_set('date.timezone','Asia/Shanghai');

function shengchengwebaddress( $type) 
{
	global $web_config; 
	
	$mtime=explode(' ',microtime());

	if ($type == 0)
	{
		$yushu=$mtime[1] % 4;
		
		switch($yushu)
		{
		case 0:
			$address = $web_config['web_set1'];
			break;
		case 1:
			$address = $web_config['web_set2'];
			break;
		case 2:
			$address = $web_config['web_set3'];
			break;
		case 3:
			$address = $web_config['web_set4'];
			break;
		default:
			$address = $web_config['web_set1'];
			break;
		}
	}
	
	if ($type == 1)
	{
		$yushu=$mtime[1] % 2;
		
		switch($yushu)
		{
		case 0:
			$address = $web_config['s_web_set1'];
			break;
		case 1:
			$address = $web_config['s_web_set2'];
			break;
		default:
			$address = $web_config['s_web_set1'];
			break;
		}
	}
	
	return $address;
}

	$login = g_iflogin();
	$sellerid = $login[0];
	
	$type = $_GET['type'];
	
	if ($type == 1)//模式一
	{
		$price = $_POST['price'] ;
		$pri = preg_match('/^(?!0+$)(?!0*\.0*$)\d{1,8}(\.\d{1,2})?$/',$price);
		if ( !$pri )
		{
			header("location:warning.php?status=1&title=上传失败！上传时间过长（可能是图片过大或者网速过慢）而导致上传终止!&time=10");
			die(0);
		}

		if ( $price == "" || !is_numeric($price) )
		{
			header("location:warning.php?status=1&title=请输入正确的图片价格&time=5");
			die(0);
		}

		if ($price < $web_config['price_min'] || $price > $web_config['price_max'] )
		{
			header("location:warning.php?status=1&title=上传失败！图片价格要最小" . $web_config['price_min'] . "元，最大" .$web_config['price_max']. "元&time=5");
			die(0);
		}
				
		if (empty($_FILES) || $_FILES['error'] == 4) 
		{
			header("location:warning.php?status=1&title=请上传正确的文件&time=5");
			die(0);
        }
		
		//判断文件类型，大小等
		if ( is_uploaded_file( $_FILES['upfile']['tmp_name']) == FALSE)
		{
			header("location:warning.php?status=1&title=发生错误!&time=5");
			die(0);
		}
		
		$upfile = $_FILES["upfile"];
		
		//获取数组里面的值
		$name = $upfile["name"]; // 上传文件的文件名
		$filetype = $upfile["type"]; //上传文件的类型
		$size = $upfile["size"]; //上传文件的大小
		$tmp_name = $upfile["tmp_name"]; //上传文件的临时存放路径
		
		if ($size > $web_config['file_size'] )
		{
			header("location:warning.php?status=1&title=上传失败，图片的大小超过2M了&time=15");
			die(0);
		}
		
		if($filetype != "image/pjpeg" && $filetype != "image/jpeg" && $filetype != "image/gif" && $filetype != "image/png" && $filetype != "image/x-png")
		{
			header("location:warning.php?status=1&title=图片类型不对1。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$externsion=pathinfo($name, PATHINFO_EXTENSION);
		
		if ( strcasecmp($externsion, "jpg") != 0 && strcasecmp($externsion, "png") != 0 && strcasecmp($externsion, "gif") != 0
				&& strcasecmp($externsion, "jpeg") != 0)
		{
			header("location:warning.php?status=1&title=图片类型不对2。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
	
		$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
		if (mysqli_connect_errno($con)) 
		{ 
			$errortext = "Could not connect: " . mysqli_connect_error(); 
			header("location:warning.php?status=1&title=$errortext&time=10");
			die(0);
		}
		
		$result = mysqli_query($con, "select PictureID from pictureinformation where SellerID = $sellerid order by PictureID desc;");
		$row = mysqli_fetch_array($result);
		$fileid = 1;
		if ($row == NULL)
		{
			$fileid = 1;
		}
		else
		{
			$fileid = $row['PictureID'] + 1;
		}
		
		mysqli_free_result($result);
		
		$filename = sprintf("data/%d_%d_%s.%s", $sellerid, $fileid, $price, $externsion);
		
		copy($tmp_name, "../" . $filename);
		$explode  = explode("8tp/addfile.php", $_SERVER["REQUEST_URI"] );
		$fileurl='http://'."admin.yimem.com:59754" . $explode[0] . "/" . $filename;

		//调用免审核 上传图片接口
		$ch = curl_init();
		$url = sprintf("http://web.8tupian.com/api/c.php?act=up1&pic=%s&price=%d&pid=%s&key=%s", $fileurl, $price * 100, trim( $config_8tupian['pid']), trim( $config_8tupian['key_id']) );
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		
		$ret = json_decode($response, true);
		
		if ($ret['code'] != 0)
		{
			unlink( "../" . $filename );
			mysqli_close($con);
			header("location:warning.php?status=1&title={$ret['msg']}&time=15");
			die (0);
		}	
		
		//记入到数据库中
		$price = $price * 100;
		$picurl = $ret['picurl'];
		$datetime = date('Y-m-d H:i:s');
		$result = mysqli_query($con, "insert into pictureinformation(Price, PicUrl, SellerID, PictureID, PayNum,  add_time) values($price, '$picurl', $sellerid, $fileid, 0,  '$datetime');" );
		
		if ($result == FALSE)
		{
			unlink( "../" . $filename );
			mysqli_close($con);
			header("location:warning.php?status=1&title=系统错误1&time=3");
			die(0);
		}
		
		mysqli_close($con);
		
		header("location:warning.php?status=0&title=上传成功&time=1&url=manage.php");
		die(0);
	}
	
	if ($type == 2)//模式二
	{
		$price = $_POST['price'];
		$pri = preg_match('/^(?!0+$)(?!0*\.0*$)\d{1,8}(\.\d{1,2})?$/',$price);
		if ( !$pri )
		{
			header("location:warning.php?status=1&title=上传失败！上传时间过长（可能是图片过大或者网速过慢）而导致上传终止!&time=10");
			die(0);
		}

		if ( $price == "" || !is_numeric($price) )
		{
			header("location:warning.php?status=1&title=请输入正确的图片价格&time=5");
			die(0);
		}
		
		if ($price < $web_config['price_min'] || $price > $web_config['price_max'] )
		{
			header("location:warning.php?status=1&title=上传失败！图片价格要最小" . $web_config['price_min'] . "元，最大" .$web_config['price_max']. "元&time=5");
			die(0);
		}
		
		if (empty($_FILES) || $_FILES['error'] == 4) 
		{
			header("location:warning.php?status=1&title=请上传正确的文件&time=5");
			die(0);
        }
		
		//判断文件类型，大小等
		if ( is_uploaded_file( $_FILES['upfile1']['tmp_name']) == FALSE)
		{
			header("location:warning.php?status=1&title=发生错误1!&time=5");
			die(0);
		}
		
		if ( is_uploaded_file( $_FILES['upfile2']['tmp_name']) == FALSE)
		{
			header("location:warning.php?status=1&title=发生错误2!&time=5");
			die(0);
		}
		
		$upfile=$_FILES["upfile1"]; 
		
		$name=$upfile["name"];//上传文件的文件名 
		$filetype=$upfile["type"];//上传文件的类型 
		$size=$upfile["size"];//上传文件的大小 
		$tmp_name1=$upfile["tmp_name"];//上传文件的临时存放路径 
		
		if ($size > $web_config['file_size'] )
		{
			header("location:warning.php?status=1&title=上传失败，第一张图片的大小超过2M了&time=15");
			die(0);
		}
		
		if($filetype != "image/pjpeg" && $filetype != "image/jpeg" && $filetype != "image/gif" && $filetype != "image/png" && $filetype != "image/x-png")
		{
			header("location:warning.php?status=1&title=第一张图片文件类型不对1。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$externsion1=pathinfo($name, PATHINFO_EXTENSION);
		
		if ( strcasecmp($externsion1, "jpg") != 0 && strcasecmp($externsion1, "png") != 0 && strcasecmp($externsion1, "gif") != 0
				&& strcasecmp($externsion1, "jpeg") != 0)
		{
			header("location:warning.php?status=1&title=第一张图片文件类型不对2。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$upfile=$_FILES["upfile2"];
		
		$name = $upfile["name"]; // 上传文件的文件名
		$filetype = $upfile["type"]; //上传文件的类型
		$size = $upfile["size"]; //上传文件的大小
		$tmp_name2 = $upfile["tmp_name"]; //上传文件的临时存放路径
		
		if ($size > $web_config['file_size'] )
		{
			header("location:warning.php?status=1&title=上传失败，第二张图片的大小超过2M了&time=15");
			die(0);
		}
		
		if($filetype != "image/pjpeg" && $filetype != "image/jpeg" && $filetype != "image/gif" && $filetype != "image/png" && $filetype != "image/x-png")
		{
			header("location:warning.php?status=1&title=第二张图片文件类型不对1。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$externsion2=pathinfo($name, PATHINFO_EXTENSION);
		
		if ( strcasecmp($externsion2, "jpg") != 0 && strcasecmp($externsion2, "png") != 0 && strcasecmp($externsion2, "gif") != 0
				&& strcasecmp($externsion2, "jpeg") != 0)
		{
			header("location:warning.php?status=1&title=第二张图片文件类型不对2。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
		if (mysqli_connect_errno($con)) 
		{ 
			$errortext = "Could not connect: " . mysqli_connect_error(); 
			header("location:warning.php?status=1&title=$errortext&time=10");
			die(0);
		}
		
		$result = mysqli_query($con, "select PictureID from pictureinformation where SellerID = $sellerid order by PictureID desc;");
		$row = mysqli_fetch_array($result);
		$fileid = 1;
		if ($row == NULL)
		{
			$fileid = 1;
		}
		else
		{
			$fileid = $row['PictureID'] + 1;
		}
		
		mysqli_free_result($result);
		
		$filename1 = sprintf("data/%d_%d_%s-1.%s", $sellerid, $fileid, $price, $externsion1);
		copy($tmp_name1, "../" . $filename1);
		
		$filename2 = sprintf("data/%d_%d_%s-2.%s", $sellerid, $fileid, $price, $externsion2);
		copy($tmp_name2, "../" . $filename2);
		
		$explode  = explode("8tp/addfile.php", $_SERVER["REQUEST_URI"] );
		$fileurl1 ='http://'. "admin.yimem.com:59754" . $explode[0] . "/" . $filename1;
		$fileurl2 ='http://'. "admin.yimem.com:59754" . $explode[0] . "/" . $filename2;
		
		//调用免审核 上传图片接口
		$ch = curl_init();
		$url = sprintf("http://web.8tupian.com/api/c.php?act=up2&pic=%s&pic2=%s&price=%d&pid=%s&key=%s", $fileurl1, $fileurl2, $price * 100, trim( $config_8tupian['pid']), trim( $config_8tupian['key_id']) );
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		
		$ret = json_decode($response, true);
		
		if ($ret['code'] != 0)
		{
			unlink( "../" . $filename1 );
			unlink( "../" . $filename2 );
			mysqli_close($con);
			header("location:warning.php?status=1&title={$ret['msg']}&time=15");
			die (0);
		}
		
		//记入到数据库中
		$price = $price * 100;
		$picurl = $ret['picurl'];
		$datetime = date('Y-m-d H:i:s');
		$result = mysqli_query($con, "insert into pictureinformation(Price, PicUrl, SellerID, PictureID, PayNum,  add_time) values($price, '$picurl', $sellerid, $fileid, 0,  '$datetime');" );
		
		if ($result == FALSE)
		{
			unlink( "../" . $filename1 );
			unlink( "../" . $filename2 );
			mysqli_close($con);
			header("location:warning.php?status=1&title=系统错误1&time=3");
			die(0);
		}
		
		mysqli_close($con);
		
		header("location:warning.php?status=0&title=上传成功&time=1&url=manage.php");
		die(0);
	}
	
	if ($type == 3)//模式三
	{
		$price = $_POST['price'];
		$pri = preg_match('/^(?!0+$)(?!0*\.0*$)\d{1,8}(\.\d{1,2})?$/',$price);
		if ( !$pri )
		{
			header("location:warning.php?status=1&title=上传失败！上传时间过长（可能是图片过大或者网速过慢）而导致上传终止!&time=10");
			die(0);
		}

		if ( $price == "" || !is_numeric($price) )
		{
			header("location:warning.php?status=1&title=请输入正确的图片价格&time=5");
			die(0);
		}
		
		if ($price < $web_config['price_min'] || $price > $web_config['price_max'] )
		{
			header("location:warning.php?status=1&title=上传失败！图片价格要最小" . $web_config['price_min'] . "元，最大" .$web_config['price_max']. "元&time=5");
			die(0);
		}
		
		$http_url = $_POST['upfile2'];
		if ( $http_url == "")
		{
			header("location:warning.php?status=1&title=请输入网络地址&time=5");
			die(0);
		}
		
		if (strlen($http_url) > 600 )
		{
			header("location:warning.php?status=1&title=文字太多了，超过数量限制！&time=5");
			die(0);
		}
		
		$http_url = str_replace("&amp;", "&", $http_url);
		if (empty($_FILES) || $_FILES['error'] == 4) 
		{
			header("location:warning.php?status=1&title=请上传正确的文件&time=5");
			die(0);
        }
		
		//判断文件类型，大小等
		if ( is_uploaded_file( $_FILES['upfile1']['tmp_name']) == FALSE)
		{
			mysqli_close($con);
			header("location:warning.php?status=1&title=发生错误1!&time=5");
			die(0);
		}
		
		$upfile=$_FILES["upfile1"]; 
		
		//获取数组里面的值 
		$name=$upfile["name"];//上传文件的文件名 
		$filetype =$upfile["type"];//上传文件的类型 
		$size=$upfile["size"];//上传文件的大小 
		$tmp_name1=$upfile["tmp_name"];//上传文件的临时存放路径
		
		if ($size > $web_config['file_size'] )
		{
			header("location:warning.php?status=1&title=上传失败，图片太大了，超过2M了&time=15");
			die(0);
		}
		
		if($filetype != "image/pjpeg" && $filetype != "image/jpeg" && $filetype != "image/gif" && $filetype != "image/png" && $filetype != "image/x-png")
		{
			header("location:warning.php?status=1&title=图片文件类型不对1。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$externsion=pathinfo($name, PATHINFO_EXTENSION);
		
		if ( strcasecmp($externsion, "jpg") != 0 && strcasecmp($externsion, "png") != 0 && strcasecmp($externsion, "gif") != 0 && strcasecmp($externsion, "jpeg") != 0)
		{
			header("location:warning.php?status=1&title=图片文件类型不对2。只能上传jpg,png,gif格式的文件！&time=15");
			die(0);
		}
		
		$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
		if (mysqli_connect_errno($con)) 
		{ 
			$errortext = "Could not connect: " . mysqli_connect_error(); 
			header("location:warning.php?status=1&title=$errortext&time=10");
			die(0);
		}
		
		$result = mysqli_query($con, "select PictureID from pictureinformation where SellerID = $sellerid order by PictureID desc;");
		$row = mysqli_fetch_array($result);
		$fileid = 1;
		if ($row == NULL)
		{
			$fileid = 1;
		}
		else
		{
			$fileid = $row['PictureID'] + 1;
		}
		
		mysqli_free_result($result);
		
		$filename1 = sprintf("data/%d_%d_%s.%s", $sellerid, $fileid, $price, $externsion);
		copy($tmp_name1, "../" . $filename1);
		
		$filename2 = sprintf("data/%d_%d_%s.txt", $sellerid, $fileid, $price );
		file_put_contents("../"  . $filename2, $http_url, FILE_APPEND);
		
		$explode  = explode("8tp/addfile.php", $_SERVER["REQUEST_URI"] );
		$fileurl1 ='http://'. "admin.yimem.com:59754" . $explode[0] . "/" . $filename1;
		$fileurl2 ='http://'. "admin.yimem.com:59754" . $explode[0] . "/" . $filename2;
		
		//调用免审核 上传图片接口
		$ch = curl_init();
		$url = sprintf("http://web.8tupian.com/api/c.php?act=up4&pic=%s&texturl=%s&price=%d&pid=%s&key=%s", $fileurl1, $fileurl2, $price * 100, trim( $config_8tupian['pid']), trim( $config_8tupian['key_id']) );
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		
		$ret = json_decode($response, true);
		
		if ($ret['code'] != 0)
		{
			unlink( "../" . $filename1 );
			unlink( "../" . $filename1 );
			mysqli_close($con);
			header("location:warning.php?status=1&title={$ret['msg']}&time=15");
			die (0);
		}	
		
		//记入到数据库中
		$price = $price * 100;
		$picurl = $ret['picurl'];
		$datetime = date('Y-m-d H:i:s');
		$result = mysqli_query($con, "insert into pictureinformation(Price, PicUrl, SellerID, PictureID, PayNum,  add_time) values($price, '$picurl', $sellerid, $fileid, 0,  '$datetime');" );
		
		if ($result == FALSE)
		{
			unlink( "../" . $filename1 );
			unlink( "../" . $filename1 );
			
			mysqli_close($con);
			header("location:warning.php?status=1&title=系统错误1&time=3");
			die(0);
		}
		
		mysqli_close($con);
		
		header("location:warning.php?status=0&title=上传成功&time=1&url=manage.php");
		die(0);
	}
?>