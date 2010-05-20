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
CheckLevel($logininid,$loginin,$classid,"tempvar");
$gid=(int)$_GET['gid'];
$gname=CheckTempGroup($gid);
$urlgname=$gname."&nbsp;>&nbsp;";
$enews=$_GET['enews'];
$cid=$_GET['cid'];
$r[myorder]=0;
$url=$urlgname."<a href=ListTempvar.php?gid=$gid>管理模板变量</a>&nbsp;>&nbsp;增加模板变量";
//修改
if($enews=="EditTempvar")
{
	$varid=(int)$_GET['varid'];
	$r=$empire->fetch1("select myvar,varname,varvalue,classid,isclose,myorder from ".GetDoTemptb("enewstempvar",$gid)." where varid='$varid'");
	$r[varvalue]=htmlspecialchars(stripSlashes($r[varvalue]));
	$url=$urlgname."<a href=ListTempvar.php?gid=$gid>管理模板变量</a>&nbsp;>&nbsp;修改模板变量：".$r[myvar];
}
//分类
$cstr="";
$csql=$empire->query("select classid,classname from {$dbtbpre}enewstempvarclass order by classid");
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
<title>增加模板变量</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td height="25">位置：<?=$url?></td>
  </tr>
</table>

<form name="form1" method="post" action="ListTempvar.php">
  <table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">增加模板变量 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="varid" type="hidden" value="<?=$varid?>"> 
        <input name="cid" type="hidden" value="<?=$cid?>"> 
        <input name="gid" type="hidden" value="<?=$gid?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25">变量名(*)</td>
      <td width="81%" height="25">[!--temp. 
        <input name="myvar" type="text" value="<?=$r[myvar]?>" size="16">
        --] <font color="#666666">(如：ecms，变量就是[!--temp.ecms--])</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">所属类别</td>
      <td height="25"><select name="classid">
          <option value="0">不隶属于任何类别</option>
          <?=$cstr?>
        </select> <input type="button" name="Submit6222322" value="管理分类" onclick="window.open('TempvarClass.php');"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">变量标识(*)</td>
      <td height="25"><input name="varname" type="text" value="<?=$r[varname]?>"> 
        <font color="#666666">(如：帝国CMS)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">是否开启变量</td>
      <td height="25"><input type="radio" name="isclose" value="0"<?=$r[isclose]==0?' checked':''?>>
        是 
        <input type="radio" name="isclose" value="1"<?=$r[isclose]==1?' checked':''?>>
        否<font color="#666666">（开启才会在模板中生效）</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">变量排序</td>
      <td height="25"><input name="myorder" type="text" value="<?=$r[myorder]?>" size="6"> 
        <font color="#666666">(值越大等级越高，可以嵌入更低等级的变量)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"><strong>变量值</strong>(*)</td>
      <td height="25">请将变量内容<a href="#ecms" onclick="window.clipboardData.setData('Text',document.form1.varvalue.value);document.form1.varvalue.select()" title="点击复制模板内容"><strong>复制到Dreamweaver(推荐)</strong></a>或者使用<a href="#ecms" onclick="window.open('editor.php?getvar=opener.document.form1.varvalue.value&returnvar=opener.document.form1.varvalue.value&fun=ReturnHtml&notfullpage=1','edittemp','width=880,height=600,scrollbars=auto,resizable=yes');"><strong>模板在线编辑</strong></a>进行可视化编辑</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2"><div align="center">
          <textarea name="varvalue" cols="90" rows="27" wrap="OFF" style="WIDTH: 100%"><?=$r[varvalue]?></textarea>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2">&nbsp;[<a href="#ecms" onclick="window.open('EnewsBq.php','','width=600,height=500,scrollbars=yes,resizable=yes');">查看模板标签语法</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('../ListClass.php','','width=800,height=600,scrollbars=yes,resizable=yes');">查看JS调用地址</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('ListTempvar.php','','width=800,height=600,scrollbars=yes,resizable=yes');">查看公共模板变量</a>] 
        &nbsp;&nbsp;[<a href="#ecms" onclick="window.open('ListBqtemp.php','','width=800,height=600,scrollbars=yes,resizable=yes');">查看标签模板</a>]</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> &nbsp;<input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
</body>
</html>
