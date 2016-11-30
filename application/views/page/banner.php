		<banner>
			<link rel="stylesheet" href="<?=$staticPath?>css/swiper.css" type="text/css" />
			<script type="text/javascript" src="<?=$staticPath?>js/idangerous.swiper-2.1.min.js"></script>
			<div class="sliders">
				<div class="swiper-container">
					<div class="swiper-wrapper">
						<?if($bannerImageList){foreach($bannerImageList as $v){?>
						<div class="swiper-slide"><a href="javascript:;" style="background-image:url('<?=$v?>')"></a></div>
						<?}}else{?>
						<div class="swiper-slide"><a href="javascript:;" style="background-image:url('<?=$staticPath?>images/banner_1.png')"></a></div>
						<div class="swiper-slide"><a href="javascript:;" style="background-image:url('<?=$staticPath?>images/banner_2.jpg')"></a></div>
						<?}?>
					</div>
				</div>
				<div class="pagination"></div>
			</div>
			<style>
			.sliders,.swiper-wrapper,.swiper-slide,.swiper-container,.swiper-slide a{height:<?=$bannerHeight?>px;}
			</style>
		</banner>