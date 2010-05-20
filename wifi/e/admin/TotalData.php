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
//验证权限
CheckLevel($logininid,$loginin,$classid,"totaldata");
$totaltype=(int)$_POST['totaltype'];
$classid=(int)$_POST['classid'];
$ztid=(int)$_POST['ztid'];
$tbname=RepPostVar($_POST['tbname']);
$startday=RepPostVar($_POST['startday']);
$endday=RepPostVar($_POST['endday']);
$userid=(int)$_POST['userid'];
$onclick=0;
$allnum=0;
$nochecknum=0;
$checknum=0;
$bfb=0;
//按类别统计
if($totaltype==0)
{
	//未审核
	$query="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where checked=0";
	//已审核
	$query1="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where checked=1";
	//点击
	$onclickquery="select avg(onclick) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where 1=1";
	if($classid)
	{
		//中级类别
		if(empty($class_r[$classid][islast]))
		{
			$where=ReturnClass($class_r[$classid][sonclass]);
		}
		//终极类别
		else
		{
			$where="classid='$classid'";
		}
		$query.=" and (".$where.")";
		$query1.=" and (".$where.")";
		$onclickquery.=" and (".$where.")";
	}
}
//按表统计
elseif($totaltype==1)
{
	if(!$tbname)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	//未审核
	$query="select count(*) as total from {$dbtbpre}ecms_".$tbname." where checked=0";
	//已审核
	$query1="select count(*) as total from {$dbtbpre}ecms_".$tbname." where checked=1";
	//点击
	$onclickquery="select avg(onclick) as total from {$dbtbpre}ecms_".$tbname." where 1=1";
}
//按专题统计
elseif($totaltype==2)
{
	if(!$class_zr[$ztid][tbname])
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	//未审核
	$query="select count(*) as total from {$dbtbpre}ecms_".$class_zr[$ztid][tbname]." where checked=0 and ztid like '%|$ztid|%'";
	//已审核
	$query1="select count(*) as total from {$dbtbpre}ecms_".$class_zr[$ztid][tbname]." where checked=1 and ztid like '%|$ztid|%'";
	//点击
	$onclickquery="select avg(onclick) as total from {$dbtbpre}ecms_".$class_zr[$ztid][tbname]." where 1=1 and ztid like '%|$ztid|%'";
}
else
{
	printerror("ErrorUrl","history.go(-1)");
}
//时间
if($startday&&$endday)
{
	$start=$startday." 00:00:00";
	$end=$endday." 23:59:59";
	$timeadd=" and (newstime>='".to_time($start)."' and newstime<='".to_time($end)."')";
	$query.=$timeadd;
	$query1.=$timeadd;
	$onclickquery.=$timeadd;
}
//用户
if($userid)
{
	$useradd=" and userid='$userid'";
	$query.=$useradd;
	$query1.=$useradd;
	$onclickquery.=$useradd;
}
//数据表
$htb=0;
$tbsql=$empire->query("select tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$select="";
	if($tbr[tbname]==$tbname)
	{
		$htb=1;
		$select=" selected";
	}
	$tbstr.="<option value='".$tbr[tbname]."'".$select.">".$tbr[tname]."</option>";
}
if($totaltype==1&&$htb==0)
{
	printerror('ErrorUrl','');
}
if($classid||$ztid||$tbname)
{
	//审核
	$checknum=$empire->gettotal($query1);
	//未审核
	$nochecknum=$empire->gettotal($query);
	//总信息数
	$allnum=$checknum+$nochecknum;
	//点击率
	$onclick=$empire->gettotal($onclickquery);
}
//栏目
$fcfile="../data/fc/ListEnews.php";
$class="<script src=../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",$classid,0,"|-",0,0);}
//专题统计
$ztsql=$empire->query("select ztid,ztname from {$dbtbpre}enewszt order by ztid");
while($ztr=$empire->fetch($ztsql))
{
	$select="";
	if($ztr[ztid]==$ztid)
	{
		$select=" selected";
	}
	$ztstr.="<option value='".$ztr[ztid]."'".$select.">".$ztr[ztname]."</option>";
}
//用户
$usersql=$empire->query("select userid,username from {$dbtbpre}enewsuser order by userid");
while($userr=$empire->fetch($usersql))
{
	if($userr[userid]==$userid)
	{$select=" selected";}
	else
	{$select="";}
	$user.="<option value='".$userr[userid]."'".$select.">".$userr[username]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>统计数据</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script src="ecmseditor/fieldfile/setday.js"></script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td height="25">位置：<a href="TotalData.php">统计数据</a></td>
  </tr>
</table>

<form name="form1" method="post" action="TotalData.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">统计数据 
        <input name="enews" type="hidden" id="enews" value="TotalData"> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="19%" height="25"><input name="totaltype" type="radio" value="0"<?=$totaltype==0?' checked':''?>>
        按栏目统计</td>
      <td width="81%" height="25"><select name="classid" id="classid">
          <?=$class?>
        </select>
        （如选择父栏目，将统计于所有子栏目） </td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><input name="totaltype" type="radio" value="1"<?=$totaltype==1?' checked':''?>>
        按数据表统计</td>
      <td height="25"><select name="tbname" id="tbname">
	  <?=$tbstr?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF">
      <td height="25"><input name="totaltype" type="radio" value="2"<?=$totaltype==2?' checked':''?>>
        按专题统计</td>
      <td height="25"><select name="ztid" id="ztid">
	  <?=$ztstr?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">录入者：</td>
      <td height="25"><select name="userid" id="userid">
          <option value="0">所有录入者</option>
          <?=$user?>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">时间范围：</td>
      <td height="25">从 
        <input name="startday" type="text" value="<?=$startday?>" size="12" onclick="setday(this)">
        到 
        <input name="endday" type="text" value="<?=$endday?>" size="12" onclick="setday(this)">
        之间的数据(两边为空则为不限制日期)</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="开始统计"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr bgcolor="#FFFFFF"> 
    <td height="25" colspan="4"> <div align="center">统计时间： 
        <?=date("Y-m-d H:i:s")?>
      </div></td>
  </tr>
  <tr class="header"> 
    <td width="23%" height="25"><div align="center">总信息数</div></td>
    <td width="23%" height="25"> <p align="center">未审核数</p></td>
    <td width="23%" height="25"> <div align="center">已审核数</div></td>
    <td width="15%"><div align="center">平均点击数</div></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25"><div align="center"><font color=red> 
        <?=$allnum?>
        </font></div></td>
    <td height="25"><div align="center"> 
        <?=$nochecknum?>
      </div></td>
    <td height="25"><div align="center"> 
        <?=$checknum?>
      </div></td>
    <td><div align="center">
        <?=$onclick?>
      </div></td>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
