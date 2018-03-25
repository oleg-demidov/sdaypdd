<?
function get_lang(){
	if(isset($_GET['lang']))
		return $_GET['lang'];
	if(isset($_SESSION['lang']))
		return $_SESSION['lang'];
	if(isset($_COOKIE['lang']))
		return $_COOKIE['lang'];
	return 'ru';
}
function set_lang($l){
	$_SESSION['lang']=$l;
	$_COOKIE['lang']=$l;
}
$lang=get_lang();
set_lang($lang);
$host="http://".$_SERVER['HTTP_HOST'];
$host_lang="http://".$_SERVER['HTTP_HOST'].'/'.$lang;
function get_cont($lang,$category){
	global $bd;
	$data=$bd->get_result($bd->sql_query("SELECT `key`,(select `value` from `content_".$lang."` where `content_keys`.`id`=`content_".$lang."`.`id`) AS `value` from `content_keys` where `category`='".$category."'"));
	if($data){
		$ar=array();
		$sd=sizeof($data);
		for($i=0;$i<$sd;$i++)$ar[$data[$i]['key']]=$data[$i]['value'];
		return $ar;	
	}
	return array();
}
$CONT=get_cont($lang,'global');
function content($key,$cont=false){
	global $CONT;
	if(isset($CONT[$key]))
		return $CONT[$key];
	if($cont&&isset($cont[$key]))
		return $cont[$key];
	return $key;
}

?>