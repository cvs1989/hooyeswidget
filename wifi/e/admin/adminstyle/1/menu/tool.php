<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>插件管理</title>
<link rel="stylesheet" href="adminstyle.css" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="adimg")
	{
		img=todisplay(doad,phome);
		document.images.adimg.src=img;
	}
	else if(imgname=="voteimg")
	{
		img=todisplay(dovote,phome);
		document.images.voteimg.src=img;
	}
	else if(imgname=="linkimg")
	{
		img=todisplay(dolink,phome);
		document.images.linkimg.src=img;
	}
	else if(imgname=="gbookimg")
	{
		img=todisplay(dogbook,phome);
		document.images.gbookimg.src=img;
	}
	else if(imgname=="feedbackimg")
	{
		img=todisplay(dofeedback,phome);
		document.images.feedbackimg.src=img;
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
<br>
<?
if($r[doad])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doadid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="adimg"><a href="#ecms" onMouseUp=turnit(doad,"adimg"); style="CURSOR: hand">广告系统</a></td>
  </tr>
  <tbody id="doad"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/AdClass.php" target="main">管理广告分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/ListAd.php" target="main">管理广告</a></td>
    </tr>
  </tbody>
</table>
<br>
<?
}
?>
<?
if($r[dovote])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dovoteid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="voteimg"><a href="#ecms" onMouseUp=turnit(dovote,"voteimg"); style="CURSOR: hand">投票系统</a></td>
  </tr>
  <tbody id="dovote"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/AddVote.php?enews=AddVote" target="main">增加投票</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/ListVote.php" target="main">管理投票</a></td>
    </tr>
  </tbody>
</table>
<br>
<?
}
?>
<?
if($r[dolink])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dolinkid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="linkimg"><a href="#ecms" onMouseUp=turnit(dolink,"linkimg"); style="CURSOR: hand">友情链接管理</a></td>
  </tr>
  <tbody id="dolink"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/LinkClass.php" target="main">管理友情链接分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/ListLink.php" target="main">管理友情链接</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[dogbook])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dogbookid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="gbookimg"><a href="#ecms" onMouseUp=turnit(dogbook,"gbookimg"); style="CURSOR: hand">留言板管理</a></td>
  </tr>
  <tbody id="dogbook"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/GbookClass.php" target="main">管理留言分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/gbook.php" target="main">管理留言</a></td>
    </tr>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[dofeedback]||$r[dofeedbackf])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dofeedbackid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="feedbackimg"><a href="#ecms" onMouseUp=turnit(dofeedback,"feedbackimg"); style="CURSOR: hand">信息反馈管理</a></td>
  </tr>
  <tbody id="dofeedback"<?=$display?>>
    <?
	if($r[dofeedbackf])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/FeedbackClass.php" target="main">管理反馈分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/ListFeedbackF.php" target="main">管理反馈字段</a></td>
    </tr>
    <?
	}
	if($r[dofeedback])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../tool/feedback.php" target="main">管理信息反馈</a></td>
    </tr>
    <?
	}
	?>
  </tbody>
</table>
  <br>
<?
}
?>
<?
if($r[donotcj])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder">
  <tr>
    <td height="25" class="header"><img src="images/noadd.gif" width="20" height="9"><a href="../../template/NotCj.php" target="main">管理防采集随机字符</a></td>
  </tr>
</table>
<br>
<?
}
?>
</body>
</html>