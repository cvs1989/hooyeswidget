<?php
if(is_file('install.php')){
	header("location:install.php");exit;
}
require("global.php");


$diyid=intval($diyid);
if(!$diyid){
	showerr("û���ҵ���Ҫ���ʵ�ҳ");
}
$diy=$db->get_one("select * from {$_pre}diypage where diyid='$diyid' ");
if(!$diy[diyid]) showerr("��ҳ���Ѿ���ɾ��");
//if(!$diy[isshow]) showerr("��ҳ���Ѿ�������");
$diy_tpl=str_replace(array('.htm'),array(''),$diy[filename]);

if(!file_exists(getTpl("$diy_tpl"))){
	showerr("��ҳ���Ѿ���ɾ��");
}

/**
*�Ƽ�����Ŀ����ҳ��ʾ
**/


//SEO
$titleDB[title]		= $webdb[Info_webname]."-".$diy[name];
$titleDB[keywords]	= $webdb[Info_metakeywords];

/**
*��ǩʹ��
**/
$ch=0;
$chdb[main_tpl]=getTpl("$diy_tpl");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//ϵͳ�ض�ID����,ÿ��ϵͳ������ͬ
require(PHP168_PATH."inc/label_module.php");

$diy=$db->get_one("update  {$_pre}diypage set hits=hits+1 where diyid='$diyid' ");


require(Mpath."inc/head.php");
require(getTpl("$diy_tpl"));
require(Mpath."inc/foot.php");


?>