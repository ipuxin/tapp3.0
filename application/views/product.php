<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section{background-color:#f2f2f2;}
product{float:left;background-color:#fff;margin-top:6px;width:100%;box-sizing:border-box;}
product>*{display:inline-block;width:100%;box-sizing:border-box;}
product p{border-bottom:1px solid #eee;height:48px;line-height:48px;padding:0 6px;}
product p rmb{font-size:16px;margin-right:6px;}
product p s{color:#999;margin-right:20px;}
product p span{float:right;}
product tit{font-weight:bolder;font-size:16px;padding:15px 6px;line-height:18px;}
product des{padding:0 6px;padding-bottom:20px;}
product tip{background-color:#f2f2f2;box-sizing:border-box;padding:12px 40px;font-size:12px;}
product tip span{padding-left:20px;background:url('<?=$staticPath?>images/icon2/icon_tag_right.png') no-repeat 4px 2px;background-size:12px;}
product msg{font-size:14px;padding:12px;}

proaddress{padding:0 16px;display:block;float:left;width:100%;box-sizing:border-box;margin-top:12px;}
proaddress p{border-bottom:1px solid #ddd;border-top:1px solid #ddd;padding:16px 0;}

proevaluate{overflow:auto}
proevaluate tit{font-weight:bolder;}
proevaluate ul li{width:100%;margin-top:20px;}
proevaluate ul li p.t{position:relative;margin-bottom:10px;padding-left:30px;height:24px;line-height:24px;}
proevaluate ul li p.t img{position:absolute;height:24px;width:24px;border-radius:2px;left:0px;top:0;}
proevaluate ul li p.t name{font-weight:bolder;}
proevaluate ul li p.t span{color:#ccc;float:right;}
proevaluate btn{float:right;margin-top:10px;}

shop{background-color:#fff;padding:10px;float:left;margin-top:6px;position:relative;box-sizing:border-box;width:100%;height:80px;padding-left:80px;}
shop img{position:absolute;top:10px;left:10px;height:60px;width:60px;}
shop p{color:#999;}
shop p.name{font-size:15px;color:#333;height:40px;line-height:40px;}
shop span.btn{position:absolute;height:32px;line-height:32px;top:24px;right:12px;border:1px solid #ccc;border-radius:4px;text-align:center;padding-left:30px;padding-right:6px;box-sizing:border-box;background:url('<?=$staticPath?>images/icon2/icon_shop.png') no-repeat 6px 6px;background-size:18px;}

probtns{padding:16px;float:left;width:100%;box-sizing:border-box;}
probtns warp{height:40px;line-height:40px;background-color:#ff4242;color:#fff;display:block;width:100%;display:flex; flex-wrap:wrap;justify-content:space-between;box-sizing:border-box;padding:0 20px;}
probtns btn{font-size:16px;}

procontent{background-color:#fff;margin-top:6px;float:left;width:100%;box-sizing:border-box;padding:12px 8px;}
procontent img{width:100%;}

footer i.font{line-height:50px;font-size:20px;}
footer div.btn{color:#fff;line-height:60px;font-size:14px;}
footer warp div.line{height:30px;border-right:1px solid #fff;margin-top:20px;}
footer warp i.icon{background-size:28px;}
</style>
<body>
	<header class="back">
		<tit>商品详情</tit>
	</header>
	<section id="page_product">
		<?
			$bannerImageList = $pro['ImageList'];
			$bannerHeight = 320;
			include('page/banner.php');
		?>
		<product>
			<p class="t">
				<rmb class="r">¥<?=$pro['Prices']['Normal']?></rmb>
				<s>¥<?=$pro['Prices']['Market']?></s>
				<span>累计销量：<r><?=$pro['SalesCountReal']?></r>件</span>
			</p>
			<tit><?=$pro['ProductName']?><i class="hot"></i></tit>
			<des><?=$pro['Description']?></des>
			<tip class="flex">
				<span>全场包邮</span><span>正品保证</span><span>同城速配</span>
			</tip>
		</product>
		<!--<proevaluate>
			<tit>宝贝评价<r>（3255）</r></tit>
			<ul>
				<li>
					<p class="t">
						<img src="<?=$staticPath?>images/user.png">
						<name>幸福的**</name>
						<span>2016.06.26</span>
					</p>
					<p>
						店家服务态度很好，物流也很给力！水果确实不错的，很超值，大家吃过都说不催哦，下次还会来买~~
					</p>
				</li>
				<li>
					<p class="t">
						<img src="<?=$staticPath?>images/user.png">
						<name>做梦D***</name>
						<span>2016.06.26</span>
					</p>
					<p>
						店家服务态度很好，物流也很给力！水果确实不错的，很超值，大家吃过都说不催哦，下次还会来买~~
					</p>
				</li>
			</ul>
			<btn class="rbtn">更多评价</btn>
		</proevaluate>-->
		<shop>
			<img src="<?=$shop['ShopLogo']?>">
			<p class="name"><?=$shop['ShopName']?></p>
			<p><?if($shop['FreightFreeAmout']>0){?>
			满<?=$shop['FreightFreeAmout']?>元包邮
			<?}else{?>
			<?=$shop['CityName']?>
			<?}?></p>
			<span class="btn page" data-page="/shop/info/<?=$shop['ShopId']?>">进店逛逛</span>
		</shop>
		<procontent>
			<?=$pro['Content']?>
		</procontent>
	</section>
	<footer class="warp">
		<warp style="padding:3px 15px;background-color:#fff">
			<div class="page" data-page="/"><i class="icon icon_home"></i>首页</div>
			<div><i class="icon icon_kefu"></i>客服</div>
			<div class="page" data-page="/shop/info/<?=$shop['ShopId']?>"><i class="icon icon_shop"></i>店铺</div>
		</warp>
		<div class="btn" id="cart_warp" style="background-color:#ff939e;width:27%;">加入购物车</div>
		<div class="btn page" style="background-color:#fe374a;width:23%;" data-page="/product/pay/payone_<?=$pro['ProductId']?>"></i>立即购买</div>
	</footer>
	<?
		$Cart['ProductId'] = $pro['id'];
		include('page/cart.php');
	?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/product.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var ProductId = '<?=$pro["ProductId"]?>';

		$(function(){
			Product.init_page();
		});
	</script>