<?php
require(dirname(__FILE__)."/"."global.php");

require(PHP168_PATH."inc/head.php");
require(html("post"));
require(PHP168_PATH."inc/foot.php");

$content=ob_get_contents();
ob_end_clean();
if($webdb[cookieDomain]){
	$content=preg_replace("/document.domain([^<]+)/is","",$content);
}
echo $content;
?>