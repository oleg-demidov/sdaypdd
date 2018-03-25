<?php
session_start();
error_reporting(E_ALL);
include('vk.php');
echo 'vk';
$config = array(
        'id' => '4861527',
        'secret' => 'BRgPDIH1D1m9ey280eP6',
        'callback' => 'http://'.$_SERVER['HTTP_HOST'].'/oauth/vk/auth.php'
);
$vk = new VK($config['id'], $config['secret']);
$vk->setAccessToken($_SESSION['user']['token']);
$data = $vk->api('wall.post', array(
                        'message'   =>'Подготовься к экзамену в ГИБДД вместе с друзьями sdaypdd.ru',
                        'attachments' => 'photo40551223_456239028'));
print_r($data);


