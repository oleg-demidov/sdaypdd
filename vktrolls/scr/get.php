<?php
header('Access-Control-Allow-Origin: http://vktrolls.loc');
//header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Credentials: true');
//if(ini_set('display_errors','On')) echo"'display_errors','On'";
//ini_set('log_errors', 'On');
//ini_set('error_log', '/var/log/php5/php_errors.log');
//error_reporting('E_ALL');
include ('config.php');         // настройки

include('safemysql.class.php'); // база mysql
$db = new SafeMySQL($optdb);

include ('vk.php');             // класс для вконтакта
if(isset($_GET['ext']) && $_GET['ext'] == 1){
    //echo 'ext';
    $vk = new VK($optvk_stnd);
}else $vk = new VK($optvk_iframe);

include ('lwz.php');
include ('scopes.php');         // проверка прав 
$scope = checkUserAccess();
if($id_user) 
    set_scope_token($_GET['access_token'], $scope, $id_user);
//echo $scope;
//sendNotif('Добавлен');
//if(get_scope_token('dfgdfd')===0)
//print_r(1);
$needAccess = array(
        'grey' => 2,
        'marker' => 1,
        'addmarker' => 1,
        'marker' => 2,
        'toblack' => 2,
        'addcat' =>3,
        'remove_black' => 3,
        'basket' => 2,
        'frombasket' => 2,
        'tobasket' => 2,
        'remove' => 3,
        'upload' => 2,
        'removecat' => 3
    );
if(isset($_GET['a'])){
    if(isset($needAccess[$_GET['a']])){
        if($scope < $needAccess[$_GET['a']]){
            echo '[{"error":"Нет доступа", "scope":"',$scope,'","error":',$error,'}]';
            exit();
        }
    }
}
                                           // запросы // 
