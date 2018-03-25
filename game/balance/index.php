<? 
session_start();						// подключение сессии
$notif='';

$a=false;
if(isset($_GET['a'])){
	$url=$_GET['a'].'.php';
	$handle = opendir("../balance"); 
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
$content_tn=get_cont($lang,'transactions');
include('../scripts/autorization.php');
if(check_hash($bd))write_hash($bd,$_SESSION);									// проверка клиента
else header("Location: ".$host_lang."/login.php?logout=1");		
	
$title=content('cfg_server_admin');						// отправка Header
include("../elements/head.php");
include("../elements/menu.php"); //Главное меню

echo'<div class="menu2">';

include('../elements/menu2index.php');
echo'</div><div class="content">';
?>
 <div
<?
	if(isset($_GET['server']))echo' class="subcont">';
	else echo'>';
	if(!$a)include('balance.php');
	else include($url);
?>
</div>
<div style="clear:both;">
</div></div> 
</body>
</html>