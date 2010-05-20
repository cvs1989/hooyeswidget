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
<title>其他管理</title>
<link rel="stylesheet" href="adminstyle.css" type="text/css">
<SCRIPT lanuage="JScript">
function DisplayImg(ss,imgname,phome)
{
	if(imgname=="newsmodimg")
	{
		img=todisplay(donewsmod,phome);
		document.images.newsmodimg.src=img;
	}
	else if(imgname=="downmodimg")
	{
		img=todisplay(dodownmod,phome);
		document.images.downmodimg.src=img;
	}
	else if(imgname=="shopmodimg")
	{
		img=todisplay(doshopmod,phome);
		document.images.shopmodimg.src=img;
	}
	else if(imgname=="payimg")
	{
		img=todisplay(dopay,phome);
		document.images.payimg.src=img;
	}
	else if(imgname=="picnewsimg")
	{
		img=todisplay(dopicnews,phome);
		document.images.picnewsimg.src=img;
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
if($r[dobefrom]||$r[dowriter]||$r[dokey]||$r[doword])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="donewsmodid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="newsmodimg"><a href="#ecms" onMouseUp=turnit(donewsmod,"newsmodimg"); style="CURSOR: hand">新闻模型相关</a></td>
  </tr>
  <tbody id="donewsmod"<?=$display?>>
	<?
	if($r[dobefrom])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../NewsSys/BeFrom.php" target="main">管理信息来源</a></td>
    </tr>
    <?
	}
	if($r[dowriter])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../NewsSys/writer.php" target="main">管理作者</a></td>
    </tr>
    <?
	}
	if($r[dokey])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../NewsSys/key.php" target="main">管理内容关键字</a></td>
    </tr>
    <?
	}
	if($r[doword])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../NewsSys/word.php" target="main">管理过滤字符</a></td>
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
if($r[dodownurl]||$r[dodeldownrecord]||$r[dodownerror]||$r[dorepdownpath]||$r[doplayer])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dodownmodid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="downmodimg"><a href="#ecms" onMouseUp=turnit(dodownmod,"downmodimg"); style="CURSOR: hand">下载模型相关</a></td>
  </tr>
  <tbody id="dodownmod"<?=$display?>>
    <?
	if($r[dodownurl])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../DownSys/url.php" target="main">管理地址前缀</a></td>
    </tr>
	<?
	}
	if($r[dodeldownrecord])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../DownSys/DelDownRecord.php" target="main">删除下载记录</a></td>
    </tr>
    <?
	}
	if($r[dodownerror])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../DownSys/ListError.php" target="main">管理错误报告</a></td>
    </tr>
    <?
	}
	if($r[dorepdownpath])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../DownSys/RepDownLevel.php" target="main">批量替换地址权限</a></td>
    </tr>
    <?
	}
	if($r[doplayer])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../DownSys/player.php" target="main">播放器管理</a></td>
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
if($r[doshoppayfs]||$r[doshopps]||$r[doshopdd])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="doshopmodid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="shopmodimg"><a href="#ecms" onMouseUp=turnit(doshopmod,"shopmodimg"); style="CURSOR: hand">商城模型相关</a></td>
  </tr>
  <tbody id="doshopmod"<?=$display?>>
    <?
	if($r[doshoppayfs])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ShopSys/ListPayfs.php" target="main">管理支付方式</a></td>
    </tr>
    <?
	}
	if($r[doshopps])
	{
	?>
	<tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ShopSys/ListPs.php" target="main">管理配送方式</a></td>
    </tr>
	<?
	}
	if($r[doshopdd])
	{
	?>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../ShopSys/ListDd.php" target="main">管理订单</a></td>
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
if($r[dopay])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dopayid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="payimg"><a href="#ecms" onMouseUp=turnit(dopay,"payimg"); style="CURSOR: hand">在线支付</a></td>
  </tr>
  <tbody id="dopay"<?=$display?>>
    <tr>
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../pay/SetPayFen.php" target="main">支付参数配置</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../pay/PayApi.php" target="main">管理支付接口</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../pay/ListPayRecord.php" target="main">管理支付记录</a></td>
    </tr>
  </tbody>
</table>
<br>
<?
}
?>
<?
if($r[dopicnews])
{
?>
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="1" class="tableborder" id="dopicnewsid">
  <tr> 
    <td height="25" class="header"><img src="<?=$addimg?>" width="20" height="9" name="picnewsimg"><a href="#ecms" onMouseUp=turnit(dopicnews,"picnewsimg"); style="CURSOR: hand">图片信息管理</a></td>
  </tr>
  <tbody id="dopicnews"<?=$display?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../NewsSys/PicClass.php" target="main">管理图片信息分类</a></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"<?=$movecolor?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="../../NewsSys/ListPicNews.php" target="main">管理图片信息</a></td>
    </tr>
  </tbody>
</table>
<br>
<?
}
?>
</body>
</html>