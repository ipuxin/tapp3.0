<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
addbox{padding:20px 0;margin:0 20px;display:block;border-bottom:1px solid #ff4242;position:relative;padding-bottom:36px;}
addbox p{line-height:28px;}
addbox p.btn{display:none}
addbox p b{font-size:15px;font-weight:bolder;}
addbox p i{display:inline-block;height:20px;width:20px;text-align:center;line-height:20px;color:#fff;background-color:#ff4242;border-radius:4px;font-size:20px;margin-right:12px;}
addbox btns{height: 26px;line-height: 16px;font-size: 14px;position:absolute;bottom:4px;width:100%;text-align:right;}
addbox btns div{display:inline-block;}

addnewbox{position:absolute;left:0;top:0;background-color:rgba(0,0,0,0.5);height:100%;width:100%;z-index:100;display:none;}
addnewbox warp{position:absolute;top:50px;left:20px;right:20px;background-color:#fff;border-radius:6px;padding-bottom:10px;}
addnewbox warp i{background-color:#bbb;color:#fff;position:absolute;right:-10px;top:-10px;height:30px;width:30px;text-align:center;line-height:30px;border-radius:50%;}
addnewbox warp tit{line-height:36px;text-align:center;border-bottom:1px solid #ddd;display:block;}
addnewbox warp input{box-sizing:border-box;display:inline-block;float:left;line-height:36px;height:36px;border-bottom:1px solid #ddd;text-indent:12px;}
addnewbox warp select{box-sizing:border-box;display:inline-block;float:left;line-height:36px;height:36px;border-bottom:1px solid #ddd;text-indent:12px;}
addnewbox warp textarea{border:0;width:100%;box-sizing:border-box;padding:12px;min-height:60px;border-bottom:1px solid #ddd;margin-bottom:10px;}
addnewbox warp btn, .guanli{width:160px;height:36px;text-align:center;line-height:36px;margin:auto;background-color:#ff4242;color:#fff;display:block;border-radius:6px;}
</style>
<body>
	<header class="back">
		<tit>我的地址</tit>
		<div class="right">
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_space_more">
		<?if($list)foreach($list as $v){?>
		<addbox id="<?=$v['AddressId']?>">
			<p><b>收货人：</b><?=$v['RealName']?> &nbsp; &nbsp; <b>电话：</b><?=$v['Mobile']?></p><p><b>收货地址：</b><?=$v['RealAddress']?></p>
			<btns data-id="<?=$v['AddressId']?>" data-pcode="<?=$v['ProviceCode']?>" data-ccode="<?=$v['CityCode']?>" data-dcode="<?=$v['DistrictCode']?>" data-name="<?=$v['RealName']?>" data-tel="<?=$v['Mobile']?>" data-address="<?=$v['Address']?>">
				<?if($type == 1){?>
					<div class="rbtn upd">编辑</div>
					<div class="rbtn del g">删除</div>
				<?}else{?>
					<a class="rbtn xuanze" value="<?=$v['AddressId']?>" href="javascript:;">选择地址</a>
				<?}?>
			</btns>
		</addbox>
		<?}?>
		<addnewbox>
			<warp>
			<i>X</i>
			<tit>添加新收获地址</tit>
			<input type="hidden" name="AddressId" id="AddressId" value="">
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
	</section>
	<?if($type == 1){?>
		<footer class="btn">
			<btn>添加地址</btn>
		</footer>
	<?}else{?>
			<footer style="background-color: #ff4242;color: #fff;">
				<a style="font-size: 16px;" href="/space/address/1" class="guanli">管理地址</a>
			</footer>
	<?}?>

	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/citySelect.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		//addressid设置到cookie
		$('.xuanze').click(function(){
			var addressid = $(this).attr('value');
			setCookie("id", addressid);
			history.back(-1);
		})

		function setCookie(name,value)
		{
			var Days = 30;
			var exp = new Date();
			exp.setTime(exp.getTime() + Days*24*60*60*1000);
			document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString()+";path=/";
		}

		//删除
		$('#page_space_more').on('click',' div.del',function(){
			var addid = $(this).parent().data('id');
			var del = $(this);
			showMsg('是否删除地址？','是','否',function(){
				ajaxLocal('/ajax/delAddress',{addressId:addid},function(json){
					if(json && json!=false){
						Msg('删除成功');
						del.parents("addbox").remove();
					}else{
						Msg('删除失败');
					}
				});
			});
		});
		//更新
		$('#page_space_more').on('click', 'div.upd',function(){
			var addid = $(this).parent().data('id');
			var proviceCode = $(this).parent().data('pcode');
			var cityCode = $(this).parent().data('ccode');
			var districtCode = $(this).parent().data('dcode');
			var realName = $(this).parent().data('name');
			var mobile = $(this).parent().data('tel');
			var address = $(this).parent().data('address');
			
			$('#AddressId').val(addid);
			$('#RealName').val(realName);
			$('#Mobile').val(mobile);
			$('#Address').val(address);
			$('#Provice').val(proviceCode);
			CitySelect.getCityList(proviceCode,cityCode);
			CitySelect.getDistrictList(cityCode,districtCode);
			isUpd = 1;
			showAddBox();
		});

		function loginout(){
			pageTurn('/main/out');
		}
		
		var data = {};
		var isUpd = 0;

		$('footer btn').click(function(){showAddBox();});
		$('addnewbox i').click(function(){hideAddBox();});
		$('addnewbox btn').click(function(){
			CitySelect.getAddressSubmit(addBack,isUpd);}
		);
		
		function showAddBox(){$('body').append($('addnewbox'));$('addnewbox').show();CitySelect.addSelectWarpBox();}
		function hideAddBox(){
			isUpd = 0;
			$('#AddressId').val();
			$('#RealName').val('');
			$('#Mobile').val('');
			$('#Address').val('');
			$('#Provice').val(0);
			$('#City').html('<option value="0">--选择城市--</option>');
			$('#District').html('<option value="0">--选择区域--</option>');
			$('addnewbox').hide();
		}
		function addBack(json){
			var line1 = $('<p><b>收货人：</b>'+json.RealName+' &nbsp; &nbsp; <b>电话：</b>'+json.Mobile+'</p>');
			var line2 = $('<p><b>收货地址：</b>'+json.RealAddress+'</p>');
			var btns  = $('<btns data-id="'+json.AddressId+'" data-pcode="'+json.ProviceCode+'" data-ccode="'+json.CityCode+'" data-dcode="'+json.DistrictCode+'" data-name="'+json.RealName+'" data-tel="'+json.Mobile+'" data-address="'+json.Address+'"><div class="rbtn upd">编辑</div> <div class="rbtn del g">删除</div></btn>');
			var box   = $('<addbox id="'+json.AddressId+'"></addbox>');
			$(box).append(line1);
			$(box).append(line2);
			$(box).append(btns);
			if(json.type === 'upd'){
				$('#'+json.AddressId).remove();
				Msg('编辑成功');

			}else{
				Msg('添加成功');
			}
			$('section').prepend(box);
			$('addnewbox').hide();
			hideAddBox();
		}

		CitySelect.init_page();
	</script>