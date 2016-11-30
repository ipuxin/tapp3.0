<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
orderhead{background-color:#fdedd7;height:100px;display:block;width:100%;}
orderhead div{background:url('<?=$staticPath?>images/order_info_bg.png') no-repeat right 8px;margin:0 20px;height:100%;background-size:140px;}
orderhead div p{font-size:12px;padding-left:12px;}
orderhead div p.r{font-size:16px;font-weight:bolder;padding-top:34px;margin-bottom:4px;}

addbox{padding:20px 0;margin:0 20px;display:block;border-bottom:1px solid #ff4242}
addbox p{line-height:28px;}
addbox p b{font-size:15px;font-weight:bolder;}

probox{margin:0 20px;padding:50px 0 20px 0;display:block;border-bottom:1px solid #ddd;padding-left:110px;position:relative;height:100px;}
probox shopname{display:block;position:absolute;left:0;top:15px;font-weight:bolder;font-size:15px;}
probox img{width:100px;height:100px;position:absolute;left:0;bottom:20px;}
probox tit{line-height:24px;font-size:16px;height:72px;display:block;overflow:hidden;margin-bottom:8px;}
probox p price{display:inline-block;}
probox p price r{font-size:20px;font-weight:bolder;margin-right:6px;}
probox p price s{margin-right:6px;color:#999;}
probox p span{float:right;margin-top:3px;}

ordercheck{margin:0 20px;padding:20px 0;display:block;border-bottom:1px solid #ddd;}
ordercheck div{margin-bottom:20px;}
ordercheck div a{height:32px;line-height:32px;color:#fff;font-weight:bolder;text-align:center;width:48%;display:inline-block;background-color:#ffa6a6;border-radius:2px;}
ordercheck p{text-align:right;font-size:12px;}
ordercheck p b{font-size:12px;}
ordercheck p rmb{font-size:16px;color:#ff4242}

orderinfo{margin:0 20px;padding:20px 0;display:block;border-bottom:1px solid #ddd;position:relative}
orderinfo p{font-size:12px;line-height:18px;}
orderinfo span.rbtn{position:absolute;right:0;bottom:20px;font-size:12px;line-height:12px;}

kuaidiinfo{border-bottom: 1px solid #ddd;margin: 0 20px;display: block;}
kuaidiinfo b{height: 60px;line-height: 60px;color: #777;display: block;font-size:15px;}
kuaidiinfo line{font-size:12px;line-height:18px;padding-bottom:10px;display:block;padding-left:94px;position:relative;}
kuaidiinfo line span{color:#999;margin-right:10px;position:absolute;top:0;left:0;}
</style>
<body>
	<header class="red back"><tit>订单详情</tit></header>
	<section id="page_order_info">
		<orderhead>
			<div>
				<p class="r"><?=$order['statusMsg']?></p>
				<!--<p>剩1天23小时自动关闭</p>-->
			</div>
		</orderhead>
		<addbox>
			<p><b>收货人：</b><?=$order['DeliveryInfo']['RealName']?> &nbsp; &nbsp; <b>电话：</b><?=$order['DeliveryInfo']['Mobile']?></p>
			<p><b>收货地址：</b><?=$order['DeliveryInfo']['RealAddress']?></p>
		</addbox>
		<?if($order['ProductList']){foreach($order['ProductList'] as $k=>$pro){?>
		<probox <?if($k>0){?>style="padding-top:20px;"<?}?>>
			<?if($k==0){?><shopname><?=$pro['ShopName']?></shopname><?}?>
			<img src="<?=$pro['ImageMin']?>">
			<tit><?=$pro['ProductName']?></tit>
			<p>
				<price><r>¥<?=$pro['Prices']?></r><s style="display:none"><?=$pro['Prices']?></s></price>
				<span>x<?=$pro['ProductCount']?></span>
			</p>
		</probox>
		<?}}else{?>
		<probox>
			<shopname><?=$order['ShopName']?></shopname>
			<img src="<?=$order['ProductInfo']['ImageMin']?>">
			<tit><?=$order['ProductInfo']['ProductName']?></tit>
			<p>
				<price><r>¥<?=$order['ProductInfo']['PricesAll'][$order['realPrice']]?></r><s style="display:none"><?=$order['ProductInfo']['PricesAll'][$order['showPrice']]?></s></price>
				<span>x<?=$order['ProductCount']?></span>
			</p>
		</probox>
		<?}?>
		<orderinfo>
			<p>订单编号：<?=$order['OrderId']?></p>
			<?if($order['PayTradeNo']){?><p>交易编号：<?=$order['PayTradeNo']?></p><?}?>
			<p>创建时间：<?=date('Y-m-d H:i:s',$order['CreatTime'])?></p>
			<?if($order['PayAmount']){?><p>支付金额：<?=$order['PayAmount']?></p><?}?>
			<?if($order['PayDateShow']){?><p>支付时间：<?=$order['PayDateShow']?></p><?}?>
			<?if($order['RefundFee']){?><p>退款金额：<?=$order['RefundFee']?></p><?}?>
			<?if($order['RefundDateShow']){?><p>退款时间：<?=$order['RefundDateShow']?></p><?}?>
			<span class="rbtn g">复制</span>
		</orderinfo>
		<ordercheck>
			<div class="btns flex"><a href="tel:<?=$shop['ShopOwnerMobile']?>">联系卖家</a><a href="tel:<?=$shop['ShopOwnerMobile']?>">拨打电话</a></div>
			<p>共 <r><?=$order['ProductCount']?></r> 件商品 <b>合计：<rmb>¥<?=$order['OrderFee']?></rmb></b> <?=$order['freightMsg']?></p>
		</ordercheck>
		<?if($order['KuaidiInfo']['Traces']){?>
		<kuaidiinfo>
			<b>快递信息</b>
			<?foreach($order['KuaidiInfo']['Traces'] as $v){$v['AcceptTime'] = substr($v['AcceptTime'],5);?>
			<line><span><?=$v['AcceptTime']?></span><?=$v['AcceptStation']?></line>
			<?}?>
		</kuaidiinfo>
		<?}?>
	</section>
	<footer class="red">
		<div></div>
		<?if($order['OrderStatus']==1){?>
			<div class="pay"><i class="icon warp menu_home"></i>付款</div>
			<div class="cancer"><i class="icon warp menu_home"></i>取消订单</div>
		<?}?>
		<?if($order['TeamId'] && $order['OrderStatus']!=1){?>
			<div><i class="icon warp menu_home page" data-page="/team/info/<?=$order['TeamId']?>"></i>查看拼团</div>
		<?}?>
		<?if($order['OrderStatus']==4){?>
			<div class="shouhuo"><i class="icon warp menu_home"></i>收货</div>
		<?}?>
		<div></div>
		<!--<div><i class="icon warp menu_home"></i>朋友代付</div>-->
	</footer>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<!--<script type="text/javascript" src="<?=$staticPath?>js/order.js?v=<?=$version?>"></script>-->
	<script type="text/javascript">
		var OrderRealId = '<?=$order["id"]?>';
		var OrderId = '<?=$order["OrderId"]?>';
		var TeamId = '<?=$order["TeamId"]?>';

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

		$('footer div.cancer').click(function(){ 
			showMsg('是否需要取消订单？','是','否',function(){orderCancel();});
		});

		$('footer div.pay').click(function(){ 
			orderPay();
		});

		$('footer div.shouhuo').click(function(){ 
			showMsg('是否确定收货？','是','否',function(){orderShouhuo();});
		});

		function orderCancel(){
			ajaxLocal('/order/cancerOrder',{id:OrderRealId},function(json){
				if(json.OrderUpdates && json.Success==true){
					pageTurn('/order/orderinfo/'+OrderId);
				}
			});
		}

		function orderShouhuo(){
			ajaxLocal('/order/shouhuoOrder',{id:OrderRealId},function(json){
				if(json.OrderUpdates && json.Success==true){
					pageTurn('/order/orderinfo/'+OrderId);
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