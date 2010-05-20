<?php
require("../class/connect.php");
if(!defined('InEmpireCMS'))
{
	exit();
}
require("../class/db_sql.php");
require("../class/q_functions.php");
require("../data/dbcache/class.php");
require LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
if(empty($id)||empty($classid))
{
	printerror("ErrorUrl","history.go(-1)",1);
}
$mid=$class_r[$classid]['modid'];
$tbname=$class_r[$classid][tbname];
if(empty($tbname))
{
	printerror("ErrorUrl","history.go(-1)",1);
}
$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$tbname." where id='$id' limit 1");
if(empty($r[id])||$r[classid]!=$classid)
{
	printerror("ErrorUrl","history.go(-1)",1);
}
//副表
$tbdataf=$emod_r[$mid]['tbdataf'];
if($tbdataf&&$tbdataf<>',')
{
	$selectdataf=substr($tbdataf,1,strlen($tbdataf)-2);
	$finfor=$empire->fetch1("select ".$selectdataf." from {$dbtbpre}ecms_".$tbname."_data_".$r[stb]." where id='$r[id]'");
	$r=array_merge($r,$finfor);
}
//权限
if($r[groupid])
{
	include('../data/dbcache/MemberLevel.php');
	define('empirecms','wm_chief');
	define('PageCheckLevel','wm_chief');
	$check_tbname=$tbname;
	$check_infoid=$id;
	$check_classid=$classid;
	$check_path="../../";
	$checkinfor=$r;
	@include("../class/CheckLevel.php");
}
$r[newstime]=date("Y-m-d H:i:s",$r[newstime]);
//存文本
$savetxtf=$emod_r[$mid]['savetxtf'];
if($savetxtf&&$r[$savetxtf])
{
	$r[$savetxtf]=GetTxtFieldText($r[$savetxtf]);
}
$r[newstext]=stripSlashes($r[newstext]);
//分页字段
$pagef=$emod_r[$mid]['pagef'];
if($pagef&&$r[$pagef])
{
	$r[$pagef]=str_replace('[!--empirenews.page--]','',$r[$pagef]);
	$r[$pagef]=str_replace('[/!--empirenews.page--]','',$r[$pagef]);
}
$url=ReturnClassLink($r[classid])."&nbsp;>&nbsp;".$fun_r['zw'];
//标题链接
$titlelink=sys_ReturnBqTitleLink($r);
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?=$r[title]?> 打印页面 - Powered by EmpireCMS</title>
<meta name="keywords" content="<?=$r[title]?> 打印页面" />
<meta name="description" content="<?=$r[title]?> 打印页面" />
<style>
body{font-family:宋体}td,.f12{font-size:12px}.f24 {font-size:24px;}.f14 {font-size:14px;}.title14 {font-size:14px;line-height:130%}.l17 {line-height:170%;}
</style>
</head>
<body bgcolor="#ffffff" topmargin=5 leftmargin=5 marginheight=5 marginwidth=5 onLoad='window.print()'>
<center>
<table width=650 border=0 cellspacing=0 cellpadding=0>
<tr>
<td height=65 width=180><A href="http://www.phome.net/"><IMG src="../../skin/default/images/elogo.jpg" alt="帝国软件" width="180" height="65" border=0></A></td> 
<td valign="bottom"><?=$url?></td>
<td width="83" align="right" valign="bottom"><a href='javascript:history.back()'>返回</a>　<a href='javascript:window.print()'>打印</a></td>
</tr>
</table>
<table width=650 border=0 cellpadding=0 cellspacing=20 bgcolor="#EDF0F5">
<tr>
<td>
<BR>
<TABLE cellSpacing=0 cellPadding=0 width="100%" border=0>
<TBODY>
<TR>
<TH class="f24"><FONT color=#05006c><?=$r[title]?></FONT></TH></TR>
<TR>
<TD>
<HR SIZE=1 bgcolor="#d9d9d9">
</TD>
</TR>
<TR>
<TD align="middle" height=20><div align="center"><?=$r[writer]?>&nbsp;&nbsp;<?=$r[newstime]?>&nbsp;&nbsp;<?=$r[befrom]?></div></TD>
</TR>
<TR>
<TD height=15></TD>
</TR>
<TR>
<TD class="l17">
<FONT class="f14" id="zoom"> 
<P><?=$r[newstext]?><br>
<BR clear=all>
</P>
</FONT>
</TD>
</TR>
<TR height=10>
<TD></TD>
</TR>
</TBODY>
</TABLE>
<?=$titlelink?>
</td>
</tr>
</table>
</center>
</body>
</html>
<?php
db_close();
$empire=null;
?>
