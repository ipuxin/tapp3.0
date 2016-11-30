<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺资金类 ***

创建 2016-08-28 刘深远 

*** ***/

class Shop_Balance_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Shop_Mingxi');

		$TypeArr = array(
			1 => '订单支付',
			2 => '拼团完成',
			3 => '提现',
			4 => '订单退款',
		);

		$ProductTypeArr = array(
			1 => '普通商品',
			2 => '拼团商品'
		);

		$IsRealArr = array(
			0 => '总余额',
			1 => '可提现余额'
		);

		$this->_model = array(
			'IsReal' => $IsRealArr,
			'ShopId' => 'num', //商铺编号
			'ShopRealId' => 'id', //商铺真实ID
			'CityCode' => 'num',
			'CityName' => 'str',
			'ProductId' => 'num',
			'ProductRealId' => 'id',
			'ProductType' => $ProductTypeArr,
			'OrderId' => 'num',
			'OrderRealId' => 'id',
			'TeamId' => 'num',
			'BalanceType' => $TypeArr,
			'Amount' => 'num',//变动金额
			'CreatTime' => 'time', //生成时间
		);
	}

	function getBalanceList($arr,$order=array('CreatTime','DESC'),$limit=array(),$sel=array()){
		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetBalanceList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetBalanceList($list){
		foreach($list as $k=>$v){
			$list[] = $this->resetBalance($v);
		}
		return $list;
	}

	function resetBalance($arr){
		if($arr['CreatTime'])$arr['CreatTimeDate'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		return $arr;	
	}

	function addOrderBalance($order,$Amount=0,$isReal = 0){
		$arr = array(
			'IsReal' => $isReal,
			'BalanceType' => 1,
			'ShopRealId' => $order['ShopId'],
			'CityCode' => $order['CityCode'],
			'CityName' => $order['CityName'],
			'ProductId' => $order['ProductId'],
			'ProductRealId' => $order['ProductRealId'],
			'ProductType' => $order['ProductInfo']['ProductType'],
			'OrderId' => $order['OrderId'],
			'OrderRealId' => $order['id'],
			'TeamId' => $order['TeamId'],
			'Amount' => $Amount
		);
		$this->addBalance($arr);
	}

	function OrderRefund($order,$Amount=0,$isReal = 0){
		$arr = array(
			'IsReal' => $isReal,
			'BalanceType' => 4,
			'ShopRealId' => $order['ShopId'],
			'CityCode' => $order['CityCode'],
			'CityName' => $order['CityName'],
			'ProductId' => $order['ProductId'],
			'ProductRealId' => $order['ProductRealId'],
			'ProductType' => $order['ProductInfo']['ProductType'],
			'OrderId' => $order['OrderId'],
			'OrderRealId' => $order['id'],
			'TeamId' => $order['TeamId'],
			'Amount' => $Amount
		);
		$this->addBalance($arr);
	}

	function addBalance($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){
			$Data['ErrorCode'] = $arr;
			if($arr==601)$Data['ErrorMessage'] = '参数缺失';
			return $Data;
		}
		if($shop = $this->add($arr)){
			$Data['Balance'] = $shop;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function setModel($arr,$type='add'){
		if($type=='add'){
			$arr['CreatTime'] = time();
		}
		return $arr;
	}
	
}