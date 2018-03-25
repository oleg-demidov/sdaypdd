<table style="width:300px; float:left; margin-top:5px;" cellpadding="0"  cellspacing="0" border="0" class="serv_tab">
<?
	$dserv=$bd->sql_query("select type,count(type),sum(UNIX_TIMESTAMP()<(last_conn+60*10))from servers where id_user=".$_SESSION['id']." group by type");
	$dserv=$bd->get_result($dserv,MYSQL_NUM);
	$ssv=array("cs16"=>array(0,0),"css"=>array(0,0),"csgo"=>array(0,0));
	for($i=0;$i<3;$i++)
		if(isset($dserv[$i][0]))
			$ssv[$dserv[$i][0]]=array($dserv[$i][1],$dserv[$i][2]);

?>
	<tr><th><? echo content('servers',$content_sa);?></th><th><? echo content('num',$content_sa);?></th><th><? echo content('activnix',$content_sa);?></th></tr>
	<tr><td>Counter Strike</td>
		<td><? echo $ssv['cs16'][0]?></td>
		<td><? echo $ssv['cs16'][1]?></td>
	</tr>
	<tr class="nochet"><td>Counter Strike:Source</td>
		<td><? echo $ssv['css'][0]?></td>
		<td><? echo $ssv['css'][1]?></td>
	</tr>
	<tr><td>Counter Strike:GO</td>
		<td><? echo $ssv['csgo'][0]?></td>
		<td><? echo $ssv['csgo'][1]?></td>
	</tr>

</table>