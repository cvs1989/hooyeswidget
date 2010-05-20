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
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$add="";
$search="";
//搜索
if($_GET['sear'])
{
	$search.="&sear=1";
	//关键字
	$keyboard=RepPostVar2($_GET['keyboard']);
	if($keyboard)
	{
		$show=$_GET['show'];
		if($show==1)
		{
			$add=" and (classname like '%$keyboard%')";
		}
		elseif($show==2)
		{
			$add=" and (intro like '%$keyboard%')";
		}
		elseif($show==3)
		{
			$add=" and (bname like '%$keyboard%')";
		}
		elseif($show==4)
		{
			$add=" and (classid='$keyboard')";
		}
		elseif($show==6)
		{
			$add=" and (bclassid='$keyboard')";
		}
		elseif($show==5)
		{
			$add=" and (classpath like '%$keyboard%')";
		}
		else
		{
			$add=" and (classname like '%$keyboard%' or intro like '%$keyboard%' or bname like '%$keyboard%' or classpath like '%$keyboard%' or classid='$keyboard')";
		}
		$search.="&keyboard=$keyboard&show=$show";
	}
	//条件
	$scond=(int)$_GET['scond'];
	if($scond)
	{
		if($scond==1)
		{
			$add.=" and islast=1";
		}
		elseif($scond==2)
		{
			$add.=" and islast=0";
		}
		elseif($scond==3)
		{
			$add.=" and islist=1 and islast=0";
		}
		elseif($scond==4)
		{
			$add.=" and islist=0 and islast=0";
		}
		elseif($scond==11)
		{
			$add.=" and islist=2 and islast=0";
		}
		elseif($scond==5)
		{
			$add.=" and islast=1 and openadd=1";
		}
		elseif($scond==6)
		{
			$add.=" and islast=1 and openpl=1";
		}
		elseif($scond==7)
		{
			$add.=" and listdt=1";
		}
		elseif($scond==8)
		{
			$add.=" and showdt=1";
		}
		elseif($scond==9)
		{
			$add.=" and showclass=1";
		}
		elseif($scond==10)
		{
			$add.=" and showdt=2";
		}
		$search.="&scond=$scond";
	}
	//模型
	$modid=(int)$_GET['modid'];
	if($modid)
	{
		$add.=" and modid=$modid";
		$search.="&modid=$modid";
	}
}
if($add)
{
	$add=" where".substr($add,4,strlen($add));
}
//系统模型
$modselect="";
$msql=$empire->query("select mid,mname from {$dbtbpre}enewsmod where usemod=0 order by myorder,mid");
while($mr=$empire->fetch($msql))
{
	$select="";
	if($mr[mid]==$modid)
	{
		$select=" selected";
	}
	$modselect.="<option value='".$mr[mid]."'".$select.">".$mr[mname]."</option>";
}
$totalquery="select count(*) as total from {$dbtbpre}enewsclass".$add;
$query="select * from {$dbtbpre}enewsclass".$add;
$num=$empire->gettotal($totalquery);//取得总条数
//排序
$myorder=(int)$_GET['myorder'];
if($myorder==1)
{
	$doorder="myorder";
}
else
{
	$doorder="classid";
}
$orderby=(int)$_GET['orderby'];
if($orderby==1)
{
	$doorderby="";
	$ordername="降序";
	$neworderby=0;
}
else
{
	$doorderby=" desc";
	$ordername="升序";
	$neworderby=1;
}
$orderidlink="<a href='ListPageClass.php?myorder=0&orderby=$neworderby".$search."' title='点击按 栏目ID ".$ordername."排列'>ID</a>";
$ordertwolink="<a href='ListPageClass.php?myorder=1&orderby=$neworderby".$search."' title='点击按 栏目顺序 ".$ordername."排列'>顺序</a>";
$search.="&myorder=$myorder&orderby=$orderby";
$query=$query." order by ".$doorder.$doorderby." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理栏目</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="20%">位置: <a href="ListPageClass.php">管理栏目</a></td>
    <td width="72%"> <div align="right">
        <input type="button" name="Submit6" value="增加栏目" onclick="self.location.href='AddClass.php?enews=AddClass&from=1'">
        <input type="button" name="Submit" value="刷新首页" onclick="self.location.href='ecmschtml.php?enews=ReIndex'">
        <input type="button" name="Submit2" value="刷新所有栏目页" onclick="window.open('ecmschtml.php?enews=ReListHtml_all&from=ListPageClass.php','','');">
        <input type="button" name="Submit3" value="刷新所有信息页面" onclick="window.open('ReHtml/DoRehtml.php?enews=ReNewsHtml&start=0&from=ListPageClass.php','','');">
        <input type="button" name="Submit4" value="刷新所有JS调用" onclick="window.open('ecmschtml.php?enews=ReAllNewsJs&from=ListPageClass.php','','');">
      </div></td>
  </tr>
