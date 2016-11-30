
var Shop = {

	defaultClick:function(){
		Shop.getproductList();
	},

	//获取首页商品列表
	getproductList:function(){
		ajaxLocal('/ajax/getProductListShop/',{ShopId:ShopId},function(json){
			if(json && json!=null){
				if(json.Count>0){Shop.printProduct(json.List);}
			}
		});
	},

	//打印首页商品列表
	printProduct:function(list){
		for(var i in list){
			var pro = list[i];
			var box = $('<li></li>');
			var line1 = $('<img src="'+pro.ImageBig+'">');
			var line2 = $('<tit>'+pro.ProductTypeShow+pro.ProductName+'</tit>');
			line2.append('<i class="hot"></i>');
			var line3 = $('<p><price><r>¥'+pro.Prices.Normal+'</r><s>¥'+pro.Prices.Market+'</s></price><btn class="rbtn page" data-page="/product/info/'+pro.ProductId+'">去抢购 &gt;</btn></p>');
			box.append(line1);
			box.append(line2);
			box.append(line3);
			$('ul.cateprolist').append(box);
		}
		setPageTurn();
	},

	resize:function(){
		$(window).resize(function(){
			Shop.resetHotList();
		});
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(){
		Shop.defaultClick();
		Shop.resize();
	}
}