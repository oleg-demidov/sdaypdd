<?
if(isset($_GET['l'])&&!isset($_POST['value0'])){
	$whe="`id`='".$_GET['id']."0' OR `id`='".$_GET['id']."1' OR `id`='".$_GET['id']."2'";
	$data=$bd->select('*','content_'.$_GET['l'],$whe);
	include('../elements/form_cont_cfg.php');
}else{
	if(isset($_POST['value0'])){
		for($i=0;$i<3;$i++){
			if(isset($_POST['value'.$i])=='')
				continue;
			$dataen=array(
				'id'=>$_GET['id'].$i,
				'value'=>$_POST['value'.$i]
			);
			$bd->insert_on_update('content_'.$_GET['l'],array('id'=>$_GET['id'].$i,'value'=>$_POST['value'.$i]));
		}
		
	}
	include('content.php');
}
?>