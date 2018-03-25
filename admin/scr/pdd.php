<?
function html_pdd($text){
	$search = array("\r\n"/*, '\\'*/);
	$rep = array('</p><p>'/*, ''*/);
	return str_replace ( $search , $rep , $text );
}

if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT * FROM `pdd` WHERE `id`=?",array($_GET['id']));
	$data['text'] = stripcslashes($data['text']);
}
if(isset($_POST['category'])){
	$data = $_POST;
	$data['text'] = html_pdd($data['text']);
	$bd->insert_on_update('pdd', $data);
	if($bd->error)echo $bd->error;
	include('pdds.php');
}else
	include('elements/form_post.php');
?>