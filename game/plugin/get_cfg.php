<?
include("key.php");
$args=$_SERVER["QUERY_STRING"];
$code=md5(substr($args,0,strpos($args,"&code")).$key);
if($code==$_GET['code']){
	include("../scripts/bd.inc.php");
	$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
	$res=$bd->select(array('id',"adv_used"),"servers",array('ip'=>$_GET['ip'],'port'=>$_GET['port']));
	if($res){
		$res2=$bd->select('rank',"privileges",array('id_server'=>$res[0]['id']));
		$data=array();
		$data[0]=$res[0]['id'];
		$data[1]=($res2[0]['rank']?$res2[0]['rank']:999);
		$data[2]=$res[0]['adv_used'];
		$bd->update("servers",array('last_conn'=>time()),array("id"=>$data[0]));
		$str=implode(";",$data);
		header('Content-Length: '.strlen($str));
		echo $str;
	}
}else
	header('Content-Length: 0');
?>