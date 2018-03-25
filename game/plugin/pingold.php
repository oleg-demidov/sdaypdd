<?
ignore_user_abort(true);
//echo date('Y-m-d H:i:s',(strtotime(date('Y-m-d',time()))+60*60*3));
define('TIME_CRON_MIN',20); // time every min
define('TIME_CRON_DAY',3); // time every day HOUR where update base

include('../scripts/bd.inc.php');
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);

if(!file_exists('cron_min.time'))file_put_contents('cron_min.time',time()+60*TIME_CRON_MIN);
$mFile=file_get_contents('cron_min.time');
//echo 'next cron min- '.date('Y-m-d H:i:s',$mFile).'; ';

$hd=strtotime(date('Y-m-d',strtotime('next day')))+60*60*TIME_CRON_DAY;
if(!file_exists('cron_day.time'))file_put_contents('cron_day.time',$hd);
$dFile=file_get_contents('cron_day.time');
//echo 'next cron day- '.date('Y-m-d H:i:s',$dFile).'; ';

function check_get(){
	$need=array(
		'ip_user',
		'id_server',
		'id_banner',
		'code'
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
if(check_get()){
	$id=$_GET['id_server'];
	if($id){
		$data=array(
			'id_banner'=>$_GET['id_banner'],
			'id_server'=>$id,
			'type'=>$_GET['type'],
			'ip_user'=>ip_code($_GET['ip_user'])
		);
		$bd->insert('shows_now',$data);
	}
	$p=$bd->select('*','variables',"`name`='price1000' OR `name`='price_click'");
	$prices=array();
	for($i=0;$i<2;$i++)$prices[$p[$i]['name']]=$p[$i]['value'];
	$rasxod=($_GET['type']=='show')?$prices['price1000']/1000:$prices['price_click'];
	$bd->update('companies',"`used_budget`=`used_budget`+".$rasxod,array('id'=>$_GET['id_banner']));
}
function ip_code($ip){
	$arrip=explode('.',$ip);
	$ip=0;
	for($i=0;$i<4;$i++)
		$ip|=(((int)$arrip[$i])<<(8*$i));
	return $ip;
}
function sort_shows($data,$key){
	$sort_data=array();
	$sd=sizeof($data);
	for($i=0;$i<$sd;$i++){
		$id=$data[$i][$key];
		if(!isset($sort_data[$id]))
			$sort_data[$id]=array(
				'shows'=>0,
				'clicks'=>0,
				'uni_shows'=>0,
				$key=>$id,
				'ips'=>array(),
				'ipc'=>array()
			);
		if($data[$i]['type']=="click"){
			if(!isset($sort_data[$id]['ipc'][$data[$i]['ip_user']])){
				$sort_data[$id]['ips'][$data[$i]['ip_user']]=1;
				$sort_data[$id]['clicks']++;
			}
		}
		if($data[$i]['type']=="show"){
			$sort_data[$id]['shows']++;
			if(!isset($sort_data[$id]['ips'][$data[$i]['ip_user']])){
				$sort_data[$id]['ips'][$data[$i]['ip_user']]=1;
				$sort_data[$id]['uni_shows']++;
			}
		}
	}
	$sd=sizeof($sort_data);
	$keys=array_keys($sort_data);
	for($i=0;$i<$sd;$i++){
		unset($sort_data[$keys[$i]]['ips']);
		unset($sort_data[$keys[$i]]['ipc']);
	}
	return $sort_data;
}
if($mFile<time()&&$mFile){
	file_put_contents('cron_min.time',time()+60*TIME_CRON_MIN);
	if($data=$bd->select('*','shows_now')){
		$banners=sort_shows(&$data,'id_banner');
		$bd->insert('shows_today_banners',$banners);
		unset($banners);
		$servers=sort_shows(&$data,'id_server');
		$bd->insert('shows_today_servers',$servers);
		unset($servers);
		$bd->clear_table('shows_now');
	}
}

function add_arr($arrA,$arrB) {
		foreach($arrA as $k=>$v) {
			if(isset($arrB[$k])) {
				$arrB[$k]+=$v;
			}
		}
		return $arrB ;
	}
function supersort($data,$key){
	$tt=strtotime(date('d-m-Y',time()))+1;
	$sd=sizeof($data);
	$sort_data=array();
	for($i=0;$i<$sd;$i++){
		if(!isset($sort_data[$data[$i][$key]]))
			$sort_data[$data[$i][$key]]=array('shows'=>0,'clicks'=>0,'uni_shows'=>0,$key=>0,'time'=>$tt);
		$sort_data[$data[$i][$key]]=add_arr($data[$i],$sort_data[$data[$i][$key]]);
		$sort_data[$data[$i][$key]][$key]=$data[$i][$key];
	}
	return $sort_data;
}

if($dFile<time()&&$dFile){
	file_put_contents('cron_day.time',$hd);
	if($servers=$bd->select('*','shows_today_servers')){
		$servers=supersort($servers,'id_server');
		$bd->insert('shows_days_servers',$servers);
		$bd->clear_table('shows_today_servers');
		unset($servers);
	}
	if($banners=$bd->select('*','shows_today_banners')){
		$banners=supersort($banners,'id_banner');
		$bd->insert('shows_days_banners',$banners);
		$bd->clear_table('shows_today_banners');
		unset($banners);
	}
}




?>