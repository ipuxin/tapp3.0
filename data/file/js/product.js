
var Product = {

	defaultClick:function(){

	},

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
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(){
		Product.defaultClick();
		Product.startBanner();
	}
}