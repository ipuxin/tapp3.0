<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
header i.logo{height:40px;width:40px;left:16px;top:8px;}
header span.city{height:100%;position:absolute;left:44px;line-height:56px;padding:0 20px;font-size:14px;}

div.nav_list{padding:30px 20px 10px 20px;}
div.nav_list line{margin-bottom:16px;}

ul.goods-left{width:80px;background-color:#f8f8f8;position:absolute;left:0;top:0px;height:100%;display:block;box-sizing:border-box;padding-top:56px;padding-bottom:60px;z-index:10}
ul.goods-left li{width:100%;background-color:#f8f8f8;color:#666;text-align:Center;line-height:40px;height:40px;margin-top:10px;}
ul.goods-left li.sel{border-right:3px solid #f00;color:#f00}

ul.goods-right{width:100%;position:absolute;left:0;top:0px;display:block;box-sizing:border-box;top:56px;padding-left:88px;z-index:9;display:none;overflow:scroll;}
ul.goods-right li{width:33.333%;box-sizing:border-box;padding:5px;float:left;text-align:Center;}
ul.goods-right li.tit{width:100%;height:36px;font-size:12px;line-height:36px;text-align:left;text-indent:26px;position:relative;}
ul.goods-right li.tit i{height:12px;width:12px;left:12px;top:16px;}
ul.goods-right li img{width:100%;}
ul.goods-right li p{height:30px;line-height:30px;overflow:hidden;}
</style>
<body>
	<header class="back">
		<tit>商品分类</tit>
		<div class="right">
			<span class="more"></span>
		</div>
	</header>
	<section id="page_index">
		<ul class="goods-left"></ul>
	</section>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/fenlei.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$(function(){
			Fenlei.init_page();
		});

		$(window).resize(function(){
			setRightHeight();
		});

		function setRightHeight(){
			var h = $(window).height();
			$('ul.goods-right').css('height',h-116);
		}
	</script>