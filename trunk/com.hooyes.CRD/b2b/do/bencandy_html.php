<?php
require(dirname(__FILE__)."/"."global.php");
@include(PHP168_PATH."php168/guide_fid.php");
require_once(PHP168_PATH."inc/encode.php");
set_time_limit(0);

if(!is_writable(PHP168_PATH."cache/makeShow1.php"))
{
	showerr("/cache/makeShow1.php文件不存在,或文件不可写");
}

$fidDB=$db->get_one("SELECT S.*,M.alias AS M_alias,M.keywords AS M_keyword,M.config AS M_config FROM {$pre}sort S LEFT JOIN {$pre}article_module M ON S.fmid=M.id WHERE S.fid='$fid'");
$fidDB[M_alias] || $fidDB[M_alias]='文章';
$fidDB[M_config]=unserialize($fidDB[M_config]);
$fidDB[config]=unserialize($fidDB[config]);
$FidTpl=unserialize($fidDB[template]);

$titleDB[_title]	= $titleDB[title];

$fupId=intval($fidDB[type]?$fid:$fidDB[fup]);















$III=intval($III);unset($iddb,$fiddb);

require_once(PHP168_PATH."cache/makeShow1.php");

$id_array=explode(",",$iddb[$III]);


unset($lfjuid,$web_admin,$lfjid,$lfjdb,$groupdb);

@include_once( PHP168_PATH."php168/group/2.php");		//以游客身份处理

