<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 获取优惠券模板 ***

创建 2016-02-20 刘深远

*** ***/

?><style>
body{background:url('/data/file/images/share.jpg');background-size:cover;}
section{width:300px;margin:auto}
p.title{font-size:24px;color:#000;text-align:center;margin-top:50px;}
p.msg{font-size:14px;color:#000;margin-top:40px;text-align:center;}
p.msg a{color:#f4782c}
img.msg{width:140px;height:140px;margin-left:80px;margin-top:30px;display:none;}
div.coupon{background:url('/data/file/images/coupon.png');background-size:cover;width:100%;height:110px;margin-top:40px;position:relative;}
div.coupon p{color:#fff;font-size:24px;text-align:center;margin-top:8px;display:inline-block;width:100%;}
div.coupon p rmb{font-size:20px;}
div.coupon p b{font-size:36px;}
div.coupon p.bt{width:250px;background-color:#fff;color:#e03033;height:20px;line-height:20px;font-size:14px;position:absolute;bottom:16px;left:25px;border-radius:10px;}
p.line{height:1px;width:100%;position:relative;margin-top:36px;}
p.line b{height:10px;border-top:1px #999 solid;width:110px;position:absolute;top:0;}
p.line b.l{left:0;}
p.line b.r{right:0;}
p.line span{display:block;width:80px;text-align:center;height:20px;line-height:20px;position:absolute;left:110px;top:-10px;color:#333;font-size:12px;}

ul.product{margin-top:20px;}
ul.product li div.con a{width:50px;}
</style>
<body>
	<section>
		<!--<p class="title">您已经领取过优惠券</p>-->
		<p class="title">恭喜您成功领取</p>
		<img class="msg" src="/data/file/images/logo.png">
		<div class="coupon"></div>
		<p class="msg">优惠券已经放入您的微信账户 <a href="/space/coupon">查看优惠券</a></p>
		<p class="line"><b class="l"></b><span>推荐商品</span><b class="r"></b></p>
		<ul class="product"></ul>
	</section>
	<script type="text/javascript" src="<?=$staticPath?>/js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>/js/share.js?v=<?=$version?>"></script>
	<script>
		var CouponId = '<?=$couponId?>';
		$(function(){
			Share.init_page();
		});
	</script>
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript">
		var fx_url = getShareUrl('coupon/share/<?=$couponId?>');

		function getImage(){return "http://wx.pingoing.cn/data/file/images/logo.png";}
		function getTitle(){return "拼一下给您发券啦，直接抵现金哦~~";}
		function getDes(){return "手快有，手慢无，抢券购买更优惠！";}

		$(function(){
			
			wx.config({
				debug: false, 
				appId: '<?=$appid?>', // 必填，企业号的唯一标识，此处填写企业号corpid
				timestamp: '<?=$timestamp?>', // 必填，生成签名的时间戳
				nonceStr: '<?=$nonceStr?>', // 必填，生成签名的随机串
				signature: '<?=$signature?>',// 必填，签名，见附录1
				jsApiList: [
					'onMenuShareTimeline',
					'onMenuShareAppMessage'
				] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
			});

			wx.error(function(res){
				//window.location.href=getUrlForReload();
			});

			wx.ready(function(){
				wx.onMenuShareTimeline({
					title: getTitle(), // 分享标题
					link: fx_url, // 分享链接
					imgUrl: getImage(), // 分享图标
					success: function () {
						// 用户确认分享后执行的回调函数
						/*  $.ajax({
						 url: '${pageContext.request.contextPath}/wx/getShareCurrency',
						 data: null,
						 type: 'post',
						 dataType:"json",
						 beforeSend:function(){
						 $(".load-box").removeClass("hide");
						 },
						 success:function(ret){
						 $(".load-box").addClass("hide");
						 },
						 error:function(XMLHttpRequest, textStatus, errorThrown){
						 $(".load-box").addClass("hide");
						 alert(errorThrown);
						 }
						 }); */
						 ajaxLocal('/ajax/shareCoupon/'+CouponId,{},function(json){
							pageTurn('/');
						});
						//location.reload();
					},
					cancel: function () {
						// 用户取消分享后执行的回调函数
						//alert("取消分享");
						//location.reload();
					}
				});

				wx.onMenuShareAppMessage({
					title: getTitle(), // 分享标题
					desc: getDes(), // 分享描述
					link: fx_url, // 分享链接
					imgUrl: getImage(), // 分享图标
					type: '', // 分享类型,music、video或link，不填默认为link
					dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
					success: function () {
						// 用户确认分享后执行的回调函数
						//location.reload();
						ajaxLocal('/ajax/shareCoupon/'+CouponId,{},function(json){
							pageTurn('/');
						});
					},
					cancel: function () {
						// 用户取消分享后执行的回调函数
						//alert("取消分享");
						//location.reload();
					}
				});
			});

		});

	</script>
</body>
