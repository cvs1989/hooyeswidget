<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
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

$enews=$_GET['enews'];
$classid=(int)$_GET['classid'];
if(empty($class_r[$classid][classid]))
{
	printerror("ErrorUrl","history.go(-1)");
}
//验证权限
$doselfinfo=CheckLevel($logininid,$loginin,$classid,"news");
if(!$class_r[$classid][tbname]||!$class_r[$classid][classid])
{
	printerror("ErrorUrl","history.go(-1)");
}
//非终极栏目
if(!$class_r[$classid]['islast'])
{
	printerror("AddInfoErrorClassid","history.go(-1)");
}
$bclassid=$class_r[$classid][bclassid];
$id=(int)$_GET['id'];
//附件验证码
if($enews=="AddNews")
{
	if(!$doselfinfo['doaddinfo'])//增加权限
	{
		printerror("NotAddInfoLevel","history.go(-1)");
	}
	$filepass=time();
	$word='增加信息';
	$ecmsfirstpost=1;
}
else
{
	if(!$doselfinfo['doeditinfo'])//编辑权限
	{
		printerror("NotEditInfoLevel","history.go(-1)");
	}
	$filepass=$id;
	$word='修改信息';
	$ecmsfirstpost=0;
}
//模型
$modid=$class_r[$classid][modid];
$enter=$emod_r[$modid]['enter'];
//导航
$url=AdminReturnClassLink($classid).'&nbsp;>&nbsp;'.$word;
//会员组
$sql1=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($l_r=$empire->fetch($sql1))
{
	$ygroup.="<option value=".$l_r[groupid].">".$l_r[groupname]."</option>";
}
if($enews=="AddNews")
{
	$group=str_replace(" value=".$class_r[$classid][groupid].">"," value=".$class_r[$classid][groupid]." selected>",$ygroup);
}
//初始化数据
$r=array();
$newstime=time();
$r[newstime]=date("Y-m-d H:i:s");
$todaytime=$r[newstime];
$r[checked]=$class_r[$classid][checked];
$r[onclick]=0;
$r[userfen]=0;
$titlefontb="";
$titlefonti="";
$titlefonts="";
$qfr[checkuser]=$class_r[$classid][checkuser];
$voteeditnum=8;
$voter[width]=500;
$voter[height]=300;
$voter[dotime]='0000-00-00';
$r[dokey]=1;
//----------- 特殊模型初始化 -----------
//下载地址前缀
if(strstr($enter,',downpath,')||strstr($enter,',onlinepath,'))
{
	$downurlqz="";
	$newdownqz="";
	$downsql=$empire->query("select urlname,url,urlid from {$dbtbpre}enewsdownurlqz order by urlid");
	while($downr=$empire->fetch($downsql))
	{
		$downurlqz.="<option value='".$downr[url]."'>".$downr[urlname]."</option>";
		$newdownqz.="<option value='".$downr[urlid]."'>".$downr[urlname]."</option>";
	}
}
//html编辑器
if($emod_r[$modid]['editorf']&&$emod_r[$modid]['editorf']!=',')
{
	include('ecmseditor/infoeditor/fckeditor.php');
}

//强制签发权限
$changeuser="<input type='button' name='Submit' value='选择用户' onclick=\"window.open('ChangeUser.php?field=checkuser&form=add','','width=300,height=520,scrollbars=yes');\">";
if($class_r[$classid][docheckuser])
{
	$checkuserreadonly=" readonly";
	$changeuser="";
}

