<?

if(isset($_GET['namev'])){
	$rez=$bd->delete_str('variables',array('name'=>$_GET['namev']));
}
include('vars.php');
?>