<?php 
session_start();
include('scripts/bd.inc.php');
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
include('scripts/reg.inc.php');
$new_reg=new REG($user_bd,$pass_bd,$bd_custom,$host_bd);
$title=content('registration');
include("elements/head.php");
include("elements/menu.php");
?>
<div class="menu2">
<? include("elements/menu2index.php");?>
</div>
<?
$error='';



echo'<div class="content">';
if($new_reg->create_user()){
	$email=$_POST['email'];
	$id_user=$new_reg->id;
	include('scripts/email_send_kod.php');//здесь отсылка сообщения на почту
	include('elements/form_email_check.php');
}else{
	if(($error=$new_reg->error)!='')include("elements/errors.php");
	include("elements/form_reg.php");
}
echo'</div>';
?>
</body>
</html>