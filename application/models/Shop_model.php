<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺类 ***

创建 2016-08-01 刘深远 

*** ***/

class Shop_model extends MY_Model {

	private $_model;
	private $_order_choucheng;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->_order_choucheng = $this->config->item('order_choucheng');
		$this->setTable('Shop_Balance');

		$IsDisableArr = array(
			0 => '启用',
			1 => '禁用'
		);

		$TypeArr = array(
			1 => '普通店铺'
		);

		$this->_model = array(
			'ShopId' => 'num', //商铺编号
			'UserId' => '', //拥有者id
			'ShopType' => $TypeArr,
			'ShopName' => 'str',
			'ShopLogo' => 'url',
			'ShopImages' => array(),
			'ShopAddress' => 'str',
			'ShopOwnerName' => 'str',
			'ShopOwnerMobile' => 'str',
			'ShopDescription' => 'str',
			'BondMoney' => 'str', //保证金
			'DeliverAddress' => 'str', //发货地址
			'ReturnAddress' => 'str', //退货地址

			'ShopTemplate' => 'str', //模板名称

			'CateArea' => '', //经营范围
			'Balance' => 'num',//余额

			'CityCode' => '', //城市Code
			'CityName' => '', //城市Name

			'Fans' => 'num', //粉丝数量

			'CreatTime' => 'time', //创建时间
			'OpenTime' => 'time', //开通时间
			
			'IsOpenAdminAccount' => 'num', //是否开通后台账号
			'Ischecked' => 'num', //是否审核通过
			'IsDisable' => $IsDisableArr
		);
	}

	function getShopList($arr,$order=array('Priority','ASC'),$limit=array(),$sel=array()){
		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetShopList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetShopList($list){
		foreach($list as $k=>$v){
			$list[] = $this->resetShop($v);
		}
		return $list;
	}

	function resetShop($arr){
		if($arr['ShopType'])$arr['ShopTypeShow'] = $this->_model['ShopType'][$arr['ShopType']];
		if($arr['CreatTime'])$arr['CreatTimeDate'] = date('Y-m-d H:i:s',$arr['CreatTime']);
		if($arr['OpenTime'])$arr['OpenTimeDate'] = date('Y-m-d H:i:s',$arr['OpenTime']);
		if(mb_substr($arr['ShopLogo'],0,4)!='http'){
			$arr['ShopLogo'] = $this->config->item('res_url').$arr['ShopLogo'];
		}
		return $arr;	
	}

	function getShop($arr,$sel = array()){
		$shop = $this->getRow($arr,$sel);
		if($shop){
			$shop = $this->resetShop($shop);
			return $shop;
		}else{
			return false;
		}
	}

	function setOrderPayed($order,$payAmount){
		$Amount = (float)$payAmount;
		if($Amount == 0 || !$Amount){return;}
		$shop_yue = $Amount;
		$this->load->model('admin_model');
		//list($shop_yue,$hehuo_cc,$admin_cc) = $this->checkChoucheng($Amount);
		if($shop_yue<=0)return;

		if($order['OrderStatus']<6){
			//$shopArr['Balance+'] = $shop_yue;
			//$this->updShop($shopArr,$order['ShopId']);

			$this->load->model('shop_balance_model');
			$this->shop_balance_model->addOrderBalance($order,$shop_yue);
		}
		//if($hehuo_cc)$this->admin_model->addHehuoBalance($order,$hehuo_cc);
		//if($admin_cc)$this->admin_model->addAdminBalance($order,$admin_cc);
	}

	function checkChoucheng($amount){
		$cc_type = $this->config->item('order_choucheng_type');
		$cc_admin = $this->config->item('order_choucheng_admin');
		$cc_hehuo = $this->config->item('order_choucheng_hehuo');
		if($cc_type==1){
			$admin_cc = intval($amount*100/$cc_admin)/100;
			$hehuo_cc = intval($amount*100/$cc_hehuo)/100;
		}elseif($cc_type==2){
			$admin_cc = 0;
			$hehuo_cc = 0;
		}
		$shop_yue = $amount - $hehuo_cc - $admin_cc;
			
		return array($shop_yue,$hehuo_cc,$admin_cc);
	}

	function checkUserHasShop($userId){
		$has = $this->getShop(array('UserId'=>$userId),array('ShopId','id'));
		return $has;
	}

	/*function addShop($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){
			$Data['ErrorCode'] = $arr;
			if($arr==201)$Data['ErrorMessage'] = '店铺ID创建失败';
			if($arr==202)$Data['ErrorMessage'] = '所属城市参数缺失';
			if($arr==203)$Data['ErrorMessage'] = '用户ID参数缺失';
			if($arr==205)$Data['ErrorMessage'] = '用户已经开通过店铺';
			return $Data;
		}
		if($shop = $this->add($arr)){
			$Data['Shop'] = $shop;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}*/

	function updShop($arr,$where= array()){
		$arr = $this->setModel($arr,'upd');
		if(is_numeric($arr)){
			$Data['ErrorCode'] = $arr;
			if($arr==204)$Data['ErrorMessage'] = '用户ID参数不可修改';
			return $Data;
		}
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function setModel($arr,$type='add'){
		if($type=='add'){
			if(!$arr['UserId']){return 203;}
			if($this->checkUserHasShop($arr['UserId'])){return 205;}
			if(!$arr['ShopId']){$arr['ShopId'] = $this->getMax('ShopId');}
			if($arr['ShopId']!==false){
				$arr['ShopId'] = $arr['ShopId'] + rand(10,49);
			}else{
				return 201;
			}
			if(!$arr['CreatTime'])$arr['CreatTime'] = time();
			if(!$arr['ShopType'])$arr['ShopType'] = 1;
			if(!$arr['IsDisable'])$arr['IsDisable'] = 0;
			if(!$arr['CityCode'] || !$arr['CityName']){
				return 202;
			}

			if(!$arr['Fans'])$arr['Fans'] = 0;
			if(!$arr['Balance'])$arr['Balance'] = 0;
			$arr['IsOpenAdminAccount'] = 0;
		}

		if($type=='upd'){
			if($arr['UserId']){return 204;}
		}
		return $arr;
	}
	
}