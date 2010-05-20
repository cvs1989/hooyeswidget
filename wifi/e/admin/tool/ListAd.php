<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require "../".LoadLang("pub/fun.php");
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
CheckLevel($logininid,$loginin,$classid,"ad");

//增加广告
function AddAd($add,$titlefont,$titlecolor,$userid,$username){
	global $empire,$dbtbpre;
	if(!$add[classid]||!$add[title]||!$add[adtype])
	{printerror("EmptyAd","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"ad");
	$add[htmlcode]=RepPhpAspJspcodeText($add[htmlcode]);
	$add[reptext]=RepPhpAspJspcodeText($add[reptext]);
	$ttitlefont=TitleFont($titlefont,'no');
	//变量处理
	$add[pic_width]=(int)$add[pic_width];
	$add[pic_height]=(int)$add[pic_height];
	$add[classid]=(int)$add[classid];
	$add[adtype]=(int)$add[adtype];
	$add[t]=(int)$add[t];
	$add[ylink]=(int)$add[ylink];
	$sql=$empire->query("insert into {$dbtbpre}enewsad(picurl,url,pic_width,pic_height,onclick,classid,adtype,title,target,alt,starttime,endtime,adsay,titlefont,titlecolor,htmlcode,t,ylink,reptext) values('$add[picurl]','$add[url]',$add[pic_width],$add[pic_height],0,$add[classid],$add[adtype],'$add[title]','$add[target]','$add[alt]','$add[starttime]','$add[endtime]','$add[adsay]','$ttitlefont','$titlecolor','$add[htmlcode]',$add[t],$add[ylink],'$add[reptext]');");
	$adid=$empire->lastid();
	GetAdJs($adid);
	if($sql)
	{
		//操作日志
		insert_dolog("adid=".$adid."<br>title=".$add[title]);
		printerror("AddAdSuccess","AddAd.php?enews=AddAd&t=".$add[t]);
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//修改广告
function EditAd($add,$titlefont,$titlecolor,$userid,$username){
	global $empire,$time,$dbtbpre;
	$add[adid]=(int)$add[adid];
	if(!$add[classid]||!$add[title]||!$add[adtype]||!$add[adid])
	{printerror("EmptyAd","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"ad");
	$add[htmlcode]=RepPhpAspJspcodeText($add[htmlcode]);
	$add[reptext]=RepPhpAspJspcodeText($add[reptext]);
	$ttitlefont=TitleFont($titlefont,'no');
	//重置
	if($add[reset])
	{$a=",onclick=0";}
	//变量处理
	$add[pic_width]=(int)$add[pic_width];
	$add[pic_height]=(int)$add[pic_height];
	$add[classid]=(int)$add[classid];
	$add[adtype]=(int)$add[adtype];
	$add[t]=(int)$add[t];
	$add[ylink]=(int)$add[ylink];
	$sql=$empire->query("update {$dbtbpre}enewsad set picurl='$add[picurl]',url='$add[url]',pic_width=$add[pic_width],pic_height=$add[pic_height],classid=$add[classid],adtype=$add[adtype],title='$add[title]',target='$add[target]',alt='$add[alt]',starttime='$add[starttime]',endtime='$add[endtime]',adsay='$add[adsay]',titlefont='$ttitlefont',titlecolor='$titlecolor',htmlcode='$add[htmlcode]',t=$add[t],ylink=$add[ylink],reptext='$add[reptext]'".$a." where adid='$add[adid]'");
	GetAdJs($add[adid]);
	if($sql)
	{
		//操作日志
		insert_dolog("adid=".$add[adid]."<br>title=".$add[title]);
		printerror("EditAdSuccess","ListAd.php?time=$time");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//删除广告
function DelAd($adid,$userid,$username){
	global $empire,$time,$public_r,$dbtbpre;
	$adid=(int)$adid;
	if(!$adid)
	{printerror("NotDelAdid","history.go(-1)");}
	//验证权限
	CheckLevel($userid,$username,$classid,"ad");
	$r=$empire->fetch1("select title from {$dbtbpre}enewsad where adid='$adid'");
	$sql=$empire->query("delete from {$dbtbpre}enewsad where adid='$adid'");
	$file="../../../d/js/acmsd/".$public_r[adfile].$adid.".js";
	DelFiletext($file);
	if($sql)
	{
		//操作日志
		insert_dolog("adid=".$adid."<br>title=".$r[title]);
		printerror("DelAdSuccess","ListAd.php?time=$time");
	}
	else
	{printerror("DbError","history.go(-1)");}
}

//批量生成广告
function ReAdJs_all($start=0,$from,$userid,$username){
	global $empire,$public_r,$fun_r,$dbtbpre;
	$start=(int)$start;
	if(empty($start))
	{
		$start=0;
    }
	$b=0;
	$sql=$empire->query("select adid from {$dbtbpre}enewsad where adid>$start order by adid limit ".$public_r['readjsnum']);
	while($r=$empire->fetch($sql))
	{
		$b=1;
		$newstart=$r[adid];
		GetAdJs($r[adid]);
	}
	if(empty($b))
	{
		//操作日志
		insert_dolog("");
		printerror("ReAdJsSuccess",$from);
	}
	echo $fun_r['OneReAdJsSuccess']."(ID:<font color=red><b>".$newstart."</b></font>)<script>self.location.href='ListAd.php?enews=ReAdJs_all&start=$newstart&from=$from';</script>";
	exit();
}

//清除注释
function ClearHtmlZs($text){
	$text=str_replace('<!--','',$text);
	$text=str_replace('//-->','',$text);
	$text=str_replace('-->','',$text);
	return $text;
}

//生成广告js
function GetAdJs($adid){
	global $empire,$public_r,$dbtbpre;
	$r=$empire->fetch1("select * from {$dbtbpre}enewsad where adid='$adid'");
	$file="../../../d/js/acmsd/".$public_r[adfile].$adid.".js";
	//到期
	if(time()>to_time($r['endtime']))
	{
		$r[reptext]=ClearHtmlZs($r[reptext]);
		$h=addslashes(str_replace("\r\n","",$r[reptext]));
		$html="document.write(\"".$h."\")";
		WriteFiletext_n($file,$html);
		return '';
	}
	if($r['ylink'])
	{
		$ad_url=$r['url'];
	}
	else
	{
		$ad_url=$public_r[newsurl]."e/public/ClickAd?adid=".$adid;//广告链接
	}
	//----------------------文字广告
	if($r[t]==1)
	{
		$r[titlefont]=$r[titlecolor].','.$r[titlefont];
		$picurl=DoTitleFont($r[titlefont],$r[picurl]);//文字属性
		$h="<a href='".$ad_url."' target=".$r[target]." title='".$r[alt]."'>".addslashes($picurl)."</a>";
		//普通显示
		if($r[adtype]==1)
		{
			$html="document.write(\"".$h."\")";
	    }
		//可移动透明对话框
		else
		{
			$html="document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_dialog.js></script>\"); 
document.write(\"<div style='position:absolute;left:300px;top:150px;width:".$r[pic_width]."; height:".$r[pic_height].";z-index:1;solid;filter:alpha(opacity=90)' id=DGbanner5 onmousedown='down1(this)' onmousemove='move()' onmouseup='down=false'><table cellpadding=0 border=0 cellspacing=1 width=".$r[pic_width]." height=".$r[pic_height]." bgcolor=#000000><tr><td height=18 bgcolor=#5A8ACE align=right style='cursor:move;'><a href=# style='font-size: 9pt; color: #eeeeee; text-decoration: none' onClick=clase('DGbanner5') >关闭>>><img border='0' src='".$public_r[newsurl]."d/js/acmsd/close_o.gif'></a>&nbsp;</td></tr><tr><td bgcolor=f4f4f4 >&nbsp;".$h."</td></tr></table></div>\");";
	    }
    }
	//------------------html广告
	elseif($r[t]==2)
	{
		$r[htmlcode]=ClearHtmlZs($r[htmlcode]);
		$h=addslashes(str_replace("\r\n","",$r[htmlcode]));
		//普通显示
		if($r[adtype]==1)
		{
			$html="document.write(\"".$h."\")";
		}
		//可移动透明对话框
		else
		{
			$html="document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_dialog.js></script>\"); 
document.write(\"<div style='position:absolute;left:300px;top:150px;width:".$r[pic_width]."; height:".$r[pic_height].";z-index:1;solid;filter:alpha(opacity=90)' id=DGbanner5 onmousedown='down1(this)' onmousemove='move()' onmouseup='down=false'><table cellpadding=0 border=0 cellspacing=1 width=".$r[pic_width]." height=".$r[pic_height]." bgcolor=#000000><tr><td height=18 bgcolor=#5A8ACE align=right style='cursor:move;'><a href=# style='font-size: 9pt; color: #eeeeee; text-decoration: none' onClick=clase('DGbanner5') >关闭>>><img border='0' src='".$public_r[newsurl]."d/js/acmsd/close_o.gif'></a>&nbsp;</td></tr><tr><td bgcolor=f4f4f4 >&nbsp;".$h."</td></tr></table></div>\");";
		}
    }
	//------------------弹出广告
	elseif($r[t]==3)
	{
		//打开新窗口
		if($r[adtype]==8)
		{
			$html="window.open('".$r[url]."');";
		}
		//弹出窗口
	    elseif($r[adtype]==9)
		{
			$html="window.open('".$r[url]."','','width=".$r[pic_width].",height=".$r[pic_height].",scrollbars=yes');";
		}
		//普能网页窗口
		else
		{
			$html="window.showModalDialog('".$r[url]."','','dialogWidth:".$r[pic_width]."px;dialogHeight:".$r[pic_height]."px;scroll:no;status:no;help:no');";
		}
    }
	//---------------------图片与flash广告
	else
	{
	$filetype=GetFiletype($r[picurl]);
	
	//flash
		if($filetype==".swf")
		{
		$h="<object classid=\\\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\\\" codebase=\\\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\\\" name=\\\"movie\\\" width=\\\"".$r[pic_width]."\\\" height=\\\"".$r[pic_height]."\\\" id=\\\"movie\\\"><param name=\\\"movie\\\" value=\\\"".$r[picurl]."\\\"><param name=\\\"quality\\\" value=\\\"high\\\"><param name=\\\"menu\\\" value=\\\"false\\\"><embed src=\\\"".$r[picurl]."\\\" width=\\\"".$r[pic_width]."\\\" height=\\\"".$r[pic_height]."\\\" quality=\\\"high\\\" pluginspage=\\\"http://www.macromedia.com/go/getflashplayer\\\" type=\\\"application/x-shockwave-flash\\\" id=\\\"movie\\\" name=\\\"movie\\\" menu=\\\"false\\\"></embed><PARAM NAME='wmode' VALUE='Opaque'></object>";
	    }
	else
		{
		$h="<a href='".$ad_url."' target=".$r[target]."><img src='".$r[picurl]."' border=0 width='".$r[pic_width]."' height='".$r[pic_height]."' alt='".$r[alt]."'></a>";
	    }
		//普通显示
			if($r[adtype]==1)
		{
			$html="document.write(\"".$h."\");";
		}
		//满屏浮动显示
		elseif($r[adtype]==4)
		{
			$html="ns4=(document.layers)?true:false;
ie4=(document.all)?true:false;
if(ns4){document.write(\"<layer id=DGbanner2 width=".$r[pic_width]." height=".$r[pic_height]." onmouseover=stopme('DGbanner2') onmouseout=movechip('DGbanner2')>".$h."</layer>\");}
else{document.write(\"<div id=DGbanner2 style='position:absolute; width:".$r[pic_width]."px; height:".$r[pic_height]."px; z-index:9; filter: Alpha(Opacity=90)' onmouseover=stopme('DGbanner2') onmouseout=movechip('DGbanner2')>".$h."</div>\");}
document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_float_fullscreen.js></script>\");";
		}
		//上下浮动显示 - 右
		elseif($r[adtype]==5)
		{
			$html="if (navigator.appName == 'Netscape')
{document.write(\"<layer id=DGbanner3 top=150 width=".$r[pic_width]." height=".$r[pic_height].">".$h."</layer>\");}
else{document.write(\"<div id=DGbanner3 style='position: absolute;width:".$r[pic_height].";top:150;visibility: visible;z-index: 1'>".$h."</div>\");}
document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_float_upanddown.js></script>\");";
		}
		//上下浮动显示 - 左
		elseif($r[adtype]==6)
		{
			$html="if(navigator.appName == 'Netscape')
{document.write(\"<layer id=DGbanner10 top=150 width=".$r[pic_width]." height=".$r[pic_height].">".$h."</layer>\");}
else{document.write(\"<div id=DGbanner10 style='position: absolute;width:".$r[pic_width].";top:150;visibility: visible;z-index: 1'>".$h."</div>\");}
document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_float_upanddown_L.js></script>\");";
		}
		//全屏幕渐隐消失
		elseif($r[adtype]==7)
		{
			$html="ns4=(document.layers)?true:false;
if(ns4){document.write(\"<layer id=DGbanner4Cont onLoad='moveToAbsolute(layer1.pageX-160,layer1.pageY);clip.height=".$r[pic_height].";clip.width=".$r[pic_width]."; visibility=show;'><layer id=DGbanner4News position:absolute; top:0; left:0>".$h."</layer></layer>\");}
else{document.write(\"<div id=DGbanner4 style='position:absolute;top:0; left:0;'><div id=DGbanner4Cont style='position:absolute;width:".$r[pic_width].";height:".$r[pic_height].";clip:rect(0,".$r[pic_width].",".$r[pic_height].",0)'><div id=DGbanner4News style='position:absolute;top:0;left:0;right:820'>".$h."</div></div></div>\");} 
document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_fullscreen.js></script>\");";
		}
		//可移动透明对话框
		elseif($r[adtype]==3)
		{
			$html="document.write(\"<script language=javascript src=".$public_r[newsurl]."d/js/acmsd/ecms_dialog.js></script>\"); 
document.write(\"<div style='position:absolute;left:300px;top:150px;width:".$r[pic_width]."; height:".$r[pic_height].";z-index:1;solid;filter:alpha(opacity=90)' id=DGbanner5 onmousedown='down1(this)' onmousemove='move()' onmouseup='down=false'><table cellpadding=0 border=0 cellspacing=1 width=".$r[pic_width]." height=".$r[pic_height]." bgcolor=#000000><tr><td height=18 bgcolor=#5A8ACE align=right style='cursor:move;'><a href=# style='font-size: 9pt; color: #eeeeee; text-decoration: none' onClick=clase('DGbanner5') >关闭>>><img border='0' src='".$public_r[newsurl]."d/js/acmsd/close_o.gif'></a>&nbsp;</td></tr><tr><td bgcolor=f4f4f4 >&nbsp;".$h."</td></tr></table></div>\");";
		}
		else
		{
			$html="function closeAd(){huashuolayer2.style.visibility='hidden';huashuolayer3.style.visibility='hidden';}function winload(){huashuolayer2.style.top=109;huashuolayer2.style.left=5;huashuolayer3.style.top=109;huashuolayer3.style.right=5;}//if(document.body.offsetWidth>800){
				{document.write(\"<div id=huashuolayer2 style='position: absolute;visibility:visible;z-index:1'><table width=0  border=0 cellspacing=0 cellpadding=0><tr><td height=10 align=right bgcolor=666666><a href=javascript:closeAd()><img src=".$public_r[newsurl]."d/js/acmsd/close.gif width=12 height=10 border=0></a></td></tr><tr><td>".$h."</td></tr></table></div>\"+\"<div id=huashuolayer3 style='position: absolute;visibility:visible;z-index:1'><table width=0  border=0 cellspacing=0 cellpadding=0><tr><td height=10 align=right bgcolor=666666><a href=javascript:closeAd()><img src=".$public_r[newsurl]."d/js/acmsd/close.gif width=12 height=10 border=0></a></td></tr><tr><td>".$h."</td></tr></table></div>\");}winload()//}";
		}
    }
	WriteFiletext_n($file,$html);
}

$enews=$_POST['enews'];
if(empty($enews))
{$enews=$_GET['enews'];}
//增加广告
if($enews=="AddAd")
{
	$add=$_POST['add'];
	$add[picurl]=$_POST['picurl'];
	$titlefont=$_POST['titlefont'];
	$titlecolor=$_POST['titlecolor'];
	AddAd($add,$titlefont,$titlecolor,$logininid,$loginin);
}
//修改广告
elseif($enews=="EditAd")
{
	$add=$_POST['add'];
	$add[picurl]=$_POST['picurl'];
	$titlefont=$_POST['titlefont'];
	$titlecolor=$_POST['titlecolor'];
	$time=$_POST['time'];
	EditAd($add,$titlefont,$titlecolor,$logininid,$loginin);
}
//删除广告
elseif($enews=="DelAd")
{
	$adid=$_GET['adid'];
	$time=$_POST['time'];
	DelAd($adid,$logininid,$loginin);
}
//批量刷新广告JS
elseif($enews=="ReAdJs_all")
{
	ReAdJs_all($_GET['start'],$_GET['from'],$logininid,$loginin);
}

$page=(int)$_GET['page'];
$start=0;
$line=10;//每页显示条数
$page_line=23;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select * from {$dbtbpre}enewsad";
//过期广告
$time=$_GET['time'];
if(empty($time))
{$time=$_POST['time'];}
$where=" where";
if($time)
{
	$date=date("Y-m-d");
	$query.=" where endtime<'$date'";
	$where=" and";
	$search="&time=$time";
}
//搜索
$sear=$_GET['sear'];
if(empty($sear))
{$sear=$_POST['sear'];}
if($sear)
{
	$keyboard=$_GET['keyboard'];
	if(empty($keyboard))
	{$keyboard=$_POST['keyboard'];}
	$keyboard=RepPostVar2($keyboard);
	$show=$_GET['show'];
	if(empty($show))
	{$show=$_POST['show'];}
	$classid=$_GET['classid'];
	if(empty($classid))
	{$classid=$_POST['classid'];}
	$t=$_GET['t'];
	if(empty($t))
	{$t=$_POST['t'];}
	if($show==1)
	{$where.=" title like '%$keyboard%'";}
	elseif($show==2)
	{$where.=" adsay like '%$keyboard%'";}
	else
	{$where.=" (title like '%$keyboard%' or adsay like '%$keyboard%')";}
	$classid=(int)$classid;
	if($classid)
	{$where.=" and classid='$classid'";}
	$t=(int)$t;
	if($t!=9)
	{$where.=" and t='$t'";}
	$search.="&classid=$classid&show=$show&t=$t&sear=1&keyboard=$keyboard";
	$query.=$where;
}
$num=$empire->num($query);//取得总条数
$query=$query." order by adid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$ty[1]="普通显示";
$ty[2]="";
$ty[3]="可移动透明对话框";
$ty[4]="满屏浮动显示";
$ty[5]="上下浮动显示 - 右";
$ty[6]="上下浮动显示 - 左";
$ty[7]="全屏幕渐隐消失";
$ty[8]="打开新窗口";
$ty[9]="弹出窗口";
$ty[10]="普通网页对话框";
$ty[11]="对联式广告";
$myt[1]="文字广告";
$myt[2]="html广告";
$myt[3]="弹出广告";
$myt[0]="图片与flash广告";
//广告类别
$csql=$empire->query("select classid,classname from {$dbtbpre}enewsadclass");
while($cr=$empire->fetch($csql))
{
	$options.="<option value=".$cr[classid].">".$cr[classname]."</option>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<title>管理广告</title>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="50%" height="25">位置：<a href="ListAd.php">管理广告</a></td>
    <td><div align="right">
        <input type="button" name="Submit5" value="增加广告" onclick="self.location.href='AddAd.php?enews=AddAd';">&nbsp;&nbsp;
        <input type="button" name="Submit52" value="管理过期广告" onclick="self.location.href='ListAd.php?time=1';">&nbsp;&nbsp;
        <input type="button" name="Submit522" value="管理广告分类" onclick="self.location.href='AdClass.php';">
      </div></td>
  </tr>
</table>
<form name="form1" method="post" action="ListAd.php">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <input type=hidden name=time value=<?=$time?>>
    <tr> 
      <td height="25" bgcolor="#FFFFFF">关键字：
        <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
        <input name="show" type="radio" value="0" checked>
        不限 
        <input name="show" type="radio" value="1">
        广告名称 
        <input name="show" type="radio" value="2">
        备注 
        <select name="classid" id="classid">
          <option value="0">不限类别</option>
		  <?=$options?>
        </select>
        <select name="t" id="t">
          <option value="9">不限广告类型</option>
          <option value="0">图片与flash广告</option>
          <option value="1">文字广告</option>
          <option value="2">html广告</option>
          <option value="3">弹出广告</option>
        </select>
        <input type="submit" name="Submit" value="搜索">
        <input name="sear" type="hidden" id="sear" value="1"></td>
    </tr>
    <tr> 
      <td height="25" bgcolor="#FFFFFF"><font color="#666666">说明：调用方式：&lt;script 
        src=广告js地址&gt;&lt;/script&gt;或用标签调用</font></td>
    </tr>
  </table>
</form>
<?
  while($r=$empire->fetch($sql))
  {
  ?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <tr class="header"> 
    <td width="12%" height="25">广告名称：</td>
    <td width="35%" height="25"> 
      <?=$r[title]?>
      </td>
    <td width="15%" height="25">点击： 
      <?=$r[onclick]?>
      </td>
    <td width="38%" height="25">管理：[<a href="../view/js.php?js=<?=$public_r['adfile']?><?=$r[adid]?>&p=acmsd" target="_blank">预览</a>]　[<a href="AddAd.php?enews=EditAd&adid=<?=$r[adid]?>&time=<?=$time?>">修改</a>]　[<a href="ListAd.php?enews=DelAd&adid=<?=$r[adid]?>&time=<?=$time?>" onclick="return confirm('确认要删除？');">删除</a>]</td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="38">广告类型：</td>
    <td height="38"> 
      <?=$myt[$r[t]]?>&nbsp;(<?=$ty[$r[adtype]]?>)
    </td>
    <td rowspan="2">备注:</td>
    <td height="25" rowspan="2"> <textarea name="textarea" cols="42" rows="5"><?=$r[adsay]?></textarea></td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">过期时间：</td>
    <td height="25"> 
      <?=$r[starttime]?>
      &nbsp;-&nbsp; 
      <?=$r[endtime]?>
    </td>
  </tr>
  <tr bgcolor="#FFFFFF"> 
    <td height="25">广告JS地址：</td>
    <td height="25" colspan="3"> <input name="textfield" type="text" value="<?=$public_r[newsurl]?>d/js/acmsd/<?=$public_r['adfile']?><?=$r[adid]?>.js" size="70">
      (ID:<b><?=$r[adid]?></b>)</td>
  </tr>
</table>
<?
  }
  ?>
<br>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>分页：<?=$returnpage?></td>
  </tr>
</table>
</body>
</html>
<?
db_close();
$empire=null;
?>
