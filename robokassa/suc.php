<?
session_start();
include ("scripts/bd.php");
// регистрационная информация (пароль #2)
// registration info (password #2)
$mrh_pass2 = "kodpddrk2014";


// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2"));

// проверка корректности подписи
// check signature
if ($my_crc !=$crc)
{
	echo "bad sign\n";
	exit();
}

// признак успешно проведенной операции
// success
//echo "OK$inv_id\n";

$query=mysql_query("select `id_user` from `oplata` where `id`='".$inv_id."'");
$dat=mysql_fetch_array($query, MYSQL_NUM);
if($out_summ==10.0)$d=3;
if($out_summ==30.0)$d=10;
if($out_summ==100.0)$d=30;
$activ=time()+60*60*24*$d;
$query="UPDATE `users` SET `activ`='".$activ."' WHERE `login`='".$dat[0]."'";
$_SESSION['activ']=$activ;
//echo $query;
mysql_query($query);
echo mysql_error($query);
?>