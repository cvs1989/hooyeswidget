<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/q_functions.php");
require("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
define('WapPage','list');
require("wapfun.php");

//栏目ID
$classid=(int)$_GET['classid'];
if(!$classid||!$class_r[$classid]['tbname'])
{
	DoWapShowMsg('您来自的链接不存在','index.php?style=$wapstyle');
}
$pagetitle=$class_r[$classid]['classname'];

$bclassid=(int)$_GET['bclassid'];
$search='';
$add='';
if($class_r[$classid]['islast'])
{
	$add.=" and classid='$classid'";
}
else
{
	$where=ReturnClass($class_r[$classid][sonclass]);
	$add.=" and (".$where.")";
}
$search.="&amp;style=$wapstyle&amp;classid=$classid&amp;bclassid=$bclassid";

$page=intval($_GET['page']);
$line=$pr['waplistnum'];//每页显示记录数
$offset=$page*$line;
$query="select * from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where checked=1".$add;
$totalnum=intval($_GET['totalnum']);
if(empty($totalnum))
{
	$totalquery="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid]['tbname']." where checked=1".$add;
	$num=$empire->gettotal($totalquery);//取得总条数
}
else
{
	$num=$totalnum;
}
$search.="&amp;totalnum=$num";
//排序
if(empty($class_r[$classid][reorderf]))
{
	$addorder=",newstime desc";
}
else
{
	$addorder=",".$class_r[$classid][reorderf]." ".$class_r[$classid][reorder];
}
$query.=" order by istop desc".$addorder.",id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=DoWapListPage($num,$line,$page,$search);
//系统模型
$modid=$class_r[$classid][modid];
$ret_r=ReturnAddF($modid,2);
require('template/'.$usewapstyle.'/list.temp.php');
db_close();
$empire=null;
?>