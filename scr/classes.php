<?php	
	session_start();
        //print_r($_SESSION['user']);
	error_reporting(E_ALL);
	
	include('bd.inc.php');		// подключение базы SQL
	$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
	
	$hh = $_SERVER['HTTP_HOST'];
	
	include('functions.php');
        include('gosts.php');
	//include('detect.php');
	

?>