<?php
require_once("xorpay_config.php");

function request_by_curl($remote_server, $post_string) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $remote_server);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $data = curl_exec($ch);
  curl_close($ch);
 
  return $data;
}

    $price = $_POST['WIDtotal_fee'];
    $name = $_POST['WIDsubject'];
    $pay_type = "alipay";
    $order_id = $_POST['WIDout_trade_no'];
	
    $notify_url = $xorpay_config['notify_url'];
    $secret = $xorpay_config['secret'];

		$api_url = "https://xorpay.com/api/pay/". $xorpay_config['aid'];


    function sign_xorpay($data_arr) {
        return md5(join('',$data_arr));
    }

    $sign = sign_xorpay(array($name, $pay_type, $price, $order_id, $notify_url, $secret));

		$post_string = sprintf("name=%s&pay_type=%s&price=%s&order_id=%s&notify_url=%s&sign=%s", $name, $pay_type, $price, $order_id, $notify_url, $sign);
		$ret =  request_by_curl($api_url, $post_string);
		$ret = json_decode($ret,true);
		if ($ret['status'] == "ok")
		{
?>
		<script language="javascript">window.location.href = "<?php echo $ret['info']['qr']; ?>";</script>
<?php
		}
		else
		{
			echo $ret['status'];
		}
?>	
