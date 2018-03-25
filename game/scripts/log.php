<?
function check_user(){
	$logObj=new REG($user_bd,$pass_bd);
	global $user;
	global $error;
	$user=mysql_query("SELECT * FROM `users` WHERE `email`='".$_POST['email']."' LIMIT 0,1");
	if(mysql_num_rows($user)){
		$user=mysql_fetch_array($user);
		print($user);
		if(!$user['pass']==$_POST['pass']){
			$error="Не верный пароль";
			return false;
		}
		if($user['ban']){
			$error="Ваша учетная запись заблокирована";
			return false;
		}
	}else{
		$error="Не верный email";
		return false;
	}
	return true;
}
function login_user(){
	global $user;
	$_SESSION['email']=$user['email'];
	$_SESSION['name']=$user['name'];
	$_SESSION['balans']=$user['balans'];
	$_SESSION['type']=$user['type'];
}
?>