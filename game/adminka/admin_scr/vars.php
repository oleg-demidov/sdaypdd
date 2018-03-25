<?
if(isset($_POST)){
	$dq=array();
	$rez=0;
	foreach($_POST as $k=>$v){
		$rez=$bd->update('variables',array('value'=>$v),array('name'=>$k));
	}
	if($rez){
		$suc="Сохранено";
		include('../elements/suc.php');
	}
}
include('../elements/form_vars.php');
?>