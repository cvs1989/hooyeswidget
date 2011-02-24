<?php
!function_exists('html') && exit('ERR');
require_once(ROOT_PATH."inc/artic_function.php");
if($job=="list"&&$Apower[adminmap]){
	require("menu.php");
	$adminDB=$menudb;
	$listhtml = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\t";
	$i=0;
	foreach($adminDB AS $key=>$array){
		if($i==0){
			$listhtml .= "<tr>\r\t";
		}
		$i++;
		$listhtml .= "<td class='in'>\r\t<div class=side>\r\t <div class=h><span>".$key."</span></div>\r\t <div class=c>\r\t";
		foreach($array AS $title=>$rs){
			$listhtml .= "<span><a href='".$rs['link']."'>".$title."</a></span>\r\t ";
		}
		$listhtml .= " </div>\r\t</div>\r\t</td>\r\t";
		if($i==3){
			$i=0;
			$listhtml .= " </tr>\r\t<tr>\r\t";
		}
	}
	if($i!=0){
		for($a=$i;$a<3;$a++){
			$listhtml .= " <td><br/></td>\r\t";
		}
		$listhtml .= " </tr>\r\t";
	}
	$listhtml .= " </table>\r\t";
}
hack_admin_tpl('list');
?>