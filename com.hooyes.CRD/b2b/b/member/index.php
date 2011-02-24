<?php
require_once("global.php");


preg_match("/(.*)\/(index\.php|)\?main=(.*)/is",$WEBURL,$array);
$MainUrl=$array[3]?$array[3]:"main.php";

require_once(Memberpath."template/index.htm");
?>