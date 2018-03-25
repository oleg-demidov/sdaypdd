<?
if(isset($_GET['id_user']))$bd->delete_str('users',array('id'=>$_GET['id_user']));
include("admin_scr/users.php");
?>