<?
$bd->sql("DELETE FROM `signs` WHERE `id`=?",array($_GET['id']));
unlink('../signs/small'.$_GET['id'].'.png');
unlink('../signs/medium'.$_GET['id'].'.png');
unlink('../signs/large'.$_GET['id'].'.png');
echo $bd->error;
include('signs.php');
?>