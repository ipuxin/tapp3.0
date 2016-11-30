<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
addbox{padding:20px 0;margin:0 20px;display:block;border-bottom:1px solid #ff4242}
addbox p{line-height:28px;width: 280px;height: auto;}
addbox p.btn{display:none}
addbox p b{font-size:15px;font-weight:bolder;}
p.btn i{display:inline-block;height:20px;width:20px;text-align:center;line-height:20px;color:#fff;background-color:#ff4242;border-radius:4px;font-size:20px;margin-right:12px;}

addnewbox{position:absolute;left:0;top:0;background-color:rgba(0,0,0,0.5);height:100%;width:100%;z-index:100;display:none;}
addnewbox warp{position:absolute;top:50px;left:20px;right:20px;background-color:#fff;border-radius:6px;padding-bottom:10px;}
addnewbox warp i{background-color:#bbb;color:#fff;position:absolute;right:-10px;top:-10px;height:30px;width:30px;text-align:center;line-height:30px;border-radius:50%;}
addnewbox warp tit{line-height:36px;text-align:center;border-bottom:1px solid #ddd;display:block;}
addnewbox warp input{box-sizing:border-box;display:inline-block;float:left;line-height:36px;height:36px;border-bottom:1px solid #ddd;text-indent:12px;}
addnewbox warp select{box-sizing:border-box;display:inline-block;float:left;line-height:36px;height:36px;border-bottom:1px solid #ddd;text-indent:12px;}
addnewbox warp textarea{border:0;width:100%;box-sizing:border-box;padding:12px;min-height:60px;border-bottom:1px solid #ddd;margin-bottom:10px;}
addnewbox warp btn{width:160px;height:36px;text-align:center;line-height:36px;margin:auto;background-color:#ff4242;color:#fff;display:block;border-radius:6px;}
i.icon_right{width: 10px;height: 20px;display: block;background-size:100% 100%;position: absolute;top:90px;right: 30px;}
.box{width: auto;height:auto;display: block;}
</style>
	<addbox>
		<p class="btn"><i>+</i>手动添加收货地址</p>
	</addbox>
	<addnewbox>
		<warp>
		<i>X</i>
		<tit>添加新收获地址</tit>
		<input style="width:50%;border-right:1px solid #ddd" type="text" id="RealName" value="" placeholder="姓名">
		<input style="width:50%;" type="text" id="Mobile" value="" placeholder="手机号">
		<select id="Provice"><option value="0">--选择省份--</option><option value="110000">北京市</option><option value="120000">天津市</option><option value="130000">河北省</option><option value="140000">山西省</option><option value="150000">内蒙古自治区</option><option value="210000">辽宁省</option><option value="220000">吉林省</option><option value="230000">黑龙江省</option><option value="310000">上海市</option><option value="320000">江苏省</option><option value="330000">浙江省</option><option value="340000">安徽省</option><option value="350000">福建省</option><option value="360000">江西省</option><option value="370000">山东省</option><option value="410000">河南省</option><option value="420000">湖北省</option><option value="430000">湖南省</option><option value="440000">广东省</option><option value="450000">广西壮族自治区</option><option value="460000">海南省</option><option value="500000">重庆市</option><option value="510000">四川省</option><option value="520000">贵州省</option><option value="530000">云南省</option><option value="540000">西藏自治区</option><option value="610000">陕西省</option><option value="620000">甘肃省</option><option value="630000">青海省</option><option value="640000">宁夏回族自治区</option><option value="650000">新疆维吾尔自治区</option><option value="710000">台湾省</option><option value="810000">香港特别行政区</option><option value="820000">澳门特别行政区</option></select>
		<select id="City">
			<option value="0">--选择城市--</option>
		</select>
		<select id="District">
			<option value="0">--选择区域--</option>
		</select>
		<textarea id="Address" value="" placeholder="请输入您的详细地址"></textarea>
		<btn>保 存</btn>
		</warp>
	</addnewbox>
	<script type="text/javascript" src="<?=$staticPath?>js/citySelect.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var data = {};
		//从cookie获得addressid
		var id = getCookie('id');
		if(id){
			data.AddressId = id;
			url = '/ajax/getUserChooseAddress';
		}else{
			data.CityCode = '<?=$addressBox["cityCode"]?>';
			url = '/ajax/getUserAddress/first';
		}
		function getCookie(name)
		{
			var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");

			if(arr=document.cookie.match(reg))

				return unescape(arr[2]);
			else
				return null;
		}


		ajaxLocal(url,data,function(json){
			if(json && json!=false){
				var line1 = $('<p><b>收货人：</b>'+json.RealName+' &nbsp; &nbsp; <b>电话：</b>'+json.Mobile+'</p>');
				var line2 = $('<p><b>收货地址：</b>'+json.RealAddress+'</p><i class="icon_right"></i>');
				var box   = $('<a href="/space/address/<?=$pro['ProductId']?>" class="box"></a>');
				$(box).append(line1);
				$(box).append(line2);
				$('addbox').append(box);
				$('addbox').data('addid',json.AddressId);
			}else{
				$('addbox p.btn').show();
			}
		});

		$('p.btn').click(function(){showAddBox();resetAddBox();CitySelect.addSelectWarpBox('<?=$addressBox["cityCode"]?>');});
		$('addnewbox i').click(function(){hideAddBox();});
		$('addnewbox btn').click(function(){CitySelect.getAddressSubmit(addBack);});
		
		function showAddBox(){$('body').append($('addnewbox'));$('addnewbox').show();}
		function hideAddBox(){$('addnewbox').hide();}
		function addBack(json){
			var line1 = $('<p><b>收货人：</b>'+json.RealName+' &nbsp; &nbsp; <b>电话：</b>'+json.Mobile+'</p>');
			var line2 = $('<p><b>收货地址：</b>'+json.RealAddress+'</p><i class="icon_right"></i>');
			var box   = $('<a href="/space/address/<?=$pro["ProductId"]?>" class="box"></a>');
			$(box).append(line1);
			$(box).append(line2);
			$('addbox').append(box);
			$('addbox').data('addid',json.AddressId);
			$(box).siblings('.box').remove();

			hideAddBox();
			$('addbox p.btn').hide();
		}

		CitySelect.init_page();
		
		var AddBoxReset = 0;
		function resetAddBox(){
			if(AddBoxReset==0){
				AddBoxReset = 1;
				<?if($addressBox['cityCode']){$addressBox['ProviceCode']=substr($addressBox['cityCode'],0,3).'000';?>
				$('#Provice').val(<?=$addressBox['ProviceCode']?>);
				$('#Provice').prop('disabled',true);
				$('#City').html('');
				CitySelect.getCityList(<?=$addressBox['ProviceCode']?>,<?=$addressBox['cityCode']?>);
				CitySelect.getDistrictList(<?=$addressBox['cityCode']?>);
				<?}?>
			}
		}
	</script>