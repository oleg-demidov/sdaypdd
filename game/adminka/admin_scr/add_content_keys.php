<?

if(isset($_POST['key'])){
	$id=rand(0,1600000);
	$data=array(
		'id'=>$id,
		'category'=>$_POST['category'],
		'key'=>$_POST['key']
		);
	$dataen=array(
		'id'=>$id,
		'value'=>$_POST['value_en']
	);
	$dataru=array(
		'id'=>$id,
		'value'=>$_POST['value_ru']
	);
	$bd->insert('content_keys',$data);
	$res=$bd->insert('content_ru',$dataru);
	$res=$bd->insert('content_en',$dataen);
	if($res){
		$suc="Контент добавлен";
		include('../elements/suc.php');
	}
}
if(isset($res)&&$res) include('content_keys.php');
else include('../elements/form_add_cont.php');
?>