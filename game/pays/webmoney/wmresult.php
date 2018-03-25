<?
//session_start();
include('../../scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
$id_trans=$_POST['LMI_PAYMENT_NO'];
include('../pay_func.php');

function check_hash(){
	$secret_key='GN6uT5cdHScHMlarc0f3';
	$str=$_POST['LMI_PAYEE_PURSE'];
	$str.=$_POST['LMI_PAYMENT_AMOUNT'];
	$str.=$_POST['LMI_PAYMENT_NO'];
	$str.=$_POST['LMI_MODE'];
	$str.=$_POST['LMI_SYS_INVS_NO'];
	$str.=$_POST['LMI_SYS_TRANS_NO'];
	$str.=$_POST['LMI_SYS_TRANS_DATE'];
	$str.=$secret_key;
	$str.=$_POST['LMI_PAYER_PURSE'];
	$str.=$_POST['LMI_PAYER_WM'];
	$sha256 = hash('sha256', $str);
	$sha256=strtoupper($sha256);
	if($_POST['LMI_HASH']==$sha256)
		return true;
	else return false;
}

if(isset($_POST['LMI_PREREQUEST'])&&$_POST['LMI_PREREQUEST']==1){
	if(check_summ($_POST['LMI_PAYMENT_AMOUNT'])){
		echo 'YES';
	}
}elseif(check_summ($_POST['LMI_PAYMENT_AMOUNT'])){
	if(check_hash()){
		pay($_POST['LMI_PAYMENT_NO'],'webmoney');
	}
}
?>