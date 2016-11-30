<?
	foreach($ShareConfig as $k=>$v){
		$v = trim($v);
		$v = str_replace("/\r|\n/",'',$v);
		$v = str_replace(" ",'',$v);
		$v = str_replace("¡¡",'',$v);
		$v = str_replace("\t",'',$v);
		$v = str_replace("\t",'',$v);
		$v = str_replace("\n",'',$v);
		$v = str_replace("\r",'',$v);
		$ShareConfig[$k] = $v;
	}
?>
	<script type="text/javascript">
		var Share_Url = setShareUrl("<?=$ShareConfig['url']?>");
		var Share_Image = "<?=$ShareConfig['image']?>";
		var Share_Title = "<?=$ShareConfig['title']?>";
		var Share_Desri = "<?=$ShareConfig['description']?>";
		
		function getShareUrl(){return Share_Url;}
		function getShareImage(){return Share_Image;}
		function getShareTitle(){return Share_Title;}
		function getShareDes(){return Share_Desri;}

	</script>