<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section{background-color:#f2f2f2;}

ul.cateprolist{padding:0;}
ul.cateprolist li{border-bottom:1px solid #ddd;margin-bottom:6px;position:relative;}
ul.cateprolist li img{width:40%;}
ul.cateprolist li tit{font-size:14px;line-height:26px;margin-top:8px;display:inline-block;}
ul.cateprolist li p teaminfo{margin-right:8px;position:relative;color:#999}
ul.cateprolist li p teaminfo i{height:18px;width:18px;background-size:16px;border-radius:3px;border:1px solid #ff4242;left:0;top:-2px;}
ul.cateprolist li p price r{font-size:18px;margin-left:4px;}
ul.cateprolist li p price s{margin-right:6px;color:#999;font-size:12px;}
ul.cateprolist li p price tag{font-size:12px;border:1px solid #ff4242;color:#ff4242;padding:0px 3px;border-radius:12px;display:inline-block;}
ul.cateprolist li btn{position:absolute;bottom:18px;right:0;}

ul.cateprolist li{padding-left:120px;}
ul.cateprolist li img{position:absolute;left:10px;top:12px;border-radius:4px;}
ul.cateprolist li btn{position:absolute;bottom:12px;right:12px;background-color:#fd4a5b;color:#fff;height:22px;line-height:22px;border-radius:4px;}
ul.cateprolist li p price{position:absolute;bottom:14px;left:40%;font-size:16px;color:#999;}
ul.cateprolist li p price m{display:block;font-size:14px;padding:0 6px;height:16px;line-height:16px;color:#fe374a;background-color:#ffebe6;border-radius:4px;position:absolute;top:-18px;left:0;width:auto;display:none}
</style>
<body>
	<header class="back">
		<tit>免费试用</tit>
	</header>
	<section id="page_index">
		<?
			$bannerHeight = 180;
			if($Banner)foreach($Banner as $v){
				$bannerImageList[] = $v['UrlShow'];
			}
			include('page/banner.php');
		?>
		<ul class="cateprolist" style="margin-top:6px;"></ul>
	</section>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>/js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>/js/pintuan.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$(function(){
			Pintuan.ProductType = 3;
			Pintuan.init_page();
		});
		
		$(window).resize(function(){
			setListImg();
		});

		function setListImg(){
			var w = $('ul.cateprolist li img').width();
			$('ul.cateprolist li img').css('height',w);
			$('ul.cateprolist li').css('padding-left',w+20);
			$('ul.cateprolist li').css('height',w+24);
			$('ul.cateprolist li p price').css('left',w+24);
		}
	</script>