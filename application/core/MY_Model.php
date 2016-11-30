<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** model基类 ***

创建 2016-07-29 刘深远 

*** ***/

class MY_Model extends CI_Model {
	
	private $_api_base;
	private $_api_type;
	private $_api_url;
	private $_api_action;
	private $_api_action_list;
	private $_api_query_time;
	private $_log_query_time;

	public $_api_table;
	public $_api_table_base;
	public $_api_table_prefix;

	public $_return_code;
	public $_return_Success;
	public $_return_Message;
	public $_return_Result;
	public $_return_Count;
	public $_return_Limit;
	public $_return_Skip;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		$this->_api_base = $this->config->item('db_api_base');
		$this->_api_type = $this->config->item('db_api_type');
		$this->_api_action_list  = $this->config->item('db_api_action');
		$this->_api_table_prefix = $this->config->item('db_table_prefix');
		$this->_api_query_time = $this->config->item('db_max_query_time');
		$this->_log_query_time = $this->config->item('db_log_query_time');

		$this->_wx_appid = $this->config->item('wx_appid');
		$this->_wx_secret = $this->config->item('wx_secret');

		$this->_wx_kf_appid = $this->config->item('wx_kf_appid');
		$this->_wx_kf_secret = $this->config->item('wx_kf_secret');

		$this->_wx_pay_shanghu = $this->config->item('wx_pay_shanghu');
		$this->_wx_pay_shanghu_key = $this->config->item('wx_pay_shanghu_key');

		$this->_wx_kf_pay_shanghu = $this->config->item('wx_kf_pay_shanghu');
		$this->_wx_kf_pay_shanghu_key = $this->config->item('wx_kf_pay_shanghu_key');

