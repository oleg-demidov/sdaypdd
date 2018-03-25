<?php
include('../../scr/bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);

function social_enter($data){
    global $bd;
    if(!$user = check_uid($data['soc'], $data['uid'])){
        $user = create_user($data['email'], $data['first_name'], rand(9999,9999999999));
        
        if(isset($_SESSION['user']['gost']))
            export_results($_SESSION['user']['id'], $data['uid']);
    }
    //print_r($user);
    $data['id_user'] = $user['id'];
    $bd->insert_on_update('social', $data);
    
    //(isset($user['post']) && $user['post']=='no')
    login_social($user['id']);
    wall_post($data['id_user'], $data['soc']);
    include('../close_window.php');
    exit();
}

function wall_post($id, $soc){
    global $bd;
    $data = $bd->get_row("SELECT `post` FROM `social` WHERE `id_user` = ? AND `soc` = ?", array($id, $soc));
    if($data['post'] == 'no'){
        $url = 'http://sdaypdd.loc/index.php?a=obuchenie';
        header("Location: http://".$_SERVER['HTTP_HOST'].'/oauth/'.$soc."/wall_post.php?pars=".$_SESSION['pars']);
        exit();
    }
}

function create_user($email, $name, $pass){
    global $bd;
    ///echo $email, $name, $pass;
    $bd->get_row("INSERT INTO `users` SET  `email`=?, `name`=?, `pass`=?, `donate`=?", 
    array(
            $email,
            $name,
            $pass,
            (time()+60*30))
    );
    return $bd->get_row("SELECT `users`.`id`, UNIX_TIMESTAMP(`users`.`first_active`) AS `first_active`, `users`.`email`, `users`.`name`, `users`.`pass`, `users`.`donate`, `social`.`uid`, `users`.`type`, `users`.`comm`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100` FROM `social` LEFT JOIN `users` ON `social`.`id_user` = `users`.`id` WHERE `users`.`id` = LAST_INSERT_ID()");
}
function login_social($id){
    $data = get_user_by_id($id);
    $_SESSION['user']= $data;
    
    //if($h)header("Location: ".$url);
}
function get_user_by_id($id){
	global $bd;
	$data =  $bd->get_row("SELECT `users`.`id`, UNIX_TIMESTAMP(`users`.`first_active`) AS `first_active`, `users`.`email`, `users`.`name`, `users`.`pass`, `users`.`donate`, `social`.`uid`, `users`.`type`, `users`.`comm`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100` FROM `users` LEFT JOIN `social` ON `users`.`id` = `social`.`id_user` WHERE `users`.`id`=?",array($id));
	set_avatar($data);
	return $data;
}

function check_uid($soc, $uid){
    global $bd;
    $user = $bd->get_row("SELECT `users`.`id`, UNIX_TIMESTAMP(`users`.`first_active`) AS `first_active`, `users`.`email`, `users`.`name`, `users`.`pass`, `users`.`donate`, `social`.`uid`, `users`.`type`, `users`.`comm`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100`, `social`.`post` FROM `social` LEFT JOIN `users` ON `social`.`id_user` = `users`.`id` WHERE `social`.`uid`=? AND `social`.`soc` = ?", array($uid,$soc));
    //echo $bd->error;
    return (isset($user['id']))?$user:false;
}
function export_results($from, $to){
    global $bd;
    return $bd->sql("UPDATE `t_results` SET `id_user`=? WHERE `id_user`=? ", array($to, $from));
}
function set_avatar(&$data){
	$src = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava';
	if($data['avatar50'] == '') $data['avatar50'] = $src.'50.jpg';
	if($data['avatar100'] == '') $data['avatar100'] = $src.'100.jpg';
} 
?>