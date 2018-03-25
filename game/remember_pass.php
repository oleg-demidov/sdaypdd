<?php 
session_start();
include('scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
$content_sp=get_cont($lang,'save_pass');
$title=content('title_parol');
include("elements/head.php");
include("elements/menu.php");
?>
<div class="menu2">
<? include("elements/menu2index.php");?>
</div>
 <div class="content">
<?
$bd->delete_str("email_kods", "`time`<'".time()."'");
function check_kod(){
	if(!isset($_GET['kod']))
		return false;
	global $bd;
	global $error;
	$kod_check=$bd->select("*","email_kods",array('kod'=>$_GET['kod']));
	if(!$kod_check){
		$error=content('error_parol');
		return false;
	}
	return $kod_check;
}
function isset_email(){
	if(!isset($_GET['email']))
		return false;
	global $bd;
	global $error;
	global $content_sp;
	$id_user=$bd->select("*","users",array('email'=>$_GET['email']));
	if($id_user)
		return $id_user;
	else{
		$error=content('no_email',$content_sp);
		return false;
	}
}
function generate_kod($length){
	$chars = 'ABDEFGHKNQRSTYZabcdefghijklmnopqrstuvwxyz0123456789';
	$numChars = strlen($chars);
	$string = '';
	for ($i = 0; $i < $length; $i++) {
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	}
	return $string;
}
function check_pass(){
	global $error;
	global $content_sp;
	if(!isset($_POST['pass1'])&&!isset($_POST['pass2']))
		return false;
	if($_POST['pass1']==''||$_POST['pass2']==''){
		$error=content('error_parol_pust',$content_sp);
		return false;
	}
	if($_POST['pass1']!=$_POST['pass2']){
		$error=content('error_parol_neravni',$content_sp);
		return false;
	}
	return true;
}

if($id_user=isset_email()){
	$kod=generate_kod(90);//отправка кода
	$bd->insert_on_update("email_kods",array('kod'=>$kod,'id_user'=>$id_user[0]['id'],'time'=>time()+60*5));
	include('scripts/email_send_pass.php');
}else{
	if($user=check_kod()){
		if(check_pass()){
			$bd->update("users",array('pass'=>$_POST['pass1']),array('id'=>$user[0]['id_user']));
			$bd->delete_str("email_kods", array('id_user'=>$user[0]['id_user']));
			$suc=content('parol_save');
			include('elements/suc.php');
		}else{
			if($error)include('elements/errors.php');
			include('elements/form_change_pass.php');
		}
		
	}else{
		if($error)include('elements/errors.php');
		include('elements/form_remember_pass.php');
	}
}


?>
 </div>
 <? include('elements/footer.php')?>
</body>
</html>