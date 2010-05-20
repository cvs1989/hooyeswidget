<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
$nr=$empire->fetch1("select id,userid,username,isqf from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' and classid='$classid'");
$r=$empire->fetch1("select id,checkuser,docheckuser,viewcheckuser,returncheck,notdocheckuser from {$dbtbpre}enewsqf where id='$id' and classid='$classid' limit 1");
if(!$nr['id'])
{
	printerror("NotDoCheckUserLevel","history.go(-1)");
}
$like=",".$loginin.",";
if($logininid<>$nr[userid]||$loginin<>$nr[username])
{
	if(!strstr($r[checkuser],$like))
	{
		printerror("NotDoCheckUserLevel","history.go(-1)");
	}
}
//全部
$checkuser=substr($r[checkuser],1,strlen($r[checkuser])-2);
//已签发
$docheckuser=substr($r[docheckuser],1,strlen($r[docheckuser])-2);
//退稿人员
$notdocheckuser=substr($r[notdocheckuser],1,strlen($r[notdocheckuser])-2);
//未签发人员
$cr=explode(",",$checkuser);
$count=count($cr);
for($i=0;$i<$count;$i++)
{
	$var=",".$cr[$i].",";
	if(!strstr($r[docheckuser],$var)&&!strstr($r[notdocheckuser],$var))
	{
		if(strstr($r[viewcheckuser],$var))
		{
			$color="red";
		}
		else
		{$color="";}
		$othercheckuser.="<font color=$color>".$cr[$i]."</font>,";
	}
}
$othercheckuser=substr($othercheckuser,0,strlen($othercheckuser)-1);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>签发文件</title>
</head>

<body>
<table width="98%%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：签发文件：<a href="ecmsinfo.php?ViewQfNews&classid=<?=$classid?>&id=<?=$r[id]?>" target=_blank><?=$r[title]?></a></td>
  </tr>
</table>
<form name="form1" method="post" action="ecmsinfo.php">
  <table width="98%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td colspan="2"><table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td><div align="center">
                <input name="enews" type="hidden" id="enews" value="DoCheckUser">
                <input name="id" type="hidden" id="id" value="<?=$id?>">
                <input name="doing" type="hidden" id="do" value="0">
                <input name="classid" type="hidden" id="classid" value="<?=$classid?>">
              </div></td>
            <td><div align="center"></div></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td width="17%" height="68" bgcolor="#FFFFFF"><strong>总签发人员：</strong></td>
      <td width="83%" height="68" bgcolor="#FFFFFF"> 
        <?=$checkuser?>
      </td>
    </tr>
    <tr> 
      <td height="68" bgcolor="#FFFFFF"><strong>已签发人员：</strong></td>
      <td height="68" bgcolor="#FFFFFF"> 
        <?=$docheckuser?>
      </td>
    </tr>
    <tr> 
      <td height="68" bgcolor="#FFFFFF"><strong>未签发人员：<br>
        （红色:看过内容） </strong></td>
      <td height="68" bgcolor="#FFFFFF"> 
        <?=$othercheckuser?>
      </td>
    </tr>
    <tr> 
      <td height="68" bgcolor="#FFFFFF"><strong>退稿人员：<br>
        </strong></td>
      <td height="68" bgcolor="#FFFFFF"> 
        <?=$notdocheckuser?>
      </td>
    </tr>
    <tr> 
      <td height="68" bgcolor="#FFFFFF">退稿评语：<br>
        (签发请留空) </td>
      <td height="68" bgcolor="#FFFFFF"><textarea name="checktext" cols="60" rows="8" id="checktext"></textarea></td>
    </tr>
    <tr> 
      <td colspan="2" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
          <tr> 
            <td><div align="center">
                <input type="submit" name="Submit" value="签发" onclick="document.form1.doing.value='0';">
              </div></td>
            <td><div align="center">
                <input type="submit" name="Submit2" value="退稿" onclick="document.form1.doing.value='1';">
              </div></td>
          </tr>
        </table></td>
    </tr>
  </table>
</form>
<table width="98%" border="0" cellspacing="1" cellpadding="3">
  <tr>
    <td><strong>退稿评语(本次评语字为红色，否则为旧评语)</strong></td>
  </tr>
  <tr>
    <td>
	<?
	$sql=$empire->query("select userid,username,checktext,checktime,isold from {$dbtbpre}enewschecktext where id='$id' and classid='$classid' order by textid desc");
	while($tr=$empire->fetch($sql))
	{
	if($tr[isold])
	{$color="";}
	else
	{$color="red";}
	?>
	  <table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td width="58%" height="25">签发者：<?=$tr[username]?>(<?=$tr[userid]?>)</td>
          <td width="42%" height="25">签发时间：<?=$tr[checktime]?></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25" colspan="2"><font color=<?=$color?>><?=nl2br(htmlspecialchars($tr[checktext]))?></font></td>
        </tr>
      </table>
	  <?
	  }
	  ?>
	  </td>
  </tr>
</table>
<br>
</body>
</html>
<?
db_close();
$empire=null;
?>
