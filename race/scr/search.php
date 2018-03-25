<?php
include('func.php');

function get_race(){
    global $bd;
    return $bd->get_all("SELECT `race`.`id_race`, `race`.`id_user`, `race`.`num`, `race`.`car`, `race`.`last_query`, `race`.`score`, `social`.`first_name`, `social`.`last_name`, `users`.`name`, `social`.`avatar50`, `social`.`avatar100` FROM `race`  LEFT JOIN (SELECT count( `id_race` ) AS `count` , `id_race` FROM `race` GROUP BY `id_race` LIMIT 1) AS `lim_race` ON `race`.`id_race` = `lim_race`.`id_race`  LEFT JOIN `users` ON `users`.`id`=`race`.`id_user` LEFT JOIN `social` ON `social`.`id_user`=`race`.`id_user` WHERE `lim_race`.`count` <4 AND `race`.`status`='pre'");
}

$data = get_race();
echo json_encode($data);
?>