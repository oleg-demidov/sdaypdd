<?php
error_reporting(E_ALL);
include('func.php');

define ("WAITTIME", 15); // Время ожидания перед стартом
define ("OUTRACE", 10); // Время таймаута гонки
define ("OUTTIME", 5); // Время выбывания

// Удалить всю гонку если в ней не осталось активных гонщиков в любом случае
$bd->sql("DELETE `race` FROM `race` INNER JOIN (SELECT `rall`.`id_race` FROM (SELECT `id_race`, count(*) AS `count` FROM `race` GROUP BY `id_race`) AS `rall` JOIN (SELECT `id_race`, count(*) AS `count` FROM `race` WHERE `last_query`<(UNIX_TIMESTAMP()-".OUTRACE.") GROUP BY `id_race`) AS `rout` ON `rall`.`id_race`=`rout`.`id_race` WHERE `rall`.`count`=`rout`.`count`) AS `rdel` ON `race`.`id_race`=`rdel`.`id_race`");

// Удалить гонщика если не дождался старта
$bd->sql("DELETE `race` FROM `race` WHERE `last_query`<(UNIX_TIMESTAMP()-".OUTTIME.") AND `status`='wait'");

// Обновить статус гонки на финиш если все гонщики либо повыбывали либо имеют 10 очков.
$bd->sql("UPDATE `race` JOIN (SELECT `id_race`, count(*) AS `outs` FROM `race` WHERE `last_query`<(UNIX_TIMESTAMP()-".OUTTIME.") OR `score`>9 GROUP BY `id_race`) AS `routs` ON `race`.`id_race` = `routs`.`id_race` JOIN (SELECT `id_race`, count(*) AS `count` FROM `race` GROUP BY `id_race`) AS `rcounts` ON `routs`.`id_race` = `rcounts`.`id_race` SET `race`.`status` = 'finish' WHERE `race`.`status`='start' AND `routs`.`outs`=`rcounts`.`count`");

// Получить ID новой гонки либо id ожидающей гонки
function get_id_race($id_user){
    global $bd;
    if($data = get_user_race($id_user)){
        //echo'ok';
        return $data[0]['id_race'];
    }
    if($data = $bd->get_all("SELECT `id_race` FROM `race` WHERE `status`='wait' GROUP BY `id_race` HAVING count( `id_race` ) < 4")){
        return $data[0]['id_race'];
    }
    //print_r($data);
    return rand(0, 1000000);
}

// Внести переменные пользователя и получить полный массив гонки
function race($data_racer){
    global $bd;
    unset($data_racer['r']);
    $id_race = get_id_race($data_racer['id_user']);
    $data_racer['last_query'] = time();
    $data_racer['id_race'] = $id_race;
    $data_racer['id_user'] = (int)$data_racer['id_user'];
    if(isset($data_racer['score'])){
        $data_racer['last_ans']=time();
    }
    //print_r($data_racer);
    $bd->insert_on_update('race', $data_racer);
    //print_r($id_race);
    //echo $bd->error;
    return get_race($id_race);
}

// Получить последнюю ожидающую гонку весь массив
function get_wait_race(){
    global $bd;
    return $bd->get_all("SELECT `race`.`user_status`,`race`.`id_race`, `race`.`status`, UNIX_TIMESTAMP(`race`.`time_enter`) AS `time_enter`, `race`.`id_user`, `race`.`num`, `race`.`car`, `race`.`last_query`, `race`.`score`, `social`.`first_name`, `social`.`last_name`, `users`.`name`, `social`.`avatar50`, `social`.`avatar100`, `race_place`.`place` FROM `race`  LEFT JOIN (SELECT `id_user`,(@rownum := @rownum + 1) AS `place` FROM `race` ORDER BY  `score` DESC ,  `last_ans` ASC )  AS `race_place` ON `race_place`.`id_user`=`race`.`id_user` LEFT JOIN `users` ON `users`.`id`=`race`.`id_user`  LEFT JOIN `social` ON `social`.`id_user`=`race`.`id_user` WHERE  `race`.`status`='wait' ORDER BY `race`.`time_enter`");
}

// Получить гонку по ее ID
function get_race($id){
    global $bd;
    return $bd->get_all("SELECT `race`.`user_status`,`race`.`id_race`, `race`.`status`, UNIX_TIMESTAMP(`race`.`time_enter`) AS `time_enter`, `race`.`id_user`, `race`.`num`, `race`.`car`, `race`.`last_query`, `race`.`score`, `social`.`first_name`, `social`.`last_name`, `users`.`name`, `social`.`avatar50`, `social`.`avatar100`, `race_place`.`place` FROM `race`  LEFT JOIN (SELECT `id_user`,(@rownum := @rownum + 1) AS `place` FROM `race` ORDER BY  `score` DESC ,  `last_ans` ASC )  AS `race_place` ON `race_place`.`id_user`=`race`.`id_user` LEFT JOIN `users` ON `users`.`id`=`race`.`id_user` LEFT JOIN `social` ON `social`.`id_user`=`race`.`id_user` WHERE `race`.`id_race`=? ORDER BY `race`.`time_enter`",array($id));
}

// Получить ID гонки по ID гонщика
function get_user_race($id_user){
    global $bd;
    return $bd->get_all("SELECT `id_race` FROM `race` WHERE `id_user`=?",array($id_user));            
}

// Стартовать если набралось 4 гонщика или хотябы больше одного и прошло некоторое время
function try_start($race){
    global $bd;
    if($race && (sizeof($race)>3 || ($race[sizeof($race)-1]['time_enter'] < time()-WAITTIME && sizeof($race)>1))){
        $bd->sql("UPDATE `race` SET `status`='start' WHERE `id_race`=?",array($race[0]['id_race']));
        return true;
    }
}


if(isset($_GET['try_id'])){
    $race = get_user_race($_GET['try_id']);
    if($race) echo json_encode($race[0]);
    else echo '[]';
    exit();
}

$bd->get_all("SET @rownum = 0");
if(isset($_GET['id_user'])){
    $race = race($_GET);
    try_start($race);
}else{    
    $race = get_wait_race();
}

if($race && $race[0]['status'] == 'finish'){
    for($i=0; $i<sizeof($race); $i++){
        if($race[$i]['last_query'] >  time()-OUTTIME && $race[$i]['user_status'] == 'user'){
            $bd->sql("INSERT INTO `race_stat`(`id_user`,`p".$race[$i]['place']."`) VALUES('".$race[$i]['id_user']."', 1) ON DUPLICATE KEY UPDATE `p".$race[$i]['place']."`=`p".$race[$i]['place']."`+1");
        }            
    }    
}

function normalize($data){
    if(($sd = sizeof($data)) && $data){
        for($i=0;$i<$sd;$i++){
            if($data[$i]['name'] == NULL)
                $data[$i]['name'] = 'Гость';
            if($data[$i]['avatar50'] == NULL){
                $data[$i]['avatar50'] = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava50.jpg';
                $data[$i]['avatar100'] = 'http://'.$_SERVER['HTTP_HOST'].'/images/def_ava100.jpg';
            }else{
                $data[$i]['name'] = $data[$i]['first_name'];
            }
        }
    }
    return $data;
}

echo json_encode(normalize($race));
?>