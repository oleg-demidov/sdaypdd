<?php
	session_start();
	include ('yandex.php');
	$client = new OAuthClient('d5a775e4526942778e8ea8452ca5d54a', 'a30e76246e4445f6a41230c677a74c12');
	if(!isset($_GET['code'])){
		$client->authRedirect(true);
		exit();
	}
	$client->requestAccessToken($_GET['code']);
	$data = $client->getUserInfo();
	$user_data = array(
		'uid' => $data->id,
		'first_name' => $data->first_name,
		'last_name' => $data->last_name,
		'email' => $data->emails[0],
		'avatar50' => 'https://avatars.yandex.net/get-yapic/'.$data->default_avatar_id.'/islands-50',
		'avatar100' => 'https://avatars.yandex.net/get-yapic/'.$data->default_avatar_id.'/islands-retina-50'
	);
	print_r($user_data);
?>