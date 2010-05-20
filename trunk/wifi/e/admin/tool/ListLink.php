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
CheckLevel($logininid,$loginin,$classid,"link");

//------------------增加友情链接
function AddLink($add,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[lname]||!$add[lurl])
	{printerror("EmptyLname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"link");
	$ltime=date("Y-m-d H:i:s");
	$add[onclick]=(int)$add[onclick];
	$add[myorder]=(int)$add[myorder];
	$add[ltype]=(int)$add[ltype];
	$add[checked]=(int)$add[checked];
	$add[classid]=(int)$add[classid];
	$add[cid]=(int)$add[cid];
	$sql=$empire->query("insert into {$dbtbpre}enewslink(lname,lpic,lurl,ltime,onclick,width,height,target,myorder,email,lsay,ltype,checked,classid) values('".addslashes($add[lname])."','".addslashes($add[lpic])."','".addslashes($add[lurl])."','$ltime',$add[onclick],'$add[width]','$add[height]','$add[target]',$add[myorder],'".addslashes($add[email])."','".addslashes($add[lsay])."',$add[ltype],$add[checked],$add[classid]);");
	$lastid=$empire->lastid();
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lastid."<br>lname=".$add[lname]);
		printerror("AddLinkSuccess","AddLink.php?enews=AddLink&cid=$add[cid]");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//----------------修改友情链接
function EditLink($add,$userid,$username){
	global $empire,$dbtbpre;
	$add[lid]=(int)$add[lid];
	if(!$add[lname]||!$add[lurl]||!$add[lid])
	{printerror("EmptyLname","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"link");
	$add[onclick]=(int)$add[onclick];
	$add[myorder]=(int)$add[myorder];
	$add[ltype]=(int)$add[ltype];
	$add[checked]=(int)$add[checked];
	$add[classid]=(int)$add[classid];
	$add[cid]=(int)$add[cid];
	$sql=$empire->query("update {$dbtbpre}enewslink set lname='".addslashes($add[lname])."',lpic='".addslashes($add[lpic])."',lurl='".addslashes($add[lurl])."',onclick=$add[onclick],width='$add[width]',height='$add[height]',target='$add[target]',myorder=$add[myorder],email='".addslashes($add[email])."',lsay='".addslashes($add[lsay])."',ltype=$add[ltype],checked=$add[checked],classid=$add[classid] where lid='$add[lid]'");
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$add[lid]."<br>lname=".$add[lname]);
		printerror("EditLinkSuccess","ListLink.php?cid=$add[cid]");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//---------------删除友情链接
function DelLink($lid,$cid,$userid,$username){
	global $empire,$dbtbpre;
	$lid=(int)$lid;
	$add[cid]=(int)$add[cid];
	if(!$lid)
	{printerror("EmptyLid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"link");
	$r=$empire->fetch1("select lname from {$dbtbpre}enewslink where lid='$lid'");
	$sql=$empire->query("delete from {$dbtbpre}enewslink where lid='$lid'");
	if($sql)
	{
		//操作日志
		insert_dolog("lid=".$lid."<br>lname=".$r[lname]);
		printerror("DelLinkSuccess","ListLink.php?cid=$add[cid]");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews=="AddLink")
{
	AddLink($_POST,$logininid,$loginin);
}
elseif($enews=="EditLink")
{
	EditLink($_POST,$logininid,$loginin);
}
elseif($enews=="DelLink")
{
	$lid=$_GET['lid'];
	$cid=$_GET['cid'];
	DelLink($lid,$cid,$logininid,$loginin);
}
else
{}
$page=(int)$_GET['page'];
$start=0;
$line=16;//每页显示条数
$page_line=25;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewslink";
$totalquery="select count(*) as total from {$dbtbpre}enewslink";
//类别
$add="";
$classid=(int)$_GET['classid'];
if($classid)
{
	$add=" where classid=$classid";
	$search="&classid=$classid";
}
$query.=$add;
$totalquery.=$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query=$query." order by myorder,lid limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//类别
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewslinkclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$classid)
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理友情链接</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%" height="25">位置：<a href="ListLink.php">管理友情链接</a></td>
    <td><div align="right">
        <input type="button" name="Submit5" value="增加友情链接" onclick="self.location.href='AddLink.php?enews=AddLink';">
      </div></td>
  </tr>
</table>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td> 选择类别： 
      <select name="classid" id="classid" onchange=window.location='ListLink.php?classid='+this.options[this.selectedIndex].value>
        <option value="0">显示所有类别</option>
        <?=$cstr?>
      </select> </td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="6%" height="25"> <div align="center">ID</div></td>
    <td width="51%" height="25"> <div align="center">预览</div></td>
    <td width="11%" height="25"> <div align="center">点击</div></td>
    <td width="12%"><div align="center">状态</div></td>
    <td width="20%" height="25"> <div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  //文字
  if(empty($r[lpic]))
  {
  $logo="<a href='".$r[lurl]."' title='".$r[lname]."' target=".$r[target].">".$r[lname]."</a>";
  }
  //图片
  else
  {
  $logo="<a href='".$r[lurl]."' target=".$r[target]."><img src='".$r[lpic]."' alt='".$r[lname]."' border=0 width='".$r[width]."' height='".$r[height]."'></a>";
  }
  if(empty($r[checked]))
  {$checked="关闭";}
  else
  {$checked="显示";}
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"> <div align="center"> 
        <?=$r[lid]?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$logo?>
      </div></td>
    <td height="25"> <div align="center"> 
        <?=$r[onclick]?>
      </div></td>
    <td><div align="center"><?=$checked?></div></td>
    <td height="25"> <div align="center">[<a href="AddLink.php?enews=EditLink&lid=<?=$r[lid]?>&cid=<?=$classid?>">修改</a>]&nbsp;[<a href="ListLink.php?enews=DelLink&lid=<?=$r[lid]?>&cid=<?=$classid?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
  </tr>
  <?
  }
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="5">&nbsp;&nbsp;&nbsp; 
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
