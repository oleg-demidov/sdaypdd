<?
session_start();
include('../../scripts/spritevtf.php');
include('../../scripts/gif_animated.php');
function convert($filename){
	$spr=new Convert2VTFSprite();
	$size=$_POST['count'];
	$origins=array();
	$urls=array();
	$delays=array();
	$names=array();
	for($i=0;$i<$size;$i++){	
		if(!isset($_POST['name'.$i]))
			break;
		$origins[$i]=array('x'=>$_POST['x'.$i],'y'=>$_POST['y'.$i]);
		$urls[$i]='tmp/'.$_POST['name'.$i];
		$delr=round($_POST['delay']/10);
		$delays[$i]=($delr<1)?1:$delr;
		$names[$i]='tmp/'.$_POST['name'.$i].'.gif';
	}
	$spr->path_watermark='../../images/sloy.png';
	$spr->origins=$origins;
	$spr->sizes=array('width'=>$_POST['width'],'height'=>$_POST['height']);
	if(!$spr->convert($urls,$filename.".spr",$filename.".vtf"))
		return $spr->error;
	$gif=new GIFEncoder($names,$delays,0,2,0, 0, 0,"url");
	fwrite ( fopen ( $filename.".gif", "wb" ), $gif->GetAnimation ( ) );
	return 0;
}
$url='http://'.$_SERVER['HTTP_HOST'].'/'.$_SESSION['lang'].'/advertiser/index.php?a=add_company&step=2';
if($error=convert('tmp/'.$_SESSION['id'])){
	$url.='&error='.$error;
}
if(isset($_POST['delay']))
	$url.='&delay='.$_POST['delay'];
header('Location:'.$url);

?>
