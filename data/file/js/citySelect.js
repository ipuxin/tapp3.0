
var CitySelect = {

	defaultClick:function(){
		$('#Provice').change(function(){
			var val = $(this).val();
			CitySelect.getCityList(val);
			CitySelect.resetDistrict();
		});

		$('#City').change(function(){
			var val = $(this).val();
			CitySelect.getDistrictList(val);
		});

		/*if(proviceCode){
			$('#Provice').val(proviceCode);
			$('#City').html('');
			CitySelect.getCityList(proviceCode,cityCode);
		}

		if(cityCode){
			$('#Provice').val(proviceCode);
			$('#City').html('');
			CitySelect.getDistrictList(cityCode,districtCode);
		}*/
	},

	getCityList:function(code,city){
		var CityCode = city;
		ajaxLocal('/ajax/getCityList/'+code,{},function(json){
			if(json && json.Result){
				$('#City').html('');
				$('#City').append('<option value="0">--选择城市--</option>');
				for(var i in json.Result){
					var pri = json.Result[i];
					var sel = '';
					if(CityCode && CityCode==pri.Code){sel = 'selected="selected"';}
					var opt = '<option value="'+pri.Code+'" '+sel+'>'+pri.Name+'</option>';
					$('#City').append(opt);
				}
			}
		});
	},

	getDistrictList:function(code,district){
		var DistrictCode = district;
		ajaxLocal('/ajax/getDistrictList/'+code,{},function(json){
			if(json && json.Result && json.Result.length){
				$('#District').html('');
				$('#District').append('<option value="0">--选择区域--</option>');
				for(var i in json.Result){
					var pri = json.Result[i];
					var sel = '';
					if(DistrictCode && DistrictCode==pri.Code){sel = 'selected="selected"';}
					var opt = '<option value="'+pri.Code+'" '+sel+'>'+pri.Name+'</option>';
					$('#District').append(opt);
				}
			}else{
				if(json.Result.length==0){
					var cityOpt = $('#City').find("option:selected");
					$('#District').html('');
					$('#District').append('<option value="'+cityOpt.val()+'">'+cityOpt.text()+'</option>');
				}
			}
		});
	},

	getAddressSubmit:function(back,upd){
		var RealName = $('#RealName').val();
		var Mobile = $('#Mobile').val();
		var ProviceCode = $('#Provice').val();
		var ProviceName = $('#Provice').find("option:selected").text();
		var CityCode = $('#City').val();
		var CityName = $('#City').find("option:selected").text();
		var DistrictCode = $('#District').val();
		var DistrictName = $('#District').find("option:selected").text();
		if(DistrictName==CityName){DistrictName='';}
		var Address = $('#Address').val();
		var Type = $('input[name=Type]:checked').val();
		var AddressId = $('#AddressId').val();
		if(!RealName){Msg('姓名不能为空');return;}
		if(!Mobile){Msg('手机不能为空');return;}
		if(!checkTel(Mobile)){Msg('手机格式不正确');return;}
		if(ProviceCode==0){Msg('请选择所在省份');return;}
		if(CityCode==0){Msg('请选择所在城市');return;}
		if(DistrictCode==0){Msg('请选择所在区域');return;}
		if(!Address){Msg('地址不能为空');return;}
		
		if(upd==1){
			ajaxLocal('/ajax/Address/upd',{RealName:RealName,Mobile:Mobile,ProviceCode:ProviceCode,ProviceName:ProviceName,CityCode:CityCode,CityName:CityName,DistrictCode:DistrictCode,DistrictName:DistrictName,Address:Address,Type:Type,AddressId:AddressId},function(json){
				back(json);
			});
		}else{
			ajaxLocal('/ajax/Address/add',{RealName:RealName,Mobile:Mobile,ProviceCode:ProviceCode,ProviceName:ProviceName,CityCode:CityCode,CityName:CityName,DistrictCode:DistrictCode,DistrictName:DistrictName,Address:Address,Type:Type},function(json){
				back(json);
			});
		}
	},

	resetDistrict:function(){
		$('#District').html('');
		$('#District').append('<option value="0">--选择区域--</option>');
	},

	resetSelect:function(citycode){
		/*$('addnewbox select').on('touchstart',function(){
		//$('addnewbox select').on('mouseup',function(){
			$(this).hide();
			CitySelect.showSelectBackground($(this));
		});*/

		$('selectDanban').click(function(){
			var selBox = $(this).prev();
			CitySelect.showSelectBackground(selBox);
		});

		//if(citycode){
		//	$('#Provice').next('selectDanban').unbind('click');
		//	$('#City').next('selectDanban').unbind('click');
		//	$('#Provice').next('selectDanban').click(function(){
		//		Msg('当前不可切换省份');
		//	});
		//	$('#City').next('selectDanban').click(function(){
		//		Msg('当前不可切换城市');
		//	});
		//}
	},

	addSelectWarpBox:function(citycode){
		if(!$('selectDanban').length){
		$('addnewbox select').each(function(){
			var top = $(this).position().top;
			var left = $(this).position().left;
			var width = $(this).width();
			var height = $(this).height();
			var danban = $('<selectDanban></selectDanban>');
			danban.css('top',top);
			danban.css('left',left);
			danban.css('height',height);
			danban.css('width',width);
			$(this).after(danban);
		});

		CitySelect.resetSelect(citycode);
		}
	},

	showSelectBackground:function(obj){
		var selectBg = $('<selectBg><selectWarp></selectWarp></selectBg>');
		$('body').append(selectBg);
		CitySelect.printSelectBox(obj);
	},

	printSelectBox:function(obj){
		obj.find('option').each(function(){
			var selectbox = $('<selectbox data-value="'+$(this).attr('value')+'">'+$(this).text()+'</selectbox>');
			$('selectWarp').append(selectbox);
		});

		$('selectWarp selectbox').click(function(){
			var value = $(this).data('value');
			var selid = obj.attr('id');
			console.log(selid);
			$('selectBg').remove();
			obj.show();
			obj.val(value);
			
			if(selid=='Provice'){
				CitySelect.getCityList(value);
				CitySelect.resetDistrict();
			}
			if(selid=='City'){CitySelect.getDistrictList(value);}
		});
	},
		
	/**
	* @ 页面初始化
	*/
	init_page:function(type){
		CitySelect.defaultClick();
		CitySelect.resetSelect();
	}
}