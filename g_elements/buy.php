<?php
if(isset($_POST['summ']) && $_POST['summ']>0)
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/robokassa/buy.php');
else include('form_buy1.php');
?>
