<?
if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT * FROM `t_que`  WHERE `id`=?", array($_GET['id']));
	$ans = $bd->get_all("SELECT * FROM `t_ans` WHERE `id_que`=?", array($_GET['id']));
	$img = $bd->get_all("SELECT * FROM `t_images` WHERE `id_que`=?", array($_GET['id']));
	if(isset($img[0]))$data['img'] = $img[0]['id'];
	$sa = sizeof($ans);
	for($i=0;$i<$sa;$i++){
		$data['ans'.($i+1)] = $ans[$i]['value'];
		if($ans[$i]['right'] == 1)
			$data['right'] = $i+1;
	}
}
function update_ans($data){
	global $bd;
	for($i=1;$i<6;$i++){
		if($data['ans'.$i] != ''){
			$q = array(
				'id_que'=>$data['id'],
				'n_ans'=>$i,
				'right'=>($data['right'] == $i)?'1':'0',
				'value'=>$data['ans'.$i]
			);
			$bd->insert_on_update('t_ans', $q);
		}else
			$bd->sql("DELETE FROM `t_ans` WHERE `id_que`=? AND `n_ans`=?",array($data['id'],$i));
		unset($data['ans'.$i]);
	}
	unset($data['right']);
	return $data;
}
if(isset($_POST['id'])){
	$data = $_POST;
	if($_POST['id'] == '')
		$data['id'] = rand(0,16000000);
	$q = update_ans($data);
	$bd->insert_on_update('t_que', $q);
	include('tests.php');
}else
	include('elements/form_test.php');
?>