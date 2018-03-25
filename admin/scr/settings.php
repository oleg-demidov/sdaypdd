<?
function update($data){
	global $bd;
	$fields = array(
		'home',
		'price'
	);
	foreach($fields as $v){
		if(isset($data[$v]))
			$bd->insert_on_update('settings', array('name'=>$v,'value'=>$data[$v]));
	}
} 

if(isset($_POST['home'])){
	$data = $_POST;
	update($data);
	if($bd->error)echo $bd->error;
}else{
	$data = array();
	$sets = $bd->get_all("SELECT * FROM `settings` ");
	$sd = sizeof($sets);
	for($i=0;$i<$sd;$i++){
		$data[$sets[$i]['name']] = $sets[$i]['value'];
	}
}
	include('elements/form_settings.php');
?>
