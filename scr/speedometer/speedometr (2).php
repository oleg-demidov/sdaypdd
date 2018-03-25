<?php
error_reporting(E_ERROR);
/**
 * author websites-development.com
 */


/** background images settings - all transparent png
 * the dial - has blank space, to allow for ledend text
 * the arrow - has to be bigger, and positioned properly. The whole thing rotates
 */
define('DIAL',       "speedometer2.png");    // the dial backgound
define('BLANK_IMG',  "blank2.png");          // a blank PNG Image
define('NEEDLE',     "arrow2.png");          // the speedial arrow
define('POINT',      "center2.png");         // a pin-point image

// legend settings
define('FONT',       'dexter.TTF'); // font for text
define('FONT_COLOR', '333366');
define('TXT_PADDING', 80);

$v     = intval($_GET['v']);
$title = isset($_GET['title']) ? $_GET['title'] : 'Готовность';
$start = isset($_GET['start']) ? intVal($_GET['start']) : 0;
$end   = isset($_GET['end']) ? intVal($_GET['end'] ) : 100;

SpeedometerChart($v, $title, $start, $end);

function SpeedometerChart( $value, $labeltitle='Готовности', $range_bottom=0, $range_top=100, $header=true ) {

  // Basic check of input
  if ($range_bottom > $range_top) {
    $tmp          = $range_bottom;
    $range_top    = $range_bottom;
    $range_bottom = $tmp;
    unset($tmp);
  }

  // prevent looping
  if ($value > $range_top)    $value = $range_top;
  if ($value < $range_bottom) $value = $range_bottom;


  // Determine angle to rotate the needle based on the range and value
  $angle = (($value - $range_bottom) * 236)/($range_top - $range_bottom);


  // load dial image
  $dial = imagecreatefrompng(DIAL);
  imageAlphaBlending($dial, true);
  imageSaveAlpha($dial, true);


  // write the legend text
  $title = imagettftext( $dial, 14, 0, TXT_PADDING-45, 150, FONT_COLOR, FONT, $labeltitle );

  //get the size of the number text, so we can align it
  $number = imagettftext( $dial, 17, 0, TXT_PADDING, 130, FONT_COLOR, FONT, number_format($value).'%' );
  // write the number text - align it center relative to legend title
 // imagettftext( $dial,16,0, TXT_PADDING+($title[2]-$title[0])/2 - ($number[2]-$title[0])/2 ,40, FONT_COLOR, FONT, number_format($value) );


  // Pull in the Needle Image, enabling
  $needleIMG = imagecreatefrompng(NEEDLE);
  imageAlphaBlending($needleIMG, true);
  imageSaveAlpha($needleIMG, true);

	

  // get original Width and Height
  $needle_x = imagesx($needleIMG);
  $needle_y = imagesy($needleIMG);

  // rotate the needle to it's position
  $needleIMG = imagerotate($needleIMG,(-$angle+28),imageColorAllocateAlpha($needleIMG, 0, 0, 0, 127));
   // the original needle is horizontal, this makes sure it starts at negative (begining of dial)
// Get new width and height
  $new_x = imagesx($needleIMG);
  $new_y = imagesy($needleIMG);


  // Create blank Image from png
  $new_img = imagecreatefrompng(BLANK_IMG);
  imageAlphaBlending($new_img, true);
  imageSaveAlpha($new_img, true);

  // Crop the image, leaving only the center part
  imagecopy($new_img, $needleIMG, 0, 0, round(($new_x - $needle_x)/2), round(($new_y - $needle_y)/2), $needle_x, $needle_y);

  // Position it over the proper 'Pin Point' for the needle
  imagecopy($dial, $new_img, 0, 0, 0, 0, imagesx($dial), imagesy($dial));

  // attach the pin-point image
  $pointImg = imagecreatefrompng(POINT);
  imagecopy($dial, $pointImg, 52, 53, 0, 0, imagesx($pointImg), imagesy($pointImg) );

  if ( $header ) {
    header("Content-Type: image/png");
  }
  imagepng($dial);
}
