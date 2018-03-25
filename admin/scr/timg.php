<?
function get_id_que($num){
	global $bd;
	$id = $bd->get_all("SELECT `id` FROM `t_que` WHERE `number`=?", array($num));
	return $id[0]['id'];
}
if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT `t_images`.`vid`, `t_images`.`id`,`t_que`.`number` FROM `t_images` LEFT JOIN `t_que` ON `t_que`.`id`= `t_images`.`id_que` WHERE `t_images`.`id`=?",array($_GET['id']));
	
}
if(isset($_POST['vid'])){
	$data = $_POST;
	if(!isset($data['id']) || $data['id'] == ''){
		$data['id'] = rand(0,65165109);
		save_jpeg('image', '../tests/images', $data['id'], 500);
		save_jpeg('image', '../tests/images', 's'.$data['id'], 100);
		unset($data['image']);
	}
	$data['id_que'] = get_id_que($data['number']);
	unset($data['number']);
	$bd->insert_on_update('t_images', $data);
	if($bd->error)echo $bd->error;
	include('timgs.php');
}else
	include('elements/form_timg.php');
?>