<?php
include('classes_test.php');
$data = array();
if(isset($_GET['ans']) && ($_GET['ans']=='true' || $_GET['ans']=='false') && isset($_GET['id_user']) && isset($_GET['id']) && isset($_GET['razdel'])){
	$data = array(
		'id_user' => $_GET['id_user'],
		$_GET['razdel'] => $_GET['ans'],
		'id_que' => $_GET['id']
	);
	if($_GET['razdel'] != 'errors' && $_GET['ans'] == 'false')
		$data['errors'] = 'false';
	//print_r($data);
	$bd->insert_on_update('t_results', $data);
	//echo $bd->error;
}
echo json_encode($_GET['ans']);
?>