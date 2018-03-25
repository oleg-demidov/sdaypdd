<?	
$error='';

$cust_desc=$bd->select('*','priv_default_desk',array('type'=>$data_server[0]['type']));
$cust_desc=$cust_desc[0];
$data=$bd->select('*','privileges',array('id_server'=>$data_server[0]['id']));
$data=$data[0];
$need_flags=array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','z'
		);
function check_form(){
	if(!$_POST)return false;
	global $error;
	global $need_flags;
	global $lang;
	$need=array(
		"name"=>array('ru'=>"Ник",'en'=>'Name'),
		
		//"age"=>"Возраст",
		//"email"=>"Email",
		//"pass"=>"Пароль",
		"days"=>array('ru'=>"Время",'en'=>'Time')
	);
	foreach($need as $k=>$v){
		if(!$_POST[$k]){
			$error=content('empty_field')." ".$v[$lang];
			return false;
		}
	}
	$n=0;
	foreach($need_flags as $k=>$v)
		if(isset($_POST[$v]))$n++;
	if(!$n){
		$error=content('un_priv',$content_sa);
		return false;
	}
	return true;
}

if(check_form()){
	function get_flags(){
		$data=$_POST;
		$flags=array();
		$need=array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','z'
		);
		foreach($need as $v){
			if(isset($data[$v])){
				$flags[]=$v;
				unset($data[$v]);
			}
		}
		if(isset($_POST['ip'])&&$_POST['ip']!='')
			$data['enter_type']='ip';
		elseif(isset($_POST['steam'])&&$_POST['steam']!='')
			$data['enter_type']='steam';
		elseif(!isset($_POST['name'])||$_POST['name']==''){
			$error=content('should_be',$content_sa);
			return false;
		}else $data['enter_type']='name';
		$data['flags']=implode(',',$flags);
		return $data;
	}
	$dataq=get_flags();
	$dataq['activ']='on';
	$dataq['id_user']=$_SESSION['id'];
	$dataq['timeout']=time()+(60*60*24*$dataq['days']);
	$dataq['id_server']=$_SESSION['server'];
	$dataq['id']=rand(1,100000000);
	$rez=$bd->insert('admins',$dataq);
	if($rez){
		$suc=content('admin_added',$content_sa);
		include('../elements/suc.php');
	}
	include('../server_admin/admins.php');
}else{
	if($error!='')include('../elements/errors.php');
	include('../elements/form_add_admin.php');
}

?>