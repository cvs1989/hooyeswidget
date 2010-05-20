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
CheckLevel($logininid,$loginin,$classid,"tempgroup");
$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
if($enews)
{
	include("../../class/tempfun.php");
}
if($enews=="LoadInTempGroup")//导入
{
	include "../".LoadLang("pub/fun.php");
	$file=$_FILES['file']['tmp_name'];
    $file_name=$_FILES['file']['name'];
    $file_type=$_FILES['file']['type'];
    $file_size=$_FILES['file']['size'];
	LoadInTempGroup($_POST,$file,$file_name,$file_type,$file_size,$logininid,$loginin);
}
elseif($enews=="LoadTempGroup")//导出
{
	LoadTempGroup($_POST,$logininid,$loginin);
}
elseif($enews=="EditTempGroup")//修改
{
	EditTempGroup($_POST,$logininid,$loginin);
}
elseif($enews=="DefTempGroup")//默认
{
	DefTtempGroup($_POST,$logininid,$loginin);
}
elseif($enews=="DelTempGroup")//删除
{
	DelTempGroup($_POST,$logininid,$loginin);
}
else
{}
$sql=$empire->query("select gid,gname,isdefault from {$dbtbpre}enewstempgroup order by gid");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>模板组管理</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function CheckDel(gid){
	var ok=confirm("确认要删除?");
	if(ok)
	{
		self.location.href='TempGroup.php?enews=DelTempGroup&gid='+gid;
	}
}
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：<a href="TempGroup.php">模板组管理</a></td>
  </tr>
</table>
<br>
<table width="100%" border="0" align="center">
  <tr>
    <td width="48%" valign="top"> 
      <table width="93%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
	  <form name=tempgroup method=post action=TempGroup.php onsubmit="return confirm('确认要执行?');">
      <input type=hidden name=enews value=EditTempGroup>
        <tr class="header"> 
          <td width="10%" height="25"> <div align="center"></div></td>
          <td width="90%" height="25">模板组名称</td>
        </tr>
  <?
  while($r=$empire->fetch($sql))
  {
  	$tempgroup_options.="<option value='".$r['gid']."'>".$r['gname']."</option>";
  	$bgcolor="#FFFFFF";
	$checked="";
  	if($r['isdefault'])
	{
		$bgcolor="#DBEAF5";
		$checked=" checked";
	}
  ?>
          <input type=hidden name=gid[] value=<?=$r[gid]?>>
          <tr bgcolor="<?=$bgcolor?>"> 
            <td height="25"> <div align="center"> 
                <input type="radio" name="changegid" value="<?=$r[gid]?>"<?=$checked?>>
              </div></td>
            <td height="25"> <input name="gname[]" type="text" id="gname[]" value="<?=$r[gname]?>" size="30">(ID：<?=$r[gid]?>) 
            </td>
          </tr>
  <?
  }
  ?>
  		  <tr bgcolor="#FFFFFF"> 
            <td height="25">&nbsp;</td>
            <td height="25"><input type="submit" name="Submit3" value="修改" onclick="document.tempgroup.enews.value='EditTempGroup';"> 
              <input type="submit" name="Submit4" value="设为默认" onclick="document.tempgroup.enews.value='DefTempGroup';">
              <input type="submit" name="Submit6" value="导出" onclick="document.tempgroup.enews.value='LoadTempGroup';">
              <input type="submit" name="Submit7" value="删除" onclick="document.tempgroup.enews.value='DelTempGroup';"> 
              <input type="reset" name="Submit5" value="重置">
            </td>
          </tr>
  		</form>
      </table>
    </td>
    <td width="52%" valign="top"> 
      <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form action=TempGroup.php method=post enctype="multipart/form-data" name=loadform onsubmit="return confirm('确认要执行?');">
          <input type=hidden name=enews value=LoadInTempGroup>
        <tr class="header"> 
          <td height="25" colspan="2">导入模板组</td>
        </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="24%" height="25">选择文件</td>
            <td width="76%"> 
              <input type="file" name="file">
              *.temp</td>
        </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25">覆盖模板组</td>
            <td> 
              <select name="gid" id="gid">
                <option value="0">新建新的模板组</option>
				<?=$tempgroup_options?>
              </select>
            </td>
        </tr>
          <tr bgcolor="#FFFFFF"> 
            <td height="25">&nbsp;</td>
            <td height="25"> 
              <input type="submit" name="Submit" value="导入">
              <input type="reset" name="Submit2" value="重置"></td>
        </tr>
	  </form>
      </table></td>
  </tr>
</table>
</body>
</html>
<?
db_close();
$empire=null;
?>