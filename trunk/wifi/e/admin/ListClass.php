<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
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
CheckLevel($logininid,$loginin,$classid,"class");

//显示无限级栏目[管理栏目时]
function ShowClass_ListClass($bclassid,$exp){
	global $empire,$fun_r,$dbtbpre;
	//缩
	if(getcvar('displayclass',1))
	{
		$display=" style='display=none'";
    }
	if(empty($bclassid))
	{
		$bclassid=0;
		$exp="";
    }
	else
	{
		$exp="&nbsp;&nbsp;&nbsp;".$exp;
	}
	$sql=$empire->query("select * from {$dbtbpre}enewsclass where bclassid='$bclassid' order by myorder,classid");
	$returnstr="";
	while($r=$empire->fetch($sql))
	{
		$classurl=sys_ReturnBqClassUrl($r);
		$divonclick="";
		$start_tbody="";
		$end_tbody="";
		$docinfo="";
		//终级栏目
		if($r[islast])
		{
			$img="<a href='AddNews.php?enews=AddNews&classid=".$r[classid]."' target=_blank><img src='../data/images/txt.gif' border=0></a>";
			$bgcolor="#ffffff";
			$renewshtml="&nbsp;[<a href='ReHtml/DoRehtml.php?enews=ReNewsHtml&from=ListClass.php&classid=".$r[classid]."&tbname[]=".$r[tbname]."'>".$fun_r['news']."</a>]&nbsp;";
			$docinfo="&nbsp;[<a href='ecmsinfo.php?enews=InfoToDoc&ecmsdoc=1&docfrom=ListClass.php&classid=".$r[classid]."' onclick=\"return confirm('确认归档?');\">归档</a>]";
		}
		else
		{
			$img="<img src='../data/images/dir.gif'>";
			if(empty($r[bclassid]))
			{
				$bgcolor="#DBEAF5";
				$divonclick=" language=JScript onMouseUp='turnit(classdiv".$r[classid].");' style='CURSOR: hand' title='open'";
				$start_tbody="<tbody id='classdiv".$r[classid]."'".$display.">";
				$end_tbody="</tbody>";
		    }
			else
			{$bgcolor="#ffffff";}
			$renewshtml="&nbsp;[<a href='ReHtml/DoRehtml.php?enews=ReNewsHtml&from=ListClass.php&classid=".$r[classid]."&tbname[]=".$r[tbname]."'>".$fun_r['news']."</a>]&nbsp;";
		}
		//外部栏目
		$classname=$r[classname];
		if($r[wburl])
		{
			$classname="<font color='#666666'>".$classname."&nbsp;(外部)</font>";
		}
		$returnstr.="<tr bgcolor='".$bgcolor."'><td><input type=text name=myorder[] value=".$r[myorder]." size=2><input type=hidden name=classid[] value=".$r[classid]."></td><td".$divonclick.">".$exp.$img."</td><td height=25> <div align=center>".$r[classid]."</div></td><td height=25><div align=left><input type=checkbox name=reclassid[] value='".$r[classid]."'>&nbsp;<a href='".$classurl."' target=_blank>".$classname."</a></div></td><td height=25><div align=center>".$r[onclick]."</div></td><td height=25><div align=center><a href='#ecms' onclick=javascript:window.open('view/ClassUrl.php?classid=".$r[classid]."','','width=500,height=200');>".$fun_r['viewurl']."</a></div></td><td height=25><div align=center>[<a href='enews.php?enews=ReListHtml&from=ListClass.php&classid=".$r[classid]."'>".$fun_r['re']."</a>]".$renewshtml."[<a href='ecmschtml.php?enews=ReSingleJs&doing=0&classid=".$r[classid]."'>JS</a>]&nbsp;[<a href='AddClass.php?classid=".$r[classid]."&enews=AddClass&docopy=1'>".$fun_r['copyclass']."</a>]&nbsp;[<a href='AddClass.php?classid=".$r[classid]."&enews=EditClass'>".$fun_r['edit']."</a>]".$docinfo."&nbsp;[<a href='ecmsclass.php?classid=".$r[classid]."&enews=DelClass' onclick=\"return confirm('".$fun_r['CheckDelClass']."');\">".$fun_r['del']."</a>]</div></td></tr>";
		//取得子栏目
		$returnstr.=$start_tbody.ShowClass_ListClass($r[classid],$exp).$end_tbody;
	}
	return $returnstr;
}

