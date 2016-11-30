
var Fenlei = {

	defaultClick:function(){
		
	},

	//获取首页商品列表
	getproductCateList:function(){
		ajaxLocal('/ajax/getproductCateList/',{},function(json){
			if(json && json!=null){
				if(json){
					Fenlei.printCate(json.CateList);
				}
			}
		});
	},

	printCate:function(list){
		for(var i in list){
			var cate = list[i];
			var line = '<li>'+cate.CateName+'</li>';
			$('ul.goods-left').append(line);

			var CateBox = '<ul class="goods-right" id="cate_'+cate.id+'"><li class="tit"><i class="icon icon_cate_tit"></i>'+cate.CateName+'</li></ul>';
			$('section').append(CateBox);
			Fenlei.printChild(cate.Child,cate.id);
		}
		Fenlei.setCateClick();
		$('ul.goods-left li').eq(0).click();
		setRightHeight();
	},
	
	setCateClick:function(){
		$('ul.goods-left li').click(function(){
			var index = $(this).index();
			$('ul.goods-right').hide();
			$('ul.goods-right').eq(index).show();
			$('ul.goods-left li').removeClass('sel');
			$(this).addClass('sel');
		});
	},

	printChild:function(list,id){
		for(var i in list){
			var cate = list[i];
			var box = $('<li class="page" data-page="/main/fenlei2/'+cate.id+'"></li>');
			var line1 = $('<img src="'+cate.ImgUrlShow+'">');
			var line2 = $('<p>'+cate.CateName+'</p>');
			box.append(line1);
			box.append(line2);
			$('#cate_'+id).append(box);
		}
		setPageTurn();
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(newLogin){
		Fenlei.defaultClick();
		Fenlei.getproductCateList();
	}
}