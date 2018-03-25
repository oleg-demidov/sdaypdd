<?
header("Content-Type: text/plain"); 
function read_bits($byte,$st,$col=1){
			if($col>1){
				$vi=~(-1<<$col);
				//echo ' не-1<<6='.$vi;
				$code=hexdec($byte)&($vi<<$st);
				//echo $code;
			}
			else $code=hexdec($byte)&(1<<$st);
			return ($code>>$st);
		}
$n= read_bits('0x25',2,3);
echo $n;
//echo (-1<<5);
?>