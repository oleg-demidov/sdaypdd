<?php
class REG extends BD{
	var $error='';
	var $id;
	function check_data(){
		if(!isset($_POST['name'])){
			return false;
		}
		if(!isset($_POST['email'])){
			$this->error='Поле "email" не заполнено!';
			return false;
		}else if(!preg_match('|([a-z0-9_\.\-]{1,20})@([a-z0-9\.\-]{1,20})\.([a-z]{2,4})|is', $_POST['email'])){
			$this->error='Некорректный email адрес! ';
			return false;
		}
		if($this->check_email($_POST['email'])){
			$this->error="Такой email уже зарегестрирован";
			return false;
		}
		if(!isset($_POST['pass'])){
			$this->error='Поле "Пароль" не заполнено!';
			return false;
		}
		if($_POST['pass2']!=$_POST['pass']){
			$this->error='Пароли не совпадают!';
			return false;
		}
		return true;
	}
	function check_email($email){
		$arr=array("email"=>"$email");
		return $this->select("email","users",$arr);
	}
	
	function create_user(){
		if(!$this->check_data())
			return false;
		$arr=$_POST;
		unset($arr['pass2']);
		$this->id=$arr['id']=rand(0,1670000);
		return $this->insert("users",$arr);
	}
}
?>
