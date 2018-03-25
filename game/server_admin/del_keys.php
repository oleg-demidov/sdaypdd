<?
		$query="DELETE FROM `promo_keys` WHERE `id`='".$bd->check_inj($_GET['id'])."'";
		$bd->sql_query($query);
		include('promokeys.php');
	
?>