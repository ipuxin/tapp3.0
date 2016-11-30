<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
header{height:54px;}
section{padding-top:0;}

spacebox{padding-top:40px;height:92px;background-color:#ff4242;color:#fff;position:relative;width:100%;display:block;margin-bottom:20px;}
spacebox img{height:70px;width:70px;border-radius:6px;position:absolute;left:20px;bottom:16px;z-index:100;}
spacebox p{font-size:18px;font-weight:bolder;left:100px;bottom:40px;position:absolute;display:block;}

clu{height:60px;line-height:60px;color:#777;border-bottom:1px solid #ddd;margin:0 20px;display:block;}
clu b{font-size:14px;padding-left:20px;position:relative;font-weight:normal;}
clu span{float:right;color:#999}
clu i.icon{left:0;top:-2px;height:18px;width:18px;}

div.nav_list{padding:20px 0;margin:0 20px;}
div.nav_list line span{color:#333;width:52px;display:inline-block;font-size:12px;}
div.nav_list line span i.icon{height:36px;width:36px;background-size:36px;}

div.loginout{width:100%;text-align:Center;height:44px;line-height:44px;color:#ff4242;background-color:#f8f8f8;margin-top:40px;}

shenqing{background-color:rgba(0,0,0,0.5);height:100%;width:100%;left:0;top:0;z-index:100;position:absolute;display:none}
shenqing div{width:260px;margin:auto;margin-top:130px;background-color:#fff;border-radius:6px;overflow:auto;padding:20px;}
shenqing div b{margin-bottom:40px;font-size:15px;display:inline-block}
shenqing div p{font-size:13px;text-align:center;margin-bottom:40px;}
shenqing div a{font-size:13px;color:#f00;float:right;}
</style>
<body>
	<section id="page_space">
		<spacebox>
			<img src="<?=$headimgurl?>">
			<p><?=$nickname?></p>
		</spacebox>
		<clu>
			<b><i class="icon space_order"></i>我的订单</b><span class="page" data-page="/order/orderlist/1">查看全部订单 ></span>
		</clu>
		<div class="nav_list" style="border-bottom:1px solid #ddd">
			<line>
				<span class="page" data-page="/order/orderlist/1"><i class="icon icon_daifu"></i>待付款</span>
				<span class="page" data-page="/order/orderlist/2"><i class="icon icon_daifa"></i>待发货</span>
				<span class="page" data-page="/order/orderlist/3"><i class="icon icon_daishou"></i>待收货</span>
				<span class="page" data-page="/order/orderlist/4"><i class="icon icon_daiping"></i>待评价</span>
				<span><i class="icon icon_daihou"></i>退款/售后</span>
			</line>
		</div>
		<clu data-page="/team/myteam/1" class="page"><b><i class="icon space_team"></i> 我的拼团</b></clu>
		<clu class="page" data-page="/space/address/1"><b><i class="icon space_loc"></i> 收货地址</b></clu>
		<clu><b><i class="icon space_coupon"></i> 我的优惠券</b></clu>
		<clu class="shenqingShop"><b><i class="icon space_shop"></i> 我是商家</b></clu>
		<div class="loginout">退出登录</div>
	</section>
	<shenqing>
		<div>
			<b>店铺入驻申请</b>
			<p>请前往电脑申请网址：www.pingoing.cn</p>
			<a href="javascript:;">知道啦</a>
		</div>
	</shenqing>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script>
		$('.shenqingShop').click(function(){
			$('shenqing').show();
		});

		$('shenqing a').click(function(){
			$('shenqing').hide();
		});

		
		$('.loginout').click(function(){ 
			showMsg('是否需要退出登录？','是','否',function(){loginout();});
		});

		function loginout(){
			pageTurn('/main/out');
		}
	</script>
	<!--<script type="text/javascript" src="<?=$staticPath?>js/space.js?v=<?=$version?>"></script>-->