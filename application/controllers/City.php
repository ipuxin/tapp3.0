<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 城市控制文件 ***

创建 2016-08-03 刘深远

*** ***/

class City extends MY_Controller {

	public function __construct(){
		header("Content-Type: text/html; charset=UTF-8");
		parent::__construct();

		$this->load->model('divisions_model');
	}

	public function cityList(){
		$this->view('city_select',$data);
	}
	
	//根据省份code获取城市列表
	public function getCityList($code = ''){
		if(empty($code)){
			$res = array('Message'=>'缺少code字段');
			$this->returnJson($res,'f');
			return;
		}
		$data = $this->divisions_model->getCityList($code);

		if(!$data['Result'] || count($data['Result'])==0){
			$res = array('Message'=>'没有返回结果');
			$this->returnJson($res,'f');
			return;
		}
		echo json_encode($data);
	}

	//根据城市code获取区域列表
	public function getDistrictList($code = ''){
		if(empty($code)){
			$res = array('Message'=>'缺少code字段');
			$this->returnJson($res,'f');
			return;
		}
		$data = $this->divisions_model->getDistrictList($code);

		if(!$data['Result'] || count($data['Result'])==0){
			$res = array('Message'=>'没有返回结果');
			$this->returnJson($res,'f');
			return;
		}
		echo json_encode($data);
	}

	//按首字母排序获取城市列表
	public function getCityListByLetter(){
		$data = $this->divisions_model->getCityListByLetter();
		echo json_encode($data);
	}

	//获取热门城市
	public function getHotCityList(){
		$data = $this->divisions_model->getHotCityList();
		echo json_encode($data);
	}
	
	//根据坐标获取城市定位信息
	public function getLocMsg(){
		extract($this->input->post());
		$res = $this->divisions_model->getLocMsg($lng,$lat);

		if($res['Result']['City']['Id']=='310200'){
			$res['Result']['City']['Id'] = '310100';
			$res['Result']['City']['Name'] = '上海市';
		}

		if($res['Result']['City']['Id']=='120200'){
			$res['Result']['City']['Id'] = '120100';
			$res['Result']['City']['Name'] = '天津市';
		}

		if($res['Result']['City']['Id']=='500200'){
			$res['Result']['City']['Id'] = '500100';
			$res['Result']['City']['Name'] = '重庆市';
		}

		if($res['Result']){
			$res['Result']['CityName'] = $res['Result']['City']['Name'];
			$res['Result']['CityCode'] = $res['Result']['City']['Id'];
			$res['Result']['ProvinceName'] = $res['Result']['Province']['Name'];
			$res['Result']['ProvinceCode'] = $res['Result']['Province']['Id'];
		}

		echo json_encode($res);
	}

	//loc 0,保存当前地址信息 1,保存全部地址信息 2,保存定位地址信息并返回是否相同及本身
	public function saveCityInfo($loc = 0){
		$arr = $this->input->post();
		$arr = array(
			'CityName' => $arr['CityName'],
			'CityCode' => $arr['CityCode'],
			'ProvinceName' => $arr['ProvinceName'],
			'ProvinceCode' => $arr['ProvinceCode']
		);
		$Code = 0;
		if(strstr($arr['CityName'],$arr['ProvinceName'])){
			$arr['CityName'] = $arr['ProvinceName'];
		}
		if($loc==1){foreach($arr as $k=>$v){
			$arr[$k.'_loc'] = $v;
		}}
		if($loc==2){
			$City = $arr;
			foreach($arr as $k=>$v){
				$arr2[$k.'_loc'] = $v;
			}
			$arr = $arr2;
			if($this->_user_citycode != $City['CityCode']){
				$Code = 2;
			}
		}
		$this->session->set_userdata($arr);
		echo json_encode(array('Code'=>$Code,'City'=>$City));
	}

}