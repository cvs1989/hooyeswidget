<?php
require(dirname(__FILE__)."/global.php");
@include(dirname(__FILE__)."/data/guide_fid.php");

/**
*获取栏目与模块的配置文件
**/
$fidDB=$db->get_one("SELECT A.* FROM {$_pre}sort A WHERE A.fid='$fid'");

/**
*跳转到外部地址
**/
if($fidDB[jumpurl])
{
	header("location:$fidDB[jumpurl]");
	exit;
}

$fidDB[descrip]=En_TruePath($fidDB[descrip],0);


//SEO
$titleDB[title]		= filtrate("$fidDB[name] - $webdb[Info_webname] - $titleDB[title]");
$titleDB[keywords]	= $titleDB[description] = filtrate("$webdb[Info_webname] - $webdb[Info_metakeywords] - $titleDB[title] - $titleDB[keywords]");


//栏目风格
$fidDB[style] && $STYLE=$fidDB[style];


/**
*模板
**/
$FidTpl=unserialize($fidDB[template]);
$head_tpl=$FidTpl['head'];
$foot_tpl=$FidTpl['foot'];

$_url="list.php?fid=$fid";



/**
*为获取标签参数
**/
if($fidDB[type]){
	$chdb[main_tpl]=getTpl("bigsort",$FidTpl['list']);
}else{
	$chdb[main_tpl]=getTpl("list",$FidTpl['list']);
}
/**
*标签
**/
$ch_fid	= intval($fidDB[config][label_list]);		//是否定义了栏目专用标签
$ch_pagetype = 2;									//2,为list页,3,为bencandy页
$ch_module = $webdb[module_id];						//系统特定ID参数,每个系统不能雷同
$ch = 0;											//不属于任何专题
require(ROOT_PATH."inc/label_module.php");

if($spid)
{
	$SQL=" AND A.spid='$spid' ";
}
else
{
	$SQL=" ";
}

if($cityID)
{
	$SQL=" AND A.cityid='$cityID' ";
}
elseif($provinceID)
{
	$SQL=" AND A.provinceid='$provinceID' ";
}

$Lrows=$fidDB[maxperpage]>0?$fidDB[maxperpage]:($webdb[Infolist_row]>0?$webdb[Infolist_row]:15);
if($fidDB[type]==0)
{
	@extract($db->get_one("SELECT COUNT(*) AS NUM FROM {$_pre}content A WHERE A.fid=$fid AND A.yz=1 $SQL"));
}

/*大分类*/
if($fidDB[type]==1)
{
	$sort_db=$listdb_moresort=ListOnlySort(100);
}

/*小分类*/
if($fidDB[type]==0)
{	
	$listdb=ListThisSort($Lrows,$webdb[InfoListLeng]>0?$webdb[InfoListLeng]:70);
	$showpage=getpage("{$_pre}content A","WHERE A.fid=$fid AND A.yz=1 $SQL",$_url,$Lrows,$NUM);	
}


require(ROOT_PATH."inc/head.php");
if($fidDB[type]){
	require(getTpl("bigsort",$FidTpl['list']));
}else{
	require(getTpl("list",$FidTpl['list']));
}
require(ROOT_PATH."inc/foot.php");

?>