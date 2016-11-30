<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 拼团控制文件 ***

创建 2016-08-25 刘深远

*** ***/

class Team extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('team_model');
	}

	public function info($teamId){
		$this->_wx_need_jsapi = 1;
		$team = $this->team_model->getTeamInfo($teamId);

		//判断倒计时是否结束
		//echo $team['EndTime'];
		
		//判断是否已经参团
		$IsInTeam = $this->checkInTeam($team['Members']);

		//判断是否有未支付订单
		$this->load->model('order_model');
		$orderSelArr = array('UserId'=>$this->_member_userId,'TeamId'=>$teamId,'OrderStatus'=>1);
		$order = $this->order_model->getOrderInfo($orderSelArr);
		if($order){redirect('/order/orderinfo/'.$order['OrderId']);}

		if($team['TeamStatus']==2){
			if($IsInTeam){
				$team['TeamStatusInfo'] = '还差 <b>'.$team['LastMemberNum'].'</b> 人,快邀请好友参团吧！';
			}else{
				$team['TeamStatusInfo'] = '<b>正在拼团</b>';
			}
		}elseif($team['TeamStatus']==3){
			$team['TeamStatusInfo'] = '组团成功，大虾请下次再来！';
		}elseif($team['TeamStatus']==4){
			$team['TeamStatusInfo'] = '组团失败，大虾请下次再来！';
		}

		$data['team'] = $team;
		$data['IsInTeam'] = $IsInTeam;
		if($team['ProductInfo']['ProductType']==4){
			$this->view('team_info_duobao',$data);
		}else{

			$this->view('team_info',$data);

		}

	}

	public function checkInTeam($member){
		/** 测试自参团，抽奖 **/
		//return false;
		/** 测试自参团，抽奖 **/
		foreach($member as $v){
			if($v['UserId']==$this->_member_userId)return true;
		}
		return false;
	}
	//一元夺宝团完成操作
	public function isEnd(){
		extract($this->input->get());
		$this->load->model('product_model');
		$data  = array('isEnd'=> 'Y');
		if($res = $this->product_model->isEnd($data, $id)){
			return $res;
		}else{
			return $res;
		}
	}
	public function myteam($type = 1){
		 $arr = array(
			 'Members.UserId' => $this->_member_userId
		 );
		 $sel = array(
		 	'TeamId','ShopId','ShopName','ShopLogo','TeamStatus','MaxOrderCount','ProductInfo', 'ProductId', 'CreatTime'
		 );
		//原始数据
		 $order = array('CreatTime','DESC');
		 $res = $this->team_model->getTeamList2($arr,$order,'',$sel);
		//按照拼团状态分组
		 $list = $this->setTeamListType($res);
		 $this->view('space/team_list',array('res'=>$res, 'list'=>$list));
	}

	//根据团列表状态分配列表数组
	public function setTeamListType($list){
		$res = array();
		foreach($list as $v){
			$res[$v['TeamStatus']][] = $v;
		}
		return $res;
	}
}