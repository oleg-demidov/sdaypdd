<?
echo"reg.php";
include('bd_adv_game.php');
$error;
function check_form(){
	global $error;
	if(!$_POST['name']){
		$error='Поле "Имя" не заполнено!';
		return false;
	}
	if(!$_POST['email']){
		$error='Поле "email" не заполнено!';
		return false;
	}else if(!preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $_POST['email'])){
		$error='Некорректный email адрес! ';
		return false;
	}
	if(!$_POST['pass']){
		$error='Поле "Пароль" не заполнено!';
		return false;
	}
	if($_POST['pass2']!=$_POST['pass']){
		$error='Пароли не совпадают!';
		return false;
	}
	return true;
}
function check_injeck(){
	global $error;
	$reg="/(select|update|insert|\?|\!|\'|\")/i";
	if(preg_match($reg,$_POST['name'])){
		$error.='<br>В поле Имя обнаружен недопустимый символ';
		return false;}
	if(preg_match($reg,$_POST['email'])){
		$error.='<br>В поле Email обнаружен недопустимый символ';
		return false;}
	if(preg_match($reg,$_POST['pass'])){
		$error.='<br>В поле Пароль обнаружен недопустимый символ';
		return false;}
	return true;
}
function check_email($email,$echo=0){
	global $error;
	$query="SELECT `email` FROM `users` WHERE `email`='".$email."'";
	$query = mysql_query($query);
	$nr=mysql_num_rows($query);
	if($nr)$error.="Такой email уже зарегестрирован";
	if($echo)echo $error;
	return $nr;
}
function save_user(){
	global $error;
	global $db;
	$query="INSERT INTO `users` (`email`,`pass`,`name`,`md5`,`type`) VALUES ('".$_POST['email']."','".$_POST['pass']."','".$_POST['name']."','".md5($_POST['pass'])."','".$_POST['type']."')";
	$query = mysql_query($query);
	if(!$query)$error.="Ошибка! ". mysql_error($db).". Попробуйте позже";
	return $query;
}
function send_reg_mail(){
	$query="SELECT * FROM `mess_email` WHERE `id`='reg_check'";
	$query = mysql_query($query);
	if($query){
		$query=mysql_fetch_row($query);
		$subject = $query[0];
		$message=str_replace ( "regmd5",md5($_POST['pass']), $query[2]);
		$message=str_replace ( "emailll",$_POST['email'], $message);
		$message=" <html><head><title>".$query[2]."</title></head> 
				<body><h4>".$query[2]."</h4>".$message."</body></html>";
	}else{
		$subject = "Ошибка подтверждения";
		$message = " <html><head><title>Ошибка подтверждения</title></head><body><h4>Ошибка подтверждения</h4><p>Извините. Ошибка сервера. Ведутся технические работы. Попробуйте позже</p></body></html>";
	}
	$headers = "From: ADVGame.com <support@adv-game.com>\r\n";
    $headers .= "Content-type: text/html; charset=utf-8 \r\n";
	mail($email_r, $subject,$message, $headers);
}
?>