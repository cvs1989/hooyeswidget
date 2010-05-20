<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../data/dbcache/class.php");
require("../class/user.php");
require("../class/q_functions.php");
require LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
//验证是否登陆
$user=islogin();
//取得模型id
$mid=(int)$_GET['mid'];
if(!$mid)
{
	printerror("ErrorUrl","history.go(-1)",1);
}
$mr=$empire->fetch1("select tbname,qmname from {$dbtbpre}enewsmod where mid='$mid'");
if(!$mr['tbname'])
{
	printerror("ErrorUrl","history.go(-1)",1);
}
esetcookie("qdelinfo","dgcms");
$totalnum=(int)$_GET['totalnum'];
$start=0;
$page=(int)$_GET['page'];
$line=25;//每行显示
$page_line=16;
$offset=$page*$line;
$add="";
$search="&mid=$mid";
//搜索
$sear=$_GET['sear'];
if($sear)
{
	$keyboard=RepPostVar2($_GET['keyboard']);
	$show=$_GET['show'];
	//关键字
	if($keyboard)
	{
		$add=" and (title like '%$keyboard%')";
	}
	$search.="&sear=1&keyboard=$keyboard&show=$show";
}
$query="select id,title,checked,ismember,username,plnum,isqf,classid,totaldown,onclick,newstime,titleurl,groupid,newspath,filename,titlepic,havehtml,truetime,lastdotime,istop,isgood,firsttitle,titlefont from {$dbtbpre}ecms_".$mr['tbname']." where userid='$user[userid]' and ismember=1".$add;
$totalquery="select count(*) as total from {$dbtbpre}ecms_".$mr['tbname']." where userid='$user[userid]' and ismember=1".$add;
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
//导入模板
require(ECMS_PATH.'e/template/DoInfo/ListInfo.php');
db_close();
$empire=null;
?>