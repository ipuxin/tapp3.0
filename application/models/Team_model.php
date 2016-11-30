<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 拼团类 ***

创建 2016-01-30 刘深远 

*** ***/

class Team_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Team');

		$TypeArr = array(
			1 => '等待团长支付',
			2 => '正在拼团',
			3 => '拼团完成',
			4 => '拼团失败',
			5 => '取消拼团'
		);

		$this->_model = array(
			'TeamStatus' => $TypeArr,
			'TeamId' => 'orderId',
			'ShopId' => 'id',
			'CityCode' => 'str',
			'CityName' => '',
			'ProductId' => 'id',
			'ProductRealId' => '',
			'ProductInfo' => array('ProductName','Prices','Description','freightAmout','DeliverAddress'),
			'LotteryCount' => 'num',
			'Members' => array(
				array('OrderId','OrderCreateDate','IsNewMember','UserId','NickName','Thnumbail')
			),
			'MaxOrderCount' => 'num',
			
			'TeamHeaderDeliveryInfo' => array(), //团长收货信息
			//'AgentId' => 'num',
			//'ActiveMsg' => 'array', //活动产品提示信息
			//'ProductRelation' => 'array', //关联产品
			//'CouponGroup' => 'id', //优惠券包id
			'CreatTime' => 'time',
			'EndTime' => 'time'
		);
	}

	function getTeamList($arr,$order=array('EndTime','DESC'),$limit){
		$list = $this->getList($arr,$order,$limit);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}
	function getTeamList2($arr,$order=array('CreatTime','DESC'),$limit,$sel){
		$list = $this->getList($arr,$order,$limit,$sel);
		return $list;
	}

	function finishTeam($teamId){
		$url = $this->getApiBase().$this->getApiType().$this->getApiTable().'.Finish?id='.$teamId;
		$res = $this->getcurl($url);
		return $res;
	}
	
	//设置拼团抽奖
	function lotteryTeam($team){

		//$team = $this->getTeamInfo($teamInfo);

		$num = $team['LotteryCount'];
		$all = $team['MaxOrderCount'];
		$order = $team['Members'];
		if($num && $num>0){
			$arr = array(); 
			if($num>=$all)$num = $all - 1;
			while(count($arr)<$num)$arr[rand(0,$all-1)]=null;
			$arr = array_keys($arr);
			for($i=0;$i<$all;$i++){
				if(in_array($i,$arr)){
					$order[$i]['Lottery'] = 1;
				}else{
					$arrTuikuan[] = $order[$i];
				}
			}
		}
		$this->updTeam($team['id'],array('Members'=>$order));
		$this->setOrderNotLottery($team,$arrTuikuan);
	}

	function setOrderNotLottery($team,$orderList){
		$this->load->model('order_model');
		
		if($team['ProductType'] == 3){
			foreach($orderList as $v){
				$OrderId[] = $v['OrderId'];
			}
			$arr = array('OrderStatus' => 5); //5已签收 6 已取消 7 已退款
			$where = array('OrderId'=>$OrderId);
			$this->order_model->updOrder($arr,$where);
		}
		if($team['ProductType'] == 4){
			foreach($orderList as $v){
				$OrderId[] = $v['OrderId'];
			}
			$arr = array('OrderStatus' => 5); //5已签收 6 已取消 7 已退款
			$where = array('OrderId'=>$OrderId);
			$this->order_model->updOrder($arr,$where);
		}
		if($team['ProductType'] == 5){
			foreach($orderList as $v){
				$OrderId[] = $v['OrderId'];
				$this->order_model->refundOrder($v['OrderId']);
			}
		}
	}

	function joinTeam($order){
		$teamInfo = $this->getTeamInfo($order['TeamId']);
		$this->load->model('user_model');
		$userInfo = $this->user_model->getUserInfo($order['UserId']);
		$Members = $teamInfo['Members'];
		$Members[] = array(
			'OrderId' => $order['OrderId'],
			'OrderCreatTime' => $order['CreatTime'],
			'IsNewMember'=> $order['IsNewMember'],
			'UserId' => $order['UserId'],
			'NickName' => $userInfo['NickName'],
			'Thnumbail' => $userInfo['Thumbnail']
		);
		$arr = array(
			'Members' => $Members
		);

		$updRes = $this->updTeam($teamInfo['id'],$arr);
		$teamInfo = $updRes['Result'];
		$this->checkTeamFinish($teamInfo,$Members);
		//return $teamInfo;
	}

	function checkTeamFinish($teamInfo,$member){
		$this->load->model('order_model');
		$count = count($member);
		if($teamInfo['MaxOrderCount']==$count){
			$arr['TeamStatus'] = 3;
			if($teamInfo['ProductType'] > 2){
				$this->lotteryTeam($teamInfo);
			}
			$this->order_model->teamFinishOrder($member);
		}
		if($teamInfo['MaxOrderCount']<$count){
			$arr['TeamStatus'] = 3;
		}
		if($arr) $a = $this->updTeam($teamInfo['id'],$arr);
		$this->load->model('product_model');
		if($a['Result']['TeamStatus'] == 4){
			$data  = array('isEnd'=> 'N');
		}elseif($a['Result']['TeamStatus'] == 3){
			$data  = array('isEnd'=> 'Y');
		}
		$this->product_model->isEnd($data, $teamInfo['ProductRealId']);
	}

	function creatTeam($order,$product){

		if(!$order['Order'])return;
		$order = $order['Order'];
		$this->load->model('user_model');
		$userInfo = $this->user_model->getUserInfo($order['UserId']);

		$CreatTime = time();
		$EndTime = $CreatTime + $product['Alive']*3600;

		$arr = array(
			'TeamStatus' => 1,
			'TeamId' => $order['TeamId'],
			'ShopId' => $product['ShopId'],
			'ShopName' => $product['ShopName'],
			'ShopLogo' => $order['ShopLogo'],
			'CityCode' => $product['CityCode'],
			'CityName' => $product['CityName'],
			'ProductId' => $product['ProductId'],
			'ProductType' => $product['ProductType'],
			'ProductRealId' => $product['id'],

			'ProductInfo' => array(
				'ProductType' => $product['ProductType'],
				'ImageMin' => $product['ImageMin'],
				'Images' => $product['Images'],
				'ProductName' => $product['ProductName'],
				'Prices' => $product['Prices'],
				'freightAmout' => $product['freightAmout'],
				'DeliverAddress' => $product['DeliverAddress'],
				'Description' => $product['Description'],
				'CityCode' => $product['CityCode'],
				'CityName' => $product['CityName'],
				'Alive' => $product['Alive']
			),

			'NewMemberCount' => $product['NewMemberCount'],
			'LotteryCount' => $product['LotteryCount'],
			'TeamHeaderDeliveryInfo' => $order['DeliveryInfo'],
			'Members' => array(
				array(
					'OrderId'=>$order['OrderId'],
					'OrderCreatTime'=>$order['CreatTime'],
					'IsNewMember' => $order['IsNewMember'],
					'UserId' => $order['UserId'],
					'NickName' => $userInfo['NickName'],
					'Thnumbail' => $userInfo['Thumbnail']
				)
			),
			'MaxOrderCount' => $product['TeamMemberLimit'],
			'CreatTime' => $CreatTime,
			'EndTime' => $EndTime
		);

		if($Team = $this->add($arr)){
			$Data['Team'] = $Team;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function updTeamStatus($TeamId,$TeamStatus){
		$where = array('TeamId'=>$TeamId);
		$arr = array('TeamStatus'=>$TeamStatus);
		$res = $this->updTeam($where,$arr);
		return $res;
	}

	function updTeam($where,$arr){
		//$arr = $this->setModel($arr);
		if($updnum = $this->update($where,$arr)){
			$Data['Result'] = $updnum[0];
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function getTeamInfo($teamId){
		$team = $this->getRow(array('TeamId'=>$teamId));
		$team = $this->resetTeam($team);
		return $team;
	}

	function resetTeam($team){
		$team['LastMemberNum'] = $team['MaxOrderCount'] - count($team['Members']);
		return $team;
	}

	function getTeamFromId($id){
		$team = $this->getRow($id);
		return $team;
	}

	function getTeamOldMemberCount($TeamId){
		$OldMemberCount = 0;
		$teamInfo = $this->getTeamInfo($TeamId);
		if($teamInfo){
			foreach($teamInfo['Orders'] as $order){
				if($order['IsNewMember']!=1){
					$OldMemberCount += 1;
				}
			}
		}

		return $OldMemberCount;
	}
	//用产品id检测团是否创建
	function  isCreatTeam($ProductId){
		if($team = $this->getRow(array('ProductId'=>$ProductId))){
				return $team;
			}else{
				return false;
		}
	}

	function setModel($arr){
		if(!$arr['ProductName'])return 301;
		if(!$arr['ProductId'])$arr['ProductId'] = $this->getMax('ProductId')+1;
		return $arr;
	}
	
}