//预设投票
if($enews=="AddNews")
{
	$infoclassr=$empire->fetch1("select definfovoteid from {$dbtbpre}enewsclass where classid='$classid'");
	$definfovoteid=0;
	if($infoclassr['definfovoteid'])
	{
		$definfovoteid=$infoclassr['definfovoteid'];
	}
	elseif($emod_r[$modid]['definfovoteid'])
	{
		$definfovoteid=$emod_r[$modid]['definfovoteid'];
	}
	if($definfovoteid)
	{
		//投票
		$voter=$empire->fetch1("select * from {$dbtbpre}enewsvotemod where voteid='$definfovoteid'");
		if($voter['voteid']&&$voter[votetext])
		{
			$d_record=explode("\r\n",$voter[votetext]);
			for($i=0;$i<count($d_record);$i++)
			{
				$j=$i+1;
				$d_field=explode("::::::",$d_record[$i]);
				$allvote.="<tr><td width='9%'><div align=center>".$j."</div></td><td width='65%'><input name=vote_name[] type=text value='".$d_field[0]."' size=30></td><td width='26%'><input name=vote_num[] type=text value='".$d_field[1]."' size=6></td></tr>";
			}
			$voteeditnum=$j;
			$allvote="<table width='100%' border=0 cellspacing=1 cellpadding=3>".$allvote."</table>";
		}
	}
}

