	<style>
	footer div i.icon{background-size:28px;}
	</style>
	<?
		if(!$navSel)$navSel = 1;
		$selFooterNav[$navSel] = 'sel';
		$selFooterStyle[$navSel] = 'color:#ff4242';
	?>
	<footer>
		<div class="page" data-page="/" style="<?=$selFooterStyle[1]?>"><i class="icon icon_home <?=$selFooterNav[1]?>"></i>首页</div>
		<div class="page" data-page="/main/fenlei" style="<?=$selFooterStyle[2]?>"><i class="icon icon_cate <?=$selFooterNav[2]?>"></i>分类</div>
		<div class="page" data-page="/cart" style="<?=$selFooterStyle[3]?>"><i class="icon icon_cart <?=$selFooterNav[3]?>"></i>购物车</div>
		<div class="page" data-page="/space" style="<?=$selFooterStyle[4]?>"><i class="icon icon_space <?=$selFooterNav[4]?>"></i>个人中心</div>
	</footer>