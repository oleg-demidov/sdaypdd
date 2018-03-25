<?
session_start();
include('../scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd);

include('../scripts/autorization.php');
if(check_hash(&$bd))write_hash(&$bd,&$_SESSION);									// проверка клиента
else header("Location: http://".$_SERVER['HTTP_HOST']."/login.php?logout=1");

if(isset($_POST)){
	$rez=$bd->update('users',$_POST,array('id'=>$_GET['id_user']));
}
header("Location: http://".$_SERVER['HTTP_HOST']."/adminka/index.php?a=users&limit_u=".$_GET['lim']);
?>