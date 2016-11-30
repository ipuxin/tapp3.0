<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
header{background-color:#f8f8f8}
header i.icon_loc{height:24px;width:24px;left:8px;top:14px;}
header span.city{height:100%;position:absolute;left:18px;line-height:56px;padding:0 20px;font-size:14px;}
section{background-color:#f2f2f2;}

div.nav_list{padding:20px 20px;background-color:#fff;margin-bottom:6px;}

ul.cateprolist{}
ul.cateprolist li{padding-bottom:20px;border-bottom:1px solid #ddd;margin-bottom:6px;position:relative;}
ul.cateprolist li img{width:100%;}
ul.cateprolist li tit{font-size:16px;font-weight:bolder;line-height:26px;margin:12px 0;padding:0 12px;display:inline-block;}
ul.cateprolist li p{padding:0 12px;position:relative;}
ul.cateprolist li p teaminfo{margin-right:8px;position:relative;padding-left:24px;}
ul.cateprolist li p teaminfo i{height:18px;width:18px;background-size:16px;border-radius:3px;border:1px solid #ff4242;left:0;top:-2px;}
ul.cateprolist li p price r{font-size:18px;font-weight:bolder;margin-right:10px;margin-right:6px;}
ul.cateprolist li p price s{margin-right:6px;color:#999;font-size:12px;}
ul.cateprolist li p price tag{font-size:12px;border:1px solid #ff4242;color:#ff4242;padding:0px 3px;border-radius:12px;display:inline-block;}
ul.cateprolist li btn{position:absolute;bottom:-6px;right:12px;background-color:#fd4a5b;color:#fff;height:24px;line-height:24px;border-radius:4px;}
</style>
<body>
	<header>
		<i class="icon icon_loc"></i>
		<span class="city page" data-page="/city/cityList"><?=$cityName?></span>
		<div class="searchWarp"><input type="input" class="search"></div>
		<div class="right"><span class="msg more"></span></div>
	</header>
	<section id="page_<?=$pageName?>">
		<?
			$bannerHeight = 180;
			if($Banner)foreach($Banner as $v){
				$bannerImageList[] = $v['UrlShow'];
			}
			include('page/banner.php');
		?>
		<div class="nav_list">
			<line>
				<span><i class="icon nav_team page" data-page="/main/pintuan"></i>拼团</span>
				<span><i class="icon nav_shiyong page" data-page="/main/shiyong"></i>免费试用</span>
				<span><i class="icon nav_duobao page" data-page="/main/duobao"></i>1元夺宝</span>
				<span><i class="icon nav_choujiang page" data-page="/main/choujiang"></i>幸运抽奖</span>
			</line>
		</div>
		<ul class="cateprolist">
			
		</ul>
	</section>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/index.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$(function(){
			Index.init_page(PAGE_NewLogin);
		});
	</script>