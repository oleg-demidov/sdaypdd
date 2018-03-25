<?
include('../../scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);

$id_trans=$_REQUEST["InvId"];
include('../pay_func.php');

// регистрационная информация (пароль #2)
// registration info (password #2)
$mrh_pass2 = "csmoneyrobokassa2015";

//установка текущего времени
//current date
$tm=getdate(time()+9*3600);
$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

// чтение параметров
// read parameters
$out_summ = $_REQUEST["OutSum"];
$inv_id = $_REQUEST["InvId"];
$shp_item = $_REQUEST["Shp_item"];
$crc = $_REQUEST["SignatureValue"];

$crc = strtoupper($crc);

$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));

// проверка корректности подписи
// check signature
if ($my_crc !=$crc)
{
  echo "bad sign\n";
  exit();
}
if(!check_summ($out_summ)){
  echo "bad summ\n";
  exit();
}
pay($inv_id,'robokassa');
// признак успешно проведенной операции
// success
echo "OK$inv_id\n";


?>


