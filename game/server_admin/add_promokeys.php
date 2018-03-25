<?	if(isset($_POST['days'])){
		$id_pkod=rand(0,10000000);
		$id_server=$_SESSION['server'];
		$id_offer=$_POST['offer'];
		$days=$_POST['days'];
		function generate_kod($length = 8){
		  $chars = 'ABDEFGHKNQRSTYZ';
		  $numChars = strlen($chars);
		  $string = '';
		  for ($i = 0; $i < $length; $i++) {
			$string .= substr($chars, rand(1, $numChars) - 1, 1);
		  }
		  return $string;
		}
		for($i=0;$i<5;$i++){
			$rez=$bd->insert('promo_keys',array('id'=>$id_pkod,'id_server'=>$id_server,'id_offer'=>$id_offer,'days'=>$days,'key'=>generate_kod()));
		}
		if(isset($rez)){
				$suc=content('settings_saved');
				include('../elements/suc.php');
			}
		include('../server_admin/promokeys.php');
	}else{
		include('../elements/form_promokeys.php');
	}
?>