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

//增加评论模板
function AddPlTemp($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	if(!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyPltempName","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$add[temptext]=RepPhpAspJspcode($add[temptext]);
	$gid=(int)$add['gid'];
	$sql=$empire->query("insert into ".GetDoTemptb("enewspltemp",$gid)."(tempname,temptext,isdefault) values('".$add[tempname]."','".addslashes($add[temptext])."',0);");
	$tempid=$empire->lastid();
	//更新页面
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		GetPlTempPage($tempid);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("AddPltempSuccess","AddPltemp.php?enews=AddPlTemp&gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改评论模板
function EditPlTemp($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$tempid=(int)$add[tempid];
	if(!$tempid||!$add[tempname]||!$add[temptext])
	{
		printerror("EmptyPltempName","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$add[temptext]=RepPhpAspJspcode($add[temptext]);
	$gid=(int)$add['gid'];
	$sql=$empire->query("update ".GetDoTemptb("enewspltemp",$gid)." set tempname='".$add[tempname]."',temptext='".addslashes($add[temptext])."' where tempid='$tempid'");
	//更新页面
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		GetPlTempPage($tempid);
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$add[tempname]&gid=$gid");
		printerror("EditPltempSuccess","ListPltemp.php?gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除评论模板
function DelPlTemp($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$tempid=(int)$add[tempid];
	if(empty($tempid))
	{
		printerror("NotChangePlTempid","history.go(-1)");
	}
	//验证权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$r=$empire->fetch1("select tempname,isdefault from ".GetDoTemptb("enewspltemp",$gid)." where tempid=$tempid");
	if($r['isdefault'])
	{
		printerror("NotDelDefPlTempid","history.go(-1)");
	}
	$sql=$empire->query("delete from ".GetDoTemptb("enewspltemp",$gid)." where tempid=$tempid");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		DelFiletext(ECMS_PATH.'e/data/filecache/template/pl'.$tempid.'.php');
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=$tempid&tempname=$r[tempname]&gid=$gid");
		printerror("DelPltempSuccess","ListPltemp.php?gid=$gid");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//设为默认评论模板
function DefPlTemp($add,$userid,$username){
	global $empire,$dbtbpre,$public_r;
	$tempid=(int)$add[tempid];
	if(!$tempid)
	{
		printerror("NotChangePlTempid","history.go(-1)");
	}
	//操作权限
	CheckLevel($userid,$username,$classid,"template");
	$gid=(int)$add['gid'];
	$tr=$empire->fetch1("select tempname from ".GetDoTemptb("enewspltemp",$gid)." where tempid='$tempid'");
	$usql=$empire->query("update ".GetDoTemptb("enewspltemp",$gid)." set isdefault=0");
	$sql=$empire->query("update ".GetDoTemptb("enewspltemp",$gid)." set isdefault=1 where tempid='$tempid'");
	if($gid==$public_r['deftempid']||(!$public_r['deftempid']&&($gid==1||$gid==0)))
	{
		$empire->query("update {$dbtbpre}enewspublic set defpltempid='$tempid' limit 1");
		GetConfig();//更新缓存
	}
	if($sql)
	{
		//操作日志
		insert_dolog("tempid=".$tempid."<br>tempname=".$tr[tempname]."&gid=$gid");
		printerror("DefPltempSuccess","ListPltemp.php?gid=$gid");
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
if($enews=="AddPlTemp")//增加评论模板
{
	AddPlTemp($_POST,$logininid,$loginin);
}
elseif($enews=="EditPlTemp")//修改评论模板
{
	EditPlTemp($_POST,$logininid,$loginin);
}
elseif($enews=="DelPlTemp")//删除评论模板
{
	DelPlTemp($_GET,$logininid,$loginin);
}
elseif($enews=="DefPlTemp")//默认评论模板
{
	DefPlTemp($_GET,$logininid,$loginin);
}

$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$url=$urlgname."<a href=ListPltemp.php?gid=$gid>管理评论模板</a>";
$search="&gid=$gid";
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select tempid,tempname,isdefault from ".GetDoTemptb("enewspltemp",$gid);
$totalquery="select count(*) as total from ".GetDoTemptb("enewspltemp",$gid);
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by tempid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理评论模板</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%">位置： 
      <?=$url?>
    </td>
    <td><div align="right"> 
        <input type="button" name="Submit5" value="增加评论模板" onclick="self.location.href='AddPltemp.php?enews=AddPlTemp&gid=<?=$gid?>';">
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
  	$color="#ffffff";
  	if($r[isdefault])
  	{
  		$color="#DBEAF5";
  	}
  ?>
  <tr bgcolor="<?=$color?>"> 
    <td height="25"><div align="center"> 
        <?=$r[tempid]?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$r[tempname]?>
      </div></td>
    <td height="25"><div align="center"> [<a href="AddPltemp.php?enews=EditPlTemp&tempid=<?=$r[tempid]?>&gid=<?=$gid?>">修改</a>] 
        [<a href="AddPltemp.php?enews=AddPlTemp&docopy=1&tempid=<?=$r[tempid]?>&gid=<?=$gid?>">复制</a>] 
        [<a href="ListPltemp.php?enews=DefPlTemp&tempid=<?=$r[tempid]?>&gid=<?=$gid?>" onclick="return confirm('确认要默认？');">设为默认</a>] 
        [<a href="ListPltemp.php?enews=DelPlTemp&tempid=<?=$r[tempid]?>&gid=<?=$gid?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
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
