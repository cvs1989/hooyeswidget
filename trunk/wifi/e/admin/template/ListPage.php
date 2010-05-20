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
CheckLevel($logininid,$loginin,$classid,"userpage");
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select id,title,path from {$dbtbpre}enewspage";
$totalquery="select count(*) as total from {$dbtbpre}enewspage";
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
$query=$query." order by id desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewspageclass order by classid");
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
<title>管理自定义页面</title>
</head>

<body>
<table width="98%%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%" height="25">位置：<a href="ListPage.php">管理自定义页面</a></td>
    <td><div align="right">
        <input type="button" name="Submit5" value="增加自定义页面" onclick="self.location.href='AddPage.php?enews=AddUserpage&gid=<?=$gid?>';">
        &nbsp;&nbsp; 
        <input type="button" name="Submit5" value="管理自定义页面分类" onclick="self.location.href='PageClass.php?gid=<?=$gid?>';">
      </div></td>
  </tr>
</table>

<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td> 选择类别： 
      <select name="classid" id="classid" onchange=window.location='ListPage.php?classid='+this.options[this.selectedIndex].value>
        <option value="0">显示所有类别</option>
        <?=$cstr?>
      </select> </td>
  </tr>
</table>
<br>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="8%" height="25"> <div align="center">ID</div></td>
    <td width="60%" height="25"> <div align="center">页面名称</div></td>
    <td width="32%" height="25"> <div align="center">操作</div></td>
  </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  //绝对地址
  if(strstr($r['path'],".."))
  {
  $path="../".$r['path'];
  }
  else
  {
  $path=$r['path'];
  }
  ?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"> <div align="center"> 
        <?=$r[id]?>
      </div></td>
    <td height="25"> <div align="center"><a href="<?=$path?>" target=_blank><?=$r[title]?></a></div></td>
    <td height="25"> <div align="center">[<a href="AddPage.php?enews=EditUserpage&id=<?=$r[id]?>&cid=<?=$classid?>">修改</a>]&nbsp;[<a href="AddPage.php?enews=AddUserpage&docopy=1&id=<?=$r[id]?>&cid=<?=$classid?>">复制</a>]&nbsp;[<a href="../ecmscom.php?enews=DelUserpage&id=<?=$r[id]?>&cid=<?=$classid?>" onclick="return confirm('确认要删除？');">删除</a>]</div></td>
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
