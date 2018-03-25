<?php 
session_start();
include('scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
$title="Игровая биржа баннеров";
include("elements/head.php");
include("elements/menu.php");
include("elements/menu2index.php");
?>
 <div class="content">
<?
function check_kod(){
	if(!isset($_GET['kod']))
		return false;
	global $bd;
	$kod_check=$bd->select("*","email_kods",array('kod'=>$_GET['kod']));
	if(!$kod_check)
		return false;
	$bd->update("users",array('activ'=>'1'),array('id'=>$kod_check[0]['id_user']));
	$bd->delete_str("email_kods",array('kod'=>$_GET['kod']));
	return true;
}
if(!check_kod()){
	$email=$_GET['email'];
	$id_user=$_GET['id_user'];
	if(isset($_GET['send_kod']))
		include('scripts/email_send_kod.php');
	include('elements/form_email_check.php');
}else{
	$suc="Ваш email успешно подтвержден. Можете продолжать пользоваться сервисом.";
	include('elements/suc.php');
	echo'<a href="'.$host_lang.'/login.php">Продолжить</a>';
}
?>
 </div>
 <? include('elements/footer.php')?>
</body>
</html>
