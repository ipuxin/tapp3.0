<?
	
?>
<script type="text/javascript">
	var cart_product = '<?=$Cart["ProductId"]?>';

	$('#cart_warp').click(function(){
		var data = {ProductId:cart_product};
		ajaxLocal('/cart/cartAdd',data,function(json){
			if(!json || json==false){Msg('添加购物车失败');return;}
			if(json.Code>0){Msg(json.Message);return;}
			if(json.ErrorMessage){
				
				if(json.ErrorMessage=="当前商品已经在购物车"){pageTurn('/cart');}

				Msg(json.ErrorMessage);
				Product.btnSub=0;
				return;
			}
			if(json.Success){
				Msg('添加购物车成功');
			}else{
				Msg('添加购物车失败');Product.btnSub=0;return;
			}
		});
	});
</script>