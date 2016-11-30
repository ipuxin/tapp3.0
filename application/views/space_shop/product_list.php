<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section tabs{height:68px;line-height:68px;border-bottom:1px solid #ff4242;margin:0 20px;}
section tabs tab{height:100%;display:block;padding:0 20px;}
section tabs tab.sel{color:#ff4242}

ul.product{margin:0 20px;display:none}
ul.product.sel{display:block;}
ul.product li{border-bottom:1px solid #ddd;position:relative;padding:20px 20px 60px;position:relative;box-sizing:border-box;padding-left:110px;line-height:22px;}
ul.product li img{position:absolute;top:20px;left:0;height:90px;width:90px;}
ul.product li tit{color:#666;font-size:14px;font-weight:bolder;display:inline-block;height:42px;overflow:hidden;}
ul.product li price{display:inline-block;width:100%;font-size:12px;color:#aaa;}
ul.product li price r{font-size:16px;margin-right:12px;}
ul.product li count{display:inline-block;width:100%;font-size:12px;}
ul.product li btns{width:100%;height:36px;line-height:36px;background-color:#f5f5f5;font-size:14px;position:absolute;bottom:14px;left:0;}
ul.product li btns btn{padding:0 12px;padding-left:32px;position:relative;}
ul.product li btns btn i{position:absolute;left:12px;height:14px;width:14px;top:10px;border-radius:3px;border:1px solid #ff4242;}
</style>
<body>
	<header class="back">
		<tit>宝贝管理</tit>
		<div class="right">
			<span class="share"></span>
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_shop_setting">
		<tabs class="flex">
			<tab class="sel">启用中(<num></num>)</tab>
			<tab>已下架(<num></num>)</tab>
		</tabs>
		<ul class="product sel"></ul>
		<ul class="product"></ul>
	</section>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/space_shop.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var forsale = 0;
		var forsaleno = 0;
		$(function(){
			//Shop.init_page();
			getProductList();
			$('tabs tab').click(function(){
				var index = $(this).index();
				$('tabs tab').removeClass('sel');
				$('ul.product').removeClass('sel');
				$(this).addClass('sel');
				$('ul.product').eq(index).addClass('sel');
			});
		});

		function getProductList(){
			ajaxLocal('/space_shop/getBaobeiList',{},function(json){
				if(json.Count==0){
					Msg('当前没有宝贝哦~~');return;
				}
				printProductList(json.List);
			});
		}

		function printProductList(list){
			for(var i in list){
				var pro = list[i];
				var warp = $('<li></li>');
				var line1 = $('<img src="'+pro.ImageMin+'">');
				var line2 = $('<tit>'+pro.ProductName+'</tit>');
				var line3 = $('<price><r>¥'+pro.Prices.Normal+'</r><sp>采购价 '+pro.Prices.Market+'</sp></price>');
				var line4 = $('<count>已售'+pro.SalesCountReal+' &nbsp; 库存'+pro.StorageCountReal+'</count>');
				var line5 = $('<btns data-id='+pro.id+' class="flex"></btns>');
				line5.append('<btn class="upd"><i class="icon nav_order"></i>编辑</btn>');
				line5.append('<btn class="forsale"><i class="icon nav_order"></i>下架</btn>');
				line5.append('<btn><i class="icon nav_order"></i>分享</btn>');
				warp.append(line1);
				warp.append(line2);
				warp.append(line3);
				warp.append(line4);
				warp.append(line5);
				if(pro.IsForSale){
					forsale += 1;
					$('ul.product').eq(0).append(warp);
				}else{
					forsaleno += 1;
					$('ul.product').eq(1).append(warp);
				}
			}
			$('tabs num').eq(0).html(forsale);
			$('tabs num').eq(1).html(forsaleno);
			tabsClick();
		}

		function tabsClick(){
			$('btns btn').click(function(){
				var index = $(this).index();
				var id = $(this).parent().data('id');
				if(index==0){
					pageTurn('/space_shop/product/upd/'+id);
				}
			});
		}
	</script>