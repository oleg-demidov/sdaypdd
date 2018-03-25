<?php
	session_start();
	$config = array(
		'id' => '732214',
		'secret' => 'f0f31e1542e98660414310b967b89390',
		'private' => '1c23fa4edfef4ac296f7f322db2dfeac',
		'callback' => 'http://'.$_SERVER['HTTP_HOST'].'/oauth/mm/auth.php'
	);
	include ('mail.php');
	$client = new MAIL($config);
	
	if(!isset($_GET['code'])){
		$client->authRedirect(true);
		exit();
	}
	$client->getAccessToken($_GET['code']);
	$data = $client->api('users.getInfo');
	$user_data = array(
		'uid' => $data[0]->uid,
		'first_name' => $data[0]->first_name,
		'last_name' => $data[0]->last_name,
		'email' => $data[0]->email,
		'avatar50' => $data[0]->pic_50,
		'avatar100' => $data[0]->pic_128
	);
	
	include('../../scr/bd.inc.php');		// подключение базы SQL
	$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
	include('../func_auth.php');
	include('../../scr/func_reg.php');
	social_enter($data);
	//print_r($user_data);

?>