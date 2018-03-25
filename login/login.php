<?php 

include('scr/auth_func.php');
$data_user=autorization();
//if($data_user){
	include("g_elements/panel_login.php");
//}else{
	include("g_elements/form_login.php");
//}
?>