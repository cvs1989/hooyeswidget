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
CheckLevel($logininid,$loginin,$classid,"delinfodata");
//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//刷新表
$retable="";
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tr=$empire->fetch($tsql))
{
	$retable.="<option value='".$tr[tbname]."'>".$tr[tname]."(".$tr[tbname].")</option>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>按条件删除信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script src="../ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td height="25">位置：<a href="DelData.php">按条件删除信息</a></td>
  </tr>
</table>
<form action="../ecmsinfo.php" method="get" name="form1" onsubmit="return confirm('确认要删除?');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25"> <div align="center">按条件删除信息</div></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"> <div align="center"> 
          <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
            <tr> 
              <td height="25">删除数据表</td>
              <td height="25"><select name="tbname" id="tbname">
                  <option value=''>------ 选择数据表 ------</option>
                  <?=$retable?>
                </select>
                *</td>
            </tr>
            <tr> 
              <td height="25">删除栏目</td>
              <td height="25"><select name="classid" id="classid">
                  <option value="0">所有栏目</option>
                  <?=$class?>
                </select> <font color="#666666">(如选择父栏目，将删除所有子栏目)</font></td>
            </tr>
            <tr> 
              <td width="23%" height="25"> <input name="retype" type="radio" value="0" checked>
                按时间删除</td>
              <td width="77%" height="25">从 
                <input name="startday" type="text" size="12" onclick="setday(this)">
                到 
                <input name="endday" type="text" size="12" onclick="setday(this)">
                之间的数据(<font color="#FF0000">不填将删除所有信息</font>)</td>
            </tr>
            <tr> 
              <td height="25"> <input name="retype" type="radio" value="1">
                按ID删除</td>
              <td height="25">从 
                <input name="startid" type="text" id="startid" value="0" size="6">
                到 
                <input name="endid" type="text" id="endid" value="0" size="6">
                之间的数据(<font color="#FF0000">如两个值为0将删除所有信息</font>)</td>
            </tr>
            <tr>
              <td height="25">信息类型</td>
              <td height="25"><input name="infost" type="radio" value="0" checked>
                所有
                <input name="infost" type="radio" value="1">
                已审核 
                <input name="infost" type="radio" value="2">
                未审核</td>
            </tr>
            <tr>
              <td height="25">删除HTML文件</td>
              <td height="25"><input name="delhtml" type="radio" value="0" checked>
                删除 
                <input type="radio" name="delhtml" value="1">
                不删除 </td>
            </tr>
            <tr> 
              <td height="25">&nbsp;</td>
              <td height="25"><input type="submit" name="Submit6" value="批量删除"> 
                <input type="reset" name="Submit7" value="重置"> <input name="enews" type="hidden" id="enews" value="DelInfoData"> 
              </td>
            </tr>
            <tr> 
              <td height="25" colspan="2">说明: <font color="#FF0000">删除后的数据不能恢复,请谨慎使用</font>.</td>
            </tr>
          </table>
        </div></td>
    </tr>
  </table>
</form>
</body>
</html>
<?php
db_close();
$empire=null;
?>
