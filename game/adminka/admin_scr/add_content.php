<?
if(isset($_POST['value0ru'])){
	$id=rand(1,1600000);
	$data=array(
		'id'=>$id,
		'time'=>time(),
		'category'=>$_POST['category'],
		'value0'=>$id.'0',
		'value1'=>$id.'1',
		'value2'=>$id.'2'
	);
	$bd->insert('content',$data);
	for($i=0;$i<3;$i++){
		if($_POST['value'.$i.'ru']=='')
			continue;
		$dataen=array(
			'id'=>$data['value'.$i],
			'value'=>$_POST['value'.$i.'en']
		);
		$dataru=array(
			'id'=>$data['value'.$i],
			'value'=>$_POST['value'.$i.'ru']
		);
		$res=$bd->insert('content_ru',$dataru);
		$res=$bd->insert('content_en',$dataen);
	}
	if($res){
		$suc="Контент добавлен";
		include('../elements/suc.php');
	}
}
if(isset($res)&&$res) include('content.php');
else include('../elements/form_add_content.php');
?>