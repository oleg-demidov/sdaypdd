<?
include('func.php');
define("WAITTIME", 35);
	
function get_race($id){
	global $bd;
	return $bd->get_all("SELECT `races`.`id`, `racers`.`car`, `racers`.`num`, `racers`.`id_user`, `users`.`name`, `social`.`first_name`, `social`.`last_name`, `social`.`avatar50`, `social`.`avatar100` FROM `racers` LEFT JOIN  `races` ON `races`.`id` = `racers`.`id_race` LEFT JOIN `users` ON `users`.`id` = `racers`.`id_user` LEFT JOIN `social` ON `social`.`id_user` = `racers`.`id_user` WHERE `races`.`id` = ? AND `races`.`time` > ?  ORDER BY `racers`.`num`",array($id, (time()-WAITTIME-1)));
	
}
function get_time_race($id){
	global $bd;
	$time = $bd->get_row("SELECT `time` FROM `races`  WHERE `id` = ?", array($id));
	$time = $time['time'] + WAITTIME - time();
	if($time < 0) $time = 0;
	return $time;
}
function control_racers( $id_user){
	global $bd;
	$bd->sql("UPDATE `racers` SET `time` = ? WHERE `id_user` = ?", array(time(), $id_user));
	$bd->sql("DELETE FROM `racers` WHERE `time` < ?", array(time()-2));
}

if(isset($_GET['id_user']))
	control_racers($_GET['id_user']);
	
$racers = get_race($_GET['id']);

for($i=0;$i<sizeof($racers);$i++){
	if($racers[$i]['avatar50'] == NULL){
		$racers[$i]['avatar50'] = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
		$racers[$i]['avatar100'] = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava100.jpg';
	}else{
		 $racers[$i]['name'] = $racers[$i]['first_name'];
	}
}
$time = get_time_race($_GET['id']);

echo json_encode(array('racers' => $racers, 'time' => $time));
?>