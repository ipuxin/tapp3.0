<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*** 登陆页模板 ***

创建 2016-04-05 刘深远

*** ***/

?><style>
body{background-color:#fbfaef}
section{text-align:center;}
section img{width:100px;height:100px;margin:110px auto 0px;display:block;}
</style>
<body>
	<header class="normal"><tit><i class="icon back"></i>登陆</tit></header>
	<section>
		<img src="<?=$staticPath?>images/wechat_login.png"><br>使用微信登陆
	</section>
	<script type="text/javascript" src="<?=$staticPath?>js/base.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>

	<script>
		function loginWechat(){
			if(IS_IOS){
				var url="loginWechat:";
				document.location = url; 
			}else{
				android.loginWechat();
			}
		}

		function loginWechatReback(code){
			ajaxLocal('/ajax/getUserInfo',{Code:code},function(json){
				if(json.ErrorCode==0){
					document.location = '/?version=1.2&newlogin=1&devicetype=ios';
				}
			});
		}
	</script>

	<script>
		$(function(){
			$('img').click(function(){
				loginWechat();
			});
		});
		
		// loginWechatReback();
		// shareWechat();
		// shareWechatReback();
	</script>
</body>
</html>
