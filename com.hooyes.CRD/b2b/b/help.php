<?php
require("global.php");


$fid=intval($fid);
$id=intval($id);

$title_name="帮 助 中 心";

if($id){ //得到本文档

	$rsdb=$db->get_one("select * from {$_pre}news where id='$id' ");
	if(!$rsdb[id]) showerr("内容不存在");
	
}else{//得到本列表

	$rows=20;
	$page=intval($page);
	if($page<1) $page=1;
	$min=($page-1)*$rows;
	$where=" where  1  ";
	if($fid)$where.=" and fid='$fid' ";
	if($help_key) $where.=" and title like('%$help_key%') ";
	$query=$db->query("select * from {$_pre}news $where  order by levels desc limit $min,$rows");
	while($rs=$db->fetch_array($query)){
		$rs[title]=str_replace($help_key,"<font color=red>".$help_key."</font>",$rs[title]);
		$listdb[]=$rs;
	}
	
	$showpage=getpage("{$_pre}news",$where,"?fid=$fid&help_key=".urlencode($help_key),$rows);
	if($fid) $title_name=$helpFid_db[name][$fid];
}




/**
*标签使用
**/
$ch=0;
$chdb[main_tpl]=getTpl("help");
$ch_fid	= $ch_pagetype = 0;
$ch_module = 127;//系统特定ID参数,每个系统不能雷同
require(PHP168_PATH."inc/label_module.php");
//SEO
$titleDB[title]			= filtrate(strip_tags("帮助中心-{$helpFid_db[name][$fid]}{$helpFid_db[name][$rsdb[fid]]} {$rsdb[title]} $webdb[Info_webname]"));
$titleDB[keywords]		= filtrate(strip_tags("$fidDB[metakeywords]  $webdb[Info_metakeywords]"));
$titleDB[description]	= get_word(strip_tags($fidDB[descrip]),200).filtrate(strip_tags("$fidDB[metadescription] $webdb[Info_metadescription]"));


require(Mpath."inc/head.php");
require(getTpl("help"));
require(Mpath."inc/foot.php");

?>