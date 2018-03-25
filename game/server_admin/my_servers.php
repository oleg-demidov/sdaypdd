<?
	//$bdObj=new BD($user_bd,$pass_bd);
	$data=$bd->select('*','servers',array('id_user'=>$_SESSION['id']));
	if($data){
	$s=sizeof($data);$i=0;
	echo'<table cellspacing="0" class="serv_tab">';
	echo'<tr><th>'.content('information').'</th><th>Был активен</th><th></th><th></th></tr>';
		while($i<$s){
			echo'<tr';
			if($i%2)echo' class="nochet" ';
			echo'><td style="background-image:url(',$host,'/images/',$data[$i]['type'],'.png);padding-left:85px;">';
			echo'<span style="color:#000;">',content('name'),':</span> ',$data[$i]['name'],'<br>';
			echo'<span style="color:#000;">Ip:</span> ',$data[$i]['ip'],':',$data[$i]['port'].'<br>';
			$country=$bd->select(array('country_name_'.$lang),'country_',array('id'=>$data[$i]['geo_country']));
			$city=$bd->select(array('city_name_'.$lang),'city_',array('id'=>$data[$i]['geo_city']));
			echo'<span style="color:#000;">',content('geo'),':</span> ',$country[0]['country_name_'.$lang],'/',$city[0]['city_name_'.$lang],'<br>';
			echo'<a href="',$host_lang,'/server_admin/index.php?a=server_cfg&server=',$data[$i]["id"],'">',content('change_data',$content_sa),'</a></td>';
			if($data[$i]['last_conn']<time()-(60*30))$sac='#FF0000';
			else $sac='#00CC00';
			if($data[$i]['last_conn']==0)$tee=' - ';
			else $tee=date('d-m-Y H:i',$data[$i]['last_conn']);
			$pathtype=array('cs16'=>'amxx',	'css'=>'smx', 'csgo'=>'smx');
			echo'<td style="color:',$sac,'">',$tee,'<a class="dl_plug" title="',content('title_dl',$content_sa),'"';
			echo' href="',$host,'/plugins/',$data[$i]['type'],'/csmoney.',$pathtype[$data[$i]['type']],'"></a>';
			echo'</td>';
			echo'<td><a href="',$host_lang,'/server_admin/index.php?a=buy_time&server=',$data[$i]["id"],'">',content('auto_sale_priv',$content_sa),'</a><br><a href="',$host_lang,'/server_admin/index.php?a=adv_conf&server=',$data[$i]["id"],'">',content('ad_settings',$content_sa),'</a></td>';
			echo'<td><a href="',$host_lang,'/server_admin/index.php?a=del_serv&s=',$data[$i]["id"],'">',content('remove'),'</a></td></tr>';
			$i++;
		} 
	echo'</table>';
	}
?>
<a  class="button" href="<? echo $host_lang?>/server_admin/index.php?a=add_server"><? echo content('add_server',$content_sa)?></a>