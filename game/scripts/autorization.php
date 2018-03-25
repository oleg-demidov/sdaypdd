<?php
define('TIMEAUTHMIN',20);
function autorization($bd){
	global $error;
	if(isset($_GET['logout'])&&isset($_SESSION['id'])){
		/*$set=array(	'id'=>$_SESSION['id'],
					'hash'=>$hash,
					'time'=>$time,
					'ip'=>$ip);
		$bd->insert_on_update('sessions',$set)*/
		setcookie($_SESSION['id'],'',(time()-3600),'/');
		unset($_SESSION['id']);
		return false;
	}
	if(!isset($_POST['email']))
		return false;
	$arr=array("email"=>$_POST['email']);
	if(!$data=$bd->select("*","users",$arr)){
		$error=content('email_not');
		return false;
	}
	if($data[0]['ban']==1){
		$error.=content('account_blocked');
		return false;
	}
	if($data[0]['pass']!=$_POST['pass']){
		$error.=content('not_valid_pass');
		return false;
	}
	return $data[0];
}
function write_hash($bd,$data){
	global $error;
	$time=time();
	$ip=$_SERVER['REMOTE_ADDR'];
	$hash=md5($data['id'].$data['pass'].$time.$ip);
	if(!setcookie($data['id'],$hash,($time+(TIMEAUTHMIN+2)*60),'/')){
		$error=content('cant_cookie');
		return false;
	}
	$set=array(	'id'=>$data['id'],
				'hash'=>$hash,
				'time'=>$time,
				'ip'=>$ip);
	if(!$bd->insert_on_update('sessions',$set)){
		$error=$bd->error;
		return false;
	}
	return true;
}
function check_hash($bd){
	global $error;
	if(!isset($_SESSION['id']))
		return false;
	//echo' id='.$_SESSION['id'];
	if(!$sessions=$bd->select('*','sessions',array('id'=>$_SESSION['id']))){
		$error.=$bd->error;
		$_SESSION['error']=content('auth_error');
		return false;
	}
	if($_COOKIE[$_SESSION['id']]!=$sessions[0]['hash']){
		$_SESSION['error']=content('auth_cookie');
		return false;
	}
	if(($sessions[0]['time']+TIMEAUTHMIN*60)<time()){
		$_SESSION['error']=content('auth_expired');
		return false;
	}
	return true;
}

?>