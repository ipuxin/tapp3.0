<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 优惠券控制文件 ***

创建 2016-02-20 刘深远

*** ***/

class Coupon extends MY_Controller {

	public function __construct(){
		parent::__construct();
		//$this->load->model('coupon_model');
		//$this->load->model('usercoupon_model');
	}
	
	public function share($couponId){
		$this->_page_title = '拼一下';

		$data['couponId'] = $couponId;
		$data['need_jsapi'] = 1;

		$this->view('coupon_share',$data);
	}

	public function getUserCoupon($CouponId){
		$UserId = $this->_member_userId;
		if(!$UserId)return false;
		$this->load->model('coupon_model');
		$this->load->model('usercoupon_model');
		$coupon = $this->coupon_model->getCoupon($CouponId);
		$coupon['ErrorCode'] = 1;
		if(!$coupon['id']){
			$coupon['ErrorMessage'] = '优惠券不存在';
			echo json_encode($coupon);
			return;
		}

		if($coupon['IsDisable']){
			$coupon['ErrorMessage'] = '活动已关闭';
			echo json_encode($coupon);
			return;
		}
		
		$time = time();
		if($time<$coupon['SendDateStart']){
			$coupon['ErrorMessage'] = '发放时间还没开始';
			echo json_encode($coupon);
			return;
		}

		if($time>$coupon['SendDateEnd']){
			$coupon['ErrorMessage'] = '发放时间已经结束';
			echo json_encode($coupon);
			return;
		}

		if($coupon['CountLimit'] != 0 && $coupon['CountLimit']<=$coupon['CountGived']){
			$coupon['ErrorMessage'] = '优惠券已经发放完毕';
			echo json_encode($coupon);
			return;
		}
		
		//限一张
		if($coupon['SendType']==2){
			$arr = array('CouponId'=>$CouponId,'UserId'=>$UserId);
			$has = $this->usercoupon_model->getCoupon($arr);
			if($has['id']){
				$coupon['ErrorMessage'] = '您已经领取过优惠券';
				$coupon['HasCoupon'] = 1;
				echo json_encode($coupon);
				return;
			}
		}
		
		//限一张
		if($coupon['SendType']==3){
			$arr = array('CouponId'=>$CouponId,'UserId'=>$UserId,'~CreatDate'=>'DESC');
			$has = $this->usercoupon_model->getCoupon($arr);
			$coupon['LastGetDate'] = $has['CreatDateShow'];
			if($has['id'] && date('Y-m-d',$has['CreatDate']) == date('Y-m-d',$time)){
				$coupon['ErrorMessage'] = '您今天已经领取过优惠券';
				$coupon['HasCoupon'] = 1;
				echo json_encode($coupon);
				return;
			}
		}

		//开始发放
		$arr = array(
			'CouponId' => $coupon['id'],
			'IsAll' => $coupon['IsAll'],
			'CityCode' => $coupon['CityCode'],
			'CityName' => $coupon['CityName'],
			'ShopId' => $coupon['ShopId'],
			'ShopName' => $coupon['ShopName'],
			'CouponName' => $coupon['CouponName'],
			'CouponLimits' => $coupon['CouponLimits'],
			'OrderType' => $coupon['OrderType'],
			'ProductId' => $coupon['ProductId'],
			'ProductName' => $coupon['ProductName'],
			'CouponAmount' => $coupon['CouponAmount'],
			'CreatDate' => $time,
			'UserId' => $UserId,
			'IsUsed' => 0,
		);
		
		if($coupon['IsUsedDate']){
			$arr['StartDate'] = $coupon['StartDate'];
			$arr['ExpiryDate'] = $coupon['ExpiryDate'];
		}else{
			$arr['StartDate'] = $time;
			$arr['ExpiryDate'] = strtotime(date('Y-m-d',$time+3600*24*($coupon['UseableDays']+1)))-1;
		}

		if($userCoupon = $this->usercoupon_model->addCoupon($arr)){
			$this->coupon_model->UserGetCoupon($CouponId);
			$userCoupon = $this->usercoupon_model->resetCoupon($userCoupon);
			//模板消息
			//$this->sendMoban('couponGetSuccess',$this->_member_openId,$userCoupon);
			$coupon['userCoupon'] = $userCoupon;
		}else{
			$coupon['ErrorMessage'] = '发放失败';
			echo json_encode($coupon);
			return;
		}
		
		$coupon['HasCoupon'] = 1;
		$coupon['ErrorCode'] = 0;
		echo json_encode($coupon);
	}

}