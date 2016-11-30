<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 店铺控制文件 ***

创建 2016-08-07 刘深远

*** ***/

class Shop extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('shop_model');
	}

	public function info($id = ''){
		$arr = array(
			'ShopId' => $id,
		);
		$shop = $this->shop_model->getShop($arr);
		$data['shop'] = $shop;
		$data['shopId'] = $id;
		$this->view('shop',$data);
	}

	public function newproduct($id = ''){
		$arr = array(
			'ShopId' => $id,
		);
		$shop = $this->shop_model->getShop($arr);
		$data['shop'] = $shop;
		$data['shopId'] = $id;
		$this->view('shop',$data);
	}

	public function allproduct($id = ''){
		$arr = array(
			'ShopId' => $id,
		);
		$shop = $this->shop_model->getShop($arr);
		$data['shop'] = $shop;
		$data['shopId'] = $id;
		$this->view('shop',$data);
	}
}