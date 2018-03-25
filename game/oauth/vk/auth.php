<?php
	session_start();
	error_reporting(E_ALL);
	$config = array(
		'id' => '4861527',
		'secret' => 'BRgPDIH1D1m9ey280eP6',
		'callback' => 'http://'.$_SERVER['HTTP_HOST'].'/oauth/vk/auth.php'
	);
	include('vk.php');
	$vk = new VK($config['id'], $config['secret']);
	
	if(!isset($_GET['code'])){
		header("Location: ".$vk->getAuthorizeUrl('',$config['callback']));
		exit();
	}
	if(isset($_GET['code']))
		$vk->getAccessToken($_GET['code'], $config['callback']);
		
	$data = $vk->api('users.get', array(
			'uids'   => $vk->getUserUid(),
			'fields' => 'first_name,last_name,sex,photo_100,photo_50'));
	$user_data = array(
		'uid' => $data['response'][0]['uid'],
		'first_name' => $data['response'][0]['first_name'],
		'last_name' => $data['response'][0]['last_name'],
		'email' => $vk->auth_user_email,
		'avatar50' => $data['response'][0]['photo_50'],
		'avatar100' => $data['response'][0]['photo_100'],
		'soc' =>'vk'
	);
	include('../../scr/bd.inc.php');		// подключение базы SQL
	$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
	include('../func_auth.php');
	include('../../scr/func_reg.php');
	social_enter($data);

?>