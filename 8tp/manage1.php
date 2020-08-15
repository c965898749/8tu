<?php
require_once "config.php";

session_start();
ini_set('date.timezone','Asia/Shanghai');

	$login = g_iflogin();
	$sellerid = $login[0];
	
	$type = $_GET['type'];
	
	if ($type == 1)//删除
	{
		$pictureid =  g_xyReplacestring ( $_POST['pictureid'] );
		$price =  g_xyReplacestring( $_POST['price'] );
		$picurl = $_POST['picurl'];
		
		//调用删除图片接口
		$ch = curl_init();
		$url = sprintf("http://web.8tupian.com/api/a.php?act=del&picurl=%s&pid=%s&key=%s", $picurl, trim( $config_8tupian['pid']), trim( $config_8tupian['key_id']) );
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$response = curl_exec($ch);
		
		$ret = json_decode($response, true);
		
		if ($ret['code'] == 0)
		{
			//数据库中的记录可以删除了
			$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
			if (mysqli_connect_errno($con)) 
			{ 
				g_putmessage(0, "系统错误");
			}
			
			$result = mysqli_query($con, "update pictureinformation set ifdelete = 1 where SellerID = $sellerid and PictureID = $pictureid;" );
			if ($result == TRUE)
			{
				mysqli_close($con);
				
				//data文件夹下的文件，也删除
				$price = $price / 100;
				$filename = $sellerid . "_" . $pictureid . "_" . $price ;
				unlink("../data/" . $filename . ".jpg");
				unlink("../data/" . $filename . ".png");
				unlink("../data/" . $filename . ".gif");
				unlink("../data/" . $filename . ".jpeg");
				unlink("../data/" . $filename . "-1.jpg");
				unlink("../data/" . $filename . "-1.png");
				unlink("../data/" . $filename . "-1.gif");
				unlink("../data/" . $filename . "-1.jpeg");
				unlink("../data/" . $filename . "-2.jpg");
				unlink("../data/" . $filename . "-2.png");
				unlink("../data/" . $filename . "-2.gif");
				unlink("../data/" . $filename . "-2.jpeg");
				unlink("../data/" . $filename . ".txt");
			
				g_putmessage(1, "删除成功");
			}
			else
			{
				mysqli_close($con);
				g_putmessage(0, "系统错误2，删除失败");
			}
		}
		else
		{
			g_putmessage(0, $ret['msg']);
		}
	}
	
	if ($type == 2)//备注
	{
		$id = $_POST['id'];
        $beizhu = $_POST['beizhu'];
		
		if ( strlen($beizhu) > 200 )
		{
			g_putmessage(0, "修改失败");
		}
		
		$id = g_xyReplacestring($id);
		$beizhu = g_xyReplacestring($beizhu);
		
		$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
		if (mysqli_connect_errno($con)) 
		{ 
			g_putmessage(0, "系统错误");
		}
		
		$result = mysqli_query($con, "update pictureinformation set beizhu = '$beizhu' where ID= $id;");
		if ($result == TRUE)
		{
			g_putmessage(1, "修改成功");
		}
		else
		{
			g_putmessage(0, "修改失败");
		}
	}
	
	if ($type == 3)//分组
	{
		$id = $_POST['id'];
        $fenzu = $_POST['fenzu'];

		$id = g_xyReplacestring($id);
		$fenzu = g_xyReplacestring($fenzu);
		
		$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
		if (mysqli_connect_errno($con)) 
		{ 
			g_putmessage(0, "系统错误");
		}
	
		if ($fenzu != 0)
		{
			$result = mysqli_query($con, "select count(PictureID) from pictureinformation where ifdelete = 0 and SellerID = $sellerid and Group_id = $fenzu;");
			$row = mysqli_fetch_array($result);
			if ($row == NULL)
			{
				mysqli_free_result($result);
				mysqli_close($con);
				g_putmessage(0, "系统错误1");
			}
		
			if ($row['count(PictureID)'] > $web_config['group_sum'] )
			{
				mysqli_free_result($result);
				mysqli_close($con);
				g_putmessage(0, "修改失败,同一组的图片数量不能超过" . $web_config['group_sum'] . "张");	
			}
		}
			
		$result = mysqli_query($con, "update pictureinformation set Group_id = $fenzu where ID=$id;");
		if ($result == TRUE)
		{
			mysqli_free_result($result);
			mysqli_close($con);
			g_putmessage(1, "修改成功");
		}
		else
		{
			mysqli_close($con);
			g_putmessage(0, "修改失败");
		}
		
	}
?>