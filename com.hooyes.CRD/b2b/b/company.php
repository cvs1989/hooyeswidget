<?php
if(is_file('install.php')){
	header("location:install.php");exit;
}
require("global.php");
require(Mpath."inc/categories.php");

//SEO
$titleDB[title]		= $webdb[Info_webname];
$titleDB[keywords]	= $webdb[Info_metakeywords];



$topMenu['company']=' ck';

/**
*推荐的栏目在首页显示
**/
/*

/**
*标签使用
**/
$ch=0;
$chdb[main_tpl]=getTpl("company");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//系统特定ID参数,每个系统不能雷同
require(PHP168_PATH."inc/label_module.php");



//固定最新信息
$webdb[InfoIndexRow]=$webdb[InfoIndexRow]?$webdb[InfoIndexRow]:8;
$webdb[InfoIndexLeng]=$webdb[InfoIndexLeng]?$webdb[InfoIndexLeng]:26;


//固定热门信息

$bcategory->cache_read();
$bcategory->unsets();

require(Mpath."inc/head.php");
require(getTpl("company"));

require(Mpath."inc/foot.php");

?>