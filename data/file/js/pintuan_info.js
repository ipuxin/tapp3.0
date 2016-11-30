
var Pintuan = {

	defaultClick:function(){
		var h = $('teambox ul').height();
		$('teambox').css('height',h+76);
		$('teambox').css('padding-top',h-13);
	},
	
	daojishi:function(EndTime){
		var NowTime =new Date().getTime();
		EndTime = parseInt(EndTime);
		NowTime = parseInt(NowTime/1000);
		var time = EndTime - NowTime;


		if(time>0){
			$('teambox p.djs').show();
			var d = parseInt(time/(3600*24));
			var h = parseInt(time/3600%24);
			var m = parseInt((time%3600)/60);
			var s = parseInt(time%60);
			h = h < 10 ? "0"+h : h;
			m = m < 10 ? "0"+m : m;
			s = s < 10 ? "0"+s : s;
			if(d){
				$('teambox p.djs num').eq(0).html(d);
				$('teambox p.djs days').show();
				$('teambox p.djs num').eq(0).show();
			}else{
				$('teambox p.djs days').hide();
				$('teambox p.djs num').eq(0).hide();
			}
			$('teambox p.djs num').eq(1).html(h);
			$('teambox p.djs num').eq(2).html(m);
			$('teambox p.djs num').eq(3).html(s);
			setTimeout(function(){Pintuan.daojishi(EndTime)},1000);
		}else{
			$('teambox p.djs').hide();
		}
	},
		
	/**
	* @ 页面初始化
	*/		
	init_page:function(){
		Pintuan.defaultClick();
	}
}