<?
include('../elements/form_transactions.php');
include('../elements/panel_balance.php');
?>
<table cellpadding="0" cellspacing="0" class="serv_tab">
<tr><th><? echo content('nomber_trans')?></th><th><? echo content('summ',$content_tn)?></th><th><? echo content('time')?></th><th><? echo content('description')?></th><th><? echo content('status')?></th><th></th></tr>
<?
$bd->delete_str('transactions',"`status`='no_paid' AND `time`<".(time()-(3600*24)));

$where='';
$zpt='';
if(isset($_POST['query'])&&$_POST['query']!=''){
	$where.="MATCH (`id`,`comment`) AGAINST ('".$_POST['query']."')";
	$zpt=' AND ';
}
if(isset($_POST['from'])&&$_POST['from']!='')
	$where.=$zpt."`time`>'".strtotime($_POST['from'])."'";
if(isset($_POST['to'])&&$_POST['to']!='')
	$where.=$zpt." AND `time`<'".strtotime($_POST['to'])."'";
$trans=$bd->select('*','transactions',$where,'ORDER BY `time` DESC','30');
if($trans){
	/*$dir=array(
		'in'=>content('input',$content_tn),
		'out'=>content('output',$content_tn)
	);*/
	$st=sizeof($trans);
	for($i=0;$i<$st;$i++){
		echo'<tr';
		if($i%2)echo' class="nochet" ';
		echo'><td>',$trans[$i]['id'],'</td>';
		echo'<td><a style="color:#FFFF00;" href="#" title="',$trans[$i]['desc'],'">',$trans[$i]['summ'],'</a> RUR</td>';
		echo'<td>',date ("d.m.y H:i",$trans[$i]['time']),'</td>';
		echo'<td>  ',$trans[$i]['desc'],'</td>';
		echo'<td>',content($trans[$i]['status'],$content_tn),' (',$trans[$i]['sposob'],')</td>';
		echo'<td>';
		if($trans[$i]['direction']=='in'&&$trans[$i]['status']=='no_paid')echo'<a style="margin:-2px;" class="button" href="',$host_lang,'/pays/index.php?a=pay&id=',$trans[$i]['id'],'">',content('payment',$content_tn),'</a>';
		echo'</td></tr>';
	}
}else
	echo'<tr><td colspan="6"><h3>',content('no_data'),'</h3></td></tr>';
?>
</table>