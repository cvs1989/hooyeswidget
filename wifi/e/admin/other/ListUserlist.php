<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];
//验证权限
CheckLevel($logininid,$loginin,$classid,"userlist");

//增加自定义信息列表
function AddUserlist($add,$userid,$username){
	global $empire,$dbtbpre;
	$listtempid=(int)$add['listtempid'];
	$maxnum=(int)$add['maxnum'];
	$lencord=(int)$add['lencord'];
	if(!$add[listname]||!$listtempid||!$add[listsql]||!$add[totalsql]||!$add[filepath]||!$add[filetype]||!$add[lencord])
	{
		printerror("EmptyUserListname","history.go(-1)");
	}
	$query_first=substr($add['totalsql'],0,7);
	$query_firstlist=substr($add['listsql'],0,7);
	if(!($query_first=="select "||$query_first=="SELECT "||$query_firstlist=="select "||$query_firstlist=="SELECT "))
	{
		printerror("ListSqlError","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userlist");
	if(empty($add['pagetitle']))
	{
		$add['pagetitle']=$add['listname'];
	}
	$add[totalsql]=ClearAddsData($add[totalsql]);
	$add[listsql]=ClearAddsData($add[listsql]);
	$sql=$empire->query("insert into {$dbtbpre}enewsuserlist(listname,pagetitle,filepath,filetype,totalsql,listsql,maxnum,lencord,listtempid) values('$add[listname]','$add[pagetitle]','$add[filepath]','$add[filetype]','".addslashes($add[totalsql])."','".addslashes($add[listsql])."',$maxnum,$lencord,$listtempid);");
	//刷新列表
	$add[listsql]=addslashes($add[listsql]);
	$add[totalsql]=addslashes($add[totalsql]);
	ReUserlist($add,"../");
	if($sql)
	{
		$listid=$empire->lastid();
		//操作日志
		insert_dolog("listid=$listid&listname=$add[listname]");
		printerror("AddUserlistSuccess","AddUserlist.php?enews=AddUserlist");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改自定义信息列表
function EditUserlist($add,$userid,$username){
	global $empire,$dbtbpre;
	$listid=(int)$add['listid'];
	$listtempid=(int)$add['listtempid'];
	$maxnum=(int)$add['maxnum'];
	$lencord=(int)$add['lencord'];
	if(!$listid||!$add[listname]||!$listtempid||!$add[listsql]||!$add[totalsql]||!$add[filepath]||!$add[filetype]||!$add[lencord])
	{
		printerror("EmptyUserListname","history.go(-1)");
	}
	$query_first=substr($add['totalsql'],0,7);
	$query_firstlist=substr($add['listsql'],0,7);
	if(!($query_first=="select "||$query_first=="SELECT "||$query_firstlist=="select "||$query_firstlist=="SELECT "))
	{
		printerror("ListSqlError","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userlist");
	/*
	//删除旧文件
	if(!($add['oldfilepath']<>$add['filepath']||$add['oldfiletype']<>$add['filetype']))
	{
		DelFiletext($add['oldjsfilename']);
	}
	*/
	if(empty($add['pagetitle']))
	{
		$add['pagetitle']=$add['listname'];
	}
	$add[totalsql]=ClearAddsData($add[totalsql]);
	$add[listsql]=ClearAddsData($add[listsql]);
	$sql=$empire->query("update {$dbtbpre}enewsuserlist set listname='$add[listname]',pagetitle='$add[pagetitle]',filepath='$add[filepath]',filetype='$add[filetype]',totalsql='".addslashes($add['totalsql'])."',listsql='".addslashes($add['listsql'])."',maxnum=$maxnum,lencord=$lencord,listtempid=$listtempid where listid=$listid");
	//刷新列表
	$add[listsql]=addslashes($add[listsql]);
	$add[totalsql]=addslashes($add[totalsql]);
	ReUserlist($add,"../");
	if($sql)
	{
		//操作日志
	    insert_dolog("listid=$listid&listname=$add[listname]");
		printerror("EditUserlistSuccess","ListUserlist.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除自定义信息列表
function DelUserlist($listid,$userid,$username){
	global $empire,$dbtbpre;
	$listid=(int)$listid;
	if(!$listid)
	{
		printerror("NotChangeUserlistid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userlist");
	$r=$empire->fetch1("select listname from {$dbtbpre}enewsuserlist where listid=$listid");
	$sql=$empire->query("delete from {$dbtbpre}enewsuserlist where listid=$listid");
	if($sql)
	{
		//操作日志
		insert_dolog("listid=$listid&listname=$r[listname]");
		printerror("DelUserlistSuccess","ListUserlist.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$addgethtmlpath="../";
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	require("../../data/dbcache/class.php");
	include("../../class/t_functions.php");
}
if($enews=="AddUserlist")
{
	AddUserlist($_POST,$logininid,$loginin);
}
elseif($enews=="EditUserlist")
{
	EditUserlist($_POST,$logininid,$loginin);
}
elseif($enews=="DelUserlist")
{
	$listid=$_GET['listid'];
	DelUserlist($listid,$logininid,$loginin);
}
else
{}
$page=(int)$_GET['page'];
$start=0;
$line=20;//每页显示条数
$page_line=20;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select listid,listname,filepath from {$dbtbpre}enewsuserlist";
$totalquery="select count(*) as total from {$dbtbpre}enewsuserlist";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by listid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理自定义信息列表</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%" height="25">位置：<a href=ListUserlist.php>管理自定义信息列表</a></td>
    <td><div align="right">
        <input type="button" name="Submit" value="增加自定义列表" onclick="self.location.href='AddUserlist.php?enews=AddUserlist';">
      </div></td>
  </tr>
</table>

<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="8%" height="25"> <div align="center">ID</div></td>
    <td width="35%" height="25"> <div align="center">列表名称</div></td>
    <td width="11%"><div align="center">预览</div></td>
    <td width="17%" height="25"> <div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  $jspath=$public_r['newsurl'].str_replace("../../","",$r['filepath']);
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"> <div align="center"> 
        <?=$r[listid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[listname]?>
      </div></td>
    <td><div align="center">[<a href="<?=$jspath?>" target="_blank">预览</a>]</div></td>
    <td height="25"> <div align="center">[<a href="AddUserlist.php?enews=EditUserlist&listid=<?=$r[listid]?>">修改</a>]&nbsp;[<a href="AddUserlist.php?enews=AddUserlist&docopy=1&listid=<?=$r[listid]?>">复制</a>]&nbsp;[<a href="ListUserlist.php?enews=DelUserlist&listid=<?=$r[listid]?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="4">&nbsp;&nbsp;&nbsp; 
      <?=$returnpage?>
    </td>
  </tr>
</table>
</body>
</html>
<?
db_close();
$empire=null;
?>
