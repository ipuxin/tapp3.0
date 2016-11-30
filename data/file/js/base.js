//全局点击对象
var clickObj;

var IS_IOS = 0;
if(navigator.userAgent.indexOf("iPhone")!=-1){IS_IOS = 1;}
if(navigator.userAgent.indexOf("iPad")!=-1){IS_IOS = 1;}

//移动端解决300ms延迟 fastclick插件方法
$(function(){FastClick.attach(document.body);});

function ajaxLocal(urlPage,data,success){
	showLoading();
	return $.ajax({
		url : urlPage,  
		dataType : 'json',
		type : 'POST',  
		data : data,
		async : true,
		timeout : 30000,
		success : function(data){
			hideLoading();
			success(data);
		},
		complete: function(data){
			//alert(data);
			hideLoading();
		},
		error :function(){
			//alert("error");
		},
	});
}

function Msg(sContent,sType){// type 1:成功 2:提醒 3:错误
	$('#info').remove();
	var sTime = 3000;
	var tips_info ='<div id="info"></div>';
	var tips_type;
	var randId = Math.ceil(Math.random()*100000);
	var objClass = 'tipBox_'+randId;
	$('body').append(tips_info);
	$('#info').addClass(objClass);
	$('#info').html(sContent);
	
	setTimeout(function(){$("."+objClass).addClass('show')},10);
	setTimeout(function(){$("."+objClass).removeClass('show')},sTime-400);
	setTimeout(function(){$("."+objClass).remove()},sTime);
}

function checkTel(tel){
	var reg = /^0?1[3|4|5|7|8][0-9]\d{8}$/;
	if(reg.test(tel)){return true;}else{return false;}
}

function showMsg(msg,yes,no,func){
	var box = $('<msgbox></msgbox>');
	var box_in = $('<msg></msg>');
	var p = $('<p>'+msg+'</p>');
	var btns = $('<btns></btns>');
	if(yes)btns.append('<span class="yes">'+yes+'</span>');
	if(no)btns.append('<span class="no">'+no+'</span>');
	box_in.append(p);
	box_in.append(btns);
	box.append(box_in);
	$('body').append(box);
	
	setTimeout(function(){
		box.css('background','rgba(0,0,0,0.7)');
		box_in.css('transform','translateY(200px)');
	},10)

	btns.find('span').click(function(){
		$('msgbox').remove();
		if($(this).hasClass('yes') && func){func();}
	});
}

var loadingTimer;

//加载loadIng背景
function showLoading(){
	hideLoading();
	loadingTimer = setTimeout(function(){
		var loadIngBox = '<div id="loaderWarp"><div class="bg"></div><div class="ball-triangle-path"><div></div><div></div><div></div></div></div>';
		$('body').append(loadIngBox);
		$('#loaderWarp .ball-triangle-path').css("top",$(window).height()/2+'px');
		$('#loaderWarp .ball-triangle-path').css("left",$(window).width()/2+'px');
	},1);
}

function hideLoading(){clearTimeout(loadingTimer);$('#loaderWarp').remove();}

function pageTurn(url){window.location.href = url;}

function trim(str){return str.replace(/\s/g,"");}