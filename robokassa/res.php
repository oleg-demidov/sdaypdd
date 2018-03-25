<?
include('../scr/bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);
include('../scr/functions.php');
// регистрационная информация (пароль #2)
// registration info (password #2)
$mrh_pass2 = "sdaypddtochkaru23197";


$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$crc = $_REQUEST["SignatureValue"];
$Shp_days = $_REQUEST['Shp_days'];
$Shp_iduser = $_REQUEST['Shp_iduser'];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_days=$Shp_days:Shp_iduser=$Shp_iduser"));


if ($my_crc !=$crc)
{
	echo "bad sign\n";
	exit();
}

echo "OK$inv_id\n";

$bd->sql("UPDATE `users` SET `donate`=? WHERE `id`=?",array($Shp_days*60*60*24+time(), $Shp_iduser));
?>