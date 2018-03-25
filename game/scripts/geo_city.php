<? session_start();?>
<select name="geo_city" id="geo_city">
<option value="0">Any</option>
<option value="0">Любой</option>
<?
include('bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
$g_data=$bd->select('*','city_',array('id_country'=>$_GET['country']),'ORDER BY city_name_'.$_SESSION['lang']);
$sg=sizeof($g_data);
for($i=0;$i<$sg;$i++){
	echo'<option value="'.$g_data[$i]['id'].'">'.$g_data[$i]['city_name_'.$_SESSION['lang']].'</option>';
}
?>
</select>