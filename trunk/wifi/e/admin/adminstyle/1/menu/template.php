<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
//模板组
$gid=(int)$_GET['gid'];
if(!$gid)
{
	if($ecmsdeftempid)
	{
		$gid=$ecmsdeftempid;
	}
	elseif($public_r['deftempid'])
	{
		$gid=$public_r['deftempid'];
	}
	else
	{
		$gid=1;
	}
}
$tempgroup="";
$tgname="";
$tgsql=$empire->query("select gid,gname,isdefault from {$dbtbpre}enewstempgroup order by gid");
while($tgr=$empire->fetch($tgsql))
{
	$tgselect="";
	if($tgr['gid']==$gid)
	{
		$tgname=$tgr['gname'];
		$tgselect=" selected";
	}
	$tempgroup.="<option value='".$tgr['gid']."'".$tgselect.">".$tgr['gname']."</option>";
}
if(empty($tgname))
{
	printerror("ErrorUrl","");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>模板管理</title>
<link rel="stylesheet" href="adminstyle.css" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="ltimg")
	{
		img=todisplay(dolt,phome);
		document.images.ltimg.src=img;
	}
	else if(imgname=="ctimg")
	{
		img=todisplay(doct,phome);
		document.images.ctimg.src=img;
	}
	else if(imgname=="ntimg")
	{
		img=todisplay(dont,phome);
		document.images.ntimg.src=img;
	}
	else if(imgname=="bqtimg")
	{
		img=todisplay(dobqt,phome);
		document.images.bqtimg.src=img;
	}
	else if(imgname=="tvimg")
	{
		img=todisplay(dotv,phome);
		document.images.tvimg.src=img;
	}
	else if(imgname=="ptimg")
	{
		img=todisplay(dopt,phome);
		document.images.ptimg.src=img;
	}
	else if(imgname=="upimg")
	{
		img=todisplay(doup,phome);
		document.images.upimg.src=img;
	}
	else if(imgname=="jstimg")
	{
		img=todisplay(dojst,phome);
		document.images.jstimg.src=img;
	}
	else if(imgname=="stimg")
	{
		img=todisplay(dost,phome);
		document.images.stimg.src=img;
	}
	else if(imgname=="pltimg")
	{
		img=todisplay(doplt,phome);
		document.images.pltimg.src=img;
	}
	else if(imgname=="vtimg")
	{
		img=todisplay(dovt,phome);
		document.images.vtimg.src=img;
	}
	else if(imgname=="bqimg")
	{
		img=todisplay(dobq,phome);
		document.images.bqimg.src=img;
	}
	else if(imgname=="otimg")
	{
		img=todisplay(doot,phome);
		document.images.otimg.src=img;
	}
	else if(imgname=="ujimg")
	{
		img=todisplay(douj,phome);
		document.images.ujimg.src=img;
	}
	else if(imgname=="ulimg")
	{
		img=todisplay(doul,phome);
		document.images.ulimg.src=img;
	}
	else
	{
	}
}
function todisplay(ss,phome)
{
	if(ss.style.display=="") 
	{
  		ss.style.display="none";
		theimg="images/add.gif";
	}
	else
	{
  		ss.style.display="";
		theimg="images/noadd.gif";
	}
	return theimg;
}
function turnit(ss,img)
{
	DisplayImg(ss,img,0);
}
</SCRIPT>
</head>

<body topmargin="0">
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder">
  <tr>
    <td height="25" class="header"><img src="images/noadd.gif" width="20" height="9">
	<select name="selecttempgroup" onchange="self.location.href='left.php?ecms=template&gid='+this.options[this.selectedIndex].value">
	<?=$tempgroup?>
	</select>
	</td>
  </tr>
  </table>
<br>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder">
  <tr>
    <td height="25" class="header"><img src="images/noadd.gif" width="20" height="9"><a href="#ecms" onclick="window.open('../../template/EnewsBq.php','','width=600,height=600,scrollbars=yes,resizable=yes');">查看标签语法</a>
	</td>
  </tr>
  </table>
