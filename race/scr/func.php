<?php
session_start();
include('../../scr/bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
?>