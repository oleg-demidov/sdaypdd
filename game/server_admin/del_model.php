<?
	if(isset($_GET['ok'])){
		//$bd->delete_str('servers',array("id"=>$_GET['s']));
		//$bd->delete_str('buy_flags',array("id_server"=>$_GET['s']));
		$bd->delete_str('models',array("id_server"=>$_GET['model']));
		//header("Location: http://".$_SERVER['HTTP_HOST']."/server_admin/index.php?a=my_servers");
	}else{
		$data=$bd->select('*','models',array("id"=>$_GET['model']));
		include('../elements/del_model.php');
	}
?>