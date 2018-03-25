<?php
include ('config.php');         // настройки

include('safemysql.class.php'); // база mysql
$db = new SafeMySQL($optdb);

include ('vk.php');             // класс для вконтакта
$vk = new VK($optvk_iframe);

include ('scopes.php');         // проверка прав 

$q = "SELECT count( `id` ) AS `agent_c`,(SELECT count(`marks`.`id_subj`) FROM (SELECT `id_subj` FROM `markers` GROUP BY `id_subj`) AS `marks` LEFT JOIN  `agents` ON `agents`.`id`=`marks`.`id_subj` WHERE `agents`.`id` IS NULL) AS `marker_c` FROM `agents` ";
$res = $db->getAll($q);

sendNotif("В черном списке: ".$res[0]['agent_c']."\nВ сером списке: ".$res[0]['marker_c']);

?>
