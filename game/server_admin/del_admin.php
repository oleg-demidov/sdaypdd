<?
		$bd->delete_str('admins',array("id"=>$_GET['admin']));
		include('admins.php');
	
?>