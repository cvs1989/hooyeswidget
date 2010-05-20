<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require LoadLang("pub/fun.php");
require("../data/dbcache/class.php");
$link=db_connect();
$empire=new mysqlquery();
//验证用户
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

$classid=(int)$_GET['classid'];
$bclassid=(int)$class_r[$classid]['bclassid'];
//取得栏目名
if(!$class_r[$classid][classid]||!$class_r[$classid][tbname])
{
	printerror("ErrorUrl","history.go(-1)");
}
//验证权限
$doselfinfo=CheckLevel($logininid,$loginin,$classid,"news");
//取得模型表
$fieldexp="<!--field--->";
$recordexp="<!--record-->";
//返回搜索字段列表
function ReturnSearchOptions($enter,$field,$record){
	global $modid,$emod_r;
	$r=explode($record,$enter);
	$count=count($r)-1;
	for($i=0;$i<$count;$i++)
	{
		if($i==0)
		{
			$or="";
		}
		else
		{
			$or=" or ";
		}
		$r1=explode($field,$r[$i]);
		if($r1[1]=="special.field"||strstr($emod_r[$modid]['tbdataf'],','.$r1[1].','))
		{
			continue;
		}
		if($r1[1]=="id")
		{
			$sr['searchallfield'].=$or.$r1[1]."='[!--key--]'";
			$sr['select'].="<option value=\"".$r1[1]."\">".$r1[0]."</option>";
			continue;
		}
		$sr['searchallfield'].=$or.$r1[1]." like '%[!--key--]%'";
		$sr['select'].="<option value=\"".$r1[1]."\">".$r1[0]."</option>";
	}
	return $sr;
}
$modid=(int)$class_r[$classid][modid];
$infomod_r=$empire->fetch1("select enter,tbname from {$dbtbpre}enewsmod where mid=".$modid);
if(empty($infomod_r['tbname']))
{
	printerror("ErrorUrl","history.go(-1)");
}
$infomod_r['enter'].='发布者<!--field--->username<!--record-->ID<!--field--->id<!--record-->';
$searchoptions_r=ReturnSearchOptions($infomod_r['enter'],$fieldexp,$recordexp);
//导航
$url=AdminReturnClassLink($classid).'&nbsp;>&nbsp;管理归档&nbsp;&nbsp;(<a href="AddNews.php?enews=AddNews&bclassid='.$bclassid.'&classid='.$classid.'">增加信息</a>)';
$start=0;
$page=(int)$_GET['page'];
$line=intval($public_r['hlistinfonum']);//每页显示
$page_line=16;
$offset=$page*$line;
$search="&bclassid=$bclassid&classid=$classid";
$add='';
//搜索
$sear=$_GET['sear'];
if($sear)
{
	$keyboard=RepPostVar2($_GET['keyboard']);
	$show=RepPostVar($_GET['show']);
	//审核
	$schecked=$_GET['schecked'];
	//关键字
	if($keyboard)
	{
		//搜索全部
		if(!$show)
		{
			$add=" and (".str_replace("[!--key--]",$keyboard,$searchoptions_r['searchallfield']).")";
		}
		//搜索字段
		elseif($show&&strstr($infomod_r['enter'],"<!--field--->".$show."<!--record-->"))
		{
			$add=$show!="id"?" and (".$show." like '%$keyboard%')":" and (".$show."='$keyboard')";
			$searchoptions_r['select']=str_replace(" value=\"".$show."\">"," value=\"".$show."\" selected>",$searchoptions_r['select']);
		}
	}
	//审核
	if($schecked==2)
	{
		$add.=" and checked=0";
	}
	elseif($schecked==1)
	{
		$add.=" and checked=1";
	}
	//专题
	$ztid=(int)$_GET['ztid'];
	if($ztid)
	{
		$add.=" and ztid like '%|".$ztid."|%'";
	}
	$search.="&sear=1&keyboard=$keyboard&show=$show&schecked=$schecked&ztid=$ztid";
}
//只能编辑自己的信息
if($doselfinfo['doselfinfo'])
{
	$add.=" and userid='$logininid' and ismember=0";
}
$query="select id,title,checked,ismember,username,plnum,isqf,classid,totaldown,onclick,newstime,titleurl,groupid,newspath,filename,titlepic,havehtml,truetime,lastdotime,istop,isgood,firsttitle from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_doc where classid='$classid'".$add;
$totalquery="select count(id) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_doc where classid='$classid'".$add;
$totalnum=(int)$_GET['totalnum'];
if(empty($totalnum))
{
	$num=$empire->gettotal($totalquery);//取得总条数
}
else
{
	$num=$totalnum;
}
//排序
$myorder=$_GET['myorder'];
//时间
if($myorder==1)
{$doorder="newstime desc";}
//评论数
elseif($myorder==2)
{$doorder="plnum desc";}
//人气
elseif($myorder==3)
{$doorder="onclick desc";}
//ID号
elseif($myorder==4)
{$doorder="id desc";}
//默认排序
else
{
	$thisclassr=$empire->fetch1("select listorderf,listorder from {$dbtbpre}enewsclass where classid='$classid'");
	if(empty($thisclassr[listorderf]))
	{
		$doorder="id desc";
	}
	else
	{
		$doorder=$thisclassr[listorderf]." ".$thisclassr[listorder];
	}
}
$search.="&totalnum=$num";
$search1=$search;
$search.="&myorder=$myorder";
$query.=" order by ".$doorder." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
//--------------------操作的栏目
$fcfile="../data/fc/ListEnews.php";
$do_class="<script src=../data/fc/cmsclass.js></script>";
if(!file_exists($fcfile))
{$do_class=ShowClass_AddClass("","n",0,"|-",$modid,4);}
$phpmyself=urlencode($_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"]);
//取得专题
$ztclass="";
$doztclass="";
$ztwhere=ReturnClass($class_r[$classid][featherclass]);
$z_sql=$empire->query("select ztname,ztid,tbname from {$dbtbpre}enewszt where classid=0 or classid='$classid' or (".$ztwhere.")");
while($z_r=$empire->fetch($z_sql))
{
	/*
	//不同表
	if($class_r[$classid][tbname]!=$z_r[tbname])
	{continue;}
	*/
	$selected="";
	if($z_r[ztid]==$ztid)
	{
		$selected=" selected";
	}
	$ztclass.="<option value='".$z_r[ztid]."'".$selected.">".$z_r[ztname]."</option>";
	$doztclass.="<option value='".$z_r[ztid]."'>".$z_r[ztname]."</option>";
}
//栏目链接
$getcurlr['classid']=$classid;
$classurl=sys_ReturnBqClassname($getcurlr,9);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" type="text/css">
<title>管理归档</title>
<script>
function CheckAll(form)
  {
  for (var i=0;i<form.elements.length;i++)
    {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
  }
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td>位置: <?=$url?></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <form name="form2" method="GET" action="ListInfoDoc.php">
    <tr> 
      <td colspan="2"><table width="100%" border="0" cellpadding="3" cellspacing="1" class="tableborder">
          <tr class="header"> 
            <td><div align="center"> 
                <input type=button name=button value=增加信息 onClick="self.location.href='AddNews.php?enews=AddNews&bclassid=<?=$bclassid?>&classid=<?=$classid?>'">
              </div></td>
            <td><div align="center"> 
                <input type="button" name="Submit" value="刷新首页" onclick="self.location.href='ecmschtml.php?enews=ReIndex'">
              </div></td>
            <td><div align="center"> 
                <input type="button" name="Submit22" value="刷新本栏目" onclick="self.location.href='enews.php?enews=ReListHtml&classid=<?=$classid?>'">
              </div></td>
            <td><div align="center"> 
                <input type="button" name="Submit4" value="刷新所有信息JS" onclick="window.open('ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?>','','');">
              </div></td>
            <td><div align="center"> 
                <input type="button" name="Submit10" value="栏目设置" onclick="self.location.href='AddClass.php?enews=EditClass&classid=<?=$classid?>'">
              </div></td>
            <td><div align="center"> 
                <input type="button" name="Submit102" value="增加采集节点" onclick="self.location.href='AddInfoClass.php?enews=AddInfoClass&newsclassid=<?=$classid?>'">
              </div></td>
            <td><div align="center"> 
                <input type="button" name="Submit103" value="管理采集节点" onclick="self.location.href='ListInfoClass.php'">
              </div></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td width="43%"><div align="left">[<a href="InfoDoc.php" target="_blank">批量归档信息</a>]&nbsp;[<a href=ReHtml/ChangeData.php target=_blank>更新数据</a>]&nbsp;[<a href=../../ target=_blank>预览首页</a>]&nbsp;[<a href="<?=$classurl?>" target=_blank>预览栏目</a>]</div></td>
      <td width="57%"><div align="right">
<input name="keyboard" type="text" id="keyboard2" value="<?=$keyboard?>" size="16">
          <select name="show">
            <option value="0" selected>不限字段</option>
            <?=$searchoptions_r['select']?>
          </select>
          <select name="schecked" id="select">
            <option value="0">不限</option>
            <option value="1">审核</option>
            <option value="2">未审核</option>
          </select>
          <select name="ztid" id="select2">
            <option value="0">所有专题</option>
            <?=$ztclass?>
          </select>
          <input type="submit" name="Submit2" value="搜索">
          <input name="sear" type="hidden" id="sear2" value="1">
          <input name="bclassid" type="hidden" id="bclassid" value="<?=$bclassid?>">
          <input name="classid" type="hidden" id="classid" value="<?=$classid?>">
        </div></td>
    </tr>
  </form>
</table>
<form name="listform" method="post" action="ecmsinfo.php" onsubmit="return confirm('确认要执行此操作？');">
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=bclassid value=<?=$bclassid?>>
  <input type=hidden name=enews value=DelInfoDoc_all>
  <input type=hidden name=doing value=1>
  <input name="ecmsdoc" type="hidden" id="ecmsdoc" value="0">
  <input name="docfrom" type="hidden" id="docfrom" value="<?=$phpmyself?>">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td width="7%" height="25"><div align="center"><a href='ListInfoDoc.php?<?=$search1?>&myorder=4'><u>ID</u></a></div></td>
      <td width="47%" height="25"> <div align="center">标题</div></td>
      <td width="18%" height="25"><div align="center">发布者</div></td>
      <td width="22%" height="25"> <div align="center"><a href='ListInfoDoc.php?<?=$search1?>&myorder=1'><u>发布时间</u></a></div></td>
      <td width="6%" height="25"> <div align="center">选择</div></td>
    </tr>
    <?php
	while($r=$empire->fetch($sql))
	{
		//状态
		$st='';
		if($r[istop])//置顶
		{
			$st.="<font color=red>[顶".$r[istop]."]</font>";
		}
		if($r[isgood])//推荐
		{
			$st.="<font color=red>[推]</font>";
		}
		if($r[firsttitle])//头条
		{
			$st.="<font color=red>[头]</font>";
		}
		//时间
		$truetime=date("Y-m-d H:i:s",$r[truetime]);
		$lastdotime=date("Y-m-d H:i:s",$r[lastdotime]);
		$oldtitle=$r[title];
		$r[title]=stripSlashes(sub($r[title],0,50,false));
		//审核
		if(empty($r[checked]))
		{
			$checked=" title='未审核' style='background:#99C4E3'";
		}
		else
		{
			$checked="";
		}
		$titleurl=sys_ReturnBqTitleLink($r);
		//会员投稿
		if($r[ismember])
		{
			$r[username]="<a href='member/AddMember.php?enews=EditMember&userid=".$r[userid]."' target='_blank'><font color=red>".$r[username]."</font></a>";
		}
		//签发
		$qf="";
		if($r[isqf])
		{
			$qfr=$empire->fetch1("select checkuser,docheckuser,returncheck from {$dbtbpre}enewsqf where id='$r[id]' and classid='$r[classid]' limit 1");
			if($qfr[returncheck])
			{
				$qf="(<font color='red'>已退稿</font>)";
			}
			elseif(strlen($qfr[checkuser])==strlen($qfr[docheckuser]))
			{
				$qf="(<font color='red'>已签发</font>)";
			}
			else
			{
				$qf="(<font color='red'>签发中</font>)";
			}
			$qf="<a href='#ecms' onclick=\"window.open('DoNewsQf.php?classid=$r[classid]&id=$r[id]','','width=600,height=520,scrollbars=yes');\">".$qf."</a>";
		}
		//标题图片
		$showtitlepic="";
		if($r[titlepic])
		{
			$showtitlepic="<a href='".$r[titlepic]."' title='预览标题图片' target=_blank><img src='../data/images/showimg.gif' border=0></a>";
		}
		//未生成
		$myid=$r['id'];
		if(empty($r[havehtml]))
		{
		$myid="<a title='未生成'><b>".$r[id]."</b></a>";
		}
	?>
    <tr bgcolor="#FFFFFF" id=news<?=$r[id]?> onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#DBEAF5'"> 
      <td height="25"> <div align="center"> 
          <?=$myid?>
        </div></td>
      <td height="25"> <div align="left"> 
          <?=$st?>
          <?=$showtitlepic?>
          <a href='<?=$titleurl?>' target=_blank title="<?=$oldtitle?>"> 
          <?=$r[title]?>
          </a> 
          <?=$qf?>
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$r[username]?>
        </div></td>
      <td height="25"> <div align="center"> <a href="#ecms" title="<? echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>"> 
          <?=date("Y-m-d H:i:s",$r[newstime])?>
          </a> </div></td>
      <td height="25"> <div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>"<?=$checked?>>
        </div></td>
    </tr>
    <?
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="4"> <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="83%"> 
              <?=$returnpage?>
            </td>
            <td width="17%"><div align="right"> 
                <input type="submit" name="Submit3" value="删除" onClick="document.listform.enews.value='DelInfoDoc_all';document.listform.action='ecmsinfo.php';">
                <input type="submit" name="Submit11" value="还原归档" onClick="document.listform.enews.value='InfoToDoc';document.listform.doing.value='1';document.listform.action='ecmsinfo.php';">
              </div></td>
          </tr>
        </table></td>
      <td height="25"><div align="center">
          <input type=checkbox name=chkall value=on onClick=CheckAll(this.form)>
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="5"> 备注：多选框为蓝色代表未审核信息,会员投稿作者为红色,未生成的信息ID为粗体.</td>
    </tr>
  </table>
</form>
</body>
</html>
<?
db_close();
$empire=null;
?>
