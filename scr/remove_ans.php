<?php
include ('remove_cod.php');
include('bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
if($cod == $_GET['cod']){
    $bd->sql("DELETE FROM `answers` WHERE `id`=?",array($_GET['id']));
}
