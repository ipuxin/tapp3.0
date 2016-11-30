<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 用户优惠券类 ***

创建 2016-02-20 刘深远 

*** ***/

class Usercoupon_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('UserCoupon');

		$OrderType = array(
			"0" => "不限",
			"1" => "单买",
			"2" => "开团",
			"3" => "参团",
		);

		$this->_model = array(
			'CouponName' => 'str',
			'CouponLimits' => 'num', //满减字段 不填则不限
			'OrderType' => $OrderType, //根据支付方式限制 
			'ProductId' => 'id', //根据产品id限制
			'ProductName' => 'str',
			'CouponAmount' => 'num', //优惠金额
			//'CouponRate' => 'num', //优惠折扣 1-99%
			'StartDate' => 'date', //使用开始时间
			'ExpiryDate' => 'date', //使用截止时间
			'CreatDate' => 'date', //获取时间
			'UsedDate' => 'date', //使用时间
			'UserId' => 'id',
			'OrderId' => 'id', //使用的订单
			'IsUsed' => 'num', //是否使用 0,1
		);
	}

	function getCouponList($arr,$order=array('CreatDate','DESC'),$limit){
		$list = $this->getList($arr,$order,$limit);
		if($list)$list = $this->resetCouponList($list);
		$data['List'] = $list;
		$data['ErrorCode'] = $this->_return_code;
		$data['ErrorMsg'] = $this->_return_Message;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetCouponList($list){
		foreach($list as $k=>$v){
			$arr[] = $this->resetCoupon($v);
		}
		return $arr;
	}

	function getCouponUsed($id,$OrderId){
		$time = time();
		$arr = array(
			'IsUsed' =>	1,
			'UsedDate' => $time,
			'OrderId' => $OrderId
		);
		if($num = $this->update($id,$arr)){
			return $num;
		}
	}

	function getCoupon($arr){
		$coupon = $this->getRow($arr);
		$coupon = $this->resetCoupon($coupon);
		return $coupon;
	}

	function resetCoupon($arr){
		if($arr['StartDate']){$arr['StartDateShow'] = date('Y-m-d',$arr['StartDate']);}
		if($arr['ExpiryDate']){$arr['ExpiryDateShow'] = date('Y-m-d',$arr['ExpiryDate']);}
		if($arr['UsedDate']){$arr['UsedDateShow'] = date('Y-m-d',$arr['UsedDate']);}
		if($arr['IsUsed']){$arr['IsUsedShow']="是";}else{$arr['IsUsedShow']="否";}
		$arr['OrderTypeShow'] = $this->_model['OrderType'][$arr['OrderType']];
		$arr['CreatDateShow'] = date('Y-m-d H:i:s',$arr['CreatDate']);
		return $arr;
	}

	function addCoupon($arr){
		if($coupon = $this->add($arr)){
			$Data = $coupon;
		}
		return $Data;
	}
	
}