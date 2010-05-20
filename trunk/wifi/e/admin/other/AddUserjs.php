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
CheckLevel($logininid,$loginin,$classid,"userjs");
$enews=$_GET['enews'];
$url="<a href=ListUserjs.php>管理用户自定义JS</a> &gt; 增加自定义JS";
$r[jsfilename]="../../d/js/js/".time().".js";
$r[substr]=50;
$r[jssql]="select * from [!db.pre!]ecms_news where checked=1 order by id limit 10";
//复制
if($enews=="AddUserjs"&&$_GET['docopy'])
{
	$jsid=(int)$_GET['jsid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuserjs where jsid='$jsid'");
	$url="<a href=ListUserjs.php>管理用户自定义JS</a> &gt; 复制自定义JS：<b>".$r[jsname]."</b>";
}
//修改
if($enews=="EditUserjs")
{
	$jsid=(int)$_GET['jsid'];
	$r=$empire->fetch1("select * from {$dbtbpre}enewsuserjs where jsid='$jsid'");
	$url="<a href=ListUserjs.php>管理用户自定义JS</a> -&gt; 修改自定义JS：<b>".$r[jsname]."</b>";
}
//js模板
$jstempsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsjstemp")." order by tempid");
while($jstempr=$empire->fetch($jstempsql))
{
	$select="";
	if($r[jstempid]==$jstempr[tempid])
	{
		$select=" selected";
	}
	$jstemp.="<option value='".$jstempr[tempid]."'".$select.">".$jstempr[tempname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>用户自定义JS</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：<?=$url?></td>
  </tr>
</table>
<form name="form1" method="post" action="ListUserjs.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">增加用户自定义JS 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="jsid" type="hidden" id="jsid" value="<?=$jsid?>"> 
        <input name="oldjsfilename" type="hidden" id="oldjsfilename" value="<?=$r[jsfilename]?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="18%" height="25">JS名称:</td>
      <td width="82%" height="25"> <input name="jsname" type="text" id="jsname" value="<?=$r[jsname]?>" size="42"> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">JS存放地址：</td>
      <td height="25"><input name="jsfilename" type="text" id="jsfilename" value="<?=$r[jsfilename]?>" size="42">
        <font color="#666666">
        <input type="button" name="Submit4" value="选择目录" onclick="window.open('../file/ChangePath.php?returnform=opener.document.form1.jsfilename.value','','width=400,height=500,scrollbars=yes');">
        (如:<strong>&quot;../../1.js</strong>&quot;表示根目录下的1.js)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="2">查询SQL语句:</td>
      <td height="25"><input name="jssql" type="text" id="jssql" value="<?=htmlspecialchars(stripSlashes($r[jssql]))?>" size="72"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><font color="#666666">(如：select * from phome_ecms_news where 
        classid=1 and checked=1 order by id limit 10)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">使用JS模板：</td>
      <td height="25"><select name="jstempid" id="jstempid">
          <?=$jstemp?>
        </select> <input type="button" name="Submit62223" value="管理JS模板" onclick="window.open('../template/ListJstemp.php');"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">标题截取字数：</td>
      <td height="25"><input name="substr" type="text" id="substr" value="<?=$r[substr]?>" size="6">
        字</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"> <input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">&nbsp;</td>
      <td height="25">表前缀可用“<strong>[!db.pre!]</strong>”表示</td>
    </tr>
  </table>
</form>
</body>
</html>
