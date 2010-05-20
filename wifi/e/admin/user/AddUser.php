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
CheckLevel($logininid,$loginin,$classid,"user");
$enews=$_GET['enews'];
$url="<a href=ListUser.php>管理用户</a>&nbsp;>增加用户";
if($enews=="EditUser")
{
	$userid=(int)$_GET['userid'];
	$r=$empire->fetch1("select username,adminclass,groupid,checked,styleid,filelevel,truename,email from {$dbtbpre}enewsuser where userid='$userid'");
	$url="<a href=ListUser.php>管理用户</a>&nbsp;>修改用户：<b>".$r[username]."</b>";
	if($r[checked])
	{$checked=" checked";}
}
//-----------用户组
$sql=$empire->query("select groupid,groupname from {$dbtbpre}enewsgroup order by groupid desc");
while($gr=$empire->fetch($sql))
{
	if($r[groupid]==$gr[groupid])
	{$select=" selected";}
	else
	{$select="";}
	$group.="<option value=".$gr[groupid].$select.">".$gr[groupname]."</option>";
}
//-----------后台样式
$stylesql=$empire->query("select styleid,stylename,path from {$dbtbpre}enewsadminstyle order by styleid");
$style="";
while($styler=$empire->fetch($stylesql))
{
	if($r[styleid]==$styler[styleid])
	{$sselect=" selected";}
	else
	{$sselect="";}
	$style.="<option value=".$styler[styleid].$sselect.">".$styler[stylename]."</option>";
}
//--------------------操作的栏目
$fcfile="../../data/fc/ListEnews.php";
$fcjsfile="../../data/fc/cmsclass.js";
if(file_exists($fcjsfile)&&file_exists($fcfile))
{
	$class=GetFcfiletext($fcjsfile);
	$acr=explode("|",$r[adminclass]);
	$count=count($acr);
	for($i=1;$i<$count-1;$i++)
	{
		$class=str_replace("<option value='$acr[$i]'","<option value='$acr[$i]' selected",$class);
	}
}
else
{
	$class=ShowClass_AddClass($r[adminclass],"n",0,"|-",0,3);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>增加用户　</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：<?=$url?></td>
  </tr>
</table>
<form name="form1" method="post" action="ListUser.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">增加用户 
        <input name="userid" type="hidden" id="userid" value="<?=$userid?>"> <input name="oldusername" type="hidden" id="oldusername" value="<?=$r[username]?>"> 
        <input name="enews" type="hidden" id="enews" value="<?=$enews?>"> <input name="oldadminclass" type="hidden" id="oldadminclass" value="<?=$r[adminclass]?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="28%" height="25">用户名：</td>
      <td width="72%" height="25"><input name="username" type="text" id="username" value="<?=$r[username]?>" size="32">
        *</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">是否禁止：</td>
      <td height="25"><input name="checked" type="checkbox" id="checked" value="1"<?=$checked?>>
        是</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">密码：</td>
      <td height="25"><input name="password" type="password" id="password" size="32">
        * <font color="#666666">(不想修改请留空)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">重复密码：</td>
      <td height="25"><input name="repassword" type="password" id="repassword" size="32">
        * <font color="#666666">(不想修改请留空)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">姓名：</td>
      <td height="25"><input name="truename" type="text" id="truename" value="<?=$r[truename]?>" size="32"></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25">邮箱：</td>
      <td height="25"><input name="email" type="text" id="email" value="<?=$r[email]?>" size="32"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">用户组(*)：</td>
      <td height="25"><select name="groupid" id="groupid">
          <?=$group?>
        </select> <input type="button" name="Submit62223222" value="管理用户组" onclick="window.open('ListGroup.php');">
        *</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">后台样式(*)：</td>
      <td height="25"><select name="styleid" id="styleid">
          <?=$style?>
        </select> <input type="button" name="Submit6222322" value="管理后台样式" onclick="window.open('../template/AdminStyle.php');">
        *</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="2" valign="top"> <p><strong>管理栏目：</strong><br>
          <br>
          <input name="filelevel" type="checkbox" id="filelevel" value="1"<?=$r[filelevel]==1?' checked':''?>>
          应用于附件权限<br>
          <br>
          (多个，请用ctrl。)</p></td>
      <td height="25" valign="top"> <select name="adminclass[]" size="12" multiple id="adminclass[]" style="width:270;">
          <?=$class?>
        </select> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" valign="top"> 注意事项：<font color="#FF0000">选择父栏目会应用于子栏目，并且如果选择父栏目，请勿选择其子栏目</font>)</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
db_close();
$empire=null;
?>
