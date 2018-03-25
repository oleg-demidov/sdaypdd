<?php
function get_menu_obuch(&$o){
	$need = array(
		'bilets'=>'Билеты',
		'tems'=>'Темы',
		'errors'=>'Ошибки'
	);
	$menu = '<div style="clear:both;"></div><div id="close"><span class="afss_day_bv">0</span> д.
<span class="afss_hours_bv">00</span>&nbsp;час.&nbsp;
<span class="afss_mins_bv">00</span>&nbsp;мин.&nbsp;
<span class="afss_secs_bv">00&nbsp;</span>&nbsp;сек.
</div><div class="obuch_menu">';
	$u = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?a=obuchenie';
	$o = (isset($_GET['o']))?$_GET['o']:((isset($_SESSION['o']))?$_SESSION['o']:'bilets');
	if(isset($_GET['o'])) $_SESSION['o'] = $_GET['o'];
	foreach($need as $k=>$v){
		$menu .= '<div';
		if($o == $k)			
			$menu .= ' id="obuch_select_menu"';
		$menu .= '><a href="'.$u.'&o='.$k.'">'.$v.'</a></div>';
	}
	$menu .= '</div>';
	return $menu;
}

function get_bilets($id_user, $type = 'ab'){
	global $bd;
	$qstr = '';
	if($type == 'cd')
		$qstr .= "SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`bilet`, `queab`.`bilet`) AS `bilet` FROM (SELECT `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='ab') AS `queab` LEFT JOIN (SELECT `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='cd') AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`";
	if($type == 'ab')
		$qstr .= "SELECT `bilet`, `id` FROM  `t_que` WHERE  `type`='ab'";
	$data = $bd->get_all("SELECT `ques`.`bilet`, count(`ques`.`id`) AS `coll`, count(`result_false`.`bilets`) AS `false`,  count(`result_true`.`bilets`) AS `true` FROM ( ".$qstr." ) AS `ques` LEFT JOIN (SELECT `id_que`, `bilets` FROM `t_results` WHERE `bilets`='false' AND `id_user`=?) AS `result_false` ON `result_false`.`id_que`=`ques`.`id` LEFT JOIN (SELECT `id_que`, `bilets` FROM `t_results` WHERE `bilets`='true' AND `id_user`=?) AS `result_true` ON `result_true`.`id_que`=`ques`.`id`  GROUP BY `bilet`", array($id_user, $id_user));
	return $data;
}
function get_tems($id_user, $type = 'ab'){
	global $bd; $qstr = '';
	if($type == 'cd')
		$qstr .= "SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`id_category`, `queab`.`id_category`) AS `id_category` FROM (SELECT `id_category`, `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='ab') AS `queab` LEFT JOIN (SELECT `id_category`, `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='cd') AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`";
	if($type == 'ab')
		$qstr .= "SELECT `id_category`, `id` FROM  `t_que` WHERE  `type`='ab'";
	$data = $bd->get_all("SELECT `ques`.`id_category`, `categories`.`num`, `categories`.`category`, count(`ques`.`id`) AS `coll`, count(`result_false`.`tems`) AS `false`,  count(`result_true`.`tems`) AS `true` FROM ( ".$qstr." ) AS `ques` LEFT JOIN `categories` ON `ques`.`id_category`=`categories`.`id` 
LEFT JOIN (SELECT `tems`, `id_que` FROM `t_results` WHERE `tems`='false' AND `id_user`=?) AS `result_false` ON `result_false`.`id_que`=`ques`.`id`  LEFT JOIN (SELECT `tems`, `id_que` FROM `t_results` WHERE `tems`='true' AND `id_user`=?) AS `result_true` ON `result_true`.`id_que`=`ques`.`id` GROUP BY `categories`.`num`,`ques`.`id_category`, `categories`.`num`, `categories`.`category`", array($id_user, $id_user));
	return $data;
}
function get_errors($id_user, $type = 'ab'){
	global $bd; $qstr = '';
	if($type == 'cd')
		$qstr .= "SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`id_category`, `queab`.`id_category`) AS `id_category` FROM (SELECT `id_category`, `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='ab') AS `queab` LEFT JOIN (SELECT `id_category`, `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='cd') AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`";
	if($type == 'ab')
		$qstr .= "SELECT `id_category`, `id` FROM  `t_que` WHERE  `type`='ab'";
        $data = $bd->get_all("SELECT `ques`.`id_category`, `categories`.`num`, `categories`.`category`, count(`result_false`.`tems`) AS `false` FROM ( ".$qstr." ) AS `ques` LEFT JOIN `categories` ON `ques`.`id_category`=`categories`.`id` 
LEFT JOIN (SELECT `tems`, `id_que` FROM `t_results` WHERE `errors`='false' AND `id_user`=?) AS `result_false` ON `result_false`.`id_que`=`ques`.`id` GROUP BY `ques`.`id_category`, `categories`.`num`, `categories`.`category`", array($id_user));
	return $data;
}

?>