</table>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
  <form name="searchclass" method="GET" action="ListPageClass.php">
    <tr> 
      <td height="32"><div align="right">搜索: 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
            <option value="0"<?=$show==0?' selected':''?>>不限字段</option>
            <option value="1"<?=$show==1?' selected':''?>>栏目名</option>
            <option value="2"<?=$show==2?' selected':''?>>栏目简介</option>
            <option value="3"<?=$show==3?' selected':''?>>栏目别名</option>
            <option value="4"<?=$show==4?' selected':''?>>栏目ID</option>
			<option value="6"<?=$show==6?' selected':''?>>父栏目ID</option>
            <option value="5"<?=$show==5?' selected':''?>>栏目目录</option>
          </select>
          <select name="scond" id="scond">
            <option value="0"<?=$scond==0?' selected':''?>>不限条件</option>
            <option value="1"<?=$scond==1?' selected':''?>>终极栏目</option>
            <option value="2"<?=$scond==2?' selected':''?>>大栏目</option>
            <option value="3"<?=$scond==3?' selected':''?>>列表式大栏目</option>
            <option value="4"<?=$scond==4?' selected':''?>>封面式大栏目</option>
			<option value="11"<?=$scond==11?' selected':''?>>页面内容式大栏目</option>
            <option value="5"<?=$scond==5?' selected':''?>>未开放投稿的栏目</option>
            <option value="6"<?=$scond==6?' selected':''?>>未开放评论的栏目</option>
            <option value="7"<?=$scond==7?' selected':''?>>动态列表的栏目</option>
            <option value="8"<?=$scond==8?' selected':''?>>动态生成内容的栏目</option>
			<option value="10"<?=$scond==10?' selected':''?>>动态内容页面的栏目</option>
            <option value="9"<?=$scond==9?' selected':''?>>不显示到导航的栏目</option>
          </select>
          <select name="modid" id="modid">
            <option value="0">不限模型</option>
            <?=$modselect?>
          </select>
          <input type="submit" name="Submit8" value="显示">
          <input name="sear" type="hidden" id="sear" value="1">
          <input name="myorder" type="hidden" id="myorder" value="<?=$myorder?>">
          <input name="orderby" type="hidden" id="orderby" value="<?=$orderby?>">
        </div></td>
    </tr>
	</form>
  </table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
  <form name=editorder method=post action=ecmsclass.php onsubmit="return confirm('确认要操作?');">
    <tr class="header"> 
      <td width="5%"><div align="center"> 
          <?=$ordertwolink?>
        </div></td>
      <td width="5%"><div align="center"></div></td>
      <td width="7%" height="25"> <div align="center"> 
          <?=$orderidlink?>
        </div></td>
      <td width="35%" height="25"> <div align="center">栏目名</div></td>
      <td width="9%" height="25"> <div align="center">访问</div></td>
      <td width="8%" height="25"><div align="center">调用地址</div></td>
      <td width="34%" height="25"> <div align="center">操作</div></td>
    </tr>
    <?
	while($r=$empire->fetch($sql))
	{
		$docinfo="";
		$classurl=sys_ReturnBqClassUrl($r);
		if($r[islast]==1)
		{
			$img="<a href='AddNews.php?enews=AddNews&classid=".$r[classid]."' target=_blank title='增加信息'><img src='../data/images/txt.gif' border=0></a>";
			$renewshtml="&nbsp;[<a href='ReHtml/DoRehtml.php?enews=ReNewsHtml&from=ListPageClass.php&classid=".$r[classid]."&tbname[]=".$r[tbname]."'>".$fun_r['news']."</a>]&nbsp;";
			$docinfo="&nbsp;[<a href='ecmsinfo.php?enews=InfoToDoc&ecmsdoc=1&docfrom=ListPageClass.php&classid=".$r[classid]."' onclick=\"return confirm('确认归档?');\">归档</a>]";
		}
		else
		{
			$img="<img src='../data/images/dir.gif'>";
			$renewshtml="&nbsp;[<a href='ReHtml/DoRehtml.php?enews=ReNewsHtml&from=ListPageClass.php&classid=".$r[classid]."&tbname[]=".$r[tbname]."'>".$fun_r['news']."</a>]&nbsp;";
		}
		//外部栏目
		$classname=$r[classname];
		if($r[wburl])
		{
			$classname="<font color='#666666'>".$classname."&nbsp;(外部)</font>";
		}
		//上级栏目
		$bclassname='';
		if($r[bclassid])
		{
			$bcr=$empire->fetch1("select classid,classname from {$dbtbpre}enewsclass where classid='$r[bclassid]'");
			$bclassname=$bcr[classname].'&nbsp;>&nbsp;';
		}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td><div align="center">
	  <input type=text name=myorder[] value="<?=$r[myorder]?>" size=2>
	  <input type=hidden name=classid[] value="<?=$r[classid]?>">
	  </div></td>
      <td><div align="center"><?=$img?></div></td>
      <td height="25"><div align="center"><?=$r[classid]?></div></td>
      <td height="25"><?="<input type=checkbox name=reclassid[] value='".$r[classid]."'>&nbsp;".$bclassname."<a href='".$classurl."' target=_blank><b>".$classname."</b></a>";?></td>
      <td height="25"><div align="center"><?=$r[onclick]?></div></td>
      <td height="25"><div align="center"><a href='#ecms' onclick=javascript:window.open('view/ClassUrl.php?classid=<?=$r[classid]?>','','width=500,height=200');>查看地址</a></div></td>
      <td height="25"><div align="center">
	  <?="[<a href='enews.php?enews=ReListHtml&from=ListPageClass.php&classid=".$r[classid]."'>刷新</a>]".$renewshtml."[<a href='ecmschtml.php?enews=ReSingleJs&doing=0&classid=".$r[classid]."'>JS</a>]&nbsp;[<a href='AddClass.php?classid=".$r[classid]."&enews=AddClass&docopy=1&from=1'>复制</a>]&nbsp;[<a href='AddClass.php?classid=".$r[classid]."&enews=EditClass&from=1'>修改</a>]".$docinfo."&nbsp;[<a href='ecmsclass.php?classid=".$r[classid]."&enews=DelClass&from=1' onclick=\"return confirm('".$fun_r['CheckDelClass']."');\">删除</a>]";?>
	  </div></td>
    </tr>
    <?
	}
  	?>
    <tr bgcolor="#ffffff"> 
      <td height="25" colspan="7"> <div align="left"> &nbsp; 
          <input type="submit" name="Submit5" value="修改栏目顺序" onClick="document.editorder.enews.value='EditClassOrder';document.editorder.action='ecmsclass.php';">
          <input name="enews" type="hidden" id="enews" value="EditClassOrder">
          &nbsp;&nbsp; 
          <input type="submit" name="Submit7" value="刷新栏目页面" onClick="document.editorder.enews.value='GoReListHtmlMoreA';document.editorder.action='ecmschtml.php';"">
          &nbsp;&nbsp; 
          <input type="submit" name="Submit72" value="终极栏目属性转换" onClick="document.editorder.enews.value='ChangeClassIslast';document.editorder.action='ecmsclass.php';"">
        </div></td>
    </tr>
    <tr bgcolor="#ffffff"> 
      <td height="25" colspan="7">&nbsp;&nbsp; 
        <?=$returnpage?>
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="65" colspan="7"><strong>终极栏目属性转换说明(只能选择单个栏目)：</strong><br>
        如果你选择的是<font color="#FF0000">非终极栏目</font>，则转为<font color="#FF0000">终极栏目</font><font color="#666666">(此栏目不能有子栏目)</font><br>
        如果你选择的是<font color="#FF0000">终极栏目</font>，则转为<font color="#FF0000">非终极栏目</font><font color="#666666">(请先把当前栏目的数据转移，否则会出现冗余数据)<br>
        </font><strong>修改栏目顺序:顺序值越小越前面</strong></td>
    </tr>
    <input name="from" type="hidden" value="ListPageClass.php">
    <input name="gore" type="hidden" value="0">
  </form>
</table>
</body>
</html>
<?
db_close();
$empire=null;
?>
