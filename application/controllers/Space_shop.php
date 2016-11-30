<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 用户商户中心控制文件 ***

创建 2016-08-05 刘深远

*** ***/

class Space_shop extends MY_Controller {

	public $_shop_info;

	public function __construct(){
		parent::__construct();
		$this->load->model('shop_model');
		/*$ShopInfo = $this->getShopInfo();
		if(!$ShopInfo){
			if($this->uri->segment(1)!='space_shop' || ($this->uri->segment(2)!='shopCreat' && $this->uri->segment(2)!='shopAdd')){
				redirect('/space_shop/shopCreat');return;
			}
		}else{
			if($ShopInfo['IsOpenAdminAccount']==0){
				if($this->uri->segment(2)!='checkedMsg'){redirect('/space_shop/checkedMsg');return;}
			}
			if($ShopInfo['IsOpenAdminAccount']==2){
				if($this->uri->segment(2)!='checkedMsg'){redirect('/space_shop/checkedMsg');return;}
			}
		}*/

		$this->_shop_info = $ShopInfo;
	}

	public function getShopInfo(){
		//if($this->session->userdata('shop_info')){
		//	return $this->session->userdata('shop_info');
		//}else{
			$ShopInfo = $this->shop_model->getShop(array('UserId'=>$this->_member_userId));
			if(!$ShopInfo){
				return false;
			}else{
				$this->session->set_userdata('shop_info',$ShopInfo);
				return $ShopInfo;
			}
		//}
	}

	public function index(){
		$data['shop'] = $this->_shop_info;
		//$this->view('space_shop',$data);
	}

	public function shopSetting(){
		$data['shop'] = $this->_shop_info;
		$this->view('space_shop/shop_setting',$data);
	}
	
	public function shopCreat(){
		$data['shop'] = $this->_shop_info;
		$this->view('space_shop/shop_creat',$data);
	}

	public function checkedMsg(){
		$data['shop'] = $this->_shop_info;
		$this->view('space_shop/checked_msg',$data);
	}

	public function product($type='list',$v=''){
		$data['shop'] = $this->_shop_info;
		if($type=='list'){
			$this->view('space_shop/product_list',$data);
		}
		if($type=='fabu'){
			$this->view('space_shop/product_fabu',$data);
		}
		if($type=='upd'){
			$this->load->model('product_model');
			$arr = array(
				'ShopId' => $this->_shop_info['id'],
				'id' => $v
			);
			$pro = $this->product_model->getProduct($arr,$where);
			if(!$pro){redirect('/space_shop/product');}
			$pro['ProductNameCount'] = mb_strlen($pro['ProductName']);

			$data['id'] = $v;
			$data['pro'] = $pro;
			$this->view('space_shop/product_upd',$data);
		}
	}

	public function fabuBaobei($type = 'add'){
		extract($this->input->post());
		$this->load->model('product_model');

		$arr = array(
			'ImageMin' => $ImageMin,
			'ProductName' => $ProductName,
			'Prices' => $Prices,
			'StorageCount' => $StorageCount,
			'freightAmout' => $freightAmout,
			'DeliverAddress' => $DeliverAddress,
			'Description' => $Description,
			'IsForSale' => intval($IsForSale)
		);
		
		if($type=='add'){
			$arr['ShopId'] = $this->_shop_info['id'];
			$arr['CityCode'] = $this->_shop_info['CityCode'];
			$arr['CityName'] = $this->_shop_info['CityName'];
			$res = $this->product_model->addProduct($arr);
		}else{
			$where = array(
				'ShopId' => $this->_shop_info['id'],
				'id' => $id
			);
			$res = $this->product_model->updProduct($arr,$where);
		}

		$this->returnJson($res);
	}

	public function shopUpd(){
		extract($this->input->post());

		$arr = array(
			'ShopName' => $ShopName,
			'ShopLogo' => $this->_member_headimgurl,
			'ShopOwnerName' => $ShopOwnerName,
			'ShopOwnerMobile' => $ShopOwnerMobile,
			'ShopDescription' => $ShopDescription,
			'ShopAddress' => $ShopAddress,
			'DeliverAddress' => $DeliverAddress,
			'ReturnAddress' => $ReturnAddress
		);
		
		$where  = array(
			'id' => $this->_shop_info['id'],
			'UserId' => $this->_member_userId
		);

		$res = $this->shop_model->updShop($arr,$where);
		
		//更新session数据
		if($res['ErrorCode']==0){
			$this->_shop_info = array_merge($this->_shop_info,$arr);
			$this->session->set_userdata('shop_info',$this->_shop_info);
		}

		$this->returnJson($res);
	}

	public function shopAdd(){
		extract($this->input->post());

		$arr = array(
			'UserId' => $this->_member_userId,
			'CityCode' => $this->_user_citycode,
			'CityName' => $this->_user_cityname,
			'ShopType' => 1,
			'ShopName' => $ShopName,
			'ShopLogo' => $this->_member_headimgurl,
			'ShopOwnerName' => $ShopOwnerName,
			'ShopOwnerMobile' => $ShopOwnerMobile,
			'ShopDescription' => $ShopDescription,
			'ShopAddress' => $ShopAddress,
			'DeliverAddress' => $ShopAddress,
			'ReturnAddress' => $ShopAddress
		);

		$res = $this->shop_model->addShop($arr);
		$this->returnJson($res);
	}

	public function getBaobeiList(){
		$this->load->model('product_model');
		
		$arr = array('ShopId' => $this->_shop_info['id']);
		$sel = array('ImageMin','IsForSale','ProductName','Prices','StorageCount','SalesCount');

		$res = $this->product_model->getProductList($arr,'','',$sel);
		$this->returnJson($res);
	}

	public function orderList($type = 1){
		$this->load->model('order_model');
		$arr = array(
			'ShopId' => $this->_shop_info['id']
		);
		$sel = array(
			'OrderId','ProductCount','ProductInfo','ProductId','OrderFee','OrderStatus'
		);
		$res = $this->order_model->getOrderList($arr,'','',$sel);
		$list = $this->setOrderListType($res['List']);

		$data['type'] = $type;
		$data['list'] = $list;
		$this->view('space_shop/order_list',$data);
	}
	
	//根据订单列表状态分配列表数组
	public function setOrderListType($list){
		$res = array(array(),array(),array(),array(),array());
		foreach($list as $v){
			if($v['OrderStatus']==1){$res[1][]=$v;}
			if($v['OrderStatus']==2){$res[2][]=$v;}
			if($v['OrderStatus']==3){$res[2][]=$v;}
			if($v['OrderStatus']==4){$res[3][]=$v;}
			if($v['OrderStatus']==5){$res[4][]=$v;}
		}
		return $res;
	}

	/*public function getBaobeiInfo(){
		$this->load->model('product_model');
		extract($this->input->post());
		$arr = array(
			'ShopId' => $this->_shop_info['id'],
			'id' => $ProductId
		);
		$res = $this->product_model->updProduct($arr,$where);
		$this->returnJson($res);
	}*/

}