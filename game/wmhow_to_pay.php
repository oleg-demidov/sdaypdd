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

 <div class="content">
<?
$to_sa=$bd->select('*','content',array('category'=>'wm_how'),'ORDER BY `time` DESC',10);
$to_sa_h=$bd->select('*','content_'.$lang,array('id'=>$to_sa[0]['value0']));
$to_sa_m=$bd->select('*','content_'.$lang,array('id'=>$to_sa[0]['value1']));
echo'<h3>',$to_sa_h[0]['value'],'</h3>';
echo'<p>',$to_sa_m[0]['value'],'</p>';

?>
</div>
<div style="clear:both;"></div>
 </div>
 <? include('elements/footer.php')?>
</body>
</html>