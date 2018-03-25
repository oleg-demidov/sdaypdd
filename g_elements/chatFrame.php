<?php
include('../scr/bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
$bd->sql("DELETE FROM `chat` WHERE `time`<UNIX_TIMESTAMP()-60*60");
if(isset($_GET['mess']) && $_GET['mess'] != '')
{
	$bd->sql("INSERT  INTO `chat`(`time`,`name`,`mess`) VALUES (?,?,?)",array(time(),$_GET['name'],strip_tags(substr($_GET['mess'], 0,100))));
}
//Запросы к бд сами
$data = $bd->get_all("SELECT * FROM `chat` ORDER BY `time` DESC LIMIT 5");
foreach($data as $post){
	echo'<div class="post_chat"><span class="name_chat">',$post['name'],'</span> <span class="time_chat">(',date('H:i',$post['time']),')</span> :<br> &nbsp;&nbsp;',$post['mess'],'</div>';
}


?>