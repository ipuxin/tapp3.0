<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** Banner图类 ***

创建 2016-10-07 刘深远 

*** ***/

class Banner_model extends MY_Model {

	private $_model;
	private $_admin_prefix;
	private $_admin_password;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Banner');

		$this->_model = array(
			'Name' => 'str',
			'Url' => '',
			'Paixu' => 'num',
			'IsDisable' => 'num',
			'CreatTime' => 'time'
		);
	}

	function getBannerList($arr=array(),$order=array(),$limit=array(),$sel=array()){
		$arr['IsDisable'] = 0;
		if(!$order)$order = array('Paixu','DESC');

		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetBannerList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;
		return $data;
	}

	function resetBannerList($list){
		foreach($list as $v){
			$listr[] = $this->resetBanner($v);
		}
		return $listr;
	}

	function resetBanner($v){
		if($v['Url'])$v['UrlShow'] = $this->config->item('res_url').$v['Url'];
		return $v;
	}

	function addBanner($arr){
		$arr = $this->setModel($arr);
		if($cate = $this->add($arr)){
			$Data['Banner'] = $cate;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function updBanner($arr,$where= array()){
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
			if(!$arr['CreatTime']){$arr['CreatTime'] = time();}
			if(!$arr['IsDisable']){$arr['IsDisable'] = 0;}
		}

		if($type=='upd'){
			
		}
		return $arr;
	}
	
}