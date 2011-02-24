<?php
require_once dirname(__FILE__)."/../php168/config.php";
if($webdb[close_count]){
	exit;
}
$PHP_SELF_TEMP=$_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$PHP_SELF=$_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:$PHP_SELF_TEMP;
$HTTP_HOST=$_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
$WEBURL='http://'.$HTTP_HOST.$PHP_SELF;
$thispath=preg_replace("/(.*)\/do\/(.*)/is","\\1",$WEBURL);

echo "
var stats_href       = escape(location.href);
var stats_referrer   = escape(document.referrer);
var stats_language   = navigator.systemLanguage ? navigator.systemLanguage : navigator.userLanguage;
var stats_colordepth = screen.colorDepth;
var stats_screensize = screen.width+'*'+screen.height;
var stats_date       = new Date();
var stats_timezone   = 0 - stats_date.getTimezoneOffset()/60;
document.write(\"<SCRIPT LANGUAGE='JavaScript' src='$thispath/do/hack.php?hack=count&fid=$_GET[fid]&nowurl=\"+stats_href+\"&fromurl=\"+stats_referrer+\"&windows_lang=\"+stats_language+\"&screen_size=\"+stats_screensize+\"'></SCRIPT>\");
	";
?>