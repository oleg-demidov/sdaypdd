<?
if(isset($_POST['t'])){
	$tab = array(
		'category' => 'categories',
		'type' => 'types'
	);
	if(isset($_POST['d'])){
		$bd->sql("DELETE FROM `".$tab[$_POST['t']]."` WHERE `id`=?", array($_POST['id']));
	}else{
		$dataQ = array(
			$_POST['t'] => $_POST[$_POST['t']],
			'num' => $_POST['num']
		);
		if(isset($_POST['id']))
			$dataQ['id'] = $_POST['id'];
		$bd->insert_on_update($tab[$_POST['t']], $dataQ);
	}
}
$dataT = $bd->get_all("SELECT * FROM `types`  ORDER BY `type`");
$dataC = $bd->get_all("SELECT * FROM `categories` ORDER BY `num`");
function get_form($id,$text,$num,$type){
	return '<tr><td><form action="http://'.$_SERVER['HTTP_HOST'].'/admin/index.php?a=categories" method="post">
	<input name="id" type="hidden" value="'.$id.'">
	<input name="t" type="hidden" value="'.$type.'">
	<input size="50" name="'.$type.'" type="text" value="'.$text.'"/>
	<input name="num" size="5" type="text" value="'.$num.'"/></td><td>
	<input type="submit" value="Сохранить"/>
	</form></td>
	<td><form action="http://'.$_SERVER['HTTP_HOST'].'/admin/index.php?a=categories" method="post">
	<input name="id" type="hidden" value="'.$id.'">
	<input name="t" type="hidden" value="'.$type.'">
	<input name="d" type="hidden" value="1"/>
	<input type="submit" value="Удалить"/>
	</form></td></tr>';
}
?>

<table align="center" cellpadding="5">
<tr><td>Категория</td><td>Записей</td></tr>
<?
$sc = sizeof($dataC);
for($i=0; $i<$sc; $i++){
	echo get_form($dataC[$i]['id'], $dataC[$i]['category'], $dataC[$i]['num'], 'category');
}
?>
<tr><td colspan="2">
<form action="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=categories" method="post">
	<input name="t" type="hidden" value="category">
	<input size="50" type="text" name="category"/>
	<input name="num" type="text" size="5" value=""/>
	<input type="submit" value="Добавить"/>
</form>
</td></tr>
</table>

