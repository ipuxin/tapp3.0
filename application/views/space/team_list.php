<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section tabs{height:68px;line-height:68px;border-bottom:1px solid #F1F1F1;margin:0 20px;}
section tabs tab{height:100%;display:block;padding:0 10px;}
section tabs tab.sel{color:#ff4242}

ul.order{padding:0 20px;display:none;}
ul.order.sel{display:block;}
ul.order li{position:relative;sborder-bottom:1px solid #ddd;position:relative;padding-top:10px;position:relative;box-sizing:border-box;line-height:22px;}
ul li .title{width: 100%;height:70px;border-bottom: 1px solid #F1F1F1;font-size: 16px;}
ul li .title img{position: absolute;top:20px; left:0;height:52px;width:52px;border-radius: 8px;}
ul li .title tit{position:absolute;left:68px;top: 36px;}
ul li .title span{position: absolute;right: 0px;height: 25px;color: red;top:36px;}
ul li .content{width: 100%;height:190px;position: relative;}
ul li .content .biaoqian{border: 1px solid red;border-radius: 4px;color: red;font-size: 12px;margin-right: 3px;}
ul li .content .neirong{height: 100%; height: 130px;border-bottom: 1px solid #F1F1F1;}
ul li .content img{width:30%;height: 100px;position: absolute;top: 20px;left: 0px;border-radius: 8px;}
ul li .content tit{width: 66%;height: 50px;position: absolute;top: 20px; left: 34%;font-size: 16px;display:inline-block;}
ul.order li price{display:inline-block;width:66%;font-size:12px;color:#aaa;position: absolute;top:100px;left: 34%;}
ul.order li price r{font-size:16px;margin-right:12px;}
ul.order li count{display:inline-block;width:100%;font-size:12px;}
ul.order li btns{height:32px;position:absolute;bottom:16px;left:40%;width: 60%;}
ul.order li btns div{font-size: 16px;line-height: 32px;float: right;margin-left: 5px;border: 1px solid #ccc;border-radius: 4px;padding: 0 5px;}
</style>
<body>
	<header class="back" data-page="/space">
		<tit style="font-weight: 700;color: #757575;">我的订单</tit>
		<div class="right">
		</div>
	</header>
	<section id="page_order_list">
		<tabs class="flex">
			<tab class="sel">全部</tab>
			<tab>拼团中</tab>
			<tab>已成团</tab>
			<tab>拼团失败</tab>
		</tabs>
		<ul class="order">
			<?foreach($res as $k => $v){?>
			<li>
				<div class="title page" data-page="/shop/info/<?=$v['ShopId']?>">
					<img src="<?=$v['ShopLogo']?>" alt="">
					<tit><?=$v['ShopName']?></tit><i class="icon_right"></i>
					<span style="color: red">
						<?
							if($v['TeamStatus'] == 4) {
								echo '拼团失败';
							}elseif($v['TeamStatus'] == 3){
								echo '拼团成功';
							}elseif($v['TeamStatus'] == 2){
								echo '正在拼团';
							}elseif($v['TeamStatus'] == 1){
								echo '未支付';
							}
						?>
					</span>
				</div>
				<div class="content">
					<div class="neirong">
						<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/Product/info/<?=$v['ProductId']?>">
						<tit class="page" data-page="/Product/info/<?=$v['ProductId']?>"><i class="biaoqian"><?if($v['ProductInfo']['ProductType']==2){echo "拼团";}elseif($v['ProductInfo']['ProductType']==3){echo "免费试用";}elseif($v['ProductInfo']['ProductType']==4){echo "1元夺宝";}elseif($v['ProductInfo']['ProductType']==5){echo "幸运抽奖";}?></i> <?=$v['ProductInfo']['ProductName']?></tit>
						<price><r>¥<?=$v['ProductInfo']['Prices']['Team']?></r><span><?=$v['MaxOrderCount']?>人团</span></price>
					</div>
					<btns>
						<div class="page" data-page="/team/info/<?=$v['TeamId']?>" class="xiangqing">拼团详情</div>
					</btns>
				</div>
				<hr>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[2] as $k => $v){?>
			<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>" data-orderid="<?=$v['OrderId']?>" data-teamid="<?=$v['TeamId']?>">
				<div class="title">
					<img src="<?=$v['ShopLogo']?>" alt="">
					<tit><?=$v['ShopName']?></tit><i class="icon_right"></i>
					<span style="color: red">正在拼团</span>
				</div>
				<div class="content">
					<div class="neirong">
						<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/Product/info/<?=$v['ProductId']?>">
						<tit class="page" data-page="/Product/info/<?=$v['ProductId']?>"><i class="biaoqian"><?if($v['ProductInfo']['ProductType']==2){echo "拼团";}elseif($v['ProductInfo']['ProductType']==3){echo "免费试用";}elseif($v['ProductInfo']['ProductType']==4){echo "1元夺宝";}elseif($v['ProductInfo']['ProductType']==5){echo "幸运抽奖";}?></i><?=$v['ProductInfo']['ProductName']?></tit>
						<price><r>¥<?=$v['ProductInfo']['Prices']['Team']?></r><span><?=$v['MaxOrderCount']?>人团</span></price>
					</div>
					<btns>
						<div class="xiangqing page" data-page="/team/info/<?=$v['TeamId']?>" >拼团详情</div>
					</btns>
				</div>
				<hr>
			</li>
			<?}?>
		</ul>
		<ul class="order">
			<?foreach($list[3] as $k => $v){?>
				<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>" data-orderid="<?=$v['OrderId']?>" data-teamid="<?=$v['TeamId']?>">
					<div class="title">
						<img src="<?=$v['ShopLogo']?>" alt="">
						<tit><?=$v['ShopName']?></tit><i class="icon_right"></i>
						<span style="color: red">已成团</span>
					</div>
					<div class="content">
						<div class="neirong">
							<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/Product/info/<?=$v['ProductId']?>">
							<tit class="page" data-page="/Product/info/<?=$v['ProductId']?>"><i class="biaoqian"><?if($v['ProductInfo']['ProductType']==2){echo "拼团";}elseif($v['ProductInfo']['ProductType']==3){echo "免费试用";}elseif($v['ProductInfo']['ProductType']==4){echo "1元夺宝";}elseif($v['ProductInfo']['ProductType']==5){echo "幸运抽奖";}?></i><?=$v['ProductInfo']['ProductName']?></tit>
							<price><r>¥<?=$v['ProductInfo']['Prices']['Team']?></r><span><?=$v['MaxOrderCount']?>人团</span></price>
						</div>
						<btns>
							<div class="xiangqing page" data-page="/team/info/<?=$v['TeamId']?>" >拼团详情</div>
						</btns>
					</div>
					<hr>
				</li>
			<?}?>
			</li>
		</ul>
		<ul class="order">
			<?foreach($list[4] as $k => $v){?>
				<li id="order_<?=$v['id']?>" data-id="<?=$v['id']?>" data-orderid="<?=$v['OrderId']?>" data-teamid="<?=$v['TeamId']?>">
					<div class="title">
						<img src="<?=$v['ShopLogo']?>" alt="">
						<tit><?=$v['ShopName']?></tit><i class="icon_right"></i>
						<span style="color: red">拼团失败</span>
					</div>
					<div class="content">
						<div class="neirong">
							<img src="<?=$v['ProductInfo']['ImageMin']?>" class="page" data-page="/Product/info/<?=$v['ProductId']?>">
							<tit class="page" data-page="/Product/info/<?=$v['ProductId']?>"><i class="biaoqian"><?if($v['ProductInfo']['ProductType']==2){echo "拼团";}elseif($v['ProductInfo']['ProductType']==3){echo "免费试用";}elseif($v['ProductInfo']['ProductType']==4){echo "1元夺宝";}elseif($v['ProductInfo']['ProductType']==5){echo "幸运抽奖";}?></i><?=$v['ProductInfo']['ProductName']?></tit>
							<price><r>¥<?=$v['ProductInfo']['Prices']['Team']?></r><span><?=$v['MaxOrderCount']?>人团</span></price>
						</div>
						<btns>
							<div class="xiangqing page" data-page="/team/info/<?=$v['TeamId']?>" >拼团详情</div>
						</btns>
					</div>
					<hr>
				</li>
			<?}?>
		</ul>
	</section>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var listType = '1';
		var OrderId = '';
		var TeamId = '';

		function jsApiCall(json){
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				json,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					if(res.err_msg=='get_brand_wcpay_request:ok'){//支付成功
						//Msg('支付成功');
						if(TeamId){
							pageTurn('/team/info/'+TeamId);
						}else{
							pageTurn('/order/orderinfo/'+OrderId);
						}
					}else{
						pageTurn('/order/orderinfo/'+OrderId);
					}
				}
			);
		}

		function callpay(jcode){
			//alert(jcode);
			if (typeof WeixinJSBridge == "undefined"){
				if( document.addEventListener ){
					document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				}else if (document.attachEvent){
					document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
					document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				}
			}else{
				jsApiCall(jcode);
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
			ajaxLocal('/wxpay/orderSubmit',{OrderId:OrderId},function(json){
				if(json.ErrorCode==0){
					var JsonCode = eval('('+json.JsonCode+')');
					callpay(JsonCode);
				}else{
					Msg(json.ErrorMsg,2);
				}
			});
		}


	</script>