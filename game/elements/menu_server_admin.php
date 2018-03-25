<div class="menu2">
<a href="<? echo $host_lang;?>/server_admin/index.php?a=stat&id_s=<? echo $_SESSION['server']?>"><? echo content('stat');?></a> &nbsp;
<a href="<? echo $host_lang;?>/server_admin/index.php?a=my_servers"><? echo content('servers',$content_sa);?></a> 
<a href="<? echo $host_lang;?>/server_admin/index.php?a=support"><? echo content('support')?></a>&nbsp;
<a style="color:#6FE6FF;" href="<? echo $host_lang;?>/server_admin/index.php?a=faq&c=faq_server"><? echo content('faq')?></a>
<? include('../elements/menu2index.php');?>

</div>