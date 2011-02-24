<?php
require_once(dirname(__FILE__)."/../inc/"."class.chinese.php");

$c=$_GET[c];
$x=$_GET[x];
$y=$_GET[y];
$str=urldecode($_GET[str]);
$size=$_GET[size];
$image='../'.$_GET[image];

if(!getimagesize(dirname(__FILE__)."/$image")){
	$image = "../images/showsp/topimg.jpg";
}

$fnt = dirname(__FILE__)."/../inc/"."font.ttf";
$str || $str = "ÇכÊהÈכ×ÖÌו!";
 
$cnvert = new Chinese("GB2312","UTF8",$str,dirname(__FILE__)."/../inc/"."gbkcode/");
$str = $cnvert->ConvertIT();

$size || $size = 30;

$img_array=getimagesize($image);

$font_array = ImageTTFBBox($size, 0, $fnt, $str);
$font_wight=intval($font_array[2]-$font_array[0]);
$font_height=intval($font_array[3]-$font_array[5]);

$x || $x=intval(($img_array[0]-$font_wight)/2);
$y || $y=intval($img_array[1]/2+$font_height/2);

$im = imagecreatefromjpeg($image);

if($c=='blue'){
	$color = imagecolorclosestalpha($im,000,000,255,20);
	$color2 = imagecolorclosestalpha($im,000,000,000,98);
	imagettftext ($im, $size, 0, $x+2, $y+2, $color2, $fnt, $str);
}elseif($c=='white'){
	$color = imagecolorclosestalpha($im,255,255,255,20);
	$color2 = imagecolorclosestalpha($im,000,000,000,99);
	imagettftext ($im, $size, 0, $x+2, $y+2, $color2, $fnt, $str);
}elseif($c=='red'){
	$color = imagecolorclosestalpha($im,255,000,000,20);
	$color2 = imagecolorclosestalpha($im,255,255,255,20);
	imagettftext ($im, $size, 0, $x+2, $y+2, $color2, $fnt, $str);
}else{
	$color = imagecolorclosestalpha($im,000,000,000,20);
	$color2 = imagecolorclosestalpha($im,255,255,255,40);
	imagettftext ($im, $size, 0, $x+2, $y+2, $color2, $fnt, $str);
}

imagettftext ($im, $size, 0, $x, $y, $color, $fnt, $str);



ImageJPEG($im);
ImageDestroy($im);

?>