//-----------------------------------------修改信息
if($enews=="EditNews")
{
	$r=$empire->fetch1("select * from {$dbtbpre}ecms_".$class_r[$classid][tbname]." where id='$id' and classid='$classid'");
	if(!$r[id])
	{
		printerror("ErrorUrl","history.go(-1)");
	}
	//签发表
	$qfr=$empire->fetch1("select * from {$dbtbpre}enewsqf where id='$id' and classid='$classid' limit 1");
	//只能编辑自己的信息
	if($doselfinfo['doselfinfo']&&($r[userid]<>$logininid||$r[ismember]))
	{
		printerror("NotDoSelfinfo","history.go(-1)");
	}
	//副表
	if($emod_r[$modid]['tbdataf']&&$emod_r[$modid]['tbdataf']<>',')
	{
		$selectdataf=substr($emod_r[$modid]['tbdataf'],1,strlen($emod_r[$modid]['tbdataf'])-2);
		$finfor=$empire->fetch1("select ".$selectdataf." from {$dbtbpre}ecms_".$class_r[$classid][tbname]."_data_".$r[stb]." where id='$id'");
		$r=array_merge($r,$finfor);
	}
	//时间
	$newstime=$r['newstime'];
	$r['newstime']=date("Y-m-d H:i:s",$r['newstime']);
	//会员组
	$group=str_replace(" value=".$r[groupid].">"," value=".$r[groupid]." selected>",$ygroup);
	//内容存文本
	$savetxtf=$emod_r[$modid]['savetxtf'];
	$newstext_url='';
	if($savetxtf)
	{
		$newstext_url=$r[$savetxtf];
		$r[$savetxtf]=GetTxtFieldText($r[$savetxtf]);
    }
	//签发
	$userlen=strlen($qfr[checkuser])-1;
	$qfr[checkuser]=substr($qfr[checkuser],1,$userlen-1);
	//标题属性
	if(strstr($r[titlefont],','))
	{
		$tfontr=explode(',',$r[titlefont]);
		$r[titlecolor]=$tfontr[0];
		$r[titlefont]=$tfontr[1];
	}
	if(strstr($r[titlefont],"b|"))
	{
		$titlefontb=" checked";
	}
	if(strstr($r[titlefont],"i|"))
	{
		$titlefonti=" checked";
	}
	if(strstr($r[titlefont],"s|"))
	{
		$titlefonts=" checked";
	}
	//投票
	$voter=$empire->fetch1("select * from {$dbtbpre}enewsinfovote where classid='$classid' and id='$id' limit 1");
	if($voter['id']&&$voter[votetext])
	{
		$d_record=explode("\r\n",$voter[votetext]);
		for($i=0;$i<count($d_record);$i++)
		{
			$j=$i+1;
			$d_field=explode("::::::",$d_record[$i]);
			$allvote.="<tr><td width='9%'><div align=center>".$j."</div></td><td width='65%'><input name=vote_name[] type=text value='".$d_field[0]."' size=30></td><td width='26%'><input name=vote_num[] type=text value='".$d_field[1]."' size=6><input type=hidden name=vote_id[] value=".$j."><input type=checkbox name=delvote_id[] value=".$j.">删除</td></tr>";
		}
		$voteeditnum=$j;
		$allvote="<table width='100%' border=0 cellspacing=1 cellpadding=3>".$allvote."</table>";
	}
}
//取得专题类别
$ztwhere=ReturnClass($class_r[$classid][featherclass]);
$z_sql=$empire->query("select ztname,ztid,tbname from {$dbtbpre}enewszt where classid=0 or classid='$classid' or (".$ztwhere.") order by ztid");
$j=0;
$br="";
while($z_r=$empire->fetch($z_sql))
{
	/*
	//不同表
	if($class_r[$classid][tbname]!=$z_r[tbname])
	{continue;}
	*/
	$j++;
	if($j%8==0)
	{
		$br="<br>";
	}
	else
	{
		$br="";
	}
	$c_zr=explode("|".$z_r[ztid]."|",$r[ztid]);
	if(count($c_zr)<>1)
	{$z_s=" checked";}
	else
	{$z_s="";}
	$z_class.="<input type=checkbox name=ztid[] value='".$z_r[ztid]."'".$z_s.">".$z_r[ztname]."&nbsp;".$br;
}
//标题分类
$tts='';
$ttsql=$empire->query("select typeid,tname from {$dbtbpre}enewsinfotype where mid='$modid' order by myorder");
while($ttr=$empire->fetch($ttsql))
{
	$select='';
	if($ttr[typeid]==$r[ttid])
	{
		$select=' selected';
	}
	$tts.="<option value='$ttr[typeid]'".$select.">$ttr[tname]</option>";
}
//内容模板
$t_sql=$empire->query("select tempid,tempname from ".GetTemptb("enewsnewstemp")." order by modid,tempid");
while($nt=$empire->fetch($t_sql))
{
	if($nt[tempid]==$r[newstempid])
	{
		$select=" selected";
	}
	else
	{
		$select="";
	}
	$newstemp.="<option value=".$nt[tempid].$select.">".$nt[tempname]."</option>";
}
//模板
$votetemp="";
$vtsql=$empire->query("select tempid,tempname from ".GetTemptb("enewsvotetemp")." order by tempid");
while($vtr=$empire->fetch($vtsql))
{
	if($voter[tempid]==$vtr[tempid])
	{
		$select=" selected";
	}
	else
	{
		$select="";
	}
	$votetemp.="<option value='".$vtr[tempid]."'".$select.">".$vtr[tempname]."</option>";
}
//同时发布
if(empty($r['copyids'])||$r['copyids']=='1')
{
	$copyclassidshowiframe='<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="ShowClassNav.php?ecms=1" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>';
	$copyclassids='<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
              <tr>
                <td>同时发布到以下栏目:</td>
              </tr>
              <tr>
                <td height="25" bgcolor="#FFFFFF" id="copyinfoshowclassnav"></td>
              </tr>
            </table>';
}
else
{
	$copyclassidshowiframe='';
	$copyclassids='<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
              <tr>
                <td>同时发布到以下栏目:</td>
              </tr>
              <tr>
                <td height="25" bgcolor="#FFFFFF" id="copyinfoshowclassnav">本信息已同步发布到其他栏目,信息ID:<br>'.$r[copyids].'</td>
              </tr>
            </table>';
}
//表单文件
$modfile="../data/html/".$modid.".php";
//栏目链接
$getcurlr['classid']=$classid;
$classurl=sys_ReturnBqClassname($getcurlr,9);
//当前使用的模板组
$thegid=GetDoTempGid();
$phpmyself=urlencode($_SERVER['PHP_SELF']."?".$_SERVER["QUERY_STRING"]);
?>
<html>
<head>
<title><?=$word?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" type="text/css">
<link id="luna-tab-style-sheet" type="text/css" rel="stylesheet" href="adminstyle/<?=$loginadminstyleid?>/tab.winclassic.css" disabled="disabled" /> 
<!-- the id is not needed. It is used here to be able to change css file at runtime -->
<style type="text/css"> 
   .dynamic-tab-pane-control .tab-page { 
          width:                100%;
 } 
  .dynamic-tab-pane-control .tab-page .dynamic-tab-pane-control .tab-page { 
         height:                150px; 
 } 
  form { 
         margin:        0; 
         padding:        0; 
 } 
  /* over ride styles from webfxlayout */ 
  .dynamic-tab-pane-control h2 { 
         font-size:12px;
		 font-weight:normal;
		 text-align:        center; 
         width:                auto;
		 height:            20; 
 } 
   .dynamic-tab-pane-control h2 a { 
         display:        inline; 
         width:                auto; 
 } 
  .dynamic-tab-pane-control a:hover { 
         background: transparent; 
 } 
  </style>
 <script type="text/javascript" src="../data/images/tabpane.js"></script> <script type="text/javascript"> 
  function setLinkSrc( sStyle ) { 
         document.getElementById( "luna-tab-style-sheet" ).disabled = sStyle != "luna"; 
  
         //document.documentElement.style.background = "";
         //document.body.style.background = sStyle == "webfx" ? "white" : "ThreeDFace"; 
 } 
