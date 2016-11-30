
var Index = {

	defaultClick:function(){
		Index.resetNavWidth();
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
				Index.resetNavWidth();
			}
		});
	},

	resize:function(){
		$(window).resize(function(){
			Index.resetNavWidth();
		});
	},

	getRealCity:function(){
		navigator.geolocation.getCurrentPosition(function(position){
			var lon = position.coords.longitude;
			var lat = position.coords.latitude;
			//lon = 121.51545505005885;
			//lat = 31.29889802239115;
			//alert(lon);
			ajaxLocal('/city/getLocMsg',{lng:lon,lat:lat},function(json){
				if(json){
					if(json.Code==0){
						var CityInfo = json.Result;
						ajaxLocal('/city/saveCityInfo/1',CityInfo,function(json){});
						if(CityInfo.CityName.indexOf(CityInfo.ProvinceName)>=0){
							CityInfo.CityName = CityInfo.ProvinceName;
						}
						$('header span.city').html(CityInfo.CityName);
					}else{
						pageTurn('/city/cityList');
					}
				}else{
					pageTurn('/city/cityList');
				}
			});
		},Index.getPositionError,Index.positionOption);
	},

	getPositionError:function(){
		pageTurn('/city/cityList');
	},

	positionOption:{
		enableHighAccuracy: true,
		maximumAge: 30000,
		timeout: 20
	},

	checkCityIsReal:function(){
		navigator.geolocation.getCurrentPosition(function(position){
			var lon = position.coords.longitude;
			var lat = position.coords.latitude;
			//lon = 121.51545505005885;
			//lat = 31.29889802239115;
			ajaxLocal('/city/getLocMsg',{lng:lon,lat:lat},function(json){
				if(json.Code==0){
					var CityInfo = json.Result;
					ajaxLocal('/city/saveCityInfo/2',CityInfo,function(json){
						if(json.Code==2){
							var city = json.City;
							showMsg('当前所在城市是'+city.CityName+'哦<br>是否切换','是','否',function(){Index.changeCity(city)});
						}
					});
				}
			});
		});
	},

	changeCity:function(city){
		ajaxLocal('/city/saveCityInfo/',city,function(json){
			pageTurn('/');
		});
	},

	//获取首页商品列表
	getproductList:function(){
		ajaxLocal('/ajax/getProductListIndex/',{},function(json){
			if(json && json!=null){
				if(json.Count>0){Index.printProduct(json.List);}
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
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(newLogin){
		Index.defaultClick();
		Index.startBanner();
		Index.resize();
		Index.navScroll();
		if(!PYX_CityName){
			Index.getRealCity();
		}
		if(newLogin && PYX_CityName){
			Index.checkCityIsReal();
		}
		Index.getproductList();
	}
}