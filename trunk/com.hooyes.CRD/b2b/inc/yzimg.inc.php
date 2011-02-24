<?php
$im = imagecreate(65,30) or die();
$ck='';
$source = '23456789ABCDEFHKLMNPQRTUVWZYX';
$l = strlen($source) -1;

for($i=0;$i<4;$i++)
	$ck .= $source{mt_rand(0, $l)};

	
imagecolorallocate($im,mt_rand(192,255),mt_rand(192,255),mt_rand(192,255));
$icol = imagecolorallocate($im,mt_rand(0,98),mt_rand(0,98),mt_rand(0,98));
$scol = imagecolorallocate($im,mt_rand(99,193),mt_rand(99,193),mt_rand(99,193));
for($i=0; $i < 2; $i++) {
$linecolor = imagecolorallocate($im, 17, 158, 20);
$lefty = rand(1, 30-1);
$righty = rand(1, 30-1);
imageline($im, 0, $lefty, imagesx($im), $righty, $linecolor);
}
for($i=0;$i<4;$i++)
	intval($ck{$i}) ? imagettftext($im,16, mt_rand(-10, 10), $i*16, mt_rand(15, 30),$icol, dirname(__FILE__).'/lsansdi.ttf', $ck{$i}) : imagettftext($im,16, mt_rand(-10, 10), $i*16, mt_rand(15, 30),$scol, dirname(__FILE__).'/lsansdi.ttf', $ck{$i}); 
//imagettftext($im, 16, -5, 12, 22, $scol, 'comic.ttf', $ck);



for($i=0;$i<64;$i++) imagesetpixel($im,mt_rand(0,65),mt_rand(0,30),imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255)));

header("Content-type: image/jpeg");
header("Expires: -1");
header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0");
header("Pragma: no-cache");
imagejpeg($im);
imagedestroy($im);
//$check_time = time()+60;
//set_cookie('yzImgNum', md5(strtolower($ck).'salt'));
$_SESSION['yzImgNum'] = strtolower($ck);
//set_cookie('check_time', $check_time);

?>