//展开
if($_GET['doopen'])
{
	$open=(int)$_GET['open'];
	SetDisplayClass($open);
}
//图标
if(getcvar('displayclass',1))
{
	$img="<a href='ListClass.php?doopen=1&open=0' title='展开'><img src='../data/images/displaynoadd.gif' width='15' height='15' border='0'></a>";
}
else
{
	$img="<a href='ListClass.php?doopen=1&open=1' title='收缩'><img src='../data/images/displayadd.gif' width='15' height='15' border='0'></a>";
}
//缓存
$displayclass=(int)getcvar('displayclass',1);
$fcfile="../data/fc/ListClass".$displayclass.".php";
echo"<link rel=\"stylesheet\" href=\"adminstyle/".$loginadminstyleid."/adminstyle.css\" type=\"text/css\">";
if(file_exists($fcfile))
{
	@include($fcfile);
	exit();
}
@ob_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>管理栏目</title>
<SCRIPT lanuage="JScript">
function turnit(ss)
{
 if (ss.style.display=="") 
  ss.style.display="none";
 else
  ss.style.display=""; 
}
var newWindow = null
</SCRIPT>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="20%">位置: <a href="ListClass.php">管理栏目</a></td>
    <td width="72%"> <div align="right">
        <input type="button" name="Submit6" value="增加栏目" onclick="self.location.href='AddClass.php?enews=AddClass'">
        <input type="button" name="Submit" value="刷新首页" onclick="self.location.href='ecmschtml.php?enews=ReIndex'">
        <input type="button" name="Submit2" value="刷新所有栏目页" onclick="window.open('ecmschtml.php?enews=ReListHtml_all&from=ListClass.php','','');">
        <input type="button" name="Submit3" value="刷新所有信息页面" onclick="window.open('ReHtml/DoRehtml.php?enews=ReNewsHtml&start=0&from=ListClass.php','','');">
        <input type="button" name="Submit4" value="刷新所有JS调用" onclick="window.open('ecmschtml.php?enews=ReAllNewsJs&from=ListClass.php','','');">
      </div></td>
  </tr>
</table>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <form name=editorder method=post action=ecmsclass.php onsubmit="return confirm('确认要操作?');">
    <tr class="header"> 
      <td width="5%"><div align="center">顺序</div></td>
      <td width="7%"><div align="center">
          <?=$img?>
        </div></td>
      <td width="6%" height="25"><div align="center">ID</div></td>
      <td width="36%" height="25"><div align="center">栏目名</div></td>
      <td width="6%" height="25"><div align="center">访问</div></td>
      <td width="8%" height="25"><div align="center">调用地址</div></td>
      <td width="35%" height="25"><div align="center">操作</div></td>
    </tr>
    <?
 echo ShowClass_ListClass(0,'');
  ?>
    <tr class="header"> 
      <td height="25" colspan="7"> <div align="left"> &nbsp;&nbsp;
          <input type="submit" name="Submit5" value="修改栏目顺序" onClick="document.editorder.enews.value='EditClassOrder';document.editorder.action='ecmsclass.php';">&nbsp;&nbsp;
          <input name="enews" type="hidden" id="enews" value="EditClassOrder">
          <input type="submit" name="Submit7" value="刷新栏目页面" onClick="document.editorder.enews.value='GoReListHtmlMoreA';document.editorder.action='ecmschtml.php';">&nbsp;&nbsp;
          <input type="submit" name="Submit72" value="终极栏目属性转换" onClick="document.editorder.enews.value='ChangeClassIslast';document.editorder.action='ecmsclass.php';">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="7"><strong>终极栏目属性转换说明(只能选择单个栏目)：</strong><br>
        如果你选择的是<font color="#FF0000">非终极栏目</font>，则转为<font color="#FF0000">终极栏目</font><font color="#666666">(此栏目不能有子栏目)</font><br>
        如果你选择的是<font color="#FF0000">终极栏目</font>，则转为<font color="#FF0000">非终极栏目</font><font color="#666666">(请先把当前栏目的数据转移，否则会出现冗余数据)<br>
        </font><strong>修改栏目顺序:顺序值越小越前面</strong></td>
    </tr>
    <input name="from" type="hidden" value="ListClass.php">
    <input name="gore" type="hidden" value="0">
  </form>
