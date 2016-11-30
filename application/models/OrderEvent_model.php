<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 订单类 ***

创建 2016-01-30 刘深远 

*** ***/

class OrderEvent_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('OrderEvent');
		$this->_model = array(
			'OrderId' => 'id',
			'EventName' => 'str',
			'EventMsg' => 'str', //事件描述
			'StatusOld' => 'num',
			'StatusNow' => 'num',

			'Ip' => 'str',

			'CreateDate' => 'datetime',
			'CreateTime' => 'time'
		);
	}

	function creatEvent($arr){
		$time = time();
		$arr = array(
			'CreateTime' => $time,
			'CreateDate' => date('Y-m-d H:i:s',$time),
			'Ip' => $this->input->ip_address()
		);

		$order = $this->add($arr);
		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		return $data;
	}

	function getEventList($arr,$order=array('CreateDate','DESC'),$limit=array(),$sel=array()){
		$list = $this->getList($arr,$order,$limit,$sel);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function getEventInfo($arr){
		return $this->getRow($arr);
	}
	
}