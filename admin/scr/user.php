<?
if(isset($_GET['id'])){
	$data = $bd->get_row("SELECT * FROM `users` WHERE `id`=?",array($_GET['id']));
}
if(isset($_POST['name'])){
	$data = $_POST;
	$q = array(
		$data['email'],
		$data['name'],
		(time()+$data['donate']*60*60*24),
		$data['pass'],
		$data['id'],
	);
	$bd->sql("UPDATE `users` SET `email`=?, `name`=?, `donate`=?, `pass`=? WHERE `id`=?", $q);
	if($bd->error)echo $bd->error;
	include('users.php');
}else
	include('elements/form_user.php');
?>