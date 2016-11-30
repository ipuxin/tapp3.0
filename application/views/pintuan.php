<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
header i.logo{height:40px;width:40px;left:16px;top:8px;}
header span.city{height:100%;position:absolute;left:44px;line-height:56px;padding:0 20px;font-size:14px;}
section{background-color:#f2f2f2;}

ul.cateprolist{}
ul.cateprolist li{padding-bottom:20px;border-bottom:1px solid #ddd;margin-bottom:6px;position:relative;}
ul.cateprolist li img{width:100%;}
ul.cateprolist li tit{font-size:16px;font-weight:bolder;line-height:26px;margin:12px 0;padding:0 12px;display:inline-block;}
ul.cateprolist li p{padding:0 12px;position:relative;}
ul.cateprolist li p teaminfo{margin-right:8px;position:relative;padding-left:24px;font-size:12px;}
ul.cateprolist li p teaminfo i{height:18px;width:18px;background-size:18px;left:0;top:-2px;}
ul.cateprolist li p price r{font-size:18px;font-weight:bolder;margin-right:10px;margin-right:6px;}
ul.cateprolist li p price s{margin-right:6px;color:#999;font-size:12px;}
ul.cateprolist li p price tag{font-size:12px;border:1px solid #ff4242;color:#ff4242;padding:0px 3px;border-radius:12px;display:inline-block;}
ul.cateprolist li btn{position:absolute;bottom:-6px;right:12px;background-color:#fd4a5b;color:#fff;height:24px;line-height:24px;border-radius:4px;}
</style>
<body>
	<header class="back">
		<tit>拼团</tit>
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
			Pintuan.ProductType = 2;
			Pintuan.init_page();
		});
	</script>