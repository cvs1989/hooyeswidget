<?php
defined('ROOT_PATH') or die();
require_once(ROOT_PATH."inc/waterimage.php");

if($webdb[is_MathYZ]){
	$array=array('-','+');
	$a=rand(0,9);
	$typem=rand(0,1);
	$b=$array[$typem];
	$c=rand(0,9);
	$string_yzimg=$a.$b.$c.'=?';
	$_string_yzimg=$typem?($a+$c):($a-$c);
}else{
	$_string_yzimg=$string_yzimg=yzImgNumRand(4);
}

$db->query("REPLACE INTO `{$pre}yzimg` ( `sid` , `imgnum` , `posttime` ) VALUES ('$usr_sid', '$_string_yzimg', '$timestamp')");

if($webdb[YzImg_difficult]){	//难识别的图片
	yz2img($string_yzimg);
	
}else{	//易识别的图片
	yzImg($string_yzimg);	
}

function yzImgNumRand($lenth){
	global $webdb;
	$string = "0123456789qwertyuipasdfghjkzxcvbnmQERTYUADFGHJLBNM";
	if(eregi("^([a-z0-9]+)$",$webdb[YzImg_string])){
		$string = $webdb[YzImg_string];
	}
	mt_srand((double)microtime() * 1000000);
	for($i=0;$i<$lenth;$i++){
		$randval.= substr($string,mt_rand(0,strlen($string)-1),1);
	}
	return $randval;
}
?>