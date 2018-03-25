<?php 
session_start();
include('scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
$content_sp=get_cont($lang,'save_pass');
include('scripts/autorization.php');

if(check_hash($bd))write_hash($bd,$_SESSION);									// проверка клиента
else header("Location: ".$host_lang."/login.php?logout=1");

$title=content('profile');
include("elements/head.php");
include("elements/menu.php");
?>
<div class="menu2">
<? include("elements/menu2index.php");?>
</div>
<div class="content">
<?


$data_p=$bd->select(array('name','email','purse','pass','type'),'users',array('id'=>$_SESSION['id']));
$error='';

function check_p_data($data_p){
	global $error;
	function compare($arr1,$arr2){
		if($arr1['name']!=$arr2['name'])
			return false;
		if($arr1['email']!=$arr2['email'])
			return false;
		if($arr1['purse']!=$arr2['purse'])
			return false;
		if($arr1['type']!=$arr2['type'])
			return false;
		if($arr1['pass']!=''&&$arr1['pass']!=$arr2['pass'])
			return false;
		return true;
	}
	
	if(!isset($_POST['name']))
		return false;
	if(compare($_POST,$data_p[0]))
		return false;
	if(isset($_POST['pass'])&&$_POST['pass']!=''){
		if($data_p[0]['pass']!=$_POST['pass_old']){
			$error=content('old_pass_incorrect',$content_sp);
			return false;
		}
	}else{
		unset($_POST['pass_old']);
		unset($_POST['pass']);
	}
	if($data_p[0]['purse']!=$_POST['purse']){
		if($data_p[0]['purse']!=''){
			$error=content('purse_cant_change',$content_sp);
			return false;
		}
	}
	return true;
}
if(check_p_data($data_p)){
	unset($_POST['pass_old']);
	if(isset($_POST['pass'])){
		$subj="Cs-money.net ".content('change_password',$content_sp);
		$text=content('attention_pass',$content_sp);
		$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
		$headers .= "From: support@cs-money.net \r\n"; 
		mail($data_p[0]['email'], $subj, $text, $headers);
	}
	$rez=$bd->update('users',$_POST,array('id'=>$_SESSION['id']));
	if($rez){
		$suc=content('settings_saved');
		include('elements/suc.php');
	}
}
if($error)include('elements/errors.php');

include('elements/form_profile.php');

?>
</div>
</body>
</html>