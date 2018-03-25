<?php
    session_start();
    if(isset($_GET['pars']))
        $_SESSION['pars'] = $_GET['pars'];
    if(isset($_GET['back']))
        $_SESSION['back'] = $_GET['back'];
    function redirect($mess = ''){
            header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php?a=login&err_soc=1&m='.$mess);
            exit();
    }
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
            if(isset($_GET['error']))
                    redirect('not_code');
            header("Location: ".$ok->getLoginUrl(array('VALUABLE ACCESS')));
            exit();
    }	
    $token = $ok->getToken($_GET['code']);
    if(!$ok->auth)
            redirect('no_token');

    $data = $ok->api('users.getCurrentUser');
    if(isset($data->error))
            redirect('no_access_api');
    
    $user_data = array(
        'uid' => $data['uid'],
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => '',
        'avatar50' => $data['pic_1'],
        'avatar100' => $data['pic_2'],
        'soc' => 'ok',
        'token' =>  $token
    );
    //print_r($user_data);
    include('../func_oauth.php');
    social_enter($user_data);
?>