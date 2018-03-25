<?
$time_wait=1;
include("../scripts/bd.inc.php");
$keys=explode(",",$_GET['keys']);
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
function get_cap($id_server){
	global $bd;
	global $keys;
	$id_cap=$bd->select(array('cap'),'servers',array('id'=>$id_server));
	if(!$id_cap[0]['cap'])
		return false;
	$cap=$bd->select($keys,'companies',array('id'=>$id_cap[0]['cap']));
	return $cap;
}
include("key.php");
$args=$_SERVER["QUERY_STRING"];
$code=md5(substr($args,0,strpos($args,"&code")).$key);

	
if($code==$_GET['code']){
	$geo=$bd->select(array('geo_country','geo_city'),'servers',array('id'=>$_GET['id_server']));
	$query="DELETE FROM `old_banners` WHERE `time`<'".time()."'";
	$bd->sql_query($query);
	$oldB=$old_banners=$bd->select(array('id'),'old_banners',"`time`>'".time()."' AND `id_server`='".$_GET['id_server']."'");
	$subquery='';
	if($oldB){
		for($i=0;$i<sizeof($oldB);$i++)
			$subquery.=" AND `id`!='".$oldB[$i]['id']."'";
	}
	$banner=$bd->select($keys,'companies',"`activ`='on' AND (`geo_country`='".$geo[0]['geo_country']."' OR `geo_country`='0') AND (`geo_city`='".$geo[0]['geo_city']."' OR `geo_city`='0') AND `budget`>`used_budget` $subquery","",'1');
	if($banner){
		$bd->insert('old_banners',array('id_server'=>$_GET['id_server'],'time'=>(time()+60*$time_wait),'id'=>$banner[0]['id']));
		$str= implode(";",$banner[0]);
		header('Content-Length: '.strlen($str));
		echo $str;
	}elseif($cap=get_cap($_GET['id_server'])){
		$str= implode(";",$cap[0]);
		header('Content-Length: '.strlen($str));
		echo $str;
	}else{
		header('Content-Length: 2');
		echo'no';
	}
}
?>