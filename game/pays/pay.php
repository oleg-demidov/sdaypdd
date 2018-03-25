<h3><? echo content('payment',$content_tn);?></h3>
<?
if(isset($_GET['id'])){
	$data=$bd->select('*','transactions',array('id'=>$_GET['id']));
	$data=$data[0];
}
if(isset($_POST['sposob'])){
	if(!isset($_GET['id'])){
		$coll=$bd->get_result($bd->sql_query("SELECT count(*) AS coll FROM `transactions`"));
		$data=array(
			'id'=>$coll[0]['coll']+1,
			'direction'=>'in',
			'summ'=>$_POST['summ'],
			'time'=>time(),
			'status'=>'no_paid',
			'desc'=>isset($_POST['desc'])?$_POST['desc']:'',
			//'id_user'=>$_SESSION['id'],
			//'sposob'=>$_POST['sposob']
		);
		$bd->insert('transactions',$data);
	}
	include($_POST['sposob'].'/'.$_POST['sposob'].'.php');
}else{
	
	include('../elements/form_pay.php');
}
?>