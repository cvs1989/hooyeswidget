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
CheckLevel($logininid,$loginin,$classid,"template");

//增加投票模板
function AddVoteTemp($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyVoteTempname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$sql=$empire->query("insert into ".GetDoTemptb("enewsvotetemp",$gid)."(tempname,temptext) values('".$add[tempname]."','".addslashes($add[temptext])."');");
	$tempid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("AddVoteTempSuccess","AddVotetemp.php?enews=AddVoteTemp&gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改投票模板
function EditVoteTemp($add,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$add[tempid];
	if(!$tempid||!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyVoteTempname","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewsvotetemp",$gid)." set tempname='".$add[tempname]."',temptext='".addslashes($add[temptext])."' where tempid='$tempid'");
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("EditVoteTempSuccess","ListVotetemp.php?gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除投票模板
function DelVoteTemp($tempid,$userid,$username){
	global $empire,$dbtbpre;
	$tempid=(int)$tempid;
	if(empty($tempid))
	{
		printerror("NotChangeVoteTempid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$_GET['gid'];
	$r=$empire->fetch1("select tempname from ".GetDoTemptb("enewsvotetemp",$gid)." where tempid=$tempid");
	$sql=$empire->query("delete from ".GetDoTemptb("enewsvotetemp",$gid)." where tempid=$tempid");
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$r[tempname]&gid=$gid");
		printerror("DelVoteTempSuccess","ListVotetemp.php?gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//操作
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
//增加投票模板
if($enews=="AddVoteTemp")
{
	AddVoteTemp($_POST,$logininid,$loginin);
}
//修改投票模板
elseif($enews=="EditVoteTemp")
{
	EditVoteTemp($_POST,$logininid,$loginin);
}
//删除投票模板
elseif($enews=="DelVoteTemp")
{
	DelVoteTemp($_GET['tempid'],$logininid,$loginin);
}
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$url=$urlgname."<a href=ListVotetemp.php?gid=$gid>管理投票模板</a>";
$search="&gid=$gid";
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select tempid,tempname from ".GetDoTemptb("enewsvotetemp",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewsvotetemp",$gid);
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by tempid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理投票模板</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置： 
      <?=$url?>
    </td>
    <td><div align="right"> 
        <input type="button" name="Submit5" value="增加投票模板" onclick="self.location.href='AddVotetemp.php?enews=AddVoteTemp&gid=<?=$gid?>';">
      </div></td>
  </tr>
</table>
  
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="10%" height="25"><div align="center">ID</div></td>
    <td width="61%" height="25"><div align="center">模板名</div></td>
    <td width="29%" height="25"><div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25"><div align="center"> 
        <?=$r[tempid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[tempname]?>
      </div></td>
    <td height="25"><div align="center"> [<a href="AddVotetemp.php?enews=EditVoteTemp&tempid=<?=$r[tempid]?>&gid=<?=$gid?>">修改</a>] 
        [<a href="AddVotetemp.php?enews=AddVoteTemp&docopy=1&tempid=<?=$r[tempid]?>&gid=<?=$gid?>">复制</a>] 
        [<a href="ListVotetemp.php?enews=DelVoteTemp&tempid=<?=$r[tempid]?>&gid=<?=$gid?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="ffffff"> 
    <td height="25" colspan="3">&nbsp; 
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
