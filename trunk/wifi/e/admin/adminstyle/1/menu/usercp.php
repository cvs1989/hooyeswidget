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
<title>用户面板</title>
<link rel="stylesheet" href="adminstyle.css" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="userimg")
	{
		img=todisplay(douser,phome);
		document.images.userimg.src=img;
	}
	else if(imgname=="memberimg")
	{
		img=todisplay(domember,phome);
		document.images.memberimg.src=img;
	}
	else if(imgname=="memberspaceimg")
	{
		img=todisplay(domemberspace,phome);
		document.images.memberspaceimg.src=img;
	}
	else if(imgname=="motherimg")
	{
		img=todisplay(domother,phome);
		document.images.motherimg.src=img;
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
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="douserid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="userimg"><a href="#ecms" onMouseUp=turnit(douser,"userimg"); style="CURSOR: hand">用户管理</a></td>
  </tr>
  <tbody id="douser"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../user/EditPassword.php" target="main">修改我的资料</a></td>
    </tr>
	<?
	if($r[dogroup])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../user/ListGroup.php" target="main">管理用户组</a></td>
    </tr>
	<?
	}
	if($r[douser])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../user/ListUser.php" target="main">管理用户</a></td>
    </tr>
	<?
	}
	if($r[dolog])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../user/ListLog.php" target="main">管理登陆日志</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../user/ListDolog.php" target="main">管理操作日志</a></td>
    </tr>
	<?
	}
	if($r[doadminstyle])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../template/AdminStyle.php" target="main">管理后台风格</a></td>
    </tr>
	<?
	}
	?>
  </tbody>
</table>
<br>
<?
if($r[domember]||$r[domemberf])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="domemberid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="memberimg"><a href="#ecms" onMouseUp=turnit(domember,"memberimg"); style="CURSOR: hand">会员管理</a></td>
  </tr>
  <tbody id="domember"<?=$display?>>
	<?
	if($r[domember])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListMemberGroup.php" target="main">管理会员组</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListMember.php" target="main">管理会员</a></td>
    </tr>
	<?
	}
	if($r[domemberf])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListMemberF.php" target="main">管理会员字段</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListMemberForm.php" target="main">管理会员表单</a></td>
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
if($r[dospacestyle]||$r[dospacedata])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="domemberspaceid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="memberspaceimg"><a href="#ecms" onMouseUp=turnit(domemberspace,"memberspaceimg"); style="CURSOR: hand">会员空间管理</a></td>
  </tr>
  <tbody id="domemberspace"<?=$display?>>
	<?
	if($r[dospacestyle])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListSpaceStyle.php" target="main">管理空间模板</a></td>
    </tr>
	<?
	}
	if($r[dospacedata])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/MemberGbook.php" target="main">管理空间留言</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/MemberFeedback.php" target="main">管理空间反馈</a></td>
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
if($r[docard]||$r[dosendemail]||$r[domsg]||$r[dobuygroup])
{
?>
  <table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="domotherid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="motherimg"><a href="#ecms" onMouseUp=turnit(domother,"motherimg"); style="CURSOR: hand">其他管理</a></td>
  </tr>
  <tbody id="domother"<?=$display?>>
	<?
	if($r[dobuygroup])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListBuyGroup.php" target="main">管理充值类型</a></td>
    </tr>
	<?
	}
	if($r[docard])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/ListCard.php" target="main">管理点卡</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/GetFen.php" target="main">批量赠送点数</a></td>
    </tr>
	<?
	}
	if($r[dosendemail])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/SendEmail.php" target="main">批量发送邮件</a></td>
    </tr>
	<?
	}
	if($r[domsg])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/SendMsg.php" target="main">批量发送短信息</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../member/DelMoreMsg.php" target="main">批量删除短信息</a></td>
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
</body>
</html>