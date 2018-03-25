<h3><? echo content('in',$content_tn);?></h3>
<?
if(isset($_POST['summ'])&&$_POST['summ']!=0){
	$coll=$bd->get_result($bd->sql_query("SELECT count(*) AS coll FROM `transactions`"));
	$data=array(
		'id'=>$coll[0]['coll']+1,
		'direction'=>'in',
		'summ'=>$_POST['summ'],
		'time'=>time(),
		'status'=>'no_paid',
		'desc'=>isset($_POST['desc'])?$_POST['desc']:'',
		'id_user'=>$_SESSION['id'],
		//'sposob'=>$_POST['sposob']
	);
	$bd->insert('transactions',$data);
	header("Location: ".$host_lang.'/pays/index.php?a=pay&id='.$data['id']);
}else{
	include('../elements/form_fill.php');
}
?>