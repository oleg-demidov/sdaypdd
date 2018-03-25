<? 
session_start();						// подключение сессии
$notif='';


$a=false;
if(isset($_GET['a'])){
	$url=$_GET['a'].'.php';
	$handle = opendir("../advertiser"); 
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
include('../scripts/language.php');
$content_adv=get_cont($lang,'advertiser');
include('../scripts/autorization.php');
if(check_hash($bd))write_hash($bd,$_SESSION);									// проверка клиента
else header("Location: http://".$_SERVER['HTTP_HOST']."/login.php?logout=1");		
	
$title=content('cfg_advertiser');						// отправка Header
include("../elements/head.php");

include("../elements/menu.php"); //Главное меню

include("../elements/menu_advertiser.php"); // главное меню администратора серверов

//предупреждения
/*if(!$_SESSION['activ'])$notif='Вы должны подтвердить свой Email. Некоторые функции могут быть недоступны';
include('../elements/notif.php');*/
?>
<div class="content">
<?
	
	if(!$a)include('company.php');
	else include($url);
?>
</div>
</body>
</html>