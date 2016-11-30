<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 优惠券类 ***

创建 2016-02-20 刘深远 

*** ***/

class Coupon_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Coupon');

		$OrderType = array(
			"0" => "不限",
			"1" => "单买",
			"2" => "开团",
			"3" => "参团"
		);
		
		$SendType = array(
			"1" => '无限制',
			"2" => '限一张',
			"3" => '一天一张'
		);

		$this->_model = array(
			'CouponName' => 'str',
			'CouponLimits' => 'num', //满减字段 不填则不限
			'OrderType' => $OrderType, //根据支付方式限制 
			'ProductId' => 'id', //根据产品id限制
			'ProductName' => 'str', //展示限制产品的名称
			'ProductTuijian' => 'id', //推荐的产品id
			'CouponAmount' => 'num', //优惠金额
			//'CouponRate' => 'num', //优惠折扣 1-99%
			'SendType' => $SendType, //发放方式
			'SendDateStart' => 'date',//发放开始时间
			'SendDateEnd' => 'date',//发放截止时间
			'IsUsedDate' => 'num', //是否使用绝对时间
			'StartDate' => 'date', //使用开始时间
			'ExpiryDate' => 'date', //使用截止时间
			'UseableDays' => 'num', //从领到时间为止几天之内有效 结束时间默认：23:59:59
			'CountLimit' => 'num', //发放最大数量 0 则不限
			'CountGived' => 'num', //已发放数量
			'ShareCount' => 'num', //分享次数
			'IsDisable' => 'num', //是否启用
			'IsActive' => 'num', //是否活动优惠券
			'IsHide' => 'num',
			'CreatDate' => 'date'
		);
	}

	function UserGetCoupon($id){

		$arr = array(
			'id' => $id,
			'CountGived+' => 1,
		);

		if($updnum = $this->update($arr['id'],$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function UserShareCoupon($id){

		$arr = array(
			'id' => $id,
			'ShareCount+' => 1,
		);

		if($updnum = $this->update($arr['id'],$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function getCouponList($arr,$order=array('CreatDate','DESC'),$limit){
		$arr['IsActive!'] = 1;
		$arr['IsHide!'] = 1;
		$list = $this->getList($arr,$order,$limit);
		if($list)$list = $this->resetCouponList($list);
		$data['List'] = $list;
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

	function resetCoupon($arr){
		if($arr['SendDateStart']){$arr['SendDateStartShow'] = date('Y-m-d',$arr['SendDateStart']);}
		if($arr['SendDateEnd']){$arr['SendDateEndShow'] = date('Y-m-d',$arr['SendDateEnd']);}
		if($arr['StartDate']){$arr['StartDateShow'] = date('Y-m-d',$arr['StartDate']);}
		if($arr['ExpiryDate']){$arr['ExpiryDateShow'] = date('Y-m-d',$arr['ExpiryDate']);}
		if($arr['IsUsedDate']){$arr['IsUsedDateShow']="是";}else{$arr['IsUsedDateShow']="否";}
		if($arr['IsDisable']){$arr['IsDisableMsg'] = "禁用";}else{$arr['IsDisableMsg'] = "启用";}
		$arr['CreatDateShow'] = date('Y-m-d H:i:s',$arr['CreatDate']);
		$arr['OrderTypeShow'] = $this->_model['OrderType'][$arr['OrderType']];
		$arr['SendTypeShow'] = $this->_model['SendType'][$arr['SendType']];
		$arr['ShareUrl'] = $this->config->item('web_url').'coupon/share/'.$arr['id'];
		$arr['ImgSrc'] = 'http://qr.liantu.com/api.php?bg=ffffff&el=l&m=10&text='.$arr['ShareUrl'];
		$arr['ShareImage'] = '<a target="_blank" href="'.$arr['ImgSrc'].'"><img src="'.$arr['ImgSrc'].'"></a>';
		return $arr;
	}

	function addCoupon($arr){
		$arr = $this->setModel($arr,'add');
		if(is_numeric($arr)){$Data['ErrorCode'] = $arr;return $Data;}
		if($tag = $this->add($arr)){
			$Data['Tag'] = $tag;
		}else{
			$Data['ErrorCode'] = 202;
		}
		return $Data;
	}

	function getCoupon($arr){
		if(is_array($arr)){
			$arr['IsActive!'] = 1;
			$arr['IsHide!'] = 1;
		}
		$coupon = $this->getRow($arr);
		if($coupon)$coupon = $this->resetCoupon($coupon);
		return $coupon;
	}

	function updCoupon($arr){
		$arr = $this->setModel($arr);
		if($updnum = $this->update($arr['id'],$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function delCoupon($arr){
		if($delnum = $this->del($arr)){
			$Data['Num'] = $delnum;
		}else{
			$Data['ErrorCode'] = 2;
		}
		return $Data;
	}

	function setModel($arr,$type){
		if($arr['CouponAmount']<=0){return 4;}
		if(!is_numeric($arr['SendDateStart'])){
			$arr['SendDateStart'] = strtotime($arr['SendDateStart']);
		}

		if(!is_numeric($arr['SendDateEnd'])){
			$arr['SendDateEnd'] = strtotime($arr['SendDateEnd']) + 24*3600-1;
		}

		if(!is_numeric($arr['StartDate'])){
			if($arr['StartDate']){$arr['StartDate'] = strtotime($arr['StartDate']);}
		}

		if(!is_numeric($arr['ExpiryDate'])){
			if($arr['ExpiryDate']){$arr['ExpiryDate'] = strtotime($arr['ExpiryDate']) + 24*3600-1;}
		}

		if($arr['IsUsedDate']==1){
			$arr['UseableDays'] = 0;
		}else{
			$arr['StartDate'] = '';
			$arr['ExpiryDate'] = '';
		}
		
		if($type=='add'){
			if(!$arr['CountGived'])$arr['CountGived'] = 0;
			if(!$arr['ShareCount'])$arr['ShareCount'] = 0;
			if(!$arr['CreatDate'])$arr['CreatDate'] = time();
		}
		
		return $arr;
	}
	
}