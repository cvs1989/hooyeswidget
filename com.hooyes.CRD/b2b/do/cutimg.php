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
	//die("剪裁成功,<A HREF='$uploadfile?$timestamp' target=_blank>点击查看效果</A> <a href='javascript:window.self.close()'>点击关闭</a>");
}
if(!ereg("^http:",$srcimg)){
	$srcimg="$weburl_array[upfiles]/$srcimg";
}
require(html("cutimg"));
?>