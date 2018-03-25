<?
ignore_user_abort(true);
//echo date('Y-m-d H:i:s',(strtotime(date('Y-m-d',time()))+60*60*3));
define('TIME_UNI_MIN',20); // time every min

include('../scripts/bd.inc.php');
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);

function is_cap(){
	global $bd;
	$id_cap=$bd->select(array('cap'),'servers',array('id'=>$_GET['id_server']));
	return ($id_cap[0]['cap']==$_GET['id_banner']);
}

function check_get(){
	$need=array(
		'ip_user',
		'id_server',
		'id_banner',
		'code',
		'type'
	);
	foreach($need as $v)
		if(!isset($_GET[$v]))
			return false;
	include("../plugin/key.php");
	$args=$_SERVER["QUERY_STRING"];
	$code=md5(substr($args,0,strpos($args,"&code")).$key);
	if($code!=$_GET['code']){
		echo"bad code";
		return false;
	}
	return true;
}

function ip_code($ip){
	$arrip=explode('.',$ip);
	$ip=0;
	for($i=0;$i<4;$i++)
		$ip|=(((int)$arrip[$i])<<(8*$i));
	return $ip;
}
function get_type(){
	global $bd;
	$ut=time();
	$ip=ip_code($_GET['ip_user']);
	$last_time=$bd->select($_GET['type'],'ip_base',array('ip'=>$ip));
	$bd->insert_on_update('ip_base',array('ip'=>$ip,$_GET['type']=>$ut),"`".$_GET['type']."`='".$ut."'");
	if(($last_time[0][$_GET['type']]+60*TIME_UNI_MIN)<$ut)
		return 'uni'.$_GET['type'];
	else
		return $_GET['type'];
}
if(check_get()){
	$type=get_type();
	$id_users=$bd->select('id_user','servers',array('id'=>$_GET['id_server']));
	$id_usera=$bd->select('id_user','companies',array('id'=>$_GET['id_banner']));
	$set=array(
		'time'=>strtotime('now 00:00:00'),
		'id_banner'=>$_GET['id_banner'],
		'id_server'=>$_GET['id_server'],
		'id_user'=>$id_users[0]['id_user'],
	);
	
	$set[$type]=1;
	$bd->insert_on_update('adv_stat',$set,"`".$type."`=`".$type."`+1");
	$quer="select (select `value` from `variables` where `name`='precent_adm') AS `precent_adm`,";
	$quer.="(select `value` from `variables` where `name`='price1000') AS `price1000`,";
	$quer.="(select `value` from `variables` where `name`='price_click') AS `price_click` from `variables`";
	$quer.=" limit 1";
	$vari=$bd->sql_query($quer);
	$vari=$bd->get_result($vari);
	$plus=0;
	if($type=='uniclick'){
		$plus=($vari[0]['price_click']/100)*$vari[0]['precent_adm'];
		$minus=$vari[0]['price_click'];
	}
	if($type=='unishow'){
		$plus=($vari[0]['precent_adm']/100)*($vari[0]['price1000']/1000);
		$minus=$vari[0]['price1000'];
	}
	if($plus&&!is_cap()){
		$bd->update('users',"`balans`=`balans`+$plus",array('id'=>$id_users[0]['id_user']));
		$bd->update('users',"`balans`=`balans`-$minus",array('id'=>$id_usera[0]['id_user']));
		$bd->update('companies',"`used_budget`=`used_budget`+$minus",array('id'=>$id_usera[0]['id_user']));
	}
	$set['time']=time()+60*TIME_UNI_MIN;
	$bd->insert('last_servers',$set);
	$quer="DELETE FROM `last_servers` WHERE `time`<'".time()."'";
	$bd->sql_query($quer);
}


?>