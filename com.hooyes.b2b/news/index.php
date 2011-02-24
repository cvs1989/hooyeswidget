<?php

require(dirname(__FILE__)."/global.php");
require(ROOT_PATH."data/friendlink.php");

//SEO
$titleDB[title]		= "$webdb[Info_webname] - $titleDB[title]";
$titleDB[keywords]	= $titleDB[description] = "$webdb[Info_webname] - $webdb[Info_metakeywords] - $titleDB[keywords]";

/**
*ฑ๊วฉสนำร
**/
$ch_fid	= $ch_pagetype = 0;
$ch_module = $webdb[module_id];
require(ROOT_PATH."inc/label_module.php");


require(ROOT_PATH."inc/head.php");
require(getTpl("index"));
require(ROOT_PATH."inc/foot.php");


?>