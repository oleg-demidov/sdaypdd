<table align="center" cellpadding="5">

<tr><td>ID</td><td>Title</td><td>Опубликовано</td><td>В меню</td><td></td><td></td></tr>
<?
$del=10;
$nowStr = isset($_GET['lim'])?$_GET['lim']:0;
$datas = $bd->get_all("SELECT `pages`.* FROM `pages` WHERE `type`='news' LIMIT ?,?", array(($nowStr),$del));
$sp = sizeof($datas);
$url_red = "http://".$_SERVER['HTTP_HOST']."/admin/index.php";
for($i=0;$i<$sp;$i++){
	echo'<tr><td>',$datas[$i]['id'],'</td>';
	echo'<td>',$datas[$i]['title'],'</td>';
	echo'<td>',$datas[$i]['post'],'</td>';
	echo'<td>',$datas[$i]['menu'],'</td>';
	echo'<td><a href="',$url_red,'?a=page&id=',$datas[$i]['id'],'">редактировать</a></td>';
	echo'<td><a href="',$url_red,'?a=page_del&id=',$datas[$i]['id'],'">удалить</a></td></tr>';
}
?>
<tr><td colspan="7"><a href="http://<? echo $_SERVER['HTTP_HOST']?>/admin/index.php?a=page">Добавить</a></td>
<tr><td  colspan="7">
<?
include('../scr/navigator.php');
$start = isset($_GET['lim']) ? intval($_GET['lim']) : 0;
 
$pageNav = new SimPageNav();
$cont = $bd->get_all("SELECT COUNT(`id`) AS `count` FROM `pages` WHERE `type`='news'");
echo $pageNav->getLinks( $cont[0]['count'], $del, $start, 5, 'lim' );

//echo get_navig($cont[0]['count'], $del);
?></td></tr>
</table>


