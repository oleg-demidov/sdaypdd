<?
	$bd->delete_str('offers',"`id`='".$bd->check_inj($_GET['id'])."'");
	$query="DELETE FROM `promo_keys` WHERE `id_offer`='".$bd->check_inj($_GET['id'])."'";
	$bd->sql_query($query);
	include('my_offers.php');
?>