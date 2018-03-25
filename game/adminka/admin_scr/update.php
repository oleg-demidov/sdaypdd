<?
if(isset($_GET['del'])){
		$bd->sql_query("DELETE FROM `files` WHERE `id`='".$_GET['del']."'");
	}
$error='';
function check_files(){
	global $error;
	if(!isset($_POST['version'])) return false;
	if(!$_FILES) return false;
	if($_FILES['file']['error']){
		$error="Не выбран файл";
		return false;
	}
	/*$extM=end(explode(".",$_FILES["file"]["name"]));
	if($extM!='amxx'||$extM!='spr'||$extM!='vtf'||$extM!='smx'){
		$error="Íå âåðíûé ôîðìàò ôàéëà";
		return false;
	}*/
	return true;
}
if(check_files()){
	function encode($str){
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
	$arrFile=altfile($_FILES["file"]["tmp_name"],250);
	$query='INSERT INTO `files`(`id`,`len`,`data`,`index`,`type`,`version`,`exp`,`name`) VALUES';
	$sf=sizeof($arrFile);
	$extM=end(explode(".",$_FILES["file"]["name"]));
	$id=rand(0,1000000);
	for($i=0;$sf>$i;$i++){
		$query.="('".$id."','".strlen($arrFile[$i])."','".$arrFile[$i]."','".$i."','".$_POST['type']."','".$_POST['version']."','".$extM."','".$_FILES["file"]["name"]."')";
		if($sf>($i+1))$query.=',';
	}
	$rez=$bd->sql_query($query);
	if($rez){
		$suc="Сохранено";
		include('../elements/suc.php');
	}
}
$error=$bd->error;
if($error!='')include("../elements/errors.php");

include('../elements/form_update.php');
?>