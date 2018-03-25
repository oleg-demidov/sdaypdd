<? $id_sess=(isset($id_sess)?$id_sess:$_GET['sess_id']);?>
 <form action="<?php echo $host_lang.'/buy_admin.php?server='.$_GET['server'].'&step=3&sess_id='.$id_sess;?>" method="post">
 <h3><? echo content('buy_admin_server',$content_buy);?> <span style="color:#000;"><? echo $data_server[0]['name'];?></span></h3>
 <table align="center" border="0" cellpadding="5"><tr><td>
<? echo content('select_privileges',$content_buy);?></td><td><a style="color:#00CC00;" href="<?php echo $host_lang.'/buy_promo.php?server='.$_GET['server'].'&sess_id='.$id_sess;?>"><? echo content('i_have_promocode',$content_buy);?></a></td></tr></table>
    	<table cellpadding="10" cellspacing="0" border="0" class="serv_tab">
          
          
                  <tr><th><? echo content('privilege');?></th><th><? echo content('description');?></th><th width="170px"><? echo content('price_1_30_days',$content_buy);?></th></tr>
<?
function get_priv_desk($bd){
	global $data_server;
	$def_desc=$bd->select('*','priv_default_desk',array('type'=>$data_server[0]['type']));
	$privileges=$bd->select('*','privileges',array('id_server'=>$data_server[0]['id']));
	$privileges=$privileges[0];
	$def_desc=$def_desc[0];
	unset($def_desc['type']);
	foreach($def_desc as $key=>$value){
		if($privileges[$key.'d']=='')
			$privileges[$key.'d']=$value;
	}
	$privileges['keys']=array_keys($def_desc);
	return $privileges;
}
$privd=get_priv_desk($bd);
$priv_on=$bd->select(array('auto_priv','autobuy'),'privileges',array('id_server'=>$_GET['server']));
$offers=$bd->select('*','offers',array('id_server'=>$_GET['server']));
$typeo=(isset($_GET['type']))?$_GET['type']:1;
if(!$offers)$typeo=2;

if($typeo==1){
	function get_flgs_desk($flags){
		$str='';
		global $privd;
		$arr=explode(',',$flags);
		$sd=sizeof($arr);
		for($i=0;$i<$sd;$i++){
			$str.='<b>'.$arr[$i].'</b> - '.$privd[$arr[$i].'d'];
			if(($i+1)<$sd)$str.='<br>';
		}
		return $str;
	}
	
	$str='';
	
	if($priv_on[0]['autobuy']=='on'&&($offers||($priv_on[0]['auto_priv']=='on'))){
		if($offers){
		$so=sizeof($offers);
			for($i=0;$i<$so;$i++){
				echo '<tr';
				echo($i%2)?'':' class="nochet" ';
				echo'><td><label><input type="radio" name="offer" id="i'.$offers[$i]['id'].'" value="'.$offers[$i]['id'].'"/>'.$offers[$i]['name'].'</label></td>';
				echo '<td><label for="i'.$offers[$i]['id'].'">'.get_flgs_desk($offers[$i]['flags']).'</label></td></td>';
				echo '<td>'.$offers[$i]['price'].' / '.($offers[$i]['price']*30).'</td></tr>';
			}
		}
		
		if($priv_on[0]['auto_priv']=='on')
			echo'<tr><td colspan="3"><a href="'.$host_lang.'/buy_admin.php?server='.$_GET['server'].'&type=2&step=2&ch=1&sess_id='.$id_sess.'">'.content('individual_flags',$content_buy).'</a></td></tr>';
		
	}else echo "<h3>".content('no_priv_on_serv').'</h3>';
}


if($typeo==2){
	$i=0;
	foreach($privd['keys'] as $value){
		if($privd[$value]=='off')continue;
		$i++;
		echo '<tr';
		echo($i%2)?'':' class="nochet" ';
		echo'><td><label><input id="'.$value.'" name="'.$value.'" type="checkbox" /> '.$value;
		echo'</label></td><td><label for="'.$value.'">'.$privd[$value.'d'].'</label>';
		echo'</td><td>'.$privd[$value.'p'].' / '.($privd[$value.'p']*30).'</td></tr>';
	}
	if($offers)echo'<tr><td colspan="3"><a href="'.$host_lang.'/buy_admin.php?server='.$_GET['server'].'&type=1&step=2&ch=1&sess_id='.$id_sess.'">'.content('hide_individual_flags',$content_buy).'</a></td></tr>';
}
?>      
</table><table border="0" cellpadding="10">
  <tr><td><input type="text" class="text_pole"  value="" name="days"/></td>
    <td><? echo content('lasts');?></td>
   
  </tr>
         <tr>
           <td colspan="2"><center><input name="" style="margin:5px;" class="button" type="submit" value="Далее"> <a class="button" style="margin:5px;" href="<?php echo $host_lang.'/buy_admin.php?server='.$_GET['server'].'&sess_id='.$id_sess;?>"><? echo content('back');?></a> 
           </center></td>
          </tr>
        </table>
        <input type="hidden" value="3" name="step">
    </form>