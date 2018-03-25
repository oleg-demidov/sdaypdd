<?
if(isset($_POST['header'])&&isset($_POST['mess'])){
	$d=array(
		'header'=>$_POST['header'],
		'mess'=>$_POST['mess'],
		'id'=>rand(),
		'date'=>time());
	$rez=$bd->insert('news',$d);
	if($rez){
		$suc="Сохранено";
		include('../elements/suc.php');
	}
	include('admin_scr/news.php');
}else include('../elements/form_add_news.php');
?>