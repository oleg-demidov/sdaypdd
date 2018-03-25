<?
if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT * FROM `signs` WHERE `id`=?",array($_GET['id']));
}
if(isset($_POST['id_category'])){
	$data = $_POST;
	if(!isset($data['id']) || $data['id'] == ''){
		$data['id'] = rand(0,65165109);
		save_jpegs('sign', '../signs', $data['id']);
		unset($data['sign']);
	}
	$data['numf'] = $data['num'];
	$bd->insert_on_update('signs', $data);
	if($bd->error)echo $bd->error;
	include('signs.php');
}else
	include('elements/form_sign.php');
?>