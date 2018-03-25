<?
	$data=$bd->select('*','promo_keys',array('id_server'=>$_SESSION['server']));
	if($data){
		$s=sizeof($data);
		$datac=array();
		$arr_keys=array();
		$m=-1;
		for($i=0;$i<$s;$i++){
			if(!isset($arr_keys[$data[$i]['id']])){
				$arr_keys[$data[$i]['id']]=$i;
				$m++;
				$datac[$m]=array();
				$datac[$m]['pkeys']=array($data[$i]['key']);
				$flags=$bd->select('flags','offers',array('id'=>$data[$i]['id_offer']));
				$datac[$m]['flags']=$flags[0]['flags'];
				$datac[$m]['days']=$data[$i]['days'];
				$datac[$m]['id_offer']=$data[$i]['id_offer'];
				$datac[$m]['id']=$data[$i]['id'];
			}else
				$datac[$m]['pkeys'][]=$data[$i]['key'];
		}
		echo'<table cellspacing="0" class="serv_tab">';
		echo'<tr><th>',content('flags',$content_sa),'</th><th>',content('days',$content_sa),'</th><th>',content('codes',$content_sa),'</th><th></th></tr>';
		$s=sizeof($datac);
		for($i=0;$i<$s;$i++){
			echo'<tr ';
			if($i%2)echo' class="nochet" ';
			echo' ><td>'.$datac[$i]['flags'].'</td>';
			echo'<td>'.$datac[$i]['days'].'</td>';
			$str_keys='';
			$sk=sizeof($datac[$i]['pkeys']);
			for($k=0;$k<$sk;$k++){
				$str_keys.=$datac[$i]['pkeys'][$k].' ';
				//echo $datac[$i]['pkeys'][$k];
				}
			echo'<td class="promokeys">'.$str_keys.'</td>';
			echo'<td><a href="',$host_lang,'/server_admin/index.php?a=del_keys&id='.$datac[$i]['id'].'&server='.$_SESSION['server'].'">',content('remove'),'</a></td></tr>';
			
		} 
		echo'</table>';
	}
	echo'<a class="button" href="',$host_lang,'/server_admin/index.php?a=add_promokeys&server='.$_SESSION['server'].'">',content('add'),'</a>';
?><div style="clear:both;"></div>
