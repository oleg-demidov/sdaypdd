<table align="center" cellpadding="5">
<tr>
		<td colspan="5">
		<select id="mysel" name="category" onChange="my_sel(this)">
		<option value=""></option><?
$categories = $bd->get_columns("SELECT * FROM `cat_signs` ORDER BY `category`");
$sc=sizeof($categories['id']);
for($i = 0; $i < $sc; $i++){
	echo'<option value="', $categories['id'][$i],'" ';
	if(isset($_GET['cat']) && $_GET['cat'] == $categories['id'][$i])
		echo'selected="selected"';
	echo'>', $categories['category'][$i], '</option>';
}
?></select>
<script language="javascript" type="text/javascript">
function my_sel(){
	sel=document.getElementById("mysel");
	window.location='http://<? echo $_SERVER['HTTP_HOST'];?>/admin/index.php?a=signs&cat='+sel.options[sel.selectedIndex].value;
}
</script>
</td>
		<td>Категория</td>
	</tr>
<tr><td></td><td>Имя</td><td>Категория</td><td>Номер</td><td></td><td></td></tr>
<tr><td colspan="7"><a href="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=sign">Добавить</a></td></tr>
<?
$cat = '';
$q = array();
if(isset($_GET['cat'])){
	$cat = "WHERE `cat_signs`.`id`=? ";
	$q[] = $_GET['cat'];
}
$del = 20;
$lim = isset($_GET['lim'])?$_GET['lim']:0;
$q[] = ($lim*$del);
$q[] = $del;
$signs = $bd->get_all("SELECT `signs`.`id`,`signs`.`num`,`signs`.`name`, `cat_signs`.`category`,`cat_signs`.`cnum` FROM `signs` LEFT JOIN `cat_signs` ON `signs`.`id_category`=`cat_signs`.`id` ".$cat." ORDER BY `signs`.`numf` LIMIT ?,?", $q);
$sp = sizeof($signs);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
for($i=0;$i<$sp;$i++){
	echo'<tr><td><img width="30" src="http://',$_SERVER['HTTP_HOST'],'/signs/small',$signs[$i]['id'],'.png" /></td>';
	echo'<td>',$signs[$i]['name'],'</td>';
	echo'<td>',$signs[$i]['cnum'],' ',$signs[$i]['category'],'</td>';
	echo'<td>',$signs[$i]['cnum'],'.',$signs[$i]['num'],'</td>';
	echo'<td><a href="',$url_red,'?a=sign&id=',$signs[$i]['id'],'">редактировать</a></td>';
	echo'<td><a href="',$url_red,'?a=sign_del&id=',$signs[$i]['id'],'">удалить</a></td></tr>';
}
?>

<tr><td class="navig" colspan="7">
<?
echo get_navig('signs', $lim, $del);
?></td></tr>
</table>