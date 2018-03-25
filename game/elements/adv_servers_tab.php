<div><? echo content('Last_banner_impressions',$content_adv);?><br>
<table cellpadding="10" style="margin:5px 0; width:500px; float:left;"  cellspacing="0" border="0" class="serv_tab">
<tr>
 <th><? echo content('campaign',$content_adv);?></th>
 <th><? echo content('server_name',$content_adv);?></th>
 <th><? echo content('ip_server',$content_adv);?></th>
</tr>
<?
	$servera=$bd->select('*','last_servers',array('id_user'=>$_SESSION['id']),'group by `id_server` ORDER BY `time` ASC',5);
	$ss=sizeof($servera);
	if($servera&&$ss){
		$q1='';$q2='';
		for($i=0;$i<$ss;$i++){
			$q1.=(($i)?" OR ":"")." `id`='".$servera[$i]['id_server']."'";
			$q2.=(($i)?" OR ":"")." `id`='".$servera[$i]['id_banner']."'";
		}
		$bnames=$bd->select(array('id','header'),'companies',$q2);
		
		$snames=$bd->select(array('id','name','ip','port'),'servers',$q1);
		
		for($i=0;$i<$ss;$i++){
			$bnames[$bnames[$i]['id']]=$bnames[$i]['header'];
			$snames[$snames[$i]['id']]=array(
				$snames[$i]['name'],
				$snames[$i]['ip'].':'.$snames[$i]['port']);
		}
		for($i=0;$i<$ss;$i++){
			echo'<tr';
			if($i&1)echo' class="nochet"';
			echo'><td>',$bnames[$servera[$i]['id_banner']],'</td>';
			echo'<td>',$snames[$servera[$i]['id_server']][0],'</td>';
			echo'<td>',$snames[$servera[$i]['id_server']][1],'</td>';
			echo'</tr>';
		}
	}else{
		echo'<tr><td colspan="3"><h3>',content('no_data'),'</h3></td></tr>';
	}
		
?>

</table>
</div>