<?	if(isset($_POST['s'])){
		$data=array();
		if(!isset($_POST['adv_used']))$data['adv_used']='off';
		else $data['adv_used']='on';
		$data['cap']=$_POST['cap'];
		$data_server[0]['adv_used']=$data['adv_used'];
		$data_server[0]['cap']=$data['cap'];
		$rez=$bd->update('servers',$data,array("id"=>$_SESSION['server']));
		if(isset($rez)){
			$suc=content('settings_saved');
			include('../elements/suc.php');
		}
	}
	include('../elements/form_adv.php');
?>