<?
	$data=$bd->select('*','models',array("id_server"=>$_SESSION['server']));
	if($data){
		$s=sizeof($data);$i=0;
		echo'<table cellspacing="0" class="serv_tab">';
		echo'<tr><th>Название</th><th>Снимок</th><th>Загрузил</th><th>Время загрузки</th><th></th></tr>';
			while($i<$s){
				echo'<tr><td>'.$data[$i]['name'].'</td>';
				echo'<td><img src="http://'.$_SERVER['HTTP_HOST'].'/models/icons/small'.$data[$i]['id'].'.'.$data[$i]['icon_type'].'"/></td>';
				echo'<td>'.$data[$i]['id_user'].'</td>';
				echo'<td>'.$data[$i]['time'].'</td>';
				echo'<td><a href="http://'.$_SERVER['HTTP_HOST'].'/server_admin/index.php?a=del_model&model='.$data[$i]['id'].'">удалить</a></td></tr>';
				
				$i++;
			} 
		echo'</table>';
	}
	echo'<a href="http://'.$_SERVER['HTTP_HOST'].'/server_admin/index.php?a=add_model&server='.$_SESSION['server'].'">Добавить</a>';
?>