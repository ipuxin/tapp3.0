<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section tabs{height:68px;line-height:68px;border-bottom:1px solid #ff4242;margin:0 20px;}
section tabs tab{height:100%;display:block;padding:0 10px;}
section tabs tab.sel{color:#ff4242}

ul.order{margin:0 20px;display:none}
ul.order.sel{display:block;}
ul.order li{border-bottom:1px solid #ddd;position:relative;padding:20px 20px 40px;position:relative;box-sizing:border-box;padding-left:110px;line-height:22px;}
ul.order li img{position:absolute;top:20px;left:0;height:90px;width:90px;}
ul.order li tit{color:#666;font-size:14px;font-weight:bolder;display:inline-block;height:42px;overflow:hidden;}
ul.order li price{display:inline-block;width:100%;font-size:12px;color:#aaa;}
ul.order li price r{font-size:16px;margin-right:12px;}
ul.order li count{display:inline-block;width:100%;font-size:12px;}
ul.order li btns{height:26px;line-height:16px;font-size:14px;position:absolute;bottom:4px;right:0;}
ul.order li btns div{float:left;margin-left:12px;}
</style>
<body>
	<header class="back" data-page="/space">
		<tit>我的订单</tit>
		<div class="right">
			<span class="share"></span>
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_order_list">
		<tabs class="flex">
			<tab class="sel">待付款</tab>
			<tab>待发货</tab>
			<tab>待收货</tab>
			<tab>待评价</tab>
		</tabs>
		<ul class="order sel">
			<?foreach($list[1] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>">
				<tit><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[2] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>">
				<tit><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
				<btns>
					<div class="fahuo rbtn">发货</div>
				</btns>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[3] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>">
				<tit><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[4] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>">
				<tit><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
			</li>
			<?}?>
		</ul>
	</section>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/order.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var listType = '<?=$type?>';

		$(function(){
			//Shop.init_page();
			$('tabs tab').click(function(){
				var index = $(this).index();
				$('tabs tab').removeClass('sel');
				$('ul.order').removeClass('sel');
				$(this).addClass('sel');
				$('ul.order').eq(index).addClass('sel');
			});

			$('tabs tab').eq(listType-1).click();
		});

		$('ul.order li btns div.cancer').click(function(){ 
			var orderId = $(this).parent().parent().data('id');
			showMsg('是否需要取消订单？','是','否',function(){orderCancel(orderId);});
		});

		$('ul.order li btns div.pay').click(function(){ 
			var orderId = $(this).parent().parent().data('id');
			showMsg('是否需要支付订单？','是','否',function(){orderPay(orderId);});
		});

		function orderCancel(orderId){
			ajaxLocal('/order/cancerOrder',{id:orderId},function(json){
				if(json.OrderUpdates==1){pageTurn('/order/orderlist/1');}
			});
		}

		function orderPay(orderId){
			ajaxLocal('/order/payOrder',{id:orderId},function(json){
				if(json && json.orderInfo){pageTurn('/order/orderlist/2');}
			});
		}


	</script>