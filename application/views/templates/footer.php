	<script type="text/javascript">
		/* 头部加载返回图标 */
		$('header.back').append('<i class="icon back"></i>');
		$('header.back i.back').click(function(){
			var page = $('header.back').data('page');
			if(page){
				pageTurn(page);
			}else{
				history.go(-1);
			}
		});

		/* 鼠标滚动隐藏头部 */
		$('section').scroll(function(){
			var top = $(this).scrollTop();
			var opacity = top/400;
			if(top<=300){
				$('header').css('opacity',1-opacity);
			}else{
				$('header').css('opacity',0.2);
			}
		});
		
		/* 底部菜单弹出效果 */
		//$('footer').css('height',20);
		//$('footer div').css('transform','scale(0.6) rotate(20deg)');
		//setTimeout(function(){
			//$('footer').css('height',60);
			//$('footer div').css('transform','scale(1) rotate(0)');
		//},10);

		$('shareBox ul li.wechat').click(function(){
			shareWechat(2);
		});

		$('shareBox ul li.timeline').click(function(){
			shareWechat(1);
		});

		function shareWechat(TypeCode){
			if(TypeCode==2){
				var Type = 'WXSceneSession';
			}else{
				var Type = 'WXSceneTimeline';
			}
			var data = {
				Type:Type,
				Title:getShareTitle(),
				Des:getShareDes(),
				Image:getShareImage(),
				Url:getShareUrl()
			};
			data = JSON.stringify(data);
			if(IS_IOS){
				document.location = "shareWechat:"+data;
			}else{
				android.shareWechat(data);
			}
		}

		function shareWechatReback(code){
			//alert(code);
		}
	</script>
	</body>
</html>