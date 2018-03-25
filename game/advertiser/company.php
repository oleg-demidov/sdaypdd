<?
	$data=$bd->select('*','companies',array('id_user'=>$_SESSION['id']));
	if($data){
	$s=sizeof($data);$i=0;
	echo'<table cellspacing="0" class="serv_tab">';
	echo'<tr><th>'.content('view').'</th><th>'.content('name').'</th><th>'.content('geo').'</th><th>'.content('budget').'</th><th>'.content('run_budget',$content_adv).'</th><th>'.content('status').'</th><th></th><th></th></tr>';
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			$urll=$host.'/advertiser/banner_creator/img_low.php?s=100&f=';
			$urlf='http://'.$_SERVER['HTTP_HOST'].'/sprites/gifs/'.$data[$i]['id'].'.gif';
			echo'><td><img src="',$urll,$urlf,'"/></td>';
			echo'<td>'.$data[$i]['header'].'</td>';
			if($data[$i]['geo_country']==0){
				echo'<td>-</td>';
			}else{
				$country=$bd->select(array('country_name_'.$lang),'country_',array('id'=>$data[$i]['geo_country']));
				$city=$bd->select(array('city_name_'.$lang),'city_',array('id'=>$data[$i]['geo_city']));
				echo'<td>'.$country[0]['country_name_'.$lang].' '.$city[0]['city_name_'.$lang].'</td>';
			}
			echo'<td>'.$data[$i]['budget'].'</td>';
			echo'<td>'.$data[$i]['used_budget'].'</td>';
			echo'<td><span class="activ_',$data[$i]['activ'],'">',content($data[$i]['activ']),'</span></td>';
			echo'<td><a href="'.$host_lang.'/advertiser/index.php?a=company_sett&id='.$data[$i]['id'].'">'.content('configure').'</a></td>';
			echo'<td><a href="'.$host_lang.'/advertiser/index.php?a=del_company&id='.$data[$i]['id'].'">'.content('remove').'</a></td></tr>';
			$i++;
		} 
	echo'</table>';
	}
	echo'<a class="button" href="'.$host_lang.'/advertiser/index.php?a=add_company">'.content('add').'</a>';
?>
<div style="clear:both;"></div>