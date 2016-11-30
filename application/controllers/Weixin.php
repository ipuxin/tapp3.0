<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 微信接入文件 ***

创建 2016-02-22 刘深远

*** ***/

class Weixin extends MY_Controller {

	public function index(){
		$this->load->model('weixin_model');
		//$this->weixin_model->valid(); //验证token

		$this->weixin_model->GetPostStr();
	}

	/*public function getOpenId(){
		$userInfo = $this->weixin_model->getUserInfo();
		print_r($userInfo);
	}*/

	public function TestCreatMenu(){
		$this->load->model('weixin_model');
		$arr_sub1[] = array(
			'type' => 'view',
			'name' => '我的订单',
			'url'  => 'http://new.pingoing.cn/order/orderlist?newlogin=1'	
		);

		/*$arr_sub1[] = array(
			'type' => 'view',
			'name' => '我的优惠券',
			'url'  => 'http://wx.pingoing.cn/space/coupon?newlogin=1'	
		);*/

		$arr_sub1[] = array(
			'type' => 'view',
			'name' => '个人中心',
			'url'  => 'http://wx.pingoing.cn/space?newlogin=1'	
		);

		$arr_sub1[] = array(
			'type' => 'view',
			'name' => '进群抢优惠劵',
			'url'  => 'http://mp.weixin.qq.com/s?__biz=MzIzMDA1MDgzNQ==&mid=400893341&idx=1&sn=0f08cdf633b9780dbdb894e10df3ab79'	
		);

		$arr_sub3[] = array(
			'type' => 'click',
			'name' => '新品提前预订',
			'key'  => 'menu_1'	
		);

		$arr_sub3[] = array(
			'type' => 'click',
			'name' => '在线客服',
			'key'  => 'menu_2'	
		);

		$arr_sub3[] = array(
			'type' => 'view',
			'name' => '发货承诺',
			'url'  => 'http://mp.weixin.qq.com/s?__biz=MzIzMDA1MDgzNQ==&mid=401094339&idx=1&sn=29df073c0561b5acbe802c5a1588bf1f'	
		);

		$arr_sub3[] = array(
			'type' => 'view',
			'name' => '拼团流程',
			'url'  => 'http://mp.weixin.qq.com/s?__biz=MzIzMDA1MDgzNQ==&mid=400991717&idx=1&sn=21b75ed491e777de9204349a264cac9b'	
		);

		$arr[] = array(
			'type' => 'view',
			'name' => '招商',
			'url'  => 'http://www.pingoing.cn'
		);
		$arr[] = array(
			'type' => 'view',
			'name' => '下单',
			'url'  => 'http://new.pingoing.cn?newlogin=1'
		);
		$arr[] = array(
			'name' => '在线客服',
			'sub_button' => $arr_sub3
		);

		$menu['button'] = $arr;
		$this->weixin_model->creatMenu($menu);
	}

}