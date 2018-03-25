<?
$bd->sql("DELETE FROM `users` WHERE `id`=?",array($_GET['id']));
$bd->sql("DELETE FROM `social` WHERE `id_user`=?",array($_GET['id']));
$bd->sql("DELETE FROM `t_results` WHERE `id_user`=?",array($_GET['id']));
$bd->sql("DELETE FROM `race_stat` WHERE `id_user`=?",array($_GET['id']));
echo $bd->error;
include('users.php');
?>