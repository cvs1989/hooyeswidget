<?php
require(dirname(__FILE__)."/"."global.php");

if($action=="cutimg"){
	$NewPic=str_replace($webdb[www_url],"",$uploadfile);
	$NewPic=PHP168_PATH.$NewPic;
	include(PHP168_PATH."inc/waterimage.php");
	cutimg($NewPic,$NewPic,$x,$y,$rw,$rh,$w,$h,$scale);
	if($reurl){
		$reurl=base64_decode($reurl);
		header("location:$reurl");
		exit;
	}
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$uploadfile?$timestamp'>";
	exit;
	//die("���óɹ�,<A HREF='$uploadfile?$timestamp' target=_blank>����鿴Ч��</A> <a href='javascript:window.self.close()'>����ر�</a>");
}
if(!ereg("^http:",$srcimg)){
	$srcimg="$weburl_array[upfiles]/$srcimg";
}
require(html("cutimg"));
?>