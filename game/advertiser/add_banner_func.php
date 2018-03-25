<?
$error='';
function check_form(){
	if(!$_POST)return false;
	global $error;
	$need=array(
			"header"=>"Заголовок",
			"mess"=>"Сообщение",
			"site"=>"Ссылка",
			"budget"=>"Бюджет"
		);
	foreach($need as $k=>$v){
		if(!$_POST[$k]){
			$error="Пустое поле ".$v;
			include('../elements/errors.php');
			return false;
		}
	}
	if(!isset($_GET['id'])&&$_GET['id']==''){
		$error="Не определен id.";
		include('../elements/errors.php');
		return false;
	}
	return true;
}
/*function encode($str){
	$data=unpack('C*',$str);
	$asc=array(48,49,50,51,52,53,54,55,56,57,65,66,67,68,69,70);
	$arr=array();
	$ss=sizeof($data)+1;
	for($i=1;$i<$ss;$i++){
		$arr[$i]=chr($asc[$data[$i]>>4]).chr($asc[$data[$i]&15]);
	}
	return implode($arr);
}
function altfile($path,$maxlen){
	$str='';
	$f=fopen($path,'rb');
	$arr=array();
	$ml=0;
	while($str=fread($f, $maxlen)){
		$arr[]=encode(&$str);
		$ml+=$maxlen;
	}
	fclose($f);
	
	return $arr;
}

$arrFile=altfile('../sprites/'.$id.'.spr',500);
	$query='INSERT INTO `files`(`id`,`data`,`len`,`index`,`type`) VALUES';
	$sf=sizeof($arrFile);
	//$hash5=md5_file('../sprites/'.$id.'.spr');
	for($i=0;$sf>$i;$i++){
		$query.="('".$id."','".$arrFile[$i]."','".strlen($arrFile[$i])."','".$i."','spr')";
		if($sf>($i+1))$query.=',';
	}
	$rez=$bd->sql_query($query);
	echo $bd->error;
	$arrFile=altfile($_FILES["vtf"]["tmp_name"],500);
	$query='INSERT INTO `files`(`id`,`data`,`len`,`index`,`type`) VALUES';
	$sf=sizeof($arrFile);
	//$hash5=md5_file('../sprites/'.$id.'.spr');
	for($i=0;$sf>$i;$i++){
		$query.="('".$id."','".$arrFile[$i]."','".strlen($arrFile[$i])."','".$i."','vtf')";
		if($sf>($i+1))$query.=',';
	}
	$rez=$bd->sql_query($query);
	echo $bd->error;
	return $data;
*/
function check_files(){
	global $error;
	if($_FILES['sprite']['error']){
		$error="Не выбран файл";
		include('../elements/errors.php');
		return false;
	}
	$extM=end(explode(".",$_FILES["sprite"]["name"]));
	if($extM!='gif'&&$extM!='GIF'&&$extM!='vtf'){
		$error="Не верный формат файла ".$_FILES["sprite"]["name"]." (должен быть gif)";
		include('../elements/errors.php');
		return false;
	}
	return true;
}
function load_sprite($id){
	global $error;
	include('../scripts/gif2sprite.php');
	$g2s=new Gif2Spr();
	$data=$g2s->convert($_FILES["sprite"]["tmp_name"],'../sprites/'.$id.'.spr');
	if($g2s->error!=''){
		$error= $g2s->error;
		include('../elements/errors.php');
		return false;
	}
	copy($_FILES["sprite"]["tmp_name"],'../sprites/gifs/'.$id.'.gif');
	imageresize('../sprites/icons/'.$id.'.gif',$_FILES["sprite"]["tmp_name"],20);
	return $data;
}
function load_vtf($id){
	if(copy($_FILES["sprite"]["tmp_name"],'../sprites/'.$id.'.vtf')){
		return true;
	}
	return false;
}
function imageresize($outfile,$infile,$percents) {
    $im=imagecreatefromgif($infile);
    $w=(int)(imagesx($im)*$percents/100);
    $h=(int)(imagesy($im)*$percents/100);
    $im1=imagecreatetruecolor($w,$h);
    imagecopyresampled($im1,$im,0,0,0,0,$w,$h,imagesx($im),imagesy($im));

    imagegif($im1,$outfile);
    imagedestroy($im);
    imagedestroy($im1);
}

?>