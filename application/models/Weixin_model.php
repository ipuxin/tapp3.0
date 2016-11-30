<?php
class Weixin_model extends MY_Model {

	private $fromUsername;
	private $toUsername;
	private $createTime;
	private $msgType;
	private $msgId;
	private $event;
	private $eventKey;
	private $respond;
	private $respondType;

	private $_wx_appid;
	private $_wx_secret;
	private $_wx_token;
	private $_jsapi_date;
	private $_token_date;
	public $_wx_code;
	public $_wx_openId;
	public $_wx_access_token;
	public $_wx_expires_in;

	public $_wx_api_action;
	
	public function __construct(){
		parent::__construct();
		//$this->getAccessToken();
		$this->init();
	}

	function init(){
		$this->_wx_appid = $this->config->item('wx_appid');
		$this->_wx_secret = $this->config->item('wx_secret');
		$this->_wx_token = $this->config->item('wx_token');
		$this->_jsapi_date = $this->config->item('wx_jsapi_path');
		$this->_token_date = $this->config->item('wx_token_path');
		$this->_wx_api_action = 'Normal';
	}

	//获取提交数据
	function GetPostStr(){
		$postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");

		if(!empty($postStr)){
			libxml_disable_entity_loader(true);
			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->fromUsername = $postObj->FromUserName;
			$this->toUsername = $postObj->ToUserName;
			$this->createTime = $postObj->CreateTime;
			$this->msgType = $postObj->MsgType;
			$this->msgId = $postObj->MsgId;
			$this->event = $postObj->Event;
			$this->eventKey = $postObj->EventKey;
			$latitude = $postObj->Latitude;
			$longitude = $postObj->Longitude;
			$precision = $postObj->Precision;
			$eventKey = $postObj->EventKey;
			$keyword = trim($postObj->Content);
			$time = time();
			
			if($this->msgType == "text"){
				$this->RespondKefu();
			}elseif($this->msgType == "event"){
				//$this->load->model('ajax_model');
				if($this->event=="subscribe"){//未关注时扫码

					$user = $this->getUserInfo();
					$this->load->model('user_model');
					$this->user_model->resetUser($user);

					/*** 获取准确的参数 ***/
					$this->eventKey = str_replace('qrscene_','',$this->eventKey);
					/*** 注册/登陆、绑定推广员用户获取userId ***/
					$this->ProcessQRCode();

					//$this->sendMubanInfo('couponSend',$this->fromUsername);
				}elseif($this->event=="SCAN"){//已关注扫码
					$this->ProcessQRCode();
				}elseif($this->event=="CLICK"){
					$this->ProcessClick();
				}
			}

		}else{
			exit();
		}
	}

	//处理菜单点击事件
	function ProcessClick(){
		if($this->eventKey=='menu_1'){
			$this->respond = '谢谢您对我们的支持。
在下方聊天框中输入#新品建议#+小主的建议+姓名+电话即可。我们会认真考虑您的每一个宝贵意见和建议，我们Buyer（请加商城国际买手微信号：1078244517）会在小主的推荐下淘到更好适合大家的精品，再次感谢小主的支持哦。';
		}elseif($this->eventKey=='menu_2'){
			$this->respond = '亲爱的小主，感谢关注拼一下商城。
在线客服时间
周一至周五：上午9：00-12:00下午13:00-18:00
周六至周日：上午10：00-12:00下午13:00-17:00
真人值守。（请直接在微信公众号上讲出你的问题，就会有客户服来接洽哦），全心全意为您服务！
小主 您概述一下遇到的问题，我们将对应问题安排客服快速、有效地帮助小主们，谢谢~

PS:有新品提前预订或新品建议，可加小疯子童鞋微信号：1078244517 ！！';
		}
		$this->RespondText();
	}

	//处理二维码扫描事件 $this->eventKey 二维码参数 
	function ProcessQRCode(){
		$key = explode('_',$this->eventKey);
		$type = $key[0];
		$eventKey = $key[1];
		if($type=='Team'){
			$this->load->model('team_model');
			$team = $this->team_model->getTeamInfo($eventKey);
			$this->respond = $team['ActiveMsg']['Guanzhu'];
			$this->respond .= ' <a href="'.$this->config->item('base_url').'team/info/'.$team['TeamId'].'/canyu/1">点此链接完成拼团->></a>';
		}else{
			$this->respond = '哎呦，不错哦~
拼一下!
既是一种拼购平台，
也是年轻人的一种生活方式
我们专注于打造水果零食拼购平台
每周持续更新，干货、零食、水果。。。
邀上小伙伴儿，赶紧拼团去吧！';
		}

		$this->RespondText();

	}

