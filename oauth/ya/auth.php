<?php
	session_start();
	function redirect($mess = ''){
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php?a=login&err_soc=1&m='.$mess);
		exit();
	}
	include ('yandex.php');
	$client = new OAuthClient('d5a775e4526942778e8ea8452ca5d54a', 'a30e76246e4445f6a41230c677a74c12');
	if(!isset($_GET['code'])){
		if(isset($_GET['error']))
			redirect('not_code');
		$client->authRedirect(true);
		exit();
	}
	if(!$client->requestAccessToken($_GET['code']))
			redirect('no_token');
	$data = $client->getUserInfo();
	if(isset($data->error))
			redirect('no_access_api');
	$user_data = array(
		'uid' => $data->id,
		'first_name' => $data->first_name,
		'last_name' => $data->last_name,
		'email' => $data->emails[0],
		'avatar50' => 'https://avatars.yandex.net/get-yapic/'.$data->default_avatar_id.'/islands-50',
		'avatar100' => 'https://avatars.yandex.net/get-yapic/'.$data->default_avatar_id.'/islands-retina-50',
		'soc' => 'ya'
	);
	include('../func_oauth.php');
	social_enter($user_data);
?>