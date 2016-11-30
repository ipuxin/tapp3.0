<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
probox{margin:0 20px;padding:50px 0 20px 0;display:block;border-bottom:1px solid #ddd;padding-left:110px;position:relative;height:100px;}
probox shopname{display:block;position:absolute;left:0;top:15px;font-weight:bolder;font-size:15px;}
probox img{width:100px;height:100px;position:absolute;left:0;bottom:20px;}
probox tit{line-height:24px;font-size:16px;height:72px;display:block;overflow:hidden;margin-bottom:8px;}
probox p price{display:inline-block;}
probox p price r{font-size:20px;font-weight:bolder;margin-right:6px;}
probox p price s{margin-right:6px;color:#999;}
probox p span{float:right;margin-top:3px;}

orderset{padding:20px 0;margin:0 20px;display:block;border-bottom:1px solid #ddd;}
orderset p{margin-bottom:12px;}
orderset p.t{font-size:14px;font-weight:bolder;}
orderset p span{float:right;color:#ccc;font-size:12px;font-weight:normal;}
orderset p span f{color:#666;font-size:12px;margin-right:6px;}
orderset p span btn{height:32px;width:32px;line-height:32px;text-align:center;background-color:#f5f5f5;color:#666;display:inline-block;}
orderset p span num{padding:0 14px;color:#333}
orderset p textarea{color:#666;width:100%;height:60px;border:0;}
orderset p tag{font-size:12px;margin-right:12px;}
orderset p tag i{padding:1px;border:1px solid #ff4242;color:#ff4242;font-size:12px;line-height:12px;border-radius:4px;width:12px;display:inline-block;text-align:center;margin-right:4px;}

tongji{height:50px;line-height:50px;text-align:right;margin:0 20px;display:block;}
tongji b{font-size:13px;}
tongji b r{font-size:18px;}

payset{margin:0 20px;display:block;}
payset p{line-height:40px;}
payset span{float:right;display:inline-block;height:24px;width:24px;border:1px solid #ddd;border-radius:4px;margin-top:8px;}
</style>
<body>
	<header class="back"><tit>确认订单</tit></header>
	<section id="page_shop">
		<?
			if($shop['CityCode'])$addressBox['cityCode'] = $shop['CityCode'];
			include('page/address_box.php');
		?>
		<probox>
			<shopname><?=$shop['ShopName']?></shopname>
			<img src="<?=$pro['ImageMin']?>">
			<tit><?=$pro['ProductName']?><i class="hot"></i></tit>
			<input type="hidden" value="<?=$pro['ProductType']?>" name="ProductType">
			<p>
				<price><r>¥<?=$pro['Prices']['Team']?></r><s><?=$pro['Prices']['Normal']?></s></price>
				<span style="display:none">x3</span>
			</p>
		</probox>
		<orderset>
			<p class="t" style="margin-bottom:20px;">购买数量：
				<span data-kuaidi="<?=$pro['freightAmout']?>" data-price="<?=$pro['Prices']['Team']?>" style="margin-top:-6px;">
					<?if(!$numSetDisable){?><btn class="del">-</btn><?}?>
					<num>1</num>
					<?if(!$numSetDisable){?><btn class="add">+</btn><?}?>
				</span>
			</p>
			<p>
				<tag><i>正</i>正品保证</tag>
				<tag><i><s>7</s></i>不支持7天退款</tag>
				<tag><i>退</i>极速退款</tag>
			</p>
			<p class="t">配送方式：
				<span><f>快递 <?if($pro['freightAmout']){echo $pro['freightAmout']."元";}else{echo "免邮";}?></f> ></span>
			</p>
			<p class="t">买家留言：</p>
			<p>
				<textarea id="remark" placeholder="选填，可填写您与卖家达成一致的要求"></textarea>
			</p>
		</orderset>
		<tongji>
			共<r>1</r>件商品 &nbsp; <b>合计：<r>¥<?=$pro['Prices']['Team']+$pro['freightAmout']?></r></b>
		</tongji>
		<payset>
			<p>可使用拼一下优惠券<span></span></p>
			<p>朋友代付<span></span></p>
			<p>匿名购买<span></span></p>
		</payset>
	</section>
	<footer class="btn">
		<btn>确 定</btn>
	</footer>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/product_creat_tuan.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var ProductId = '<?=$pro["ProductId"]?>';
		var ProductNum = 1;

		$(function(){
			Product.init_page();
		});
	</script>