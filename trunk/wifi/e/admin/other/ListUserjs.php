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
CheckLevel($logininid,$loginin,$classid,"userjs");

//增加用户自定义js
function AddUserjs($add,$userid,$username){
	global $empire,$dbtbpre;
	$jstempid=(int)$add['jstempid'];
	if(!$add[jsname]||!$jstempid||!$add[jssql]||!$add[jsfilename])
	{
		printerror("EmptyUserJsname","history.go(-1)");
	}
	$query_first=substr($add['jssql'],0,7);
	if(!($query_first=="select "||$query_first=="SELECT "))
	{
		printerror("JsSqlError","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userjs");
	$add[jssql]=ClearAddsData($add[jssql]);
	$add[substr]=(int)$add[substr];
	$sql=$empire->query("insert into {$dbtbpre}enewsuserjs(jsname,jssql,jstempid,jsfilename,substr) values('$add[jsname]','".addslashes($add[jssql])."',$jstempid,'$add[jsfilename]',$add[substr]);");
	//刷新js
	$add[jssql]=addslashes($add[jssql]);
	ReUserjs($add,"../");
	if($sql)
	{
		$jsid=$empire->lastid();
		//操作日志
		insert_dolog("jsid=$jsid&jsname=$add[jsname]");
		printerror("AddUserjsSuccess","AddUserjs.php?enews=AddUserjs");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改用户自定义js
function EditUserjs($add,$userid,$username)
{global $empire,$dbtbpre;
	$jsid=(int)$add['jsid'];
	$jstempid=(int)$add['jstempid'];
	if(!$jsid||!$add[jsname]||!$jstempid||!$add[jssql]||!$add[jsfilename])
	{
		printerror("EmptyUserJsname","history.go(-1)");
	}
	$query_first=substr($add['jssql'],0,7);
	if(!($query_first=="select "||$query_first=="SELECT "))
	{
		printerror("JsSqlError","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userjs");
	//删除旧js文件
	if($add['oldjsfilename']<>$add['jsfilename'])
	{
		DelFiletext($add['oldjsfilename']);
	}
	$add[jssql]=ClearAddsData($add[jssql]);
	$add[substr]=(int)$add[substr];
	$sql=$empire->query("update {$dbtbpre}enewsuserjs set jsname='$add[jsname]',jssql='".addslashes($add[jssql])."',jstempid=$jstempid,jsfilename='$add[jsfilename]',substr=$add[substr] where jsid=$jsid");
	//刷新js
	$add[jssql]=addslashes($add[jssql]);
	ReUserjs($add,"../");
	if($sql)
	{
		//操作日志
	    insert_dolog("jsid=$jsid&jsname=$add[jsname]");
		printerror("EditUserjsSuccess","ListUserjs.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除用户自定义js
function DelUserjs($jsid,$userid,$username){
	global $empire,$dbtbpre;
	$jsid=(int)$jsid;
	if(!$jsid)
	{
		printerror("NotChangeUserjsid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"userjs");
	$r=$empire->fetch1("select jsname,jsfilename from {$dbtbpre}enewsuserjs where jsid=$jsid");
	$sql=$empire->query("delete from {$dbtbpre}enewsuserjs where jsid=$jsid");
	//删除文件
	DelFiletext("../".$r['jsfilename']);
	if($sql)
	{
		//操作日志
		insert_dolog("jsid=$jsid&jsname=$r[jsname]");
		printerror("DelUserjsSuccess","ListUserjs.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	require("../../data/dbcache/class.php");
}
if($enews=="AddUserjs")
{
	AddUserjs($_POST,$logininid,$loginin);
}
elseif($enews=="EditUserjs")
{
	EditUserjs($_POST,$logininid,$loginin);
}
elseif($enews=="DelUserjs")
{
	$jsid=$_GET['jsid'];
	DelUserjs($jsid,$logininid,$loginin);
}
else
{}
$page=(int)$_GET['page'];
$start=0;
$line=20;//每页显示条数
$page_line=20;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select jsid,jsname,jsfilename from {$dbtbpre}enewsuserjs";
$totalquery="select count(*) as total from {$dbtbpre}enewsuserjs";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by jsid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理用户自定义JS</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%" height="25">位置：<a href=ListUserjs.php>管理用户自定义JS</a></td>
    <td><div align="right">
        <input type="button" name="Submit" value="增加自定义JS" onclick="self.location.href='AddUserjs.php?enews=AddUserjs';">
      </div></td>
  </tr>
</table>

<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="8%" height="25"> <div align="center">ID</div></td>
    <td width="35%" height="25"> <div align="center">JS名称</div></td>
    <td width="29%" height="25"> <div align="center">JS地址</div></td>
    <td width="11%"><div align="center">预览</div></td>
    <td width="17%" height="25"> <div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  $jspath=$public_r['newsurl'].str_replace("../../","",$r['jsfilename']);
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"> <div align="center"> 
        <?=$r[jsid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[jsname]?>
      </div></td>
    <td height="25"> <div align="center">
        <input name="jspath" type="text" id="jspath" value="<?=$jspath?>">
      </div></td>
    <td><div align="center">[<a href="../view/js.php?js=<?=$jspath?>&classid=1" target="_blank">预览</a>]</div></td>
    <td height="25"> <div align="center">[<a href="AddUserjs.php?enews=EditUserjs&jsid=<?=$r[jsid]?>">修改</a>]&nbsp;[<a href="AddUserjs.php?enews=AddUserjs&docopy=1&jsid=<?=$r[jsid]?>">复制</a>]&nbsp;[<a href="ListUserjs.php?enews=DelUserjs&jsid=<?=$r[jsid]?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="5">&nbsp;&nbsp;&nbsp; 
      <?=$returnpage?>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF">
    <td height="25" colspan="5">JS调用方法： 
      <input name="textfield" type="text" size="60" value="&lt;script src=&quot;JS地址&quot;&gt;&lt;/script&gt;"></td>
  </tr>
</table>
</body>
</html>
<?
db_close();
$empire=null;
?>
