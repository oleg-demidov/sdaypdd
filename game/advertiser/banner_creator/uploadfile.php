<?
session_start();
function check_file(){
	if($_FILES['file']['error']){
		return false;
	}
	$type=strtolower(end(explode('.',$_FILES['file']['name'])));
	if(!($type=="gif"||$type=="png"||$type=="jpeg"||$type=="jpg"))
		return false;
	return $type;
}
function move_real_size($from,$to){
	$maxw=1024;
	$maxh=512;
	$ss=getimagesize($from);
	if($ss[0]<=$maxw&&$ss[1]<=$maxh){
		move_uploaded_file($from,$to);
		return $ss;
	}
	if($ss[2]==IMAGETYPE_GIF)$img=imagecreatefromgif($from);
	elseif($ss[2]==IMAGETYPE_JPEG)$img=imagecreatefromjpeg($from);
	elseif($ss[2]==IMAGETYPE_PNG)$img=imagecreatefrompng($from);
	$iw=$ss[0];
	$ih=$ss[1];
	if($iw>$ih){
		$new_w=$maxw;
		$new_h=ceil($ih/($iw/$maxw));
		if($new_h>$maxh){
			$new_h=$maxh;
			$new_w=ceil($iw/($ih/$maxh));
		}
	}else{
		$new_h=$maxh;
		$new_w=ceil($iw/($ih/$maxh));
	}
	$dst=imagecreatetruecolor ($new_w, $new_h);
	imagecopyresampled ($dst, $img, 0, 0, 0, 0, $new_w, $new_h, $iw, $ih);
	$ii=imagejpeg($dst,$to);
	imagedestroy($img);
	imagedestroy($dst);
	return array($new_w,$new_h);
}
if($type=check_file()){
	$file='tmp/'.$_SESSION['id'].$_SESSION['bf'];
	if($ss=move_real_size($_FILES['file']['tmp_name'], $file)){
		$nameFile=$_SESSION['id'].$_SESSION['bf'];
		$url='http://'.$_SERVER['HTTP_HOST'].'/advertiser/banner_creator/tmp/'.$_SESSION['id'].$_SESSION['bf'];
		echo'<link href="/advertiser/banner_creator/b_creator.css" rel="stylesheet" type="text/css" />';
		echo'<div class="num_img">',$_GET['b']+1,'</div>';
		echo'<img class="low_img" id="low_img" onclick="window.top.window.low_img_click(',$_GET['b'],')" src="/advertiser/banner_creator/img_low.php?s=135&f=',$url,'"/>';
		echo'<script language="javascript" type="text/javascript">window.top.window.ok_upload("',$nameFile,'",',$ss[0],',',$ss[1],');</script>';
		$_SESSION['bf']++;
	}	
}else include('upload_frame.php');
?>