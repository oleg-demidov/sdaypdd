<?
	$lim=(isset($_GET['lim']))?$_GET['lim']:0;
	$serv=$_GET['server'];
	$query="SELECT * FROM `admins` WHERE `id_server`='".$bd->check_inj($serv)."'";
	if(isset($_POST['find'])&&$_POST['find']!=''){
		$find=$bd->check_inj($_POST['find']);
		$query.=" AND (`name`='".$find."' OR `email`='".$find."' OR `steam`='".$find."')";
		$query.=" AND `activ`='".$bd->check_inj($_POST['activ'])."'";
	}
	$query.=" LIMIT ".$bd->check_inj($lim).",16";
	
	$data=$bd->get_result($bd->sql_query($query));
	if(!$data&&isset($_POST['find'])&&$_POST['find']!=''){
		$suc= content('search_results',$content_sa)."(<b>".$find."</b>) 0";
		include('../elements/suc.php');
	$data=$bd->select('*','admins',array('id_server'=>$serv));
	}
	?>
	<form method="post" action="<? echo $host_lang;?>/server_admin/index.php?a=admins&server=<? echo $serv;?>">
	
    <input type="submit" class="button" value="<? echo content('search')?>">
	<input type="text" style="float:right; margin:7px;" class="text_pole" name="find" >
	<select name="activ" style="float:right; margin:10px 0">
		<option value="on"><? echo content('active')?></option>
		<option value="off"><? echo content('not_active')?></option>
		
	</select></form>
<?
	if($data){
		$s=sizeof($data);$i=0;
		echo'<table cellspacing="0" border="0" class="serv_tab">';
		echo'<tr><th>',content('data',$content_sa),'</th><th>',content('authorised',$content_sa),'</th><th>',content('flags',$content_sa),'</th><th>',content('ends',$content_sa),'</th><th></th><th></th></tr>';
			while($i<$s&&$i!=15){
				$classTr=($i%2)?'':'class="nochet"';
				echo'<tr '.$classTr.'><td>';
				if($data[$i]['name']!='')echo '<div><span style="color:#999;">',content('nick'),':</span> ',$data[$i]['name'],'</div>';
				if($data[$i]['steam']!='')echo'<div><span style="color:#999;">Steam_id:</span> ',$data[$i]['steam'],'</div>';
				if($data[$i]['ip']!='')echo'<div><span style="color:#999;">Ip:</span> ',$data[$i]['ip'],'</div>';
				if($data[$i]['email']!='')echo'<div><span style="color:#999;">Email:</span> ',$data[$i]['email'],'</div>';
				if($data[$i]['age']!='')echo'<div><span style="color:#999;">',content('age'),':</span> ',$data[$i]['age'],'</div>';
				echo'</td><td><div><span style="color:#999;">',$data[$i]['enter_type'],':</span> ',$data[$i][$data[$i]['enter_type']],'</div>';
				if($data[$i]['pass']!='')echo'<div><span style="color:#999;">',content('password'),':</span> ',$data[$i]['pass'],'</div>';
				echo'</td><td>',$data[$i]['flags'],'</td>';
				
				if($data[$i]['activ']=='off')$date=content('not_active'); 
				elseif($data[$i]['timeout']<time()) $date=content('expired',$content_sa).' '.date( 'j.m.Y  H:h:i', $data[$i]['timeout']);
				else $date=date( 'j.m.Y  h:i', $data[$i]['timeout']);
				echo'<td>'.$date.'</td>';
				echo'<td>';
				//if($data[$i]['timeout']<time()&&$data[$i]['timeout'])
					echo'<a href="'.$host_lang.'/server_admin/index.php?a=admin_prolong&admin=',$data[$i]['id'],'&server=',$_GET['server'],'">',content('extend',$content_sa),'</a>';
				echo'</td>';
				echo'<td><a href="'.$host_lang.'/server_admin/index.php?a=del_admin&admin=',$data[$i]['id'],'&server=',$_GET['server'],'">',content('remove'),'</a></td></tr>';
				
				$i++;
			} 
		echo'</table>';
	}
	if($lim>0)echo'<a href="'.$host_lang.'/server_admin/index.php?a=admins&server='.$serv.'&act='.$activ.'&lim='.($lim-15).'"> < </a> ';
	if(isset($s)&&$s>15)echo'<a href="'.$host_lang.'/server_admin/index.php?a=admins&server='.$serv.'&act='.$activ.'&lim='.($lim+15).'"> > </a> <br>';
	
?>
<a class="button" style="clear:right;" href="<? echo $host_lang;?>/server_admin/index.php?a=add_admin&server=<? echo $serv; ?>"><? echo content('add')?></a>
<div style="clear:both;"></div>