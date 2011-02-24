<?php
require(dirname(__FILE__)."/"."global.php");
//跳转到整站去
header("location:".$webdb[www_url]."/member/pm.php?job=$job");
?>