<?php
require_once("xorpay_config.php");

    $price = $_POST['WIDtotal_fee'];
    $name = $_POST['WIDsubject'];
    $pay_type = "jsapi";
    $order_id = $_POST['WIDout_trade_no'];
	
    $notify_url = $xorpay_config['notify_url'];
    $secret = $xorpay_config['secret'];

		$api_url = "https://xorpay.com/api/cashier/". $xorpay_config['aid'];


    function sign_xorpay($data_arr) {
        return md5(join('',$data_arr));
    }

    $sign = sign_xorpay(array($name, $pay_type, $price, $order_id, $notify_url, $secret));
	
		echo '<html>
			  <head><title>redirect...</title></head>
			  <body>
				  <form id="post_data" action="'.$api_url.'" method="post">
					  <input type="hidden" name="name" value="'.$name.'"/>
					  <input type="hidden" name="pay_type" value="'.$pay_type.'"/>
					  <input type="hidden" name="price" value="'.$price.'"/>
					  <input type="hidden" name="order_id" value="'.$order_id.'"/>
					  <input type="hidden" name="notify_url" value="'.$notify_url.'"/>
					  <input type="hidden" name="sign" value="'.$sign.'"/>
				  </form>
				  <script>document.getElementById("post_data").submit();</script>
			  </body>
			  </html>';

?>
