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
	<header class="back red" data-page="/space">
		<tit>我的订单</tit>
		<div class="right">
			<!--<span class="share"></span>-->
			<span class="more"></span>
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
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>" data-orderid="<?=$v['OrderId']?>" data-teamid="<?=$v['TeamId']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>">
				<tit class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>"><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
				<btns>
					<div class="cancer rbtn g">取消</div>
					<div class="pay rbtn">支付</div>
				</btns>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[2] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>">
				<tit class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>"><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
				<btns>
					<?if($v['TeamId']){?><div data-teamid="<?=$v['TeamId']?>" class="team rbtn">查看拼团</div><?}?>
				</btns>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[3] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>">
				<tit class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>"><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
				<btns>
					<div class="shouhuo rbtn">收货</div>
				</btns>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[4] as $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>">
				<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>">
				<tit class="page" data-page="/order/orderinfo/<?=$v['OrderId']?>"><?=$v['ProductInfo']['ProductName']?></tit>
				<price><r>¥<?=$v['OrderFee']?></r><span>数量：<?=$v['ProductCount']?></span></price>
				<btns>
					<div class="pinjia rbtn">评价</div>
				</btns>
			</li>
			<?}?>
		</ul>
	</section>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<!--<script type="text/javascript" src="<?=$staticPath?>js/order.js?v=<?=$version?>"></script>-->
	<script type="text/javascript">
		var listType = '<?=$type?>';
		var OrderId = '';
		var TeamId = '';

		function getTeamId(){return TeamId;}
		function getOrderId(){return OrderId;}

		function wechatPayFinish(type,orderid){
			var TeamId = getTeamId();
			var OrderId = getOrderId();
			if(!(type=='fail')){
				if(TeamId){
					pageTurn('/team/info/'+TeamId);
				}else{
					pageTurn('/order/orderinfo/'+OrderId);
				}
			}else{
				pageTurn('/order/orderinfo/'+OrderId);
			}
		}

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

		$('ul.order li btns div.shouhuo').click(function(){ 
			var orderId = $(this).parent().parent().data('id');
			showMsg('是否确定收货？','是','否',function(){orderShouhuo(orderId);});
		});

		$('ul.order li btns div.pay').click(function(){ 
			OrderId = $(this).parent().parent().data('orderid');
			TeamId = $(this).parent().parent().data('teamid');
			showMsg('是否需要支付订单？','是','否',function(){orderPay();});
		});

		$('ul.order li btns div.team').click(function(){ 
			var teamId = $(this).data('teamid');
			pageTurn('/team/info/'+teamId);
		});

		function orderCancel(orderId){
			ajaxLocal('/order/cancerOrder',{id:orderId},function(json){
				if(json.OrderUpdates && json.Success==true){
					pageTurn('/order/orderlist/1');
				}
			});
		}

		function orderShouhuo(orderId){
			ajaxLocal('/order/shouhuoOrder',{id:orderId},function(json){
				if(json.OrderUpdates && json.Success==true){
					pageTurn('/order/orderlist/4');
				}
			});
		}

		function orderPay(){
			ajaxLocal('/ajax/prepayWechat',{OrderId:OrderId},function(json){
				if(json.result=='Success'){
					if(json.json.result_code=='FAIL'){Msg(json.json.err_code_des);return;}
					var jsonP = json.json.PayRequest;
					console.log(jsonP);
					jsonP = JSON.stringify(jsonP);
					if(IS_IOS==0){
						android.wechatPay(jsonP);
					}else{
						document.location = "wechatPay:"+jsonP;
					}
				}else{
					Msg(json.msg,2);
				}
			});
		}


	</script>