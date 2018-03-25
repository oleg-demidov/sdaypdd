<?
if(isset($_GET['id'])){
	$res=$bd->delete_str('content',array('id'=>$_GET['id']));
	for($i=0;$i<3;$i++){
		$res=$bd->delete_str('content_en',array('id'=>$_GET['id'].$i));
		$res=$bd->delete_str('content_ru',array('id'=>$_GET['id'].$i));
	}
	if($res){
		$suc="Контент удален";
		include('../elements/suc.php');
	}
}
include('content.php');
?>