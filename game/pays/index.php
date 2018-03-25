<? 
$notif='';

$a=false;
if(isset($_GET['a'])){
	$url=$_GET['a'].'.php';
	$handle = opendir("../pays"); 
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
	
$title=content('payment',$content_tn);						// отправка Header
include("../elements/head_buy.php");


?>
<div class="content"  style="width:800px;"><div style="float:left;" id="buy_form"></div>
<div style="float:right;"><? include('../elements/languages.php');?></div>
<div style="clear:both;"></div>
<?php
	if(!$a)include('fill.php');
	else include($url);
?>
<div style="clear:both;"></div>
</div> 
</body>
</html>