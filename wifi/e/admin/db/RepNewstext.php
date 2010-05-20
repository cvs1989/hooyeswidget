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
CheckLevel($logininid,$loginin,$classid,"repnewstext");
$url="<a href=RepNewstext.php>批量替换信息内容</a>";
//栏目
$fcfile="../../data/fc/ListEnews.php";
$class="<script src=../../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$class=ShowClass_AddClass("",0,0,"|-",0,0);}
//数据表
$tbname=RepPostVar($_GET['tbname']);
$first=1;
$htb=0;
$tsql=$empire->query("select tid,tbname,tname from {$dbtbpre}enewstable order by tid");
while($tr=$empire->fetch($tsql))
{
	if($first==1)
	{
		$firsttable=$tr[tbname];
		$firsttid=$tr[tid];
		$first=0;
	}
	else
	{$first=0;}
	if($tbname==$tr[tbname])
	{
		$htb=1;
		$select=" selected";
		$thistid=$tr[tid];
	}
	else
	{
		$select="";
	}
	$table.="<option value='".$tr[tbname]."'".$select.">".$tr[tname]."</option>";
}
if(!$table)
{printerror("NotRepNewstextTb","history.go(-1)");}
$table="<select name='tbname' onchange=self.location='RepNewstext.php?tbname='+this.options[this.selectedIndex].value>".$table."</select>";
//字段
if(empty($tbname))
{
	$showtable=$firsttable;
	$showtid=$firsttid;
}
else
{
	if($htb==0)
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	$showtable=$tbname;
	$showtid=$thistid;
}
$field='';
$s=$empire->query("SHOW FIELDS FROM {$dbtbpre}ecms_".$showtable);
$noshowfield=",id,onclick,newspath,keyboard,keyid,userid,username,ztid,istop,truetime,ismember,dokey,isgood,titlecolor,titlefont,titleurl,filename,fh,plnum,firsttitle,opentime,starttime,endtime,checkuser,docheckuser,viewcheckuser,returncheck,notdocheckuser,totaldown,filenameqz,";
while($r=$empire->fetch($s))
{
	//不显示字段
	if(strstr($noshowfield,",".$r[Field].","))
	{
		//continue;
	}
	$field.="<option value='".$r[Field]."'>".$r[Field]."</option>";
}
$datafsql=$empire->query("SHOW FIELDS FROM {$dbtbpre}ecms_".$showtable."_data_1");
while($dfr=$empire->fetch($datafsql))
{
	if($dfr[Field]=='classid'||$dfr[Field]=='id')
	{
		continue;
	}
	$field.="<option value='".$dfr[Field]."'>".$dfr[Field]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>批量替换信息内容</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td height="25">位置：<?=$url?></td>
  </tr>
</table>

<form action="../ecmscom.php" method="post" name="form1" target="_blank" onsubmit="return confirm('确认要替换？');">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">批量替换信息内容 
        <input name="enews" type="hidden" id="enews" value="DoRepNewstext"> <input name="tid" type="hidden" id="tid" value="<?=$showtid?>"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">选择替换表(*)：</td>
      <td height="25"> 
        <?=$table?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td width="15%" height="25">操作栏目：</td>
      <td width="85%" height="25"><select name="classid" id="classid">
          <option value=0>所有栏目</option>
          <?=$class?>
        </select> <font color="#666666">(如选择父栏目，将应用于所有子栏目)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">替换字段(*)：</td>
      <td height="25"><select name="field" size="12" id="field" style="width:180">
          <?=$field?>
        </select> </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="26">替换内容：</td>
      <td height="26"> <table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
          <tr> 
            <td> <div align="center">原字符</div></td>
            <td bgcolor="#FFFFFF"> <div align="left"> 
                <textarea name="oldword" cols="100" rows="5" id="textarea"></textarea>
                (*)</div></td>
          </tr>
          <tr> 
            <td> <div align="center">新字符 </div></td>
            <td bgcolor="#FFFFFF"> <div align="left"> 
                <textarea name="newword" cols="100" rows="5" id="newword2"></textarea>
              </div></td>
          </tr>
        </table></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td rowspan="2">&nbsp;</td>
      <td height="26">完全替换 
        <input name="over" type="checkbox" id="over" value="1"> <font color="#666666">(完全替换:将针对“字段值=旧字符”的情况下才替换.而不是模糊替换.)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="26">正则替换 
        <input name="dozz" type="checkbox" id="dozz" value="1"> <font color="#666666"> 
        (正则替换:旧字符可以是用正则表示，用&quot;*&quot;表示任意字符)</font></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">&nbsp;</td>
      <td height="25"><input type="submit" name="Submit" value="提交"> <input type="reset" name="Submit2" value="重置"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="2">备注：替换时最好备份一下数据．</td>
    </tr>
  </table>
</form>
</body>
</html>
