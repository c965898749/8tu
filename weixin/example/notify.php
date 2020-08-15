<?php
/**
*
* example目录下为简单的支付样例，仅能用于搭建快速体验微信支付使用
* 样例的作用仅限于指导如何使用sdk，在安全上面仅做了简单处理， 复制使用样例代码时请慎重
* 请勿直接直接使用样例对外提供服务
* 
**/
ini_set('date.timezone','Asia/Shanghai');

require_once("../../config.php");

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once "WxPay.Config.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);

		$config = new WxPayConfig();
		$result = WxPayApi::orderQuery($config, $input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	/**
	*
	* 回包前的回调方法
	* 业务可以继承该方法，打印日志方便定位
	* @param string $xmlData 返回的xml参数
	*
	**/
	public function LogAfterProcess($xmlData)
	{
		Log::DEBUG("call back， return xml:" . $xmlData);
		return;
	}
	
	//重写回调处理函数
	/**
	 * @param WxPayNotifyResults $data 回调解释出的参数
	 * @param WxPayConfigInterface $config
	 * @param string $msg 如果回调处理失败，可以将错误信息输出到该方法
	 * @return true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
	 */
	public function NotifyProcess($objData, $config, &$msg)
	{
		$data = $objData->GetValues();
		//TODO 1、进行参数校验
		if(!array_key_exists("return_code", $data) 
			||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
			//TODO失败,不是支付成功的通知
			//如果有需要可以做失败时候的一些清理处理，并且做一些监控
			$msg = "异常异常";
			return false;
		}
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}

		//TODO 2、进行签名验证
		try {
			$checkResult = $objData->CheckSign($config);
			if($checkResult == false){
				//签名错误
				Log::ERROR("签名错误...");
				return false;
			}
		} catch(Exception $e) {
			Log::ERROR(json_encode($e));
		}

		//TODO 3、处理业务逻辑
//		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		
		global $config_8tupian;
		
		$orderid = $data["out_trade_no"];
		$fee = $data["total_fee"];
		
		$postdata = array(
			'orderid'  => $orderid,
			'fee'      => $fee 
		);

		$sign =  sign($postdata, trim( $config_8tupian['key_id'] ) );
		
		$url = sprintf("%s?orderid=%s&fee=%d&sign=%s", $config_8tupian['api_url'], $orderid, $fee , $sign);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		
		global $data_config;
		
        if($response == "success") 
		{
			//回写数据库						
			$con = mysqli_connect($data_config['DB_HOST'], $data_config['DB_USER'], $data_config['DB_PWD'], $data_config['DB_NAME']);
			if (mysqli_connect_errno($con)) 
			{
				die( mysqli_connect_error() );
			}
			
			$sql = sprintf("select * from order_temp where order_id='%s' order by time desc", $orderid  );
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			$picurl = $row['picurl'];
			$order_temp_id = $row['id'];
			$ifsuccess = $row['ifsuccess'];
			if ($ifsuccess > 0)
			{
				echo "success";
				curl_close($ch);
				die(0);
			}
			
			mysqli_free_result($result);
			
			$sql = sprintf("select * from pictureinformation where picurl = '%s'; ", $picurl );
			$result = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($result);
			$sellerid = $row['SellerID'];
			$picid = $row['ID'];
			
			mysqli_free_result($result);
			
			$sql = sprintf("update order_temp set ifsuccess = ifsuccess + 1  where id = %d;", $order_temp_id );
			mysqli_query($con, $sql);
			
			$sql = sprintf("update pictureinformation set paynum = paynum + 1, lastpaytime = '%s'  where id = %d;", date("YmdHis"), $picid  );
			mysqli_query($con, $sql);
		
			$sql = sprintf("update jiesuanbiao set balance = balance + %d  where sellerid = %d;", $fee, $sellerid );
			mysqli_query($con, $sql);
			
			mysqli_close($con);
        }
		else
		{
			return false;

		}
		
		curl_close($ch);
			  
		return true;
	}
}

$config = new WxPayConfig();
//Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle($config, false);