function chgBg(obj,color){
 if (document.all || document.getElementById)
   obj.style.backgroundColor=color;
 else if (document.layers)
   obj.bgColor=color;
}
  setLinkSrc( "luna" ); 
</script>
<script>
function dovoteadd(){
	var i;
	var str="";
	var oldi=0;
	var j=0;
	oldi=parseInt(document.add.v_editnum.value);
	for(i=1;i<=document.add.v_vote_num.value;i++)
	{
		j=i+oldi;
		str=str+"<tr><td width='9%' height=20> <div align=center>"+j+"</div></td><td width='65%'> <div align=center><input type=text name=vote_name[] size=30></div></td><td width='26%'> <div align=center><input type=text name=vote_num[] value=0 size=6></div></td></tr>";
	}
	document.getElementById('addvote').innerHTML="<table width='100%' border=0 cellspacing=1 cellpadding=3>"+str+"</table>";
}

function doSpChangeFile(name,url,filesize,filetype,idvar){
	document.getElementById(idvar).value=url;
	if(document.add.filetype!=null)
	{
		if(document.add.filetype.value=='')
		{
			document.add.filetype.value=filetype;
		}
	}
	if(document.add.filesize!=null)
	{
		if(document.add.filesize.value=='')
		{
			document.add.filesize.value=filesize;
		}
	}
}

