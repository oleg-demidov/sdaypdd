<?
function save_jpegs($name, $dir, $id, $max = 100){
	if(!$img=open_img($_FILES[$name]['tmp_name']))
		return false;
	imagealphablending($img[2], false);
	imagesavealpha($img[2], true);
	if($img[0]>$img[1]){
		$nw = $max;
		$nh = get_proportion($img[0], $img[1], $max);
	}else{
		$nh = $max;
		$nw = get_proportion($img[1], $img[0], $max);
	}
	$img1 = img_res($img, $nw, $nh);
	$img2 = img_res($img, $nw*2, $nh*2);
	imagepng($img1, $dir.'/small'.$id.'.png');
	imagepng($img2, $dir.'/medium'.$id.'.png');
	imagepng($img[2], $dir.'/large'.$id.'.png');
	imagedestroy($img[2]);
	imagedestroy($img1);
	imagedestroy($img2);
}
function get_proportion($s1, $s2, $max = 100){
	return ceil($s2/($s1/$max));
}
function open_img($file){
	$ss=getimagesize($file);
	if(!$ss)
		return false;
	if($ss[2]==IMAGETYPE_GIF)$img=imagecreatefromgif($file);
	elseif($ss[2]==IMAGETYPE_JPEG)$img=imagecreatefromjpeg($file);
	elseif($ss[2]==IMAGETYPE_PNG)$img=imagecreatefrompng($file);
	return array($ss[0], $ss[1], $img);
}
function save_jpeg($name, $dir, $id, $max = 100){
	if(!$img=open_img($_FILES[$name]['tmp_name']))
		return false;
	$nw = $max;
	$nh = get_proportion($img[0], $img[1], $max);
	$nimg = img_res($img, $nw, $nh);
	imagejpeg($nimg, $dir.'/'.$id.'.jpg');
	imagedestroy($nimg);
}
function img_res($arr, $w, $h){
	$ni=imagecreatetruecolor ($w, $h);
	imagealphablending($ni, false);
	imagesavealpha($ni, true);
	imagecopyresampled ($ni, $arr[2], 0, 0, 0, 0, $w, $h, $arr[0], $arr[1]);
	return $ni;
}
?>