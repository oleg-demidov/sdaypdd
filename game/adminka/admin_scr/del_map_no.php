<?
if(isset($_GET['id'])){
	$res=$bd->delete_str('maps_no',array('id'=>$_GET['id']));
	if($res){
		$suc="Контент удален";
		include('../elements/suc.php');
	}
}
include('maps_no.php');
?>