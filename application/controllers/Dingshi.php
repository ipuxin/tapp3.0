<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 定时循环控制文件 ***

创建 2016-10-09 刘深远

*** ***/

class Dingshi extends MY_Controller {

    //判断拼团倒计时结束，拼团失败
    public function CheckTeamTimeOut(){
        $this->load->model('team_model');
        $this->load->model('order_model');
        $this->load->model('product_model');

		$arr = array(
			'TeamStatus' => 2,
			'EndTime<' => time()+10
		);

        $res = $this->team_model->getTeamList($arr,'',array(1,0));
        if($res['Count']>0){
            $team = $res['List'][0];
            foreach($team['Members'] as $member){
                $this->order_model->refundOrder($member['OrderId']);
                $this->order_model->updOrder(array('IsNeedRefund'=>1,'IsHasRefund'=>0),array('OrderId'=>$member['OrderId']));

            }
            $a = $this->team_model->updTeam($team['id'],array('TeamStatus'=>4));
            if($a['Result']['TeamStatus'] == 4){
                $data  = array('isEnd'=> 'N');
            }elseif($a['Result']['TeamStatus'] == 3){
                $data  = array('isEnd'=> 'Y');
            }
            $b = $this->product_model->isEnd($data, $a['Result']['ProductRealId']);
            $this->setTimeReload(0.1);
            return;
        }

		$this->setTimeReload(5);

	}
	
	//订单发货十天，自动确认已发货订单
	public function TurnOrderQianshou(){
		$this->load->model('order_model');
		$arr = array(
			'OrderStatus' => 4,
			'FahuoTime<' => time()-10*24*3600
		);
		$order = $this->order_model->getRow($arr);
		if($order){
			$arr = array('OrderStatus' => 5,'QianshouMsg' => '超过10天未确认，自动签收');
			$this->order_model->updOrder($arr,$order['id']);
			$this->setTimeReload(0.1);
			return;
		}

		$this->setTimeReload(5);
	}
	
	//将订单的金额转入店铺可提现余额，更新订单标记
	public function CheckOrderBalanceToReal(){
		$this->load->model('order_model');
		$arr = array(
			'OrderStatus' => array(5,7),
			'BalanceRealIn!' => 1,
			'PayAmount>' => 0
		);
		$order = $this->order_model->getRow($arr);

		$amount = $order['PayAmount'];
		
		if($order){
			//$shopArr['BalanceReal+'] = $amount;
			//$this->load->model('shop_model');
			//$this->shop_model->updShop($shopArr,$order['ShopId']);

			$this->load->model('shop_balance_model');
			$this->shop_balance_model->addOrderBalance($order,$order['PayAmount'],1);
			
			$upd['BalanceRealIn'] = 1;
			$upd['BalanceRealAmount'] = $amount;
			$this->load->model('order_model');
			$this->order_model->updOrder($upd,$order['id']);

			$this->setTimeReload(0.1);
			return;
		}

		$this->setTimeReload(1);
	}

	//将店铺余额变动记录里面的数字打入到店铺
	public function TurnOrderBalanceToShop(){
		$this->load->model('shop_balance_model');
		$this->load->model('shop_model');
		$arr = array(
			'InsertShop!' => 1
		);
		$balance = $this->shop_balance_model->getRow($arr);
		$shop = $this->shop_model->getRow($balance['ShopRealId']);

		$amount = $balance['Amount'];
		
		if($balance){
			if($balance['IsReal']==0){
				$base = $shop['Balance'];
				$now = $base + $amount;
				$now = round($now,2);
				$arr = array('Balance' => $now);
				if($shop['Balance'])$where['Balance'] = $shop['Balance'];
			}elseif($balance['IsReal']==1){
				$base = $shop['BalanceReal'];
				$now = $base + $amount;
				$now = round($now,2);
				$arr = array('BalanceReal' => $now);
				if($shop['BalanceReal'])$where['BalanceReal'] = $shop['BalanceReal'];
			}
			
			$where['id'] = $shop['id'];
			$res = $this->shop_model->updShop($arr,$where);
			if(!$res['ErrorCode']){
				$this->shop_balance_model->update($balance['id'],array('InsertShop'=>1));
			}

			$this->setTimeReload(0.1);
			return;
		}

		$this->setTimeReload(1);
	}

	public function setTimeReload($time = 1){
		$time = $time*1000;
		echo '<!DOCTYPE html><html>
		<script type="text/javascript">
			var timeLine = '.$time.';

			function remainTime(){
				location.reload();
			}

			setTimeout(remainTime,timeLine);
		</script>';
	}

}