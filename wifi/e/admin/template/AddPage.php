<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
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
$cid=$_GET['cid'];
$enews=$_GET['enews'];
$r[path]='../../page.html';
$url="<a href=ListPage.php>管理自定义页面</a>&nbsp;>&nbsp;增加自定义页面";
//复制
if($enews=="AddUserpage"&&$_GET['docopy'])
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select id,title,path,pagetext,classid,pagetitle,pagekeywords,pagedescription from {$dbtbpre}enewspage where id='$id'");
	$url="<a href=ListPage.php>管理自定义页面</a>&nbsp;>&nbsp;复制自定义页面：<b>".$r[title]."</b>";
}
//修改
if($enews=="EditUserpage")
{
	$id=(int)$_GET['id'];
	$r=$empire->fetch1("select id,title,path,pagetext,classid,pagetitle,pagekeywords,pagedescription from {$dbtbpre}enewspage where id='$id'");
	$url="<a href=ListPage.php>管理自定义页面</a>&nbsp;>&nbsp;修改自定义页面：<b>".$r[title]."</b>";
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewspageclass order by classid");
while($cr=$empire->fetch($csql))
{
	$select="";
	if($cr[classid]==$r[classid])
	{
		$select=" selected";
	}
	$cstr.="<option value='".$cr[classid]."'".$select.">".$cr[classname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加用户自定义页面</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function ReturnHtml(html)
{
document.form1.pagetext.value=html;
}
</script>
<SCRIPT lanuage="JScript">
<!--
function tempturnit(ss)
{
 if (ss.style.display=="") 
  ss.style.display="none";
 else
  ss.style.display=""; 
}
-->
</SCRIPT>
</head>

<body>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td height="25">位置：<?=$url?></td>
  </tr>
</table>

<form name="form1" method="post" action="../ecmscom.php">
  <table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">增加用户自定义页面 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="id" type="hidden" id="id" value="<?=$id?>"> 
        <input name="oldpath" type="hidden" id="oldpath" value="<?=$r[path]?>"> 
        <input name="cid" type="hidden" id="cid" value="<?=$cid?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">页面名称(*)</td>
      <td width="81%" height="25"> <input name="title" type="text" id="title" value="<?=$r[title]?>" size="42"> 
        <font color="#666666">(如：联系我们)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">文件名(*)</td>
      <td height="25"><input name="path" type="text" id="path" value="<?=$r[path]?>" size="42"> 
        <input type="button" name="Submit4" value="选择目录" onclick="window.open('../file/ChangePath.php?returnform=opener.document.form1.path.value','','width=400,height=500,scrollbars=yes');">
        <font color="#666666">(如：../../about.html，放于根目录)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">所属分类</td>
      <td height="25"><select name="classid" id="classid">
          <option value="0">不隶属于任何类别</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onclick="window.open('PageClass.php');"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">网页标题</td>
      <td height="25"><input name="pagetitle" type="text" id="pagetitle" value="<?=htmlspecialchars(stripSlashes($r[pagetitle]))?>" size="42"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">网页关键词</td>
      <td height="25"><input name="pagekeywords" type="text" id="pagekeywords" value="<?=htmlspecialchars(stripSlashes($r[pagekeywords]))?>" size="42"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">网页描述</td>
      <td height="25"><input name="pagedescription" type="text" id="pagedescription" value="<?=htmlspecialchars(stripSlashes($r[pagedescription]))?>" size="42"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top"><strong>页面内容</strong>(*)</td>
      <td height="25">请将页面内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.pagetext.value);document.form1.pagetext.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.pagetext.value&returnvar=opener.document.form1.pagetext.value&fun=ReturnHtml','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2" valign="top"><div align="center"> 
          <textarea name="pagetext" cols="90" rows="27" id="pagetext" wrap="OFF" style="WIDTH: 100%"><?=htmlspecialchars(stripSlashes($r[pagetext]))?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2">&nbsp;&nbsp;[<a href="#ecms" onclick="tempturnit(showtempvar);">显示模板变量说明</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('EnewsBq.php','','width=600,height=500,scrollbars=yes,resizable=yes');">查看模板标签语法</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php','','width=800,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('ListTempvar.php','','width=800,height=600,scrollbars=yes,resizable=yes');">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('ListBqtemp.php','','width=800,height=600,scrollbars=yes,resizable=yes');">查看标签模板</a>]</td>
    </tr>
    <tr bgcolor="#FFFFFF" id="showtempvar" style="display:none"> 
      <td height="25" colspan="2"><table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
          <tr bgcolor="#FFFFFF"> 
            <td> 
              <input name="textfield" type="text" value="[!--pagetitle--]">
              :网页标题</td>
            <td> 
              <input name="textfield2" type="text" value="[!--pagekeywords--]">
              :网页关键词</td>
            <td> 
              <input name="textfield3" type="text" value="[!--pagedescription--]">
              : 网页描述</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td><strong>支持公共模板变量</strong></td>
            <td><strong>支持所有模板标签</strong></td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
</body>
</html>
