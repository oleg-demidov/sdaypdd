<?
$trans=$bd->select('*','transactions',array('id'=>$id_trans));

function pay($id,$sposob){
	global $bd;
	global $trans;
	if($trans[0]['direction']=='adminka'){
		$precent=$bd->select(array('value'),'variables',array('name'=>'precent_adminka'));
		$summ=($trans[0]['summ']/100)*(100-$precent[0]['value']);
		$bd->update('admins',array('activ'=>'on'),array('id'=>$trans[0]['id_adminka']));
	}
	if($trans[0]['direction']=='in')
		$summ=$trans[0]['summ'];
	
	if(!($bd->update('users',"`balans`=`balans`+".$summ,array('id'=>$trans[0]['id_user']))))
		return false;
	$bd->update('transactions',array('status'=>'paid','sposob'=>$sposob,'time'=>time()),array('id'=>$id));
	return true;
}

function check_summ($summ){
	global $trans;
	if($trans){
		if(floatval($summ)==floatval($trans[0]['summ']))
			return true;
	}
	return false;
}
?>