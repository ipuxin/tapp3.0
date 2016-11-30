<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 订单类 ***

创建 2016-09-05 刘深远 

*** ***/

class Order_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Order');
		$TypeArr = array(
			1 => '单买',
			2 => '开团',
			3 => '参团'
		);

		$DeliveryInfo = array(
			'Address' => 'str',
			'ContactName' => 'str',
			'Mobile' => 'num',
			'City' => 'str',
			'Region' => 'str',
			'Type' => 'str'
		);

		$OrderStatus = array(
			1 => '待支付',
			2 => '已支付',
			3 => '待发货',
			4 => '已发货',
			5 => '已完成',
			6 => '已取消',
			7 => '已退款'
		);

		$this->_model = array(
			'OrderId' => 'num',
			'OrderStatus' => $OrderStatus,
			'ShopId' => 'id',
			'UserId' => 'id',

			'CityCode' => '',
			'CityName' => '',

			'OrderType' => $TypeArr,
			'Remark' => 'str',
			'DeliveryInfo' => $DeliveryInfo, //收货信息
			'ProductFee' => 'num', //产品总价
			'freightFee' => 'num', //运费
			'OrderFee' => 'num', //实际需要支付的价格
			
			'PayAmount' => 'num', //支付金额
			'PayType' => array('wechat','alipay','app_wechat','app_alipay'),
			'PayTradeNo' => 'str', //第三方交易号
			'PayStatus' => array('未支付','已支付'),

			'Logistics' => array('LogisticsName','LogisticsCode'), //快递信息
			'CreatTime' => 'time',

			//单买
			'ProductCount' => 'num',
			'ProductInfo' => array('ProductName','Description','Image','Prices','ProductId','ProductRealId'),

			//拼团
			'ProductId' => 'str',
			'ProductRealId' => 'num', //产品真实ID
			'ProductCount' => 'num',
			'ProductInfo' => array('ProductName','Description','Image','Prices'),

			//拼团特有属性
			'TeamId' => 'id',
		);
	}
	
	//支付回调
	// $arr=array(
	//		'orderId' => $acctoken,
	//		'payType' => 'Wechat',
	//		'payTradeNo' => $payTradeNo,
	//		'payAmount' => $trade_fee
	//	);
	function payNotifyWechat($arr){
		//$this->load->model('team_model');
		$orderInfo = $this->getOrderInfo($arr['orderId']);
		$orderNow = $orderInfo;
		if($orderInfo['OrderStatus']>=2){
			return;
		}else{
			$Events = $orderInfo['Events'];
			$upd = array(
				'id' => $orderInfo['id'],
				'OrderStatus' => 2,
				'PayStatus' => '已支付',
				'PayType' => $arr['payType'],
				'PayAmount' => $arr['payAmount'],
				'PayTradeNo' => '"'.$arr['payTradeNo'].'"',
				'PayTime' => time()
			);
			$this->updOrder($upd);
		}

		$upd = array('id' => $orderInfo['id']);

		$orderNow['PayAmount'] = $arr['payAmount'];
		$Date['orderInfo'] = $orderNow;
		$Date['orderPayed'] = 1;

		$this->load->model('shop_model');
		$this->shop_model->setOrderPayed($orderInfo,$arr['payAmount']);
		
		if($orderInfo['OrderType'] == 1){//单买则直接到待发货
			$upd['OrderStatus'] = 3;
			$this->payOrderSingle($orderInfo,$arr['payAmount']);
		}elseif($orderInfo['OrderType'] == 2){//开团则修改拼团状态为2(正在拼团)
			$this->payOrderTeamCreat($orderInfo);
		}elseif($orderInfo['OrderType'] == 3){
			$this->payOrderTeamJoin($orderInfo);
		}

		$this->updOrder($upd);
		return $Date;
	}

	function refundOrder($orderId){
		include_once(APPPATH."controllers/WxRefund/WxPay.Api.php");

		$order = $this->getOrderInfo(array('OrderId'=>$orderId));
		$total_fee = $order['PayAmount'] * 100;
		$transaction_id = $order['PayTradeNo'];

		if(!$order){$data['ErrorCode'] = 500;$data['ErrorMsg'] = '错误的订单信息';}
		if(!$total_fee || !$transaction_id){$data['ErrorCode'] = 500;$data['ErrorMsg'] = '订单未支付或支付失败';}
		if($data)return $data;

		$input = new WxPayRefund();
		$input->SetTransaction_id($transaction_id);
		$input->SetTotal_fee($total_fee);
		$input->SetRefund_fee($total_fee);
		$input->SetOut_refund_no(WxPayConfig::MCHID.date("YmdHis"));
		$input->SetOp_user_id(WxPayConfig::MCHID);
		$return = WxPayApi::refund($input);
		if($return['result_code']==FAIL){
			$data['ErrorCode'] = 500;$data['ErrorMsg'] = $return['err_code_des'];
			return $data;
		}

		$upd = array(
			'RefundFee' => $total_fee/100,
			'RefundTime' => time(),
			'OrderStatus' => 7
		);
		$this->updOrder($upd,$order['id']);
		
		/*$this->load->model('shop_model');
		$shopArr['Balance-'] = $refund_fee/100;
		$this->shop_model->updShop($shopArr,$order['ShopId']);

		$this->load->model('shop_balance_model');
		$this->shop_balance_model->OrderRefund($order,$refund_fee);*/
		
		$this->load->model('shop_model');
		//$shopArr['Balance-'] = $total_fee/100;
		//$shopArr['BalanceReal-'] = $total_fee/100;
		//$this->shop_model->updShop($shopArr,$order['ShopId']);

		$this->load->model('shop_balance_model');
		$this->shop_balance_model->OrderRefund($order,-1*($total_fee/100));
		$this->shop_balance_model->OrderRefund($order,-1*($total_fee/100),1);

		$data['ErrorCode'] = 0;
		$data['ErrorMsg'] = '退款成功';
		return $data;
	}

	//微信支付预支付
	function prepayWechat($orderInfo){
		$url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
		
		$proName = $orderInfo['ProductInfo']['ProductName'];
		$proName = substr(trim($proName),0,128);

		$data = array(
			'appid' => $this->_wx_kf_appid,
			'body' => $proName,
			//'device_info' => 'WEB',
			'mch_id' => $this->_wx_kf_pay_shanghu,
			'nonce_str' => $this->getRandStr(32),
			'notify_url' => 'http://new.pingoing.cn/wxpay/orderNotify/App',
			'out_trade_no' => $orderInfo['id'],
			'spbill_create_ip' => $this->input->ip_address(),
			'total_fee' => 100*$orderInfo['OrderFee'],
			'trade_type' => 'APP'
		);

		$sign = $this->getSign($data);
		$data['sign'] = $sign;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		// 这一句是最主要的
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->arrayToXml($data));
		$response = curl_exec($ch);

		$res= $this->xmlToArray($response);

		$pay = array(
			'appid' => $this->_wx_kf_appid,
			'noncestr' => $this->getRandStr(32),
			'package' => 'Sign=WXPay',
			'partnerid' => $data['mch_id'],
			'prepayid' => $res['prepay_id'],
			'timestamp' => time()
		);

		$sign = $this->getSign($pay);
		$payNew['sign'] = $sign;
		$payNew['partnerId'] = $pay['partnerid'];
		$payNew['prepayId'] = $pay['prepayid'];
		$payNew['nonceStr'] = $pay['noncestr'];
		$payNew['packageValue'] = $pay['package'];
		$payNew['timeStamp'] = $pay['timestamp'];
		$payNew['orderId'] = $orderInfo['id'];

		$res['Code'] = 0;
		$res['Message'] = 'Success';
		$res['PayRequest'] = $payNew;
		return $res;
	}

	function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                 $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

	function xmlToArray($xml){ 
		libxml_disable_entity_loader(true); 
		$xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA); 
		$val = json_decode(json_encode($xmlstring),true); 
		return $val;
	} 

	function getSign($arr){
		foreach($arr as $k=>$v){
			if($v){$clu[]=$k."=".$v;}
		}
		$str = implode('&',$clu);
		$str .= '&key='.$this->_wx_kf_pay_shanghu_key;
		$str = md5($str);
		$str = strtoupper($str);
		return $str;
	}

	function payOrderSingle($orderInfo,$payAmount){
		$this->load->model('product_model');
		$this->product_model->SalesCountAdd($orderInfo,$payAmount);
	}

	function payOrderTeamCreat($orderInfo){
		$this->load->model('product_model');
		$this->product_model->CountToWaiting($orderInfo);

		$this->load->model('team_model');
		$this->team_model->updTeamStatus($orderInfo['TeamId'],2);
	}

	function payOrderTeamJoin($orderInfo){
		$this->load->model('product_model');
		$this->product_model->CountToWaiting($orderInfo);

		$this->load->model('team_model');
		//成团判断，余额记录，库存扣除均放在这里。。
		$this->team_model->joinTeam($orderInfo);
	}
	
	//成团后订单操作处理
	function teamFinishOrder($member = array()){
		$this->load->model('product_model');
		foreach($member as $v){$orderIds[] = $v['OrderId'];}

		$listSelArr['OrderId'] = $orderIds;
		$orderList = $this->getOrderList($listSelArr);
		$countSales = 0;
		$countPrice = 0;
		if($orderList['Count']){foreach($orderList['List'] as $v){
			$productId = $v['ProductRealId'];
			$countSales += $v['ProductCount'];
			$countPrice += $v['OrderFee'];
		}}
		
		//订单完成对店铺的处理
		$this->load->model('shop_model');
		if($orderList['Count']){foreach($orderList['List'] as $orderInfo){
			//$this->shop_model->setOrderPayed($orderInfo);
			$this->product_model->WaitingToReal($orderInfo);
		}}
		
		$arr = array(
			'ProductRealId' => $productId,
			'ProductCount' => $countSales
		);
		//$this->product_model->WaitingToReal($arr);
		$listSelArr['OrderStatus'] = 2;
		$this->update($listSelArr,array('OrderStatus'=>3));
	}

	function creatOrder($arr){
		$arr = $this->setModel($arr);

		if(is_numeric($arr)){
			$Data['ErrorCode'] = $arr;
			if($arr==401)$Data['ErrorMessage'] = '订单ID创建失败';
			if($arr==402)$Data['ErrorMessage'] = '所属店铺参数缺失';
			if($arr==403)$Data['ErrorMessage'] = '用户ID参数缺失';
			if($arr==404)$Data['ErrorMessage'] = '订单类型参数缺失';
			if($arr==405)$Data['ErrorMessage'] = '所属城市参数缺失';
			if($arr==406)$Data['ErrorMessage'] = '收货信息参数缺失';
			return $Data;
		}
		if($order = $this->add($arr)){
			$data['Order'] = $order;
		}

		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		return $data;
	}

	function getOrderList($arr,$order=array(),$limit=array(),$sel=array()){
		if(!$order)$order=array('OrderId','DESC');
		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetOrderList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function orderCancel($arr){
		if($num = $this->update($arr,array('OrderStatus'=>6))){
			$data['OrderUpdates'] = $num;
		}
		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		
		//取消团
		//$this->load->model('team_model');
		//$this->team_model->updTeam(array('TeamId'=>$arr['id']),array('TeamStatus'=>5));
		return $data;
	}

	function orderShouhuo($arr){
		if($num = $this->update($arr,array('OrderStatus'=>5))){
			$data['OrderUpdates'] = $num;
		}
		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		return $data;
	}

	function resetOrderList($list){
		foreach($list as $k=>$v){
			$list[$k] = $this->resetOrder($v);
		}
		return $list;
	}

	function getOrderInfo($arr){
		$order = $this->getRow($arr);
		if($order)$order = $this->resetOrder($order);
		if($order['Logistics']['LogisticsCode'] && $order['Logistics']['LogisticsNum']){
			$order['KuaidiInfo'] = $this->getKuaidiInfo('',$order);
		}
		return $order;
	}

	function updOrder($arr,$where = ''){
		//$arr = $this->setModel($arr);
		//if(is_numeric($arr)){$Data['ErrorCode'] = $arr;return $Data;}
		if(!$where)$where = $arr['id'];
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function resetOrder($arr){
		if($arr['OrderType'])$arr['OrderTypeMsg'] = $this->_model['OrderType'][$arr['OrderType']];
		if($arr['ProductType'])$arr['ProductTypeMsg'] = $this->_model['ProductType'][$arr['ProductType']];
		if($arr['OrderStatus'])$arr['OrderStatusMsg'] = $this->_model['OrderStatus'][$arr['OrderStatus']];
		if($arr['ProductInfo'])$arr['ProductName'] = $arr['ProductInfo']['ProductName'];
		if($arr['DeliveryInfo'])$arr['Mobile'] = $arr['DeliveryInfo']['Mobile'];
		if($arr['DeliveryInfo'])$arr['RealName'] = $arr['DeliveryInfo']['RealName'];
		if($arr['CreatTime'])$arr['CreatDateShow'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		if($arr['RefundTime'])$arr['RefundDateShow'] = date('Y-m-d H:i:s',$arr['RefundTime']);
		if($arr['PayTime'])$arr['PayDateShow'] = date('Y-m-d H:i:s',$arr['PayTime']);
		if($arr['ProductInfo']){
			$arr['ProductInfo']['ImageMinReal'] = $arr['ProductInfo']['ImageMin'];
			$arr['ProductInfo']['ImageMin'] = $this->config->item('res_url').$arr['ProductInfo']['ImageMin'];
		}
		return $arr;
	}

	function orderRefund($id,$order){
		//$order = $this->getOrderInfo($id);
		$status = 7;
		$return = $order['ReturnAmount'] ? $order['ReturnAmount'] : 0;
		$return = (($order['PayAmount']*100)-$return*100)/100;

		if($order['PayAmount'] && $order['PayAmount']>=0 && $return>0){
			$refundid = time().rand(10000,99999);
			$url = $this->getApiBase().'data.order.refund?id='.$id.'&refundid='.$refundid.'&fee='.$return.'&status='.$status;
			$res = $this->getcurl($url,1);
			if($res['Code']===0){
				$res['ReturnMoney'] = $return;
			}
			return $res;
		}else{
			$res['ErrorCode'] = 255;
			return $res;
		}
	}

	function getKuaidiInfo($id = '',$order = ''){
		if(!$order){$order = $this->getOrderInfo($id);}
		$Code = $order['Logistics']['LogisticsCode'];
		$Num = $order['Logistics']['LogisticsNum'];

		$url = 'http://106.75.62.247:81/api/logistics/trace?lid='.$Num.'&sid='.$Code;
		$res = $this->getcurl($url);
		return $res['Result'];
	}

	function setModel($arr,$type="add"){
		if($type=='add'){
			$arr['OrderId'] = $this->getMax('OrderId');
			if($arr['OrderId']!==false){
				$arr['OrderId'] = $arr['OrderId'] + rand(10,49);
			}else{
				return 401;
			}

			if($arr['OrderType']==2){
				$arr['TeamId'] = $arr['OrderId'];
			}

			if(!$arr['ShopId'])return 402;
			if(!$arr['UserId'])return 403;
			if(!$arr['OrderType'])return 404;
			if(!$arr['CityCode'] || !$arr['CityName']){return 405;}
			if(!$arr['DeliveryInfo'] || count($arr['DeliveryInfo'])<3){return 406;}
			

			$arr['CreatTime'] = time();
			$arr['OrderStatus'] = 1;
			$arr['PayStatus'] = '未支付';
		}
		return $arr;
	}
	
}