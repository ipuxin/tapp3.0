<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wxpay extends MY_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){
		//$this->view('paytest',$data);
	}

	//提交订单 
	public function orderSubmit(){
		include_once(APPPATH."controllers/wxPay/WxPayPubHelper.php");
		extract($this->input->post());
		$orderError = 0;

		//$OrderId = $OrderId;
		$this->load->model('order_model');
		$OrderInfo = $this->order_model->getOrderInfo(array('OrderId'=>$OrderId,'UserId'=>$this->_member_userId));

		if($OrderInfo['OrderStatus']!=1){
			$return['ErrorCode'] = 250;
			$return['ErrorMsg'] = '订单状态不为未支付！请核对后再支付。';
		}
			/*$OrderTeamInfo = $this->team_model->getTeamInfo($OrderInfo['TeamId']);
			if($OrderTeamInfo['TeamStatus']!=2){
				$return['ErrorCode'] = 250;
				$return['ErrorMsg'] = '此次拼团已结束，请取消订单';
				echo json_encode($return);
				return;
			}*/

		if($OrderInfo){
			//如果是零元活动或者使用优惠券 订单总金额为0
			if($OrderInfo['OrderFee']==0){

				$arr=array(
					'orderId' => $OrderInfo['id'],
					'payType' => '免付',
					'payTradeNo' => '',
					'payAmount' => 0
				);

				$res = $this->order_model->payNotifyWechat($arr);
				$this->returnJson($res);
				return;
			}

			//$OrderInfo['OrderFee'] = 0.01;

			$orderTitle = '【拼一下】'.$OrderInfo['ProductInfo']['ProductName'];
			$orderTitle = str_replace(' ','',$orderTitle);
			$orderTitle = mb_substr($orderTitle,0,32);
			$orderId  = $OrderInfo['id'];
			$orderFee = $OrderInfo['OrderFee'];
			$openId   = $this->_member_openId;
			$time     = time();
			$orderFee = $orderFee*100;
			
			$dataArr = array(
				'openid' =>	$openId,
				'body' =>	$orderTitle,
				'time' =>	$time,
				'out_trade_no' =>	$orderId,
				'total_fee' =>	$orderFee,
			);
			$this->logResult('dataArr:'.json_encode($dataArr));
			
			//构造要请求的参数数组，无需改动
			$jsApi = new JsApi_pub();
			$unifiedOrder = new UnifiedOrder_pub();
			$unifiedOrder->setParameter("openid","$openId");//商品描述
			$unifiedOrder->setParameter("body",$orderTitle);//商品描述
			$out_trade_no = WxPayConf_pub::APPID."$time";
			$unifiedOrder->setParameter("out_trade_no","$orderId");//商户订单号 
			$unifiedOrder->setParameter("total_fee","$orderFee");//总金额
			$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
			$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
			$prepay_id = $unifiedOrder->getPrepayId();

			//使用jsapi调起支付
			$jsApi->setPrepayId($prepay_id);

			$jsApiParameters = $jsApi->getParameters();
			//$data['jcode']   = json_decode($jsApiParameters);
			$res['JsonCode']  = $jsApiParameters;
			if($OrderInfo['OrderType']==1){
				$res['TeamId'] = 0;
			}elseif($OrderInfo['OrderType']==2){
				$res['TeamId'] = $OrderInfo['id'];
			}else{
				$res['TeamId'] = $OrderInfo['TeamId'];
			}
			$res['ErrorCode'] = 0;

			//$this->logResult('json',$jsApiParameters);
			$this->logResult('json:'.json_encode($OrderInfo));

		}else{
			$res['ErrorCode'] = 233;
			$res['ErrorMsg']  = "交易发起失败";
		}

		echo json_encode($res);
	}

	//异步回调
	public function orderNotify(){
		include_once(APPPATH."controllers/wxPay/WxPayPubHelper.php");
		
		//使用通用通知接口
		$notify = new Notify_pub();

		//存储微信的回调
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];	
		$notify->saveData($xml);

		//验证签名，并回应微信。
		//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		//尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
		}
		$returnXml = $notify->returnXml();
		echo $returnXml;

		$this->logResult('xml:'.$xml.'\r\n');

		if($notify->checkSign() == TRUE){
			if ($notify->data["return_code"] == "FAIL") {
				$this->logResult("【通信出错】\r\n");
			}
			elseif($notify->data["result_code"] == "FAIL"){
				$this->logResult("【业务出错】\r\n");
			}
			else{
				$acctoken   = $notify->data["out_trade_no"];
				$payTradeNo = $notify->data["transaction_id"];
				$trade_fee  = $notify->data["cash_fee"];
				$trade_fee  = $trade_fee/100;

				$arr = array(
					'orderId' => $acctoken,
					'payType' => '微信',
					'payTradeNo' => $payTradeNo,
					'payAmount' => $trade_fee
				);

				$this->logResult(json_encode($arr));

				$this->load->model('order_model');
				$res = $this->order_model->payNotifyWechat($arr);

				$word  = "【支付成功】\r\n";
				$word .= "商户订单:$acctoken \r\n";
				$word .= "微信交易号:$payTradeNo \r\n";
				$word .= "支付金额:$trade_fee \r\n";
				$word .= "提交链接:".$res['url']."\r\n";
				$word .= "返回信息:".json_encode($res)."\r\n";

				$this->logResult($word);
			}
		}
	}

	public function logResult($word=''){
		$dir = $this->config->item('data_log_path').'WxPay';
		if(!file_exists($dir)){mkdir($dir,'0777',true);}
		$fileName = $dir.'/'.date('Y-m-d').'.txt';
		$fp = fopen($fileName,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\r\n".$word."\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

}