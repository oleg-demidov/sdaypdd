<?
include("../scripts/bd.inc.php");
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
$res=$bd->select('value',"variables",array('name'=>'version_'.$_GET['v']));
if($res){
	$str=$res[0]['value'];
	header('Content-Length: '.strlen($str));
	echo $str;
}
?>