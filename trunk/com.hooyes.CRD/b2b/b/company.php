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
*�Ƽ�����Ŀ����ҳ��ʾ
**/
/*

/**
*��ǩʹ��
**/
$ch=0;
$chdb[main_tpl]=getTpl("company");
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
require(getTpl("company"));

require(Mpath."inc/foot.php");

?>