<?php
include('auth_func.php');
if(check_hash($_SESSION['user']) && $_SESSION['user']['id'] ==5){
	echo'fgfdg';
	write_hash($_SESSION['user']);
}
echo $_SESSION['error'];
//else header("Location: http://".$_SERVER['HTTP_HOST']."/index.php?a=login&logout=1");

?>