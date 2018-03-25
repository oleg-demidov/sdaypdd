<?php
include('scr/auth_func.php');
include('scr/func_reg.php');
if($_POST){
	if(check_reg_data()){
		$name = (isset($_POST['name']))?$_POST['name']:'';
		$userid = create_user($_POST['email'], $name, $_POST['pass']);
                //print_r($userid);
		$data_user = get_user_by_id($userid['id']);
		$_SESSION['user'] = $data_user;
		include("g_elements/panel_login.php");
	}else include("g_elements/form_reg.php");
}else
	include("g_elements/form_reg.php");
?>
