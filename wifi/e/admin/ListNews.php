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
$url=AdminReturnClassLink($classid).'&nbsp;>&nbsp;信息列表';
$start=0;
$page=(int)$_GET['page'];
$line=intval($public_r['hlistinfonum']);//每页显示
$page_line=12;
$offset=$page*$line;
$search="&bclassid=$bclassid&classid=$classid";
$add='';
//搜索
$sear=$_GET['sear'];
if($sear)
{
	$keyboard=RepPostVar2($_GET['keyboard']);
	$show=RepPostVar($_GET['show']);
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
	//特殊属性
	$showspecial=$_GET['showspecial'];
	if($showspecial==1)//置顶
	{
		$add.=' and istop<>0';
	}
	elseif($showspecial==2)//推荐
	{
		$add.=' and isgood=1';
	}
	elseif($showspecial==3)//头条
	{
		$add.=' and firsttitle=1';
	}
	elseif($showspecial==4)//未审核
	{
		$add.=' and checked=0';
	}
	elseif($showspecial==5)//签发
	{
		$add.=' and isqf=1';
	}
	elseif($showspecial==6)//已审核
	{
		$add.=' and checked=1';
	}
	//专题
	$ztid=(int)$_GET['ztid'];
	if($ztid)
	{
		$add.=" and ztid like '%|".$ztid."|%'";
	}
	$search.="&sear=1&keyboard=$keyboard&show=$show&showspecial=$showspecial&ztid=$ztid";
}
//显示重复标题
if($_GET['showretitle']==1)
{
	$search.="&showretitle=1&srt=".$_GET['srt'];
	$addsrt="";
	$srtid="";
	$first=1;
	$srtsql=$empire->query("select id,title from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid' group by title having(count(*))>1");
	while($srtr=$empire->fetch($srtsql))
	{
		if($first==1)
		{
			$addsrt.="title='".addslashes(stripSlashes($srtr['title']))."'";
			$srtid.=$srtr['id'];
			$first=0;
		}
		else
		{
			$addsrt.=" or title='".addslashes(stripSlashes($srtr['title']))."'";
			$srtid.=",".$srtr['id'];
		}
	}
	if(!empty($addsrt))
	{
		if($_GET['srt']==1)
		{
			$add.=" and (".$addsrt.") and id not in (".$srtid.")";
		}
		else
		{
			$add.=" and (".$addsrt.")";
		}
	}
	else
	{
		printerror("HaveNotReInfo","ListNews.php?bclassid=$bclassid&classid=$classid");
	}
}
//只能编辑自己的信息
if($doselfinfo['doselfinfo'])
{
	$add.=" and userid='$logininid' and ismember=0";
}
$query="select id,title,checked,ismember,userid,username,plnum,isqf,classid,totaldown,onclick,newstime,titleurl,groupid,newspath,filename,titlepic,havehtml,truetime,lastdotime,istop,isgood,firsttitle from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid'".$add;
$totalquery="select count(*) as total from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where classid='$classid'".$add;
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
if($myorder==1)//时间
{$doorder="newstime desc";}
elseif($myorder==2)//评论数
{$doorder="plnum desc";}
elseif($myorder==3)//人气
{$doorder="onclick desc";}
elseif($myorder==4)//ID号
{$doorder="id desc";}
else//默认排序
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
<title>管理信息</title>
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
    <td width="52%">位置： 
      <?=$url?>
    </td>
    <td width="48%"> <div align="right">[<a href="AddClass.php?enews=EditClass&classid=<?=$classid?>">栏目设置</a>] 
        [<a href="AddInfoClass.php?enews=AddInfoClass&newsclassid=<?=$classid?>">增加采集</a>] 
        [<a href="ListInfoClass.php">管理采集</a>] [<a href="ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?>">刷新所有信息JS</a>]</div></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="searchinfo" method="GET" action="ListNews.php">
    <tr> 
      <td width="43%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="25%"> <div align="left"> 
                <input type=button name=button value="增加信息" onClick="self.location.href='AddNews.php?enews=AddNews&bclassid=<?=$bclassid?>&classid=<?=$classid?>'">
              </div></td>
            <td width="75%"> <div align="right"> 
                <select name="dore">
                  <option value="1">生成当前栏目</option>
                  <option value="2">生成首页</option>
                  <option value="3">生成父栏目</option>
                  <option value="4">生成当前栏目与父栏目</option>
                  <option value="5">生成父栏目与首页</option>
                  <option value="6" selected>生成当前栏目、父栏目与首页</option>
                </select>
                <input type="button" name="Submit12" value="提交" onclick="self.location.href='ecmsinfo.php?enews=AddInfoToReHtml&classid=<?=$classid?>&dore='+document.searchinfo.dore.value;">
              </div></td>
          </tr>
        </table></td>
      <td width="57%"><div align="right">搜索: 
          <select name="showspecial" id="showspecial">
            <option value="0"<?=$showspecial==0?' selected':''?>>不限属性</option>
            <option value="1"<?=$showspecial==1?' selected':''?>>置顶</option>
            <option value="2"<?=$showspecial==2?' selected':''?>>推荐</option>
            <option value="3"<?=$showspecial==3?' selected':''?>>头条</option>
            <option value="4"<?=$showspecial==4?' selected':''?>>未审核</option>
			<option value="6"<?=$showspecial==6?' selected':''?>>已审核</option>
            <option value="5"<?=$showspecial==5?' selected':''?>>签发</option>
          </select>
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>" size="16">
          <select name="show">
            <option value="0" selected>不限字段</option>
            <?=$searchoptions_r['select']?>
          </select>
          <select name="ztid" id="ztid">
            <option value="0">所有专题</option>
            <?=$ztclass?>
          </select>
          <input type="submit" name="Submit2" value="搜索">
          <input name="sear" type="hidden" id="sear" value="1">
          <input name="bclassid" type="hidden" id="bclassid" value="<?=$bclassid?>">
          <input name="classid" type="hidden" id="classid" value="<?=$classid?>">
        </div></td>
    </tr>
  </form>
