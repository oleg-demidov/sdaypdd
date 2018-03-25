<?
class Convert2Sprite{
	var $origins;
	var $sizes;
	var $fileGD;
	var $palCorrect=array();
	var $transparentIf=60;
	var $headerData=array();
	var $error;
	function convert($files,$sprite){
		$frames=sizeof($files);
		$this->headerData['frames']=$frames;
		$this->file_spr = fopen($sprite, "w+b");
		if(!$this->file_spr){
			$this->error="Cant create file ".$sprite;
			return false;
		}
		if(!$ss=$this->check_size($files[0]))
			return false;
		$this->headerData['width']=$ss[0];
		$this->headerData['height']=$ss[1];
		for($i=0;$i<$frames;$i++){
			if(!$this->open_file($files[$i],$i)){
				return false;
			}
		}
		$this->write_header();
		$this->write_palette();
		$this->write_data();
		fclose($this->file_spr);
		return true;
	}
	function open_file($path,$n){
		if(!$ss=$this->check_size($path))
			return false;
		$counF=$n+1;
		if($this->headerData['width']!=$ss[0]||$this->headerData['height']!=$ss[1]){
			$this->error="Size(".$ss[0].",".$ss[1].") dont true in ".$path;
			return false;
		}
		$tempfileGD=imagecreatetruecolor($ss[0],($ss[1]*$counF));
		if($n){
			imagecopy($tempfileGD,$this->fileGD,0,0,0,0,$ss[0],$ss[1]);
			imagedestroy($this->fileGD);
		}
		$this->fileGD=$tempfileGD;
		if($ss[2]==IMAGETYPE_GIF)$fileGD=imagecreatefromgif($path);
		elseif($ss[2]==IMAGETYPE_JPEG)$fileGD=imagecreatefromjpeg($path);
		elseif($ss[2]==IMAGETYPE_PNG)$fileGD=imagecreatefrompng($path);
		else{
			$this->error="Not supported format ".$path;
			return false;
		}
		$x=0;$y=0;
		if(is_array($this->origins)){
			$x=$this->origins[$n]['x'];
			$y=$this->origins[$n]['y'];
		}
		imagecopy($this->fileGD,$fileGD,0,($ss[1]*$n),$x,$y,$ss[0],$ss[1]);
		imagedestroy($fileGD);
		return true;
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
		$rightSizes=array(16,32,64,128,256,512,1024,2048);
		$wc=false;$hc=false;
		for($i=0;$i<8;$i++){
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
	function write_header(){
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
		imagetruecolortopalette($this->fileGD,false,255);
		$colors=imagecolorstotal($this->fileGD);
		$palSize=($colors+1)*3;
		$this->write_uint16(($colors+1));
		$this->write_byte(255);
		$this->write_byte(255);
		$this->write_byte(255);
		for($i=0;$i<$colors;$i++){
			$c24=imagecolorsforindex($this->fileGD,$i);
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
					$ind=imagecolorat($this->fileGD,$x,$y);
					$this->write_byte($this->palCorrect[$ind]);
				}
			}
		}
		return true;
	}
	function write_byte($p,$s=1){
		$b=pack("C",$p);
		return fwrite($this->file_spr,$b,$s);
	}
	function write_array($arr,$s){
		$carr=array_merge(array('C*'),$arr);
		return fwrite($this->file_spr,call_user_func_array('pack',$carr),$s);
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