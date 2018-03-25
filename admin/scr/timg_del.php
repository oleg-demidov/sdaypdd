<?
$bd->sql("DELETE FROM `t_images` WHERE `id`=?",array($_GET['id']));
unlink('../tests/images/s'.$_GET['id'].'.jpg');
unlink('../tests/images/'.$_GET['id'].'.jpg');
echo $bd->error;
include('timgs.php');
?>