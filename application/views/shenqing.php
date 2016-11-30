<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
</style>
<body>
	<script type="text/javascript">
		<?if($Success){?>
			alert("申请成功！敬请耐心等待我们的审核结果！");
		<?}else{?>
			alert("申请失败！");
		<?}?>
		window.location.href="http://www.pingoing.cn";
	</script>
