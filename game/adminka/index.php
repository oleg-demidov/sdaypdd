<? 
session_start();						// подключение сессии
$notif='';

$a=false;
if(isset($_GET['a'])){
	$url=$_GET['a'].'.php';
	$handle = opendir("../adminka/admin_scr"); 
	while (($file = readdir($handle))!== false){ 
		if($url==$file&&$url!='index.php'){
			$a=true;
			break; 
		}
	} 
	closedir($handle);
}

include('../scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
$v_adm=$bd->select('value','variables',array('name'=>'admin_id'));
include('../scripts/language.php');
// проверка клиента
include('../scripts/autorization.php');
if(check_hash($bd)&&$v_adm[0]['value']==$_SESSION['id'])write_hash($bd,$_SESSION);
else header("Location: ".$host_lang."/login.php?logout=1");		
	
$title='Админка';						// отправка Header
include("../elements/head.php");

include("../elements/menu.php"); //Главное меню

include("../elements/menu_adminka.php"); // главное меню администратора серверов

//предупреждения
/*
if(!$_SESSION['activ'])$notif='Вы должны подтвердить свой Email. Некоторые функции могут быть недоступны';
include('../elements/notif.php');*/

?>
<div class="content">
<?
	if(!$a)include('admin_scr/companies.php');
	else include('admin_scr/'.$url);
?>
</div>
</body>
</html>