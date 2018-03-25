<?
	if(isset($_POST['activ']))
		$bd->update('companies',array('activ'=>$_POST['activ']),array('id'=>$_GET['id_com']));
	if(isset($_POST['del'])){
		$bd->delete_str('companies',array('id'=>$_GET['id_com']));
		unlink('../sprites/'.$_GET['id_com'].'.spr');
		unlink('../sprites/'.$_GET['id_com'].'.vtf');
		unlink('../sprites/gifs/'.$_GET['id_com'].'.gif');
	}
	$data=$bd->select('*','companies');
	if($data){
	$s=sizeof($data);$i=0;
	echo'<table cellspacing="0" class="serv_tab">';
	echo'<tr><th>Файл</th><th>Название</th><th>География</th><th>Url</th><th>Бюджет</th><th></th><th></th></tr>';
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			echo'><td><a target="_blank" href="',$host,'/sprites/gifs/'.$data[$i]['id'].'.gif"/>';
			echo'<img src="',$host,'/advertiser/banner_creator/img_low.php?s=100&f=',$host,'/sprites/gifs/'.$data[$i]['id'].'.gif"/></a></td>';
			echo'<td>'.$data[$i]['header'].'</td>';
			mysql_select_db('geo_rus',$bd->connect);
			$country=$bd->select(array('country_name_ru'),'country_',array('id'=>$data[$i]['geo_country']));
			$city=$bd->select(array('city_name_ru'),'city_',array('id'=>$data[$i]['geo_city']));
			echo'<td>'.$country[0]['country_name_ru'].'/'.$city[0]['city_name_ru'].'</td>';
			echo'<td>'.$data[$i]['site'].'</td>';
			echo'<td>'.$data[$i]['budget'].'</td>';
			if($data[$i]['activ']=='off')echo'<td><form action="'.$host.$_SERVER['REQUEST_URI'].'&id_com='.$data[$i]['id'].'" method="post"><input  name="activ" style="display:none;" value="on"/><input name="" class="button" type="submit" value="Актив"></form></td>';
			else echo'<td><form action="'.$host.$_SERVER['REQUEST_URI'].'&id_com='.$data[$i]['id'].'" method="post"><input  name="activ" style="display:none;" value="off"/><input name="" class="button" type="submit" value="Деактив"></form></td>';
			echo'<td><form action="'.$host.$_SERVER['REQUEST_URI'].'&id_com='.$data[$i]['id'].'" method="post"><input  name="del"  style="display:none;" value="1"/><input name="" class="button" type="submit" value="Удалить"></form></td></tr>';
			$i++;
		} 
	echo'</table>';
	}
	echo'<a class="button" href="http://'.$_SERVER['HTTP_HOST'].'/advertiser/index.php?a=add_company">Добавить</a>';
?>
<div style="clear:both;"></div>