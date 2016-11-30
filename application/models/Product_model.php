<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*** 产品类 ***

创建 2016-01-29 刘深远 

*** ***/

class Product_model extends MY_Model {

	private $_model;
	private $_TypeShowArr;
	
	public function __construct(){
		parent::__construct();
		$this->init();
	}

	function init(){
		parent::init();
		$this->setTable('Product');
		$IsDisableArr = array(
			0 => '启用',
			1 => '禁用'
		);

		$IsForSaleArr = array(
			0 => '下架',
			1 => '上架'
		);

		$TypeArr = array(
			1 => '普通商品',
			2 => '拼团商品',
			3 => '免费试用',
			4 => '一元夺宝',
			5 => '幸运抽奖'
		);

		$this->_TypeShowArr = array(
			1 => '',
			2 => '[拼团]',
			3 => '[免费试用]',
			4 => '[一元夺宝]',
			5 => '[幸运抽奖]'
		);

		$TeamTypeArr = array(
			1 => '普通拼团',
			2 => '秒杀',
			3 => '办公室',
			4 => '校园',
			5 => '家庭'
		);

		$StatusArr = array(
			1 => '预告中',
			2 => '进行中',
			3 => '已完成'
		);

		$this->_model = array(
			'ProductId' => 'num',
			'ShopId' => '',
			'ProductName' => 'str',
			'ProductNameMin' => 'str',
			'Description' => 'str',
			'DescriptionMin' => 'str',
			'Content' => 'text',
			'ImageMin' => 'url', //小图，展示图
			'Images' => 'list', //相册
			'ProductType' => $TypeArr,
			'Tags' => 'array', //分类标签
			'Prices' => array('Normal','Team','Market'),
			//'ProductRelation' => 'array', //关联产品id
			
			'StorageCount' => 'num',//存货数量
			'SalesCount'=> array('Waiting','Real','Adjust'),
			'DisplayRange' => array('all','wechat','app'), //显示范围
			'ProductStatus' => $StatusArr,
			'DeliverAddress' => 'str', //发货地址
			'freightAmout' => 'str', //运费
			
			/* 购买限制参数 */
			'NeedAppLogined' => 'num', //需要APP登录才能购买
			'NewUserOnly' => 'num', //需要新人才能购买
			'OnceSalesLimit' => 'num',//每次最多购买数量。
			'BuyCountLimit' => 'num',//可购买次数。

			'CityCode' => 'str',
			'CityName' => 'str',
			'DisplayRange' => array('','all'), //显示方式

			'Priority' => 'num', //排序
			'IsForSale' => $IsForSaleArr, //上架，商家控制属性
			'IsDisable' => $IsDisableArr, //后台管理员控制属性
			'IsHide' => 'num', //伪删除，隐藏
			'isEnd'  => '',//一元夺宝是否结束
			'CreatTime' => '',

			/* 拼团特有参数 */
			'TeamType' => $TeamTypeArr,
			'ProductEndDate' => 'date', //产品结束时间
			'Alive' => 'num', //秒杀的存活时间，单位小时
			'TeamMemberLimit' => 'num',//团购人数上限
			'LotteryCount' => 'num', //抽奖人数
			'NewMemberCount' => 'num', //新人参与人数
			'ShowTeamList' => 'num', //展示当前参团列表
			'IsTuanzhangGet' => 'num', //是否团长代收货
			'IsCountDown' => 'num', //是否倒计时功能（秒杀）
		);
	}

	function ResetPaixu($list,$num = 0){
		if(!$list)return $list;
		foreach($list as $k=>$v){
			$timeNow = time();
			$Hour = intval(($timeNow - $v['CreatTime'])/3600);
			$Xiaoliang = intval($v['SalesCountReal']);
			$Price = intval($v['Prices']['Normal']);
			$Score = $Xiaoliang/(24+$Hour) - $Price/20;
			$v['PaixuScore'] = $Score;
			$listn[] = $v;
		};

		$listn = $this->my_sort($listn,'PaixuScore');

		foreach($listn as $k=>$v){
			if($num){
				if($k>=$num)break;
			}
			$listnew[] = $v;
		}
		return $listnew;
	}

	function my_sort($arrays,$sort_key,$sort_order=SORT_DESC,$sort_type=SORT_NUMERIC ){   
        if(is_array($arrays)){   
            foreach ($arrays as $array){   
                if(is_array($array)){   
                    $key_arrays[] = $array[$sort_key];   
                }else{   
                    return false;   
                }   
            }   
        }else{   
            return false;   
        }  
        array_multisort($key_arrays,$sort_order,$sort_type,$arrays);   
        return $arrays;   
    }  
 

	function getProductList($arr,$order=array(),$limit=array(),$sel=array()){
		$arr['IsHide!'] = 1;
		if(!$order)$order=array('ProductId','DESC');
		//$arr['DisplayRange'] = '["all","wechat"]';
		$list = $this->getList($arr,$order,$limit,$sel);
		if($list)$list = $this->resetProductList($list);
		$data['List'] = $list;
		$data['Count'] = $this->_return_Count;
		$data['Limit'] = $this->_return_Limit;
		$data['Skip'] = $this->_return_Skip;

		/*if($data['Count']==0 && !$arr['ShopId'] && $arr['CityCode']){
			$arr['ShowCityCode'] = '"'.$arr['CityCode'].'"';
			$arr['IsAllCity'] = 1;
			unset($arr['CityCode']);
			return $this->getProductList($arr,$order,$limit,$sel);
		}*/

		return $data;
	}

	function resetProductList($list){
		foreach($list as $k=>$v){
			$list[$k] = $this->resetProduct($v);
		}
		return $list;
	}

