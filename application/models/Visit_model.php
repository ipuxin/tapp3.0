<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 访问类 ***

创建 2016-08-01 刘深远 

*** ***/

class Visit_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Visit');

		$StatusArr = array(
			1 => '预告中',
			2 => '进行中',
			3 => '已完成'
		);

		$this->_model = array(
			'VisitType' => array('shop','product','user'), //访问类型
			'WorkId' => 'id', //各种业务ID
			'UserId' => 'id', //访问者ID
			'Ip' => 'str',
			'VisitDate' => 'date',
			'VisitTime' => 'time',
		);
	}

	function getVisitList($arr,$order=array('VisitDate','ASC'),$limit=array(),$sel=array()){
		$list = $this->getList($arr,$order,$limit,$sel);
		//if($list)$list = $this->resetVisitList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetVisitList($list){
		foreach($list as $k=>$v){
			//$list[$k]['TypeMsg'] = $this->_model['Type'][$v['Type']];
		}
		return $list;
	}

	function getVisit($arr){
		$product = $this->getRow($arr);
		return $product;
	}

	function addVisit($arr){
		$arr = $this->setModel($arr);
		if($arr){
			$Data['ErrorCode'] = $arr;
			if($arr==101)$Data['ErrorMessage'] = '缺少WorkId参数';
			if($arr==102)$Data['ErrorMessage'] = '缺少UserId参数';
			return $Data;
		}
		if($res = $this->add($arr)){
			//$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function setModel($arr){
		if(!$arr['WorkId']){return 101;}
		if(!$arr['UserId']){$arr['UserId'] = $this->_member_userId;}
		if(!$arr['UserId']){return 102;}
		if(!$arr['VisitTime']){$arr['VisitTime'] = time();}
		if(!$arr['VisitDate']){$arr['VisitDate'] = date('Y-m-d H:i:s',$arr['VisitTime']);}
		if(!$arr['Ip']){$arr['Ip'] = $this->input->ip_address();}
		return $arr;
	}
	
}