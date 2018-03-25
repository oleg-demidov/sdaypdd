<?php
include("bd_config.php");
class BD{
	var $error;
	var $connect;
	function BD($user,$pass,$bd='adv_game',$host='localhost'){
		$this->connect=mysql_connect($host,$user,$pass);
		if($this->connect){
			if($bd)mysql_select_db($bd,$this->connect);
			mysql_query('SET NAMES utf8',$this->connect);
		}else {
			//header("Location: http://".$_SERVER['HTTP_HOST']."/error.php?error=".mysql_error());
			$this->error=mysql_error();
			return false;
		}
	}
	function check_inj($str){
		//$cont='select|update|insert|drop|delete|grant all|lock tables|references|create';
		//$str=preg_replace ( $cont ,"" , $str );
		return mysql_real_escape_string($str);
	}
	function sql_query($query){
		//echo $query;
		global $error;
		$rez=mysql_query($query,$this->connect);
		if(!$rez){
			//header("Location: http://".$_SERVER['HTTP_HOST']."/error.php?error=".mysql_error().' - '.$query);
			$this->error=mysql_error();
			return false;
		}
		return $rez;
	}
	function arr2query($arr,$kav='`'){
		$arr_str='';
		$n=sizeof($arr);
		for($i=0;$n>$i;$i++){
			$arr[$i]=mysql_real_escape_string($arr[$i]);
			$arr_str.=$kav.$arr[$i].$kav;
			if($n!=$i+1)$arr_str.=",";
		}
		return $arr_str;
	}
	function assoc2query($arr,$ins=0,$raz=','){
		if($ins)$arr_str=array('','');
		else $arr_str='';
		$n=sizeof($arr);$i=0;
		foreach($arr as $k=>$v){
			$k=mysql_real_escape_string($k);
			$v=mysql_real_escape_string($v);
			if(!$ins)$arr_str.="`$k`='$v'";
			else{
				$arr_str[0].="`$k`";
				$arr_str[1].="'$v'";
			}
			if($n!=$i+1){
				if(!$ins)$arr_str.=$raz;
				else{
					$arr_str[0].=$raz;
				    $arr_str[1].=$raz;
				}
			}
			$i++;
		}
		return $arr_str;
	}
	function select($what,$table,$where='',$order_by='',$limit='',$type=MYSQL_ASSOC){
		$what_str;
		$where_str;
		if(is_array($what))
			$what_str=$this->arr2query($what);
		else{
			if($what=='*')$what_str=$what;
			else $what_str='`'.$what.'`';
		}
		if(is_array($where))
			$where_str=$this->assoc2query($where);
		else $where_str=$where;
		if($where)$where_str='WHERE '.$where_str;
		$where_str=str_replace(',',' AND ',$where_str);
		if($limit)$limit=' LIMIT '.$limit;
		$query="SELECT $what_str FROM `$table` $where_str $order_by $limit";
		$result=$this->sql_query($query);
		return $this->get_result($result,$type);
	}
	function get_result($result,$type=MYSQL_ASSOC){
		if(!$result)
			return false;
		if(mysql_num_rows($result)){
			$data=array();
			while($dat=mysql_fetch_array($result,$type)){
				$i=sizeof($data);
				foreach ($dat as $k=>$v){
					$data[$i][$k]=$v;}
				}
			return $data;
			}
		return false;
	}
	function update($table,$set,$where=0,$limit=''){
		$set_str='';
		$where_str='';
		if(is_array($set)){
			$set=$this->assoc2query($set);
			$set_str=$set;}
		else $set_str=$set;
		if($where){
			if(is_array($where))
				$where_str=$this->assoc2query($where,0,' AND ');
			else $where_str=$where;
			$where_str=' WHERE '.$where_str;
		}
		
		$query="UPDATE `$table` SET $set_str $where_str $limit";
		//echo $query;
		return $this->sql_query($query);
	}
	function insert($table,$keys,$values=0){
		$keys_str='';
		$values_str='';
		if(is_array($keys)){
			$keysAr=array_keys($keys);
			if(is_array($keys[$keysAr[0]])){
				$s=sizeof($keysAr);
				for($i=0;$i<$s;$i++){
					$arr_str=$this->assoc2query($keys[$keysAr[$i]],1);
					if($i==0)$keys_str=$arr_str[0];
					$values_str.='('.$arr_str[1].')';
					if(($i+1)<$s)$values_str.=',';
				}
			}else{
				$arr_str=$this->assoc2query($keys,1);
				$keys_str=$arr_str[0];
				$values_str='('.$arr_str[1].')';
			}
		}else{
			$keys_str=$keys;
			$values_str='('.$values.')';
		}
		$query="INSERT INTO `$table` ($keys_str) VALUES $values_str";
		return $this->sql_query($query);
	}
	function insert_on_update($table,$set,$set_plus=false){
		$set_str;
		$where_str;
		if(is_array($set)){
			$set=$this->assoc2query($set);
			$set_str=$set;}
		else $set_str=$set;
		if(!$set_plus)$set_plus=$set_str;
		$query="INSERT INTO `$table` SET $set_str ON DUPLICATE KEY UPDATE $set_plus";
		return $this->sql_query($query);
	}
	function delete_str($table,$where,$raz=','){
		if(is_array($where))$where=$this->assoc2query($where,0,$raz);
		$query="DELETE FROM `$table` WHERE $where ";
		return $this->sql_query($query);
	}
	function clear_table($table){
		$query="TRUNCATE TABLE `$table`";
		return $this->sql_query($query);
	}
	function delete_tab($table){
		$query="DROP TABLE `$table`";
		return $this->sql_query($query);
	}

}
?>