</table>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="13%" height="25"> 
      <div align="center">名称</div></td>
    <td width="39%" height="25">调用地址</td>
    <td width="13%">
<div align="center">名称</div></td>
    <td width="35%"> 
      <div align="center">调用地址</div></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"><div align="center">热门信息调用</div></td>
    <td height="25" bgcolor="#FFFFFF"> <input name="textfield" type="text" value="<?=$public_r[newsurl]?>d/js/js/hotnews.js">
      [<a href="ecmschtml.php?enews=ReHot_NewNews">刷新</a>][<a href="view/js.php?js=hotnews&p=js" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center">横向搜索表单</div></td>
    <td bgcolor="#FFFFFF"> <div align="left"> 
        <input name="textfield3" type="text" value="<?=$public_r[newsurl]?>d/js/js/search_news1.js">
        [<a href="view/js.php?js=search_news1&p=js" target="_blank">预览</a>]</div></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"> <div align="center">最新信息调用</div></td>
    <td height="25" bgcolor="#FFFFFF"> <input name="textfield2" type="text" value="<?=$public_r[newsurl]?>d/js/js/newnews.js">
      [<a href="ecmschtml.php?enews=ReHot_NewNews">刷新</a>][<a href="view/js.php?js=newnews&p=js" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center">纵向搜索表单</div></td>
    <td bgcolor="#FFFFFF"> <div align="left"> 
        <input name="textfield4" type="text" value="<?=$public_r[newsurl]?>d/js/js/search_news2.js">
        [<a href="view/js.php?js=search_news2&p=js" target="_blank">预览</a>]</div></td>
  </tr>
  <tr> 
    <td height="25" bgcolor="#FFFFFF"><div align="center">推荐信息调用</div></td>
    <td height="25" bgcolor="#FFFFFF"><input name="textfield22" type="text" value="<?=$public_r[newsurl]?>d/js/js/goodnews.js">
      [<a href="ecmschtml.php?enews=ReHot_NewNews">刷新</a>][<a href="view/js.php?js=goodnews&p=js" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center">搜索页面地址</div></td>
    <td bgcolor="#FFFFFF"> <div align="left"> 
        <input name="textfield5" type="text" value="<?=$public_r[newsurl]?>search">
        [<a href="../../search" target="_blank">预览</a>]</div></td>
  </tr>
  <tr> 
    <td height="24" bgcolor="#FFFFFF"> 
      <div align="center">控制面板地址</div></td>
    <td height="24" bgcolor="#FFFFFF">
<input name="textfield52" type="text" value="<?=$public_r[newsurl]?>e/member/cp">
      [<a href="../member/cp" target="_blank">预览</a>]</td>
    <td bgcolor="#FFFFFF"><div align="center"></div></td>
    <td bgcolor="#FFFFFF"><div align="center"></div></td>
  </tr>
  <tr class="header"> 
    <td height="25" colspan="4">js调用方式：&lt;script src=js地址&gt;&lt;/script&gt;</td>
  </tr>
</table>
</body>
</html>
<?
db_close();
$empire=null;
$string=@ob_get_contents();
WriteFiletext($fcfile,$string);
?>
