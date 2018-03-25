<?
function generate_kod($length = 8){
	  $chars = 'ABDEFGHKNQRSTYZ';
	  $numChars = strlen($chars);
	  $string = '';
	  for ($i = 0; $i < $length; $i++) {
		$string .= substr($chars, rand(1, $numChars) - 1, 1);
	  }
	  return $string;
}
$error='';
if(isset($_GET['server'])){
	include('scripts/bd.inc.php');		// подключение базы SQL
	$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
	include('scripts/language.php');
	$content_buy=get_cont($lang,'buy_admin');
	$title=content('buy_priv',$content_buy);
	include("elements/head_buy.php");
	echo'<div class="content" style="width:800px;"><div id="buy_form"></div><div style="float:right;">';
	include('elements/languages.php');
	echo'</div><div style="clear:both;"></div>';
	$data_server=$bd->select('*','servers',array("id"=>$_GET['server']));
	if(isset($_POST['promocode'])){
		$key=$bd->select('*','promo_keys',array('key'=>$_POST['promocode']));
		if($key){
			$rez=$bd->update('promo_keys',array('key'=>generate_kod()),array('key'=>$_POST['promocode']));
			$offer=$bd->select('*','offers',array('id'=>$key[0]['id_offer']));
			$dataForm=$bd->select('*','sess_buy',array('id'=>$_GET['sess_id']));
			$dataForm=unserialize($dataForm[0]['value']);
			$dataForm['flags']=$offer[0]['flags'];
			$dataForm['days']=$key[0]['days'];
			$dataForm['id']=rand();
			$dataForm['id_server']=$_GET['server'];
			$dataForm['activ']="on";
			$dataForm['timeout']=time()+$key[0]['days']*24*60*60;
			$bd->insert('admins',$dataForm);
			$bd->delete_str('sess_buy',array('id'=>$_GET['sess_id']));
			include("elements/form_activ_promo.php");
		}
	}
	include("elements/form_buy_promo.php");
	
}
?></div>
</body>
</html>