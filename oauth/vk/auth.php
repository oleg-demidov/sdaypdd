<?php
        session_start();
        if(isset($_GET['pars']))
            $_SESSION['pars'] = $_GET['pars'];
        if(isset($_GET['back']))
           $_SESSION['back'] = $_GET['back'];
        error_reporting(E_ALL);
        function redirect($mess = ''){
                header('Location: http://'.$_SERVER['HTTP_HOST'].'/index.php?a=login&err_soc=1&m='.$mess);
                exit();
        }
        $config = array(
                'id' => '4861527',
                'secret' => 'BRgPDIH1D1m9ey280eP6',
                'callback' => 'http://'.$_SERVER['HTTP_HOST'].'/oauth/vk/auth.php'
        );
        include('vk.php');
        $vk = new VK($config['id'], $config['secret']);

        if(!isset($_GET['code'])){
                if(isset($_GET['error']))
                        redirect('not_code');
                //echo $vk->getAuthorizeUrl('wall',$config['callback']);
                header("Location: ".$vk->getAuthorizeUrl('wall',$config['callback']));
                exit();
        }
        if(isset($_GET['code']))
                $token =$vk->getAccessToken($_GET['code'], $config['callback']);
        if(!$vk->auth)
                redirect('no_token');	
        $data = $vk->api('users.get', array(
                        'uids'   => $vk->getUserUid(),
                        'fields' => 'first_name,last_name,sex,photo_100,photo_50'));
        if(isset($data->error))
                redirect('no_access_api');
        $user_data = array(
                'uid' => $data['response'][0]['uid'],
                'first_name' => $data['response'][0]['first_name'],
                'last_name' => $data['response'][0]['last_name'],
                'email' => (isset($vk->auth_user_email))?$vk->auth_user_email:'',
                'avatar50' => $data['response'][0]['photo_50'],
                'avatar100' => $data['response'][0]['photo_100'],
                'soc' => 'vk',
                'token' =>  $token,
                'post' => isset($_SESSION['post'])?$_SESSION['post']:'no'
        );
        //print_r($user_data);
        include('../func_oauth.php');
        social_enter($user_data);
?>