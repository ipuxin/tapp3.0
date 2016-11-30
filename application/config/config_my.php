<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 数据接口配置 */
$config['db_api_base'] = 'http://tapi.pingoing.cn/';
$config['db_api_type'] = 'restful.';
$config['db_table_prefix'] = 'PYX3_';
$config['db_api_action'] = array('select'=>'Query','create'=>'Create','update'=>'Update','save'=>'Save','delete'=>'Delete');
$config['db_max_query_time'] = 5; 
$config['db_log_query_time'] = 1; 

/* 微信配置 */
$config['wx_appid'] = 'wx6240c7fe19801745';
$config['wx_secret'] = '257e02e472cc6650f3117a4e31a81c6d ';
$config['wx_token'] = 'D25qFgENJxdzdpPrx2MA';
$config['wx_jsapi_path'] = 'application/data/jsapi.txt';
$config['wx_token_path'] = 'application/data/token.txt';

/* 微信开放平台参数 */
$config['wx_kf_appid'] = 'wx066c051d879c9f82';
$config['wx_kf_secret'] = '045ec9d7462530621a04ffd9278dab93';

/* 微信商户参数 */
$config['wx_pay_shanghu'] = '1267301701';
$config['wx_pay_shanghu_key'] = '150M3GMLTP7XO89GMOL0AWBYN8WYID8C';

$config['wx_kf_pay_shanghu'] = '1329074701';
$config['wx_kf_pay_shanghu_key'] = '064A4C00C92341A09369E816E111FC7C';

/* 页面配置 */
$config['pg_version_open'] = TRUE;
//$config['pg_version'] = '201607290001';
$config['pg_version'] = time();

/* 店铺配置 */
$config['order_choucheng_type'] = 1; //订单抽成方式 1，百分比 2，实际金额
$config['order_choucheng_admin'] = 1; //总部订单抽成金额
$config['order_choucheng_hehuo'] = 1; //合伙人订单抽成金额

/* 手续费设置 */
$config['shop_charge_now'] = 0.04;  //商铺提现手续费
$config['hehuo_charge_now'] = 0.02;  //城市合伙人收益率