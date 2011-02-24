<?php
require_once("global.php");
require("datainfo.php");

$linkdb=array("一天内"=>"?day=1","一周内"=>"?day=7","一个月内"=>"?day=30","六个月内"=>"?day=180","全部"=>"?day=100000000");

require("head.php");
require("template/data.htm");
require("foot.php");
?>