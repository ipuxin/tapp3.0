<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 管理员类 ***

创建 2016-01-25 刘深远 

*** ***/

class Admin_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Admin');

		$IsDisable = array(
			0 => '启用',
			1 => '禁用'
		);

		$TypeArr = array(
			1 => '系统管理员',
			2 => '加盟商',
			3 => '店铺',
			4 => '品牌商'
		);

		$this->_model = array(
			'Account' => 'str',
			'Username' => 'str',
			'Password' => 'md5',
			'IsDisable' => $IsDisable,
			'CityCode' => 'code',
			'CityName' => 'str',
			'UserType' => $TypeArr,
			'CreatDate' => 'date'
		);
	}

	function getAdmin($arr){
		$admin = $this->getRow($arr);
		if($admin){
			$Data['Admin'] = $admin;
		}else{
			$Data['ErrorCode'] = 102;
		}
		return $Data;
	}

	function addHehuoBalance($order,$amout){
		
	}

	function addAdminBalance($order,$amout){
		
	}

	/*function addShopAdmin($ShopInfo){

		$arr = array(
			'Account' => 'shop_'.$ShopInfo['ShopId'],
			'Username' => $ShopInfo['ShopName'],
			'Password' => md5('123456'),
			'IsDisable' => 1,
			'UserType' => 3,
			'CityCode' => $ShopInfo['CityCode'],
			'CityName' => $ShopInfo['CityName'],
			'ShopId' => $ShopInfo['ShopId'],
			'ShopName' => $ShopInfo['ShopName'],
			'ShopRealId' => $ShopInfo['id'],
			'CreatDate' => time()
		);

		if(is_numeric($arr)){$Data['ErrorCode'] = $arr;return $Data;}
		if($user = $this->add($arr)){
			return true;
		}

		return false;
	}*/
	
}