		$this->setUserInfo();
	}

	function setUserInfo(){
		$this->_member_userId = $this->session->userdata('UserId');
		$this->_member_unionId = $this->session->userdata('UnionId');
		$this->_member_openId = $this->session->userdata('OpenId');
		$this->_member_nickName = $this->session->userdata('NickName');
		$this->_member_headimgurl = $this->session->userdata('Thumbnail');
		if(!$this->_member_nickName){$this->_member_nickName = '游客';}
		if(!$this->_member_headimgurl){$this->_member_headimgurl = $this->config->item('static_file_path')."images/user.png";}
	}

	function setTable($table,$realName = false){
		$this->_api_table_base = $table;
		if($realName){
			$this->_api_table = $this->_api_table_base;
		}else{
			$this->_api_table = $this->_api_table_prefix.$this->_api_table_base;
		}
	}

	function setApiType($type = ''){
		$this->_api_type = $type;
	}

	function getApiBase(){return $this->_api_base;}
	function getApiType(){return $this->_api_type;}
	function getApiTable(){return $this->_api_table;}

	function creatUrl($action){
		$this->_api_action = $this->_api_action_list[$action];
		$this->_api_url = $this->_api_base.$this->_api_type.$this->_api_table.'.'.$this->_api_action."?";
	}

	function getRow($arr,$sel=array()){
		if(!is_array($arr))$arr = array('id'=>$arr);
		$this->creatUrl('select');
		$this->putDate($arr,array(1,0),$sel);
		$res = $this->getcurl();
		if($this->_return_code===0){
			return $this->_return_Result[0];
		}
		return false;
	}

	function getList($arr=array(),$orderby=array(),$limit=array(),$sel=array()){
		if(is_array($orderby)){$arr['~'.$orderby[0]]=$orderby[1];}
		$this->creatUrl('select');
		$this->putDate($arr,$limit,$sel);
		$res = $this->getcurl();
		if($this->_return_code===0){
			return $this->_return_Result;
		}
		return false;
	}

	function add($arr){
		$this->creatUrl('create');
		$this->putDate($arr);
		$res = $this->getcurl();
		if($this->_return_code===0){
			return $this->_return_Result[0];
		}
		return false;
	}

	function update($where,$arr){
		$this->creatUrl('update');
		$this->putDateUpd($where,$arr);
		$res = $this->getcurl();
		if($this->_return_code===0){
			return $this->_return_Result;
		}
		return false;
	}

	function del($arr){
		if(!is_array($arr))$arr = array('id'=>$arr);
		$this->creatUrl('delete');
		$this->putDate($arr);
		$res = $this->getcurl();
		if($this->_return_code===0){
			return $this->_return_Result;
		}
		return false;
	}
	
	//检查某字段-值是否存在，如果存在则返回true
	function checkHas($key,$value){
		$arr = array($key=>$value);
		if($this->getRow($arr,$key)){
			return true;
		}
		return false;
	}
	
	//获取某个字段的当前最大值，一般用来做num叠加
	function getMax($key){
		$arr['~'.$key]='DESC';
		$res = $this->getRow($arr,$key);
		if($this->_return_code===0){
			if($this->_return_Result){
				return $this->_return_Result[0][$key];
			}else{
				return 0;
			}
		}
		return false;
	}

	function getcurl($url='',$get=0){
		$timeBegin = microtime();

		$url = $url ? $url : $this->_api_url;
		if($get==0){
			$urlArr = explode('?',$url);
			$urlReal = $urlArr[0];
			$dataReal = $urlArr[1];
		}else{
			$urlReal = $url;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $urlReal);
		curl_setopt($ch, CURLOPT_TIMEOUT,$this->_api_query_time);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		// 这一句是最主要的
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($get==0){curl_setopt($ch, CURLOPT_POSTFIELDS, $dataReal);}
		$response = curl_exec($ch);
		curl_close($ch);
		
		$seconds = $this->getTimeMicrQuery($timeBegin);
		$this->setModelLog($url,$response,$seconds);
		
		$response = json_decode($response,TRUE);
		$this->_return_code = $response['Code'];
		$this->_return_Success = $response['IsSuccess'];
		$this->_return_Message = $response['Message'];
		$this->_return_Result = $response['Result'];
		$this->_return_Count = $response['Count'];
		$this->_return_Limit = $response['Limit'];
		$this->_return_Skip = $response['Skip'];
		return $response;
	}

	function setModelLog($url,$response,$seconds){
		$resArr = json_decode($response,TRUE);

		if($this->_api_action && $this->_api_table_base){
			$dir = $this->_api_table_base.'/'.$this->_api_action.'';
		}else{
			$dir = 'Other';	
		}

		$this->setLogDate($dir,$url,$response,$seconds);
		
		if($resArr['Code']){
			$dir = 'Error';
			$this->setLogDate($dir,$url,$response,$seconds);
		}
		if($seconds>=$this->_log_query_time){
			$dir = 'OutTime';
			$this->setLogDate($dir,$url,$response,$seconds);
		}
		if(!$response){
			$dir = 'NoData';
			$this->setLogDate($dir,$url,$response,$seconds);
		}
	}

	function setLogDate($dir,$url,$response,$seconds){
		$dir = $this->config->item('data_log_path').$dir;
		if(!file_exists($dir)){mkdir($dir,'0777',true);}
		$file = $dir.'/'.date('Y-m-d').'.txt';

		$word = '接口路径:'.$url."\r\n";
		$word .= '超时时间:'.$this->_api_query_time."\r\n";
		$word .= '处理时间:'.$seconds."\r\n";
		$word .= '返回参数:'.$response."\r\n";
		$fp = fopen($file,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\r\n".$word."\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

	function getTimeMicrQuery($timeBegin){
		$timeNow = microtime();
		$timeBegin = explode(' ',$timeBegin);
		$timeNow = explode(' ',$timeNow);

		$sec = $timeNow[1]-$timeBegin[1];
		$secm = $timeNow[0]-$timeBegin[0];
		$time = $sec+$secm;
		return $time;
	}

	function putDate($arr,$limit=array(),$sel=array()){
		if(is_array($arr)){
			foreach($arr as $k=>$v){
				if(is_array($v)){$v = json_encode($v);}
				$pararr[] = $k.'='.str_replace('+','%20',urlencode($v));
			}
			$parameter = implode('&',$pararr);
		}
		
		if($sel && !is_array($sel)){$sel = array($sel);}
		if(is_array($sel) && count($sel)){
			foreach($sel as $v){
				$parsel[] = '&@'.$v;
			}
			$parameter .= implode('',$parsel);
		}

		if($limit[0])$parameter .= '&$Limit='.$limit[0];
		if($limit[1])$parameter .= '&$Skip='.$limit[1];
		$this->_api_url.= $parameter;
	}

	function putDateUpd($where,$arr){
		unset($arr['id']);
		if(!is_array($where)){$pararr[] = "id=$where";}else{
			$this->putDate($where);
		}
		if(is_array($arr)){
			foreach($arr as $k=>$v){
				if(is_array($v)){$v = json_encode($v);}
				if(substr($k,-1,1)=='-' || substr($k,-1,1)=='+' || substr($k,-1,1)=='<' || substr($k,-1,1)=='>' || substr($k,-1,1)=='!'){
					$join = '=';
				}else{$join = '==';}
				$pararr[] = $k.$join.str_replace('+','%20',urlencode($v));
			}
			$parameter = implode('&',$pararr);
			$this->_api_url.= '&'.$parameter;
		}
	}

	function getRandStr($length = 8) {  
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';  
		$password = '';  
		for($i = 0;$i<$length;$i++){  
			$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
		}  
		return $password;
	}

	function returnJson($data){
		if(!$data['ErrorCode'])$data['ErrorCode'] = 0;
		if(!$data['ErrorMsg'])$data['ErrorMsg'] = '';
		echo json_encode($data);
	}

}