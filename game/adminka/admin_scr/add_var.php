<?

if(isset($_POST)){
	$rez=$bd->insert('variables',$_POST);
}
include('vars.php');
?>