</table>
<form name="listform" method="post" action="ecmsinfo.php" onsubmit="return confirm('确认要执行此操作？');">
<input type=hidden name=classid value=<?=$classid?>>
<input type=hidden name=bclassid value=<?=$bclassid?>>
<input type=hidden name=enews value=DelNews_all>
<input type=hidden name=isgood value=0>
<input type=hidden name=doing value=0>
  <input name="ecmsdoc" type="hidden" id="ecmsdoc" value="0">
  <input name="docfrom" type="hidden" id="docfrom" value="<?=$phpmyself?>">
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  	<tr class="header"> 
      <td height="25" colspan="8"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="46%"><font color="#ffffff">[<a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?>&showretitle=1&srt=1" title="查询重复标题，并保留一条信息">查询重复标题A</a>]&nbsp;[<a href="ListNews.php?bclassid=<?=$bclassid?>&classid=<?=$classid?>&showretitle=1&srt=0" title="查询重复标题的信息(不保留信息)">查询重复标题B</a>]&nbsp;[<a href="ecmsinfo.php?bclassid=<?=$bclassid?>&classid=<?=$classid?>&enews=SetAllCheckInfo" title="本栏目所有信息全设为审核状态" onclick="return confirm('确认要操作?');">审核本栏目全部信息</a>]</font> 
            </td>
            <td width="54%"> <div align="right"><font color="#ffffff">[<a href="file/ListFile.php?type=9&classid=<?=$classid?>">附件管理</a>]&nbsp;[<a href="ListInfoDoc.php?bclassid=<?=$bclassid?>&classid=<?=$classid?>">管理归档</a>]&nbsp;[<a href=ReHtml/ChangeData.php target=_blank>更新数据</a>]&nbsp;[<a href=../../ target=_blank>预览首页</a>]&nbsp;[<a href="<?=$classurl?>" target=_blank>预览栏目</a>]</font></div></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td width="3%"><div align="center"></div></td>
      <td width="7%" height="25"><div align="center"><a href='ListNews.php?<?=$search1?>&myorder=4'><u>ID</u></a></div></td>
      <td width="36%" height="25"> <div align="center">标题</div></td>
      <td width="15%" height="25"><div align="center">发布者</div></td>
      <td width="8%" height="25"><div align="center"><a href='ListNews.php?<?=$search1?>&myorder=3'><u>点击</u></a></div></td>
      <td width="17%" height="25"> <div align="center"><a href='ListNews.php?<?=$search1?>&myorder=1'><u>发布时间</u></a></div></td>
      <td width="7%"><div align="center"><a href='ListNews.php?<?=$search1?>&myorder=2'><u>评论</u></a></div></td>
      <td width="7%" height="25"> <div align="center">操作</div></td>
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
			$titleurl="ShowInfo.php?classid=$r[classid]&id=$r[id]";
		}
		else
		{
			$checked="";
			$titleurl=sys_ReturnBqTitleLink($r);
		}
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
		$myid="<a href='ecmschtml.php?enews=ReSingleInfo&classid=$r[classid]&id[]=".$r[id]."'>".$r['id']."</a>";
		if(empty($r[havehtml]))
		{
			$myid="<a href='ecmschtml.php?enews=ReSingleInfo&classid=$r[classid]&id[]=".$r[id]."' title='未生成'><b>".$r[id]."</b></a>";
		}
	?>
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#DBEAF5'" id=news<?=$r[id]?>> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>"<?=$checked?>>
        </div></td>
      <td height="27"> <div align="center"> 
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
      <td height="25"> <div align="center"> <a title="下载次数:<?=$r[totaldown]?>"> 
          <?=$r[onclick]?>
          </a> </div></td>
      <td height="25"> <div align="center"> <a href="AddNews.php?enews=EditNews&id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?>" title="<? echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>"> 
          <?=date("Y-m-d H:i:s",$r[newstime])?>
          </a> </div></td>
      <td><div align="center"><a href="pl/ListPl.php?id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?>" title="管理评论"><u> 
          <?=$r[plnum]?>
          </u></a></div></td>
      <td height="25"> <div align="center"> <a href="AddNews.php?enews=EditNews&id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?>"><img src='../data/images/EditNews.png' alt='修改' title='修改' border=0></a>&nbsp;&nbsp; 
          <a href="ecmsinfo.php?enews=DelNews&id=<?=$r[id]?>&classid=<?=$classid?>&bclassid=<?=$bclassid?>" onClick="return confirm('确认要删除？');"><img src='../data/images/DelNews.png' alt='删除' title='删除' border=0></a>&nbsp; 
        </div></td>
    </tr>
    <?
	}
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"> <div align="center">
          <input type=checkbox name=chkall value=on onClick="CheckAll(this.form)">
        </div></td>
      <td height="25" colspan="7"><div align="right">
          <input type="submit" name="Submit3" value="删除" onClick="document.listform.enews.value='DelNews_all';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit8" value="审核" onClick="document.listform.enews.value='CheckNews_all';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit9" value="取消审核" onClick="document.listform.enews.value='NoCheckNews_all';document.listform.action='ecmsinfo.php';">
		  <input type="submit" name="Submit8" value="刷新" onClick="document.listform.enews.value='ReSingleInfo';document.listform.action='ecmschtml.php';">
          <input type="submit" name="Submit82" value="推荐" onClick="document.listform.enews.value='GoodInfo_all';document.listform.isgood.value='1';document.listform.doing.value='0';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit822" value="取消推荐" onClick="document.listform.enews.value='GoodInfo_all';document.listform.isgood.value='0';document.listform.doing.value='0';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit823" value="头条" onClick="document.listform.enews.value='GoodInfo_all';document.listform.isgood.value='1';document.listform.doing.value='1';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit8222" value="取消头条" onClick="document.listform.enews.value='GoodInfo_all';document.listform.isgood.value='0';document.listform.doing.value='1';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit11" value="归档" onClick="document.listform.enews.value='InfoToDoc';document.listform.doing.value='0';document.listform.action='ecmsinfo.php';">
          <select name="istop" id="select">
            <option value="0">0级置顶</option>
            <option value="1">1级置顶</option>
            <option value="2">2级置顶</option>
            <option value="3">3级置顶</option>
            <option value="4">4级置顶</option>
            <option value="5">5级置顶</option>
            <option value="6">6级置顶</option>
          </select>
          <input type="submit" name="Submit7" value="置顶" onClick="document.listform.enews.value='TopNews_all';document.listform.action='ecmsinfo.php';">
          <select name="ztid">
            <option value="0">选择专题</option>
            <?=$doztclass?>
          </select>
          <input type="submit" name="Submit52" value="转移" onClick="document.listform.enews.value='DoZtNews_all';document.listform.action='ecmsinfo.php';">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8"> <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td width="64%"> 
              <?=$returnpage?>
            </td>
            <td width="36%"> <div align="right">
			<span id="moveclassnav"></span>
                <input type="submit" name="Submit5" value="移动" onClick="document.listform.enews.value='MoveNews_all';document.listform.action='ecmsinfo.php';">
                <input type="submit" name="Submit6" value="复制" onClick="document.listform.enews.value='CopyNews_all';document.listform.action='ecmsinfo.php';">
              </div></td>
          </tr>
        </table></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="8"> <font color="#666666">备注：多选框为蓝色代表未审核信息,置顶级别越高越前面,会员投稿作者为红色,签发信息标题后有说明,未生成的信息ID为粗体.</font></td>
    </tr>
  </table>
</form>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=3" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?
db_close();
$empire=null;
?>
