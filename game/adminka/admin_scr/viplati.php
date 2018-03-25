<table cellpadding="0" cellspacing="0" class="serv_tab">
<tr><th><? echo content('nomber_trans')?></th><th><? echo content('user')?></th><th><? echo content('time')?></th><th><? echo content('summ')?></th><th></th></tr>
<?
$where=array('direction'=>'out','status'=>'no_paid');
$trans=$bd->select('*','transactions',$where,'ORDER BY `time` DESC','30');
if($trans){
	$st=sizeof($trans);
	for($i=0;$i<$st;$i++){
		echo'<tr';
		if($i%2)echo' class="nochet" ';
		echo'><td>',$trans[$i]['id'],'</td>';
		$datau=$bd->select('*','users',array('id'=>$trans[$i]['id_user']));
		echo'<td>',$datau[0]['name'],' (',$datau[0]['purse'],')</td>';
		echo'<td>',date ("d.m.y",$trans[$i]['time']),'</td>';
		echo'<td><a style="color:#FFFF00;" href="#" title="',$trans[$i]['comment'],'">',$trans[$i]['summ'],'</a></td>';
		
		echo'<td><a style="margin:-2px;" class="button" href="',$host_lang,'/adminka/index.php?a=pay&id=',$trans[$i]['id'],'">Вывод</a></td></tr>';
	}
}else
	echo'<tr><td colspan="5"><h3>',content('no_data'),'</h3></td></tr>';
?>
</table>