<?
class Convert2VTFSprite{
	var $origins;
	var $sizes;
	var $fileGD;
	var $fileGDS;
	var $palCorrect=array();
	var $error;
	var $minSize=4;
	var $maxSize=1024;
	var $transparentIf=100;
	var $mmsizes;
	var $lastFrame=0;
	var $path_watermark=0;
	var $headerData=array(
		"sig"=>"VTF\0",		//char[4]
		"ver"=>array(7,2),	//int[2]
		"headerSize"=>80,	//int
		"width"=>0,			//short
		"height"=>0,		//short
		"flags"=>0,			//int
		"frames"=>1,		//short
		"firstFrame"=>0,	//short
		"reflectPad1"=>array(0,0,0,0),	//char[4]
		"reflectVec"=>array(0.0,0.0,0.0), //float[3]
		"reflectPad2"=>array(0,0,0,0),	//char[4]
		"bumpmapScale"=>0.0,	//float
		"highResImageFormat"=>13, 	//int
		"mipmapCount"=>0,		//char
		"lowResImageFormat"=>-1,	//int
		"lowResImageWidth"=>0,	////char
		"lowResImageHeight"=>0, //char
		"depth"=>1	//short
	);

	function convert($files,$sprite,$vtf){
		$frames=sizeof($files);
		$this->headerData['frames']=$frames;
		if(!$this->open_spr($vtf))
			return false;
		if(!$sizeMM=$this->get_mipmaps_size($files[0])){
			return false;
		}
		for($i=0;$i<$frames;$i++){
			if(!$this->open_file($files[$i],$i)){
				$this->error="Cant open frame ".$files[$i];
				return false;
			}
			$this->write_mipmaps($i);
		}
		$this->write_header();
		fclose($this->file_spr);
		if(!$this->open_spr($sprite))
			return false;
		$this->write_headerS();
		$this->write_palette();
		$this->write_data();
		fclose($this->file_spr);
		imagedestroy($this->fileGDS);
		return true;
	}
	function open_spr($path){
		$this->file_spr = fopen($path, "w+b");
		if(!$this->file_spr){
			$this->error="Cant create file".$path;
			return false;
		}
		return true;
	}
	function open_file($path,$n){
		if(!$ss=$this->check_size($path))
			return false;
		
		if($this->headerData['width']!=$ss[0]||$this->headerData['height']!=$ss[1]){
			$this->error="Size need divisible by 4 and >".$this->minSize." and <".$this->maxSize;
			return false;
		}
		$counF=$n+1;
		$this->fileGD=imagecreatetruecolor ($ss[0],$ss[1]);
		$tempfileGD=imagecreatetruecolor($ss[0],($ss[1]*$counF));
		if($n){
			imagecopy($tempfileGD,$this->fileGDS,0,0,0,0,$ss[0],$ss[1]);
			imagedestroy($this->fileGDS);
		}
		$this->fileGDS=$tempfileGD;
		if($ss[2]==IMAGETYPE_GIF)$fileGD=imagecreatefromgif($path);
		elseif($ss[2]==IMAGETYPE_JPEG)$fileGD=imagecreatefromjpeg($path);
		elseif($ss[2]==IMAGETYPE_PNG)$fileGD=imagecreatefrompng($path);
		else{
			$this->error="Not supported format";
			return false;
		}
		
		$x=0;$y=0;
		if(is_array($this->origins)){
			$x=$this->origins[$n]['x'];
			$y=$this->origins[$n]['y'];
		}
		if($this->path_watermark)
			$this->add_watermark($fileGD,$this->path_watermark,$x,$y);
		imagecopy($this->fileGD,$fileGD,0,0,$x,$y,$ss[0],$ss[1]);
		imagecopy($this->fileGDS,$fileGD,0,($ss[1]*$n),$x,$y,$ss[0],$ss[1]);
		imagegif($this->fileGD,$path.'.gif');
		imagedestroy($fileGD);
		return true;
	}
	function add_watermark($image,$path,$x,$y){
		$gd_sloy=imagecreatefrompng($path);
		imagecopy($image, $gd_sloy, $x, $y, 0, 0, imagesx($gd_sloy), imagesy($gd_sloy));
	}
	function get_image_size($path){
		$ext=getimagesize($path);
		if(is_array($this->sizes)){
			$ext[0]=$this->sizes['width'];
			$ext[1]=$this->sizes['height'];
		}
		return $ext;
	}
	function check_size($path){
		if(!file_exists($path)){
			$this->error="No such file or directory ".$path;
			return false;
		}
		$ext=$this->get_image_size($path);
		$w=$ext[0];
		$h=$ext[1];
		$rightSizes=array(4,8,16,32,64,128,256,512,1024,2048);
		$wc=false;$hc=false;
		for($i=0;$i<10;$i++){
			if($rightSizes[$i]==$w)
				$wc=true;
			if($rightSizes[$i]==$h)
				$hc=true;
		}
		if(!($wc&&$hc)){
			$this->error="Size dont correct file ".$path;
			return false;
		}
		else 
			return array($w,$h,$ext[2]);
	}
	function get_mipmaps_size($path){
		if(!$ss=$this->check_size($path))
			return false;
		$this->headerData['width']=$ss[0];
		$this->headerData['height']=$ss[1];
		$w=$ss[0];
		$h=$ss[1];
		$sizes=array(array($w,$h));
		$mmc=1;
		$summByte=0;
		while(($w>4)&&($h>4)){
			$w=$w/2;
			$h=$h/2;
			$summByte+=($w*$h)/2;
			$sizes[]=array($w,$h);
			$mmc++;
		}
		$this->headerData["mipmapCount"]=$mmc;
		$this->mmsizes=$sizes;
		return $summByte;
	}
	function write_headerS(){
		$this->write_char32('IDSP');
		$this->write_uint32(2);
		$this->write_uint32(3);//Type of spr
		$this->write_uint32(0);//texture format
		$f = (float)sqrt(($this->headerData['width'] >> 1) * ($this->headerData['width'] >> 1) + ($this->headerData['height'] >> 1) * ($this->headerData['height'] >> 1));
		$this->write_float32($f);
		$this->write_uint32($this->headerData['width']);//Max width
		$this->write_uint32($this->headerData['height']);
		$this->write_uint32($this->headerData['frames']);
		$this->write_float32('0.0f'); //Always 0 ?
		$this->write_uint32(1); //Synch. type
	}
	function write_palette(){
		imagetruecolortopalette($this->fileGDS,false,255);
		$colors=imagecolorstotal($this->fileGDS);
		$palSize=($colors+1)*3;
		$this->write_uint16(($colors+1));
		$this->write_byte(255);
		$this->write_byte(255);
		$this->write_byte(255);
		for($i=0;$i<$colors;$i++){
			$c24=imagecolorsforindex($this->fileGDS,$i);
			$this->write_byte($c24['red']);
			$this->write_byte($c24['green']);
			$this->write_byte($c24['blue']);
			if($c24['alpha']>$this->transparentIf){
				$this->palCorrect[$i]=0;
				
			}else{
				$this->palCorrect[$i]=$i+1;
			}
		}
		
	}
	function write_data(){
		for($i=0;$i<$this->headerData['frames'];$i++){
			$this->write_uint32(0); //group
			$this->write_uint32(0/*-(int)($this->headerData['width']/2)*/); ///origin x
			$this->write_uint32(0/*(int)($this->headerData['height']/2)*/); 	//origin y
			$this->write_uint32($this->headerData['width']); //w
			$this->write_uint32($this->headerData['height']); //h
			$d=$this->headerData['height']*($i+1);
			for($y=$this->headerData['height']*$i;$y<$d;$y++){
				for($x=0;$x<$this->headerData['width'];$x++){
					$ind=imagecolorat($this->fileGDS,$x,$y);
					$this->write_byte($this->palCorrect[$ind]);
				}
			}
		}
		return true;
	}
	function write_header(){
		fseek($this->file_spr, 0);
		$this->write_char32($this->headerData['sig']);
		$this->write_uint32($this->headerData['ver'][0]);
		$this->write_uint32($this->headerData['ver'][1]);
		$this->write_uint32($this->headerData['headerSize']);
		$this->write_uint16($this->headerData['width']);
		$this->write_uint16($this->headerData['height']);
		$this->write_uint32($this->headerData['flags']);
		$this->write_uint16($this->headerData['frames']);
		$this->write_uint16($this->headerData['firstFrame']);
		$this->write_byte($this->headerData['reflectPad1'][0]);
		$this->write_byte($this->headerData['reflectPad1'][1]);
		$this->write_byte($this->headerData['reflectPad1'][2]);
		$this->write_byte($this->headerData['reflectPad1'][3]);
		$this->write_float32($this->headerData['reflectVec'][0]);
		$this->write_float32($this->headerData['reflectVec'][1]);
		$this->write_float32($this->headerData['reflectVec'][2]);
		$this->write_byte($this->headerData['reflectPad2'][0]);
		$this->write_byte($this->headerData['reflectPad2'][1]);
		$this->write_byte($this->headerData['reflectPad2'][2]);
		$this->write_byte($this->headerData['reflectPad2'][3]);
		$this->write_float32($this->headerData['bumpmapScale']);
		$this->write_uint32($this->headerData['highResImageFormat']);
		$this->write_byte($this->headerData['mipmapCount']);
		$this->write_uint32($this->headerData['lowResImageFormat']);
		$this->write_byte($this->headerData['lowResImageWidth']);
		$this->write_byte($this->headerData['lowResImageHeight']);
		$this->write_uint16($this->headerData['depth']);
	}
	function dxt1convert($img){
		$maxInsex=imagecolorclosest($img,255,255,255);
		$maxColor=imagecolorsforindex($img,$maxInsex);
		$minInsex=imagecolorclosest($img,0,0,0);
		$minColor=imagecolorsforindex($img,$minInsex);
		$countColors=imagecolorstotal($img);
		$transparent=false;
		for($c=0;$c<$countColors;$c++){
			$col=imagecolorsforindex($img,$c);
			if($col['alpha']>$this->transparentIf){
				$transparent=true;
				break;
			}
		}
		$colors=array();
		if($transparent){
			$colors[0]=$minColor;
			$colors[1]=$maxColor;
			$colors[2]=array(
				'red'=>round(($colors[0]['red']+$colors[1]['red'])/2),
				'green'=>round(($colors[0]['green']+$colors[1]['green'])/2),
				'blue'=>round(($colors[0]['blue']+$colors[1]['blue'])/2)
			);
			$colors[3]=array(
				'red'=>0,
				'green'=>0,
				'blue'=>0
			);
		}else{
			$colors[0]=$maxColor;
			$colors[1]=$minColor;
			$colors[2]=array(
				'red'=>round((2 * $colors[0]['red'] + $colors[1]['red'] + 1) / 3),
				'green'=>round((2 * $colors[0]['green'] + $colors[1]['green'] + 1) / 3),
				'blue'=>round((2 * $colors[0]['blue'] + $colors[1]['blue'] + 1) / 3)
			);
			$colors[3]=array(
				'red'=>round(($colors[0]['red'] + 2 * $colors[1]['red'] + 1) / 3),
				'green'=>round(($colors[0]['green'] + 2 * $colors[1]['green'] + 1) / 3),
				'blue'=>round(($colors[0]['blue'] + 2 * $colors[1]['blue'] + 1) / 3)
			);
		}
		$img2=imagecreate(4,4);
		for($i=0;$i<4;$i++){
			imagecolorallocate($img2,$colors[$i]['red'],$colors[$i]['green'],$colors[$i]['blue']);
		}
		$dataDxt=array();
		$dataDxt[0]=$this->rgb888_to_565($colors[0]);
		$dataDxt[1]=$this->rgb888_to_565($colors[1]);
		$dataDxt[2]=0;
		$dataDxt[3]=0;
		$d=2;
		for($y=0;$y<4;$y++){
			if($y==2)$d++;
			for($x=3;$x>-1;$x--){
				$c=imagecolorsforindex($img,imagecolorat($img,$x,$y));
				if($transparent&&$c['alpha']>$this->transparentIf){
					$dataDxt[$d]=($dataDxt[$d]<<2)|3;
					continue;
				}
				$c=imagecolorclosestalpha($img2,$c['red'],$c['green'],$c['blue'],0);
				$dataDxt[$d]=($dataDxt[$d]<<2)|$c;
			}
		}
		$dataDxt[2]=($dataDxt[2]>>8)|(($dataDxt[2]&255)<<8);
		$dataDxt[3]=($dataDxt[3]>>8)|(($dataDxt[3]&255)<<8);
		imagedestroy($img2);
		imagedestroy($img);
		return $dataDxt;
	}
	function create_low_img($x,$y,$img_from){
		$img=imagecreatetruecolor(4,4);
		imagecopy($img,$img_from,0,0,$x,$y,4,4);
		imagetruecolortopalette($img,false,16);
		return $img;
	}
	function rgb888_to_565($rgb){
		return ((($rgb['red']>>3)&0x001f)<<11)|((($rgb['green']>>2)&0x003f)<<5)|(($rgb['blue']>>3)&0x001f);
	}
	function write_mipmap($w,$h){
		for($y=0;$y<$w;$y+=4){
			for($x=0;$x<$h;$x+=4){
				$img=$this->create_low_img($x,$y,$this->fileGD);
				$data=$this->dxt1convert($img);
				for($n=0;$n<4;$n++){
					$this->write_uint16($data[$n]);
				}
			}
		}
	}
	function get_position($f,$m){
		$cmm=sizeof($this->mmsizes);
		$pos=0;
		for($s=$m+1;$s<$cmm;$s++){
			$pos+=$this->mmsizes[$s][0]*$this->mmsizes[$s][1]/2*$this->headerData['frames'];
		}
		$pos+=$this->mmsizes[$m][0]*$this->mmsizes[$m][1]/2*$f;
		$pos+=$this->headerData['headerSize'];
		return $pos;
	}
	function write_mipmaps($f){
		
		$mmSize=$this->mmsizes;
		$countMm=sizeof($mmSize);
		for($i=0;$i<$countMm;$i++){
			fseek($this->file_spr,$this->get_position($f,$i));
			$this->write_mipmap($mmSize[$i][1],$mmSize[$i][0]);
			if($i<($countMm-1)){
				//$img=imagecreate( $mmSize[$i+1][0], $mmSize[$i+1][1]);
				imagecopyresampled($this->fileGD, $this->fileGD,0,0,0,0, $mmSize[$i+1][0], $mmSize[$i+1][1], $mmSize[$i][0], $mmSize[$i][1]);
				//imagedestroy($this->fileGD);
				//$this->fileGD=$img;
			}
		}
	}
	function write_byte($p,$s=1){
		$b=pack("C",$p);
		return fwrite($this->file_spr,$b,$s);
	}
	function write_uint32($p){
		return fwrite($this->file_spr,pack("i",$p),4);
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
	function write_str($p,$s=1){
		return fwrite($this->file_spr,pack("A",$p),$s);
	}

}

?>