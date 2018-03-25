<?php 
session_start();

include('../scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('../scripts/language.php');
$title="Игровая биржа баннеров/Новости";
include("../elements/head.php");
include("../elements/menu.php");
?>
<div class="menu2">
<? include("../elements/menu2index.php");?>
</div>
<div class="content">
<?
if(isset($_GET['server']))
	include("../elements/server.php");
else
	include("../elements/monitoring.php");?>

</div>
</body>
</html>