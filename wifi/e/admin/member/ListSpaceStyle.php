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
CheckLevel($logininid,$loginin,$classid,"spacestyle");

//返回会员组
function ReturnSpaceStyleMemberGroup($membergroup){
	$count=count($membergroup);
	if($count==0)
	{
		return '';
	}
	$mg='';
	for($i=0;$i<$count;$i++)
	{
		$mg.=$membergroup[$i].',';
	}
	if($mg)
	{
		$mg=','.$mg;
	}
	return $mg;
}

//增加会员空间模板
function AddSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	if(empty($add[stylename])||empty($add[stylepath]))
	{
		printerror('EmptySpaceStyle','history.go(-1)');
	}
	$add[stylepath]=RepPathStr($add[stylepath]);
	//目录是否存在
	if(!file_exists("../../space/template/".$add[stylepath]))
	{
		printerror("EmptySpaceStylePath","history.go(-1)");
	}
	$mg=ReturnSpaceStyleMemberGroup($add['membergroup']);
	$sql=$empire->query("insert into {$dbtbpre}enewsspacestyle(stylename,stylepic,stylesay,stylepath,isdefault,membergroup) values('$add[stylename]','$add[stylepic]','$add[stylesay]','$add[stylepath]',0,'$mg');");
	if($sql)
	{
		$styleid=$empire->lastid();
		insert_dolog("styleid=$styleid&stylename=$add[stylename]");//操作日志
		printerror("AddSpaceStyleSuccess","AddSpaceStyle.php?enews=AddSpaceStyle");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//修改会员空间模板
function EditSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=intval($add[styleid]);
	if(empty($add[stylename])||empty($add[stylepath])||!$styleid)
	{
		printerror('EmptySpaceStyle','history.go(-1)');
	}
	$add[stylepath]=RepPathStr($add[stylepath]);
	//目录是否存在
	if(!file_exists("../../space/template/".$add[stylepath]))
	{
		printerror("EmptySpaceStylePath","history.go(-1)");
	}
	$mg=ReturnSpaceStyleMemberGroup($add['membergroup']);
	$sql=$empire->query("update {$dbtbpre}enewsspacestyle set stylename='$add[stylename]',stylepic='$add[stylepic]',stylesay='$add[stylesay]',stylepath='$add[stylepath]',membergroup='$mg' where styleid='$styleid'");
	if($sql)
	{
		insert_dolog("styleid=$styleid&stylename=$add[stylename]");//操作日志
		printerror("EditSpaceStyleSuccess","ListSpaceStyle.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//删除会员空间模板
function DelSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=intval($add[styleid]);
	if(!$styleid)
	{
		printerror('EmptySpaceStyleid','history.go(-1)');
	}
	$r=$empire->fetch1("select stylename,isdefault from {$dbtbpre}enewsspacestyle where styleid='$styleid'");
	if($r[isdefault])
	{
		printerror('NotDelDefSpaceStyle','history.go(-1)');
	}
	$sql=$empire->query("delete from {$dbtbpre}enewsspacestyle where styleid='$styleid'");
	if($sql)
	{
		insert_dolog("styleid=$styleid&stylename=$r[stylename]");//操作日志
		printerror("DelSpaceStyleSuccess","ListSpaceStyle.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

//默认会员空间模板
function DefSpaceStyle($add,$userid,$username){
	global $empire,$dbtbpre;
	$styleid=intval($add[styleid]);
	if(!$styleid)
	{
		printerror('EmptyDefSpaceStyleid','history.go(-1)');
	}
	$r=$empire->fetch1("select stylename from {$dbtbpre}enewsspacestyle where styleid='$styleid'");
	$usql=$empire->query("update {$dbtbpre}enewsspacestyle set isdefault=0");
	$sql=$empire->query("update {$dbtbpre}enewsspacestyle set isdefault=1 where styleid='$styleid'");
	$upsql=$empire->query("update {$dbtbpre}enewspublic set defspacestyleid='$styleid'");
	if($sql)
	{
		GetConfig();
		insert_dolog("styleid=$styleid&stylename=$r[stylename]");//操作日志
		printerror("DefSpaceStyleSuccess","ListSpaceStyle.php");
	}
	else
	{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews=="AddSpaceStyle")
{
	AddSpaceStyle($_POST,$logininid,$loginin);
}
elseif($enews=="EditSpaceStyle")
{
	EditSpaceStyle($_POST,$logininid,$loginin);
}
elseif($enews=="DelSpaceStyle")
{
	DelSpaceStyle($_GET,$logininid,$loginin);
}
elseif($enews=="DefSpaceStyle")
{
	DefSpaceStyle($_GET,$logininid,$loginin);
}
$page=(int)$_GET['page'];
$start=0;
$line=16;//每页显示条数
$page_line=25;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewsspacestyle";
$totalquery="select count(*) as total from {$dbtbpre}enewsspacestyle";
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by styleid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>会员空间模板</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%" height="25">位置：<a href="ListSpaceStyle.php">管理会员空间模板</a></td>
    <td><div align="right">
        <input type="button" name="Submit5" value="增加会员空间模板" onclick="self.location.href='AddSpaceStyle.php?enews=AddSpaceStyle';">
      </div></td>
  </tr>
</table>

<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="10%" height="25"> <div align="center">ID</div></td>
    <td width="56%" height="25"> <div align="center">模板名称</div></td>
    <td width="34%" height="25"> <div align="center">操作</div></td>
  </tr>
  <?php
  while($r=$empire->fetch($sql))
  {
  	$color="#ffffff";
  	if($r[isdefault])
	{
		$color="#DBEAF5";
	}
  ?>
  <tr bgcolor="<?=$color?>"> 
    <td height="25"> <div align="center"> 
        <?=$r[styleid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[stylename]?>
      </div></td>
    <td height="25"> <div align="center">[<a href="ListSpaceStyle.php?enews=DefSpaceStyle&styleid=<?=$r[styleid]?>">设为默认</a>] [<a href="AddSpaceStyle.php?enews=EditSpaceStyle&styleid=<?=$r[styleid]?>">修改</a>]&nbsp;[<a href="ListSpaceStyle.php?enews=DelSpaceStyle&styleid=<?=$r[styleid]?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="3">&nbsp;&nbsp;&nbsp; 
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
