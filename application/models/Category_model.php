<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 店铺类 ***

创建 2016-09-11 刘深远 

*** ***/

class Category_model extends MY_Model {

	private $_model;
	private $_admin_prefix;
	private $_admin_password;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Category');

		$this->_model = array(
			'CateName' => 'str',
			'CateLevel' => 'num',
			'CateParent' => '',
			'CateSorting' => 'num',

			'IsDisable' => $IsDisableArr,
		);
	}

	function getCategoryList($arr,$order=array(),$limit=array(),$sel=array()){
		if(!$order)$order = array('CateSorting','DESC');

		if($this->session->userdata('UserType')==2){
			$CityCode = $this->session->userdata('CityCode');
			$CityCodeNum = intval($CityCode);
			$arr['CityCode'] = $CityCodeNum;
		}

		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function getCategoryAll(){
		$parentList = $this->getCategoryList(array('CateLevel'=>1));
		$list = $parentList['List'];
		$childList = $this->getCategoryList(array('CateLevel'=>2,'IsDisable'=>0));
		$clist = $childList['List'];
		
		foreach($list as $k=>$v){$nlist[$v['id']] = $v;}
		if($clist)foreach($clist as $v){
			$nlist[$v['CateParent']]['Child'][] = $v;
		}

		return $nlist;
	}

	function resetList($list){
		foreach($list as $v){
			$listr[] = $this->resetCate($v);
		}
		return $listr;
	}

	function resetCate($v){
		if($v['ImgUrl']){
			$v['ImgUrlShow'] = $this->config->item('res_url').$v['ImgUrl'];
		}else{
			$v['ImgUrlShow'] = '';
		}
		return $v;
	}

	function addCategory($arr){
		$arr = $this->setModel($arr);
		if($cate = $this->add($arr)){
			$Data['Cate'] = $cate;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function updCategory($arr,$where= array()){
		$arr = $this->setModel($arr,'upd');
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}

	function setModel($arr,$type='add'){
		if($type=='add'){
			if(!$arr['CateParent']){$arr['CateParent'] = '';}
			if(!$arr['CateLevel']){$arr['CateLevel'] = 1;}
			if(!$arr['CateSorting']){$arr['CateSorting'] = 0;}
			if(!$arr['IsDisable']){$arr['IsDisable'] = 0;}
		}

		if($type=='upd'){
			
		}
		return $arr;
	}
	
}