function SpOpenChFile(type,field){
	window.open('ecmseditor/FileMain.php?classid=<?=$classid?>&filepass=<?=$filepass?>&type='+type+'&tranfrom=2&field='+field,'','width=700,height=550,scrollbars=yes');
}
</script>
<script src="ecmseditor/fieldfile/setday.js"></script>
<script src="../data/html/postinfo.js"></script>
<script>
function bs(){
	var f=document.add;
	if(f.title.value.length==0){alert("标题还没写");f.title.focus();return false;}
}
function foreColor(){
  if(!Error())	return;
  var arr = showModalDialog("../data/html/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
  if (arr != null) document.add.titlecolor.value=arr;
  else document.add.titlecolor.focus();
}
function FieldChangeColor(obj){
  if(!Error())	return;
  var arr = showModalDialog("../data/html/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
  if (arr != null) obj.value=arr;
  else obj.focus();
}
</script>
</head>

<body bgcolor="#FFFFFF" text="#000000" onload="document.add.title.focus();">
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td width="62%" height="25">位置： 
      <?=$url?>
    </td>
    <td width="38%"><div align="right">
      </div></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
  <form name="searchinfo" method="GET" action="ListNews.php">
  <tr>
      <td width="42%">
<select name="dore">
        <option value="1">生成当前栏目</option>
        <option value="2">生成首页</option>
        <option value="3">生成父栏目</option>
        <option value="4">生成当前栏目与父栏目</option>
        <option value="5">生成父栏目与首页</option>
        <option value="6" selected>生成当前栏目、父栏目与首页</option>
      </select> <input type="button" name="Submit12" value="提交" onclick="self.location.href='ecmsinfo.php?enews=AddInfoToReHtml&classid=<?=$classid?>&dore='+document.searchinfo.dore.value;">
      </td>
      <td width="58%"><div align="right">[<font color="#ffffff"><a href=../../ target=_blank>预览首页</a></font>] 
          [<font color="#ffffff"><a href="<?=$classurl?>" target=_blank>预览栏目</a></font>] 
          [<font color="#ffffff"><a href="file/ListFile.php?type=9&classid=<?=$classid?>">附件管理</a></font>] 
          [<a href="AddClass.php?enews=EditClass&classid=<?=$classid?>">栏目设置</a>] 
          [<a href="ecmschtml.php?enews=ReAllNewsJs&from=<?=$phpmyself?>">刷新所有信息JS</a>] 
        </div></td>
  </tr>
</form>
</table><br>
<form name="add" method="post" enctype="multipart/form-data" action="ecmsinfo.php" onsubmit="EmpireCMSInfoPostFun(document.add,'<?=$modid?>');">
<div class="tab-pane" id="TabPane1">
	<script type="text/javascript">
	tb1 = new WebFXTabPane( document.getElementById( "TabPane1" ) );
	</script>
	<div class="tab-page" id="baseinfo">        
		<h2 class="tab">&nbsp;<font class=tabcolor>基本属性</font>&nbsp;</h2>
		<script type="text/javascript">tb1.addTabPage( document.getElementById( "baseinfo" ) );</script>
		<table width="100%" align="center" cellpadding="3" cellspacing="1" class="tableborder">
			<tr class="header"> 
				<td width="16%" height="25">
					<div align="left"><?=$word?></div>
				</td>
				<td>
					<input type="submit" name="addnews2" value="提交"> <input type="reset" name="Submit23" value="重置">
					<input type=hidden value=<?=$enews?> name=enews> <input type=hidden value=<?=$classid?> name=classid> 
					<input type=hidden value=<?=$bclassid?> name=bclassid> <input name=id type=hidden value=<?=$id?>> 
					<input type=hidden value="<?=$r[newspath]?>" name=newspath> <input type=hidden value="<?=$r[ztid]?>" name=oldztid> 
					<input type=hidden value="<?=$filepass?>" name=filepass> <input type=hidden value="<?=$r[username]?>" name=username> 
					<input name="oldfilename" type="hidden" value="<?=$r[filename]?>">  
					<input name="oldgroupid" type="hidden" value="<?=$r[groupid]?>"> 
					<input name="oldchecked" type="hidden" value="<?=$r[checked]?>"> 
					<input name="oldcheckuser" type="hidden" value="<?=$qfr[checkuser]?>"> 
					<input name="oldnotdocheckuser" type="hidden" value="<?=$qfr[notdocheckuser]?>"> 
					<input name="oldviewcheckuser" type="hidden" value="<?=$qfr[viewcheckuser]?>"> 
					<input name="newstext_url" type="hidden" value="<?=$newstext_url?>"> 
				</td>
			</tr>
		</table>
		<?php
		include($modfile);
		?>
	</div>
	<div class="tab-page" id="spsetting"> 
		<h2 class="tab">&nbsp;<font class=tabcolor>特殊属性</font>&nbsp;</h2>
        <script type="text/javascript">tb1.addTabPage( document.getElementById( "spsetting" ) );</script>
		<table width=100% align=center cellpadding=3 cellspacing=1 class="tableborder">
			<tr><td class=header>特殊属性</td></tr>
			<tr>
				<td bgcolor='#ffffff'> 
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
						<tr> 
							<td height="25" bgcolor="#FFFFFF"> 置顶级别: 
							<select name="istop">
							<option value="0"<?=$r[istop]==0?' selected':''?>>0级置顶</option>
							<option value="1"<?=$r[istop]==1?' selected':''?>>1级置顶</option>
							<option value="2"<?=$r[istop]==2?' selected':''?>>2级置顶</option>
							<option value="3"<?=$r[istop]==3?' selected':''?>>3级置顶</option>
							<option value="4"<?=$r[istop]==4?' selected':''?>>4级置顶</option>
							<option value="5"<?=$r[istop]==5?' selected':''?>>5级置顶</option>
							<option value="6"<?=$r[istop]==6?' selected':''?>>6级置顶</option>
							</select>
							内容模板: 
							<select name="newstempid">
							<option value="0"<?=$r[newstempid]==0?' selected':''?>>使用默认模板</option>
							<?=$newstemp?>
							</select> <input type="button" name="Submit62222" value="管理内容模板" onclick="window.open('template/ListNewstemp.php?gid=<?=$thegid?>');">
							</td>
						</tr>
						<tr> 
							<td height="25" bgcolor="#FFFFFF">权限设置: 
							<select name="groupid">
							<option value="0">游客</option>
							<?=$group?>
							</select>
							查看扣除点数: 
							<input name="userfen" type="text" value="<?=$r[userfen]?>" size="6">
							</td>
						</tr>
						<tr> 
							<td height="25" bgcolor="#FFFFFF">
							文件名&nbsp;&nbsp;&nbsp;: <input name="filename" type="text" value="<?=$r[filename]?>">, 
							<input type=checkbox name=closepl value=1<?=$r[closepl]==1?" checked":""?>>关闭评论
							</td>
						</tr>
					</table>
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
						<tr> 
							<td>所属专题:</td>
						</tr>
						<tr> 
							<td height="25" bgcolor="#FFFFFF"> 
							<?=$z_class?>
							</td>
						</tr>
					</table>
					<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
						<tr>    
							<td>签发<font color="#333333">(多个签发人员，请用&quot;,&quot;格开．不想使用签发，请留空) </font></td>
						</tr>
						<tr> 
							<td height="25" bgcolor="#FFFFFF"> <input name="checkuser" type="text" value="<?=stripSlashes($qfr[checkuser])?>" size="66"<?=$checkuserreadonly?>> 
							<?=$changeuser?> 
							</td>
						</tr>
						<tr>
							<td height="25" bgcolor="#FFFFFF"><input name="recheckuser" type="checkbox" value="1">
							重新签发<font color="#333333">（稿件被退下来后重新提交给未签发的人员的操作，如修改签发人员，所有人员需重新签发）</font>
							</td>
						</tr>
					</table>
					<?=$copyclassids?>
				</td>
			</tr>
		</table>
	</div>
	<div class="tab-page" id="votesetting">       
		<h2 class="tab">&nbsp;<font class=tabcolor>投票设置</font>&nbsp;</h2>
        <script type="text/javascript">tb1.addTabPage( document.getElementById( "votesetting" ) );</script>
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
			<tr class="header"> 
				<td height="25" colspan="2">投票设置</td>
			</tr>
			<tr bgcolor="#FFFFFF"> 
				<td width="21%" height="25">主题标题</td>
				<td width="79%" height="25"> <input name="vote_title" type="text" size="60" value="<?=$voter[title]?>"> 
				</td>
			</tr>
			<tr bgcolor="#FFFFFF"> 
				<td height="25" valign="top">投票项目</td>
				<td height="25">
					<table width="100%" border="0" cellspacing="1" cellpadding="3">
						<tr> 
							<td>
								<table width="100%" border="0" cellspacing="1" cellpadding="3">
									<tr bgcolor="#DBEAF5"> 
										<td width="9%" height="20"> <div align="center">编号</div></td>
										<td width="65%"> <div align="center">项目名称</div></td>
										<td width="26%"> <div align="center">投票数</div></td>
									</tr>
								</table>
								<?php
								if(($voter['id']&&$voter[votetext])||$definfovoteid)
								{
									echo"$allvote";
								}
								else
								{
								?>
									<table width="100%" border="0" cellspacing="1" cellpadding="3">
										<tr> 
											<td height="24" width="9%"> <div align="center">1</div></td>
											<td height="24" width="65%"> <div align="center"> 
											<input name="vote_name[]" type="text" size="30">
											</div></td>
											<td height="24" width="26%"> <div align="center"> 
											<input name="vote_num[]" type="text" value="0" size="6">
											</div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">2</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">3</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">4</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">5</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">6</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">7</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                    <tr> 
                      <td height="24"> <div align="center">8</div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_name[]" type="text" size="30">
                        </div></td>
                      <td height="24"> <div align="center"> 
                          <input name="vote_num[]" type="text" value="0" size="6">
                        </div></td>
                    </tr>
                  </table>
                  <?php
			  }
			  ?>
                </td>
              </tr>
              <tr> 
                <td>投票扩展数量: 
                  <input name="v_vote_num" type="text" value="1" size="6"> <input type="button" name="Submit52" value="输出地址" onclick="javascript:dovoteadd();"> 
                  <input name="v_editnum" type="hidden" value="<?=$voteeditnum?>"> 
                </td>
              </tr>
              <tr> 
                <td id="addvote"></td>
              </tr>
            </table></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">投票类型</td>
          <td height="25"><input name="vote_class" type="radio" value="0"<?=$voter['voteclass']==0?' checked':''?>>
            单选 
            <input type="radio" name="vote_class" value="1"<?=$voter['voteclass']==1?' checked':''?>>
            多选</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">限制IP</td>
          <td height="25"><input type="radio" name="dovote_ip" value="0"<?=$voter['doip']==0?' checked':''?>>
            不限制 
            <input name="dovote_ip" type="radio" value="1"<?=$voter['doip']==1?' checked':''?>>
            限制(限制后同一IP只能投一次票)</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">过期时间</td>
          <td height="25"> <input name="vote_olddotime" type=hidden value="<?=$voter[dotime]?>"> 
            <input name="vote_dotime" type="text" value="<?=$voter[dotime]?>" size="12" onClick="setday(this)">
            (超过此期限,将不能投票,0000-00-00为不限制)</td>
        </tr>
		<tr bgcolor="#FFFFFF"> 
      	  <td height="25">查看投票窗口</td>
      	<td height="25">宽度: 
        <input name="vote_width" type="text" value="<?=$voter[width]?>" size="6">
        高度: 
        <input name="vote_height" type="text" value="<?=$voter[height]?>" size="6"></td>
    	</tr>
        <tr bgcolor="#FFFFFF"> 
          <td height="25">选择模板</td>
          <td height="25"><select name="vote_tempid">
              <?=$votetemp?>
            </select> <input type="button" name="Submit62223" value="管理投票模板" onclick="window.open('template/ListVotetemp.php?gid=<?=$thegid?>');"> 
          </td>
        </tr>
      </table>
	</div>
</div>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
    <tr>
      <td width="16%">&nbsp;</td>
      <td><input type="submit" name="addnews" value=" 提 交 "> &nbsp;&nbsp;&nbsp;<input type="reset" name="Submit2" value="重置"></td>
    </tr>
  </table>
</form>
<?=$copyclassidshowiframe?>
</body>
</html>
<?php
db_close();
$empire=null;
?>