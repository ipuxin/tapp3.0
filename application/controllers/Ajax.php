<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 通用ajax调用控制文件 ***

创建 2016-08-06 刘深远

*** ***/

class Ajax extends MY_Controller {

	public function test(){
		$this->load->model('order_model');

		$OrderId[] = 6276;
		$OrderId[] = 4649;
		$OrderId[] = 6290;
		$arr = array('OrderStatus' => 5); //5已签收 6 已取消 7 已退款
		$where = array('OrderId'=>$OrderId);
		$this->order_model->updOrder($arr,$where);
	}

	public function prepayWechat(){
		extract($this->input->post());
		$this->load->model('order_model');
		
		//检查支付金额是否为零
		$OrderInfo = $this->order_model->getOrderInfo(array('OrderId'=>$OrderId,'UserId'=>$this->_member_userId));

		if($OrderInfo){
			//如果是零元活动或者使用优惠券 订单总金额为0
			if($OrderInfo['OrderStatus']!=1){
				$res['msg'] = '订单状态异常';
				$res['result'] = 'False';
			}else{
				if($OrderInfo['OrderFee']==0){
					$arr=array(
						'orderId' => $OrderInfo['id'],
						'payType' => '免付',
						'payTradeNo' => '',
						'payAmount' => 0
					);

					$res = $this->order_model->payNotifyWechat($arr);
					$this->returnJson($res);
					return;
				}else{
					$json = $this->order_model->prepayWechat($OrderInfo);
					$res['json'] = $json;
					$res['result'] = 'Success';
				}
			}
		}else{
			$res['msg'] = '订单不存在';
			$res['result'] = 'False';
		}
		echo json_encode($res);
	}

	public function getUserInfo(){
		extract($this->input->post());
		$this->saveCode($Code);
		//$Code = '001FRp1J1smB3b0wwP4J1w1l1J1FRp1n';
		$this->getAccessToken($Code);
	}

