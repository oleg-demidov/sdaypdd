<?
$kod=rand(0,100000);//отправка кода
echo $kod;
$bd->insert_on_update("email_kods",array('kod'=>$kod,'id_user'=>$id_user));
$suc="Код отправлен";
include('elements/suc.php');
?>
