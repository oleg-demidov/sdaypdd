<h3><? echo content('apply_withdrawal',$content_tn);?></h3>
<?
function check_summ($summ){
	global $bd;
	$sum=$bd->select(array('balans'),'users',array('id'=>$_SESSION['id']));
	if($sum[0]['balans']>=$summ&&$sum[0]['balans']>10)
		return true;
	$error=content('error_balanse',$content_tn);
	include("elements/errors.php");
	return false;
}
if(isset($_POST['sum'])&&$_POST['sum']!=0&&check_summ($_POST['sum'])){
	$coll=$bd->get_result($bd->sql_query("SELECT count(*) AS coll FROM `transactions`"));
	$data=array(
		'id'=>$coll[0]['coll']+1,
		'direction'=>'out',
		'summ'=>$_POST['sum'],
		'time'=>time(),
		'status'=>'no_paid',
		'comment'=>isset($_POST['comment'])?$_POST['comment']:'',
		'id_user'=>$_SESSION['id']
	);
	$res=$bd->insert('transactions',$data);
	if($res){
		$suc=content('suc_vivod',$content_tn);
		include('../elements/suc.php');
	}
	include('balance.php');
}else
	include('../elements/form_vivesti.php');
?>