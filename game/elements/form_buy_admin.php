 <form action="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>" method="post">
    	<table cellpadding="10" cellspacing="0" border="1" class="table_adv">
          <tr class="tr_top">
           <td colspan="3"><center><? echo content('buy_admin_server',$content_buy);?> <b><? echo $data_server[0]['name'];?></b></center></td>
          </tr>
          <tr>
          	<td><? echo content('nick');?> *</td>
            <td colspan="2"><input name="nik" type="text" size="20" value="" /></td>
          </tr>
          <tr>
          	<td><? echo content('age');?> *</td>
            <td colspan="2"><input name="age" type="text" size="20" value="" /></td>
          </tr>
          <tr>
          	<td><? echo content('ip');?></td>
            <td colspan="2"><input name="ip" type="text" size="20" value="" /></td>
          </tr>
          <tr>
          	<td><? echo content('steam_id');?></td>
            <td colspan="2"><input name="steam_id" type="text" size="20" value="" /></td>
          </tr>
          <tr>
          	<td><? echo content('email');?> *</td>
            <td colspan="2"><input name="email" type="text" size="20" value="" /></td>
          </tr>
          <tr>
          	<td><? echo content('password');?> *</td>
            <td colspan="2"><input name="pass" type="text" size="20" value="" /></td>
          </tr>
          <tr>
          	<td><? echo content('time_days');?> *</td>
            <td colspan="2"><input name="days" type="text" size="20" value="" /></td>
          </tr>
         <tr><td><? echo content('privilege');?></td><td><? echo content('description');?></td><td><? echo content('price_1_30_days',$content_buy);?></td></tr>
<?
if($_GET['type']==1){
	$str='';
	$offers=$bd->select('*','offers',array('id_server'=>$_GET['server']));
	$so=sizeof($offers);
	function get_flgs_desk(&$arr){
		global $bd;
		global $data_server;
		$desk_ser=$bd->select('flags','buy_flags',array('id_server'=>$_GET['server']));
		$desks=$bd->select('*','privelegies',array('type'=>$data_server[0]['type']));
		$deskD=array();
		$sd=sizeof($desks);
		for($i=0;$i<$sd;$i++)
			$deskD[$desks[$i]['id']]=$desks[$i]['name'];
		$desk_ser=unserialize($desk_ser[0]['flags']);
		$strD='';
		$i=0;
		foreach($arr as $k=>$v){
			if($v=='on'){
				if(isset($desk_ser['name_'.$k])&&$desk_ser['name_'.$k]!='')
					$des=$desk_ser['name_'.$k];
				else $des=$deskD[$k];
				if($i>0)$strD.='<br>';
				$strD.=$des;
				$i++;
			}
			
		}
		return $strD;
	}
	for($i=0;$i<$so;$i++){
		$str.='<tr><td>';
		$str.='<label><input type="radio" name="offer" id="i'.$offers[$i]['id'].'" value="'.$offers[$i]['id'].'"/>';
		$str.= $offers[$i]['name'].'</label><td class="desktd"><label for="i'.$offers[$i]['id'].'">'.get_flgs_desk($offers[$i]).'</label></td></td>';
		$str.='<td>'.$offers[$i]['price'].' / '.($offers[$i]['price']*30).'</td></tr>';
	}
	echo $str;
	$priv_on=$bd->select('autobuy','buy_flags',array('id_server'=>$_GET['server']));
		if($priv_on[0]['autobuy']=='on')
			echo'<tr><td colspan="3"><a href="http://'.$_SERVER['HTTP_HOST'].'/buy_admin.php?server='.$_GET['server'].'&type=2">'.content('individual_flags',$content_buy).'</a></td></tr>';
}
if($_GET['type']==2){
	$priv_on=$bd->select('flags','buy_flags',array('id_server'=>$data_server[0]['id']));
	$priv_on=unserialize($priv_on[0]['flags']);
	$privv=$bd->select('*','privelegies',array('type'=>$data_server[0]['type']));
	$sp=sizeof($privv);
	$str='';
	$rows=0;
	$n=0;
	for($i=0;$sp>$i;$i++){
		if(isset($priv_on['flag_'.$privv[$i]['id']])){
			$rows++;
			$str.=($n)?'<tr>':'';
			$str.='<td><label><input id="'.$privv[$i]['id'].'" name="'.$privv[$i]['id'].'" type="checkbox" /> ';
			$str.= $privv[$i]['id'].'</label></td>';
			$str.='<td><label for="'.$privv[$i]['id'].'">';
			$str.=(isset($priv_on['name_'.$privv[$i]['id']]))?$priv_on['name_'.$privv[$i]['id']]:$privv[$i]['name'];
			$str.='</label></td><td>'.$priv_on[$privv[$i]['id']].' / '.($priv_on[$privv[$i]['id']]*30).'</td></tr>';
			$n++;
		}
	}
	$str.='<tr><td colspan="3"><a href="http://'.$_SERVER['HTTP_HOST'].'/buy_admin.php?server='.$_GET['server'].'&type=1">'.content('hide_individual_flags',$content_buy).'</a></td></tr>';
	echo $str;
}
?>    
         <tr class="tr_grey">
           <td colspan="3"><center><input name="" type="submit" value="<? echo content('next');?>"></center></td>
          </tr>

        </table>
    </form>