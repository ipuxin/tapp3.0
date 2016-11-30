<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺申请类 ***

创建 2016-08-01 刘深远 

*** ***/

class Shop_Shenhe_model extends MY_Model {

	private $_model;
	private $_order_choucheng;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Shop_Shenhe');
	}
	
}