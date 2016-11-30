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
product typeinfo{border-top:6px solid #f2f2f2;padding:12px 12px 24px;}
product typeinfo b{color:#ff4242;font-size:16px;line-height:32px;font-weight:normal}
product typeinfo p{line-height:22px;color:#777;text-indent:0;padding:0;height:auto;border:0;}
product tip{background-color:#f2f2f2;box-sizing:border-box;padding:12px 40px;font-size:12px;}
product tip span{padding-left:20px;background:url('<?=$staticPath?>images/icon2/icon_tag_right.png') no-repeat 4px 2px;background-size:12px;}
product msg{font-size:14px;padding:12px;}

proaddress{padding:0 16px;display:block;float:left;width:100%;box-sizing:border-box;margin-top:12px;}
proaddress p{border-bottom:1px solid #ddd;border-top:1px solid #ddd;padding:16px 0;}

teamlist{float:left;width:100%;box-sizing:border-box;background-color:#fff;margin-top:6px;}
teamlist tit{font-size:12px;font-weight:bolder;display:inline-block;width:100%;background-color:#f2f2f2;padding:12px;}
teamlist team{height:76px;position:relative;width:100%;display:block;padding:10px;padding-left:68px;box-sizing:border-box;}
teamlist team img{height:48px;width:48px;border-radius:6px;position:absolute;left:8px;top:12px;}
teamlist team div{border-bottom:1px solid #ddd;height:100%;position:relative;box-sizing:border-box;padding-top:30px;}
teamlist team div name{font-size:16px;font-weight:bolder;position:absolute;left:0;top:4px;}
teamlist team div btn{border:1px solid #ff4242;border-radius:6px;height:24px;line-height:24px;padding:0 10px;display:block;position:absolute;top:20px;right:0;font-size:12px;color:#ff4242;font-weight:bolder;}
teamlist team div p{font-size:12px;padding-right:90px;}
teamlist team div p span.red{display:none;background-color:#ff4242}
teamlist team div p span:nth-child(3){float:right}

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
footer warp div.btn{color:#fff;line-height:60px;font-size:16px;}
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
				<rmb class="r">¥<?=$pro['Prices']['Team']?></rmb>
				<s>¥<?=$pro['Prices']['Market']?></s>
				<r><?=$pro['TeamMemberLimit']?>人团</r>
				<span>已有<r><?=$pro['SalesCountReal']?></r>人参团</span>
			</p>
			<tit><?=$pro['ProductName']?><i class="hot"></i></tit>
			<des><?=$pro['Description']?></des>
			<?if($pro['ProductType']==5){?>
			<typeinfo style="background-color:#fde8ee">
				<b>抽奖规则</b>
				<p>1) 组团成功，系统立即开奖。每个团随机抽取<?=$pro['LotteryCount']?>名幸运用户，可在团详情里查看。</p>
				<p>2) 中奖用户获得商品，未中奖用户，系统立即退款，原路返还。</p>
			</typeinfo>
			<?}?>
			<?if($pro['ProductType']==4){?>
			<typeinfo style="background-color:#fde8ee">
				<b>夺宝规则</b>
				<p>1) 组团成功，系统立即开奖。每个团随机抽取<?=$pro['LotteryCount']?>名幸运用户，可在团详情里查看。</p>
				<p>2) 中奖用户获得商品，未中奖用户，系统不会退款，夺宝需谨慎哦~</p>
			</typeinfo>
			<?}?>
			<?if($pro['ProductType']==3){?>
			<!--<typeinfo style="background-color:#ebf6fd">
				<b>免费试用规则</b>
				<p>1) 拼团成功即获得免费试用抽奖资格，每个用户每件商品限参与一次；</p>
				<p>2) 活动结束后，从拼团成功的用户中随机抽取<?=$pro['LotteryCount']?>名使用者，将获得试用商品并自己保留；</p>
				<p>3) 中奖以短信通知为准，中奖用户，系统会在活动结束后，给用户推送一条中奖短信。</p>
			</typeinfo>-->
			<?}?>
			<tip class="flex">
				<span>全场包邮</span><span>正品保证</span><span>同城速配</span>
			</tip>
			<msg>支付开团并邀请<?=$pro['TeamMemberLimit']-1?>人参加，人数不足自动退款</msg>
		</product>
		<!--<teamlist style="display:none">
			<tit>以下小伙伴正在发起团购，您可以直接参与哦！</tit>
			<team>
				<img src="<?=$staticPath?>images/user.png">
				<div>
					<name>dwerdwer</name>
					<p>
						<span>北京市</span>
						<span class="red">差2人</span>
						<span>剩余23:33:44</span>
					</p>
					<btn>去参团 ></btn>
				</div>
			</team>
			<team>
				<img src="<?=$staticPath?>images/user.png">
				<div>
					<name>王晓明</name>
					<p>
						<span>北京市</span>
						<span class="red">差2人</span>
						<span>剩余23:33:44</span>
					</p>
					<btn>去参团 ></btn>
				</div>
			</team>
		</teamlist>-->
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
		<warp style="padding:3px 16px;background-color:#ff4242;">
			<!--<div><i class="icon font page" data-page="/product/payone/<?=$pro['ProductId']?>">¥<?=$pro['Prices']['Normal']?></i>单独购买</div>
			<div class="line"></div>-->
			<?if($teamid){?>
			<div style="color:#fff;"><i class="icon font page" data-page="/product/joinTuan/<?=$pro['ProductId']?>/<?=$teamid?>">¥<?=$pro['Prices']['Team']?></i>立即参团</div>
			<?}else{?>
				<?if($pro['ProductType']==2 || $pro['ProductType']==5){?>
				<div style="color:#fff;"><i class="icon font page" data-page="/product/pay/creatTuan_<?=$pro['ProductId']?>">¥<?=$pro['Prices']['Team']?></i><?=$pro['TeamMemberLimit']?>人团</div>
				<?}?>
				<?if($pro['ProductType']==3){?>
				<div class="btn page" data-page="/product/pay/creatTuan_<?=$pro['ProductId']?>">申请试用</div>
				<?}?>
				<?if($pro['ProductType']==4){?>
						<?if(!$pro['isEnd'] == 'Y'){?>
							<div class="btn page" data-page="/product/pay/creatTuan_<?=$pro['ProductId']?>">立即夺宝</div>
						<?}else{?>
							<div class="btn">夺宝已完成</div>
						<?}?>
				<?}?>
			<?}?>
		</warp>
	</footer>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/product.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var ProductId = '<?=$pro["ProductId"]?>';

		$(function(){
			Product.init_page();
		});
	</script>