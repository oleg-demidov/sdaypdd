<? 
session_start();						// подключение сессии
$notif='';

$a=false;
if(isset($_GET['a'])){
	$url=$_GET['a'].'.php';
	$handle = opendir("../server_admin"); 
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
$content_sa=get_cont($lang,'server_admin');
include('../scripts/autorization.php');
if(check_hash($bd))write_hash($bd,$_SESSION);									// проверка клиента
else header("Location: ".$host_lang."/login.php?logout=1");		
	
$title=content('cfg_server_admin');						// отправка Header
include("../elements/head.php");
include("../elements/menu.php"); //Главное меню
include("../elements/menu_server_admin.php"); // главное меню администратора серверов

echo'<div class="content">';
//предупреждения
/*
if(!$_SESSION['activ'])$notif='Вы должны подтвердить свой Email. Некоторые функции могут быть недоступны';
include('../elements/notif.php');*/

function get_server($bd){
	if(!isset($_GET['server']))
		return false;
	return $bd->select('*','servers',array("id"=>$_GET['server']));
}
if($data_server=get_server($bd)){
	$_SESSION['server']=$_GET['server'];
	include("../elements/menu_server.php"); // меню сервера
}else if(isset($_GET['server']))$url='my_servers.php';

?>
 <div
<?
	if(isset($_GET['server']))echo' class="subcont">';
	else echo'>';
	if(!$a)include('my_servers.php');
	else include($url);
?>
</div>
<div style="clear:both;">
</div></div> 
</body>
</html>