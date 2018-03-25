<?	

$error='';
$autobuy;
function check_data($bd,$error){
		global $data_server;
		global $autobuy;
		if(!$_POST['ip']){
			$error=content('empty_field').' "Ip"';
			return false;
		}
		if($bd->select('*',"servers","`ip`='".$_POST['ip']."'")&&$data_server[0]['ip']!=$_POST['ip']){
			$error=content('server_exists_in_db',$content_sa);
			return false;
		}
		if(!$_POST['port']){
			$error=content('empty_field').' "Port"';
			return false;
		}
		if(!$_POST['type']){
			$error=content('empty_field').' "Type"';
			return false;
		}
		return true;
	}

	if(isset($_POST['name'])){
		if(check_data($bd,$error)){
			$data_s=$_POST;
			$rez=$bd->update('servers',$data_s,array('id'=>$data_server[0]['id']));
			$data_server[0]=$data_s;
		}else include('../elements/errors.php');
	}
	if(isset($rez)){
		$suc=content('settings_saved');
		include('../elements/suc.php');
	}
	include('../elements/form_cfg_serv.php');
?>