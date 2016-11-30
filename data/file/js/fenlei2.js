
var Fenlei = {

	defaultClick:function(){
		$('bar span').click(function(){
			var type = $(this).data('type');
			
			if(type=='price' && $(this).hasClass('sel')){
				$(this).data('type','price2');
				$(this).html('价格 ↑');
				type = 'price2';
			}else if(type=='price2' && $(this).hasClass('sel')){
				$(this).data('type','price');
				$(this).html('价格 ↓');
				type = 'price';
			}

			$('bar span').removeClass('sel');
			$(this).addClass('sel');
			PaixuType = type;
			Fenlei.getproductList();
		});
	},

	//获取首页商品列表
	getproductList:function(){
		ajaxLocal('/ajax/getCateProductList/',{Category2:cateId,PaixuType:PaixuType},function(json){
			if(json && json!=null){
				if(json.Count>0){Fenlei.printProduct(json.List);}
			}
		});
	},

	//打印首页商品列表
	printProduct:function(list){
		$('ul.fenlei_pro').html('');
		for(var i in list){
			var pro = list[i];
			var box = $('<li class="page" data-page="/product/info/'+pro.ProductId+'"></li>');
			var line1 = $('<img src="'+pro.ImageMin+'">');
			var line2 = $('<p class="t">'+pro.ProductTypeShow+pro.ProductName+'</p>');
			//line2.append('<i class="hot"></i>');
			var line3 = $('<p><r>¥ '+pro.Prices.Normal+'</r><s>¥ '+pro.Prices.Market+'</s><i class="icon icon_cart sel"></i></p>');
			box.append(line1);
			box.append(line2);
			box.append(line3);
			$('ul.fenlei_pro').append(box);
		}
		setListImg();
		setPageTurn();
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(newLogin){
		Fenlei.defaultClick();
		Fenlei.getproductList();
	}
}