/***********************开始***********************/
foreach( $id_array AS $key=>$value){

unset($bencandy_content);

list($id,$page)=explode("-",$value);

$aid=$id;
if(!$id){
	continue;
}
if($page<1){
	$page=1;
}

$erp=$Fid_db[iftable][$fid]?$Fid_db[iftable][$fid]:'';
$min=intval($page)-1;
$rsdb=$db->get_one("SELECT R.*,A.* FROM {$pre}article$erp A LEFT JOIN {$pre}reply$erp R ON A.aid=R.aid WHERE A.aid=$aid ORDER BY R.topic DESC,R.orderid ASC LIMIT $min,1");


$titleDB[title]		= filtrate(strip_tags("$rsdb[title] - $fidDB[name] - $titleDB[_title]"));
$titleDB[keywords]	= filtrate($rsdb[keywords]);
$rsdb[description] || $rsdb[description]=get_word(preg_replace("/(<([^<]+)>|	|&nbsp;|\n)/is","",$rsdb[content]),250);
$titleDB[description] = filtrate($rsdb[description]);

if( $fidDB[allowviewcontent] || ($rsdb[begintime]&&$timestamp<$rsdb[begintime]) || ($rsdb[endtime]&&$timestamp>$rsdb[endtime]) || $rsdb[yz]!=1 || ($rsdb[passwd]||$fidDB[passwd]) || $rsdb[allowview] || $rsdb[jumpurl] )
{
	$bencandy_content="<META HTTP-EQUIV=REFRESH CONTENT='0;URL=$webdb[www_url]/bencandy.php?&fid=$fid&id=$id&NeedCheck=1'>";
}


$STYLE = $rsdb[style] ? $rsdb[style] : ($fidDB[style] ? $fidDB[style] : $STYLE);

//相关栏目名称模板
if(is_file(html("$webdb[SideSortStyle]"))){
	$sortnameTPL=html("$webdb[SideSortStyle]");
}else{
	$sortnameTPL=html("side_sort/0");
}

if($rsdb[iframeurl])
{
	$head_tpl="template/default/none.htm";
	$main_tpl="template/default/none.htm";
	$foot_tpl="template/default/iframe.htm";
}
else
{
	$showTpl=unserialize($rsdb[template]);
	$head_tpl=$showTpl[head]?$showTpl[head]:$FidTpl['head'];
	$main_tpl=$showTpl[bencandy]?$showTpl[bencandy]:$FidTpl['bencandy'];
	$foot_tpl=$showTpl[foot]?$showTpl[foot]:$FidTpl['foot'];
}

//兼容V6前的版本
if(!$rsdb[ishtml])
{
	//附件真实地址还原
	$rsdb[content] = En_TruePath($rsdb[content],0);
	$rsdb[content] = format_text($rsdb[content]);
}
else
{
	$rsdb[content] = En_TruePath($rsdb[content],0,1);
	$rsdb[content]=preg_replace("/<IMG src=\"([^\"]+)\" border=0><A href=\"([^\"]+)\" target=_blank>([^<>]+)<\/A>/eis","encode_fileurl('\\1','\\2','\\3')",$rsdb[content]);

	$rsdb[content]=preg_replace("/href=\"([^\"]+)\"([^<>]+)p8name=\"p8download\">([^<>]+)<\/A>/eis","encode_fileurl('','\\1','\\3')",$rsdb[content]);
}

$rsdb[content]=show_keyword($rsdb[content]);	//突出显示关键字
$IS_BIZ && AvoidGather();	//防采集处理


$rsdb[posttime]=date("Y-m-d H:i:s",$rsdb[full_posttime]=$rsdb[posttime]);

if($rsdb[copyfromurl]&&!strstr($rsdb[copyfromurl],"http://")){
	$rsdb[copyfromurl]="http://$rsdb[copyfromurl]";
}

$showpage=getpage("","","bencandy.php?fid=$fid&aid=$aid",1,$rsdb[pages]);

//上一篇与下一篇
$nextdb=$db->get_one("SELECT title,aid,fid FROM {$pre}article$erp WHERE aid<'$id' AND fid='$fid' ORDER BY aid DESC LIMIT 1");
$nextdb[subject]=get_word($nextdb[title],34);
$backdb=$db->get_one("SELECT title,aid,fid FROM {$pre}article$erp WHERE aid>'$id' AND fid='$fid' ORDER BY aid ASC LIMIT 1");
$backdb[subject]=get_word($backdb[title],34);


//获取标签参数
$chdb[main_tpl]=html("bencandy",$main_tpl);

//标签
$ch_fid	= intval($fidDB[config][label_bencandy]);	//是否定义了栏目专用标签
$ch_pagetype = 3;									//2,为list页,3,为bencandy页
$ch_module = 0;										//文章模块,默认为0
$ch = 0;											//不属于任何专题
require(PHP168_PATH."inc/label_module.php");

//文章自定义模型$fidDB[config]
if($rsdb[mid]){
	if($rsdb[mid]!=$fidDB[fmid]){
		@extract($db->get_one("SELECT config AS m_config FROM {$pre}article_module WHERE id='$rsdb[mid]'"));
		$M_config=unserialize($m_config);
	}else{
		$M_config=$fidDB[M_config];
	}
	
	$_rsdb=$db->get_one("SELECT * FROM `{$pre}article_content_$rsdb[mid]` WHERE aid='$id' AND rid='$rsdb[rid]'");
	if($_rsdb){
		$rsdb=$rsdb+$_rsdb;
		show_module_content($M_config);
	}
}

$rsdb[picurl]=tempdir($rsdb[picurl]);
$webdb[AutoTitleNum] && $rsdb[pages]>1 && $rsdb[title]=Set_Title_PageNum($rsdb[title],$page);
if($rsdb[keywords]){
	unset($array);
	$detail=explode(" ",$rsdb[keywords]);
	foreach( $detail AS $key=>$value){
		$_value=urlencode($value);
		$array[]="<A HREF='$webdb[www_url]/do/search.php?type=keyword&keyword=$_value' target=_blank>$value</A>";
	}
	$rsdb[keywords]=implode(" ",$array);
}
//过滤不良词语
$rsdb[content]=replace_bad_word($rsdb[content]);
$rsdb[title]=replace_bad_word($rsdb[title]);
$rsdb[subhead]=replace_bad_word($rsdb[subhead]);

ob_end_clean();
ob_start();
$MenuArray='';

//多模型扩展接口
@include(PHP168_PATH."inc/bencandy_{$rsdb[mid]}.php");

require(PHP168_PATH."inc/head.php");
if($rsdb[mid]&&file_exists(html("bencandy_$rsdb[mid]",$main_tpl))){
	require(html("bencandy_$rsdb[mid]",$main_tpl));
}else{
	require(html("bencandy",$main_tpl));
}
require(PHP168_PATH."inc/foot.php");

$bencandy_content || $bencandy_content=ob_get_contents();
$bencandy_content=preg_replace("/<!--php168(.*?)php168-->/is","\\1",$bencandy_content);
make_html($bencandy_content,'bencandy');


}
/***********************结尾***********************/

ob_end_clean();

$III++;

if($fid=$fiddb[$III])
{
	//非批量生成静态,不需要看进度状况
	if($JumpUrl){
		header("location:?III=$III&fid=$fid");exit;
	}
	$havemake=floor((100*$III)/count($fiddb));
	write_file(PHP168_PATH."cache/makeShow_record.php","?III=$III&fid=$fid");
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
	echo "请稍候,正在生成内容页静态,已完成<a style='color:red;'>{$havemake}%</a>...<META HTTP-EQUIV=REFRESH CONTENT='0;URL=?III=$III&fid=$fid'>";
	exit;
}
else
{
	unlink(PHP168_PATH."cache/makeShow1.php");
	//非批量生成静态,不需要看进度状况
	if($JumpUrl){
		header("location:$JumpUrl");exit;
	}
	unlink(PHP168_PATH."cache/makeShow_record.php");
	echo '<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />';
	if( $webdb[TheSameMakeIndexHtml] ){
		echo "<div style='display:none'><iframe src=$webdb[www_url]/index.php?MakeIndex=1></iframe></div>";
	}
	echo "<A HREF='$weburl'>静态页生成完毕,请点击返回</A>";
	exit;
}
?>