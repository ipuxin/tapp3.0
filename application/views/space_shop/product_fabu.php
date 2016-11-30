<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section imageBox{background:url('<?=$staticPath?>images/icon/photo.png') center #fdedd7 no-repeat;background-size:60px;height:140px;width:100%;display:block;margin-bottom:30px;}

section tit{padding:0 20px;display:block;color:#aaa;height:36px;line-height:36px;margin-top:20px;}
section tit:first-child{margin-top:30px;}
section tit span{float:right;color:#444;}
section line{margin:6px 20px 0;display:block;min-height:40px;line-height:40px;position:relative;}
section line.b{border-bottom:1px solid #ddd;}
section line t{font-size:16px;}
section line m{position:absolute;font-size:12px;left:0px;top:20px;}
section line span{float:right;color:#aaa}
section line span g{color:#666;margin-right:6px;}
section line input{text-align:right;}
section line textarea{width:100%;line-height:24px;min-height:72px;border:0;}
section line.in{padding-left:60px;}
section line.in t{position:absolute;left:0;top:0;height:100%;display:block;}
section line.in input{padding-right:12px;box-sizing:border-box;}
section line.in dw{position:absolute;right:0;top:0;height:100%;display:block;color:#aaa}
section line fontbox{position:absolute;right:0;bottom:-32px;}

section line btn.check{position:absolute;right:0;top:14px;height:14px;border-radius:4px;background-color:#eee;width:84px;}
section line btn.check check{position:absolute;top:2px;left:2px;height:10px;background-color:#fff;width:40px;border-radius:4px;}
section line btn.check.sel{background-color:#6c9;}
section line btn.check.sel check{left:42px;}

section line btn.check,section line btn.check check{transition:all 0.3s ease;}
</style>
<body>
	<header class="back">
		<tit>发布宝贝</tit>
		<div class="right">
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_shop_setting">
		<imageBox>
		</imageBox>
		<line class="b" style="margin-bottom:30px;">
			<textarea id="ProductName" placeholder="请输入宝贝标题"></textarea>
			<fontbox><f>0</f>/60</fontbox>
		</line>
		<line class="b in" style="display:none">
			<t>类目</t>
			<input placeholder=""></input>
			<dw>></dw>
		</line>
		<line class="b in">
			<t>价格</t>
			<input id="Prices_Normal" placeholder=""></input>
			<dw>¥</dw>
		</line>
		<line class="b in">
			<t>库存</t>
			<input id="StorageCount" placeholder=""></input>
		</line>
		<line class="b in">
			<t>运费</t>
			<input id="freightAmout" placeholder=""></input>
			<dw>¥</dw>
		</line>
		<line class="b in">
			<t>发货地</t>
			<input id="DeliverAddress" placeholder="上海市名航区国定东路200号" value="<?=$shop['DeliverAddress']?>"></input>
			<dw>></dw>
		</line>
		<line class="b in">
			<t>描述</t>
			<input id="Description" placeholder="请输入宝贝描述"></input>
			<dw>></dw>
		</line>
		<line class="b">
			<t>上架</t>
			<btn id="IsForSale" class="check sel"><check></check></btn>
		</line>
	</section>
	<footer>
		<btn>立即发布</btn>
	</footer>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/shop.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$(function(){
			//Shop.init_page();
			$('btn.check').click(function(){
				var check = $(this).hasClass('sel');
				if(check){
					$(this).removeClass('sel');
				}else{
					$(this).addClass('sel');
				}
			});
		});

		$('textarea').bind('input',(function(){
			var v = trim($(this).val());
			var l = v.length;
			if(l>'60'){
				v = v.substring(0,60);
				l = 60;
				$(this).val(v);
			}
			$('fontbox f').html(l);
		}));

		$('footer btn').click(function(){
			var data = getData();
			if(data){
				ajaxLocal('/space_shop/fabuBaobei',data,function(json){
					if(json.Pro.ProductId){
						pageTurn('/space_shop');
					}else{
						Msg('宝贝添加失败!');
					}
				});
			}
		});

		function getData(){
			var ProductName = $('#ProductName').val();
			var Prices_Normal = $('#Prices_Normal').val();
			var StorageCount = $('#StorageCount').val();
			var freightAmout = $('#freightAmout').val();
			var DeliverAddress = $('#DeliverAddress').val();
			var Description = $('#Description').val();
			var IsForSale = $('#IsForSale').hasClass('sel') ? 1:0;
			if(!ProductName){Msg('请输入宝贝标题');return;}
			if(!Prices_Normal){Msg('请输入宝贝价格');return;}
			if(!StorageCount){Msg('请输入宝贝库存');return;}
			if(!freightAmout){Msg('请输入运费');return;}
			if(!DeliverAddress){Msg('请输入发货地址');return;}
			if(!Description){Msg('请输入宝贝描述');return;}

			var Prices = {Normal:Prices_Normal};
			var data = {ProductName:ProductName,Prices:Prices,StorageCount:StorageCount,freightAmout:freightAmout,DeliverAddress:DeliverAddress,Description:Description,IsForSale:IsForSale};

			return data;
		}
	</script>