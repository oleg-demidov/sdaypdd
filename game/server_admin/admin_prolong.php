<?	if($_POST){
		$timeout=(!$_POST['prolong'])?0:(time()+(60*60*24*$_POST['prolong']));
		$rez=$bd->update('admins',array('timeout'=>$timeout,'activ'=>'on'),array("id"=>$_GET['admin']));
		if(isset($rez)){
				$suc=content('settings_saved');
				include('../elements/suc.php');
			}
		include('../server_admin/admins.php');
	}else{
		$data=$bd->select('*','admins',array("id"=>$_GET['admin']));
		$data=$data[0];
		include('../elements/form_prolong.php');
	}
?>