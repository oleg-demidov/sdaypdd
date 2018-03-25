<?php 
error_reporting(E_ALL);
session_start();
include('scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
$title="Игровая биржа баннеров";
include("elements/head.php");
include("elements/menu.php");
?>
<div class="menu2">
<? include("elements/menu2index.php");?>
</div>
 <div class="content">
<h3>Запрашиваемой страницы не существует</h3>
 </div>
 <? include('elements/footer.php')?>
</body>
</html>