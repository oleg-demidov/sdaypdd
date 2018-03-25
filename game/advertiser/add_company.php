<h1><? echo content('create_campaign',$content_adv);?></h1>
<?
function check_form(){
	if(!$_POST)return false;
	global $error;
	global $content_adv;
	$need=array(
			"header"=>content('title'),
			"mess"=>content('message',$content_adv),
			"site"=>content('link'),
			"budget"=>content('budget')
		);
	foreach($need as $k=>$v){
		if(!$_POST[$k]){
			$error=content('empty_field').$v;
			include('../elements/errors.php');
			return false;
		}
	}
	return true;
}
if(!isset($_GET['step'])||!$_GET['step'])
	include('banner_creator/form_create_banner.php');
elseif($_GET['step']==2)
	include('../elements/form_company.php');
elseif($_GET['step']==3&&check_form()){
	$idC=rand(1,1000000);
	$source='banner_creator/tmp/'.$_SESSION['id'];
	$dest='../sprites/';
	copy($source.'.gif',$dest.'gifs/'.$idC.'.gif');
	copy($source.'.vtf',$dest.$idC.'.vtf');
	copy($source.'.spr',$dest.$idC.'.spr');
	$data=$_POST;
	$data['id_user']=$_SESSION['id'];
	$data['id']=$idC;
	if($_GET['delay'])
		$data['delay']=1000/$_GET['delay'];
	$rez=$bd->insert_on_update('companies',$data);
	if($rez){
		$suc=content('settings_saved');
		include('../elements/suc.php');
		include('company.php');
	}
}else
	include('../elements/form_company.php');
?>
