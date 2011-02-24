<?php
require_once("global.php");

if(!$lfjuid){
	refreshto("login.php","请先登录",1);
}


if(!$url){
	die("地址不存在");
}else{
	$url=base64_decode($url);
}
if(ereg("^$webdb[www_url]",$url)&&(ereg(".rar$",$url)||ereg(".zip$",$url))){
		$fileurl=str_replace("$webdb[www_url]","",$url);
		$filename=basename($fileurl);
		$filetype=substr(strrchr($filename,'.'),1);
		$_filename=preg_replace("/([\d]+)_(200[\d]+)_([^_]+)\.([^\.]+)/is","\\3",$filename);
		
		if(eregi("^([a-z0-9=]+)$",$_filename)){
			$filename=urldecode(base64_decode($_filename)).".$filetype";
		}
		ob_end_clean();
		header('Last-Modified: '.gmdate('D, d M Y H:i:s',time()).' GMT');
		header('Pragma: no-cache');
		header('Content-Encoding: none');
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-type: '.$filetype);
		if( eregi(".php",$fileurl) ){
			die("ERR");
		}
		echo read_file(PHP168_PATH."$fileurl");
}else{
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$url'>";
	exit;
}

?>