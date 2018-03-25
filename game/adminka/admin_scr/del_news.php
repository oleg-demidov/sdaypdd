<?
if(isset($_GET['id']))$bd->delete_str('news',array('id'=>$_GET['id']));
include('admin_scr/news.php');
?>