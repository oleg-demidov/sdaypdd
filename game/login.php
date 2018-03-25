<? 
session_start();
$error='';

if(isset($_SESSION['error'])){
	$error=$_SESSION['error'];
	unset($_SESSION['error']);
}

include('scripts/bd.inc.php');
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
include('scripts/autorization.php');
if($data=autorization($bd)){
	if(!$data['activ']){
		header("Location: ".$host_lang."/email_check.php?email=".$data['email']."&id_user=".$data['id']);
	}else{
		write_hash($bd,$data);
		$_SESSION=array_merge($_SESSION,$data);
		header("Location: ".$host_lang."/".$_SESSION['type']."/");
	}
}
else{
	$title="Вход";
	include("elements/head.php");
	include("elements/menu.php");
	?>
	<div class="menu2">
	<? include("elements/menu2index.php");?>
	</div><div class="content">
	<?
	if($error!='')include("elements/errors.php");
	include("elements/form_login.php");
	echo'</div>';
}
?>
</body>
</html>