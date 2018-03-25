<?php
class CONTENT{
	var $title = '';
	var $desc = '';
	var $keyw = '';
	var $header = '';
	var $inc = '';
	var $data = false;
	var $bd;
	var $razdel = 'homepage';
	function get_razdel_from($name){
		return $this->bd->get_row("SELECT * FROM `pages` WHERE `type`='razdel' AND `name`=?", array($name));
		
	}
	function get_razdel($a = false, $cat = false){
		if(isset($_GET['a'])){
			if(!($razdel = $this->get_razdel_from($_GET['a']))){
				if($_GET['a'] == 'page'){
					$this->inc = 'g_elements/page.php';
					$a = 'page';
				}
			}else{
				$this->parse($razdel);
				$this->inc = $razdel['path'];
				$this->cat = (isset($_GET['cat']) && !$cat) ? $_GET['cat'] : $cat;
				$a = $_GET['a'];
			}
		}
		$this->razdel = $a;
		return $a;
	}
	function parse(&$data){
		if(isset($data['title'])){
			$this->title .= $data['title'];
			$this->desc .= $data['description'];
			$this->keyw .= $data['keywords'];
			$this->header .= $data['header'];
		}elseif(isset($this->data[0]['category'])){
			$this->keyw .= ', '.$this->data[0]['category'];
			$this->desc .=  $this->data[0]['category'];
		}
		if(isset($data['text']))
			$this->data['text'] = stripslashes($data['text']);
		if(isset($data['seotext']))
			$this->data['seotext'] = stripslashes($data['seotext']);
	}
	function __construct($bd){
		$this->bd = $bd;
		switch ($this->get_razdel()) {
			case false:
				$this->inc = 'g_elements/page.php';
				$this->get_homepage();
				break;
			case 'page':
				$this->get_page($_GET['id']);
				if(!sizeof($this->data))
					$this->get_homepage();
				break;
			case 'pdd':
				$this->get_pdd();
				break;
			case 'signs':
				$this->get_signs();
				break;
			case 'razm':
				$this->get_razm();
				break;
		}
		$this->parse($this->data);
	}
	function get_page($id){
		$this->data = $this->bd->get_row("SELECT * FROM `pages` WHERE `id`=?", array($id));
	}
	function get_homepage(){
		$home = $this->bd->get_row("SELECT `value` FROM `settings` WHERE `name`='home'");
		$id = $home['value'];
		$this->get_page($id);
		$this->razdel = 'homepage';
	}
	function get_pdd(){
		if($this->cat){
			$this->data = $this->bd->get_all("SELECT `pdd`.`id`, `pdd`.`text`,`pdd`.`punkt`, `categories`.`category`, `categories`.`num` FROM `pdd` LEFT JOIN `categories` ON `pdd`.`category`=`categories`.`id` WHERE `pdd`.`category`=? ORDER BY convert(`pdd`.`punkt`, decimal)", array($_GET['cat']));
		if($this->data)$this->title .= ' | '.$this->data[0]['category'];
		}else 
			$this->data = $this->bd->get_all("SELECT * FROM `categories` ORDER BY `num`");
	}
	function get_signs(){
		if($this->cat){
			$this->data = $this->bd->get_all("SELECT `signs`.`id`, `signs`.`name`, `signs`.`text`,`signs`.`num`, `cat_signs`.`category`, `cat_signs`.`cnum` FROM `signs` LEFT JOIN `cat_signs` ON `signs`.`id_category`=`cat_signs`.`id` WHERE `signs`.`id_category`=?  ORDER BY `signs`.`numf`", array($_GET['cat']));
			if($this->data)$this->title .= ' | '.$this->data[0]['category'];
		}else 
			$this->data = $this->bd->get_all("SELECT * FROM `cat_signs` WHERE  `id`<9 ORDER BY `cnum`");
	}
	function get_razm(){
		$data1 = $this->bd->get_all("SELECT `signs`.`id`, `signs`.`name`, `signs`.`text`,`signs`.`num`, `cat_signs`.`category`, `cat_signs`.`cnum` FROM `signs` LEFT JOIN `cat_signs` ON `signs`.`id_category`=`cat_signs`.`id` WHERE `signs`.`id_category`=?  ORDER BY `signs`.`numf`", array(9));
		$data2 = $this->bd->get_all("SELECT `signs`.`id`, `signs`.`name`, `signs`.`text`,`signs`.`num`, `cat_signs`.`category`, `cat_signs`.`cnum` FROM `signs` LEFT JOIN `cat_signs` ON `signs`.`id_category`=`cat_signs`.`id` WHERE `signs`.`id_category`=?  ORDER BY `signs`.`numf`", array(10));
		$this->data =  array($data1, $data2);
	}
}
function get_dir($def, $d = false){
	if(isset($_GET['a'])){
		$url=$_GET['a'].'.php';
		$dir = ($d)?('./'.$d):'.';
		$handle = opendir($dir); 
		while (($file = readdir($handle))!== false){
			if($url==$file&&$url!='index.php'){
				return $_GET['a']; 
			}
		} 
		closedir($handle);
	}
	return $def;
}

