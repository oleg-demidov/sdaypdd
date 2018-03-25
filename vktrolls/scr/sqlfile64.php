<?php
include ('config.php');         // настройки

include('safemysql.class.php'); // база mysql
$db = new SafeMySQL($optdb);

if(isset($_GET['id'])){
    $res = $db->getAll("SELECT * FROM `files` WHERE `id`=?i", $_GET['id']);
    header( 'Content-Type: text/plain'/*.$res[0]['type']*/ );
    echo 'data:',$res[0]['type'],';base64,',base64_encode($res[0]['data']);
}
?>
