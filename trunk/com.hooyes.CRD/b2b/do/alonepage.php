<?php
require(dirname(__FILE__)."/"."global.php");

$rsdb=$db->get_one("SELECT * FROM {$pre}alonepage WHERE id='$id'");
$db->query("UPDATE {$pre}alonepage SET hits=hits+1 WHERE id='$id' ");

if(!$rsdb)
{
	showerr("���ݲ�����");
}

//SEO
$titleDB[title]		= "$rsdb[title]";
$titleDB[keywords]	= $titleDB[description] = "$rsdb[title] - $rsdb[keywords]";

//ģ��
$head_tpl=$rsdb['tpl_head'];
$main_tpl=$rsdb['tpl_main'];
$foot_tpl=$rsdb['tpl_foot'];


//��ǩ
$chdb[main_tpl]=html("alonepage",$main_tpl);			//��ȡ��ǩ����
$ch_fid	= intval($id);								//ÿ����ǩ��һ��
$ch_pagetype = 9;									//2,Ϊlistҳ,3,Ϊbencandyҳ
$ch_module = 0;										//����ģ��,Ĭ��Ϊ0
$ch = 0;											//�������κ�ר��
require(PHP168_PATH."inc/label_module.php");


if(!$rsdb[ishtml]){
	require_once(PHP168_PATH."inc/encode.php");
	$rsdb[content]=format_text($rsdb[content]);
}else{
	$rsdb[content] = En_TruePath($rsdb[content],0);
}

$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[posttime]);


require(PHP168_PATH."inc/head.php");
require(html("alonepage",$main_tpl));
require(PHP168_PATH."inc/foot.php");

if($job=='makehtml'&&$rsdb[filename]){
	$content=ob_get_contents();
	$path=dirname(PHP168_PATH.$rsdb[filename]);
	if(!is_dir($path)){
		makepath($path);
	}
	write_file(PHP168_PATH."$rsdb[filename]",$content);
	ob_end_clean();
	echo "<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/$rsdb[filename]'>";
	exit;
}
?>