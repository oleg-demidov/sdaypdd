<?
include('bd.inc.php');		// подключение базы SQL
$bd = new BD($user_bd, $pass_bd, $bd, $host_bd);

function add_sign($arr){
	echo '<div class="mediaimg">';
	echo'<img src="http://',$_SERVER['HTTP_HOST'],'/signs/small',$arr['id'],'.png">';
	echo '<p><b>',$arr['cnum'],'.',$arr['num'],'</b> ',stripcslashes($arr['text']),'</p></div>';
}
function add_punkt($arr){
	echo '<div><b>',$arr['num'],'.',$arr['punkt'],'</b> ',stripcslashes($arr['text']),'</div>';
}
function get_all_signs($arr, $type){
	global $bd;
	$str = '';
	$qarr = array();
	$qarr[] = $type;
	for($i=0; sizeof($arr)>$i; $i++){
		if($i)
			$str .= " OR";
		$str .= " CONCAT(`cat_signs`.`cnum`,'.',`signs`.`num`) = ? ";
		$qarr[] = (string)$arr[$i];
	}
	return $bd->get_all("SELECT `signs`.`id`, `signs`.`num`, `cat_signs`.`cnum`, `signs`.`text` FROM `signs` LEFT JOIN `cat_signs` ON  `signs`.`id_category` = `cat_signs`.`id` WHERE `signs`.`type` = ? AND (".$str.") ORDER BY `signs`.`numf`", $qarr);
}

function get_all_punkts($arr){
	global $bd;
	$str = '';
	$qarr = array();
	for($i=0; sizeof($arr)>$i; $i++){
		if($i)
			$str .= " OR";
		$str .= " CONCAT(`categories`.`num`,'.',`pdd`.`punkt`) = ? ";
		$qarr[] = (string)$arr[$i];
	}
	$q = "SELECT `categories`.`num`, `pdd`.`punkt`, `pdd`.`text` FROM `pdd` LEFT JOIN `categories` ON `categories`.`id` = `pdd`.`category` WHERE ".$str;
	return $bd->get_all($q, $qarr);
} 

$need = array('z' => 'sign', 'r' => 'razm' , 'p' => 'punkt');
$get = false;
$key = '';
foreach($need as $k=>$v){
	if(isset($_GET[$k])){
		$get = $_GET[$k];
		$type = $v;
		$key = $k;
		break;
	}
}

if($key == 'z' || $key == 'r'){
	$data = get_all_signs(explode ( ';', $get ), $type);
	if($data){
		foreach($data as $v){
			add_sign($v);
		}
	}
}
if($key == 'p'){
	$data = get_all_punkts(explode ( ';', $get ));
	if($data){
		foreach($data as $v){
			add_punkt($v);
		}
	}
}	

?>