<br>
<?
if($r[dotemplate])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doctid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ctimg"><a href="#ecms" onMouseUp=turnit(doct,"ctimg"); style="CURSOR: hand">栏目封面模板</a></td>
  </tr>
  <tbody id="doct"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ClassTempClass.php?gid=<?=$gid?>" target="main">管理封面模板分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListClasstemp.php?gid=<?=$gid?>" target="main">管理封面模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doltid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ltimg"><a href="#ecms" onMouseUp=turnit(dolt,"ltimg"); style="CURSOR: hand">列表模板</a></td>
  </tr>
  <tbody id="dolt"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListtempClass.php?gid=<?=$gid?>" target="main">管理列表模板分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListListtemp.php?gid=<?=$gid?>" target="main">管理列表模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dontid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ntimg"><a href="#ecms" onMouseUp=turnit(dont,"ntimg"); style="CURSOR: hand">内容模板</a></td>
  </tr>
  <tbody id="dont"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/NewstempClass.php?gid=<?=$gid?>" target="main">管理内容模板分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListNewstemp.php?gid=<?=$gid?>" target="main">管理内容模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dobqtid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="bqtimg"><a href="#ecms" onMouseUp=turnit(dobqt,"bqtimg"); style="CURSOR: hand">标签模板</a></td>
  </tr>
  <tbody id="dobqt"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/BqtempClass.php?gid=<?=$gid?>" target="main">管理标签模板分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListBqtemp.php?gid=<?=$gid?>" target="main">管理标签模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotempvar])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dotvid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="tvimg"><a href="#ecms" onMouseUp=turnit(dotv,"tvimg"); style="CURSOR: hand">公共模板变量</a></td>
  </tr>
  <tbody id="dotv"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/TempvarClass.php?gid=<?=$gid?>" target="main">管理模板变量分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListTempvar.php?gid=<?=$gid?>" target="main">管理模板变量</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doptid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ptimg"><a href="../../template/EditPublicTemp.php?gid=<?=$gid?>" target="main" onMouseUp=turnit(dopt,"ptimg"); style="CURSOR: hand">公共模板</a></td>
  </tr>
  <tbody id="dopt">
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=indextemp&gid=<?=$gid?>" target="main">修改首页模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=cptemp&gid=<?=$gid?>" target="main">修改控制面板模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=schalltemp&gid=<?=$gid?>" target="main">修改全站搜索模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=searchformtemp&gid=<?=$gid?>" target="main">修改高级搜索表单模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=searchformjs&gid=<?=$gid?>" target="main">修改横向搜索JS模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=searchformjs1&gid=<?=$gid?>" target="main">修改纵向搜索JS模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=otherlinktemp&gid=<?=$gid?>" target="main">修改相关信息模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=printtemp&gid=<?=$gid?>" target="main">修改信息打印模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=gbooktemp&gid=<?=$gid?>" target="main">修改留言板模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=pljstemp&gid=<?=$gid?>" target="main">修改评论JS调用模板</a></td>
    </tr>
    <tr>
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=downpagetemp&gid=<?=$gid?>" target="main">修改最终下载页模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=downsofttemp&gid=<?=$gid?>" target="main">修改下载地址模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=onlinemovietemp&gid=<?=$gid?>" target="main">修改在线播放地址模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=listpagetemp&gid=<?=$gid?>" target="main">修改列表分页模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=loginiframe&gid=<?=$gid?>" target="main">修改登陆状态模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/EditPublicTemp.php?tname=loginjstemp&gid=<?=$gid?>" target="main">修改JS调用登陆模板</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[douserpage])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doupid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="upimg"><a href="#ecms" onMouseUp=turnit(doup,"upimg"); style="CURSOR: hand">自定义页面</a></td>
  </tr>
  <tbody id="doup"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/PageClass.php?gid=<?=$gid?>" target="main">管理自定义页面分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/AddPage.php?enews=AddUserpage&gid=<?=$gid?>" target="main">增加自定义页面</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListPage.php?gid=<?=$gid?>" target="main">管理自定义页面</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[douserjs])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doujid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ujimg"><a href="#ecms" onMouseUp=turnit(douj,"ujimg"); style="CURSOR: hand">自定义JS</a></td>
  </tr>
  <tbody id="douj"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/AddUserjs.php?enews=AddUserjs&gid=<?=$gid?>" target="main">增加自定义JS</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/ListUserjs.php?gid=<?=$gid?>" target="main">管理自定义JS</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[douserlist])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doulid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="ulimg"><a href="#ecms" onMouseUp=turnit(doul,"ulimg"); style="CURSOR: hand">自定义列表</a></td>
  </tr>
  <tbody id="doul"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/AddUserlist.php?enews=AddUserlist&gid=<?=$gid?>" target="main">增加自定义列表</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../other/ListUserlist.php?gid=<?=$gid?>" target="main">管理自定义列表</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dojstid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="jstimg"><a href="#ecms" onMouseUp=turnit(dojst,"jstimg"); style="CURSOR: hand">JS模板</a></td>
  </tr>
  <tbody id="dojst"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/JsTempClass.php?gid=<?=$gid?>" target="main">管理JS模板分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListJstemp.php?gid=<?=$gid?>" target="main">管理JS模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dostid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="stimg"><a href="#ecms" onMouseUp=turnit(dost,"stimg"); style="CURSOR: hand">搜索模板</a></td>
  </tr>
  <tbody id="dost"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/SearchtempClass.php?gid=<?=$gid?>" target="main">管理搜索模板分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListSearchtemp.php?gid=<?=$gid?>" target="main">管理搜索模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dopltid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="pltimg"><a href="#ecms" onMouseUp=turnit(doplt,"pltimg"); style="CURSOR: hand">评论列表模板</a></td>
  </tr>
  <tbody id="doplt"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/AddPltemp.php?enews=AddPlTemp&gid=<?=$gid?>" target="main">增加评论模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListPltemp.php?gid=<?=$gid?>" target="main">管理评论模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dovtid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="vtimg"><a href="#ecms" onMouseUp=turnit(dovt,"vtimg"); style="CURSOR: hand">投票模板</a></td>
  </tr>
  <tbody id="dovt"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/AddVotetemp.php?enews=AddVoteTemp&gid=<?=$gid?>" target="main">增加投票模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListVotetemp.php?gid=<?=$gid?>" target="main">管理投票模板</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dobq])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dobqid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="bqimg"><a href="#ecms" onMouseUp=turnit(dobq,"bqimg"); style="CURSOR: hand">标签</a></td>
  </tr>
  <tbody id="dobq"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/BqClass.php?gid=<?=$gid?>" target="main">管理标签分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ListBq.php?gid=<?=$gid?>" target="main">管理标签</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
<?
if($r[dotempgroup])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder">
  <tr>
    <td height="25" class="header"><img src="images/noadd.gif" width="20" height="9"><a href="../../template/TempGroup.php" target="main">模板组管理</a></td>
  </tr>
  </table>
  <br>
<?
}
?>
<?
if($r[dotemplate])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dootid">
  <tr> 
    <td height="25" class="header"><p><img src="<?=$addimg?>" width="20" height="9" name="otimg"><a href="#ecms" onMouseUp=turnit(doot,"otimg"); style="CURSOR: hand">其它管理</a></p>
      </td>
  </tr>
  <tbody id="doot"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/LoadTemp.php?gid=<?=$gid?>" target="main">批量导入栏目模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/ChangeListTemp.php?gid=<?=$gid?>" target="main">批量更换列表模板</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/RepTemp.php?gid=<?=$gid?>" target="main">批量替换模板字符</a></td>
    </tr>
  </tbody>
  </table>
  <br>
<?
}
?>
</body>
</html>