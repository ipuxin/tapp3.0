<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
shopbox{height:120px;background-color:#fee;color:#666;width:100%;display:block;position:relative;}
shopbox img{width:60px;height:60px;border:3px solid #fff;border-radius:6px;position:absolute;bottom:-12px;left:20px;}
shopbox p{padding-left:92px;padding-right:20px;position:absolute;left:0;bottom:12px;font-size:16px;width:100%;box-sizing:border-box;}
shopbox p span{float:right;font-size:12px;margin-top:4px;}

tongji{border-bottom:1px solid #ddd;padding:40px 0 20px;margin:0 20px;overflow:auto;display:block;box-sizing:border-box;}
tongji div{border-left:1px solid #eee;width:32%;box-sizing:border-box;height:50px;line-height:20px;text-align:center;float:left;color:#999;}
tongji div:first-child{width:36%;border:0;}
tongji div p{margin-bottom:10px;}

div.nav_list{padding:20px;}
div.nav_list line span{color:#666;width:50px;font-size:12px;}
div.nav_list line span i.icon{height:30px;width:30px;}
</style>
<body>
	<header class="back">
		<tit>我的店铺</tit>
		<div class="right">
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_space">
		<shopbox>
			<img src="<?=$shop['ShopLogo']?>">
			<p><?=$shop['ShopName']?> <span>店铺号：<?=$shop['ShopId']?></span></p>
		</shopbox>
		<tongji>
			<div>
				<p>今日成交总额</p>
				<p class="r">¥3025</p>
			</div>
			<div>
				<p>今日访客</p>
				<p class="r">302</p>
			</div>
			<div>
				<p>今日订单</p>
				<p class="r">216</p>
			</div>
		</tongji>
		<div class="nav_list">
			<line style="margin-bottom:18px;">
				<span class="page" data-page="/space_shop/product/fabu"><i class="icon nav_shoucan_item"></i>发布宝贝</span>
				<span class="page" data-page="/space_shop/product"><i class="icon nav_shoucan_shop"></i>宝贝管理</span>
				<span class="page" data-page="/space_shop/orderList"><i class="icon nav_shoucan_news"></i>订单管理</span>
				<span><i class="icon nav_order"></i>生意参谋</span>
			</line>
			<line>
				<span><i class="icon nav_shop"></i>我要代理</span>
				<span><i class="icon nav_user_canter"></i>店铺装修</span>
				<span><i class="icon nav_coupon"></i>营销推广</span>
				<span class="page" data-page="/space_shop/shopSetting"><i class="icon nav_coupon"></i>设 置</span>
			</line>
		</div>
	</section>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/space_shop.js?v=<?=$version?>"></script>