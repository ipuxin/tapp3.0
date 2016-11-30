<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 宝贝控制文件 ***

创建 2016-08-03 刘深远

*** ***/

class Product extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('product_model');
	}

	public function index(){
		$this->_page_title = '拼一下';

		$arr = array(
			'haha' => 1,
			'OpenId' => 'test_dwerdwer'
		);

		//$this->user_model->addUser($arr);

		$data['need_jsapi'] = 1;
		$this->view('index',$data);
	}

	public function info($id,$teamid=''){
		$arr = array('ProductId' => $id);
		$pro = $this->product_model->getProduct($arr,$where);
		if($pro['ShopId']){
			$this->load->model('shop_model');
			$shop = $this->shop_model->getShop($pro['ShopId'],array('CityName','ShopName','ShopLogo','ShopId','FreightFreeAmout'));
		}

		$data['pro'] = $pro;
		$data['shop'] = $shop;
		if($teamid)$data['teamid'] = $teamid;

		if($pro['ProductType']==1){
			$this->view('product',$data);
		}elseif($pro['ProductType']==2){
			$this->view('pintuan_product',$data);
		}elseif($pro['ProductType']==3){
			$this->view('pintuan_product',$data);
		}elseif($pro['ProductType']==4){
			$this->view('pintuan_product',$data);
		}elseif($pro['ProductType']==5){
			$this->view('pintuan_product',$data);
		}
	}

	public function creatTuan($id){	
		$arr = array('ProductId' => $id);
		$pro = $this->product_model->getProduct($arr,$where);
		if($pro['ProductType']==1){redirect('product/pay/payone_'.$id);}
		$data['pro'] = $pro;
		if($pro['ProductType']>2){$data['numSetDisable'] = 1;}
		$this->view('product_creat_tuan',$data);
	}

	public function joinTuan($id,$teamid){	
		$arr = array('ProductId' => $id);
		$pro = $this->product_model->getProduct($arr,$where);

		$data['pro'] = $pro;
		$data['teamid'] = $teamid;
		if($pro['ProductType']>2){$data['numSetDisable'] = 1;}
		$this->view('product_join_tuan',$data);
	}

	public function payone($id){
		$this->load->model('shop_model');
		$arr = array('ProductId' => $id);
		$pro = $this->product_model->getProduct($arr,$where);
		if($pro['ProductType']==4||$pro['ProductType']==2||$pro['ProductType']==3||$pro['ProductType']==5){redirect('product/pay/creatTuan_'.$id);}
		$arr = array('id' => $pro['ShopId']);
		$shop = $this->shop_model->getShop($arr);

		if($pro['Prices']['Normal']>=$shop["FreightFreeAmout"]){
			$pro['freightAmoutReal'] = 0;
		}else{
			$pro['freightAmoutReal'] = $pro['freightAmout'];
		}

		$data['pro'] = $pro;
		$data['shop'] = $shop;
		$this->view('product_payone',$data);
	}

	public function payCart(){
		$this->load->model('cart_model');
		$this->load->model('shop_model');

		$arr = array(
			'CityCode' => $this->_user_citycode,
			'UserId' => $this->_member_userId,
			'Select' => 1
		);
		$cart = $this->cart_model->getCartList($arr);
		if($cart['Count']>0){
			foreach($cart['List'] as $v){
				$ShopRealId = $v['ShopRealId'];
				$ProductIds[] = $v['ProductId'];
				$cartInfo[$v['ProductId']]['ProductCount'] = $v['ProductCount'];
				$cartInfo[$v['ProductId']]['CartId'] = $v['id'];
			}
			$ProductIds = implode(',',$ProductIds);
			$sel = array('ImageMin','ProductName','ProductId','Description','Prices','freightAmout','ShopName','ShopId');
			$list = $this->product_model->getProductList(array('ProductId'=>'['.$ProductIds.']'),'','',$sel);
			list($list,$info) = $this->cart_model->getCartInfo($list['List'],$cartInfo);
			$shop = $this->shop_model->getShop(array('id' => $ShopRealId));
			if($info['PriceProduct']>=$shop['FreightFreeAmout']){
				$info['PricesAll'] -= $info['freightAmout'];
				$info['freightAmout'] = 0;
			}
			$data['shop'] = $shop;
			$data['Info'] = $info;
			$data['ProductList'] = $list;
		}else{return;}

		$this->view('product_paycart',$data);
	}

	public function out(){
		session_destroy();
		redirect('/');
	}

}