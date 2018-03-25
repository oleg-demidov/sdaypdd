<?
if(isset($_POST)){
	if(isset($_POST['d'])){
		$bd->sql("DELETE FROM `menu` WHERE `id_page`=?", array($_POST['id_page']));
	}else{
		$dataQ = $_POST;
		$bd->insert_on_update('menu', $dataQ);
	}
}
$dataC = $bd->get_all("SELECT * FROM `menu` ORDER BY `num`");
function get_form($id_page,$text,$class,$num){
	return '<tr><td><form action="http://'.$_SERVER['HTTP_HOST'].'/admin/index.php?a=menu" method="post">
	<input size="5" name="id_page" name="text" value="'.$id_page.'"></td><td>
	<input size="30" name="text" type="text" value="'.$text.'"/></td><td>
	<input size="10" type="text" name="class" value="'.$class.'"/></td><td>
	<input size="5" name="num" size="5" type="text" value="'.$num.'"/></td><td>
	<input type="submit" value="Сохранить"/>
	</form></td>
	<td><form action="http://'.$_SERVER['HTTP_HOST'].'/admin/index.php?a=menu" method="post">
	<input name="id_page" type="hidden" value="'.$id_page.'">
	<input name="d" type="hidden" value="1"/>
	<input type="submit" value="Удалить"/>
	</form></td></tr>';
}
?>

<table align="center" cellpadding="5">
<tr><td>id_page</td><td>Текст</td><td>Class</td>Номер<td></td><td></td><td></td></tr>
<?
$sc = sizeof($dataC);
for($i=0; $i<$sc; $i++){
	echo get_form($dataC[$i]['id_page'], $dataC[$i]['text'],$dataC[$i]['class'], $dataC[$i]['num']);
}
?>
<tr><td>
<form action="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=menu" method="post">
	<input size="5" name="id_page" type="text"></td><td>
	<input size="30" type="text" name="text"/></td><td>
	<input size="10" type="text" name="class"/></td><td>
	<input size="5" name="num" type="text" size="5"/></td><td colspan="2">
	<input type="submit" value="Добавить"/>
</form>
</td></tr>
</table>

