<?
include('func.php');
function go_race($id){
	global $bd;
	$bd->get_all("UPDATE `races` SET `status` = 'go' WHERE `status` = 'wait' AND `id` = ?", array($id));
}
function go_racer($id){
	global $bd;
	$bd->sql("UPDATE `racers` SET `time_start` = ? WHERE  `id_user` = ?", array(time(),$id));
}

if(isset($_GET['id_user']) && isset($_GET['id_race'])){
	go_race($_GET['id_race']);
	go_racer($_GET['id_user']);
}
echo 1;
?>