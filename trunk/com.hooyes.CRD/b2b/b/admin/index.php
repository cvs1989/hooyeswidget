<?php
require_once("global.php");

if($iframe=='head')
{
	
	require_once("template/header.htm");
	exit;
}

preg_match("/(.*)\/(index\.php|)\?main=([^\/]+)/is",$WEBURL,$array);
$MainUrl=$array[3]?$array[3]:"main.php";
require_once("template/index.htm");

?>