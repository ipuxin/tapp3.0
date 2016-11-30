<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section{background-color:#f2f2f2;}
bar{height:60px;line-height:60px;padding:0 30px;background-color:#fff;display:block;}
bar span.sel{color:#ff4242;}

ul.fenlei_pro{}
ul.fenlei_pro li{width:48%;margin:5px 1% 0;float:left;}
ul.fenlei_pro li img{width:100%;}
ul.fenlei_pro li p{padding:8px;position:relative;}
ul.fenlei_pro li p.t{height:40px;line-height:20px;overflow:hidden;padding-bottom:0px;}
ul.fenlei_pro li p r{font-size:14px;margin-right:6px;}
ul.fenlei_pro li p s{font-size:12px;color:#ccc;}
ul.fenlei_pro li p i{position:absolute;right:6px;bottom:6px;height:18px;width:18px;}
</style>
<body>
	<header class="back">
		<tit><?=$cate['CateName']?></tit>
		<div class="right">
			<span class="more"></span>
		</div>
	</header>
	<section id="page_fenlei2">
		<bar class="flex">
			<span data-type="main" class="sel">综合</span>
			<span data-type="xiaoliang">销量</span>
			<span data-type="new">最新</span>
			<span data-type="price">价格 ↓</span>
		</bar>
		<ul class="fenlei_pro"></ul>
	</section>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/fenlei2.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var cateId = '<?=$cate["id"]?>';
		var PaixuType = 'main';

		$(function(){
			Fenlei.init_page();
		});

		$(window).resize(function(){
			setListImg();
		});

		function setListImg(){
			var w = $('ul.fenlei_pro li img').width();
			$('ul.fenlei_pro li img').css('height',w);
		}
	</script>