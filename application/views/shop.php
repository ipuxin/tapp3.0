<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
shopbox{height:140px;width:100%;display:block;background-color:#000;box-sizing:border-box;position:relative;padding:96px 20px 20px 80px;}
shopbox img{height:56px;width:56px;border-radius:6px;position:absolute;bottom:20px;left:12px;}
shopbox p{font-size:16px;color:#fff;font-weight:bolder;line-height:24px;}
shopbox p span{float:right;font-size:12px;font-weight:normal;margin-top:2px;}
shopbox p span i{font-size:12px;border:1px solid #fff;border-radius:12px;padding:5px;margin-left:5px;}
shopbox p span i r{color:#ff4242;margin-right:2px;}

div.nav_list{margin:0 20px;padding-top:30px;padding-bottom:14px;border-bottom:1px solid #ff4242}

ul.cateprolist{padding:0 20px;}
ul.cateprolist li{padding-bottom:20px;border-bottom:1px solid #ddd;margin-bottom:20px;position:relative;}
ul.cateprolist li img{width:100%;}
ul.cateprolist li tit{font-size:16px;font-weight:bolder;line-height:26px;margin:12px 0;display:inline-block;}
ul.cateprolist li p teaminfo{margin-right:8px;position:relative;padding-left:24px;}
ul.cateprolist li p teaminfo i{height:18px;width:18px;background-size:16px;border-radius:3px;border:1px solid #ff4242;left:0;top:-2px;}
ul.cateprolist li p price r{font-size:18px;font-weight:bolder;margin-right:10px;margin-right:6px;}
ul.cateprolist li p price s{margin-right:6px;color:#999;font-size:12px;}
ul.cateprolist li p price tag{font-size:12px;border:1px solid #ff4242;color:#ff4242;padding:0px 3px;border-radius:12px;display:inline-block;}
ul.cateprolist li btn{position:absolute;bottom:18px;right:0;}
</style>
<body>
	<header class="back">
		<div class="searchWarp left"><input type="input" class="search"></div>
		<div class="right">
			<span class="cate">分类</span>
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_shop">
		<shopbox>
			<img src="<?=$shop['ShopLogo']?>">
			<p><?=$shop['ShopName']?><!--<span>1.6万粉丝<i><r>+</r>关注</i></span>--></p>
		</shopbox>
		<div class="nav_list">
			<line>
				<span><i class="icon nav_shop page" data-page="/shop/info/<?=$shopId?>"></i>店铺首页</span>
				<span><i class="icon nav_shop_item page" data-page="/shop/allproduct/<?=$shopId?>"></i>全部宝贝</span>
				<span><i class="icon nav_new page" data-page="/shop/newproduct/<?=$shopId?>"></i>新品上架</span>
				<!--<span><i class="icon nav_dongtai"></i>店铺动态</span>-->
			</line>
		</div>
		<ul class="cateprolist">
			
		</ul>
	</section>
	<!--
	<footer>
		<div><i class="icon warp menu_cate"></i>宝贝分类</div>
		<div><i class="icon warp menu_shop_info"></i>店铺简介</div>
		<div><i class="icon warp menu_kefu"></i>联系卖家</div>
	</footer>
	-->
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/shop.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var ShopId = '<?=$shop["id"]?>';
		$(function(){
			Shop.init_page();
		});
	</script>