<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../data/dbcache/class.php");
require LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"cj");
$line=50;
$page_line=22;
$classid=(int)$_GET['classid'];
$page=(int)$_GET['page'];
$start=0;
$offset=$page*$line;
//节点名称
$cr=$empire->fetch1("select classname,newsclassid,tbname,hiddenload from {$dbtbpre}enewsinfoclass where classid='$classid'");
$addwhere=" and checked=0";
//显示已导入的信息
if($cr['hiddenload'])
{
	$addwhere="";
}
$query="select * from {$dbtbpre}ecms_infotmp_".$cr[tbname]." where classid='$classid'".$addwhere;
$totalquery="select count(*) as total from {$dbtbpre}ecms_infotmp_".$cr[tbname]." where classid='$classid'".$addwhere;
$num=$empire->gettotal($totalquery);
$query.=" order by id desc limit $offset,$line";
$sql=$empire->query($query);
//栏目名称
$newsclassid=$cr[newsclassid];
$newsclassname=$class_r[$newsclassid][classname];
$newsbclassname=$class_r[$class_r[$newsclassid][bclassid]][classname];
$newsclass="<font color=red>".$newsbclassname."&nbsp;->&nbsp;".$newsclassname."</font>";
$checked=" checked";
$search="&classid=$classid";
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
if($_GET['from'])
{
	$listclasslink="ListPageInfoClass.php";
}
else
{
	$listclasslink="ListInfoClass.php";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>审核采集</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
	if(e.name=='checked'||e.name=='uptime')
		{
		continue;
	    }
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
  function LoadIn(obj)
  {
	var checkedval=0;
	var uptimeval=0;
	if(confirm("确认操作?"))
	{
		if(obj.checked.checked)
		{
			checkedval=1;
		}
		if(obj.uptime.checked)
		{
			uptimeval=1;
		}
  		self.location.href='ecmscj.php?enews=CjNewsIn_all&from=<?=$_GET['from']?>&classid='+obj.classid.value+'&checked='+checkedval+'&uptime='+uptimeval;
	}
  }
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td height="25">位置：采集 -&gt; <a href="<?=$listclasslink?>">管理节点</a> -&gt; <a href="CheckCj.php?classid=<?=$classid?>&from=<?=$_GET['from']?>">审核采集</a> 
      -&gt; 节点名称： 
      <?=$cr[classname]?>&nbsp;(共<b><font color=red><?=$num?></font></b>条未入库记录)
    </td>
  </tr>
  <tr> 
    <td height="25">入库栏目：
      <?=$newsclass?>
    </td>
  </tr>
</table>
<form name="listform" method="post" action="ecmscj.php" onsubmit="return confirm('确认操作？');">
<input type=hidden name=from value='<?=$_GET['from']?>'>
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=enews value=DelCjNews_all>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td width="5%" height="25"><div align="center">ID</div></td>
      <td width="34%" height="25"><div align="center">标题</div></td>
      <td width="14%" height="25"><div align="center">采集者</div></td>
      <td width="23%" height="25"><div align="center">采集时间</div></td>
      <td width="10%" height="25"><div align="center">采集地址</div></td>
      <td width="14%" height="25"><div align="center">操作</div></td>
    </tr>
    <?
	while($r=$empire->fetch($sql))
	{
	$r[title]=stripSlashes(sub($r[title],0,30,false));
	if($r[checked])
	{
	$tcolor="";
	}
	else
	{
	$tcolor=" bgcolor='#FFFFFF'";
	}
	?>
    <tr<?=$tcolor?> id=news<?=$r[id]?>> 
      <td height="25"><div align="center"> 
          <?=$r[id]?>
        </div></td>
      <td height="25"><div align="left"><a href="EditCjNews.php?classid=<?=$classid?>&id=<?=$r[id]?>&enews=EditCjNews&from=<?=$_GET['from']?>" title="查看"> 
          <?=$r[title]?>
          </a></div></td>
      <td height="25"><div align="center"> 
          <?=$r[username]?>
        </div></td>
      <td height="25"><div align="center"> 
          <?=$r[tmptime]?>
        </div></td>
      <td height="25"><div align="center"><a href="<?=$r[oldurl]?>" target="_blank">查看地址</a></div></td>
      <td height="25"><div align="center"><a href="EditCjNews.php?classid=<?=$classid?>&id=<?=$r[id]?>&enews=EditCjNews&from=<?=$_GET['from']?>"><img src=../data/images/EditNews.png alt='修改' title='修改信息' border=0></a>&nbsp; 
          <a href="ecmscj.php?enews=DelCjNews&classid=<?=$classid?>&id=<?=$r[id]?>&from<?=$_GET['from']?>" onclick="return confirm('确认要删除？');"><img src=../data/images/DelNews.png alt='删除' title='删除信息' border=0></a>&nbsp; 
          <input name="id[]" type="checkbox" id="id[]" onclick="if(this.checked){news<?=$r[id]?>.style.backgroundColor='#DBEAF5';}else{news<?=$r[id]?>.style.backgroundColor='#ffffff';}" value="<?=$r[id]?>"<?=$checked?>>
        </div></td>
    </tr>
    <?
	}
	db_close();
	$empire=null;
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="5"><div align="right"> <font color="#666666">(已入库的信息，为蓝色背景)</font>&nbsp;&nbsp; 
          <input name="checked" type="checkbox" id="checked" value="1"<?=$checked?>>
          直接审核
          <input name="uptime" type="checkbox" id="uptime" value="1">
          发布时间设为入库时间 
          <input type="submit" name="Submit32" value="入库选中" onclick="document.listform.enews.value='CjNewsIn';">
          &nbsp;&nbsp; 
          <input type="button" name="Submit" value="本节点的信息全部入库" onclick="return LoadIn(document.listform)">
          &nbsp;&nbsp; 
          <input type="submit" name="Submit3" value="删除" onclick="document.listform.enews.value='DelCjNews_all';">
        </div></td>
      <td height="25"> <div align="center"> 
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
          选中全部</div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="6"> 
        <?=$returnpage?>
      </td>
    </tr>
  </table>
</form>
</body>
</html>
