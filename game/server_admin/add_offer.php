<?	
$an=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','z');
if($_POST){
	$schet=0;
	foreach($an as $key=>$val){
		if(isset($_POST[$val]))
			$schet++;
	}
	$data=$_POST;
	if(!$schet){
		$error=content('you_have_no_flag',$content_sa);
		include('../elements/errors.php');
		include('../elements/form_offer.php');
	}else{
		$dataQ=array();
		$dataQ['id']=rand(0,1000000);
		$dataQ['id_server']=$data_server[0]['id'];
		$dataQ['price']=$data['price'];
		$dataQ['name']=$data['name'];
		$dataQ['flags']='';
		foreach($an as $key=>$val){
			if(isset($data[$val])){
				$dataQ['flags'].=$val.',';
			}
		}
		$dataQ['flags']=substr_replace ($dataQ['flags'],'', -1);
		$rez=$bd->insert_on_update('offers',$dataQ);
		if(!$rez)include('../elements/form_offer.php');
		else include('my_offers.php');
	}
}else include('../elements/form_offer.php');
?>