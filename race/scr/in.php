<?
include('func.php');

function in_race($id_race, $id_user, $car){
	global $bd;
	if($id_race == 0){
		$id_race = rand(1,999999);
		$bd->sql("INSERT INTO `races` (`id`, `time`, `status`) VALUES(?,?,'wait')", array($id_race,time()));
	}
	$count = $bd->get_row("SELECT count(*) AS `count` FROM `racers` WHERE `id_race` = ?",array($id_race));
	if($count['count']>3)
		return false;
	$data = array(
		'id_race' => $id_race,
		'id_user' => $id_user,
		'num' => $count['count']+1,
		'car' => $car,
		'time_start' => 0,
		'time' => time(),
		'result' => 0
	);
	$bd->insert_on_update("racers", $data);
	return $id_race;
}

if(!isset($_GET['id_race']) || !isset($_GET['id_user']) || !isset($_GET['car']))
	$data = false;
else $data = in_race($_GET['id_race'], $_GET['id_user'], $_GET['car']);

echo json_encode(array('id_race' => $data));
?>