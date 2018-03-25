<?php
include('func.php');

$bd->get_all("SET @rownum = 0");
$race = $bd->get_all(" SELECT `id`,  (@rownum := @rownum + 1) AS `rowNumber` FROM `users`");

echo json_encode($race);
?>