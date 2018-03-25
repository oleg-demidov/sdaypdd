<?php
function create_user($email, $name, $pass){
	global $bd;
        ///echo $email, $name, $pass;
	$bd->get_row("INSERT INTO `users` SET  `email`=?, `name`=?, `pass`=?, `donate`=?", 
	array(
		$email,
		$name,
		$pass,
		(time()+60*30))
	);
	return $bd->get_row("SELECT * FROM `users` WHERE `id` = LAST_INSERT_ID()");
}
function check_email($email){
	global $bd;
	return $bd->get_row("SELECT `id`, `pass` FROM `users` WHERE `email`=?", array($email));
}
function check_reg_data(){
	global $error;
	
	if(!isset($_POST['email'])){
		$error="Поле Email не заполнено";
		return false;
	}
	$user = check_email($_POST['email']);
	if(isset($user['id'])){
		$error="Такой Email зарегистрирован";
		return false;
	}
	if(!check_email_valid($_POST['email'])){
		$error="Поле Email заполнено не верно";
		return false;
	}
	if($_POST['pass'] == '' || $_POST['pass2'] == ''){
		$error="Пароль не заполнен";
		return false;
	}
	if($_POST['pass2'] != $_POST['pass']){
		$error="Пароли не совпадают";
		return false;
	}
	if (empty($_POST['captcha']) || trim(strtolower($_POST['captcha'])) != $_SESSION['capaword']) {
        $error = "Не верная captcha";
        return false;
    }
	return true;
}
function check_email_valid($email){
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>