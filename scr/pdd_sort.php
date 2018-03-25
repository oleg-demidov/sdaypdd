<?

class PDDSORT{
	function __construct($bd){
		$this->signs = $bd->get_all("SELECT CONCAT( `cat_signs`.`cnum`,'.',`signs`.`num`) as `nums`, `cat_signs`.`cnum` AS `cat` FROM `signs` LEFT JOIN `cat_signs` ON `signs`.`id_category` = `cat_signs`.`id` WHERE `signs`.`type`='sign' ORDER BY `cat_signs`.`cnum`,`signs`.`numf`");
		$this->razms = $bd->get_all("SELECT CONCAT( `cat_signs`.`cnum`,'.',`signs`.`num`) as `nums`, `cat_signs`.`cnum` AS `cat`  FROM `signs` LEFT JOIN `cat_signs` ON `signs`.`id_category` = `cat_signs`.`id` WHERE `signs`.`type`='razm' ORDER BY `cat_signs`.`cnum`,`signs`.`numf`");
		$this->punkts = $bd->get_all("SELECT CONCAT( `categories`.`num`,'.', `pdd`.`punkt`) AS `nums`, `categories`.`id` AS `cat`  FROM `pdd` LEFT JOIN `categories` ON `categories`.`id` = `pdd`.`category`  WHERE `categories`.`num` < 26  ORDER BY `nums`");
		$this->href='<a class="ssilka" href="http://'.$_SERVER['HTTP_HOST'].'/index.php%s" media="http://'.$_SERVER['HTTP_HOST'].'/scr/get_media.php%s">%s</a>';
		$this->limit_search_len = 70;
		$this->reg1 = "/(Знак|знак|Пункт|пункт|табличк|Разметк|разметк|п\.)[а-ю]{0,5} [0-9]/u";
		$this->reg2 = "/(([0-9]{1,2}\.?){1,4}[0-9]{1,2})|-/";
		$this->need_search = array(	
			'знак' => array('?z=','?a=signs&cat='),
			'Знак' => array('?z=','?a=signs&cat='),
			'пункт' => array('?p=','?a=pdd&cat='),
			'Пункт' => array('?p=','?a=pdd&cat='),
			'п.' => array('?p=','?a=pdd&cat='),
			'табличк' => array('?z=','?a=signs&cat='), 
			'разметк' => array('?r=','?a=razm'),
			'Разметк' => array('?r=','?a=razm')
		);
	}
	function add_href($text){
		$str = '';
		$args = array();
		$start = 0;
		$matches = $this->get_maches($text, $this->reg1);
		//print_r($this->signs);
		$hrefs = array();
		$sm = sizeof($matches);
		for($i = 0; $i < $sm; $i++){
			$start = strlen($matches[$i][0]) + $matches[$i][1] -1;
			$length = $this->get_length_search($start, $matches, $i);
			$str = substr ( $text , $start, $length);
			$args = $this->get_arg($matches[$i][0]);
			$hrefs = array_merge( 
				$hrefs, 
				$this->get_vals($str, $args, $start)
			);			
		}
		return stripcslashes($this->add2text($hrefs, $text));		
	}
	function get_arg(&$str){
		foreach($this->need_search as $k => $v)
			if(strpos( $str, $k ) !== false)
				return $v;
	}
	function get_length_search($start, &$matches, $i){
		if(!isset($matches[$i+1]))
			return $this->limit_search_len;
		$len = $matches[$i+1][1] - $start;
		if($len > $this->limit_search_len)
			return $this->limit_search_len;
		return $len;
	}
	function add2text($hrefs, $text){
		$fstr = ''; $len = 0;
		$sh = sizeof($hrefs);
		for($i = 0; $i < $sh; $i++){
			$fstr = sprintf($this->href, $hrefs[$i][3], $hrefs[$i][2], $hrefs[$i][0]);
			$text = substr_replace($text, $fstr, $hrefs[$i][1] + $len, strlen($hrefs[$i][0]));
			$len += strlen($fstr) - strlen($hrefs[$i][0]);
		}
		return $text;
	}
	function get_maches($text, $reg){
		$matches = array();
		preg_match_all ( $reg , $text, $matches, PREG_OFFSET_CAPTURE);
		return $matches[0];
	}
	function get_vals($text, $args, $start){
		$result = array();
		$matches = $this->get_maches($text, $this->reg2);
		$sm = sizeof($matches);
		for($i = 0; $i < $sm; $i++){
			if(isset($matches[$i+2][0]) && isset($matches[$i+1][0]) && $matches[$i+1][0] == '-'){
				$result[] = array(
					$matches[$i][0].' - '.$matches[$i+2][0],
					$matches[$i][1] + $start,
					$this->get_all_nums($args[0], $matches[$i][0], $matches[$i+2][0],$args[1]),
					$args[1].$this->get_id_cat($matches[$i][0], $args[0])
				);
				$i += 2;
			}else{
				$result[] = array( 
					$matches[$i][0], 
					$matches[$i][1]+$start, 
					$args[0].$matches[$i][0], 
					$args[1].$this->get_id_cat($matches[$i][0], $args[0])
				);
			}
			
		}
		return $result;
	}
	function get_id_cat($num, $arg){
		if($arg == '?z=')
			return $this->get_idcat_form_num($num, $this->signs).'#z'.$num;
		if($arg == '?p=') 
			return $this->get_idcat_form_num($num, $this->punkts).'#p'.$num;
	}
	function get_idcat_form_num($num, $arr){
		$sa = sizeof($arr);
		for($i=0;$i<$sa;$i++){
			if($arr[$i]['nums'] == $num)
				return $arr[$i]['cat'];
		}
	}
	function get_all_nums($arg, $from, $to){
		if($arg == '?z=')
			return $arg.$this->get_counts( $this->signs, $from, $to );
		if($arg == '?p=') 
			return $arg.$this->get_counts( $this->punkts, $from, $to );
		if($arg == '?r=') 
			return $arg.$this->get_counts( $this->razms, $from, $to );
	}
	function get_counts($arr, $from, $to){
		$str = '';
		$write = false;
		$ss = sizeof($arr);
		//print_r($arr);
		for($s = 0; $s < $ss; $s++){
			if($arr[$s]['nums'] == $from)
				$write = true;
			if(!$write) continue;
			$str .= $arr[$s]['nums'].';';
			if($to == $arr[$s]['nums']){
				break;
			}
		}
		return $str;
	}
}
?>