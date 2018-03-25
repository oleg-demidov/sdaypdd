<script type="text/javascript" src="<?php echo 'http://',$_SERVER['HTTP_HOST']?>/scr/pdd_punkts.js" ></script>
<h1><? if(isset($CONT->header))echo $CONT->header;?></h1>
<h4>Дорожная разметка</h4>

<div class="overlay" title="окно"></div>
<div class="popup">
</div>
<h4><? echo $CONT->data[0][0]['category']?></h4>
<?
include('scr/pdd_sort.php');
$pddsort = new PDDSORT($bd);
function html_sign($data){
	global $pddsort;
	$img = 'http://'.$_SERVER['HTTP_HOST'].'/signs/small%d.png';
	$spdd = sizeof($data);
	?><table cellpadding="5" border="0"><?
	for($i=0;$i<$spdd;$i++){
		?><tr class="pdd_punkt" >
			<td class="punkt_znak"><img alt="<? echo $data[$i]['cnum'],'.',$data[$i]['num'],'. ',$data[$i]['name'];?>" title="<? echo $data[$i]['cnum'],'.',$data[$i]['num'],'. ',$data[$i]['name'];?>" src="<? echo sprintf ($img, $data[$i]['id']);?>"/><br>
				"<? echo $data[$i]['name'];?>"
			</td>
			<td><span class="pdd_num" id="<? echo $data[$i]['cnum'].'.'.$data[$i]['num']?>"><? echo $data[$i]['cnum'].'.'.$data[$i]['num']?></span>
			 
			<? echo $pddsort->add_href($data[$i]['text'])?></td>
		</tr><?
	}
	?></table><?
}
	html_sign($CONT->data[0]);
?>
<h4><? echo $CONT->data[1][0]['category']?></h4>
<?
	html_sign($CONT->data[1]);
?>