/*function get_navig($cont, $maxInStr, $maxWidth = 5){
	$nowStr = isset($_GET['lim'])?$_GET['lim']:0;
	if(isset($_GET['lim'])){
		$url = substr($_SERVER['REQUEST_URI'],0 , strpos ($_SERVER['REQUEST_URI'] , '&lim='));
	}else
		$url = $_SERVER['REQUEST_URI'];
	if($maxInStr > $cont)
		return false;
	$html = '';
	$colStr = ceil($cont/$maxInStr);
        
	$i = 1;
	if($nowStr > ceil($maxWidth/2))
		$i = $nowStr - ceil($maxWidth/2);
        
        if($i>1){
		$html .= '<a  href="'.$url.'&lim='.($nowStr - $maxWidth).'"><</a>';
	}
        $stop = false;
	for($i; $i < ($maxWidth+1); $i++){
		if(($i+1) > $colStr){
                    $stop = true;
                    break;
                }
		$html .= '<a ';
		if($i == $nowStr)$html .= 'id="selected"';
		$html .= ' href="'.$url.'&lim='.($i-1).'">'.$i.'</a>';
	}
	if(!$stop) 
		$html .= '<a  href="'.$url.'&lim='.$i.'">></a>';
	return $html;
}*/
function substrpos($str, $need){
	return substr($str, 0, strpos($str, $need));
}
function breadcrumbs($title,$n = 3){
	$str = '';//print_r($_SESSION['breadcrumbs']);
	if(isset($_SESSION['breadcrumbs'])){
		$data = unserialize($_SESSION['breadcrumbs']);
		for($i=0;$i<sizeof($data)&&$i<$n;$i++){
			$str.='<a href="'.$data[$i]['uri'].'" title="'.$data[$i]['titfull'].'">'.$data[$i]['title'].'</a>';
		}
	}else{
		$data = array();
	}
	
	$sd = sizeof($data);
	if(isset($data[$sd-1]['uri']) && $data[$sd-1]['uri'] == $_SERVER['REQUEST_URI'])
		return $str;
	if($title == '')
		return $str;
	$tf = $title;
	if(($tr = strpos($title, '|'))!==false)
		$title = substr($title, $tr+1);
	$pos = 20;
	if(strlen($title)<$pos)$pos = strlen($title);
	if(($pr = strpos($title, ' ', $pos))!==false)
		$title = substr($title, 0, $pr);
		
	if($sd == $n){
		array_shift($data);
	}
	$data[] = array('uri' => $_SERVER['REQUEST_URI'], 'title' => $title, 'titfull' =>$tf );
	$_SESSION['breadcrumbs'] = serialize($data);
	return $str;
}
function get_count_que($type = 'ab'){
	global $bd;
	$qstr = '';
	if($type == 'cd')
		$qstr .= "SELECT count(IFNULL( `quecd`.`number`, `queab`.`number`)) AS `count`  FROM (SELECT `bilet`, `number` FROM  `t_que` WHERE  `type`='ab') AS `queab` LEFT JOIN (SELECT `bilet`, `number` FROM  `t_que` WHERE  `type`='cd') AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`";
	if($type == 'ab')
		$qstr .= "SELECT count(`id`) AS `count` FROM  `t_que` WHERE  `type`='ab'";
	$data = $bd->get_row($qstr);
	return $data['count'];
}
function get_user_results($id, $type = 'ab'){
	global $bd;
	$qstr = '';
	if($type == 'cd')
		$qstr .= "SELECT IFNULL( `quecd`.`id`, `queab`.`id`) AS `id`, IFNULL( `quecd`.`id_category`, `queab`.`id_category`) AS `id_category` FROM (SELECT `id_category`, `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='ab') AS `queab` LEFT JOIN (SELECT `id_category`, `bilet`, `id`, `number` FROM  `t_que` WHERE  `type`='cd') AS `quecd` ON `quecd`.`number`=`queab`.`number` AND `quecd`.`bilet`=`queab`.`bilet`";
	if($type == 'ab')
		$qstr .= "SELECT `id_category`, `id` FROM  `t_que` WHERE  `type`='ab'";
	return $bd->get_row("SELECT count(`tem_false`.`id_que`) AS `tems_false`, count( `tem_true`.`id_que`) AS `tems_true`, count(`bil_false`.`id_que`) AS `bils_false`, count(`bil_true`.`id_que`) AS `bils_true`
FROM (".$qstr.") AS `ques` LEFT JOIN (SELECT `id_que` FROM `t_results` WHERE `tems`='false' AND `id_user`=?) AS `tem_false` ON `ques`.`id` = `tem_false`.`id_que`LEFT JOIN (SELECT `id_que` FROM `t_results` WHERE `tems`='true' AND `id_user`=?) AS `tem_true` ON `ques`.`id` = `tem_true`.`id_que` LEFT JOIN (SELECT `id_que` FROM `t_results` WHERE `bilets`='false' AND `id_user`=?) AS `bil_false` ON `ques`.`id` = `bil_false`.`id_que` LEFT JOIN (SELECT `id_que` FROM `t_results` WHERE `bilets`='true' AND `id_user`=?) AS `bil_true` ON `ques`.`id` = `bil_true`.`id_que`", array($id,$id,$id,$id));
	
}
function set_visit_user($id){
	global $bd;
	return $bd->sql("UPDATE `users` SET `time` = FROM_UNIXTIME(?) WHERE `id` = ?", array(time(), $id));
}
function set_visit_gost(){
	global $bd;
	return $bd->insert_on_update('visitors', array('time' => time(), 'ip'=>ip2long ($_SERVER['REMOTE_ADDR'])));
}
function get_visitors($id = 0){
	global $bd;
	$time = time () - (60);
	return $bd->get_all("SELECT `users`.`name`, `social`.`first_name`, `social`.`uid`,  `social`.`soc`, `social`.`last_name`, `social`.`avatar50`, ( SELECT count(*) FROM `users` WHERE `time`>FROM_UNIXTIME(?)) AS `all` FROM `users`  LEFT JOIN  `social` ON `users`.`id` = `social`.`id_user` WHERE `users`.`time`>FROM_UNIXTIME(?) AND `users`.`id` != ? GROUP BY `users`.`id` LIMIT 5", array($time, $time, $id));
}
function get_profile_link($data){
    $soclins = array(
        'ok' => 'https://ok.ru/profile/',
        'vk' => 'https://vk.com/',
        'mm' => 'https://my.mail.ru/mail/'
     );
    return $soclins[$data['soc']].$data['uid'];
}
function get_set($name){
	global $bd;
	$data = $bd->get_row("SELECT `value` FROM `settings` WHERE `name`=?", array($name));
	return $data['value'];
}
function get_gosts(){
	global $bd;
	$time = time () - (60*5);
	$vrs = $bd->get_row("SELECT count(*) AS `gosts` FROM `visitors` WHERE `time`>?", array($time));
	if(!$vrs) $vrs = 0;
	else $vrs = $vrs['gosts'];
	return $vrs;
}
?>