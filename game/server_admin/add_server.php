<?php 
class SERV{
	var $error='';
	var $bd;
	function __construct($bd){
		$this->bd=$bd;
	}
	function check_data(){
		if(!$_POST['ip']){
			$this->error=content('empty_field').' "Ip"';
			return false;
		}
		if(!$_POST['port']){
			$this->error=content('empty_field').' "Port"';
			return false;
		}
		if(!$_POST['type']){
			$this->error=content('empty_field').' "Type"';
			return false;
		}
		if($this->bd->select('*',"servers","`ip`='".$_POST['ip']."'AND`port`='".$_POST['port']."'")){
			$this->error=content('ip_already_exists',$content_sa);
			return false;
		}
		return true;
	}
	function create_serv(){
		if(!$this->check_data())
			return false;
		$arr=$_POST;
		$arr['id']=rand(1,16700000);
		$arr['id_user']=$_SESSION['id'];
	/*	if(!$this->new_user_plugin($arr['id'],$_POST['ip'])){
			$this->error='Ошибка добавления плагина! '.$this->bd->error;
			return false;
		}*/
		if(!$this->bd->insert("privileges",array('id_server'=>$arr['id']))){
			$this->error=' CFG! '.$this->bd->error;
			return false;
		}
		if(!$this->bd->insert("servers",$arr)){
			$this->error='No add! '.$this->bd->error;
			return false;
		}
		
		return true;
	}

}

if(isset($_POST['name'])){
	
	$servObj=new SERV($bd);
	
	if($servObj->create_serv())
		include('my_servers.php');
	else {
		$error=$servObj->error;
		include('../elements/errors.php');
		include("../elements/form_add_serv.php");
	}
}else include("../elements/form_add_serv.php");
?>
