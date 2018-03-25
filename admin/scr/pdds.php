<table align="center" cellpadding="5">
<tr>
		<td colspan="4">
		<select id="mysel" name="category" onChange="my_sel(this)"><?
$categories = $bd->get_columns("SELECT * FROM `categories` ORDER BY `num`");
$sc=sizeof($categories['id']);
for($i = 0; $i < $sc; $i++){
	echo'<option value="', $categories['id'][$i],'" ';
	if(isset($_GET['cat']) && $_GET['cat'] == $categories['id'][$i])
		echo'selected="selected"';
	echo'>', $categories['num'][$i], ' ', $categories['category'][$i], '</option>';
}
?></select>
<script language="javascript" type="text/javascript">
function my_sel(){
	sel=document.getElementById("mysel");
	window.location='http://<? echo $_SERVER['HTTP_HOST'];?>/admin/index.php?a=pdds&cat='+sel.options[sel.selectedIndex].value;
}
</script>
</td>
		<td>Категория</td>
	</tr>
<tr>
<td colspan="7"><a href="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=pdd">Добавить</a></td>
</tr>
<tr><td>ID</td><td>Категория</td><td>Пункт</td><td></td><td></td></tr>
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
$qstr = "SELECT `pdd`.`id`,`pdd`.`punkt`,`categories`.`category`,`categories`.`num` FROM `pdd` LEFT JOIN `categories` ON `pdd`.`category`=`categories`.`id` ".$cat." ORDER BY `categories`.`num`, convert(`pdd`.`punkt`, decimal) LIMIT ?,?"; 
$pdd = $bd->get_all($qstr, $q);
$sp = sizeof($pdd);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
for($i=0;$i<$sp;$i++){
	echo'<tr><td>',$pdd[$i]['id'],'</td>';
	echo'<td>',$pdd[$i]['category'],'</td>';
	echo'<td>',$pdd[$i]['num'],'.',$pdd[$i]['punkt'],'</td>';
	echo'<td><a href="',$url_red,'?a=pdd&id=',$pdd[$i]['id'],'">редактировать</a></td>';
	echo'<td><a href="',$url_red,'?a=pdd_del&id=',$pdd[$i]['id'],'">удалить</a></td></tr>';
}
?>

<tr><td class="navig" colspan="7">
<?
echo get_navig('pdd', $lim, $del);
?></td></tr>
</table>