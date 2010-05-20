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
CheckLevel($logininid,$loginin,$classid,"feedback");
$id=(int)$_GET['id'];
$r=$empire->fetch1("select * from {$dbtbpre}enewsfeedback where id='$id' limit 1");
$br=$empire->fetch1("select bname,enter,filef from {$dbtbpre}enewsfeedbackclass where bid='$r[bid]'");
$username="游客";
if($r['userid'])
{
	$username="<a href='../member/AddMember.php?enews=EditMember&userid=".$r['userid']."' target=_blank>".$r['username']."</a>";
}
db_close();
$empire=null;
$record="<!--record-->";
$field="<!--field--->";
$er=explode($record,$br['enter']);
$count=count($er);
for($i=0;$i<$count-1;$i++)
{
	$er1=explode($field,$er[$i]);
	//附件
	if(strstr($br['filef'],",".$er1[1].","))
	{
		if($r[$er1[1]])
		{
			$fileurl=$public_r['fileurl']."p/".$r[$er1[1]];
			$val="<b>附件：</b><a href='".$fileurl."' target=_blank>".$r[$er1[1]]."</a>";
		}
		else
		{
			$val="";
		}
	}
	else
	{
		$val=$r[$er1[1]];
	}
	$feedbackinfo.="<tr bgcolor='#FFFFFF'><td height=25>".$er1[0].":</td><td>".nl2br($val)."</td></tr>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>查看反馈信息</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class=tableborder>
  <tr class=header> 
    <td height="25" colspan="2">所属分类：<?=$br[bname]?></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td width="19%" height="25">提交者:</td>
    <td width="81%" height="25"> 
      <?=$username?>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">发布时间:</td>
    <td height="25"> 
      <?=$r[saytime]?>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">IP地址:</td>
    <td height="25"> 
      <?=$r[ip]?>
    </td>
  </tr>
  <?=$feedbackinfo?>
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="2"><div align="center">[ <a href="javascript:window.close();">关 
        闭</a> ]</div></td>
  </tr>
</table>
</body>
</html>
