<?
function mail_to($email){
	//lsdfhvldsjfbv
}
  if(empty($_GET['sess'])){
	setcookie("test","1");
    header("Location: ".$_SERVER['PHP_SELF']."?sess=".$_GET['sess_id']);
  }else{
    if(empty($_COOKIE["test"])) $cooc=false;
    else $cooc=true;
  }
include('scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd);
$dataForm=$bd->select('*','sess_buy',array('id'=>$_GET['sess_id']));
$dataForm=unserialize($dataForm[0]['value']);
$bd->delete_str('sess_buy',array('id'=>$_GET['sess_id']));
$bd->insert('admins',$dataForm);
if($cooc) $cc=1;
else{
	$cc=0;
	mail_to($dataForm['email']);
}
header('Location: http://'.$_SERVER['HTTP_HOST'].'/elements/buy_finish.php?sess='.$_GET['sess_id'].'&id='.$dataForm['id'].'&cook='.$cc);
?>