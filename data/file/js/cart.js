
var Cart = {

	defaultClick:function(){
		//编辑按钮功能
		Cart.updShopCartClick();
		//绑定删除事件
		Cart.delCartInfoClick();
		//绑定数字加减事件
		Cart.updCartNumClick();
		//点击选择产品事件
		Cart.selCartRowClick();
		//点击选择店铺事件
		Cart.selCartShopClick();
		//创建订单事件
		Cart.creatOrderClick();
	},

	updShopCartClick:function(){
		$('cartshop shopbox btn').click(function(){
			var upding = $(this).data('upding');
			var itembox = $(this).parent().parent().parent().find('itembox');

			if(upding){
				itembox.find('updbox').hide();
				$(this).html('编辑');
			}else{
				itembox.find('updbox').show();
				$(this).html('完成');
			}
			$(this).data('upding',(upding+1)%2);
		});
	},

	delCartInfoClick:function(){
		$('cartshop itembox updbox sc').click(function(){
			var ItemBox = $(this).parent().parent().parent();
			var CartId = ItemBox.data('cartid');
			showMsg('确认要删除这个宝贝吗','确认','取消',function(){Cart.delCartRow(CartId,ItemBox);});
		});
	},

	delCartRow:function(CartId,ItemBox){
		ajaxLocal('/cart/delCartRow',{CartId:CartId},function(json){
			if(!json || json==false){Msg('操作失败');return;}
			if(json.Code>0){Msg(json.Message);return;}
			if(json.ErrorCode>0){Msg(json.ErrorMessage);return;}
			if(json.Code===0){
				ItemBox.remove();
				var ShopBox = ItemBox.parent();
				if(!ShopBox.find('itembox').length){
					ShopBox.remove();
				}
			}
		});
	},

	updCartNumClick:function(){
		$('cartshop itembox updbox btn').click(function(){
			var btnType = $(this).data('t');
			var ItemBox = $(this).parent().parent().parent();
			var UpdBox = $(this).parent();
			var proCount = parseInt(ItemBox.data('count'));
			var CartId = ItemBox.data('cartid');

			if(btnType=='add'){proCount+=1;}else{proCount-=1;}
			if(proCount<=0){Msg('宝贝不能再减少了哦~');return;}

			Cart.updCartNum(CartId,ItemBox,proCount);
		});
	},

	updCartNum:function(CartId,ItemBox,proCount){
		ajaxLocal('/cart/updCartNum',{CartId:CartId,ProductCount:proCount},function(json){
			if(!json || json==false){Msg('操作失败');return;}
			if(json.Code>0){Msg(json.Message);return;}
			if(json.ErrorCode>0){Msg(json.ErrorMessage);return;}
			if(json.Code===0){
				Cart.setCartRowNum(ItemBox,proCount);
				Cart.checkCartAllSel();
			}
		});
	},

	setCartRowNum:function(ItemBox,proCount){
		ItemBox.data('count',proCount);
		ItemBox.find('num').html(proCount);
	},

	selCartRowClick:function(){
		$('cartshop itembox checkbox').click(function(){
			var has = $(this).hasClass('check');
			var cartShop = $(this).parent().parent();

			cartShop.siblings().find('checkbox').removeClass('check');

			if(has){
				$(this).removeClass('check');
			}else{
				$(this).addClass('check');
			}
			//检查整个商铺商品是否全选
			Cart.checkShopCartRowSel(cartShop);
			//重新计算统计
			Cart.checkCartAllSel();
		});
	},

	selCartShopClick:function(){
		$('cartshop shopbox checkbox').click(function(){
			var has = $(this).hasClass('check');
			var cartShop = $(this).parent().parent();

			cartShop.siblings().find('checkbox').removeClass('check');

			if(has){
				$(this).removeClass('check');
				cartShop.find('itembox checkbox').removeClass('check');
			}else{
				$(this).addClass('check');
				cartShop.find('itembox checkbox').addClass('check');
			}
			//重新计算统计
			Cart.checkCartAllSel();
		});
	},

	checkShopCartRowSel:function(shopBox){
		var shopCheckBox = shopBox.find('shopbox').find('checkbox');
		var hasNoSel = 0;
		shopBox.find('itembox').each(function(){
			var has = $(this).find('checkbox').hasClass('check');
			if(!has){
				hasNoSel = 1;
			}
		});
		if(hasNoSel){
			shopCheckBox.removeClass('check');
		}else{
			shopCheckBox.addClass('check');
		}
	},

	checkCartAllSel:function(){
		var countAll = 0;
		var priceAll = 0;
		var pricebox = $('pricebox');
		$('cartshop itembox checkbox.check').each(function(){
			var itembox = $(this).parent();
			var count = itembox.data('count');
			var price = itembox.data('price');
			countAll += count;
			priceAll += count*price;
		});
		
		priceAll = priceAll/100;

		pricebox.find('num').eq(0).html(priceAll);
		pricebox.find('num').eq(1).html(countAll);
	},

	creatOrderClick:function(){
		var pricebox = $('pricebox');
		pricebox.find('btn').click(function(){
			var pronum = $(this).find('num').html();
			if(pronum!=0){
				Cart.submitCart();
			}else{
				Msg('当前未选择产品');
			}
		});
	},

	submitCart:function(){
		var cart = Array();
		$('itembox').each(function(){
			var has = $(this).find('checkbox').hasClass('check');
			var cartid = $(this).data('cartid');
			if(has){
				cart.push(cartid);
			}
		});
		var data = {cart:cart};
		ajaxLocal('/cart/putCartToCheck/',data,function(json){
			if(json && json!=null){
				pageTurn('/product/pay/paycart');
			}
		});
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(){
		Cart.defaultClick();
	}
}