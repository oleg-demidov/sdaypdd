<?
include('func.php');
define("WAITTIME", 60);
function get_race(){
	global $bd;
	return $bd->get_all("SELECT `races`.`id`, `races`.`time`, `racers`.`car`, `racers`.`num`, `racers`.`id_user`, `users`.`name`, `socials`.`first_name`, `socials`.`last_name`, `socials`.`avatar50`, `socials`.`avatar100` FROM  `racers` LEFT JOIN `races` ON `races`.`id` = `racers`.`id_race` LEFT JOIN `users` ON `users`.`id` = `racers`.`id_user` LEFT JOIN (SELECT * FROM `social` GROUP BY `id_user` ) AS `socials` ON `socials`.`id_user` = `racers`.`id_user` WHERE `races`.`id` = (SELECT `id` FROM `races` WHERE `status` = 'wait' AND `time` > ? LIMIT 1) ORDER BY `racers`.`num`", array(time()-WAITTIME));
}
function get_race_id($id){
	global $bd;
	return $bd->get_all("SELECT `races`.`id`, `races`.`time`, `racers`.`car`, `racers`.`num`, `racers`.`id_user`, `users`.`name`, `socials`.`first_name`, `socials`.`last_name`, `socials`.`avatar50`, `socials`.`avatar100` FROM `racers` LEFT JOIN  `races` ON `races`.`id` = `racers`.`id_race` LEFT JOIN `users` ON `users`.`id` = `racers`.`id_user` LEFT JOIN (SELECT * FROM `social` GROUP BY `id_user` ) AS `socials` ON `socials`.`id_user` = `racers`.`id_user` WHERE `races`.`id` = ? ORDER BY `racers`.`num`", array($id));
	
}
function control_racers(){
	global $bd;
	if(isset($_GET['id_user'])&&isset($_GET['id_race'])){
		$bd->sql("UPDATE `racers` SET `time` = ? WHERE `id_user` = ?", array(time(), $_GET['id_user']));
		$bd->sql("DELETE FROM `racers` WHERE `time` < ?", array(time()-5));
	}
	$bd->sql("DELETE FROM `racers` WHERE `time` < ?", array(time()-30*60));
	$bd->sql("DELETE FROM `races` WHERE `time`<? ", array(time()-60*30));
}
function normalize($data){
	if(($sd = sizeof($data)) && $data){
		for($i=0;$i<$sd;$i++){
			if($data[$i]['avatar50'] == NULL){
				$data[$i]['avatar50'] = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
				$data[$i]['avatar100'] = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava100.jpg';
			}else{
				 $data[$i]['name'] = $data[$i]['first_name'];
			}
		}
		$data = array(
			'racers' => $data, 
			'id_race' => $data[0]['id'], 
			'time' => ($data[0]['time']+WAITTIME-time()),
			'sd' => $sd
		);
	}else{
		$data = array('racers' => array(), 'id_race' => 0, 'time' => 0);
	}
	return $data;
}
control_racers();

if(isset($_GET['id_race']))
	$data = get_race_id($_GET['id_race']);
else $data = get_race();


echo json_encode(normalize($data));
?>