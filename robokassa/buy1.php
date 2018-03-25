<?php
session_start();
include('../scr/bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
include('../scr/functions.php');

$mrh_login = "sdaypdd";
$mrh_pass1 = "sdaypdd87054503719";

$Shp_iduser = $_SESSION['user']['id'];
$Shp_days = $_GET['days'];
$inv_desc = $_GET['days']." дней тренировки на sdaypdd.ru";
// сумма заказа
$out_summ = $_GET['summ'];
// build CRC value
$crc  = md5("$mrh_login:$out_summ:0:$mrh_pass1:Shp_days=$Shp_days:Shp_iduser=$Shp_iduser");
// build URL
$url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&".
    "OutSum=$out_summ&InvId=0&Desc=$inv_desc&Shp_days=$Shp_days&Shp_iduser=$Shp_iduser&SignatureValue=$crc";
	
header('Location:'.$url);
?>