
function wechatPayFinish(type,orderid){
	var OrderId = Product.getOrderId();
	if(!(type=='fail')){
		pageTurn('/order/orderinfo/'+OrderId);
	}else{
		pageTurn('/order/orderinfo/'+OrderId);
	}
}

var Product = {

	btnSub : 0,
	OrderId : '',

	getOrderId:function(){
		return Product.OrderId;
	},

	defaultClick:function(){
		$('footer btn').click(function(){
			var data = {};
			data.AddressId = $('addbox').data('addid');
			data.Remark = $('#remark').val();
			
			if(!data.AddressId){Msg('请先添加地址');return;}

			if(Product.btnSub==0){
				Product.btnSub = 1;
				ajaxLocal('/order/creatOrderByCart',data,function(json){
					if(!json || json==false){Msg('订单创建失败');Product.btnSub=0;return;}
					if(json.Code>0){Msg(json.Message);Product.btnSub=0;return;}
					if(json.ErrorCode>0){Msg(json.ErrorMessage);Product.btnSub=0;return;}
					if(json.Order && json.Order.OrderId){
						Product.OrderId = json.Order.OrderId;
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
									pageTurn('/order/orderinfo/'+Product.OrderId);
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