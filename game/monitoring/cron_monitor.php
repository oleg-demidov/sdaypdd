<?
include('/home/httpd/vhosts/cs-money.net/httpdocs/scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);

//include("/home/httpd/vhosts/cs-money.net/httpdocs/monitoring/servers_monitor.php");
function serverInfo($ip,$port){
	$url="http://pddrk.kz/monitoring/server.php?ip=".$ip."&port=".$port;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	$content = curl_exec( $ch );
	return unserialize($content);
}

$data=$bd->select(array('id','ip','port'),'servers','','ORDER BY `last_monitor`',200);
$sd=sizeof($data);
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