<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>系统设置</title>
<link rel="stylesheet" href="adminstyle.css" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="settingimg")
	{
		img=todisplay(dosetting,phome);
		document.images.settingimg.src=img;
	}
	else if(imgname=="tableimg")
	{
		img=todisplay(dotable,phome);
		document.images.tableimg.src=img;
	}
	else if(imgname=="taskimg")
	{
		img=todisplay(dotask,phome);
		document.images.taskimg.src=img;
	}
	else if(imgname=="bakimg")
	{
		img=todisplay(dobak,phome);
		document.images.bakimg.src=img;
	}
	else
	{
	}
}
function todisplay(ss,phome)
{
	if(ss.style.display=="") 
	{
  		ss.style.display="none";
		theimg="images/add.gif";
	}
	else
	{
  		ss.style.display="";
		theimg="images/noadd.gif";
	}
	return theimg;
}
function turnit(ss,img)
{
	DisplayImg(ss,img,0);
}
</SCRIPT>
</head>

<body topmargin="0">
<br>
<?
if($r[dopublic]||$r[dochangedata]||$r[dopostdata])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dosettingid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="settingimg"><a href="#ecms" onMouseUp=turnit(dosetting,"settingimg"); style="CURSOR: hand">基本设置</a></td>
  </tr>
  <tbody id="dosetting"<?=$display?>>
	<?
	if($r[dopublic])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../SetEnews.php" target="main">系统参数设置</a></td>
    </tr>
    <?
	}
	if($r[dochangedata])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ReHtml/ChangeData.php" target="main">数据更新中心</a></td>
    </tr>
    <?
	}
	if($r[dopostdata])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../PostUrlData.php" target="main">远程发布</a></td>
    </tr>
    <?
	}
	?>
  </tbody>
</table>
<br>
<?
}
?>
<?
if($r[dof]||$r[dom]||$r[dotable])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dotableid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="tableimg"><a href="#ecms" onMouseUp=turnit(dotable,"tableimg"); style="CURSOR: hand">数据表与系统模型</a></td>
  </tr>
  <tbody id="dotable"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../db/AddTable.php?enews=AddTable" target="main">新建数据表</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../db/ListTable.php" target="main">管理数据表</a></td>
    </tr>
  </tbody>
</table>
<br>
<?
}
?>
<?
if($r[dodo]||$r[dotask])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dotaskid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="taskimg"><a href="#ecms" onMouseUp=turnit(dotask,"taskimg"); style="CURSOR: hand">计划任务</a></td>
  </tr>
  <tbody id="dotask"<?=$display?>>
    <?
	if($r[dodo])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListDo.php" target="main">管理刷新任务</a></td>
    </tr>
	<?
	}
	if($r[dotask])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/ListTask.php" target="main">管理计划任务</a></td>
    </tr>
    <?
	}
	?>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[dodbdata]||$r[doexecsql])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dobakid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="bakimg"><a href="#ecms" onMouseUp=turnit(dobak,"bakimg"); style="CURSOR: hand">备份/恢复数据</a></td>
  </tr>
  <tbody id="dobak"<?=$display?>>
    <?
	if($r[dodbdata])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ebak/ChangeDb.php" target="main">备份数据</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ebak/ReData.php" target="main">恢复数据</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ebak/ChangePath.php" target="main">管理备份目录</a></td>
    </tr>
    <?
	}
	if($r[doexecsql])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../db/DoSql.php" target="main">执行SQL语句</a></td>
    </tr>
    <?
	}
	?>
  </tbody>
</table>
  <br>
<?
}
?>
</body>
</html>