<?	
	
	$error='';$extI='';
	function getExt($filename) {
   		return end(explode(".", $filename));
	}
	function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
		global $error;
		list($w_i, $h_i, $type) = getimagesize($file_input);
		if (!$w_i || !$h_i) {
			$error='Невозможно получить длину и ширину изображения';
			return false;
		}
			$types = array('','gif','jpeg','png');
			$ext = $types[$type];
		if ($ext) {
			$func = 'imagecreatefrom'.$ext;
			$img = $func($file_input);
		} else {
			$error='Некорректный формат файла';
			return false;
			}
		if ($percent) {
			$w_o *= $w_i / 100;
			$h_o *= $h_i / 100;
		}
		if (!$h_o) $h_o = $w_o/($w_i/$h_i);
		if (!$w_o) $w_o = $h_o/($h_i/$w_i);
	
		$img_o = imagecreatetruecolor($w_o, $h_o);
		imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
		if ($type == 2) {
			return imagejpeg($img_o,$file_output,100);
		} else {
			$func = 'image'.$ext;
			return $func($img_o,$file_output);
		}
	}
	function load_files($files,$limit_mb){
		global $extI;
			global $error;
		if($files['models']['error']||$files['icons']['error']){
			$error="Нет файлов ".$files['models']['error'].', '.$files['icons']['error'];
			return false;
		}
		if($files["models"]["size"] > 1024*$limit_mb*1024||$files['icons']['size']> 1024*$limit_mb*1024){
			$error="Размер превышает $limit_mb мегабайта";
			return false;
		}
		$extM=getExt($files["models"]["name"]);
		if($extM!='mdl'){
			$error="Не верный формат файла ".$files["models"]["name"];
			return false;
		}
		global $extI;
		$extI=getExt($files["icons"]["name"]);
		if($extI!='jpg'&&$extI!='png'&&$extI!='gif'){
			$error="Не верный формат файла ".$files["icons"]["name"]."(должен быть jpg, gif или png)";
			return false;
		}
		$id=rand(1,100000000);
		move_uploaded_file($files["icons"]["tmp_name"], "../models/icons/large".$id.'.'.$extI);
		resize("../models/icons/large".$id.'.'.$extI, "../models/icons/small".$id.'.'.$extI, 100, 100);
		move_uploaded_file($files["models"]["tmp_name"], "../models/".$id.'.'.$extM);
		return $id;
	}
	function save_model($bd){
		global $error;
		global $extI;
		if(!$_POST)return false;
		if(!isset($_POST['name'])||$_POST['name']==''){
			$error="Пустое имя";
			return false;
		}
		if(!$id=load_files($_FILES,3)){
			$error="Не загрузить файл";
			return false;
		}
		$data=$_POST;
		$data['time']=time();
		$data['id']=$id;
		$data['size']=$_FILES["models"]["size"];
		$data['id_server']=$_SESSION['server'];
		$data['icon_type']=$extI;
		$data['id_user']=$_SESSION['id'];
		$bd->insert('models',$data);
		return true;
	}
	if(save_model($bd)){
		$suc='Сохранено';
		include('../elements/suc.php');
		include('../server_admin/models.php');
	}
	else {
		if($error!='')include('../elements/errors.php');
		include('../elements/form_upload.php');
	}
?>