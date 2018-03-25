<?	$data=$bd->select(array('time_type','time_week','time_from','time_to','autobuy','rank'),'privileges',array("id_server"=>$_SESSION['server']));
	$data=$data[0];
	$flags=explode(',',$data['time_week']);
	$flags=array_combine($flags,$flags);
	$data['time_from']=date('d-m-Y',$data['time_from']);
	$data['time_to']=date('d-m-Y',$data['time_to']);
	if($_POST){
		$data=$_POST;
		function get_flags($data,$flagsf){
			$flags=array();
			$need=array(
			'pn','vt','sr','ch','pt','sb','vs'
			);
			foreach($need as $v){
				if(isset($data[$v])){
					$flagsf[$v]=$v;
					$flags[]=$v;
					unset($data[$v]);
				}else unset($flagsf[$v]);
			}
			$data['time_week']=implode(',',$flags);
			return $data;
		}
		$dataq=get_flags($data,$flags);
		$dataq['time_from']=strtotime($dataq['time_from']);
		$dataq['time_to']=strtotime($dataq['time_to']);
		if(!isset($_POST['autobuy']))
		$dataq['autobuy']='off';
		else $dataq['autobuy']='on';
		
		$rez=$bd->update('privileges',$dataq,array("id_server"=>$_SESSION['server']));
		if($rez){
			$suc=content('settings_saved');
			include('../elements/suc.php');
		}else{
			$error=$bd->error;
			include('../elements/errors.php');
		}
	}
	include('../elements/form_time.php');
?>