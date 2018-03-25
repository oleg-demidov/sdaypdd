<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/scr/pdd_punkts.js" ></script>
<h1><? if(isset($CONT->header))echo $CONT->header;?></h1>
<h4><? if(isset($_GET['cat']) && isset($CONT->data[0]))echo $CONT->data[0]['category']?></h4>
<div class="overlay" title="окно"></div>
<div class="popup">
</div>
<p style="text-indent:0;">
<?

include('scr/pdd_sort.php');
$pddsort = new PDDSORT($bd);

function without_num($num){
	$noneed = array(28,29,30,31,32);
	foreach($noneed as $v){
		if($num == $v)
		return true;
	}
	return false;
}
if(!isset($_GET['cat'])){
	$cats = $CONT->data;
	if($cats){
		$sd = sizeof($cats);
		$astr = '<a class="url_category" href="%s">%s</a><br>';
		$u = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=';
		$pc = 'pdd&cat=';
		for($i=0; $i<$sd; $i++){
			if($cats[$i]['num'] == 26){
				echo sprintf( $astr, $u.'signs', $cats[$i]['num'].'. '.$cats[$i]['category']);
				continue;
			}
			if($cats[$i]['num'] == 27){
				echo sprintf( $astr, $u.'razm', $cats[$i]['num'].'. '.$cats[$i]['category']);
				continue;
			}
			echo sprintf( $astr, $u.$pc.$cats[$i]['id'], $cats[$i]['num'].'. '.$cats[$i]['category']);
		}
	}
}else{
	if(without_num($CONT->data[0]['num']))
		$cat = '';
	else $cat = $CONT->data[0]['num'].'.';
	$spdd = sizeof($CONT->data);
	for($i=0;$i<$spdd;$i++){
		?><div class="pdd_punkt">
			<p><span class="pdd_num" id="<? echo $cat,$CONT->data[$i]['punkt']?>"><? echo $cat,$CONT->data[$i]['punkt']?>.</span> 
			<? echo $pddsort->add_href($CONT->data[$i]['text'])?></p>
		</div><?
	}
}
?>
</p>