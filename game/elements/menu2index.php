<a href="<? echo $host_lang;?>/index.php" class="apic" title="<? echo content('home')?>" id="a_home"></a>
<a href="<? echo $host_lang;?>/feedback.php" class="apic" title="<? echo content('feedback')?>" id="a_mail"></a>
<a href="<? echo $host_lang;?>/rss.php" class="apic" id="a_rss"></a>
<?
$vars=$bd->select('value',"variables",array('name'=>'admin_id'));
if(isset($_SESSION['id'])){
	if($vars[0]['value']==$_SESSION['id'])
		echo' <a href="'.$host_lang.'/adminka/" class="apic" id="a_adm"></a>';
}
?>