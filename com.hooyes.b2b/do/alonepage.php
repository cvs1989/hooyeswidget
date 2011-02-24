<?php
require(dirname(__FILE__)."/"."global.php");

$rsdb=$db->get_one("SELECT * FROM {$pre}alonepage WHERE id='$id'");
$db->query("UPDATE {$pre}alonepage SET hits=hits+1 WHERE id='$id' ");

if(!$rsdb)
{
	showerr("内容不存在");
}

//SEO
$titleDB[title]		= "$rsdb[title]";
$titleDB[keywords]	= $titleDB[description] = "$rsdb[title] - $rsdb[keywords]";

//模板
$head_tpl=$rsdb['tpl_head'];
$main_tpl=$rsdb['tpl_main'];
$foot_tpl=$rsdb['tpl_foot'];


//标签
$chdb[main_tpl]=html("alonepage",$main_tpl);			//获取标签参数
$ch_fid	= intval($id);								//每个标签不一样
$ch_pagetype = 9;									//2,为list页,3,为bencandy页
$ch_module = 0;										//文章模块,默认为0
$ch = 0;											//不属于任何专题
require(ROOT_PATH."inc/label_module.php");


if(!$rsdb[ishtml]){
	require_once(ROOT_PATH."inc/encode.php");
	$rsdb[content]=format_text($rsdb[content]);
}else{
	$rsdb[content] = En_TruePath($rsdb[content],0);
}

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);


require(ROOT_PATH."inc/head.php");
require(html("alonepage",$main_tpl));
require(ROOT_PATH."inc/foot.php");

if($job=='makehtml'&&$rsdb[filename]){
	$content=ob_get_contents();
	$path=dirname(ROOT_PATH.$rsdb[filename]);
	if(!is_dir($path)){
		makepath($path);
	}
	write_file(ROOT_PATH."$rsdb[filename]",$content);
	ob_end_clean();
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/$rsdb[filename]'>";
	exit;
}
?>