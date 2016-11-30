<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 订单控制文件 ***

创建 2016-08-06 刘深远

*** ***/

class Order extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('order_model');
	}

	public function creatOrder(){
		extract($this->input->post());
		$this->load->model('product_model');
		$this->load->model('shop_model');

		$arr = array('ProductId' => $ProductId);
		$pro = $this->product_model->getProduct($arr);
		$shop = $this->shop_model->getShop($arr = array('id' => $pro['ShopId']));
		if(!$pro){$this->returnJson(array('Message'=>'获取产品信息失败'),'f');return;}
		if($pro['IsDisable']!=0){$this->returnJson(array('Message'=>'产品已禁用'),'f');return;}
		if($pro['IsForSale']!=1){$this->returnJson(array('Message'=>'产品已下架'),'f');return;}
		if($pro['IsChecked']!=1){$this->returnJson(array('Message'=>'产品正在审核中'),'f');return;}
		if($pro['IsShopDisable']==1){$this->returnJson(array('Message'=>'店铺已关闭'),'f');return;}

		$add = $this->user_model->getAddress($this->_member_userId,$AddressId);
		if(!$add){$this->returnJson(array('Message'=>'获取地址信息失败'),'f');return;}
		//if($add['CityCode']!=$shop['CityCode']){$this->returnJson(array('Message'=>'地址信息超出服务范围，请重新选择'),'f');return;}

		$ProductFee = $pro['Prices']['Normal'] * $ProductNum;
		if($ProductFee>=$shop["FreightFreeAmout"]){
			$pro['freightAmoutReal'] = 0;
		}else{
			$pro['freightAmoutReal'] = $pro['freightAmout'];
		}
		$arr = array(
			'ShopId' => $pro['ShopId'],
			'ShopName' => $pro['ShopName'],
			'UserId' => $this->_member_userId,
			'CityCode' => $add['CityCode'],
			'CityName' => $add['CityName'],
			'OrderType' => 1,
			'ProductId' => $pro['ProductId'],
			'ProductRealId' => $pro['id'],
			'ProductCount' => $ProductNum,
			'ProductInfo' => array(
				'ImageMin' => $pro['ImageMinReal'],
				'ProductName' => $pro['ProductName'],
				'Prices' => $pro['Prices']['Normal'],
				'PricesAll' => $pro['Prices'],
				'DeliverAddress' => $pro['DeliverAddress'],
				'Description' => $pro['Description'],
				'ProductType' => $pro['ProductType']
			),

			'Remark' => $Remark,
			'DeliveryInfo' => $add,
			'ProductFee' => $ProductFee,
			'freightFee' => $pro['freightAmoutReal'],
			'OrderFee' => $ProductFee + $pro['freightAmoutReal']
		);
		
		$res = $this->order_model->creatOrder($arr);
		$this->returnJson($res);
	}

	public function creatOrderTuanCreat(){
		extract($this->input->post());
		$this->load->model('product_model');
		$this->load->model('team_model');
		$this->load->model('shop_model');

		$arr = array('ProductId' => $ProductId);
		$pro = $this->product_model->getProduct($arr);
		$shop = $this->shop_model->getShop($arr = array('id' => $pro['ShopId']));
		if(!$pro){$this->returnJson(array('Message'=>'获取产品信息失败'),'f');return;}
		if($pro['IsDisable']!=0){$this->returnJson(array('Message'=>'产品已禁用'),'f');return;}
		if($pro['IsForSale']!=1){$this->returnJson(array('Message'=>'产品已下架'),'f');return;}
		if($pro['IsChecked']!=1){$this->returnJson(array('Message'=>'产品正在审核中'),'f');return;}
		if($pro['IsShopDisable']==1){$this->returnJson(array('Message'=>'店铺已关闭'),'f');return;}

		$add = $this->user_model->getAddress($this->_member_userId,$AddressId);
		if(!$add){$this->returnJson(array('Message'=>'获取地址信息失败'),'f');return;}
		//if($add['CityCode']!=$shop['CityCode']){$this->returnJson(array('Message'=>'地址信息超出服务范围，请重新选择'),'f');return;}

		$ProductFee = $pro['Prices']['Team'] * $ProductNum;
		$arr = array(
			'ShopId' => $pro['ShopId'],
			'ShopName' => $pro['ShopName'],
			'UserId' => $this->_member_userId,
			'CityCode' => $add['CityCode'],
			'CityName' => $add['CityName'],
			'ShopLogo'=> $shop['ShopLogo'],
			'OrderType' => 2, //开团
			'ProductId' => $pro['ProductId'],
			'ProductRealId' => $pro['id'],
			'ProductCount' => $ProductNum,
			'ProductInfo' => array(
				'ImageMin' => $pro['ImageMinReal'],
				'ProductName' => $pro['ProductName'],
				'Prices' => $pro['Prices']['Team'],
				'PricesAll' => $pro['Prices'],
				'DeliverAddress' => $pro['DeliverAddress'],
				'Description' => $pro['Description'],
				'ProductType' => $pro['ProductType']
			),

			'Remark' => $Remark,
			'DeliveryInfo' => $add,
			'ProductFee' => $ProductFee,
			'freightFee' => $pro['freightAmout'],
			'OrderFee' => $ProductFee + $pro['freightAmout']
		);
		
		$res = $this->order_model->creatOrder($arr);
		$this->team_model->creatTeam($res,$pro);
		$this->returnJson($res);
	}
	public function DuobaocreatOrderTuanCreat(){
		extract($this->input->post());

		$this->load->model('product_model');
		$this->load->model('team_model');
		$this->load->model('shop_model');

		$arr = array('ProductId' => $ProductId);

		$pro = $this->product_model->getProduct($arr);
		$shop = $this->shop_model->getShop($arr = array('id' => $pro['ShopId']));
		if(!$pro){$this->returnJson(array('Message'=>'获取产品信息失败'),'f');return;}
		if($pro['IsDisable']!=0){$this->returnJson(array('Message'=>'产品已禁用'),'f');return;}
		if($pro['IsForSale']!=1){$this->returnJson(array('Message'=>'产品已下架'),'f');return;}
		if($pro['IsChecked']!=1){$this->returnJson(array('Message'=>'产品正在审核中'),'f');return;}
		if($pro['IsShopDisable']==1){$this->returnJson(array('Message'=>'店铺已关闭'),'f');return;}
		if($pro['TeamMemberLimit']<$pro['SalesCount']['Real']){$this->returnJson(array('Message'=>'夺宝人数已够'),'f');return;}

		$add = $this->user_model->getAddress($this->_member_userId,$AddressId);
		if(!$add){$this->returnJson(array('Message'=>'获取地址信息失败'),'f');return;}
		//if($add['CityCode']!=$shop['CityCode']){$this->returnJson(array('Message'=>'地址信息超出服务范围，请重新选择'),'f');return;}

		$ProductFee = $pro['Prices']['Team'] * $ProductNum;
		$team = $this->team_model->isCreatTeam($pro['ProductId']);
		if($team){
			$OrderType = 3;
			if($team['TeamStatus']!=2){$this->returnJson(array('Message'=>'拼团未开始或已结束'),'f');return;}
			if($team['EndTime']<=(time()+20)){$this->returnJson(array('Message'=>'拼团结束时间已到'),'f');return;}
			if($team['MaxOrderCount']<=count($team['Members'])){$this->returnJson(array('Message'=>'拼团已满员，下次再来吧！'),'f');return;}
		}else{
			$OrderType = 2;
		}
		$arr = array(
				'ShopId' => $pro['ShopId'],
				'ShopName' => $pro['ShopName'],
				'ShopLogo'=> $shop['ShopLogo'],
				'UserId' => $this->_member_userId,
				'CityCode' => $add['CityCode'],
				'CityName' => $add['CityName'],
				'OrderType' => $OrderType, //开团或加入团
				'ProductId' => $pro['ProductId'],
				'ProductRealId' => $pro['id'],
				'ProductCount' => $ProductNum,
				'ProductInfo' => array(
						'ImageMin' => $pro['ImageMinReal'],
						'ProductName' => $pro['ProductName'],
						'Prices' => $pro['Prices']['Team'],
						'PricesAll' => $pro['Prices'],
						'DeliverAddress' => $pro['DeliverAddress'],
						'Description' => $pro['Description'],
						'ProductType' => $pro['ProductType']
				),

				'Remark' => $Remark,
				'DeliveryInfo' => $add,
				'ProductFee' => $ProductFee,
				'freightFee' => $pro['freightAmout'],
				'OrderFee' => $ProductFee + $pro['freightAmout']
		);
		if($OrderType == 3){$arr['TeamId'] = $team['TeamId'];}
		$res = $this->order_model->creatOrder($arr);
		$res['UserId']  = $this->_member_userId;
		if($pro['ProductType'] == 4 && $team){
			$this->team_model->joinTeam($res);
		}else{
			$this->team_model->creatTeam($res,$pro);
		}
		$this->returnJson($res);
	}

	public function creatOrderTuanJoin(){
		extract($this->input->post());
		$this->load->model('product_model');
		$this->load->model('team_model');
		$this->load->model('order_model');
		$this->load->model('shop_model');

		$arr = array('ProductId' => $ProductId);
		$pro = $this->product_model->getProduct($arr);
		$shop = $this->shop_model->getShop($arr = array('id' => $pro['ShopId']));
		if(!$pro){$this->returnJson(array('Message'=>'获取产品信息失败'),'f');return;}
		if($pro['IsDisable']!=0){$this->returnJson(array('Message'=>'产品已禁用'),'f');return;}
		if($pro['IsForSale']!=1){$this->returnJson(array('Message'=>'产品已下架'),'f');return;}
		if($pro['IsChecked']!=1){$this->returnJson(array('Message'=>'产品正在审核中'),'f');return;}
		if($pro['IsShopDisable']==1){$this->returnJson(array('Message'=>'店铺已关闭'),'f');return;}

		$add = $this->user_model->getAddress($this->_member_userId,$AddressId);
		if(!$add){$this->returnJson(array('Message'=>'获取地址信息失败'),'f');return;}
		//if($add['CityCode']!=$shop['CityCode']){$this->returnJson(array('Message'=>'地址信息超出服务范围，请重新选择'),'f');return;}

		//检查拼团状态
		$team = $this->team_model->getTeamInfo($TeamId);
		if($team['TeamStatus']!=2){$this->returnJson(array('Message'=>'拼团未开始或已结束'),'f');return;}
		if($team['EndTime']<=(time()+20)){$this->returnJson(array('Message'=>'拼团结束时间已到'),'f');return;}
		if($team['MaxOrderCount']<=count($team['Members'])){$this->returnJson(array('Message'=>'拼团已满员，下次再来吧！'),'f');return;}
		
		//检查是否已参团
		$orderSelArr = array('UserId'=>$this->_member_userId,'TeamId'=>$TeamId);
		$order = $this->order_model->getOrderInfo($orderSelArr);
		/** 测试自参团，抽奖 **/
		if($order){
			if($order['OrderStatus']!=6){//存在非取消的订单
				$this->returnJson(array('Message'=>'您已经参加此拼团，请勿重复参加'),'f');
				return;
			}
		}
		/** 测试自参团，抽奖 **/


		$ProductFee = $pro['Prices']['Team'] * $ProductNum;
		$arr = array(
			'ShopId' => $pro['ShopId'],
			'ShopName' => $pro['ShopName'],
			'UserId' => $this->_member_userId,
			'CityCode' => $add['CityCode'],
			'CityName' => $add['CityName'],
			'OrderType' => 3, //参团
			'ProductId' => $pro['ProductId'],
			'ProductRealId' => $pro['id'],
			'ProductCount' => $ProductNum,
			'ProductInfo' => array(
				'ImageMin' => $pro['ImageMinReal'],
				'ProductName' => $pro['ProductName'],
				'Prices' => $pro['Prices']['Team'],
				'PricesAll' => $pro['Prices'],
				'DeliverAddress' => $pro['DeliverAddress'],
				'Description' => $pro['Description'],
				'ProductType' => $pro['ProductType']
			),

			'Remark' => $Remark,
			'DeliveryInfo' => $add,
			'ProductFee' => $ProductFee,
			'freightFee' => $pro['freightAmout'],
			'OrderFee' => $ProductFee + $pro['freightAmout'],
			'TeamId' => $TeamId
		);
		
		$res = $this->order_model->creatOrder($arr);
		$this->returnJson($res);
	}
	
	//购物车生成订单
	public function creatOrderByCart(){
		extract($this->input->post());
		$this->load->model('product_model');
		$this->load->model('cart_model');
		$this->load->model('shop_model');
		$this->load->model('order_model');

		$add = $this->user_model->getAddress($this->_member_userId,$AddressId);
		if(!$add){$this->returnJson(array('Message'=>'获取地址信息失败'),'f');return;}

		$arr = array(
			'CityCode' => $this->_user_citycode,
			'UserId' => $this->_member_userId,
			'Select' => 1
		);
		$cart = $this->cart_model->getCartList($arr);
		if($cart['Count']>0){
			foreach($cart['List'] as $v){
				$ShopRealId = $v['ShopRealId'];
				$ProductIdsArr[] = $v['ProductId'];
				$ProductRealIdsArr[] = '"'.$v['ProductRealId'].'"';
				$cartInfo[$v['ProductId']]['ProductCount'] = $v['ProductCount'];
				$cartInfo[$v['ProductId']]['CartId'] = $v['id'];
			}
			$ProductIds = implode(',',$ProductIdsArr);
			$sel = array('ImageMin','ProductName','ProductId','Description','Prices','freightAmout','ShopName','ShopId','DeliverAddress','ProductType');
			$list = $this->product_model->getProductList(array('ProductId'=>'['.$ProductIds.']'),'','',$sel);
			list($list,$info) = $this->cart_model->getCartInfo($list['List'],$cartInfo);
			$shop = $this->shop_model->getShop(array('id' => $ShopRealId));
			//if($add['CityCode']!=$shop['CityCode']){$this->returnJson(array('Message'=>'地址信息超出服务范围，请重新选择'),'f');return;}
			if($info['PriceProduct']>=$shop['FreightFreeAmout']){
				$info['PricesAll'] -= $info['freightAmout'];
				$info['freightAmout'] = 0;
			}
		}else{return;}

		if(!$shop)return;
		if($list){
			foreach($list as $k=>$v){
				$list[$k]['Prices'] = $v['Prices']['Normal'];
				$list[$k]['PricesAll'] = $v['Prices'];
			}
		}

		$arr = array(
			'ShopId' => $shop['id'],
			'ShopName' => $shop['ShopName'],
			'UserId' => $this->_member_userId,
			'CityCode' => $shop['CityCode'],
			'CityName' => $shop['CityName'],
			'OrderType' => 1,
			'ProductId' => $ProductIdsArr,
			'ProductRealId' => $ProductRealIdsArr,
			'ProductCount' => $info['CountAll'],
			'ProductInfo' => array(
				'ImageMin' => $list[0]['ImageMinReal'],
				'ProductName' => $list[0]['ProductName'],
				'Prices' => $list[0]['Prices'],
				'PricesAll' => $list[0]['PricesAll'],
				'DeliverAddress' => $list[0]['DeliverAddress'],
				'Description' => $list[0]['Description'],
				'ProductType' => $list[0]['ProductType']
			),
			//'ProductList' => $list,

			'Remark' => $Remark,
			'DeliveryInfo' => $add,
			'ProductFee' => $info['PriceProduct'],
			'freightFee' => $info['freightAmout'],
			'OrderFee' => $info['PriceProduct'] + $info['freightAmout']
		);

		$res = $this->order_model->creatOrder($arr);
		if($res && $res['Order']){
			$upd = array(
				'id' => $res['Order']['id'],
				'ProductList' => $list,
			);
			$this->order_model->updOrder($upd);
			$this->cart_model->delList($cart['List']);
		}
		$this->returnJson($res);
	}

	public function orderList($type = 1){
		$arr = array(
			'UserId' => $this->_member_userId
		);
		$sel = array(
			'OrderId','ProductCount','ProductInfo','ProductId','OrderFee','OrderStatus','TeamId','OrderType'
		);
		$res = $this->order_model->getOrderList($arr,'','',$sel);
		$list = $this->setOrderListType($res['List']);

		$data['type'] = $type;
		$data['list'] = $list;
		$this->view('space/order_list',$data);
	}

	public function orderinfo($orderId){
		$order = $this->order_model->getOrderInfo(array('OrderId'=>$orderId));
		
		if($order['OrderStatus']==1){$order['statusMsg'] = '等待买家付款';}
		if($order['OrderStatus']==2){$order['statusMsg'] = '等待拼团完成';}
		if($order['OrderStatus']==3){$order['statusMsg'] = '等待发货';}
		if($order['OrderStatus']==4){$order['statusMsg'] = '等待收货';}
		if($order['OrderStatus']==5){$order['statusMsg'] = '订单已完成';}
		if($order['OrderStatus']==6){$order['statusMsg'] = '订单已取消';}
		if($order['OrderStatus']==7){$order['statusMsg'] = '订单已退款';}

		if($order['OrderType']==1){$order['realPrice'] = 'Normal';$order['showPrice'] = 'Market';}
		if($order['OrderType']==2){$order['realPrice'] = 'Team';$order['showPrice'] = 'Normal';}
		if($order['OrderType']==3){$order['realPrice'] = 'Team';$order['showPrice'] = 'Normal';}

		if($order['freightFee']==0){$order['freightMsg'] = '（免运费）';}else{$order['freightMsg'] = '（运费 '.$order['freightFee'].'元）';}

		$data['order'] = $order;
		if($order['ShopId']){
			$this->load->model('shop_model');
			$shop = $this->shop_model->getShop($order['ShopId']);
			$data['shop'] = $shop;
		}
		$this->view('space/order_info',$data);
	}
	
	//根据订单列表状态分配列表数组
	public function setOrderListType($list){
		$res = array(array(),array(),array(),array(),array());
		foreach($list as $v){
			if($v['OrderStatus']==1){$res[1][]=$v;}
			if($v['OrderStatus']==2){$res[2][]=$v;}
			if($v['OrderStatus']==3){$res[2][]=$v;}
			if($v['OrderStatus']==4){$res[3][]=$v;}
			if($v['OrderStatus']==5){$res[4][]=$v;}
		}
		return $res;
	}

	public function cancerOrder(){
		extract($this->input->post());
		$res = $this->order_model->orderCancel($id);
		$this->returnJson($res);
	}

	public function shouhuoOrder(){
		extract($this->input->post());
		$res = $this->order_model->orderShouhuo($id);
		$this->returnJson($res);
	}

	/*public function payOrder(){
		extract($this->input->post());

		$arr = array(
			'orderId' => $id,
			'payAmount' => 'payAmount',
			'PayTradeNo' => 'PayTradeNo'
		);

		$res = $this->order_model->payNotifyWechat($arr);
		$this->returnJson($res);
	}*/

}