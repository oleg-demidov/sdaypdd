<?
$ss=getimagesize($_GET['f']);
if($ss){
	header('Content-Type: image/jpeg');
	if($ss[2]==IMAGETYPE_GIF)$img=imagecreatefromgif($_GET['f']);
	elseif($ss[2]==IMAGETYPE_JPEG)$img=imagecreatefromjpeg($_GET['f']);
	elseif($ss[2]==IMAGETYPE_PNG)$img=imagecreatefrompng($_GET['f']);
	$iw=$ss[0];
	$ih=$ss[1];
	$max=$_GET['s'];
	if($iw>$ih){
		$new_w=$max;
		$new_h=ceil($ih/($iw/$max));
	}else{
		$new_h=$max;
		$new_w=ceil($iw/($ih/$max));
	}
	$dst=imagecreatetruecolor ($new_w, $new_h);
	imagecopyresampled ($dst, $img, 0, 0, 0, 0, $new_w, $new_h, $iw, $ih);
	imagejpeg ($dst);
	imagedestroy($img);
	imagedestroy($dst);
}
?>