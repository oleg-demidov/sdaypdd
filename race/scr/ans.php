<?
include('func.php');

function get_race_id($id){
	global $bd;
	return $bd->get_all("SELECT `races`.`id`, `racers`.`car`, `racers`.`num`, `racers`.`id_user`, `racers`.`time`, `racers`.`result` FROM `racers` LEFT JOIN  `races` ON `races`.`id` = `racers`.`id_race`  WHERE `races`.`id` = ? ORDER BY `racers`.`num`", array($id));
	
}

function control_racers($id_user, $ans){
	global $bd;
	if($ans == 'true')
		$bd->sql("UPDATE `racers` SET `result` = `result`+1, `time` = ? WHERE `id_user` = ?", array(time(), $id_user));
	else $bd->sql("UPDATE `racers` SET `time` = ? WHERE `id_user` = ?", array(time(), $id_user));
}

function normalize($data){
	if(($sd = sizeof($data)) && $data){
		//$data['count'] = $sd;
		for($i=0; $i < $sd; $i++){
			$data[$i]['timer'] = $data[$i]['time'] - time();
		}
	}
	return $data;
}


if(isset($_GET['id_user']) && isset($_GET['ans']))
	control_racers($_GET['id_user'], $_GET['ans']);

if(isset($_GET['id_race']))
$data = get_race_id($_GET['id_race']);
else $data = array();
echo json_encode(normalize($data));
?>