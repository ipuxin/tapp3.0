<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
shopbox{height:120px;background-color:#fee;color:#666;width:100%;display:block;position:relative;}
shopbox img{width:60px;height:60px;border:3px solid #fff;border-radius:6px;position:absolute;bottom:-12px;left:20px;}
shopbox p{padding-left:92px;padding-right:20px;position:absolute;left:0;bottom:12px;font-size:16px;width:100%;box-sizing:border-box;}
shopbox p span{float:right;font-size:12px;margin-top:4px;}

checkedbox{margin:60px 20px;display:block;text-align:center;font-size:24px;line-height:36px;}

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
		<checkedbox>
			店铺审核中<br>
			请等待审核通过
		</checkedbox>
	</section>
	<?include('page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>