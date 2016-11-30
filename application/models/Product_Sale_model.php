<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 产品销售记录类 ***

创建 2016-08-29 刘深远 

*** ***/

class Product_Sale_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Product_Sale');

		$TypeArr = array(
			1 => '普通商品',
			2 => '拼团商品',
			3 => '免费试用',
			4 => '1元夺宝',
			5 => '幸运抽奖'
		);

		$this->_model = array(		
			'ShopId' => 'num', //商铺编号
			'ShopRealId' => 'id', //商铺真实ID
			'CityCode' => 'num',
			'CityName' => 'str',
			'ProductId' => 'num',
			'ProductRealId' => 'id',
			'ProductType' => $TypeArr,
			'OrderId' => 'num',
			'OrderRealId' => 'id',
			'TeamId' => 'num',
			'SalesCount' => 'num',//销售数量
			'CreatTime' => 'time', //生成时间
		);
	}

	function getSaleList($arr,$order=array('CreatTime','DESC'),$limit=array(),$sel=array()){
		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetSaleList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetSaleList($list){
		foreach($list as $k=>$v){
			$list[] = $this->resetSale($v);
		}
		return $list;
	}

	function resetSale($arr){
		if($arr['CreatTime'])$arr['CreatTimeDate'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		return $arr;	
	}

	function addOrderSale($order){
		$arr = array(
			'ShopRealId' => $order['ShopId'],
			'CityCode' => $order['CityCode'],
			'CityName' => $order['CityName'],
			'ProductId' => $order['ProductId'],
			'ProductRealId' => $order['ProductRealId'],
			'ProductType' => $order['ProductInfo']['ProductType'],
			'OrderId' => $order['OrderId'],
			'OrderRealId' => $order['id'],
			'TeamId' => $order['TeamId'],
			'SalesCount' => $order['ProductCount']
		);
		$this->addSale($arr);
	}

	function addSale($arr){
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