<?php
if(is_file('install.php')){
	header("location:install.php");exit;
}
require("global.php");


$diyid=intval($diyid);
if(!$diyid){
	showerr("没有找到您要访问的页");
}
$diy=$db->get_one("select * from {$_pre}diypage where diyid='$diyid' ");
if(!$diy[diyid]) showerr("此页面已经被删除");
//if(!$diy[isshow]) showerr("此页面已经被隐藏");
$diy_tpl=str_replace(array('.htm'),array(''),$diy[filename]);

if(!file_exists(getTpl("$diy_tpl"))){
	showerr("此页面已经被删除");
}

/**
*推荐的栏目在首页显示
**/


//SEO
$titleDB[title]		= $webdb[Info_webname]."-".$diy[name];
$titleDB[keywords]	= $webdb[Info_metakeywords];

/**
*标签使用
**/
$ch=0;
$chdb[main_tpl]=getTpl("$diy_tpl");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//系统特定ID参数,每个系统不能雷同
require(PHP168_PATH."inc/label_module.php");

$diy=$db->get_one("update  {$_pre}diypage set hits=hits+1 where diyid='$diyid' ");


require(Mpath."inc/head.php");
require(getTpl("$diy_tpl"));
require(Mpath."inc/foot.php");


?>