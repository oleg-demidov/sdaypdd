<?
	$error;
	define( 'VP_PARALLEL_UPRIGHT', 0 );
	define( 'FACING_UPRIGHT', 1 );
	define( 'VP_PARALLEL', 2 );
	define( 'ORIENTED', 3 );
	define( 'VP_PARALLEL_ORIENTED', 4 );
	
	define( 'SPR_NORMAL', 0 );
	define( 'SPR_ADDITIVE', 1 );
	define( 'SPR_INDEXALPHA', 2 );
	define( 'SPR_ALPHTEST', 3 );
	
	define( 'MAX_SIZE_SPR', 1000000 );
    echo'do2';
	class Image2Sprite{
		var $file_spr;
		var $images=array();
		var $size_spr=0;
		function __construct($outSpr, $files, $type, $textFormat, $palIndex=0,$dither=true){
			
			global $error;
			$maxH=0;$maxW=0;
			foreach($files as $path){
				if(!$data_img=$this->path2gd($path,$dither))
					return false;
				$this->images[sizeof($this->images)]=$data_img;
				
				//maximum size
				if($maxW<$data_img[1])$maxW=$data_img[1];
				if($maxH<$data_img[2])$maxH=$data_img[2];
			}
			//echo'from- '.$files[0].' to '.$outSpr;
			//Calc. bounding box
			$f = (float)sqrt(($maxW >> 1) * ($maxW >> 1) + ($maxH >> 1) * ($maxH >> 1));
			
			//write header
			$this->file_spr = @fopen($outSpr, "w+b");
			$this->write_char32('IDSP');
			$this->write_uint32(2);
			$this->write_uint32($type);
			$this->write_uint32($textFormat);
			$this->write_float32($f);
            $this->write_uint32($maxW);
            $this->write_uint32($maxH);
            $this->write_uint32(sizeof($files));
            $this->write_float32('0.0f'); //Always 0 ?
            $this->write_uint32(1); //Synch. type
                
			//Color palette
			$sizePal=$this->images[$palIndex][3];
			$this->write_uint16($sizePal);
			
			for($i=0;$i<$sizePal;$i++){
				$arr=imagecolorsforindex($this->images[$palIndex][0] ,$i);
				$this->write_byte($arr['red']);
				$this->write_byte($arr['green']);
				$this->write_byte($arr['blue']);
			}
			
			//Data images
			for($i=0;$i<sizeof($files);$i++){
				$this->write_uint32(0); //group
				$this->write_uint32(-(int)($this->images[$i][1] / 2)); //origin x
				$this->write_uint32((int)($this->images[$i][2] / 2)); //origin y
				$this->write_uint32($this->images[$i][1]); //w
				$this->write_uint32($this->images[$i][2]); //h
				for($y=0;$y<$this->images[$i][2];$y++){
					for($x=0;$x<$this->images[$i][1];$x++){
						$ind=imagecolorat($this->images[$i][0],$x , $y );
						$this->write_byte($ind);
					}
				}
			}
			fclose($this->file_spr);
		}
		
        
					
		function path2gd($path,$dither){
			global $error;
			echo $path;
			list($w_i, $h_i, $type) = getimagesize($path);
			
			if (!$w_i || !$h_i) {
				$error='Невозможно получить длину и ширину изображения';
				return false;
				}
			if(($this->size_spr+=$w_i*$h_i)>MAX_SIZE_SPR){
				$error='Размер спрайта будет превышать 1мб ';
				return false;
			}
			$types = array('','gif','jpeg','png');
			$ext = $types[$type];
			if (!$ext) {
				$error='Некорректный формат файла';
				return false;
			}
			$func = 'imagecreatefrom'.$ext;
			if(!$img = $func($path)){
				$error='Невозможно открыть изображение '.$path;
				return false;
			}
			if($ext!='gif'){
				$true_img=imagecreatetruecolor ( $w_i , $h_i );
				if(!$true_img){
					$error='Не создать полноцветное изображение';
					return false;
				}
				$copy=imagecopy ( $true_img , $img , 0 ,0 , 0 , 0 , $w_i , $h_i );
				$colP=imagetruecolortopalette($true_img,$dither,256);
				imagedestroy($img);
				if(!($copy||$colP)){
					$error='Не imagecopy, imagetruecolortopalette';
					return false;
				}
				$img=$true_img;
			}
				
				
				$pal=imagecolorstotal($img);
				echo ' размер палитры-'.$pal;
			
			return array($img,$w_i, $h_i,$pal);
		}
		function write_uint32($p){
			return fwrite($this->file_spr,pack("I",$p),4);
		}
		function write_uint16($p){
			return fwrite($this->file_spr,pack("S",$p),2);
		}
		function write_char32($p){
			return fwrite($this->file_spr,pack("a*",$p),4);
		}
		function write_float32($p){
			$xx='';
			for($i=0;$i<(4-strlen($p));$i++)$xx.='x';
			return fwrite($this->file_spr,pack($xx."f",$p),4);
		}
		function write_byte($p,$s=1){
			return fwrite($this->file_spr,pack("C",$p),$s);
		}
	}
	
	$f=new Image2Sprite('sprite.spr',array('ibanez.jpg','262670.png'),VP_PARALLEL,SPR_NORMAL);
	echo $error;
?>