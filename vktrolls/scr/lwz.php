<?

function print_gzipped_page() {

    global $HTTP_ACCEPT_ENCODING;
    if( headers_sent() ){
        $encoding = false;
    }elseif( strpos($HTTP_ACCEPT_ENCODING, 'x-gzip') !== false ){
        $encoding = 'x-gzip';
    }elseif( strpos($HTTP_ACCEPT_ENCODING,'gzip') !== false ){
        $encoding = 'gzip';
    }else{
        $encoding = false;
    }

    if( $encoding ){
        $contents = ob_get_contents();
        ob_end_clean();
        header('Content-Encoding: '.$encoding);
        print("\x1f\x8b\x08\x00\x00\x00\x00\x00");
        $size = strlen($contents);
        $contents = gzcompress($contents, 9);
        $contents = substr($contents, 0, $size);
        print($contents);
        exit();
    }else{
        ob_end_flush();
        exit();
    }
}

// At the beginning of each page call these two functions
ob_start();
ob_implicit_flush(0);
/*
function lzw_compress($string) {
        // compression
        $dictionary = array_flip(range("\0", "\xFF"));
        $word = "";
        $codes = array();
        for ($i=0; $i <= strlen($string); $i++) {
                $x = substr($string, $i, 1);
                if (strlen($x) && isset($dictionary[$word . $x])) {
                        $word .= $x;
                } elseif ($i) {
                        $codes[] = $dictionary[$word];
                        $dictionary[$word . $x] = count($dictionary);
                        $word = $x;
                }
        }
       
        // convert codes to binary string
        $dictionary_count = 256;
        $bits = 8; // ceil(log($dictionary_count, 2))
        $return = "";
        $rest = 0;
        $rest_length = 0;
        foreach ($codes as $code) {
                $rest = ($rest << $bits) + $code;
                $rest_length += $bits;
                $dictionary_count++;
                if ($dictionary_count >> $bits) {
                        $bits++;
                }
                while ($rest_length > 7) {
                        $rest_length -= 8;
                        $return .= chr($rest >> $rest_length);
                        $rest &= (1 << $rest_length) - 1;
                }
        }
        return $return . ($rest_length ? chr($rest << (8 - $rest_length)) : "");
}*/
?>