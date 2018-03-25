<div class="header_panel">ПДД</div>
<div class="panel_block">
<div class="pdd_panel_urls">
<?php
	function strlow($text, $pos = 25){
		if(strlen($text)<$pos)$pos = strlen($text);
		if(($pr = strpos($text, ' ', $pos))!==false)
			$text = substr($text, 0, $pr);
		return $text;
	}
	$cats = $bd->get_all("SELECT * FROM `categories` ORDER BY `num`");
	$astr = '<a href="%s" title="%s">%s</a><br>';
	$u = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=';
	$pc = 'pdd&cat=';
	$sd = sizeof($cats);
	for($i=0; $i<$sd; $i++){
		if($cats[$i]['num'] == 26){
			echo sprintf( $astr, $u.'signs', $cats[$i]['category'], $cats[$i]['num'].'. '.strlow($cats[$i]['category']));
			continue;
		}
		if($cats[$i]['num'] == 27){
			echo sprintf( $astr, $u.'razm', $cats[$i]['category'], $cats[$i]['num'].'. '.strlow($cats[$i]['category']));
			continue;
		}
		echo sprintf( $astr, $u.$pc.$cats[$i]['id'], $cats[$i]['category'], $cats[$i]['num'].'. '.strlow($cats[$i]['category']));
	}
?>
</div>
</div>