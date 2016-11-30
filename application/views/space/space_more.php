<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
ul.list{padding:40px 30px;}
ul.list li{height:40px;line-height:40px;border-bottom:1px solid #ddd;position:relative;margin-left:38px;margin-bottom:10px;}
ul.list li span{float:right;color:#bbb;}
ul.list li i{height:26px;width:26px;border:1px solid #ff4242;border-radius:4px;left:-38px;bottom:0;}
</style>
<body>
	<header class="back">
		<tit>设置</tit>
		<div class="right">
			<span class="more dot"></span>
		</div>
	</header>
	<section id="page_space_more">
		<ul class="list">
			<li>
				<i class="icon nav_order_shouhou"></i>个人资料<span>></span>
			</li>
			<li>
				<i class="icon nav_order_shouhou"></i>账户安全<span>></span>
			</li>
			<li>
				<i class="icon nav_order_shouhou"></i>消息通知<span>></span>
			</li>
			<li>
				<i class="icon nav_order_shouhou"></i>通 &nbsp; &nbsp; &nbsp; 用<span>></span>
			</li>
			<li>
				<i class="icon nav_order_shouhou"></i>关于软件<span>></span>
			</li>
		</ul>
	</section>
	<footer>
		<btn>退出当前账户</btn>
	</footer>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		$('footer btn').click(function(){ 
			showMsg('是否需要退出登录？','是','否',function(){loginout();});
		});

		function loginout(){
			pageTurn('/main/out');
		}
	</script>