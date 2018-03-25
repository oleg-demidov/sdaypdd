<?php
	session_start();
	include('odnoklassniki.php');
	$ok = new Social_APIClient_Odnoklassniki(
		array(
			'client_id' => '1132797440',
			'application_key' => 'CBAPBGIEEBABABABA',
			'client_secret' => '874877CAFF43BB7F842C3F1C'
		)
	);
	$ok->setRedirectUrl('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
	if(!isset($_GET['code'])){
		header("Location: ".$ok->getLoginUrl(array('VALUABLE ACCESS', 'SET STATUS')));
		exit();
	}	
	$ok->getToken($_GET['code']);
	$data = $ok->api('users.getCurrentUser');
	$user_data = array(
		'uid' => $data['uid'],
		'first_name' => $data['first_name'],
		'last_name' => $data['last_name'],
		'email' => 0,
		'avatar50' => $data['pic_1'],
		'avatar100' => $data['pic_2']
	);
	print_r($user_data);
?>