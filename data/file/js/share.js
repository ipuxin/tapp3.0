var Share = {

	getCoupon:function(){
		ajaxLocal('/coupon/getUserCoupon/'+CouponId,{},function(json){
			if(json.ErrorCode>0){
				Share.couponFail(json.ErrorMessage);
			}else{
				Share.printCoupon(json.userCoupon);
			}
			Share.giveCoupon(json.HasCoupon);
			Share.getProduct(json.ProductTuijian);
		});
	},

	getProduct:function(ProductId){
		if(ProductId){
			ajaxLocal('/ajax/getProductInfo/'+ProductId,{},function(json){
				if(json){
					Share.printProduct(json);
				}else{
					Msg(json.ErrorMsg,2);
				}
			});
		}else{
			$('p.line').hide();
		}
	},

	printProduct:function(json){
		var pro = json;
		var url = '/product/info/'+pro.ProductId;
		var image = '';
		if(pro.Images && pro.ImagesShow)image = pro.ImagesShow[0];
		var pro_collection = '';
		var warp = $('<li></li>');
		var box = $('<div class="box"></div>');
		var box_img = $('<a href="'+url+'"><img class="pro" src="'+image+'"></a>');
		var tit = $('<p class="tit">'+pro.ProductNameMin+'</p>');
		var des = $('<p class="des">'+pro.DescriptionMin+'</p>');
		var price = pro.Prices.Team;
		price = price.toString();
		price = price.split(".");
		if(price[1]){price[0]+='.';}else{price[1]='';}
		var pri = $('<p class="pri"><rmb>¥</rmb><b>'+price[0]+'</b><b class="s">'+price[1]+'</b><span>元</span></p>');
		var con = $('<div class="con"></div>');
		var con_btns = $('<span><i class="icon tuan"></i>'+pro.TeamMemberLimit+'人团</span><a href="'+url+'">去开团</a>');
		box.append(box_img);
		con.append(con_btns);
		warp.append(box);
		warp.append(tit);
		warp.append(des);
		warp.append(pri);
		warp.append(con);
		$('ul.product').append(warp);
	},

	printCoupon:function(json){
		var msg = '';
		var line = '<p><rmb>¥</rmb><b> '+json.CouponAmount+' </b>优惠券</p>';
		
		if(json.CityName){msg += '仅限'+json.CityName+' ';}
		if(json.CouponLimits){msg += '满'+json.CouponLimits+'元可用 ';}
		if(json.OrderType){msg += '仅限'+json.OrderTypeShow+'使用 ';}
		if(json.ProductId){msg += '仅限产品 ( '+json.ProductName+' ) 使用 ';}
		if(msg){
			line += '<p style="bottom:42px;background-color:#e60000;color:#fff;font-size:12px;overflow:hidden;" class="bt">'+msg+'</p>';
			$('div.coupon').css('height',110);
		}

		line += '<p class="bt">有效期 '+json.StartDateShow+' ~ '+json.ExpiryDateShow+'</p>';
		$('div.coupon').append(line);
		$('div.coupon').show();
	},

	couponFail:function(msg){
		$('p.title').html(msg);
		$('img.msg').show();
		$('div.coupon').hide();
		$('p.msg').hide();
	},

	giveCoupon:function(has){
		if(has){$('p.msg').show();}
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(type){
		Share.getCoupon();
	}
}