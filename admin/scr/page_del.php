<?
$bd->sql("DELETE FROM `pages` WHERE `id`=?",array($_GET['id']));
echo $bd->error;
include('pages.php');
?>