$from = isset($_GET['from'])?$_GET['from']:0;
$to = isset($_GET['to'])?$_GET['to']:10;
if(isset($_GET['a'])){
    switch ($_GET['a']) {
        case 'grey':
            $q = $db->parse("SELECT `cats`.`name` AS `catname`, `cats`.`id` AS `catid`, `markers`.`id_subj` AS `agent`,`agents`.`id_user` AS `admin`, `markers`.`date`,  GROUP_CONCAT(`markers`.`id_user` SEPARATOR ',') AS `markers`
FROM `markers` LEFT JOIN  `agents` ON `agents`.`id`=`markers`.`id_subj` 
LEFT JOIN  `cats` ON `markers`.`cat`=`cats`.`id` WHERE `agents`.`id` IS NULL
GROUP BY `markers`.`id_subj` ORDER BY `markers`.`date` DESC LIMIT ?i,?i", $from, $to);
            break;
        case 'user':
            $q = $db->parse("SELECT `cats`.`name` AS `catname`, `cats`.`id` AS `catid`,`cats`.`id_file`, `agents`.`id` AS `agent`, `agents`.`id_user` AS `admin`, `agents`.`date`, `agents`.`comm` AS `commadmin`, GROUP_CONCAT(`markers`.`id_user` SEPARATOR ',') AS `markers`
FROM  `agents` LEFT JOIN  `markers` ON `agents`.`id`=`markers`.`id_subj` LEFT JOIN  `cats` ON `agents`.`cat`=`cats`.`id` 
WHERE `agents`.`id`=?i AND `agents`.`basket`=0 GROUP BY `id_subj`  ", $_GET['user']);
            break;
        case 'black':
            $q = $db->parse("SELECT FROM_UNIXTIME( `agents`.`date` , '%d.%m.%y %H:%i ' ) AS `ftime` , `cats`.`name` AS `catname`, `cats`.`id_file`, `cats`.`id` AS `catid`, `agents`.`id` AS `agent`, `agents`.`id_user` AS `admin`, `agents`.`date`, `agents`.`comm` AS `commadmin`, GROUP_CONCAT(`markers`.`id_user` SEPARATOR ',') AS `markers`
FROM  `agents` LEFT JOIN  `markers` ON `agents`.`id`=`markers`.`id_subj` LEFT JOIN  `cats` ON `agents`.`cat`=`cats`.`id` 
WHERE `agents`.`id`>0 AND `agents`.`basket`=0 GROUP BY `id_subj` ORDER BY `agents`.`date` DESC LIMIT ?i,?i", $from, $to);
            break;
        case 'gblack':
            $q = $db->parse("SELECT FROM_UNIXTIME( `agents`.`date` , '%d.%m.%y %H:%i ' ) AS `ftime` , `cats`.`name` AS `catname`, `cats`.`id_file`, `cats`.`id` AS `catid`, `agents`.`id` AS `agent`, `agents`.`id_user` AS `admin`, `agents`.`date`, `agents`.`comm` AS `commadmin`, GROUP_CONCAT(`markers`.`id_user` SEPARATOR ',') AS `markers`
FROM  `agents` LEFT JOIN  `markers` ON `agents`.`id`=`markers`.`id_subj` LEFT JOIN  `cats` ON `agents`.`cat`=`cats`.`id` 
WHERE `agents`.`id`<0 AND `agents`.`basket`=0 GROUP BY `id_subj` ORDER BY `agents`.`date` DESC LIMIT ?i,?i", $from, $to);
            break;
        case 'all':
            $q =  $db->parse("SELECT `id`, `cat` FROM  `agents` WHERE `basket`= 0 AND `date`>?i ORDER BY  `date` DESC ", $_GET['from']);
            break;
        case 'basket':
            $q = $db->parse("SELECT FROM_UNIXTIME( `agents`.`date` , '%d.%m.%y %H:%i ' ) AS `ftime` , `cats`.`name` AS `catname`, `cats`.`id` AS `catid`, `agents`.`id` AS `agent`, `agents`.`id_user` AS `admin`, `agents`.`date`, `agents`.`comm` AS `commadmin`, GROUP_CONCAT(`markers`.`id_user` SEPARATOR ',') AS `markers`
FROM  `agents` LEFT JOIN  `markers` ON `agents`.`id`=`markers`.`id_subj` LEFT JOIN  `cats` ON `agents`.`cat`=`cats`.`id` 
WHERE `agents`.`basket`=1 GROUP BY `id_subj` ORDER BY `agents`.`date` DESC LIMIT ?i,?i", $from, $to);
            break;
        case 'cats':
            $q = "SELECT `id`, `name`, `id_file` FROM  `cats`";
            break;
        case 'dels':
            $q = "SELECT `id` FROM `agents` WHERE `basket`=1";
            break;
        case 'cat_desc':
            $q = $db->parse("SELECT * FROM  `cats` WHERE `id`=?i", $_GET['cat']);
            break;
        case 'counts':
            $q = "SELECT COUNT(IF(`id`>0,1, NULL) AND IF(`basket`=0,1, NULL)) AS `agent_c` ,"
                . "COUNT(IF(`id`<0,1, NULL) AND IF(`basket`=0,1, NULL)) AS `group_c` , "
                . "COUNT(IF(`basket`=1,1, NULL)) AS `basket_c` ,(SELECT count( `id` ) FROM `cats`) AS `cat_c` ,"
                . "(SELECT count(`marks`.`id_subj`) FROM (SELECT `id_subj` FROM `markers` GROUP BY `id_subj`) AS `marks` LEFT JOIN  `agents` ON `agents`.`id`=`marks`.`id_subj` WHERE `agents`.`id` IS NULL) AS `marker_c` FROM `agents`";
            break;
        case 'desc':
            $q = $db->parse("SELECT FROM_UNIXTIME( `markers`.`date` , '%d.%m.%y %H:%i ' ) AS `ftime`, `markers`.`id_user`,`markers`.`anonim`, `cats`.`name`, `markers`.`id`, `markers`.`cat`,`markers`.`id_subj`, `markers`.`wall`, `markers`.`post`, `markers`.`reply`, `markers`.`comm`, `markers`.`date` FROM `markers` LEFT JOIN `cats` ON `markers`.`cat`=`cats`.`id` WHERE `id_subj`=?i ORDER BY `markers`.`date` LIMIT ?i,?i",$_GET['agent'], $from, $to);
            break;
        case 'marker': 
            $q = $db->parse("SELECT FROM_UNIXTIME( `markers`.`date` , '%d.%m.%y %H:%i ' ) AS `ftime`, `markers`.`id_user`, `markers`.`id_subj`, `markers`.`comm`,`cats`.`name`, `cats`.`id`,  FROM `markers` LEFT JOIN `cats` ON `markers`.`cat`=`cats`.`id` WHERE `id_subj`=?i ORDER BY `markers`.`date` ASC LIMIT ?i,?i",$_GET['agent'], $from, $to);
            break;
        case 'addmarker':
            
            $data = array(
                'id_subj' => $_GET['id'],
                'comm' => addslashes($_GET['comm']), 
                'cat' => $_GET['cat'], 
                'date' => time(), 
                'id_user' => $id_user, 
                'wall' => $_GET['wall'],
                'post' => $_GET['post'],
                'reply' => $_GET['reply'],
                'first_active' => $_GET['first_active'],
                'anonim' => $_GET['anonim']
                );
                //print_r($data);
            $sql  = "INSERT INTO `markers` SET ?u";// ON DUPLICATE KEY UPDATE prod_name=concat(prod_name,',','abc')?u";
            $q = $db->parse($sql,$data);//,$data);
            //echo $q;
            break;
        case 'delmarker':
            $q = $db->parse("DELETE FROM `markers` WHERE `id`=?i", $_GET['id']);
            break;
        case 'addcat':
            $data = array('name' => $_GET['name'], 'comm' => addslashes($_GET['comm']), 'id_file' => $_GET['id_file']);
            if(isset($_GET['id'])) $data['id'] = $_GET['id'];
            $sql  = "INSERT INTO `cats` SET ?u ON DUPLICATE KEY UPDATE ?u";
            $q = $db->parse($sql,$data,$data);
            break;
        case 'removecat':
            $q = $db->parse("DELETE FROM `cats` WHERE `id`=?i", $_GET['id']);
            break;
        case 'tobasket':
            $data = array('id' => $_GET['id'], 'basket' => 1, 'id_user' => $_GET['admin']);
            $sql  = "INSERT INTO `agents` SET ?u ON DUPLICATE KEY UPDATE ?u";
            $q = $db->parse($sql,$data,$data);
            break;
        case 'frombasket':
            $data = array('id' => $_GET['id'], 'basket' => 0, 'id_user' => $_GET['admin']);
            $sql  = "INSERT INTO `agents` SET ?u ON DUPLICATE KEY UPDATE ?u";
            $q = $db->parse($sql,$data,$data);
            break;
        case 'toblack':
            $data = array('id' => $_GET['agent'], 'comm' => addslashes($_GET['comm']), 'cat' => $_GET['cat'], 'date' => time(), 'id_user' => $id_user, 'basket' => 0);
            $sql  = "INSERT INTO `agents` SET ?u ON DUPLICATE KEY UPDATE ?u";
            $q = $db->parse($sql,$data,$data);
            break;
        case 'remove':
            $db->getAll("DELETE FROM `agents` WHERE `id`=?s", $_GET['id']);
            $q = $db->parse("DELETE FROM `markers` WHERE `id_subj`=?s", $_GET['id']);
            //echo $q;
            break;
        case 'remove_black':
            $q = $db->parse("DELETE FROM `agents` WHERE `id`=?i", $_GET['id']);
            break;
        case 'upload':
            //print_r($_FILES);
            if (isset($_FILES['uploads']['name'])){
                $q = "INSERT INTO `files` SET `type`='".$_FILES['uploads']['type']."', `name`='".$_FILES['uploads']['name']."', `data`='".mysql_escape_string(file_get_contents($_FILES['uploads']['tmp_name']))."'";
                $res = $db->getAll($q);
                $q = "SELECT LAST_INSERT_ID() AS `id`";
            }
            break;
        default:
           $q = $db->parse("SELECT `cats`.`name` AS `catname`, `cats`.`id` AS `catid`, `agents`.`id` AS `agent`, `agents`.`id_user` AS `admin`, `agents`.`date`, `agents`.`comm` AS `commadmin`, GROUP_CONCAT(`markers`.`id_user` SEPARATOR ', ') AS `markers`
FROM  `agents` LEFT JOIN  `markers` ON `agents`.`id`=`markers`.`id_subj` LEFT JOIN  `cats` ON `agents`.`cat`=`cats`.`id` 
GROUP BY `id_subj` LIMIT ?i,?i", $from, $to);
    }
}
//echo $q;
//if($_GET['a'] != 'upload'){
    $res = $db->getAll($q);

    if($_GET['a'] == 'counts'){
        $res[0]['scope'] = $scope;
    }
    if($_GET['a'] == 'cat_desc'){
        $res[0]['comm'] = stripcslashes($res[0]['comm']);
    }    
    $json = json_encode($res);
    
    echo $json;
    print_gzipped_page();
//}
?>
