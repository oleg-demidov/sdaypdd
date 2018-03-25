<?php
include ('config.php');         // настройки

include('safemysql.class.php'); // база mysql
$db = new SafeMySQL($optdb);

if(isset($_GET['id'])){
    $res = $db->getAll("SELECT * FROM `files` WHERE `id`=?i", $_GET['id']);
    header( 'Content-Type: '.$res[0]['type'] );
    echo $res[0]['data'];
}
?>

