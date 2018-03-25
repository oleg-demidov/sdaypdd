<?
include('func.php');
function winner($id){
	global $bd;
	$bd->sql("INSERT INTO `race_stat` SET `wins` = 1, `id_user` = ? ON DUPLICATE KEY UPDATE `wins` = `wins`+1 ", array($id));
	$bd->sql("DELETE `race_stat` FROM `race_stat`,`users` WHERE `race_stat`.`id_user`=`users`.`id` AND `users`.`time`<(UNIX_TIMESTAMP()-60*60*24*30)");
}

if(isset($_GET['id_winner'])){
	winner($_GET['id_winner']);
}
echo 1;
?>