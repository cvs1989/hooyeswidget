<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/q_functions.php");
require("../data/dbcache/class.php");
require LoadLang("pub/fun.php");
require("../class/user.php");
$link=db_connect();
$empire=new mysqlquery();
$search='';
require('CheckUser.php');//验证用户
//模型
$mid=intval($_GET['mid']);
if(!$mid)
{
	printerror("ErrorUrl","",1);
}
$mr=$empire->fetch1("select tbname,qmname from {$dbtbpre}enewsmod where mid='$mid'");
if(!$mr['tbname'])
{
	printerror("ErrorUrl","",1);
}
$search.="&userid=$userid&mid=$mid";
//用户
$add="userid='$userid'";
//栏目
$classid=intval($_GET['classid']);
if($classid)
{
	if($class_r[$classid][islast])
	{
		$add.=" and classid='$classid'";
	}
	else
	{
		$add.=' and '.ReturnClass($class_r[$classid][sonclass]);
	}
	$search.="&classid=$classid";
}
$start=0;
$page=intval($_GET['page']);
$line=25;//每行显示
$page_line=16;
$offset=$page*$line;
$query="select * from {$dbtbpre}ecms_".$mr['tbname']." where ".$add." and ismember=1 and checked=1";
$totalquery="select count(*) as total from {$dbtbpre}ecms_".$mr['tbname']." where ".$add." and ismember=1 and checked=1";
$totalnum=intval($_GET['totalnum']);
if(empty($totalnum))
{
	$num=$empire->gettotal($totalquery);//取得总条数
}
else
{
	$num=$totalnum;
}
$search.="&totalnum=$num";
$query.=" order by newstime desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page1($num,$line,$page_line,$start,$page,$search);
require('template/'.$spacestyle.'/list.temp.php');
db_close();
$empire=null;
?>