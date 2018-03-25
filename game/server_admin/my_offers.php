<?
	$data=$bd->select('*','offers',array('id_server'=>$_SESSION['server']));
	if($data){
	$s=sizeof($data);$i=0;
	echo'<table cellspacing="0" class="serv_tab">';
	echo'<tr><th>',content('name'),'</th><th>',content('flags',$content_sa),'</th><th>',content('price_per_day',$content_sa),'</th><th></th></tr>';
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			echo'><td>'.$data[$i]['name'].'</td>';
			echo'<td>'.$data[$i]['flags'].'</td>';
			$str_fags='';
			echo'<td>'.$data[$i]['price'].'</td>';
			echo'<td><a href="',$host_lang,'/server_admin/index.php?a=del_offer&id=',$data[$i]['id'],'&server=',$_SESSION['server'],'">',content('remove'),'</a></td></tr>';
			
			$i++;
		} 
	echo'</table>';
	}
	echo'<a class="button" href="',$host_lang,'/server_admin/index.php?a=add_offer&server='.$_SESSION['server'].'">',content('add'),'</a>';
?><div style="clear:both;"></div>