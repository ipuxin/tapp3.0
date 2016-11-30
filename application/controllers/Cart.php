<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 购物车控制文件 ***

创建 2016-08-29 刘深远

*** ***/

class Cart extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('cart_model');
	}

	public function index(){
		$arr = array(
			'CityCode' => $this->_user_citycode,
			'UserId' => $this->_member_userId
		);

		$cartList = $this->cart_model->getCartList($arr);
		$cartCount = $cartList['Count'];
		$cartList = $this->resetUserCartList($cartList['List']);
		
		$data['navSel'] = 3;
		$data['cartList'] = $cartList;
		$data['cartCount'] = $cartCount;
		$this->view('cart/cart',$data);
	}

	public function cartAdd(){
		extract($this->input->post());
		$this->load->model('product_model');

		if(!$ProductId){$this->returnJson(array('Message'=>'无效的产品Id'),'f');return;}
		$ProductInfo = $this->product_model->getProduct($ProductId);
		if(!$ProductInfo){$this->returnJson(array('Message'=>'获取产品信息失败'),'f');return;}
		if(!$ProductCount)$ProductCount = 1;

		$arr = array(
			'CityCode' => $ProductInfo['CityCode'],
			'CityName' => $ProductInfo['CityName'],
			'UserId' => $this->_member_userId, 
			'ShopName' => $ProductInfo['ShopName'],
			'ShopId' => '', 
			'ShopRealId' => $ProductInfo['ShopId'],
			'ProductId' => $ProductInfo['ProductId'],
			'ProductRealId' => $ProductInfo['id'],
			'ProductCount' => $ProductCount,
		);

		$res = $this->cart_model->addCart($arr);
		$this->returnJson($res);
	}

	public function delCartRow(){
		extract($this->input->post());

		$arr = array(
			'id' => $CartId,
			'UserId' => $this->_member_userId
		);

		$code = $this->cart_model->del($arr);
		if($code){
			$this->returnJson($res);
		}else{
			$this->returnJson(array('Message'=>'删除购物车失败'),'f');
		}
	}

	public function updCartNum(){
		extract($this->input->post());

		$arr = array('ProductCount'=>$ProductCount);

		$where = array(
			'id' => $CartId,
			'UserId' => $this->_member_userId
		);

		$code = $this->cart_model->update($where,$arr);
		if($code){
			$this->returnJson($res);
		}else{
			$this->returnJson(array('Message'=>' 编辑购物车失败'),'f');
		}
	}
	
	//提交购物车产品到确认订单页面
	public function putCartToCheck(){
		extract($this->input->post());
		if(!$cart)return;
		$this->cart_model->update(array('UserId'=>$this->_member_userId),array('Select'=>0));
		foreach($cart as $v){
			$res = $this->cart_model->update($v,array('Select'=>1));
		}
		$this->returnJson($res);
	}

	public function resetUserCartList($list = array()){
		if(!$list)return;
		$this->load->model('product_model');
		foreach($list as $v){
			$productId[] = $v['ProductId'];
		}
		$productId = implode(',',$productId);
		$arr = array('ProductId' => '['.$productId.']');
		$sel = array('Prices','ImageMin','ProductName');
		$productList = $this->product_model->getProductList($arr,'','',$sel);
		if($productList['Count']>0){
			foreach($productList['List'] as $v){$productListCart[$v['id']]=$v;}
		}else{return;}
		foreach($list as $v){
			$v['Product'] = $productListCart[$v['ProductRealId']];
			$listNew[$v['ShopRealId']]['List'][] = $v;
			$listNew[$v['ShopRealId']]['ShopName'] = $v['ShopName'];
		}
		return $listNew;
	}

}