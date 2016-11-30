function wechatPayFinish(type,orderid){
	var TeamId = Product.getTeamId();
	var OrderId = Product.getOrderId();
	if(!(type=='fail')){
		if(TeamId){
			pageTurn('/team/info/'+TeamId);
		}else{
			pageTurn('/order/orderinfo/'+OrderId);
		}
	}else{
		pageTurn('/order/orderinfo/'+OrderId);
	}
}

var Product = {

	btnSub : 0,
	OrderId : '',
	TeamId : '',

	getOrderId:function(){
		return Product.OrderId;
	},

	getTeamId:function(){
		return Product.TeamId;
	},

	defaultClick:function(){
		$('orderset span btn').click(function(){
			var isdel = $(this).hasClass('del');
			var price = parseInt($(this).parent().data('price')*100);
			var kuaidi = parseInt($(this).parent().data('kuaidi')*100);
			var num = parseInt($(this).parent().find('num').html());
			if(isdel){num-=1;}else{num+=1;}
			if(num<1){num=1;}
			
			price = ((num*price+kuaidi)/100);
			$(this).parent().find('num').html(num);
			$('tongji r').eq(0).html(num);
			$('tongji r').eq(1).html('¥'+price);

			ProductNum = num;
		});

		$('footer btn').click(function(){
			var data = {};
			var ProductType = $("input:hidden[name='ProductType']").val();
			data.ProductId = ProductId;
			data.ProductNum = ProductNum;
			data.AddressId = $('addbox').data('addid');
			data.Remark = $('#remark').val();

			if(!data.AddressId){Msg('请先添加地址');return;}
			
			if(Product.btnSub==0){
				Product.btnSub = 1;
				if(ProductType == 4){
					url = '/order/DuobaocreatOrderTuanCreat'
				}else{
					url = '/order/creatOrderTuanCreat'
				}
				ajaxLocal(url,data,function(json){
					if(!json || json==false){Msg('订单创建失败');Product.btnSub=0;return;}
					if(json.Code>0){Msg(json.Message);Product.btnSub=0;return;}
					if(json.ErrorCode>0){Msg(json.ErrorMessage);Product.btnSub=0;return;}
					if(json.Order && json.Order.OrderId){
						Product.OrderId = json.Order.OrderId;
						Product.TeamId = json.Order.TeamId;
						Msg('订单创建成功');
						/*setTimeout(function(){
							pageTurn('/order/orderlist/1');
						},1000);*/
						/*ajaxLocal('/wxpay/orderSubmit',{OrderId:json.Order.OrderId},function(json){
							if(json.ErrorCode==0){
								var JsonCode = eval('('+json.JsonCode+')');
								callpay(JsonCode);
							}else{
								if(json.orderPayed==1){
									if(Product.TeamId){
										pageTurn('/team/info/'+Product.TeamId);
									}else{
										pageTurn('/order/orderinfo/'+Product.OrderId);
									}
								}
								Msg(json.ErrorMsg,2);
							}
						});*/
						ajaxLocal('/ajax/prepayWechat',{OrderId:json.Order.OrderId},function(json){
							if(json.result=='Success'){
								if(json.json.result_code=='FAIL'){Msg(json.json.err_code_des);return;}
								var jsonP = json.json.PayRequest;
								console.log(jsonP);
								jsonP = JSON.stringify(jsonP);
								if(IS_IOS==0){
									android.wechatPay(jsonP);
								}else{
									document.location = "wechatPay:"+jsonP;
								}
							}else{
								Msg(json.msg,2);
							}
						});
					}else{
						Msg('未知错误');Product.btnSub=0;return;
					}
				});
			}
		});
	},

	/**
	* @ 页面初始化
	*/		
	init_page:function(){
		Product.defaultClick();
	}
}