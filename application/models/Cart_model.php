<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 购物车类 ***

创建 2016-08-01 刘深远 

*** ***/

class Cart_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Cart');

		$this->_model = array(
			'CityCode' => '',
			'CityName' => '',

			'UserId' => 'id', 
			'ShopName' => 'str',
			'ShopId' => 'num', 
			'ShopRealId' => 'id',
			'ProductId' => 'num',
			'ProductRealId' => 'id',
			'ProductCount' => 'num',
			//'ProductInfo' => 'info', 通过接口实时拉取最新数据
			
			'creatTime' => 'time',
		);
	}

	function getCartList($arr,$order=array('creatTime','DESC'),$limit=array(),$sel=array()){
		$list = $this->getList($arr,$order,$limit,$sel);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetCartList($list){
		foreach($list as $k=>$v){
			//$list[$k]['TypeMsg'] = $this->_model['Type'][$v['Type']];
		}
		return $list;
	}

	function getCart($arr){
		$cart = $this->getRow($arr);
		return $cart;
	}

	function addCart($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){
			$Data['ErrorMessage'] = '缺少UserId参数';
			if($arr==401)$Data['ErrorMessage'] = '缺少用户参数';
			if($arr==402)$Data['ErrorMessage'] = '缺少店铺参数';
			if($arr==403)$Data['ErrorMessage'] = '缺少产品参数';
			return $Data;
		}
		$has = $this->checkUserProductCart($arr);
		if($has){$Data['ErrorMessage'] = '当前商品已经在购物车';return $Data;}
		if(!$res = $this->add($arr)){
			$Data['ErrorMessage'] = '添加购物车失败';
		}
		return $Data;
	}

	public function getCartInfo($list,$cartList){
		$price = 0;
		$count = 0;
		foreach($list as $k=>$v){
			$cartId = $cartList[$v['ProductId']]['CartId'];
			$proCount = $cartList[$v['ProductId']]['ProductCount'];
			$proPrice = $v['Prices']['Normal'];
			$list[$k]['ProductCount'] = $proCount;
			$list[$k]['CartId'] = $cartId;
			$count += $proCount;
			$price += $proCount*$proPrice;
		}
		$info['ShopName'] = $list[0]['ShopName'];
		$info['CountAll'] = $count;
		$info['PriceProduct'] = $price;
		$info['freightAmout'] = $this->getProductAmount($list);
		$info['PricesAll'] = $info['PriceProduct'] + $info['freightAmout'];
		return array($list,$info);
	}

	public function getProductAmount($list){
		$amout = 0;
		foreach($list as $v){
			if($v['freightAmout']>$amout)$amout = $v['freightAmout'];
		}
		return $amout;
	}

	function delList($list){
		foreach($list as $v){
			$this->del($v['id']);
		}
	}

	function checkUserProductCart($arr){
		$arr = array(
			'ProductRealId' => $arr['ProductRealId'],
			'UserId' => $arr['UserId']	
		);
		$cart = $this->getCart($arr);
		return $cart;
	}

	function setModel($arr,$type='add'){
		if($type=='add'){
			if(!$arr['UserId']){$arr['UserId'] = $this->_member_userId;}
			if(!$arr['UserId']){return 401;}
			if(!$arr['ShopRealId'] || !$arr['ShopName']){return 402;}
			if(!$arr['ProductId'] || !$arr['ProductCount']){return 403;}
			if(!$arr['creatTime'])$arr['creatTime'] = time();
		}
		return $arr;
	}
	
}