<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 定时循环控制文件 ***
 *
 * 创建 2016-10-09 刘深远
 *** ***/
class Dingshi extends MY_Controller
{

    const HHR = 0.02;

    //判断拼团倒计时结束，拼团失败
    public function CheckTeamTimeOut()
    {
        $this->load->model('team_model');
        $this->load->model('order_model');
        $this->load->model('product_model');

        $arr = array(
            'TeamStatus' => 2,
            'EndTime<' => time() + 10
        );

        $res = $this->team_model->getTeamList($arr, '', array(1, 0));
        if ($res['Count'] > 0) {
            $team = $res['List'][0];
            foreach ($team['Members'] as $member) {
//                $this->order_model->refundOrder($member['OrderId']);
                $this->order_model->updOrder(array('IsNeedRefund' => 1, 'IsHasRefund' => 0), array('OrderId' => $member['OrderId']));

            }
            $a = $this->team_model->updTeam($team['id'], array('TeamStatus' => 4));
            if ($a['Result']['TeamStatus'] == 4) {
                $data = array('isEnd' => 'N');
            } elseif ($a['Result']['TeamStatus'] == 3) {
                $data = array('isEnd' => 'Y');
            }
            $b = $this->product_model->isEnd($data, $a['Result']['ProductRealId']);
            $this->setTimeReload(0.1);
            return;
        }

        $this->setTimeReload(5);
    }

    //给标记需要退款的订单退款
    public function CheckOrderNeedRefund()
    {
        $this->load->model('order_model');

        $arr = array(
            'IsNeedRefund' => 1,
            'IsHasRefund' => 0,
            'OrderStatus' => array(2, 3)
        );

        $order = $this->order_model->getOrderInfo($arr);
        if ($order) {
            $this->order_model->refundOrder($order['OrderId']);
            $this->order_model->updOrder(array('IsHasRefund' => 1), $order['id']);
            $this->setTimeReload(0.1);
            return;
        }

        $this->setTimeReload(5);
    }

    //订单发货十天，自动确认已发货订单
    public function TurnOrderQianshou()
    {
        $this->load->model('order_model');
        $arr = array(
            'OrderStatus' => 4,
            'FahuoTime<' => time() - 10 * 24 * 3600
        );
        $order = $this->order_model->getRow($arr);
        if ($order) {
            $arr = array('OrderStatus' => 5, 'QianshouMsg' => '超过10天未确认，自动签收');
            $this->order_model->updOrder($arr, $order['id']);
            $this->setTimeReload(0.1);
            return;
        }

        $this->setTimeReload(5);
    }

    //将订单的金额转入店铺可提现余额，更新订单标记
    public function CheckOrderBalanceToReal()
    {
        $this->load->model('order_model');
        $arr = array(
            'OrderStatus' => 7,
            'BalanceRealIn!' => 1,
            'PayAmount>' => 0
        );
        $order = $this->order_model->getRow($arr);

        $amount = $order['PayAmount'];

        if ($order) {
            //$shopArr['BalanceReal+'] = $amount;
            //$this->load->model('shop_model');
            //$this->shop_model->updShop($shopArr,$order['ShopId']);

            $this->load->model('shop_balance_model');
            $this->shop_balance_model->addOrderBalance($order, $order['PayAmount'], 1);

            $upd['BalanceRealIn'] = 1;
            $upd['BalanceRealAmount'] = $amount;
            $this->load->model('order_model');
            $this->order_model->updOrder($upd, $order['id']);

            $this->setTimeReload(0.1);
            return;
        }

        $this->setTimeReload(1);
    }

