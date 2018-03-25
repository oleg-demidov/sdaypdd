<td <? if(isset($_GET['a'])&&$_GET['a']=='buy_time')echo'class="link_cfg"';?>>
<a href="<? echo $host_lang;?>/server_admin/index.php?a=buy_time&server=<? echo $_SESSION['server']?>"><? echo content('time');?></a>
 </td><td <? if(isset($_GET['a'])&&$_GET['a']=='privelegies')echo'class="link_cfg"';?>>
<a href="<? echo $host_lang;?>/server_admin/index.php?a=privelegies&server=<? echo $_SESSION['server']?>"><? echo content('i_priv',$content_sa);?></a>
 </td><td <? if(isset($_GET['a'])&&($_GET['a']=='my_offers'||$_GET['a']=='add_offer'||$_GET['a']=='del_offer'))echo'class="link_cfg"';?>>
<a href="<? echo $host_lang;?>/server_admin/index.php?a=my_offers&server=<? echo $_SESSION['server']?>"><? echo content('packages_priv',$content_sa);?></a>
 </td><td <? if(isset($_GET['a'])&&($_GET['a']=='promokeys'||$_GET['a']=='add_promokeys'||$_GET['a']=='del_keys'))echo'class="link_cfg"';?>>
<a href="<? echo $host_lang;?>/server_admin/index.php?a=promokeys&server=<? echo $_SESSION['server']?>"><? echo content('promocodes',$content_sa);?></a>
 </td><td <? if(isset($_GET['a'])&&($_GET['a']=='admins'||$_GET['a']=='add_admin'||$_GET['a']=='admin_prolong'||$_GET['a']=='del_admin' ))echo'class="link_cfg"';?>>
<a href="<? echo $host_lang;?>/server_admin/index.php?a=admins&server=<? echo $_SESSION['server']?>"><? echo content('admins',$content_sa);?></a>
 </td>