<?php
require_once("config.php");
require_once("bufpay_config.php");
ini_set('date.timezone','Asia/Shanghai');

	//获取参数
	$fee = trim( $_REQUEST['fee'] );
	$order_id = trim( $_REQUEST['orderid'] );
	$out_trade_no = trim( $_REQUEST['orderid']);
	$picurl = trim( $_REQUEST['picurl'] );
	
	$out_trade_no = str_replace("'","a",$out_trade_no);
	$out_trade_no = str_replace("\"","a",$out_trade_no);
	$out_trade_no = str_replace(";","a",$out_trade_no);
	$out_trade_no = str_replace("*","a",$out_trade_no);
	$out_trade_no = str_replace(",","a",$out_trade_no);
	$out_trade_no = str_replace(" ","a",$out_trade_no);
	$out_trade_no = str_replace("(","a",$out_trade_no);
	$out_trade_no = str_replace(")","a",$out_trade_no);
	$out_trade_no = str_replace("[","a",$out_trade_no);
	$out_trade_no = str_replace("]","a",$out_trade_no);
	$out_trade_no = str_replace("{","a",$out_trade_no);
	$out_trade_no = str_replace("}","a",$out_trade_no);
	
	$picurl = str_replace("'","a",$picurl);
	$picurl = str_replace("\"","a",$picurl);
	$picurl = str_replace(";","a",$picurl);
	$picurl = str_replace("*","a",$picurl);
	$picurl = str_replace(",","a",$picurl);
	$picurl = str_replace(" ","a",$picurl);
	$picurl = str_replace("(","a",$picurl);
	$picurl = str_replace(")","a",$picurl);
	$picurl = str_replace("[","a",$picurl);
	$picurl = str_replace("]","a",$picurl);
	$picurl = str_replace("{","a",$picurl);
	$picurl = str_replace("}","a",$picurl);
	
	$order_id = str_replace("'","a",$order_id);
	$order_id = str_replace("\"","a",$order_id);
	$order_id = str_replace(";","a",$order_id);
	$order_id = str_replace("*","a",$order_id);
	$order_id = str_replace(",","a",$order_id);
	$order_id = str_replace(" ","a",$order_id);
	$order_id = str_replace("(","a",$order_id);
	$order_id = str_replace(")","a",$order_id);
	$order_id = str_replace("[","a",$order_id);
	$order_id = str_replace("]","a",$order_id);
	$order_id = str_replace("{","a",$order_id);
	$order_id = str_replace("}","a",$order_id);
	
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
	$type = "alipay";
	
	if (stripos($user_agent, "MicroMessenger") == TRUE )
	{
		$type = "wechat";
	}
	
	if (stripos($user_agent, "AlipayClient") == TRUE)
	{
		$type = "alipay";
	}
	
	$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
	if (mysqli_connect_errno($con)) 
	{
		die('Could not connect: ' . mysqli_connect_error() );
	}
	$sql = sprintf("insert into order_temp (order_id, picurl, time, ifsuccess) values ('%s', '%s', '%s', 0 ); ", $out_trade_no, $picurl, date("YmdHis")  );
	$result = mysqli_query($con, $sql);
	if ($result == FALSE)
	{
		die('failed!' );
	}
	
	mysqli_close($con);
	
    #注意: 使用之前先到 bufpay 后台上传微信、支付宝App生成的收款二维码

    $price = $fee/100; # 获取充值金额
    $order_id = $order_id;       # 自己创建的本地订单号
    $order_uid = 'hello@hello.com';  # 订单对应的用户id
    $name = '扫码支付';  # 订单商品名称
    $pay_type = $type;    # 付款方式
    $notify_url = $bufpay_config['notify_url'];   # 回调通知地址
    $return_url = $bufpay_config['return_url'];   # 支付成功页面跳转地址

    $secret = $bufpay_config['secret'];     # app secret, 在个人中心配置页面查看
    $api_url = 'https://bufpay.com/api/pay/'.$bufpay_config['aid'];   # 付款请求接口，在个人中心配置页面查看

    function bufpay_sign($data_arr) {
        return md5(join('',$data_arr));
    };

    $sign = bufpay_sign(array($name, $pay_type, $price, $order_id, $order_uid, $notify_url, $return_url, $secret));


echo '<html>
      <head><title>redirect...</title></head>
      <body>
          <form id="post_data" action="'.$api_url.'" method="post">
              <input type="hidden" name="name" value="'.$name.'"/>
              <input type="hidden" name="pay_type" value="'.$pay_type.'"/>
              <input type="hidden" name="price" value="'.$price.'"/>
              <input type="hidden" name="order_id" value="'.$order_id.'"/>
              <input type="hidden" name="order_uid" value="'.$order_uid.'"/>
              <input type="hidden" name="notify_url" value="'.$notify_url.'"/>
              <input type="hidden" name="return_url" value="'.$return_url.'"/>
              <input type="hidden" name="sign" value="'.$sign.'"/>
          </form>
          <script>document.getElementById("post_data").submit();</script>
      </body>
      </html>';
?>