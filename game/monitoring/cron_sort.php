<?
include('/home/httpd/vhosts/cs-money.net/httpdocs/scripts/bd.inc.php');		// подключение базы SQL
$bd=new BD($user_bd,$pass_bd,$bd_custom,$host_bd);
$servers=$bd->get_result($bd->sql_query("SELECT `id` FROM `servers` GROUP BY `id`"));
$ss=sizeof($servers);
$queryI='INSERT INTO `monitoring`(`id_server`,`time`,`players`,`type`)';
$tt=strtotime('now 00:00:00');
for($i=0;$i<$ss;$i++){
	$sel="SELECT SUM(`players`) AS `sum_players` FROM `monitoring` WHERE `id_server`='".$servers[$i]['id']."' AND `type`='hour'";
	$sel=$bd->get_result($bd->sql_query($sel));
	$query=$queryI." VALUES('".$servers[$i]['id']."','".$tt."','".$sel[0]['sum_players']."','day')";
	$bd->sql_query($query);
}
print_r($sel);
echo '<br>',$query;
echo '<br>',$bd->error;
$bd->sql_query("DELETE FROM `monitoring` WHERE `type`='hour' ");	
?>