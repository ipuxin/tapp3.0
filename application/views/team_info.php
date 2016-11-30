<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
probox{margin:0 20px 30px;padding:50px 0 20px 0;display:block;padding-left:110px;position:relative;height:100px;}
probox shopname{display:block;position:absolute;left:0;top:15px;font-weight:bolder;font-size:15px;}
probox img{width:100px;height:100px;position:absolute;left:0;bottom:20px;}
probox tit{line-height:24px;font-size:16px;height:72px;display:block;overflow:hidden;margin-bottom:8px;}
probox p price{display:inline-block;font-size:16px;color:#ff4242}
probox p price r{font-weight:bolder;margin-right:16px;}

teambox{position:relative;margin:0 20px 24px;display:block;border-radius:6px;border:1px solid #ff4242;height:130px;padding-top:40px;box-sizing:border-box;}
teambox ul{position:absolute;top:-20px;left:12px;right:12px;text-align:center;overflow:hidden;}
teambox ul li{padding:0 5px;display:inline-block;position:relative;margin-bottom:10px;}
teambox ul li img{height:40px;width:40px;border-radius:4px;}
teambox ul li t{position:absolute;color:#fff;background-color:#ff4242;border-radius:4px;height:14px;line-height:14px;padding:0 4px;display:block;font-size:12px;bottom:-2px;right:-4px;z-index:1;}
teambox p{text-align:center;color:#999;}
teambox p.djs{color:#333;margin-top:4px;}
teambox p.djs dj{color:#ff4242;font-size:18px;font-weight:bolder;}
teambox p.djs num{font-size:18px;}
teambox p.djs days{font-size:14px;display:inline;}
teambox bt{width:100%;position:absolute;left:0;bottom:0;text-align:center;color:#fff;font-size:14px;line-height:32px;height:32px;background-color:#ff4242;left:0;bottom:0;}
teambox bt b{font-size:20px;font-weight:normal;}

ul.memberlist{margin:0 20px;display:block;}
ul.memberlist li{position:relative;padding-left:44px;margin-bottom:16px;height:40px;}
ul.memberlist li img{position:absolute;height:40px;width:40px;border-radius:4px;left:0;top:0;}
ul.memberlist li p.t{color:#ff4242;margin-bottom:6px;font-weight:bolder;}
ul.memberlist li p span{color:#999;float:right;font-size:12px;}

footer i.font{line-height: 50px;font-size: 20px;}

shareBox{background-color:rgba(0,0,0,0.7);height:100%;width:100%;left:0;top:0;position:absolute;z-index:100;display:none}
shareBox img{width:300px;float:right;margin-right:10px;margin-top:30px;}

tag{width:90px;height:90px;position:absolute;right:-10px;top:-60px;background-size:90px;}
tag.success{background-image:url('<?=$staticPath?>images/icon/team_success.png');}
tag.false{background-image:url('<?=$staticPath?>images/icon/team_false.png');}
</style>
<body>
	<header class="red back" data-page="/order/orderlist/"><tit>我的拼团</tit></header>
	<section id="page_team_info">
		<probox>
			<shopname><?=$team['ShopName'];?></shopname>
			<img src="<?=$team['ProductInfo']['ImageMin']?>" class="page" data-page="/product/info/<?=$team['ProductId']?>/<?=$team['TeamId']?>">
			<tit><?=$team['ProductInfo']['ProductName']?></tit>
			<p>
				<price><r><?=$team['MaxOrderCount']?>人团</r>¥<?=$team['ProductInfo']['Prices']['Team']?></price>
			</p>
		</probox>
		<teambox>
			<?if($team['TeamStatus']==3){?><tag class="success"></tag><?}?>
			<?if($team['TeamStatus']==4){?><tag class="false"></tag><?}?>
			<ul>
				<?foreach($team['Members'] as $k=>$v){
					//if($k>6)break;
					$msg = '';
					if($k==0)$msg = '团长';
					if($v['Lottery'])$msg = '中奖';
				?>
				<li><img src="<?=$v['Thnumbail']?>"><?if($msg){?><t><?=$msg?></t><?}?></li>
				<?}?>
			</ul>
			<p>团长：<?=$team['Members'][0]['NickName']?> <?=date('Y-m-d',$team['CreatTime'])?>开团</p>
			<p class="djs">剩余 <dj><num></num><days>天</days><num></num>:<num></num>:<num></num></dj> 结束</p>
			<bt><?=$team['TeamStatusInfo']?></bt>
		</teambox>
		<ul class="memberlist">
			<?foreach($team['Members'] as $k=>$v){
				$teamName = '小兵';
				$teamType = '参团';
				if($k==0){$teamName='团长';$teamType='开团';}
				if($k==1){$teamName='营长';}
				if($k==2){$teamName='连长';}
				if($k==3){$teamName='排长';}
			?>
			<li>
				<img src="<?=$v['Thnumbail']?>">
				<p class="t"><?=$teamName?></p>
				<p><?=$v['NickName']?> <span><?=date('Y-m-d H:i:s',$v['OrderCreatTime'])?><?=$teamType?></span></p>
			</li>
			<?}?>
		</ul>
		<?include('page/team_wanfa.php');?>
	</section>
	<?if($team['TeamStatus']==3){?>
		<footer class="red">
			<btn class="page" data-page="/">我也去开个团/返回首页</btn>
		</footer>
	<?}else{?>
	<?if($IsInTeam){?>
		<?if($team['TeamStatus']==2){?>
		<footer class="red" style="z-index:101">
			<btn class="showShareBg" data-open="0">邀请好友参团</btn>
		</footer>
		<?}else{?>
		<footer class="red">
			<btn class="page" data-page="/">我也去开个团/返回首页</btn>
		</footer>
		<?}?>
	<?}else{?>
	<footer class="red warp" style="height: 60px;">
		<warp class="f" style="padding:3px 15px;">
			<div><i class="icon warp menu_home page" data-page="/"></i>首页</div>
			<div><i class="icon warp menu_shoucang"></i>收藏</div>
			<div><i class="icon warp menu_shoucang page" data-page="/product/info/<?=$team['ProductId']?>/<?=$team['TeamId']?>"></i>宝贝详情</div>
		</warp>
		<warp style="padding:3px 16px;">
			<div><i class="icon font page" data-page="/product/pay/joinTuan_<?=$team['ProductId']?>_<?=$team['TeamId']?>">¥<?=$team['ProductInfo']['Prices']['Team']?></i>立即参团</div>
		</warp>
	</footer>
	<?}}?>
	<!--<shareBox><img src="<?=$staticPath?>images/fenxiang_bg.png"></shareBox>-->
	<style>
	shareBox{background-color:rgba(0,0,0,0.5);position:absolute;left:0;top:0;width:100%;height:100%;z-index:102;display:none}
	shareBox ul{height:90px;width:100%;position:absolute;left:0;bottom:60px;background-color:#fff;text-align:center;}
	shareBox ul li{height:80px;width:80px;background-size:50px;background-position:top center;background-repeat:no-repeat;display:inline-block;margin:10px;margin-bottom:0;text-align:center;box-sizing:border-box;padding-top:50px;line-height:20px;}
	shareBox ul li.wechat{background-image:url('/data/file/images/icon_weixin.jpg');}
	shareBox ul li.timeline{background-image:url('/data/file/images/icon_timeline.jpg');}
	shareBox div.return{height:60px;width:100%;left:0;bottom:0;position:absolute;background-color:#fff;box-sizing:border-box;border-top:1px solid #ccc;text-align:center;line-height:60px;font-size:20px;}
	</style>
	<shareBox>
		<ul>
			<li class="wechat">微信好友</li>
			<li class="timeline">朋友圈</li>
		</ul>
		<div class="return">取消</div>
	</shareBox>
	<script type="text/javascript" src="<?=$staticPath?>js/normal.js?v=<?=$version?>"></script>
	<script type="text/javascript" src="<?=$staticPath?>js/pintuan_info.js?v=<?=$version?>"></script>
	<script type="text/javascript">
		var endTime = '<?=$team["EndTime"]?>';

		/*$('footer btn.showShareBg').click(function(){
			var open = $(this).data('open');
			if(open){
				$('shareBox').hide();
				$(this).data('open',0);
			}else{
				$('shareBox').show();
				$(this).data('open',1);
			}
		});*/

		$('footer btn.showShareBg').click(function(){
			$('shareBox').show();
		});

		$('shareBox div.return').click(function(){
			$('shareBox').hide();
		});

		$(function(){
			<?if($team['TeamStatus']==2){?>
				Pintuan.daojishi(endTime);
			<?}else{?>
				$('teambox p.djs').hide();
			<?}?>
			Pintuan.init_page();
		});
	</script>
	<?
		$ShareConfig['url'] = 'team/info/'.$team['TeamId'];
		$ShareConfig['image'] = $team['ProductInfo']['ImageMin'];
		$ShareConfig['title'] = '我参加了拼一下：'.$team['ProductInfo']['Prices']['Team'].'元抢'.$team['ProductInfo']['ProductName'];
		$ShareConfig['description'] = '[还差'.$team['LastMemberNum'].'人]'.$team['ProductInfo']['Description'];
		include('page/weixin_share.php');
	?>