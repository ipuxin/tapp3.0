<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 用户类 ***

创建 2016-07-29 刘深远 

*** ***/

class User_model extends MY_Model {

	private $_model;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('User');
		$this->_model = array(
			'UnionId' => 'str',
			'OpenId' => 'str',
			'Subscribe' => 'num', //是否关注
			'NickName' => 'str',
			'UserInfo' => 'array',
			'Thumbnail' => 'url', //头像
			'Favorites' => 'array',
			'Addresses' => 'array'
		);
	}

	function resetUser($user){

		$userinfo = array(
			'Sex' => $user['sex'],
			'City' => $user['city'],
			'Province' => $user['province'],
			'Country' => $user['country'],
			'Subscribe_time' => $user['subscribe_time']
		);
		$arr = array(
			'UnionId' => $user['unionid'],
			'OpenId' => $user['openid'],
			'Subscribe' => $user['subscribe'],
			'NickName' => $user['nickname'],
			'Thumbnail' => $user['headimgurl'],
			'UserInfo' => $userinfo
		);

		if($userId = $this->checkUser($user['openid'])){
			if($this->update($userId,$arr)){
				$arr['id'] = $userId;
				$this->putSession($arr);
			}
		}else{
			if($user = $this->add($arr)){
				$this->putSession($user);
			}
		}
	}

	function loginUser($user){
		if($user_app = $this->checkUserApp($user['unionid'])){
			//$this->putSession($user);
			$userId = $user_app['id'];
			$user_app['OpenId'] = $user['openid'];
			$user_app['Subscribe'] = $user['subscribe'];
			$user_app['UserInfo']['Subscribe_time'] = $user['subscribe_time'];
			if($this->update($userId,$user_app)){
				$arr['id'] = $userId;
				$this->putSession($user_app);
			}
		}else{
			if(!$this->checkUser($user['openid'])){
				$userinfo = array(
					'Sex' => $user['sex'],
					'City' => $user['city'],
					'Province' => $user['province'],
					'Country' => $user['country'],
					'Subscribe_time' => $user['subscribe_time']
				);
				$arr = array(
					'UnionId' => $user['unionid'],
					'OpenId' => $user['openid'],
					'Subscribe' => $user['subscribe'],
					'NickName' => $user['nickname'],
					'Thumbnail' => $user['headimgurl'],
					'UserInfo' => $userinfo
				);
				if($user = $this->add($arr)){
					$this->putSession($user);
				}
			}
		}
	}

	function addUser($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){$Data['ErrorCode'] = $arr;return $Data;}
		if($user = $this->add($arr)){
			return  $user;
		}
		return false;
	}

	function getUserInfo($arr,$sel=array()){
		return $this->getRow($arr,$sel);
	}

	function checkAppLogined($userId){
		$userInfo = $this->getUserInfo($userId);
		return $userInfo['AppLogined'];
	}

	function checkUserUnionId($unionId){
		$user = $this->getUserInfo(array('UnionId'=>$unionId));
		if($user){
			$this->bindAppLogined($user['id']);
			return $user;
		}else{
			return false;
		}
	}

	//标记此用户使用app登陆过
	function bindAppLogined($userId){
		$arr = array(
			'AppLogined' => 1
		);
		$this->update($userId,$arr);
	}

	function addAddress($userid,$arr,$first=0){
		$Address = $this->getAddress($userid,'all');
		if($first && $Address){
			array_unshift($Address,$arr);
		}else{
			$Address[] = $arr;
		}
		if($this->update($userid,array('Addresses'=>$Address))){
			return $this->resetAddress($arr);
		}
		return false;
	}

	function addFavorite($userid,$productId){
		$Favorite = $this->session->Favorites;
		$Favorite[] = $productId;
		$Favorite = array_unique($Favorite); //去重
		if($this->update($userid,array('Favorites'=>$Favorite))){
			$this->session->set_userdata('Favorites',$Favorite);
			return $Favorite;
		}
		return false;
	}

	function delFavorite($userid,$productId){
		$Favorite = $this->session->Favorites;
		if($Favorite){
			foreach($Favorite as $k=>$v){
				if($v==$productId){
					unset($Favorite[$k]);
				}
			}
			if($this->update($userid,array('Favorites'=>$Favorite))){
				$this->session->set_userdata('Favorites',$Favorite);
				return $Favorite;
			}
		}
		return false;
	}

	function delAddress($userId,$addressId){
		$Address = $this->getUserInfo($userId,array('Addresses'));
		$Address = $Address['Addresses'];
		if($Address){
			foreach($Address as $k=>$v){
				if($v['AddressId']==$addressId){unset($Address[$k]);break;}
			}
			if($this->update($userId,array('Addresses'=>$Address))){
				return true;
			}
			return false;
		}
		return false;
	}

	function updAddress($userId,$arr,$addressId){
		$Address = $this->getUserInfo($userId,array('Addresses'));
		$Address = $Address['Addresses'];
		if($Address){
			foreach($Address as $k=>$v){
				if($v['AddressId']==$addressId){
					$Address[$k] = $arr;break;
				}
			}
			if($this->update($userId,array('Addresses'=>$Address))){
				return $this->resetAddress($arr);
			}
			return false;
		}
		return false;
	}

	function getAddress($userId,$addressId = '',$CityCode = ''){
		$Address = $this->getUserInfo($userId,array('Addresses'));
		$Address = $Address['Addresses'];
		if($CityCode){
			$Address = $this->getAddressCity($Address,$CityCode);
		}
		if($Address){
			if($addressId==='all'){
				return $this->resetAddressList($Address);
			}
			if($addressId===0){return $this->resetAddress($Address[0]);}
			if($addressId){
				foreach($Address as $v){
					if($v['AddressId']==$addressId)return $this->resetAddress($v);
				}
			}
			return $this->resetAddressList($Address);
		}else{
			return false;
		}
	}
	
	//获取特定城市的地址列表
	function getAddressCity($Address,$CityCode){
		if($CityCode=='110100'){
			$CityCode = array('110100','110200');
		}elseif($CityCode=='120100'){
			$CityCode = array('120100','120200');
		}elseif($CityCode=='310100'){
			$CityCode = array('310100','310200');
		}else{
			$CityCode = array($CityCode);
		}
		if($Address)foreach($Address as $v){
			if(in_array($v['CityCode'],$CityCode)){
				$AddressReturn[] = $v;
			}
		}
		return $AddressReturn;
	}

	function resetAddressList($list = array()){
		foreach($list as $v){
			$rlist[] = $this->resetAddress($v);
		}
		return $rlist;
	}

	function resetAddress($arr){
		$CityName = $arr['CityName'];
		if(strstr($CityName,'市辖区')){$CityName = '';}
		$arr['RealAddress'] = $arr['ProviceName'].$CityName.$arr['DistrictName'].$arr['Address'];
		return $arr;
	}

	function delUser($id){
		if(is_array($id)){$Data['ErrorCode'] = 103;return $Data;}
		if($delnum = $this->del($id)){
			$Data['Num'] = $delnum;
		}else{
			$Data['ErrorCode'] = 103;
		}
		return $Data;
	}

	function setModel($arr){
		if(!$arr['OpenId']){
			return 104;
		}else{
			if($this->checkHas('OpenId',$arr['OpenId'])){
				return 105;
			}
			//判断checkHas执行没有返回
			if(!($this->_return_code===0)){
				return 105;
			}
		}
		return $arr;
	}

	//将登陆信息保存到session
	function putSession($user){
		$userInfo = array(
			'UserId'    => $user['id'],
			'UnionId'   => $user['UnionId'],
			'OpenId'    => $user['OpenId'],
			'Subscribe' => $user['Subscribe'],
			'NickName'  => $user['NickName'],
			'Thumbnail' => $user['Thumbnail'],
			'UserInfo'  => $user['UserInfo']
		);

		if($user['Favorites']){$userInfo['Favorites'] = $user['Favorites'];}
		if($user['Addresses']){$userInfo['Addresses'] = $user['Addresses'];}
		$this->session->set_userdata($userInfo);
	}
	
	//检查登陆信息
	function checkUser($openId){
		$arr = array(
			'OpenId'  => $openId
		);
		$user = $this->getRow($arr);
		if($user){
			$this->putSession($user);
			return $user['id'];
		}
		return false;
	}

	//检查是否在app上面创建过用户
	function checkUserApp($unionId = ''){
		if(!$unionId)return false;
		$arr = array(
			'UnionId'  => $unionId
		);
		$user = $this->getRow($arr);
		if($user){
			//$this->putSession($user);
			//return $user['id'];
			return $user;
		}
		return false;
	}

}