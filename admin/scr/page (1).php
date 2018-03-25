<?
if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT * FROM `pages` WHERE `id`=?",array($_GET['id']));
	$data['text'] = stripslashes($data['text']);
}
if(isset($_POST['title'])){
	$data = $_POST;
	if(!isset($data['post']))
		$data['post'] = 'off';
	if(!isset($data['menu']))
		$data['menu'] = 'off';
	$bd->insert_on_update('pages', $data);
	if($bd->error)echo $bd->error;
	include('pages.php');
}else
	include('elements/form_page.php');
?>