<?php
if(is_file('install.php')){
	header("location:install.php");exit;
}
require("global.php");

if($jobs != 'show')
	cache_page(PHP_SELF);

require(Mpath."inc/categories.php");

//SEO
$titleDB[title]		= $webdb[Info_webname];
$titleDB[keywords]	= $webdb[Info_metakeywords];



$topMenu[$ctype]=' ck';

/**
*�Ƽ�����Ŀ����ҳ��ʾ
**/
/*

/**
*��ǩʹ��
**/
$ch=0;
$chdb[main_tpl]=getTpl("sortlist_1");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
require(PHP168_PATH."inc/label_module.php");

//�̶�������Ϣ
$webdb[InfoIndexRow]=$webdb[InfoIndexRow]?$webdb[InfoIndexRow]:8;
$webdb[InfoIndexLeng]=$webdb[InfoIndexLeng]?$webdb[InfoIndexLeng]:26;


//�̶�������Ϣ

$bcategory->cache_read();
$bcategory->unsets();

require(Mpath."inc/head.php");

require(getTpl("sortlist_1"));

//require(getTpl("sortlist_1"));
require(Mpath."inc/foot.php");

if($jobs != 'show')
	cache_page_save();
?>