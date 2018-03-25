<?php
	session_start();
        if(isset($_GET['post'])){
            $_SESSION['wallpost'] = $_GET['post'];
        }
        if(!isset($_SESSION['wallpost'])){
            $_SESSION['wallpost'] = 'no';
        }
	function redirect($mess = ''){
		header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php?a=login&err_soc=1&m='.$mess);
		exit();
	}
	$config = array(
		'id' => '732214',
		'secret' => 'f0f31e1542e98660414310b967b89390',
		'private' => '1c23fa4edfef4ac296f7f322db2dfeac',
		'callback' => 'http://'.$_SERVER['HTTP_HOST'].'/oauth/mm/auth.php'
	);
	include ('mail.php');
	$client = new MAIL($config);
	
	if(!isset($_GET['code'])){
		if(isset($_GET['error']))
			redirect('not_code');
		$client->authRedirect(true);
		exit();
	}
	$tok = $client->getAccessToken($_GET['code']);
	
	if(!$client->auth)
		redirect('no_token');
	//print_r($tok);
	$data = $client->api('users.getInfo');
	
	if($data->error)
		redirect('no_access_api');
	$user_data = array(
		'uid' => $data[0]->uid,
		'first_name' => $data[0]->first_name,
		'last_name' => $data[0]->last_name,
		'email' => $data[0]->email,
		'avatar50' => $data[0]->pic_50,
		'avatar100' => $data[0]->pic_128,
		'soc' => 'mm',
                'post' => $_SESSION['wallpost']
	);
	//echo'==';print_r($user_data);
	include('../func_oauth.php');
	social_enter($user_data);

?>