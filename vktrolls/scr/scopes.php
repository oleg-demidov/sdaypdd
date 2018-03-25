<?php
$id_user = 0;
$error = 0;
function checkUserAccess(){
    global $id_user;
    if(!isset($_GET['access_token']))
        return 0;
    if(($scope = get_scope_token($_GET['access_token'])) !== false)
        return $scope;
    //echo ' no to db';
    if(!setTokenServer())
        return 0;
    //echo ' token tru ';
    if(!$id_user = get_uid()){
        return 0;
    }
    if(checkInList($id_user)){
        return 0;
    }
    $scope = 1;
    //echo ' no to list ';
   //echo $id_user;
    if(!setAdminToken())
        return 0;
    /*if(!is_member($id_user))
        return 0;*/
    
    if(!$role = is_admin())
        return $scope;
    //echo ' admin ';
    $scope = getScopeRole($role);
    return $scope;
}
function checkInList($id_user){
    global $db;
    $data = $db->getAll("SELECT * FROM `agents` WHERE `id` = ?s AND `basket` = 0", $id_user.'');
    if(isset($data[0]))
        return true;
    return false;
}
function sendNotif($notif){
    global $vk;
    if(!setAdminToken())
        return false;
    $data = $vk->api("groups.getMembers", array(
        'group_id'=>GROUP,
        'filter'=>"managers")
    );
    if(isset($data['error']))
        return false;
    if(!setTokenServer())
        return false;
    $ids = '';
    for($i=0; sizeof($data['response']['users'])>$i; $i++){
        $ids .= $data['response']['users'][$i]['id'].',';
    }
    $data = $vk->api("secure.sendNotification", array(
        'user_ids'=>$ids,
        'message'=>$notif)
    );
    return $data;
}
function setTokenServer(){
    global $vk;
    if(!$token_server = getTokenDb()){
        if(!$token_server = $vk->getAccessToken())
            return false;
        setTokenDb($token_server);
    }else{
        $vk->setServerToken($token_server);
    }
    return TRUE;
}
function setAdminToken(){
    global $vk;
    if(!$token_server = getTokenDb('admin_token'))
        return false;
    $vk->setUserToken($token_server);
    return TRUE;
}
function getTokenDb($key = 'token_server'){
    global $db;
    $data = $db->getAll("SELECT * FROM `vars` WHERE `key` = ?s", $key);
    if(isset($data[0]['value']))
        return $data[0]['value'];
    return false;
}
function setTokenDb($token){
    global $db;
    $data = array('key' => 'token_server', 'value' => $token, 'time' => time());
    $sql  = "INSERT INTO `vars` SET ?u ON DUPLICATE KEY UPDATE ?u";
    return $db->query($sql,$data,$data);
}
function is_admin(){
    global $vk;
    $data = $vk->api("groups.getMembers", array(
        'group_id'=>GROUP,
        'filter'=>"managers")
    );
    //echo json_encode($data);
    if(isset($data['error']))
        return false;
    return getRole($data['response']['users']);
}
function getRole($data){
    global $id_user;
    $sd = sizeof($data);
    for($i=0; $i<$sd; $i++){
        if($data[$i]['id']==$id_user){
            return $data[$i]['role'];
        }
    }
    return false;
}
function getScopeRole($role){
    $roles = array(
        'moderator' => 2,
        'editor' => 2,
        'administrator' => 3,
        'creator' => 3
    );
    return $roles[$role];
}
function get_uid(){
    global $scope_desc;
    global $vk;
    global $error;
    $data = $vk->api("secure.checkToken", array('token' => $_GET['access_token']));
    //echo'<br>uid? ';
    //echo(json_encode($data));
    if(isset($data['error'])){
        $error = $data['error']['error_code'];
        if($error == 5){
            del_var('token_server');
        }
        return false;
    }
    return $data['response']['user_id'];        
}
function del_var($var){
    global $db;
    $db->query('DELETE FROM `vars` WHERE `key`=?s', $var);
}
function is_member($id){
    global $vk;
    $vk = new VK($token);
    $vk->setUserToken($_GET['access_token']);
    $data = $vk->api("groups.isMember", array(
        'group_id'=>GROUP,
        'user_id'=>$id)
    );
    //echo'<br>member? ';print_r($data);
    return $data['response'];
}
function get_scope_token($token){
    global $db;
    global $id_user;
    $db->query("DELETE FROM `tokens` WHERE `date`<?i", (time()-(20*60)));
    $token = $db->getAll("SELECT `scope`,`id_user` FROM `tokens` WHERE `token` = ?s", $token);
    //print_r($token);
    if(isset($token[0]['scope'])){
        $id_user = $token[0]['id_user'];
        return $token[0]['scope'];
    }
    return false;
}
function set_scope_token($token, $scope, $id_user){
    global $db;
    $data = array('token' => $token, 'date' => time(), 'scope' => $scope, 'id_user' => $id_user);
    $sql  = "INSERT INTO `tokens` SET ?u ON DUPLICATE KEY UPDATE ?u";
    $db->query($sql,$data,$data);
}