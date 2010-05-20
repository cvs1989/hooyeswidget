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
<title>栏目管理</title>
<link rel="stylesheet" href="adminstyle.css" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="classimg")
	{
		img=todisplay(doclass,phome);
		document.images.classimg.src=img;
	}
	else if(imgname=="cinfoimg")
	{
		img=todisplay(docinfo,phome);
		document.images.cinfoimg.src=img;
	}
	else if(imgname=="ztimg")
	{
		img=todisplay(dozt,phome);
		document.images.ztimg.src=img;
	}
	else if(imgname=="fileimg")
	{
		img=todisplay(dofile,phome);
		document.images.fileimg.src=img;
	}
	else if(imgname=="searchallimg")
	{
		img=todisplay(dosearchall,phome);
		document.images.searchallimg.src=img;
	}
	else if(imgname=="cjimg")
	{
		img=todisplay(docj,phome);
		document.images.cjimg.src=img;
	}
	else if(imgname=="wapimg")
	{
		img=todisplay(dowap,phome);
		document.images.wapimg.src=img;
	}
	else if(imgname=="cotherimg")
	{
		img=todisplay(docother,phome);
		document.images.cotherimg.src=img;
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
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="docinfoid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="cinfoimg"><a href="#ecms" onMouseUp=turnit(docinfo,"cinfoimg"); style="CURSOR: hand">信息相关管理</a></td>
  </tr>
  <tbody id="docinfo"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListAllInfo.php" target="main">管理信息</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListAllInfo.php?showspecial=4&sear=1" target="main">审核信息</a></td>
    </tr>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListNewsQf.php" target="main">签发信息</a></td>
    </tr>
	<?
	if($r[dopl])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../pl/ListAllPl.php" target="main">管理评论</a></td>
    </tr>
	<?
	}
	?>
  </tbody>
</table>
<br>
<?
if($r[doclass]||$r[dosetmclass])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doclassid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="classimg"><a href="#ecms" onMouseUp=turnit(doclass,"classimg"); style="CURSOR: hand">栏目管理</a></td>
  </tr>
  <tbody id="doclass"<?=$display?>>
	<?
	if($r[doclass])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListClass.php" target="main">管理栏目</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListPageClass.php" target="main">管理栏目(分页)</a></td>
    </tr>
	<?
	}
	if($r[dosetmclass])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../SetMoreClass.php" target="main">批量设置栏目属性</a></td>
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
if($r[dozt])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doztid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ztimg"><a href="#ecms" onMouseUp=turnit(dozt,"ztimg"); style="CURSOR: hand">专题管理</a></td>
  </tr>
  <tbody id="dozt"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListZtClass.php" target="main">管理专题分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListZt.php" target="main">管理专题</a></td>
    </tr>
  </tbody>
</table>
<br>
<?
}
?>
<?
if($r[dofile])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dofileid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="fileimg"><a href="#ecms" onMouseUp=turnit(dofile,"fileimg"); style="CURSOR: hand">附件管理</a></td>
  </tr>
  <tbody id="dofile"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../file/TranMoreFile.php" target="main">上传多附件</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../file/ListFile.php?type=9" target="main">数据库式管理附件</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../file/FilePath.php" target="main">目录式管理附件</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[docj])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="docjid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="cjimg"><a href="#ecms" onMouseUp=turnit(docj,"cjimg"); style="CURSOR: hand">采集管理</a></td>
  </tr>
  <tbody id="docj"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../AddInfoC.php" target="main">增加采集节点</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListInfoClass.php" target="main">管理采集节点</a></td>
    </tr>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ListPageInfoClass.php" target="main">管理采集节点(分页)</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[dosearchall])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dosearchallid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="searchallimg"><a href="#ecms" onMouseUp=turnit(dosearchall,"searchallimg"); style="CURSOR: hand">全站全文搜索</a></td>
  </tr>
  <tbody id="dosearchall"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../searchall/SetSearchAll.php" target="main">全站搜索设置</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../searchall/ListSearchLoadTb.php" target="main">管理搜索数据源</a></td>
    </tr>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../searchall/ClearSearchAll.php" target="main">清理搜索数据</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[dowap])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dowapid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="wapimg"><a href="#ecms" onMouseUp=turnit(dowap,"wapimg"); style="CURSOR: hand">WAP管理</a></td>
  </tr>
  <tbody id="dowap"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/SetWap.php" target="main">WAP设置</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/WapStyle.php" target="main">管理WAP模板</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[domovenews]||$r[doinfodoc]||$r[dodelinfodata]||$r[dorepnewstext]||$r[dototaldata]||$r[dosearchkey]||$r[dovotemod])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="docotherid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="cotherimg"><a href="#ecms" onMouseUp=turnit(docother,"cotherimg"); style="CURSOR: hand">其他管理</a></td>
  </tr>
  <tbody id="docother"<?=$display?>>
	<?
	if($r[dototaldata])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../TotalData.php" target="main">统计信息数据</a></td>
    </tr>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../user/UserTotal.php" target="main">用户发布统计</a></td>
    </tr>
	<?
	}
	if($r[dosearchkey])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../SearchKey.php" target="main">管理搜索关键字</a></td>
    </tr>
	<?
	}
	if($r[dorepnewstext])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../db/RepNewstext.php" target="main">批量替换字段值</a></td>
    </tr>
	<?
	}
	if($r[domovenews])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../MoveClassNews.php" target="main">批量转移信息</a></td>
    </tr>
	<?
	}
	if($r[doinfodoc])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../InfoDoc.php" target="main">信息批量归档</a></td>
    </tr>
	<?
	}
	if($r[dodelinfodata])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../db/DelData.php" target="main">批量删除信息</a></td>
    </tr>
	<?
	}
	if($r[dovotemod])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/ListVoteMod.php" target="main">管理预设投票</a></td>
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