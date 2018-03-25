<?
	$error;$extI;
	function load_files($files,$limit_mb){
		function getE($filename) {
    		return end(explode(".", $filename));
		}
			global $error;
		if($files['image']['error']||$files['icons']['error']){
			$error="Ошибка загрузки - ".$files['models']['error'].', '.$files['icons']['error'];
			return false;
		}
		if($files["image"]["size"] > 1024*$limit_mb*1024||$files['icons']['size']> 1024*$limit_mb*1024){
			$error="Размер превышает $limit_mb мегабайта";
			return false;
		}
		global $extI;
		$extI=getE($files["image"]["name"]);
		if($extI!='jpg'&&$extI!='png'&&$extI!='gif'&&$extI!='jpeg'){
			$error="Не верный формат файла ".$files["icons"]["name"]."(должен быть jpeg, gif или png)";
			return false;
		}
		$id=rand(1,100000000);
		move_uploaded_file($files["image"]["tmp_name"], "../banners/tmp/".$id.'.'.$extI);
		return $id;
	}
	
?>