<?
$ss=$bd->select('*','servers',array('id_user'=>$_SESSION['id']));
if($ss){
	echo'<tr><td>Установить как заглушку для</td><td>
	<select name="special_serv" id="special_serv"><option value="none">нет</option>';
	$sg=sizeof($ss);
	for($i=0;$i<$sg;$i++){
		echo'<option value="'.$ss[$i]['id'].'"';
		if(isset($data['special_serv']))
			if($data['special_serv']==$ss[$i]['id'])echo'selected';
		echo'>'.$ss[$i]['name'].'</option>';
	}
	echo'</select></td></tr>';
}
?>