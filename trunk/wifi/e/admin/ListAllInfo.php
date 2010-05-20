<?php
require('../class/connect.php');
require('../class/db_sql.php');
require('../class/functions.php');
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
//取得数据表
$tbname=$_GET['tbname']?$_GET['tbname']:$public_r['tbname'];
$tbname=RepPostVar($tbname);
$changetbs='';
$havetb=0;
$tbsql=$empire->query("select tbname,tname from {$dbtbpre}enewstable order by tid");
while($tbr=$empire->fetch($tbsql))
{
	$selected='';
	if($tbname==$tbr[tbname])
	{
		$selected=' selected';
		$havetb=1;
	}
	$changetbs.="<option value='".$tbr[tbname]."'".$selected.">".$tbr[tname]."(".$tbr[tbname].")</option>";
}
if($havetb==0)
{
	printerror('ErrorUrl','');
}
//取得相应的信息
$user_r=$empire->fetch1("select groupid,adminclass from {$dbtbpre}enewsuser where userid='$logininid'");
//取得用户组
$gr=$empire->fetch1("select doall,doselfinfo from {$dbtbpre}enewsgroup where groupid='$user_r[groupid]'");
//管理员
$where='';
$and='';
if(!$gr['doall'])
{
	$cids='';
	$a=explode("|",$user_r['adminclass']);
	for($i=1;$i<count($a)-1;$i++)
	{
		$dh=',';
		if(empty($cids))
		{
			$dh='';
		}
		$cids.=$dh.$a[$i];
	}
	if($cids=='')
	{
		$cids=0;
	}
	$where=' where classid in ('.$cids.')';
}
//只能编辑自己的信息
if($gr['doselfinfo'])
{
	$and=$where?' and ':' where ';
	$where.=$and."userid='$logininid' and ismember=0";
}
$url="<a href=ListAllInfo.php?tbname=".$tbname.">管理信息</a>";
$start=0;
$page=(int)$_GET['page'];
$search="&tbname=$tbname";
$line=intval($public_r['hlistinfonum']);//每页显示
$page_line=21;
$offset=$page*$line;
//栏目ID
$classid=intval($_GET['classid']);
if($classid)
{
	$and=$where?' and ':' where ';
	if($class_r[$classid][islast])
	{
		$where.=$and."classid='$classid'";
	}
	else
	{
		$where.=$and."(".ReturnClass($class_r[$classid][sonclass]).")";
	}
	$search.="&classid=$classid";
}
//专题ID
$ztid=intval($_GET['ztid']);
if($ztid)
{
	$and=$where?' and ':' where ';
	$where.=$and."ztid like '%|".$ztid."|%'";
	$search.="&ztid=$ztid";
}
//取得专题
$ztclass="";
$doztclass="";
$z_sql=$empire->query("select ztname,ztid from {$dbtbpre}enewszt where tbname='$tbname' order by ztid desc");
while($z_r=$empire->fetch($z_sql))
{
	$selected="";
	if($z_r[ztid]==$ztid)
	{
		$selected=" selected";
	}
	$ztclass.="<option value='".$z_r[ztid]."'".$selected.">".$z_r[ztname]."</option>";
	$doztclass.="<option value='".$z_r[ztid]."'>".$z_r[ztname]."</option>";
}
//搜索
$sear=$_GET['sear'];
if($sear)
{
	$and=$where?' and ':' where ';
	$showspecial=$_GET['showspecial'];
	if($showspecial==1)//置顶
	{
		$where.=$and.'istop<>0';
	}
	elseif($showspecial==2)//推荐
	{
		$where.=$and.'isgood=1';
	}
	elseif($showspecial==3)//头条
	{
		$where.=$and.'firsttitle=1';
	}
	elseif($showspecial==4)//未审核
	{
		$where.=$and.'checked=0';
	}
	elseif($showspecial==5)//签发
	{
		$where.=$and.'isqf=1';
	}
	elseif($showspecial==6)//已审核
	{
		$where.=$and.'checked=1';
	}
	$and=$where?' and ':' where ';
	if($_GET['keyboard'])
	{
		$keyboard=RepPostVar2($_GET['keyboard']);
		$show=$_GET['show'];
		if($show==0)//搜索全部
		{
			$where.=$and."(title like '%$keyboard%' or username like '%$keyboard%' or id='$keyboard')";
		}
		elseif($show==1)//搜索标题
		{
			$where.=$and."(title like '%$keyboard%')";
		}
		elseif($show==3)//ID
		{
			$where.=$and."(id='$keyboard')";
		}
		else
		{
			$where.=$and."(username like '%$keyboard%')";
		}
	}
	$search.="&sear=1&keyboard=$keyboard&show=$show&showspecial=$showspecial";
}
//显示重复标题
if($_GET['showretitle']==1)
{
	$and=$where?' and ':' where ';
	$search.="&showretitle=1&srt=".$_GET['srt'];
	$addsrt="";
	$srtid="";
	$first=1;
	$srtsql=$empire->query("select id,title from {$dbtbpre}ecms_".$tbname." group by title having(count(*))>1");
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
			$where.=$and."(".$addsrt.") and id not in (".$srtid.")";
		}
		else
		{
			$where.=$and."(".$addsrt.")";
		}
	}
	else
	{
		printerror("HaveNotReInfo","ListAllInfo.php?tbname=".$tbname);
	}
}
//排序
$orderby=$_GET['orderby'];
$doorderby=$orderby?'asc':'desc';
$myorder=$_GET['myorder'];
if($myorder==1)//ID号
{$doorder="id";}
elseif($myorder==2)//时间
{$doorder="newstime";}
elseif($myorder==5)//评论数
{$doorder="plnum";}
elseif($myorder==3)//人气
{$doorder="onclick";}
elseif($myorder==4)//下载
{$doorder="totaldown";}
else//默认排序
{$doorder="id";}
$doorder.=' '.$doorderby;
$search.="&myorder=$myorder&orderby=$orderby";
$totalquery="select count(*) as total from {$dbtbpre}ecms_".$tbname.$where;
//取得总条数
$totalnum=intval($_GET['totalnum']);
if(empty($totalnum))
{
	$num=$empire->gettotal($totalquery);
}
else
{
	$num=$totalnum;
}
$search1=$search;
$search.="&totalnum=$num";
$query="select id,title,checked,ismember,userid,username,plnum,isqf,classid,totaldown,onclick,newstime,titleurl,groupid,newspath,filename,titlepic,havehtml,truetime,lastdotime,istop,isgood,firsttitle from {$dbtbpre}ecms_".$tbname.$where;
$query.=" order by ".$doorder." limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
$phpmyself=urlencode($_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"]);
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
<form name="AddNewsForm" method="get">
  <tr> 
    <td width="37%">位置： 
      <?=$url?>
    </td>
    <td width="63%"><div align="right">&nbsp;&nbsp; 
          <input type="button" name="Submit4" value="刷新首页" onclick="self.location.href='ecmschtml.php?enews=ReIndex'">
          &nbsp; 
          <input type="button" name="Submit4" value="刷新所有信息JS" onclick="window.open('ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?>','','');">
		  &nbsp;
		  <span id="showaddclassnav"></span>
          <input type="button" name="Submit" value="增加信息" onclick="if(document.AddNewsForm.addclassid.value!=0){window.open('AddNews.php?enews=AddNews&classid='+document.AddNewsForm.addclassid.value,'','');}else{alert('请选择要增加信息的栏目');document.AddNewsForm.addclassid.focus();}">
        </div></td>
  </tr>
</form>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="SearchForm" method="GET" action="ListAllInfo.php">
    <tr> 
      <td width="100%"> <div align="right">&nbsp;搜索： 
          <select name="showspecial" id="showspecial">
            <option value="0"<?=$showspecial==0?' selected':''?>>不限属性</option>
            <option value="1"<?=$showspecial==1?' selected':''?>>置顶</option>
            <option value="2"<?=$showspecial==2?' selected':''?>>推荐</option>
            <option value="3"<?=$showspecial==3?' selected':''?>>头条</option>
            <option value="4"<?=$showspecial==4?' selected':''?>>未审核</option>
			<option value="6"<?=$showspecial==6?' selected':''?>>已审核</option>
            <option value="5"<?=$showspecial==5?' selected':''?>>签发</option>
          </select>
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show">
            <option value="0"<?=$show==0?' selected':''?>>不限字段</option>
            <option value="1"<?=$show==1?' selected':''?>>标题</option>
            <option value="2"<?=$show==2?' selected':''?>>发布者</option>
			<option value="3"<?=$show==3?' selected':''?>>ID</option>
          </select>
          <select name="ztid" id="ztid">
            <option value="0">所有专题</option>
            <?=$ztclass?>
          </select>
		  <span id="searchclassnav"></span>
          <select name="myorder" id="myorder">
            <option value="1"<?=$myorder==1?' selected':''?>>按信息ID</option>
            <option value="2"<?=$myorder==2?' selected':''?>>按发布时间</option>
            <option value="3"<?=$myorder==3?' selected':''?>>按点击率</option>
            <option value="4"<?=$myorder==4?' selected':''?>>按下载数</option>
            <option value="5"<?=$myorder==5?' selected':''?>>按评论数</option>
          </select>
          <select name="orderby" id="orderby">
            <option value="0"<?=$orderby==0?' selected':''?>>降序排序</option>
            <option value="1"<?=$orderby==1?' selected':''?>>升序排序</option>
          </select>
          <input type="submit" name="Submit2" value="搜索">
          <input name="tbname" type="hidden" value="<?=$tbname?>">
          <input name="sear" type="hidden" value="1">
        </div></td>
    </tr>
  </form>
</table>
<form name="listform" method="post" action="ecmsinfo.php" onsubmit="return confirm('确认要执行此操作？');">
  <input type=hidden name=enews value=DelNews_all>
  <input name=mid type=hidden id="mid" value=<?=$mid?>>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td width="46%"><select name="tbname" onchange="if(this.options[this.selectedIndex].value!=0){self.location.href='ListAllInfo.php?<?=str_replace('&tbname=','&',$search1)?>&tbname='+this.options[this.selectedIndex].value;}">
                <?=$changetbs?>
              </select> </td>
            <td width="54%"> <div align="right"><font color="#ffffff">[<a href="ListAllInfo.php?tbname=<?=$tbname?>&showretitle=1&srt=1" title="查询重复标题，并保留一条信息">查询重复标题A</a>]&nbsp;[<a href="ListAllInfo.php?tbname=<?=$tbname?>&showretitle=1&srt=0" title="查询重复标题的信息(不保留信息)">查询重复标题B</a>]&nbsp;[<a href="ReHtml/ChangeData.php" target=_blank>更新数据</a>]&nbsp;[<a href="../../" target=_blank>预览首页</a>]</font></div></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td width="3%"><div align="center"></div></td>
      <td width="7%" height="25"><div align="center">ID</div></td>
      <td width="38%" height="25"><div align="center">标题</div></td>
      <td width="15%" height="25"><div align="center">发布者</div></td>
      <td width="8%" height="25"><div align="center">点击</div></td>
      <td width="17%" height="25"> <div align="center">发布时间</div></td>
      <td width="12%" height="25"> <div align="center">操作</div></td>
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
		$oldtitle=$r[title];
		$r[title]=stripSlashes(sub($r[title],0,36,false));
		//时间
		$truetime=date("Y-m-d H:i:s",$r[truetime]);
		$lastdotime=date("Y-m-d H:i:s",$r[lastdotime]);
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
		//取得类别名
		$do=$r[classid];
		$dob=$class_r[$r[classid]][bclassid];
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
    <tr bgcolor="#FFFFFF" onmouseout="this.style.backgroundColor='#ffffff'" onmouseover="this.style.backgroundColor='#DBEAF5'"> 
      <td><div align="center"> 
          <input name="id[]" type="checkbox" id="id[]" value="<?=$r[id]?>"<?=$checked?>>
        </div></td>
      <td height="25"> <div align="center"> 
          <?=$myid?>
        </div></td>
      <td height="25"> <div align="left">
		<?=$st?>
        <?=$showtitlepic?>
		<a href='<?=$titleurl?>' target=_blank title="<?=$oldtitle?>"><?=$r[title]?></a>
        <?=$qf?>
          <br>
          <font color="#574D5C">栏目:<a href='ListNews.php?bclassid=<?=$class_r[$r[classid]][bclassid]?>&classid=<?=$r[classid]?>'> 
          <font color="#574D5C"><?=$class_r[$dob][classname]?></font>
          </a> > <a href='ListNews.php?bclassid=<?=$class_r[$r[classid]][bclassid]?>&classid=<?=$r[classid]?>'> 
          <font color="#574D5C"><?=$class_r[$r[classid]][classname]?></font>
          </a></font></div></td>
      <td height="25"> <div align="center"> 
          <?=$r[username]?>
        </div></td>
      <td height="25"> <div align="center"><a title="下载次数:<?=$r[totaldown]?>"> 
          <?=$r[onclick]?>
        </a></div></td>
      <td height="25"> <div align="center"> 
          <a href="AddNews.php?enews=EditNews&id=<?=$r[id]?>&classid=<?=$r[classid]?>&bclassid=<?=$class_r[$r[classid]][bclassid]?>" title="<? echo"增加时间：".$truetime."\r\n最后修改：".$lastdotime;?>" target="_blank"><?=date("Y-m-d H:i:s",$r[newstime])?></a>
        </div></td>
      <td height="25"> <div align="center">[<a href="pl/ListPl.php?id=<?=$r[id]?>&classid=<?=$r[classid]?>&bclassid=<?=$class_r[$r[classid]][bclassid]?>" target="_blank">评论</a>]&nbsp;&nbsp; 
          <a href="AddNews.php?enews=EditNews&id=<?=$r[id]?>&classid=<?=$r[classid]?>&bclassid=<?=$class_r[$r[classid]][bclassid]?>" target="_blank"><img src=../data/images/EditNews.png alt='修改' title='修改信息' border=0></a>&nbsp;&nbsp; 
          <a href="ecmsinfo.php?enews=DelNews&id=<?=$r[id]?>&classid=<?=$r[classid]?>&bclassid=<?=$class_r[$r[classid]][bclassid]?>" onclick="return confirm('确认要删除？');"><img src=../data/images/DelNews.png alt='删除' title='删除信息' border=0></a> 
        </div></td>
    </tr>
    <?
	}
	?>
    <input type=hidden name=classid value=<?=$do?>>
    <input type=hidden name=bclassid value=<?=$dob?>>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"> <div align="center">
          <input type=checkbox name=chkall value=on onclick=CheckAll(this.form)>
        </div></td>
      <td height="25" colspan="6"><div align="right">
          <input type="submit" name="Submit3" value="删除" onclick="document.listform.enews.value='DelNews_all';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit8" value="审核" onClick="document.listform.enews.value='CheckNews_all';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit9" value="取消审核" onClick="document.listform.enews.value='NoCheckNews_all';document.listform.action='ecmsinfo.php';">
		  <input type="submit" name="Submit8" value="刷新" onClick="document.listform.enews.value='ReSingleInfo';document.listform.action='ecmschtml.php';">
          <select name="istop" id="select2">
            <option value="0">0级置顶</option>
            <option value="1">1级置顶</option>
            <option value="2">2级置顶</option>
            <option value="3">3级置顶</option>
            <option value="4">4级置顶</option>
            <option value="5">5级置顶</option>
            <option value="6">6级置顶</option>
          </select>
          <input type="submit" name="Submit7" value="置顶" onclick="document.listform.enews.value='TopNews_all';document.listform.action='ecmsinfo.php';">
		  <span id="moveclassnav"></span>
          <input type="submit" name="Submit5" value="移动" onclick="document.listform.enews.value='MoveNews_all';document.listform.action='ecmsinfo.php';">
          <input type="submit" name="Submit6" value="复制" onclick="document.listform.enews.value='CopyNews_all';document.listform.action='ecmsinfo.php';">
        </div></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="7"> 
        <?=$returnpage?>
        　 </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25" colspan="7"> <font color="#666666">备注：多选框为蓝色代表未审核信息，置顶级别越高越前面，会员投稿作者为红色,签发信息标题后有说明．</font></td>
    </tr>
  </table>
</form>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=2&classid=<?=$classid?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?
db_close();
$empire=null;
?>
