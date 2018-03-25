<?
	if(isset($_GET['ok'])){
		$login=array();
		for($i=0;$i<7;$i++)$login[$i]=chr(97+($_GET['s']>>(4*$i)&15));
		$login=implode($login);
		$bd->delete_str('servers',array("id"=>$_GET['s']));
		$bd->delete_str('privileges',array("id_server"=>$_GET['s']));
		$bd->delete_str('offers',array("id_server"=>$_GET['s']));
		$bd->delete_str('admins',array("id_server"=>$_GET['s']));
		$bd->delete_str('promo_keys',array("id_server"=>$_GET['s']));
		/*$query="REVOKE SELECT  ON adv_game.admins FROM '".$login."'@'".$sIp[0]['ip']."';";
		$bd->sql_query($query);
		$query=" REVOKE INSERT  ON adv_game.shows_now FROM '".$login."'@'".$sIp[0]['ip']."';";
		$bd->sql_query($query);
		$query=" REVOKE SELECT  ON adv_game.maps FROM '".$login."'@'".$sIp[0]['ip']."';";
		$bd->sql_query($query);
		$query="DELETE FROM mysql.user WHERE user = '".$login."';";
		$bd->sql_query($query);*/
		$suc=content('server_removed',$content_sa);
		include('../elements/suc.php');
		include('my_servers.php');
	}else{
		$data=$bd->select('*','servers',array("id"=>$_GET['s']));
		include('../elements/del_server.php');
	}
?>