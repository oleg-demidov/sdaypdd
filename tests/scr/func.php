<?php

function attach_ans($data){
	if(!isset($data['id']))
		return false;
	global $bd;
	$ans = $bd->get_all("SELECT * FROM `t_ans` WHERE `id_que`=?", array($data['id']));
	$img = $bd->get_all("SELECT * FROM `t_images` WHERE `id_que`=?", array($data['id']));
	if(isset($img[0]))$data['img'] = $img[0]['id'];
	$sa = sizeof($ans);
	for($i=0;$i<$sa;$i++){
		$data['ans'.($i+1)] = $ans[$i]['value'];
		if($ans[$i]['right'] == 1)
			$data['right'] = $i+1;
	}
	return $data;
}
function get_que_bilet($bilet = false, $num = false, $type = 'ab'){
	if(!$bilet || !$num) return false;
	global $bd;
	$qstr = '';
	if($type == 'cd')
		$data = $bd->get_row("SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`id_category`, `queab`.`id_category`) AS `id_category`, IFNULL( `quecd`.`value`, `queab`.`value`) AS `value`, 
IFNULL( `quecd`.`number`, `queab`.`number`) AS `number`, IFNULL( `quecd`.`bilet`, `queab`.`bilet`) AS `bilet`, IFNULL( `quecd`.`comm`, `queab`.`comm`) AS `comm` FROM (SELECT * FROM  `t_que` WHERE  `type`='ab' AND `number`=? AND `bilet`=?) AS `queab` LEFT JOIN (SELECT * FROM  `t_que` WHERE  `type`='cd' AND `number`=? AND `bilet`=?) AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`", array( $num, $bilet, $num, $bilet));
	if($type == 'ab')
		$data = $bd->get_row("SELECT * FROM `t_que`  WHERE `bilet`=? AND `number`=? AND `type` = ?", array($bilet, $num, $type));
	return attach_ans($data);
}
function get_que_tema($tema = false, $num = false, $type = 'ab'){
	if(!$tema || !$num) return false;
	global $bd;
	if($type == 'cd')
		$data = $bd->get_row("SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`value`, `queab`.`value`) AS `value`, IFNULL( `quecd`.`comm`, `queab`.`comm`) AS `comm` FROM (SELECT `t_que`.`id`, `t_que`.`value`, `t_que`.`comm`, `t_que`.`number`, `t_que`.`bilet` FROM  `t_que` LEFT JOIN `categories` ON `t_que`.`id_category`=`categories`.`id` WHERE `t_que`.`type`='ab' AND `categories`.`num`=?) AS `queab` LEFT JOIN (SELECT `t_que`.`id`, `t_que`.`value`, `t_que`.`comm`, `t_que`.`number`, `t_que`.`bilet` FROM  `t_que` LEFT JOIN `categories` ON `t_que`.`id_category`=`categories`.`id` WHERE `t_que`.`type`='cd' AND `categories`.`num`=?) AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet` ORDER BY `id` LIMIT ?, 1", array( $tema, $tema, $num-1));
	if($type == 'ab')
		$data = $bd->get_row("SELECT `t_que`.`id`, `t_que`.`value`, `t_que`.`comm` FROM  `t_que` LEFT JOIN `categories` ON `t_que`.`id_category`=`categories`.`id` WHERE `t_que`.`type`='ab' AND `categories`.`num`=? ORDER BY `t_que`.`id` LIMIT ?, 1", array($tema, $num-1));
	return attach_ans($data);
}
function get_que_false($id_user, $num = false, $type = 'ab'){
	if(!$num || !$id_user) return false;
	global $bd;
	if($type == 'cd')
		$data = $bd->get_row("SELECT `t_que`.`id`, `t_que`.`value`, `t_que`.`comm` FROM (SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id` FROM (SELECT `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='ab') AS `queab` LEFT JOIN (SELECT `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='cd') AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`) AS `ques` LEFT JOIN `t_results` ON `t_results`.`id_que` = `ques`.`id` LEFT JOIN `t_que` ON `t_que`.`id` = `ques`.`id` WHERE `t_results`.`id_user`=? AND `t_results`.`errors` = 'false' LIMIT 0, 1", array($id_user));
	if($type == 'ab')
		$data = $bd->get_row("SELECT `t_que`.`id`, `t_que`.`value`, `t_que`.`comm`, `t_que`.`number`, `t_que`.`bilet` FROM  `t_que` LEFT JOIN `t_results` ON `t_que`.`id` = `t_results`.`id_que` WHERE `t_que`.`type`='ab' AND `t_results`.`errors`='false' AND `t_results`.`id_user`=? LIMIT 0, 1", array($id_user));
	return attach_ans($data);
}
function get_que_random($type = 'ab'){
	global $bd;
	$count = $bd->get_row("SELECT count(*) AS `count` FROM `t_que` WHERE `type`=?", array($type));
	$num = rand(0, $count['count']-1);
	$data = $bd->get_row("SELECT `t_que`.`id`,`t_que`.`value`,`t_que`.`comm` FROM `t_que` WHERE `type`=? LIMIT ?,1", array($type, $num));
	return attach_ans($data);
}
function get_number($num, $type = 'ab'){
	global $bd;
	if($type == 'cd')
		$data = $bd->get_row("SELECT IFNULL(  `quecd`.`id` ,  `queab`.`id` ) AS  `id` , IFNULL(  `quecd`.`value` ,  `queab`.`value` ) AS  `value` , IFNULL(  `quecd`.`comm` ,  `queab`.`comm` ) AS  `comm` ,  `queab`.`bilet` , `queab`.`number` FROM (SELECT * FROM  `t_que` WHERE  `type` =  'ab') AS  `queab` LEFT JOIN (SELECT * FROM  `t_que` WHERE  `type` =  'cd') AS  `quecd` ON  `quecd`.`number` =  `queab`.`number` AND  `quecd`.`bilet` = `queab`.`bilet` ORDER BY  `queab`.`bilet` ,  `queab`.`number` LIMIT ? , 1", array($num-1));
	if($type == 'ab')
		$data = $bd->get_row("SELECT `t_que`.`id`,`t_que`.`value`,`t_que`.`comm` FROM `t_que` WHERE `t_que`.`type`='ab' ORDER BY  `t_que`.`bilet` ,  `t_que`.`number` LIMIT ?,1", array($num-1));
	return attach_ans($data);
}
function get_stat_bilet($bilet, $user, $type = 'ab'){
	global $bd;
	if($type == 'cd'){
            $qstr = "SELECT `t_res`.`bilets` FROM (SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`number`, `queab`.`number`) AS `number` FROM (SELECT `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='ab' AND `bilet`=?) AS `queab` LEFT JOIN (SELECT `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='cd' AND `bilet`=?) AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`) AS `bilet` LEFT JOIN (SELECT `id_que`,`bilets` FROM `t_results` WHERE `id_user`=?) AS `t_res` ON `bilet`.`id`=`t_res`.`id_que` ORDER BY  `bilet`.`number`";
            $data = $bd->get_all($qstr, array($bilet, $bilet, $user));      
        }
        if($type == 'ab'){
            $qstr = "SELECT `t_res`.`bilets` FROM (SELECT `number`, `id` FROM  `t_que` WHERE  `type`='ab'  AND `bilet`=?) AS `bilet` LEFT JOIN (SELECT `id_que`,`bilets` FROM `t_results` WHERE `id_user`=?) AS `t_res` ON `bilet`.`id`=`t_res`.`id_que` ORDER BY  `bilet`.`number`";
            $data = $bd->get_all($qstr, array( $bilet, $user));      
        }
        $res = array();
        for($i=0; $i<sizeof($data);$i++){
            $res[$i] = $data[$i]['bilets'];
        }
        return $res;
}
function get_stat_tems($tema, $user, $type = 'ab'){
	global $bd;
	if($type == 'cd'){
            $qstr = "SELECT `t_res`.`tems` FROM (SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id` FROM (SELECT `t_que`.`id` FROM `t_que` LEFT JOIN `categories` ON `categories`.`id`=`t_que`.`id_category` WHERE `type`='ab' AND `categories`.`num`=?) AS `queab` LEFT JOIN (SELECT `t_que`.`id` FROM `t_que` LEFT JOIN `categories` ON `categories`.`id`=`t_que`.`id_category` WHERE `type`='cd' AND `categories`.`num`=?) AS `quecd` ON `quecd`.`id`=`queab`.`id`) AS `tema` LEFT JOIN (SELECT `id_que`,`tems` FROM `t_results` WHERE `id_user`=?) AS `t_res` ON `tema`.`id`=`t_res`.`id_que` ORDER BY `tema`.`id`";
            $data = $bd->get_all($qstr, array($tema, $tema, $user));      
        }
        if($type == 'ab'){
            $qstr = "SELECT `t_res`.`tems` FROM (SELECT `t_que`.`id` FROM `t_que` LEFT JOIN `categories` ON `categories`.`id`=`t_que`.`id_category` WHERE `type`='ab' AND `categories`.`num`=?) AS `tema` LEFT JOIN (SELECT `id_que`,`tems` FROM `t_results` WHERE `id_user`=?) AS `t_res` ON `tema`.`id`=`t_res`.`id_que` ORDER BY `tema`.`id`";
            $data = $bd->get_all($qstr, array( $tema, $user));      
        }
        $res = array();
        for($i=0; $i<sizeof($data);$i++){
            $res[$i] = $data[$i]['tems'];
        }
        return $res;
}
?>