	function RespondKefu(){
		$time = time();
		$resultStr = sprintf($this->PrintKefu(), $this->fromUsername, $this->toUsername, $time);
		echo $resultStr;
	}
	
	//回复文字信息 $content 消息内容 直接输出完整xml给微信接收
	function RespondText(){
		$time = time();
		$resultStr = sprintf($this->PrintText(), $this->fromUsername, $this->toUsername, $time, 'text', $this->respond);
		echo $resultStr;
	}

	//客服接口消息模板
	function PrintKefu(){
		return $Tpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[transfer_customer_service]]></MsgType></xml>";
	}
	
	//文字消息模板 返回模板xml代码
	function PrintText(){
		return $Tpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Content><![CDATA[%s]]></Content><FuncFlag>0</FuncFlag></xml>";
	}
	

	/*** 获取二维码图片url ***/
	function getSceneQRCodeImg($sceneId = ''){
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->_wx_access_token;
		$data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$sceneId.'"}}}';
		$res = $this->getcurl($url,$data,0);
		return $res;
	}

	/*** 获取jsapi签名 ***/
	function getSignature($ShareConfig){
		$str = 'jsapi_ticket='.$ShareConfig['ticket'];
		$str.= '&noncestr='.$ShareConfig['nonceStr'];
		$str.= '&timestamp='.$ShareConfig['timestamp'];
		$str.= '&url='.$ShareConfig['pageUrl'];
		return sha1($str);
	}

	/*** 获取jsapi_ticket ***/
	function getJsapiTicket($type=''){
		$timeNow = time();
		$file = $this->_jsapi_date;

		$ticketData = $this->getFileDate($file);
		$ticketData = explode('----------',$ticketData);

		$ticket = $ticketData[0];
		$ticketTime = $ticketData[1];

		if($ticketTime<=($timeNow-100) || $ticket==''){
			$access_token = $this->getAccessToken();
			$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
			$return = $this->getcurl($url);
			if($return['errcode']===0){
				$fp = fopen($file,"w");
				flock($fp, LOCK_EX);
				fwrite($fp,$return['ticket'].'----------'.($timeNow+7100));
				fclose($fp);
				return $return['ticket'];
			}else{
				//$this->getAccessToken('new');
				//return $this->getJsapiTicket();
			}
		}else{
			return $ticket;
		}

	}

	function getcurl($url='',$data=array()){
		$timeBegin = microtime();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,5);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		// 这一句是最主要的
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$response = curl_exec($ch);
		curl_close($ch);

		$seconds = $this->getTimeMicrQuery($timeBegin);
		$this->setModelLog($url,$response,$seconds);

		return json_decode($response,TRUE);
	}

	function setModelLog($url,$response,$seconds){
		$resArr = json_decode($response,TRUE);
		$dir = 'Wechat/'.$this->_wx_api_action;	

		$this->setLogDate($dir,$url,$response,$seconds);
		
		/*if($resArr['Code']){
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
		}*/
	}

	function setLogDate($dir,$url,$response,$seconds){
		$dir = $this->config->item('data_log_path').$dir;
		if(!file_exists($dir)){mkdir($dir,'0777',true);}
		$file = $dir.'/'.date('Y-m-d').'.txt';

		$word = '接口路径:'.$url."\r\n";
		$word .= '超时时间:5 '."\r\n";
		$word .= '处理时间:'.$seconds."\r\n";
		$word .= '返回参数:'.$response."\r\n";
		$fp = fopen($file,"a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\r\n".$word."\r\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

	/*** 获取微信accessToken ***/
	function getAccessToken($type = ''){
		$this->_wx_api_action = 'GetAccessToken';
		$file = $this->_token_date;
		$tokenDate = $this->getFileDate($file);
		$time = time();
		
		if($type!='new'){
			$ticketData = json_decode($tokenDate,true);
			if($ticketData['access_token'] && $time<=(intval($ticketData['expires_in'])+intval($ticketData['time'])-100)){
				return $ticketData['access_token'];
			}
		}

		$url = "https://api.weixin.qq.com/cgi-bin/token?";
		$data = array(
			'grant_type' => 'client_credential',
			'appid'      => $this->_wx_appid,
			'secret'     => $this->_wx_secret
		);
		$return = $this->getcurl($url,$data);
		if($return['access_token']){
			$return['time'] = time();
			$fp = fopen($file,"w");
			flock($fp, LOCK_EX);
			fwrite($fp,json_encode($return));
			fclose($fp);
		}
		return $return['access_token'];
	}

	/*** 生成微信菜单 ***/
	function creatMenu($arr=array()){
		$token = $this->getAccessToken('new');
		foreach($arr['button'] as $k=>$v){
			foreach($v as $K=>$V){
				if($K=='name'){$arr['button'][$k][$K]=urlencode($V);}
				if($K=='sub_button'){
					foreach($V as $m=>$n){
						$arr['button'][$k][$K][$m]['name'] = urlencode($n['name']);
					}
				}
			}
		}
		$msg = urldecode(json_encode($arr));
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$token;
		$res = $this->getcurl($url,$msg,0);
		print_r($res);
	}
	
	/*** 发送模板消息 ***/
	function sendMubanInfo($type,$touser,$info=array()){
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$this->_wx_access_token;
		switch ($type){
			case 'couponSend':$msg = $this->getMubanCouponSend($touser,$info);break;
			default:break;
		}
		$res = $this->getcurl($url,$msg,0);
		if($res['errcode']==0){
		
		}else{
			
		}
	}

	/*** 获取微信openId ***/
	function getOpenId(){
		$this->_wx_api_action = 'GetOpenId';
		if($this->session->userdata('OpenId')){
			return $this->session->userdata('OpenId');
		}else{
			$Get = $this->input->get();
			if($Get['code']){
				$this->_wx_code = $Get['code'];
				$state = $Get['state'];
				$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->_wx_appid."&secret=".$this->_wx_secret."&code=".$this->_wx_code."&grant_type=authorization_code";
				$return = $this->getcurl($url);
				$this->session->set_userdata('OpenId',$return['openid']);
				$this->session->set_userdata('WXCode',$this->_wx_code);

				return $return['openid'];
			}else{
				$this->getOauthCode();
			}
		}
	}

	/*** 获取用户信息 ***/
	//nickname,sex(1.男 2.女),headimgurl,city,province,subscribe_time(关注时间)
	//{"subscribe":1,"openid":"opY33snJ-zA9Sat9kVIixa1ieDUs","nickname":"dwerdwer","sex":1,"language":"zh_CN","city":"","province":"上海","country":"中国","headimgurl":"http:\/\/wx.qlogo.cn\/mmopen\/A3zJOer7qE9rxzhWqfHRo4LVgzJNAbeyzE5rueDBofWUrDKZhJkOPuiajzvrEt0ZKZqjQcLLtlpQRQsJ6QnsHczNCb97CkFP7\/0","subscribe_time":1469798384,"remark":"","groupid":0,"tagid_list":[]}
	function getUserInfo(){
		$openId = $this->getOpenId();
		$AccessToken = $this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$AccessToken."&openid=".$openId."&lang=zh_CN";
		$this->_wx_api_action = 'GetUserInfo';
		$return = $this->getcurl($url);
		if($return['errcode']>0){
			$AccessToken = $this->getAccessToken('new');
			$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$AccessToken."&openid=".$openId."&lang=zh_CN";
			$this->_wx_api_action = 'GetUserInfo';
			$return = $this->getcurl($url);
		}
		return $return;

	}

	/*** 获取code ***/
	function getOauthCode(){
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->_wx_appid."&redirect_uri=".current_url()."&response_type=code&scope=snsapi_base&state=#wechat_redirect";
		redirect($url);
	}
	
	/* 微信认证 */
	public function valid(){
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

	/* 微信认证 */
	private function checkSignature(){
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = $this->_wx_token;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

	/* 获取本地文件数据 */
	private function getFileDate($file = ''){
		$data = '';
		if(file_exists($file)){
			$data = file_get_contents($file);
		}else{
			fopen($file,'a');
		}

		return $data;
	}
}