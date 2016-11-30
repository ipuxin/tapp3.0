<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 省市区地址类 ***

创建 2016-02-26 刘深远 

*** ***/

class Divisions_model extends MY_Model {

	private $_model;
	private $_data;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setApiType('geography.');
		$this->setTable('division',true);

		$this->_model = array(
			'Cities' => 'array',
			'Districts' => 'array',
			'Code' => 'num',
			'Location' => 'arr',
			'Name' => 'str'
		); 
	}

	function getHot(){
		$this->setApiType('restful.');
		$this->setTable('HotCity');
		$list = $this->getList();
		return $list;
	}

	function getProviceList(){
		$url = $this->getApiBase().$this->getApiType().$this->getApiTable().'?&@Code&@Name';
		$res = $this->getcurl($url);
		return $res['Result'];
	}

	function getCityList($code){
		$url = $this->getApiBase().$this->getApiType().$this->getApiTable().'?Id='.$code.'&@Cities.Name&@Cities.Id@Name';
		$res = $this->getcurl($url);
		if($this->_return_code===0){
			$res['Result'] = $res['Result'][0]['Cities'];
		}
		foreach($res['Result'] as $k=>$v){
			$res['Result'][$k]['Code'] = $v['Id'];
		}
		return $res;
	}

	function getDistrictList($code){
		$CityCode = substr($code,0,2).'0000';
		$CityList = $this->getCityList($CityCode);
		foreach($CityList['Result'] as $v){
			if($v['Id']==$code){
				$DistrictList = $v['Districts'];break;
			}
		}
		foreach($DistrictList as $k=>$v){
			$DistrictList[$k]['Code'] = $v['Id'];
		}
		
		$res['Result'] = $DistrictList;
		$res['Code'] = 0;

		return $res;
	}

	function getLocMsg($lng,$lat){
		$url = $this->getApiBase().'geography.locate?x='.$lng.'&y='.$lat;
		$response = $this->getcurl($url);
		return $response;
	}

	function getHotCityList(){
		$url = $this->getApiBase().'Data.Divisions.Query?Cities.Hot=1&@Cities.Name@Cities.Code@Name@Code@Cities.Hot';
		$response = $this->getcurl($url);
		return $response;
	}

	function getCityListByLetter(){
		$url = $this->getApiBase().'restful.city?~Letter=1@Name@ProvinceId@ProvinceName@Letter';
		$response = $this->getcurl($url,1);
		if($response['Result']){
			foreach($response['Result'] as $k=>$v){
				$response['Result'][$k]['CityName'] = $v['Name'];
				$response['Result'][$k]['CityCode'] = $v['id'];
			}
		}
		return $response;
	}
	
}