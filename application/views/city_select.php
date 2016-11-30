<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
searchBox{margin:0 20px;padding-top:14px;height:36px;line-height:36px;border-bottom:1px solid #ff4242;display:block;margin-bottom:14px;}
searchBox input{text-align:center;font-size:14px;}

cityBox{position:relative;display:block;overflow:auto}
t{height:20px;line-height:20px;color:#ff4242;background-color:#fef2f1;margin:0 20px;margin-top:-1px;display:block;text-indent:4px;}
city{height:40px;line-height:40px;margin:0 20px;display:block;border-bottom:1px solid #ddd}
city:last-child{border:0;}
city span{color:#aaa;display:inline-block;text-align:center;width:100%;}

aside{position:absolute;right:20px;top:120px;font-size:12px;width:26px;display:block;}
aside a{width:100%;height:16px;line-height:16px;display:inline-block;text-align:center;}
</style>
<body>
	<header class="back">
		<tit>选择城市</tit>
	</header>
	<section id="page_change_city">
		<searchBox>
			<input type="text" placeholder="中文/拼音搜索城市">
		</searchBox>
		<cityBox>
			<?if($cityName_loc){?><t id="dw">定位当前所在城市 ></t>
			<city data-citycode="<?=$cityCode_loc?>" data-cityname="<?=$cityName_loc?>" data-provincecode="<?=$provinceCode_loc?>" data-provincename="<?=$provinceName_loc?>"><?=$cityName_loc?></city><?}?>
			<t id="rm">热门城市 ></t>
			<city><span>暂无热门城市</span></city>
		</cityBox>
		<cityBox>
		</cityBox>
		<cityBox style="display:none">
			<t id="ss">搜索结果 ></t>
		</cityBox>
	</section>
	<aside>
		<a data-v="dw">定位</a>
		<a data-v="rm">热门</a>
	</aside>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/city.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$(function(){
			City.init_page();
		});
	</script>