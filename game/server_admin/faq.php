<? include('../scripts/faq_style.php');?>
<h3><? echo content('faq')?></h3>
<?
	$faqData=$bd->select('*','content',array('category'=>$_GET['c']));
	$whe="`id`='".$faqData[0]['value0']."' OR `id`='".$faqData[0]['value1']."' OR `id`='".$faqData[0]['value2']."'";
	$faqVals=$bd->select('*','content_'.$lang,$whe);
	if($faqData){
		$sf=sizeof($faqData);
		for($i=0;$i<$sf;$i++){
			$whe="`id`='".$faqData[$i]['value0']."' OR `id`='".$faqData[$i]['value1']."' OR `id`='".$faqData[$i]['value2']."'";
			$faqVals=$bd->select('*','content_'.$lang,$whe);
			echo'<div class="spoiler_head"><a class="spoiler_links">',$faqVals[0]['value'];
			echo'</a><div class="spoiler_body">',$faqVals[1]['value'];
			echo'</div></div>';
		}
	}
?>
<div style="clear:both;"></div>