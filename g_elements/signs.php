<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/scr/pdd_punkts.js" ></script>
<h1><? if(isset($CONT->header))echo $CONT->header;?></h1>
<h4>Дорожные знаки</h4>
<h4><? if(isset($_GET['cat'] )&& $CONT->data)echo $CONT->data[0]['category'];?></h4>
<div class="overlay" title="окно"></div>
<div class="popup">
</div>
<table cellpadding="5" border="0">
<?
include('scr/pdd_sort.php');
$pddsort = new PDDSORT($bd);
$img = 'http://'.$_SERVER['HTTP_HOST'].'/signs/small%d.png';
if(!isset($_GET['cat'])){
	$cats = $CONT->data;
	if($cats){
		$sd = sizeof($cats);
		$u = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=signs&cat=';
		for($i=0;$i<$sd;$i++){
			echo'<a class="url_category" href="',$u,$cats[$i]['id'],'">',$cats[$i]['cnum'],'. ',$cats[$i]['category'],'</a><br>';
		}
	}
}else{
	//print_r($CONT->data);
	$spdd = sizeof($CONT->data);
	for($i=0;$i<$spdd;$i++){
		?><tr class="pdd_punkt" >
			<td class="punkt_znak"><img alt="<? echo $CONT->data[$i]['cnum'],'.',$CONT->data[$i]['num'],'. ',$CONT->data[$i]['name'];?>" title="<? echo $CONT->data[$i]['cnum'],'.',$CONT->data[$i]['num'],'. ',$CONT->data[$i]['name'];?>" src="<? echo sprintf ($img, $CONT->data[$i]['id']);?>"/><br>
				"<? echo $CONT->data[$i]['name'];?>"
			</td>
			<td><span class="pdd_num" id="z<? echo $CONT->data[$i]['cnum'].'.'.$CONT->data[$i]['num'];?>"><? echo $CONT->data[$i]['cnum'].'.'.$CONT->data[$i]['num']?></span>
			 
			<? echo $pddsort->add_href($CONT->data[$i]['text'])?></td>
		</tr><?
	}
}
?>
</table>