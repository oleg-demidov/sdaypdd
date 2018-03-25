<table style="float:left; width:450px;  " cellpadding="0" cellspacing="0" class="serv_tab">
<tr><th colspan="2"><? echo content('balance');?></th></tr>
<tr><td>
<?
	$dataU=$bd->select('*','users',array('id'=>$_SESSION['id']));
	echo '<span style="color:#999;">',content('name'),':</span> ',$dataU[0]['name'];
	echo '<br><span style="color:#999;">Email:</span> ',$dataU[0]['email'];
	echo '<br><span style="color:#00FF00;font-size:22px;">',content('summ',$content_tn),': ',$dataU[0]['balans'],'</span> RUR';
?>
</td><td>
<a href="<? echo $host_lang.'/balance/index.php?a=vivesti'?>" 
style="margin:5px;" class="button"><? echo content('vivesti');?></a>
<a href="<? echo $host_lang.'/balance/index.php?a=fill'?>"
style="margin:5px;" class="button"><? echo content('fill');?></a>
</td></tr>
</table>