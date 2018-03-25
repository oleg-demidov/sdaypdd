<h1>Новости</h1>
<?
$news=$bd->select('*','content',array('category'=>'news'),'ORDER BY `time` DESC',10);
$s=sizeof($news);
	for($i=0;$i<$s;$i++){
		$news_h=$bd->select('*','content_'.$lang,array('id'=>$news[$i]['value0']));
		$news_m=$bd->select('*','content_'.$lang,array('id'=>$news[$i]['value1']));
		echo'<div class="news_block"><h3>',$news_h[$i]['value'],'</h3>';
		echo'<div>',date('d-m-Y',$news[$i]['time']),'</div>';
		echo'<p>',$news_m[$i]['value'],'</p>';
		echo'</div>';
		$i++;
	}

?>
