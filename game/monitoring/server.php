<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?
include('../scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
//include("servers_monitor.php");
$data=$bd->select(array('id','ip','port'),'servers','','ORDER BY `last_monitor`',200);
$sd=sizeof($data);
function serverInfo($ip,$port){
	$url="http://pddrk.kz/monitoring/server.php?ip=".$ip."&port=".$port;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	$content = curl_exec( $ch );
	echo $content;
	return unserialize($content);
}
for($i=0;$i<$sd;$i++){
	$newdata=serverInfo($data[$i]['ip'],$data[$i]['port']);
	
	$newdata['last_monitor']=time();
	if(!isset($newdata['players']))$newdata['players']=0;
	$bd->update('servers',$newdata,array('id'=>$data[$i]['id']));
	if($newdata['status']=='on')
		$bd->insert('monitoring',array(
			'id_server'=>$data[$i]['id'],
			'time'=>time(),
			'players'=>$newdata['players'],
			'type'=>'hour'));
}
echo'<br>';print_r($newdata);
?>
</body>
</html>