    //订单确认收货5天后,把订单金额转入店铺可提现余额，更新订单标记
    public function DelayCheckOrderBalanceToReal()
    {
        $this->logResultMy('---DelayCheckOrderBalanceToReal-----');

        $this->load->model('order_model');
        $arr = array(
            'OrderStatus' => 5,
            'BalanceRealIn!' => 1,
            'PayAmount>' => 0
        );
        $order = $this->order_model->getRow($arr);

        $amount = $order['PayAmount'];

        if ($order) {

            $this->logResultMy(json_encode('---Begin-DelayCheckOrderBalanceToReal---'));

            //$shopArr['BalanceReal+'] = $amount;
            //$this->load->model('shop_model');
            //$this->shop_model->updShop($shopArr,$order['ShopId']);
            $RealReceiptTime = $order['RealReceiptTime'];
//            $timeDelay = 5*24*3600;//5天
            $timeDelay = 5*60;
            $endTime = $RealReceiptTime + $timeDelay;

            $this->logResultMy(json_encode('---begin-DelayCheckOrderBalanceToReal-order---'));
            $this->logResultMy(json_encode($order));
            $this->logResultMy(json_encode($RealReceiptTime));

            //当前时间超过设置时间,执行更新
            if (time() > $endTime) {
                $this->load->model('shop_balance_model');
                $this->shop_balance_model->addOrderBalance($order, $order['PayAmount'], 1);

                $upd['BalanceRealIn'] = 1;
                $upd['BalanceRealAmount'] = $amount;
                $upd['RealAmountInTime'] = time();
                $this->load->model('order_model');
                $this->order_model->updOrder($upd, $order['id']);

                $this->setTimeReload(0.1);
                $this->logResultMy(json_encode('---End-DelayCheckOrderBalanceToReal-upd---'));
                $this->logResultMy(json_encode($upd));
                return;
            }
        }

        $this->setTimeReload(1);
    }

//打印日志函数--ci
    function logResultMy($word = '')
    {
        $dir = $this->config->item('data_log_path') . 'MyDingshiDelay';
        if (!file_exists($dir)) {
            mkdir($dir, '0777', true);
        }
        $fileName = $dir . '/' . 'MyDingshiDelay' . '.txt';
        $fp = fopen($fileName, "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y-%m-%d~%H:%M:%S", time()) . "\r\n" . $word . "\r\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    //将店铺余额变动记录里面的数字打入到店铺
    public function TurnOrderBalanceToShop()
    {
        $this->logResultMy('---TurnOrderBalanceToShop-111----');

        $this->load->model('shop_balance_model');
        $this->load->model('shop_model');
        $this->load->model('admin_model');
        $arr = array(
            'InsertShop!' => 1
        );
        $balance = $this->shop_balance_model->getRow($arr);
        $shop = $this->shop_model->getRow($balance['ShopRealId']);
        //订单中的城市代码
        $CityCode = $balance['CityCode'];
        //订单中要更新的金额
        $amount = $balance['Amount'];

        $this->logResultMy('---balance----');
        $this->logResultMy(json_encode($balance));
        $this->logResultMy(json_encode($arr));

        $this->logResultMy('---shop----');
        $this->logResultMy(json_encode($shop));

        if ($balance) {
            if ($balance['IsReal'] == 0) {

                $base = $shop['Balance'];
                $now = $base + $amount;
                $now = round($now, 2);
                $arr = array('Balance' => $now);
                if ($shop['Balance']) $where['Balance'] = $shop['Balance'];


                //开始更新门店商的Balance字段
                //判断当前门店有无邀请码
                if ($shop['InvitationCode']) {
                    //获取原来门店商的Balance
                    $whereAdmin = array('InvitationCode' => $shop['InvitationCode'], 'UserType' => 4);
                    $mDAdmin = $this->admin_model->getAdmin($whereAdmin);
                    $mDOldBalance = $mDAdmin['Admin']['Balance'];

                    $this->logResultMy('---mDAdmin-mDAdmin-----');
                    $this->logResultMy(json_encode($mDAdmin));

                    $this->logResultMy('---mDAdmin-InvitationCode-----');
                    $this->logResultMy(json_encode($mDAdmin['Admin']['InvitationCode']));

                    //获取当前店铺原有的Balance
                    $nowMDBalance = $amount * self::HHR + $mDOldBalance;
                    $arrAdmin = array('Balance' => $nowMDBalance);

                    //如果邀请码正确,执行更新
                    if ($mDAdmin['Admin']['InvitationCode']) {
                        $res = $this->admin_model->updAdmin($arrAdmin, $whereAdmin);
                    }


                }

                //开始根据订单中的城市CityCode更新城市合伙人Balance
                if ($CityCode) {
                    //得到原来城市合伙人的Balance
                    $whereAdmin = array('CityCode' => $CityCode, 'UserType' => 2);
                    $CityAdmin = $this->admin_model->getAdmin($whereAdmin);
                    $cityOldBalance = $CityAdmin['Admin']['Balance'];

                    $nowCityBalance = $amount * self::HHR + $cityOldBalance;
                    $arrAdmin = array('Balance' => $nowCityBalance);

                    $this->logResultMy('---mDAdmin-CityAdmin-whereAdmin-Balance----');
                    $this->logResultMy(json_encode($whereAdmin));

                    $this->logResultMy('---mDAdmin-CityAdmin-Balance----');
                    $this->logResultMy(json_encode($CityAdmin));

                    $this->logResultMy('---CityAdmin-CityCode-Balance----');
                    $this->logResultMy(json_encode($CityAdmin['Admin']['CityCode']));

                    //城市码正确,执行修改
                    if ($CityAdmin['Admin']['CityCode']) {
                        $res = $this->admin_model->updAdmin($arrAdmin, $whereAdmin);
                    }
                }

            } elseif ($balance['IsReal'] == 1) {
                //审核提现
                $base = $shop['BalanceReal'];
                $now = $base + $amount;
                $now = round($now, 2);
                $arr = array('BalanceReal' => $now);
                if ($shop['BalanceReal']) $where['BalanceReal'] = $shop['BalanceReal'];

                //开始更新店铺的BalanceReal字段
                //判断当前店铺有无邀请码
                if ($shop['InvitationCode']) {
                    //获取原来门店商的BalanceReal
                    $whereAdmin = array('InvitationCode' => $shop['InvitationCode'], 'UserType' => 4);
                    $mDAdmin = $this->admin_model->getAdmin($whereAdmin);
                    $mDOldBalance = $mDAdmin['Admin']['BalanceReal'];

                    $this->logResultMy('---mDAdmin-mDAdmin-BalanceReal----');
                    $this->logResultMy(json_encode($mDAdmin));

                    $this->logResultMy('---mDAdmin-InvitationCode-BalanceReal----');
                    $this->logResultMy(json_encode($mDAdmin['Admin']['InvitationCode']));

                    //获取当前店铺原有的BalanceReal，保持门店商金额与其名下的店铺金额一致
                    $nowMDBalance = $amount * self::HHR + $mDOldBalance;
                    $arrAdmin = array('BalanceReal' => $nowMDBalance);

                    //如果邀请码正确执行更新
                    if ($mDAdmin['Admin']['InvitationCode']) {
                        $res = $this->admin_model->updAdmin($arrAdmin, $whereAdmin);
                    }
                }

                //开始根据订单中的城市CityCode更新城市合伙人BalanceReal
                if ($CityCode) {
                    //得到原来城市合伙人的BalanceReal
                    $whereAdmin = array('CityCode' => $CityCode, 'UserType' => 2);
                    $CityAdmin = $this->admin_model->getAdmin($whereAdmin);
                    $cityOldBalance = $CityAdmin['Admin']['BalanceReal'];

                    $this->logResultMy('---mDAdmin-CityAdmin-BalanceReal----');
                    $this->logResultMy(json_encode($CityAdmin));

                    $this->logResultMy('---mDAdmin-InvitationCode-BalanceReal----');
                    $this->logResultMy(json_encode($CityAdmin['Admin']['CityCode']));

                    $nowCityBalance = $amount * self::HHR + $cityOldBalance;
                    $arrAdmin = array('BalanceReal' => $nowCityBalance);
                    //如果城市码正确执行更新
                    if ($CityAdmin['Admin']['CityCode']) {
                        $res = $this->admin_model->updAdmin($arrAdmin, $whereAdmin);
                    }
                }
            }

            $where['id'] = $shop['id'];
            $res = $this->shop_model->updShop($arr, $where);
            if (!$res['ErrorCode']) {
                $this->shop_balance_model->update($balance['id'], array('InsertShop' => 1));
            }

            $this->setTimeReload(0.1);
            return;
        }

        $this->setTimeReload(1);
    }

    public function setTimeReload($time = 1)
    {
        $time = $time * 1000;
        echo '<!DOCTYPE html><html>
		<script type="text/javascript">
			var timeLine = ' . $time . ';

			function remainTime(){
				location.reload();
			}

			setTimeout(remainTime,timeLine);
		</script>';
    }

}