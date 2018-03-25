<?php 
error_reporting(E_ALL);
session_start();
include('scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
include('scripts/language.php');
$title=content('title_home');
include("elements/head.php");
include("elements/menu.php");
?>
<div class="menu2">
<? include("elements/menu2index.php");?>
</div>
<?

include("elements/logo.php");
?>
 <div class="content">
 <!--<div>
<? echo content('home_text');?>
</div>
<div>-->
<?
include('news.php');
?>
</div>
<div style="clear:both;"></div>
 </div>
 <? include('elements/footer.php')?>
</body>
</html>