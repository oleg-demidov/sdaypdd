<?
$bd->sql("DELETE FROM `pdd` WHERE `pdd`.`id`=?",array($_GET['id']));
?>