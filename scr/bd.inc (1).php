<?php
include("bd_config.php");
class BD{
	var $error = false;
	var $connect;
	function BD($user,$pass,$bd,$host){
		$this->connect=new mysqli($host, $user, $pass, $bd);
		if(!$this->connect->connect_errno){
			if ($this->connect)$this->connect->select_db($bd);
			$this->connect->set_charset('SET NAMES utf8');
		}else {
			$this->error=$this->connect->connect_error;
			return false;
		}
	}
	function blk_inj($str){
		return $this->connect->real_escape_string($str);
	}
	function execute_query($que, $params){
		echo $que;
		if (!$stmt = $this->connect->prepare($que)){
			$this->error = $this->connect->error;
			return false;
		}
		if(!$types=$this->get_types($params)){
			$this->error = 'Error getting types';
			return false;
		}
		$paramsRef = array($types);
		foreach ($params as $key => $p) {
			$paramsRef[] = &$params[$key]; // pass by reference, not value
		}
		if(!call_user_func_array(array($stmt, 'bind_param'), $paramsRef)){
			$this->error = 'Error bind param';
			echo $this->error;
			return false;
		}
		if (!$stmt->execute()){
			$this->error = $stmt->error;
			$stmt->close();
			return false;
		}
		return $stmt;//$result->get_result();
	}
	
	function get_types(&$params){
		if (!is_array($params))
			return false;
		$sa = sizeof($params);
		$types = '';
		for($i = 0; $sa > $i; $i++){
			$type = substr(gettype($params[$i]), 0, 1);
			if ($type == 's') $params[$i] = $this->blk_inj($params[$i]);
			$types .= $type;
		}
		return $types;
	}
	function sql_query($que){
		if(!$result = $this->connect->query($que)){
			$this->error = $this->connect->error;
			return false;
		}
		return $result;
	}
	function sql_get($que, $param){
		if(!$param){
			$result = $this->sql_query($que);
			$data = array();
			while($row = $result->fetch_assoc())
				$data[] = $row;
			$result->close();
			return $data;
		}
		$stmt = $this->execute_query($que, $param);
		$data = $this->feth_assoc( $stmt );
		$stmt->close();
		return $data;
	}
	function sql($que, $param = false){
		$stmt = $this->sql_get($que, $param);
		$rows = $stmt->affected_rows;
		$stmt->close();
		return $rows;
	}
	function arr_to_str($arr, $del = ', '){
		$vals = array();
		$str = '';
		$zpt = false;
		foreach($arr as $k => $v){
			if($zpt){
				$str .= $del;
			}
			$str .= "`".$k."`=?";
			if(!$zpt)$zpt = true;
			$vals[] = $v;
		}
		return array($str, $vals);
	}
	function insert_on_update($table, $arr){
		$str = $this->arr_to_str($arr);
		$q = "INSERT INTO `".$table."` SET ".$str[0]."  ON DUPLICATE KEY UPDATE ".$str[0];
		//echo $q;
		return $this->sql($q, array_merge($str[1], $str[1]));
	}
	function db_bind_array($stmt, &$row){
		$md = $stmt->result_metadata();
		$params = array();
		while($field = $md->fetch_field()) {
			$params[] = &$row[$field->name];
		}
		return call_user_func_array(array($stmt, 'bind_result'), $params);
	}
	function feth_assoc($stmt){
		$row = array();
		if ($this->db_bind_array($stmt, $row) === FALSE) {
			$this->error = 'bind_array error';
			return false;
		}
		$data = array();
		while ($stmt->fetch()) {
			foreach($row as $v)
				$data[] = $row;
		}
		return $data;
	}
	function get_all($que, $param = false){
		if (!$data = $this->sql_get($que, $param))
			return false;
		return $data;
	}
	function get_row($que, $param = false){
		$data = $this->get_all($que, $param);
		return (isset($data[0]))?$data[0]:$data;
	}
	
	function get_columns($que, $param = false){
		$data = $this->get_all($que, $param);
		$sd = sizeof($data);
		if(!$sd)
			return $data;
		$columns = array();
		$keys = array_keys($data[0]);
		$sk = sizeof($keys);
		for($k = 0; $k < $sk; $k++){
			$columns[$keys[$k]] = array();
			for($i = 0; $i < $sd; $i++){
				$columns[$keys[$k]][$i] = $data[$i][$keys[$k]];
			}
		}
		return $columns;
	}
}
?>