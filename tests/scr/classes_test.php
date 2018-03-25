<?	
	session_start();
	error_reporting(E_ALL);
	include('../../scr/bd.inc.php');		// подключение базы SQL
	$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
	include('func.php');

?>