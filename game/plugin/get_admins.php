<?
include("key.php");
$args=$_SERVER["QUERY_STRING"];
$code=md5(substr($args,0,strpos($args,"&code")).$key);
if($code==$_GET['code']){
	include("../scripts/bd.inc.php");
	$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
	$data=$_GET;
	unset($data['code']);
	$keys=explode(",",$data['keys']);
	unset($data['keys']);
	$data['activ']="on";
	$res=$bd->select($keys,"admins","`activ`='on' AND `timeout`>'".time()."'AND `id_server`='".$_GET['id_server']."'");
	if($res){
		$str='';
		$sr=sizeof($res);
		for($i=0;$sr>$i;$i++){
			$str.= implode(";",$res[$i]);
			if(($i+1)!=$sr)echo"||";
		}
		header('Content-Length: '.strlen($str));
		echo $str;
	}else header('Content-Length: 0');
}
?>