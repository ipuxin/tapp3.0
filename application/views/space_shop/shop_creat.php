<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
section tit{padding:0 20px;display:block;color:#aaa;height:36px;line-height:36px;margin-top:20px;}
section tit:first-child{margin-top:30px;}
section tit span{float:right;color:#444;}
section line{margin:6px 20px 0;display:block;min-height:40px;line-height:40px;position:relative;}
section line.b{border-bottom:1px solid #ddd;}
section line t{font-size:16px;}
section line m{position:absolute;font-size:12px;left:0px;top:20px;}
section line span{float:right;color:#aaa}
section line span g{color:#666;margin-right:6px;}
section line img{height:50px;width:50px;border-radius:6px;position:absolute;top:0;right:0px;}
section line input{text-align:right;}
section line btn.check{position:absolute;right:0;top:14px;height:14px;border-radius:4px;background-color:#eee;width:84px;}
section line btn.check check{position:absolute;top:2px;left:2px;height:10px;background-color:#fff;width:40px;border-radius:4px;}
section line btn.check.sel{background-color:#6c9;}
section line btn.check.sel check{left:42px;}

section line btn.check,section line btn.check check{transition:all 0.3s ease;}
</style>
<body>
	<header class="back">
		<tit>店铺申请</tit>
		<div class="right">
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_shop_setting">
		<tit>店铺设置</tit>
		<line class="b" style="height:70px;">
			<t>店铺头像</t></br><m>快来上传您满意的头像吧~</m>
			<img src="<?=$headimgurl?>">
		</line>
		<line class="b">
			<t>店铺名称</t>
			<input id="ShopName" placeholder="" value="<?=$shop['ShopName']?>"></input>
		</line>
		<line class="b">
			<t>联系人</t>
			<input id="ShopOwnerName" placeholder="" value="<?=$shop['ShopOwnerName']?>"></input>
		</line>
		<line class="b">
			<t>联系人手机</t>
			<input id="ShopOwnerMobile" placeholder="" value="<?=$shop['ShopOwnerMobile']?>"></input>
		</line>
		<line class="b">
			<t>店铺介绍</t>
			<input id="ShopDescription" placeholder="" value="<?=$shop['ShopDescription']?>"></input>
		</line>
		<line class="b">
			<t>店铺地址</t>
			<input id="ShopAddress" placeholder="" value="<?=$shop['ShopAddress']?>"></input>
		</line>
		<!--<line class="b">
			<t>发货地址</t>
			<input id="DeliverAddress" placeholder="" value="<?=$shop['DeliverAddress']?>"></input>
		</line>
		<line class="b">
			<t>退货地址</t>
			<input id="ReturnAddress" placeholder="" value="<?=$shop['ReturnAddress']?>"></input>
		</line>-->
		<tit>允许附近发现的地址信息<span>关于附近发现</span></tit>
		<line class="b" style="height:56px;margin-top:-5px;">
			<t>允许附近可见</t>
			<btn class="check"><check></check></btn>
		</line>
		<tit>其他</tit>
		<line>
			<t>绑定城市</t><span><g><?=$cityName?></g>></span>
		</line>
		<line style="display:none">
			<t>消息设置</t><span>></span>
		</line>
		<line style="display:none">
			<t>保证金</t><span><g>¥2000</g>></span>
		</line>
		<line style="display:none">
			<t>帮助与反馈</t><span>></span>
		</line>
	</section>
	<footer>
		<btn>申请开通</btn>
	</footer>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
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
		
		$('footer btn').click(function(){
			var data = getData();
			if(data){
				ajaxLocal('/space_shop/shopAdd',data,function(json){
					if(json.Shop){
						pageTurn('/space_shop');
					}else{
						Msg('店铺申请失败!');
					}
				});
			}
		});

		function getData(){
			var ShopName = $('#ShopName').val();
			var ShopOwnerName = $('#ShopOwnerName').val();
			var ShopOwnerMobile = $('#ShopOwnerMobile').val();
			var ShopDescription = $('#ShopDescription').val();
			var ShopAddress = $('#ShopAddress').val();
			if(!ShopName){Msg('请输入店铺名称');return;}
			if(!ShopOwnerName){Msg('请输入联系人姓名');return;}
			if(!ShopOwnerMobile){Msg('请输入联系人手机');return;}
			if(!ShopDescription){Msg('请输入店铺介绍');return;}
			if(!ShopAddress){Msg('请输入店铺地址');return;}

			var data = {ShopName:ShopName,ShopOwnerName:ShopOwnerName,ShopOwnerMobile:ShopOwnerMobile,ShopDescription:ShopDescription,ShopAddress:ShopAddress};

			return data;
		}
	</script>