<?php
define('TIMEAUTHMIN',30);
function autorization(){
	$data = check_login_data();
	if($data) $_SESSION['user'] = $data;
	write_hash($data);
	return $data;
}
function check_login_data(){
	global $bd;
	global $error;
	if(isset($_GET['logout'])){
		$_SESSION['user'] = array();
		return false;
	}
	if(isset($_GET['id']) && isset($_GET['pass']) && $_GET['pass'] != '%')
		return get_user_by_id_pass($_GET['id'], $_GET['pass']);
	if(isset($_SESSION['user']['id']))
		return get_user_by_id($_SESSION['user']['id']);
	if(!isset($_POST['email']))
		return false;
	
	if(!$data=get_user_by_email($_POST['email'])){
		$error="Нет такой учетной записи";
		return false;
	}
	
	if($data['pass']!=$_POST['pass']){
		$error.="Не верный пароль";
		return false;
	}
	return $data;
}
function set_avatar(&$data){
	$src = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava';
	if($data['avatar50'] == '') $data['avatar50'] = $src.'50.jpg';
	if($data['avatar100'] == '') $data['avatar100'] = $src.'100.jpg';
}
function get_user_by_id_pass($id, $pass = ''){
	global $bd;
	$data = $bd->get_row("SELECT `users`.`id`, `users`.`email`, `users`.`name`, `users`.`pass`, `users`.`donate`, `social`.`uid`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100` FROM `users` LEFT JOIN `social` ON `users`.`id` = `social`.`id_user` WHERE `users`.`id`=? AND `users`.`pass`=?",array($id, $pass));
	set_avatar($data);
	return $data;
}
function get_user_by_id($id){
	global $bd;
	$data =  $bd->get_row("SELECT `users`.`id`, `users`.`email`, `users`.`name`, `users`.`pass`, `users`.`donate`, `social`.`uid`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100` FROM `users` LEFT JOIN `social` ON `users`.`id` = `social`.`id_user` WHERE `users`.`id`=?",array($id));
	set_avatar($data);
	return $data;
}
function get_user_by_email($email){
	global $bd;
	$data = $bd->get_row("SELECT `users`.`id`, `users`.`email`, `users`.`name`, `users`.`pass`, `users`.`donate`, `social`.`uid`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100` FROM `users` LEFT JOIN `social` ON `users`.`id` = `social`.`id_user` WHERE `users`.`email`=?",array($email));
	set_avatar($data);
	return $data;
}
function write_hash($data){
	global $error;
	$ip=$_SERVER['REMOTE_ADDR'];
	$hash=md5($data['id'].$data['pass'].$ip);
	echo'sess-',$data['id'];
	if(!setcookie($data['id'].'',$hash,(time()+(TIMEAUTHMIN+2)*60),'/')){
		$error="Невозможно записать COOKIE";
		return false;
	}
	return true;
}
function check_hash($data){
	//print_r($data);
	global $error;
	if(!isset($data['id']))
		return false;
	$ip=$_SERVER['REMOTE_ADDR'];
	$hash=md5($data['id'].$data['pass'].$ip);
	if(!isset($_COOKIE[$data['id']])||$_COOKIE[$data['id']]!=$hash){
		$_SESSION['error']="Время авторизации истекло";
		return false;
	}
	return true;
}

?>