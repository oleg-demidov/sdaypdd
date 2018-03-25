<table align="center" cellpadding="5">
<tr>
		<td colspan="5">
		<select id="mysel" name="category" onChange="my_sel(this)"><?
$categories = $bd->get_columns("SELECT * FROM `categories` ORDER BY `category`");
$sc=sizeof($categories['id']);
for($i = 0; $i < $sc; $i++){
	echo'<option value="', $categories['id'][$i],'" ';
	if(isset($_GET['cat']) && $_GET['cat'] == $categories['id'][$i])
		echo'selected="selected"';
	echo'>',$categories['num'][$i],' ', $categories['category'][$i], '</option>';
}
?></select>
<script language="javascript" type="text/javascript">
function my_sel(){
	sel=document.getElementById("mysel");
	window.location='http://<? echo $_SERVER['HTTP_HOST'];?>/admin/index.php?a=tests&cat='+sel.options[sel.selectedIndex].value;
}
</script>
</td>
		<td>Категория</td>
	</tr>
<tr><td>ID</td><td>Номер</td><td>Билет</td><td>Категория</td><td></td><td></td></tr>
<?
$cat = '';
$q = array();
if(isset($_GET['cat'])){
	$cat = "WHERE `categories`.`id`=? ";
	$q[] = $_GET['cat'];
}
$del = 20;
$lim = isset($_GET['lim'])?$_GET['lim']:0;
$q[] = ($lim*$del);
$q[] = $del;
$tests = $bd->get_all("SELECT `t_que`.`id`,`t_que`.`number`,`t_que`.`bilet`,`categories`.`category` FROM `t_que` LEFT JOIN `categories` ON `t_que`.`id_category`=`categories`.`id` ".$cat." LIMIT ?,?", $q);
$sp = sizeof($tests);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
for($i=0;$i<$sp;$i++){
	echo'<tr><td>',$tests[$i]['id'],'</td>';
	echo'<td>',$tests[$i]['number'],'</td>';
	echo'<td>',$tests[$i]['bilet'],'</td>';
	echo'<td>',$tests[$i]['category'],'</td>';
	echo'<td><a href="',$url_red,'?a=test&id=',$tests[$i]['id'],'">редактировать</a></td>';
	echo'<td><a href="',$url_red,'?a=test_del&id=',$tests[$i]['id'],'">удалить</a></td></tr>';
}
?>
<tr><td colspan="7"><a href="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=test">Добавить</a></td>
<tr><td class="navig" colspan="7">
<?
echo get_navig('t_que', $lim, $del);
?></td></tr>
</table>
