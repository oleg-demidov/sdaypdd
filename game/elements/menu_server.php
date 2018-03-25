


<div class="menu_server">
<div class="headerserver" style="background-image: url(<? echo $host;?>/images/<? echo $data_server[0]['type']?>.png);" ><div style="margin-top:-10px;"><? echo $data_server[0]['name']; ?></div>
<div style="margin-top:-26px;"><a href="<? echo $host_lang;?>/server_admin/index.php?a=server_cfg&server=<? echo $_SESSION['server']?>"><? echo content('change_data',$content_sa);?></a><br><a href="<? echo $host_lang;?>/server_admin/index.php?a=buy_time&server=<? echo $_SESSION['server']?>"><? echo content('auto_sales_priv',$content_sa);?></a><br><a href="<? echo $host_lang;?>/server_admin/index.php?a=adv_conf&server=<? echo $_SESSION['server']?>"><? echo content('ad_settings',$content_sa);?></a></div></div>
<table cellpadding="0" cellspacing="0" class="table_menu_serv">
<tr>
<? 
	if($_GET['a']=='server_cfg') echo'<td class="link_cfg">',content('change_data',$content_sa),'</td>';
	if($_GET['a']=='privelegies'||$_GET['a']=='my_offers'||$_GET['a']=='del_offer'||$_GET['a']=='del_admin'||$_GET['a']=='del_keys'||$_GET['a']=='promokeys'||$_GET['a']=='buy_time'||$_GET['a']=='admins'||$_GET['a']=='add_offer'||$_GET['a']=='add_promokeys'||$_GET['a']=='admin_prolong'||$_GET['a']=='add_admin') include('privileges.php');
?>
<? if($_GET['a']=='adv_conf') echo'<td class="link_cfg">',content('advertisement'),'</td>';?>
</tr></table>
</div>