	public function saveCode(){
		extract($this->input->post());

		$dir = $this->config->item('data_log_path').'App';
		if(!file_exists($dir)){mkdir($dir,'0777',true);}
		$fileName = $dir.'/code_'.date('Y-m-d').'.txt';

		$word = 'Code:'.$Code."\r\n";
		$fp = fopen($fileName,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\r\n".$word."\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

	public function CreatShopShenqing(){
		$arr = $this->input->post();

		$this->load->model('shop_shenqing_model');
		unset($arr['id']);
		if($arr)$this->shop_shenqing_model->add($arr);

		if($arr['type']){
			$shenhe = $arr;
			$cityinfo = explode('=',$shenhe['cityname']);
			$shenhe['CityCode'] = $cityinfo[0];
			$shenhe['CityName'] = $cityinfo[1];
			$shenhe['ShopType'] = $arr['type'];
			$shenhe['CreatTime'] = time();
			unset($shenhe['cityname']);
			$this->load->model('shop_shenhe_model');

			foreach($shenhe as $k=>$v){
				$shenhe[$k] = str_replace('/upload/image/','http://www.pingoing.cn/upload/image/',$v);
			}

			$this->shop_shenhe_model->add($shenhe);

			$data['Success'] = 1;
		}else{
			$data['Success'] = 0;
		}

		$this->view('shenqing',$data);
	}

	public function getproductCateList(){
		$this->load->model('category_model');
		$cateList = $this->category_model->getCategoryAll();
		$data['CateList'] = $cateList;
		echo json_encode($data);
	}

	public function getProductListShop(){
		extract($this->input->post());
		$this->load->model('product_model');

		$arr['ShopId'] = $ShopId;
		$arr['IsHide!'] = 1;
		$arr['IsDisable'] = 0;
		$arr['IsForSale'] = 1;
		$arr['IsChecked'] = 1;
		$arr['IsShopDisable!'] = 1; 

		$sel = array('ProductId','ProductName','Description','Prices','ImageMin','ImageBig','SalesCount','StorageCount','ProductType');
		$res = $this->product_model->getProductList($arr,'','',$sel);
		
		echo json_encode($res);
	}

	public function getProductListIndex(){
		extract($this->input->post());
		$this->load->model('product_model');

		$arr['CityCode'] = $this->_user_citycode;
		$arr['IsHide!'] = 1;
		$arr['IsDisable'] = 0;
		$arr['IsForSale'] = 1;
		$arr['IsChecked'] = 1;
		$arr['ProductType'] = 1; 
		$arr['IsShopDisable!'] = 1; 

		$sel = array('ProductId','ProductName','Prices','ImageBig','SalesCount','StorageCount','ProductType','CreatTime');
		$res = $this->product_model->getProductList($arr,'','',$sel);
		
		$res['List'] = $this->product_model->ResetPaixu($res['List'],100);
		
		echo json_encode($res);
	}

	public function getProductList(){
		extract($this->input->post());
		$this->load->model('product_model');
		
		$arr['CityCode'] = $this->_user_citycode;
		$arr['IsHide!'] = 1;
		$arr['IsDisable'] = 0;
		$arr['IsForSale'] = 1;
		$arr['IsChecked'] = 1;
		$arr['ProductType'] = 1; //普通单买宝贝
		$arr['IsShopDisable!'] = 1; 

		$sel = array('ProductId','ProductName','Prices','ImageBig','SalesCount','StorageCount','ProductType','CreatTime');
		$res = $this->product_model->getProductList($arr,'','',$sel);
		
		echo json_encode($res);
	}

	public function getProductPinTuanList(){
		extract($this->input->post());
		$this->load->model('product_model');
		
		$arr['CityCode'] = $this->_user_citycode;
		$arr['IsHide!'] = 1;
		$arr['IsDisable'] = 0;
		$arr['IsForSale'] = 1;
		$arr['IsChecked'] = 1;
		$arr['ProductType'] = $ProductType; //拼团宝贝
		$arr['IsShopDisable!'] = 1; 

		$sel = array('ProductId','ProductName','Prices','ImageBig','ImageMin','SalesCount','StorageCount','ProductType','CreatTime','TeamMemberLimit','isEnd');
		$res = $this->product_model->getProductList($arr,'','',$sel);

		$res['List'] = $this->product_model->ResetPaixu($res['List'],100);
		
		echo json_encode($res);
	}

	public function getCateProductList(){
		extract($this->input->post());
		$this->load->model('product_model');
		
		$arr['CityCode'] = $this->_user_citycode;
		$arr['IsHide!'] = 1;
		$arr['IsDisable'] = 0;
		$arr['IsForSale'] = 1;
		$arr['IsChecked'] = 1;
		$arr['Category2'] = $Category2;
		$arr['ProductType'] = 1; //普通单买宝贝
		$arr['IsShopDisable!'] = 1; 

		if($PaixuType=='xiaoliang'){$orderBy = array('SalesCount.Real','DESC');}
		if($PaixuType=='new'){$orderBy = array('CreatTime','DESC');}
		if($PaixuType=='price'){$orderBy = array('Prices.Normal','DESC');}
		if($PaixuType=='price2'){$orderBy = array('Prices.Normal','ASC');}

		$sel = array('ProductId','ProductName','Prices','ImageMin','ImageBig','SalesCount','StorageCount','ProductType','CreatTime','TeamMemberLimit');
		$res = $this->product_model->getProductList($arr,$orderBy,'',$sel);

		if($PaixuType=='main'){$res['List'] = $this->product_model->ResetPaixu($res['List'],100);}
		
		echo json_encode($res);
	}

	public function delAddress(){
		extract($this->input->post());
		$res = $this->user_model->delAddress($this->_member_userId,$addressId);
		echo json_encode($res);
	}
	
	//type : first list
	public function getUserAddress($type='list'){
		extract($this->input->post());
		if($type=='first'){
			$addressId = 0;
		}
		$address = $this->user_model->getAddress($this->_member_userId,$addressId,$CityCode);

		echo json_encode($address);
	}
	public function getUserChooseAddress(){
		extract($this->input->post());
		$address =  $this->user_model->getAddress($this->_member_userId,$AddressId);
		echo json_encode($address);
	}

	public function getHotCitys(){
		$this->load->model('divisions_model');
		$list = $this->divisions_model->getHot($code);
		$res['List'] = $list;
		echo json_encode($res);
	}

	public function getCityList($code){
		$this->load->model('divisions_model');
		$list = $this->divisions_model->getCityList($code);
		echo json_encode($list);
	}

	public function getDistrictList($code){
		$this->load->model('divisions_model');
		$list = $this->divisions_model->getDistrictList($code);
		echo json_encode($list);
	}

	public function address($type='add'){
		$this->load->model('user_model');
		extract($this->input->post());
		if(!$this->_member_userId)return false;
		$arr = array(
			'AddressId' => 'add_'.time().rand(100,999),
			'RealName' => $RealName,
			'Mobile' => $Mobile,
			'ProviceCode' => $ProviceCode,
			'ProviceName' => $ProviceName,
			'CityCode' => $CityCode,
			'CityName' => $CityName,
			'DistrictCode' => $DistrictCode,
			'DistrictName' => $DistrictName,
			'Address' => $Address,
			'Type' => $Type
		);
		if($type=='add'){
			$res = $this->user_model->addAddress($this->_member_userId,$arr,1);
			$res['type'] = "add";
		}elseif($type=='upd'){
			$arr['AddressId'] = $AddressId;
			$res = $this->user_model->updAddress($this->_member_userId,$arr,$AddressId);
			$res['type'] = "upd";
		}
		echo json_encode($res);
	}

}