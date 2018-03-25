<?
$bd->sql("DELETE FROM `t_que` WHERE `t_que`.`id`=?",array($_GET['id']));
$bd->sql("DELETE FROM `t_ans` WHERE `t_ans`.`id_que`=?",array($_GET['id']));
echo $bd->error;
include('tests.php');
?>