<?
include("key.php");
$args=$_SERVER["QUERY_STRING"];
$code=md5(substr($args,0,strpos($args,"&code")).$key);
include("../scripts/bd.inc.php");
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
$data=$_GET;
unset($data['code']);
$keys=explode(",",$data['keys']);
unset($data['keys']);
	
if($code==$_GET['code']){
	$res=$bd->select($keys,"maps",$data);
	if($res){
		$str='';
		$sr=sizeof($res);
		for($i=0;$sr>$i;$i++){
			$str.= implode(";",$res[$i]);
			if(($i+1)!=$sr)echo"||";
		}
		header('Content-Length: '.strlen($str));
		echo $str;
	}else{
		$map=$bd->select('*',"maps_no",$data);
		if(!$map){
			$data['id']=rand(1,9999999);
			$bd->insert('maps_no',$data);
		}
		header('Content-Length: 0');
	}
}
?>