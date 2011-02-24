<?php
!function_exists('html') && exit('ERR');
if($webdb[MakeIndexHtmlTime]>0){
	$time=$webdb[MakeIndexHtmlTime]*60;
	$htmlname || $htmlname='index.htm';
	if((time()-@filemtime(PHP168_PATH."$htmlname"))>$time){
		$phpname || $phpname='index.php';
		echo "<div style='display:none'><iframe src=$webdb[www_url]/$phpname?ch=$ch&MakeIndex=1></iframe></div>";
	}
}
?>