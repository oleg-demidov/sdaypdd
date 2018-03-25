<?
$host="localhost";
$user="root";
$pwd="87054503719";
$db=mysql_connect($host,$user,$pwd);
if($db)echo'connect!';
mysql_select_db("adv_game",$db);
mysql_query('SET NAMES utf8');
?>