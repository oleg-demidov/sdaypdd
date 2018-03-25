<?php

if(!isset($_SESSION['user']['id'])){
    $lastId = $bd->get_row("SELECT `id` FROM `users` ORDER BY `id` DESC LIMIT 1");
    $_SESSION['user'] = array(
        'id' => rand($lastId['id']+100, 429496729),
        'avatar50' => 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg',
        'avatar100' => 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava100.jpg',
        'first_name' => 'Гость',
        'last_name' => '',
        'email' => '',
        'first_active' => time(),
        'type' => 'ab',
        'comm' => 0,
        'donate' => 0,
        'gost' => 1
    );
}
