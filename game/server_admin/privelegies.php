<?
$defDesk=$bd->select('*','priv_default_desk',array('type'=>$data_server[0]['type']));	$defDesk=$defDesk[0];
$arr_names=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','z');
if($_POST){
	$dataP=$_POST;
	foreach($arr_names as $val){
		if(isset($dataP[$val.'d'])&&$defDesk[$val]==$dataP[$val.'d'])
			$dataP[$val.'d']='';
		if(!isset($dataP[$val]))
			$dataP[$val]='off';
	}
	if(!isset($dataP['auto_priv']))
			$dataP['auto_priv']='off';
	$rez=$bd->update('privileges',$dataP,array('id_server'=>$data_server[0]['id']));
	if(isset($rez)){
		$suc=content('settings_saved');
		include('../elements/suc.php');
	}
}
if(!isset($dataP)){
	$data=$bd->select('*','privileges',array('id_server'=>$data_server[0]['id']));
	$data=$data[0];
}else $data=$dataP;
include('../elements/form_priv.php');
		
?>

