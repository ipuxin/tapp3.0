<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section{background-color:#f8f8f8;}
checkbox{width:20px;height:20px;border:1px solid #ddd;border-radius:4px;position:absolute;left:0;top:0px;}
checkbox.check{color:#000;font-size:14px;background-color:#ff4242;border-color:#ff4242;line-height:22px;text-align:center;}
checkbox.check:after{content:'✔';color:#fff}

cartshop{padding:24px 0 12px;margin:0 16px;border-top:1px solid #ddd;display:block;overflow:auto;}
cartshop:first-child{border:0;}
cartshop shopbox{display:block;height:44px;line-height:24px;font-size:16px;font-weight:bolder;padding-left:30px;position:relative;}
cartshop shopbox btn{float:right;color:#999;font-weight:normal;}
cartshop itembox{display:block;padding-left:30px;height:70px;position:relative;margin-bottom:24px;}
cartshop itembox div{padding-left:80px;position:relative;}
cartshop itembox div img{height:70px;width:70px;position:absolute;left:0;top:0;}
cartshop itembox div tit{line-height:25px;height:50px;overflow:hidden;display:block;}
cartshop itembox div p{}
cartshop itembox div p price r{font-size:20px;font-weight:bolder;margin-right:6px;}
cartshop itembox div p price s{margin-right:6px;color:#999;}
cartshop itembox div p price tag{font-size:12px;border:1px solid #ff4242;color:#ff4242;padding:0px 3px;border-radius:12px;display:inline-block;}
cartshop itembox div p span{color:#999;float:right;margin-top:6px;}
cartshop itembox div updbox{position:absolute;height:100%;left:72px;right:0;top:0;background-color:#f8f8f8;padding-right:50px;line-height:70px;display:none}
cartshop itembox div updbox num{width:40%;text-align:center;display:block;float:left;font-size:18px;border-right:1px solid #fff;box-sizing:border-box;color:#000}
cartshop itembox div updbox btn{width:30%;text-align:center;display:block;float:left;font-size:24px;border-right:1px solid #fff;box-sizing:border-box;}
cartshop itembox div updbox sc{position:absolute;height:100%;right:0px;width:50px;text-align:center;color:#fff;background-color:#ff4242;line-height:70px;}

pricebox{padding:14px 0 12px;margin:0 16px;border-top:1px solid #ff4242;display:block;overflow:auto;position:relative;padding-left:30px;}
pricebox checkbox{top:12px;}
pricebox p{line-height:16px;font-size:14px;font-weight:bolder;padding-right:104px;display:block;float:left;width:100%;box-sizing:border-box;}
pricebox p span{float:right;}
pricebox p span r{font-size:16px;}
pricebox p tip{float:right;font-size:12px;font-weight:normal;}
pricebox btn{position:absolute;top:12px;right:0;background-color:#ff4242;color:#fff;text-align:center;width:96px;height:32px;line-height:32px;border-radius:4px;}
</style>
<body>
	<header class="back">
		<tit>购物车（<num><?=$cartCount?></num>）</tit>
		<div class="right">
			<span class="more"></span>
		</div>
	</header>
	<section id="page_cart">
		<?if($cartList)foreach($cartList as $v){?>
		<cartshop>
			<shopbox>
				<checkbox></checkbox>
				<p><?=$v['ShopName']?><btn data-upding='0'>编辑</btn></p>
			</shopbox>
			<?foreach($v['List'] as $k=>$c){$pro = $c['Product'];?>
			<itembox data-cartid='<?=$c['id']?>' data-productid='<?=$c['ProductId']?>' data-count="<?=$c['ProductCount']?>" data-price='<?=$pro['Prices']['Normal']*100?>'>
				<checkbox></checkbox>
				<div>
					<img src="<?=$pro['ImageMin']?>">
					<tit class="page" data-page="/product/info/<?=$c['ProductId']?>"><?=$pro['ProductName']?></tit>
					<p>
						<price><r>¥<?=$pro['Prices']['Normal']?></r><s><?$pro['Prices']['Market']?></s><tag style="display:none">特惠</tag></price>
						<span>x<num><?=$c['ProductCount']?></num></span>
					</p>
					<updbox>
						<btn data-t='del'>-</btn>
						<num><?=$c['ProductCount']?></num>
						<btn data-t='add'>+</btn>
						<sc>删除</sc>
					</updbox>
				</div>
			</itembox>
			<?}?>
		</cartshop>
		<?}?>
		<pricebox>
			<checkbox style="display:none"></checkbox>
			<p><!--全选--><span>合计：<r>¥<num>0</num></r></span></p>
			<p><tip>不含运费</tip></p>
			<btn>结算（<num>0</num>）</btn>
		</pricebox>
	</section>
	<?include(dirname(dirname(__FILE__)).'/page/footer.php');?>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/cart.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$(function(){
			Cart.init_page();
		});
	</script>