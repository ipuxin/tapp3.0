
var Pintuan = {

	ProductType:0,

	defaultClick:function(){
		Pintuan.resetNavWidth();
	},
	
	//菜单栏宽度重设
	resetNavWidth:function(){
		var w = $('section').width();
		step = (w-320)/4+20;
		$('div.nav_list').addClass('show');
		$('div.nav_list').css('padding-left',step);
		$('div.nav_list').css('padding-right',step);
	},
	
	//加载banner效果
	startBanner:function(){
		jQuery(".slide_1").show();
		var mySwiper = new Swiper(".swiper-container",{
			autoplay: 3000,
			pagination:".pagination",
			loop:true,
			grabCursor:true,
			calculateHeight:false,
			paginationClickable:true
		});
	},
	
	//顶部菜单栏滚动效果
	navScroll:function(){
		$('section').scroll(function(){
			var top = $(this).scrollTop();
			var pad = 12;
			if(top>=500){
				$('div.nav_list').css('padding-left',12);
				$('div.nav_list').css('padding-right',12);
			}else{
				Pintuan.resetNavWidth();
			}
		});
	},

	resize:function(){
		$(window).resize(function(){
			Pintuan.resetNavWidth();
		});
	},

	//获取拼团商品列表
	getproductList:function(){
		ajaxLocal('/ajax/getProductPinTuanList/',{ProductType:Pintuan.ProductType},function(json){
			if(json && json!=null){
				if(json.Count>0){
					if(Pintuan.ProductType==3){
						Pintuan.printShiyongProduct(json.List);
					}else if(Pintuan.ProductType==4){
						Pintuan.printDuobaoProduct(json.List);
					}else{
						Pintuan.printProduct(json.List);
					}
				}
			}
		});
	},

	//打印试用商品列表
	printShiyongProduct:function(list){
		for(var i in list){
			var pro = list[i];
			var box = $('<li></li>');
			var line1 = $('<img src="'+pro.ImageMin+'">');
			var line2 = $('<tit>'+pro.ProductName+'</tit>');
			line2.append('<i class="hot"></i>');
			var line3 = $('<p><teaminfo>'+pro.TeamMemberLimit+'人团 | <r>'+pro.SalesCountReal+'</r>人已申请</teaminfo><price>价值<r>¥'+pro.Prices.Normal+'</r><m>限量 '+pro.StorageCount+' 件</m></price><btn class="rbtn page" data-page="/product/info/'+pro.ProductId+'">免费试用</btn></p>');
			box.append(line1);
			box.append(line2);
			box.append(line3);
			$('ul.cateprolist').append(box);
		}
		setListImg();
		setPageTurn();
	},

	//打印夺宝商品列表
	printDuobaoProduct:function(list){
		for(var i in list){
			var pro = list[i];
			var box = $('<li></li>');

			var line2 = $('<tit>'+pro.ProductName+'</tit>');
			line2.append('<i class="hot"></i>');
			if(!pro.isEnd){
				var line1 = $('<img src="'+pro.ImageMin+'">');
				var line3 = $('<p><teaminfo>'+pro.TeamMemberLimit+'人夺宝| <r>'+pro.SalesCountReal+'</r>人已申请</teaminfo><price>价值<r>¥'+pro.Prices.Normal+'</r><m>限量 '+pro.StorageCount+' 件</m></price><btn class="rbtn page" data-page="/product/info/'+pro.ProductId+'">立即抢夺</btn></p>');
			}else{
				if(pro.isEnd == 'N'){
					var line3 = $('<p><teaminfo>'+pro.TeamMemberLimit+'人夺宝| <r>'+pro.SalesCountReal+'</r>人已申请</teaminfo><price>价值<r>¥'+pro.Prices.Normal+'</r><m>限量 '+pro.StorageCount+' 件</m></price><btn class="rbtn page" style="background:#ccc;border: none" data-page="/product/info/'+pro.ProductId+'">夺宝失败</btn></p>');
					var line1 = $('<i class="duobaoshibai"></i><img src="'+pro.ImageMin+'">');
				}else{
					var line3 = $('<p><teaminfo>'+pro.TeamMemberLimit+'人夺宝| <r>'+pro.SalesCountReal+'</r>人已申请</teaminfo><price>价值<r>¥'+pro.Prices.Normal+'</r><m>限量 '+pro.StorageCount+' 件</m></price><btn class="rbtn page" style="background:#ccc;border: none" data-page="/product/info/'+pro.ProductId+'">夺宝成功</btn></p>');
					var line1 = $('<i class="duobaochenggong"></i><img src="'+pro.ImageMin+'">');
				}

			}

			box.append(line1);
			box.append(line2);
			box.append(line3);
			$('ul.cateprolist').append(box);
		}
		setListImg();
		setPageTurn();
	},

	//打印商品列表
	printProduct:function(list){
		for(var i in list){
			var pro = list[i];
			var box = $('<li></li>');
			var line1 = $('<img src="'+pro.ImageBig+'">');
			var line2 = $('<tit>'+pro.ProductName+'</tit>');
			line2.append('<i class="hot"></i>');
			var line3 = $('<p><teaminfo><i class="icon team_r"></i>'+pro.TeamMemberLimit+'人团</teaminfo><price><r>¥'+pro.Prices.Team+'</r><s>¥'+pro.Prices.Market+'</s></price><btn class="rbtn page" data-page="/product/info/'+pro.ProductId+'">去开团 &gt;</btn></p>');
			box.append(line1);
			box.append(line2);
			box.append(line3);
			$('ul.cateprolist').append(box);
		}
		setPageTurn();
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(newLogin){
		Pintuan.defaultClick();
		Pintuan.startBanner();
		Pintuan.resize();
		Pintuan.navScroll();
		Pintuan.getproductList();
	}
}