<?php

include('../scr/bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);

$data =  $bd->get_row("SELECT `post` FROM `social` WHERE `soc`=? AND `uid`=?",array($_GET['soc'], $_GET['uid']));
//print_r($data);
if(!isset($data['post']))
    $data['post'] = 'no';
echo json_encode($data);
