
var City = {

	defaultClick:function(){
		$('input').bind('input',function(){
			var text = $(this).val();
			if(text && text!=''){
				City.ShowSearch();
				City.searchCity(text);
			}else{
				City.HideSearch();
			}
		});
	},

	ShowSearch:function(){
		$('aside').hide();
		$('cityBox').eq(0).hide();
		$('cityBox').eq(1).hide();
		$('cityBox').eq(2).show();
	},

	HideSearch:function(){
		$('aside').show();
		$('cityBox').eq(0).show();
		$('cityBox').eq(1).show();
		$('cityBox').eq(2).hide();
	},

	searchCity:function(text){
		var text = text;
		$('cityBox').eq(2).find('city').remove();
		$('cityBox').eq(1).find('city').each(function(){
			var str = $(this).html();
			if(str.indexOf(text)>=0){
				$(this).show();
				$('cityBox').eq(2).append($(this).clone(true));
			}
		});
	},

	defaultBtns:function(){
		var w = $('section').width();
		w = (w-44)/3;
		$('div.box span').css('width',w);
	},

	getHotCityList:function(){
		ajaxLocal('/ajax/getHotCitys',{},function(json){
			if(json && json!=null){
				if(json.List)City.printHotCityList(json.List);
			}else{
				Msg('热门城市获取失败');
			}
		});
	},

	getCityList:function(){
		ajaxLocal('/city/getCityListByLetter',{},function(json){
			if(json && json!=null){
				City.printCityList(json.Result);
			}else{
				Msg('城市列表获取失败');
			}
		});
	},

	printHotCityList:function(list){
		var tit = $('#rm');
		var moren = $('cityBox').eq(0).find('city').eq(1);
		moren.remove('');
		for(var i in list){
			var city = list[i];
			var li = '<city data-citycode="'+city.CityCode+'" data-cityname="'+city.CityName+'" data-provincecode="'+city.ProvinceCode+'" data-provincename="'+city.ProvinceName+'">'+city.CityName+'</city>';
			tit.after(li);
		}
		City.defaultBtns();
		City.listClick();
	},

	printCityList:function(list){
		var Letter = '';
		var warp = $('cityBox').eq(1);
		for(var i in list){
			var city = list[i];
			if(Letter!=city.Letter){
				var li = '<t id="'+city.Letter+'">'+city.Letter+'</t>';
				warp.append(li);
				var a = '<a data-v="'+city.Letter+'">'+city.Letter+'</a>';
				$('aside').append(a);
				Letter = city.Letter;
			}
			if(city.CityName=='上海市郊县'){console.log(city);continue;}
			if(city.CityName=='天津市郊县'){console.log(city);continue;}
			if(city.CityName=='重庆市郊县'){console.log(city);continue;}
			var CityName = city.CityName;
			CityName = CityName.replace('市辖区','');
			var li = '<city data-citycode="'+city.CityCode+'" data-cityname="'+CityName+'" data-provincecode="'+city.ProvinceId+'" data-provincename="'+city.ProvinceName+'" class="b">'+CityName+'</city>';
			warp.append(li);
		}
		City.listClick();
		City.asideClick();
	},

	listClick:function(){
		$('city').unbind('click');
		$('city').click(function(){
			var citycode = $(this).data('citycode');
			var cityname = $(this).data('cityname');
			var provincecode = $(this).data('provincecode');
			var provincename = $(this).data('provincename');
			if(!citycode)return;
			var CityInfo = {CityCode:citycode,CityName:cityname,ProvinceCode:provincecode,ProvinceName:provincename};
			ajaxLocal('/city/saveCityInfo',CityInfo,function(json){
				pageTurn('/');
			});
		});
	},

	asideClick:function(){
		$('aside a').click(function(){
			var id = $(this).data('v');
			var top = $('#'+id).position().top;
			var box = $('cityBox').eq(0).height();
			if(id=='dw' || id=='rm'){top+=60;}else{top+=box+60;}
			$('section').scrollTop(top);
		});
	},
		
	/**
	* @ 页面初始化
	*/
	init_page:function(type){
		City.defaultClick();
		City.getHotCityList();
		City.getCityList();
		$(window).resize(function(){
			City.defaultBtns();
		});
	}
}