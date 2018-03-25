<table align="center" cellpadding="5">
<tr>
		<td colspan="5">
		<input id="search" name="search" size="50" value="<? if(isset($_GET['search'])) echo $_GET['search'];?>"/>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#mybtn").click(function(){
		window.location='http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=users&search='+$("#search").val();
	});
});
</script>
</td>
		<td><input id="mybtn"  type="button" value="Поиск"/></td>
	</tr>
<tr><td>Email</td><td>Пароль</td><td>Имя</td><td>Донат до</td><td></td><td></td></tr>
<?
$del = 20;
$lim = isset($_GET['lim'])?$_GET['lim']:0;
$search = '';
if(isset($_GET['search'])){
	$search = preg_replace("/[^\w\x7F-\xFF\s]/", " ", $_GET['search']);
	$search = "WHERE `email` LIKE '%".$search."%' OR `name` LIKE '%".$search."%'";
}
$q = "SELECT * FROM `users`  ".$search." LIMIT ?,?";
$users = $bd->get_all($q, array(($lim*$del),$del));
$sp = sizeof($users);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
for($i=0;$i<$sp;$i++){
	echo'<tr><td>',$users[$i]['email'],'</td>';
	echo'<td>',$users[$i]['pass'],'</td>';
	echo'<td>',$users[$i]['name'],'</td>';
	echo'<td>';
	if($users[$i]['donate'] > time()) 
		echo date('d.m.y H:i',$users[$i]['donate']);
	else echo '-';
	echo'</td>';
	echo'<td><a href="',$url_red,'?a=user&id=',$users[$i]['id'],'">редактировать</a></td>';
	echo'<td><a href="',$url_red,'?a=user_del&id=',$users[$i]['id'],'">удалить</a></td></tr>';
}
?>
<tr><td class="navig" colspan="7">
<?
echo get_navig('users', $lim, $del);
?></td></tr>
</table>