	function resetProduct($v){
		if(!$v)return;
		if($v['ProductType'])$v['ProductTypeMsg'] = $this->_model['ProductType'][$v['ProductType']];
		if($v['ProductType'])$v['ProductTypeShow'] = $this->_TypeShowArr[$v['ProductType']];
		if($v['ProductStatus'])$v['ProductStatusMsg'] = $this->_model['ProductStatus'][$v['ProductStatus']];
		if($v['ProductStatus'])$v['IsDisable'] = $this->_model['IsDisable'][$v['IsDisable']];
		if($v['ImageList'] && count($v['ImageList'])){
			$ImagesShow = array();
			foreach($v['ImageList'] as $img){
				$ImagesShow[] = $this->config->item('res_url').$img;
			}
			$v['ImageList'] = $ImagesShow;
		}
		
		if($v['ImageMin']){
			$v['ImageMinReal'] = $v['ImageMin'];
			$v['ImageMin'] = $this->config->item('res_url').$v['ImageMin'];
		}
		if($v['ImageBig']){
			$v['ImageBigReal'] = $v['ImageBig'];
			$v['ImageBig'] = $this->config->item('res_url').$v['ImageBig'];
		}
		if($v['SalesCount']){
			if($v['ProductType'] == 4){
				$v['SalesCountReal'] = $v['SalesCount']['Waiting'] + $v['SalesCount']['Real'];
			}else{
				$v['SalesCountReal'] = $v['SalesCount']['Waiting'] + $v['SalesCount']['Real'] + $v['SalesCount']['Adjust'];
			}
			if($v['StorageCount'])$v['StorageCountReal'] = $v['StorageCount'];
		}
		if(isset($v['IsDisable']))$v['IsDisableMsg'] = $this->_model['IsDisable'][$v['IsDisable']];
		if(isset($v['IsForSale']))$v['IsForSaleMsg'] = $this->_model['IsForSale'][$v['IsForSale']];
		//if(!$v['ImageMin'])$v['ImageMin'] = $this->config->item('static_file_path').'images/pro/07.jpg';

		if($v['ProductType']>1){$v['freightAmout'] = 0;}
		return $v;
	}

	function CountToWaiting($order){
		$arr = array(
			'id' => $order['ProductRealId'],
			'StorageCount-' => $order['ProductCount'],
			'SalesCount.Waiting+' => $order['ProductCount']
		);
		$this->updProduct($arr);
	}

	function WaitingToReal($order){
		$arr = array(
			'id' => $order['ProductRealId'],
			'SalesCount.Waiting-' => $order['ProductCount'],
			'SalesCount.Real+' => $order['ProductCount']
		);
		$this->updProduct($arr);

		$this->load->model('product_sale_model');
		$this->product_sale_model->addOrderSale($order);
	}

	function SalesCountAdd($order){
		$arr = array(
			'id' => $order['ProductRealId'],
			'StorageCount-' => $order['ProductCount'],
			'SalesCount.Real+' => $order['ProductCount']
		);
		$this->updProduct($arr);

		$this->load->model('product_sale_model');
		$this->product_sale_model->addOrderSale($order);
	}

	function getProduct($arr,$sel=array()){
		$product = $this->getRow($arr,$sel);
		$product = $this->resetProduct($product);
		return $product;
	}

	function addProduct($arr){
		$arr = $this->setModel($arr);
		if(is_numeric($arr)){
			$Data['ErrorCode'] = $arr;
			if($arr==301)$Data['ErrorMessage'] = '宝贝名称不能为空';
			if($arr==302)$Data['ErrorMessage'] = '宝贝ID创建失败';
			return $Data;
		}
		if($pro = $this->add($arr)){
			$Data['Pro'] = $pro;
		}else{
			$Data['ErrorCode'] = 4;
		}
		return $Data;
	}

	function updProduct($arr,$where=array()){
		if(!$where)$where = $arr['id'];
		$arr = $this->setModel($arr,'upd');
		if($updnum = $this->update($where,$arr)){
			$Data['Num'] = $updnum;
		}else{
			$Data['ErrorCode'] = 3;
		}
		return $Data;
	}
	function isEnd($arr,$where){
		if($this->update($where,$arr)){
			return true;
		}else{
			return false;
		}
	}
	
	function delProduct($arr){
		if($delnum = $this->del($arr)){
			$Data['Num'] = $delnum;
		}else{
			$Data['ErrorCode'] = 2;
		}
		return $Data;
	}

	function setModel($arr,$type="add"){
		if($type=='add'){
			if(!$arr['ProductName'])return 301;
			if(!$arr['ProductId']){$arr['ProductId'] = $this->getMax('ProductId');}
			if($arr['ProductId']!==false){
				$arr['ProductId'] = $arr['ProductId'] + rand(10,39);
			}else{
				return 302;
			}
			if(!$arr['SalesCount']){
				$arr['SalesCount'] = array(
					'Waiting' => 0,
					'Real' => 0,
					'Adjust' => 0
				);
			}
			if(!$arr['CreatTime'])$arr['CreatTime'] = time();
			if(!$arr['ProductType'])$arr['ProductType'] = 1;
			if(!$arr['Prices']['Market'])$arr['Prices']['Market'] = intval($arr['Prices']['Normal']*1.2);

			$arr['Prices']['Normal'] = round($arr['Prices']['Normal'],2);

			$arr['IsDisable'] = 0;
			$arr['IsHide'] = 0;
		}

		if($type=='upd'){
			if($arr['Prices']['Normal']){
				$arr['Prices']['Normal'] = round($arr['Prices']['Normal'],2);
				if(!$arr['Prices']['Market'])$arr['Prices']['Market'] = intval($arr['Prices']['Normal']*1.2);
			}
		}
		return $arr;
	}
	
}