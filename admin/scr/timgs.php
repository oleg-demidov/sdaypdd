<table align="center" cellpadding="5">
<tr><td></td><td>Вид</td><td>Номер</td><td></td><td></td></tr>
<?
$q = array();
$del = 20;
$lim = isset($_GET['lim'])?$_GET['lim']:0;
$q[] = ($lim*$del);
$q[] = $del;
$imgs = $bd->get_all("SELECT `t_images`.`id`, `t_images`.`vid`, `t_que`.`number` FROM `t_images` LEFT JOIN `t_que` ON `t_images`.`id_que`=`t_que`.`id`  LIMIT ?,?", $q);
$sp = sizeof($imgs);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
for($i=0;$i<$sp;$i++){
	echo'<tr><td><img src="http://',$_SERVER['HTTP_HOST'],'/tests/images/s',$imgs[$i]['id'],'.jpg" /></td>';
	echo'<td>',$imgs[$i]['vid'],'</td>';
	echo'<td>',$imgs[$i]['number'],'</td>';
	echo'<td><a href="',$url_red,'?a=timg&id=',$imgs[$i]['id'],'">редактировать</a></td>';
	echo'<td><a href="',$url_red,'?a=timg_del&id=',$imgs[$i]['id'],'">удалить</a></td></tr>';
}
?>
<tr><td colspan="5"><a href="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=timg">Добавить</a></td></tr>
<tr><td class="navig" colspan="7">
<?
echo get_navig('t_images', $lim, $del);
?></td></tr>
</table>