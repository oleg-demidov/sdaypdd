<div class="menu_cont">
<div id="logo_small">

</div>
<div class="menu">
<a href="<?php echo $host_lang;?>/monitoring"><? echo content('monitoring')?></a>
<?
if(isset($_SESSION['id'])){
	echo' <a href="'.$host_lang.'/server_admin/">'.content('my_server').'</a>';
	echo' <a href="'.$host_lang.'/advertiser/">'.content('my_ad').'</a>';
	echo' <a href="'.$host_lang.'/profile.php">'.content('profile').'</a>';
	echo' <a id="balanceurl" href="'.$host_lang.'/balance/index.php">'.content('balance').'</a>';
	}else{
	echo' <a href="'.$host_lang.'/infosa.php">'.content('for_server_adm').'</a>';
	echo' <a href="'.$host_lang.'/infoadv.php">'.content('for_advertiser').'</a>';
}
?>


</div>

</div>