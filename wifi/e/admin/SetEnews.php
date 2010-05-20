<?php
require("../class/connect.php");
require("../class/db_sql.php");
require("../class/functions.php");
require("../class/t_functions.php");
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
CheckLevel($logininid,$loginin,$classid,"public");

//参数设置
function SetEnews($add,$userid,$username){
	global $empire,$dbtbpre;
	//操作权限
	CheckLevel($userid,$username,$classid,"public");
	$add[newsurl]=htmlspecialchars($add[newsurl],ENT_QUOTES);
	if(empty($add[indextype])){
		$add[indextype]=".html";
	}
	if(empty($add[searchtype])){
		$add[searchtype]=".html";
	}
	//备份目录
	if(empty($add[bakdbpath])){
		$add[bakdbpath]="bdata";
	}
	if(!file_exists("ebak/".RepPathStr($add[bakdbpath])))
	{
		printerror("NotBakDbPath","");
	}
	if(empty($add[bakdbzip])){
		$add[bakdbzip]="zip";
	}
	if(!file_exists("ebak/".RepPathStr($add[bakdbzip]))){
		printerror("NotbakZipPath","");
	}
	//函数是否存在
    if(!function_exists($add['listpagefun'])||!function_exists($add['textpagefun'])||!function_exists($add['listpagelistfun']))
	{
		printerror("NotPageFun","history.go(-1)");
    }
	//adfile
	$add['adfile']=RepFilenameQz($add['adfile']);
	//修改ftp密码
	if($add[changeftpp]){
		$a="ftppassword='$add[ftppassword]',";
    }
	//变量处理
	$add[filesize]=(int)$add[filesize];
	$add[hotnum]=(int)$add[hotnum];
	$add[newnum]=(int)$add[newnum];
	$add[relistnum]=(int)$add[relistnum];
	$add[renewsnum]=(int)$add[renewsnum];
	$add[min_keyboard]=(int)$add[min_keyboard];
	$add[max_keyboard]=(int)$add[max_keyboard];
	$add[search_num]=(int)$add[search_num];
	$add[search_pagenum]=(int)$add[search_pagenum];
	$add[newslink]=(int)$add[newslink];
	$add[checked]=(int)$add[checked];
	$add[pltime]=(int)$add[pltime];
	$add[searchtime]=(int)$add[searchtime];
	$add[loginnum]=(int)$add[loginnum];
	$add[logintime]=(int)$add[logintime];
	$add[addnews_ok]=(int)$add[addnews_ok];
	$add[register_ok]=(int)$add[register_ok];
	$add[goodlencord]=(int)$add[goodlencord];
	$add[goodnum]=(int)$add[goodnum];
	$add[exittime]=(int)$add[exittime];
	$add[smalltextlen]=(int)$add[smalltextlen];
	$add[defaultgroupid]=(int)$add[defaultgroupid];
	$add[phpmode]=(int)$add[phpmode];
	$add[install]=(int)$add[install];
	$add[plsize]=(int)$add[plsize];
	$add[plincludesize]=(int)$add[plincludesize];
	$add[hotplnum]=(int)$add[hotplnum];
	$add[dorepnum]=(int)$add[dorepnum];
	$add[loadtempnum]=(int)$add[loadtempnum];
	$add[firstnum]=(int)$add[firstnum];
	$add[min_userlen]=(int)$add[min_userlen];
	$add[max_userlen]=(int)$add[max_userlen];
	$add[min_passlen]=(int)$add[min_passlen];
	$add[max_passlen]=(int)$add[max_passlen];
	$add[filechmod]=(int)$add[filechmod];
	$add[sametitle]=(int)$add[sametitle];
	$add[addrehtml]=(int)$add[addrehtml];
	$add[loginkey_ok]=(int)$add[loginkey_ok];
	$add[limittype]=(int)$add[limittype];
	$add[plkey_ok]=(int)$add[plkey_ok];
	$add[redodown]=(int)$add[redodown];
	$add[fpnum]=(int)$add[fpnum];
	$add[havefp]=(int)$add[havefp];
	$add[candocode]=(int)$add[candocode];
	$add[opennotcj]=(int)$add[opennotcj];
	$add[reuserpagenum]=(int)$add[reuserpagenum];
	$add[revotejsnum]=(int)$add[revotejsnum];
	$add[readjsnum]=(int)$add[readjsnum];
	$add[qaddtran]=(int)$add[qaddtran];
	$add[qaddtransize]=(int)$add[qaddtransize];
	$add[ebakthisdb]=(int)$add[ebakthisdb];
	$add[delnewsnum]=(int)$add[delnewsnum];
	$add[markpos]=(int)$add[markpos];
	$add[adminloginkey]=(int)$add[adminloginkey];
	$add[php_outtime]=(int)$add[php_outtime];
	$add[addreinfo]=(int)$add[addreinfo];
	$add[rssnum]=(int)$add[rssnum];
	$add[rsssub]=(int)$add[rsssub];
	$add[dorepdlevelnum]=(int)$add[dorepdlevelnum];
	$add[listpagelistnum]=(int)$add[listpagelistnum];
	$add[infolinknum]=(int)$add[infolinknum];
	$add[searchgroupid]=(int)$add[searchgroupid];
	$add[opencopytext]=(int)$add[opencopytext];
	$add[reuserjsnum]=(int)$add[reuserjsnum];
	$add[reuserlistnum]=(int)$add[reuserlistnum];
	$add[opentitleurl]=(int)$add[opentitleurl];
	$add['qaddtranfile']=(int)$add['qaddtranfile'];
	$add['qaddtranfilesize']=(int)$add['qaddtranfilesize'];
	$add['sendmailtype']=(int)$add['sendmailtype'];
	$add['loginemail']=(int)$add['loginemail'];
	$add['feedbacktfile']=(int)$add['feedbacktfile'];
	$add['feedbackfilesize']=(int)$add['feedbackfilesize'];
	$add['searchtempvar']=(int)$add['searchtempvar'];
	$add['showinfolevel']=(int)$add['showinfolevel'];
	$add['spicwidth']=(int)$add['spicwidth'];
	$add['spicheight']=(int)$add['spicheight'];
	$add['spickill']=(int)$add['spickill'];
	$add['jpgquality']=(int)$add['jpgquality'];
	$add['markpct']=(int)$add['markpct'];
	$add['redoview']=(int)$add['redoview'];
	$add['reggetfen']=(int)$add['reggetfen'];
	$add['regbooktime']=(int)$add['regbooktime'];
	$add['revotetime']=(int)$add['revotetime'];
	$add['fpath']=(int)$add['fpath'];
	$add['openmembertranimg']=(int)$add['openmembertranimg'];
	$add['memberimgsize']=(int)$add['memberimgsize'];
	$add['openmembertranfile']=(int)$add['openmembertranfile'];
	$add['memberfilesize']=(int)$add['memberfilesize'];
	$add['openspace']=(int)$add['openspace'];
	$add['realltime']=(int)$add['realltime'];
	$add['plfacenum']=(int)$add['plfacenum'];
	$add['textpagelistnum']=(int)$add['textpagelistnum'];
	$add['memberlistlevel']=(int)$add['memberlistlevel'];
	$add['ebakcanlistdb']=(int)$add['ebakcanlistdb'];
	$add['keytog']=(int)$add['keytog'];
	$add['keytime']=(int)$add['keytime'];
	$add['regkey_ok']=(int)$add['regkey_ok'];
	$add['opengetdown']=(int)$add['opengetdown'];
	$add['gbkey_ok']=(int)$add['gbkey_ok'];
	$add['fbkey_ok']=(int)$add['fbkey_ok'];
	$add['newaddinfotime']=(int)$add['newaddinfotime'];
	$add['classnavline']=(int)$add['classnavline'];
	$add['plgroupid']=(int)$add['plgroupid'];
	$add['docnewsnum']=(int)$add['docnewsnum'];
	$add['dtcanbq']=(int)$add['dtcanbq'];
	$add['dtcachetime']=(int)$add['dtcachetime'];
	$add['buycarnum']=(int)$add['buycarnum'];
	$add['shopddgroupid']=(int)$add['shopddgroupid'];
	$add['regretime']=(int)$add['regretime'];
	$add['regemailonly']=(int)$add['regemailonly'];
	$add['repkeynum']=(int)$add['repkeynum'];
	$add['getpasstime']=(int)$add['getpasstime'];
	$add['acttime']=(int)$add['acttime'];
	$add['regacttype']=(int)$add['regacttype'];
	$add['opengetpass']=(int)$add['opengetpass'];
	$add['hlistinfonum']=(int)$add['hlistinfonum'];
	if(empty($add['hlistinfonum']))
	{
		$add['hlistinfonum']=30;
	}
	$add['qlistinfonum']=(int)$add['qlistinfonum'];
	if(empty($add['qlistinfonum']))
	{
		$add['qlistinfonum']=30;
	}
	$add['dtncanbq']=(int)$add['dtncanbq'];
	$add['dtncachetime']=(int)$add['dtncachetime'];
	$add['readdinfotime']=(int)$add['readdinfotime'];
	$add['qeditinfotime']=(int)$add['qeditinfotime'];
	$add['ftpmode']=(int)$add['ftpmode'];
	$add['ftpssl']=(int)$add['ftpssl'];
	$add['ftppasv']=(int)$add['ftppasv'];
	$add['ftpouttime']=(int)$add['ftpouttime'];

	$add[filetype]="|".$add[filetype]."|";
	$add[qimgtype]="|".$add['qaddtranimgtype']."|";
	$add[qfiletype]="|".$add['qaddtranfiletype']."|";
	$add[feedbackfiletype]="|".$add['feedbackfiletype']."|";
	$add[memberimgtype]="|".$add['memberimgtype']."|";
	$add[memberfiletype]="|".$add['memberfiletype']."|";
	$sql=$empire->query("update {$dbtbpre}enewspublic set ".$a."sitename='$add[sitename]',newsurl='$add[newsurl]',email='$add[email]',filetype='$add[filetype]',filesize=$add[filesize],hotnum=$add[hotnum],newnum=$add[newnum],relistnum=$add[relistnum],renewsnum=$add[renewsnum],min_keyboard=$add[min_keyboard],max_keyboard=$add[max_keyboard],search_num=$add[search_num],search_pagenum=$add[search_pagenum],newslink=$add[newslink],checked=$add[checked],pltime=$add[pltime],searchtime=$add[searchtime],loginnum=$add[loginnum],logintime=$add[logintime],addnews_ok=$add[addnews_ok],register_ok=$add[register_ok],indextype='$add[indextype]',goodlencord=$add[goodlencord],goodtype='$add[goodtype]',goodnum=$add[goodnum],searchtype='$add[searchtype]',exittime=$add[exittime],smalltextlen=$add[smalltextlen],defaultgroupid=$add[defaultgroupid],fileurl='$add[fileurl]',phpmode=$add[phpmode],ftphost='$add[ftphost]',ftpport='$add[ftpport]',ftpusername='$add[ftpusername]',ftppath='$add[ftppath]',ftpmode='$add[ftpmode]',install=$add[install],plsize=$add[plsize],plincludesize=$add[plincludesize],hotplnum=$add[hotplnum],dorepnum=$add[dorepnum],loadtempnum=$add[loadtempnum],firstnum=$add[firstnum],bakdbpath='$add[bakdbpath]',bakdbzip='$add[bakdbzip]',downpass='$add[downpass]',min_userlen=$add[min_userlen],max_userlen=$add[max_userlen],min_passlen=$add[min_passlen],max_passlen=$add[max_passlen],filechmod=$add[filechmod],loginkey_ok=$add[loginkey_ok],limittype=$add[limittype],plkey_ok=$add[plkey_ok],redodown=$add[redodown],fpnum=$add[fpnum],havefp=$add[havefp],candocode=$add[candocode],opennotcj=$add[opennotcj],reuserpagenum=$add[reuserpagenum],revotejsnum=$add[revotejsnum],readjsnum=$add[readjsnum],qaddtran=$add[qaddtran],qaddtransize=$add[qaddtransize],ebakthisdb=$add[ebakthisdb],delnewsnum=$add[delnewsnum],markpos=$add[markpos],markimg='$add[markimg]',marktext='$add[marktext]',markfontsize='$add[markfontsize]',markfontcolor='$add[markfontcolor]',markfont='$add[markfont]',adminloginkey=$add[adminloginkey],php_outtime=$add[php_outtime],listpagefun='$add[listpagefun]',textpagefun='$add[textpagefun]',adfile='$add[adfile]',notsaveurl='$add[notsaveurl]',rssnum=$add[rssnum],rsssub=$add[rsssub],dorepdlevelnum=$add[dorepdlevelnum],listpagelistfun='$add[listpagelistfun]',listpagelistnum=$add[listpagelistnum],infolinknum=$add[infolinknum],searchgroupid=$add[searchgroupid],opencopytext=$add[opencopytext],reuserjsnum=$add[reuserjsnum],reuserlistnum=$add[reuserlistnum],opentitleurl='$add[opentitleurl]',qaddtranimgtype='$add[qimgtype]',qaddtranfile=$add[qaddtranfile],qaddtranfilesize=$add[qaddtranfilesize],qaddtranfiletype='$add[qfiletype]',sendmailtype=$add[sendmailtype],smtphost='$add[smtphost]',fromemail='$add[fromemail]',loginemail=$add[loginemail],emailusername='$add[emailusername]',emailpassword='$add[emailpassword]',smtpport='$add[smtpport]',emailname='$add[emailname]',feedbacktfile=$add[feedbacktfile],feedbackfilesize=$add[feedbackfilesize],feedbackfiletype='$add[feedbackfiletype]',searchtempvar=$add[searchtempvar],showinfolevel=$add[showinfolevel],navfh='".addslashes($add[navfh])."',spicwidth=$add[spicwidth],spicheight=$add[spicheight],spickill=$add[spickill],jpgquality=$add[jpgquality],markpct=$add[markpct],redoview=$add[redoview],reggetfen=$add[reggetfen],regbooktime=$add[regbooktime],revotetime=$add[revotetime],fpath=$add[fpath],filepath='$add[filepath]',openmembertranimg=$add[openmembertranimg],memberimgsize=$add[memberimgsize],openmembertranfile=$add[openmembertranfile],memberfilesize=$add[memberfilesize],memberimgtype='$add[memberimgtype]',memberfiletype='$add[memberfiletype]',canposturl='$add[canposturl]',openspace='$add[openspace]',realltime=$add[realltime],closeip='$add[closeip]',openip='$add[openip]',hopenip='$add[hopenip]',plfacenum=$add[plfacenum],closewords='$add[closewords]',closewordsf='$add[closewordsf]',textpagelistnum=$add[textpagelistnum],memberlistlevel=$add[memberlistlevel],ebakcanlistdb=$add[ebakcanlistdb],keytog='$add[keytog]',keyrnd='$add[keyrnd]',keytime='$add[keytime]',regkey_ok='$add[regkey_ok]',opengetdown='$add[opengetdown]',gbkey_ok='$add[gbkey_ok]',fbkey_ok='$add[fbkey_ok]',newaddinfotime='$add[newaddinfotime]',classnavline='$add[classnavline]',classnavfh='".addslashes($add[classnavfh])."',plgroupid=$add[plgroupid],sitekey='$add[sitekey]',siteintro='$add[siteintro]',docnewsnum='$add[docnewsnum]',dtcanbq='$add[dtcanbq]',dtcachetime='$add[dtcachetime]',plclosewords='$add[plclosewords]',buycarnum='$add[buycarnum]',shopddgroupid='$add[shopddgroupid]',regretime='$add[regretime]',regclosewords='$add[regclosewords]',regemailonly='$add[regemailonly]',repkeynum='$add[repkeynum]',getpasstime='$add[getpasstime]',acttime='$add[acttime]',regacttype='$add[regacttype]',acttext='".addslashes($add[acttext])."',getpasstext='".addslashes($add[getpasstext])."',acttitle='".addslashes($add[acttitle])."',getpasstitle='".addslashes($add[getpasstitle])."',opengetpass='$add[opengetpass]',hlistinfonum='$add[hlistinfonum]',qlistinfonum='$add[qlistinfonum]',dtncanbq='$add[dtncanbq]',dtncachetime='$add[dtncachetime]',readdinfotime='$add[readdinfotime]',qeditinfotime='$add[qeditinfotime]',ftpssl='$add[ftpssl]',ftppasv='$add[ftppasv]',ftpouttime='$add[ftpouttime]';");
	GetConfig();
	if($sql){
		insert_dolog("");//操作日志
		printerror("SetPublicSuccess","SetEnews.php");
	}
	else{
		printerror("DbError","history.go(-1)");
	}
}

$enews=$_POST['enews'];
if(empty($enews))
{
	$enews=$_GET['enews'];
}
if($enews=="SetEnews")//参数设置
{
	SetEnews($_POST,$logininid,$loginin);
}

$r=$empire->fetch1("select * from {$dbtbpre}enewspublic limit 1");
//文件类别
$filetype=substr($r[filetype],1,strlen($r[filetype]));
$filetype=substr($filetype,0,strlen($filetype)-1);
//投稿图片扩展名
$qaddimgtype=substr($r[qaddtranimgtype],1,strlen($r[qaddtranimgtype]));
$qaddimgtype=substr($qaddimgtype,0,strlen($qaddimgtype)-1);
//投稿附件扩展名
$qaddfiletype=substr($r[qaddtranfiletype],1,strlen($r[qaddtranfiletype]));
$qaddfiletype=substr($qaddfiletype,0,strlen($qaddfiletype)-1);
//反馈附件
$feedbackfiletype=substr($r[feedbackfiletype],1,strlen($r[feedbackfiletype])-2);
//会员表单
$memberimgtype=substr($r[memberimgtype],1,strlen($r[memberimgtype]));
$memberimgtype=substr($memberimgtype,0,strlen($memberimgtype)-1);
$memberfiletype=substr($r[memberfiletype],1,strlen($r[memberfiletype]));
$memberfiletype=substr($memberfiletype,0,strlen($memberfiletype)-1);
//----------会员组
$sql1=$empire->query("select groupid,groupname from {$dbtbpre}enewsmembergroup order by level");
while($l_r=$empire->fetch($sql1))
{
	if($r[defaultgroupid]==$l_r[groupid])
	{$select=" selected";}
	else
	{$select="";}
	//搜索会员组
	if($r[searchgroupid]==$l_r[groupid])
	{$s_select=" selected";}
	else
	{$s_select="";}
	//查看资料权限
	if($r[showinfolevel]==$l_r[groupid])
	{$showinfo_select=" selected";}
	else
	{$showinfo_select="";}
	//会员列表查看权限
	if($r[memberlistlevel]==$l_r[groupid])
	{$memberlist_select=" selected";}
	else
	{$memberlist_select="";}
	//评论权限
	if($r[plgroupid]==$l_r[groupid])
	{$plgroup_select=" selected";}
	else
	{$plgroup_select="";}
	//商城订单权限
	if($r[shopddgroupid]==$l_r[groupid])
	{$ddgroup_select=" selected";}
	else
	{$ddgroup_select="";}
	$membergroup.="<option value=".$l_r[groupid].$select.">".$l_r[groupname]."</option>";
	$searchmembergroup.="<option value=".$l_r[groupid].$s_select.">".$l_r[groupname]."</option>";
	$showinfolevel.="<option value=".$l_r[groupid].$showinfo_select.">".$l_r[groupname]."</option>";
	$memberlistlevel.="<option value=".$l_r[groupid].$memberlist_select.">".$l_r[groupname]."</option>";
	$plgroup.="<option value=".$l_r[groupid].$plgroup_select.">".$l_r[groupname]."</option>";
	$shopddgroup.="<option value=".$l_r[groupid].$ddgroup_select.">".$l_r[groupname]."</option>";
}
db_close();
$empire=null;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>参数设置</title>
<link href="adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
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
  
  function foreColor()
{
  if (!Error())	return;
  var arr = showModalDialog("ecmseditor/fieldfile/selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
  if (arr != null) document.form1.markfontcolor.value=arr;
  else document.form1.markfontcolor.focus();
}
  </script> 
</head>

<body>
<table width="100%%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr>
    <td>位置：<a href="SetEnews.php">参数设置</a></td>
  </tr>
</table>
<form name="form1" method="post" action="SetEnews.php">
<div class="tab-pane" id="TabPane1"> <script type="text/javascript">
tb1 = new WebFXTabPane( document.getElementById( "TabPane1" ) );
</script>
<div class="tab-page" id="baseinfo"> 
                    
      <h2 class="tab">&nbsp;<font class=tabcolor>基本属性</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "baseinfo" ) );</script>
      <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <input type=hidden name=enews value=SetEnews>
        <tr class="header"> 
          <td height="25" colspan="2">基本信息设置</td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">站点名称</td>
          <td width="78%" height="25" bgcolor="#FFFFFF"> <input name="sitename" type="text" id="sitename" value="<?=$r[sitename]?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">网站地址</td>
          <td height="25" bgcolor="#FFFFFF"> <input name="newsurl" type="text" id="newsurl4" value="<?=$r[newsurl]?>" size="38"> 
            <font color="#666666">(后面需加“/”，如：/)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">附件地址</td>
          <td height="25" bgcolor="#FFFFFF"><input name="fileurl" type="text" id="fileurl" value="<?=$r[fileurl]?>" size="38">
            <font color="#666666">(绑定域名时设置，后面需加“/”，如：/d/file/)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">管理员邮箱</td>
          <td height="25" bgcolor="#FFFFFF"> <input name="email" type="text" id="email" value="<?=$r[email]?>" size="38"></td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF">网站关键字</td>
          <td height="25" bgcolor="#FFFFFF"><input name="sitekey" type="text" id="sitekey" value="<?=$r[sitekey]?>" size="38"></td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF">网站简介</td>
          <td height="25" bgcolor="#FFFFFF"><textarea name="siteintro" cols="80" rows="5" id="siteintro"><?=$r[siteintro]?></textarea></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">首页文件扩展名</td>
          <td height="25" bgcolor="#FFFFFF"><input name="indextype" type="text" id="indextype" value="<?=$r[indextype]?>" size="38"> 
            <font color="#666666"> 
            <select name="select" onchange="document.form1.indextype.value=this.value">
              <option value=".html">扩展名</option>
              <option value=".html">.html</option>
              <option value=".htm">.htm</option>
              <option value=".php">.php</option>
              <option value=".shtml">.shtml</option>
            </select>
            <input name="oldindextype" type="hidden" id="oldindextype" value="<?=$r[indextype]?>">
            <font color="#666666"></font>(如：.html,.htm,.xml,.php)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">PHP超时时间设置</td>
          <td height="25" bgcolor="#FFFFFF"><input name="php_outtime" type="text" id="php_outtime" value="<?=$r[php_outtime]?>" size="38">
            秒 <font color="#666666">(一般不需要设置)</font></td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">远程保存忽略地址<br> <br> <font color="#666666">(一行为一个地址)</font></td>
          <td height="25" bgcolor="#FFFFFF"><textarea name="notsaveurl" cols="80" rows="8" id="notsaveurl"><?=$r[notsaveurl]?></textarea></td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">前台允许提交的来源地址<br> <br> 
            <font color="#666666">(一行为一个地址)</font></td>
          <td height="25" bgcolor="#FFFFFF"><textarea name="canposturl" cols="80" rows="8" id="canposturl"><?=$r[canposturl]?></textarea></td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">验证码字符组成</td>
          <td height="25" bgcolor="#FFFFFF"><select name="keytog" id="keytog">
              <option value="0"<?=$r[keytog]==0?' selected':''?>>数字</option>
              <option value="1"<?=$r[keytog]==1?' selected':''?>>字母</option>
              <option value="2"<?=$r[keytog]==2?' selected':''?>>数字+字母</option>
            </select></td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">验证码过期时间</td>
          <td height="25" bgcolor="#FFFFFF"><input name="keytime" type="text" id="keytime" value="<?=$r[keytime]?>" size="38">
            分钟 </td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">验证码加密字符串</td>
          <td height="25" bgcolor="#FFFFFF"><input name="keyrnd" type="text" id="keyrnd" value="<?=$r[keyrnd]?>" size="38"> 
            <font color="#666666">(10~60个任意字符，最好多种字符组合)</font></td>
        </tr>
      </table>
  </div>
    <div class="tab-page" id="login"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">用户设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "login" ) );</script>
	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
          <td height="25" colspan="2">后台设置</td>
    </tr>
	<tr> 
          <td height="25" bgcolor="#FFFFFF">后台登陆验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="adminloginkey" value="0"<?=$r[adminloginkey]==0?' checked':''?>>
            开启 
            <input type="radio" name="adminloginkey" value="1"<?=$r[adminloginkey]==1?' checked':''?>>
            关闭</td>
        </tr>
    <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">后台登录次数限制</td>
      <td height="25" bgcolor="#FFFFFF"><input name="loginnum" type="text" id="loginnum" value="<?=$r[loginnum]?>" size="38">
        次</td>
    </tr>
    <tr> 
          <td height="25" bgcolor="#FFFFFF">重新登录时间间隔</td>
      <td height="25" bgcolor="#FFFFFF"><input name="logintime" type="text" id="logintime" value="<?=$r[logintime]?>" size="38">
        分钟</td>
    </tr>
    <tr> 
          <td height="25" bgcolor="#FFFFFF">登录超时限制</td>
      <td height="25" bgcolor="#FFFFFF"><input name="exittime" type="text" id="exittime" value="<?=$r[exittime]?>" size="38">
        分钟</td>
    </tr>
	</table>
	
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="24" colspan="2">前台设置</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><p>会员注册</p></td>
          <td height="25" bgcolor="#FFFFFF"><p> 
              <input type="radio" name="register_ok" value="0"<?=$r[register_ok]==0?' checked':''?>>
              开启 
              <input type="radio" name="register_ok" value="1"<?=$r[register_ok]==1?' checked':''?>>
              关闭</p></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">注册会员默认会员组</td>
          <td height="25" bgcolor="#FFFFFF"><select name="defaultgroupid">
              <?=$membergroup?>
            </select></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">注册赠送点数</td>
          <td height="25" bgcolor="#FFFFFF"><input name="reggetfen" type="text" id="reggetfen" value="<?=$r[reggetfen]?>" size="38"></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">注册用户名限制</td>
          <td height="25" bgcolor="#FFFFFF"><input name="min_userlen" type="text" id="min_userlen" value="<?=$r[min_userlen]?>" size="6">
            ~ 
            <input name="max_userlen" type="text" id="max_userlen" value="<?=$r[max_userlen]?>" size="6">
            个字节</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">注册密码限制</td>
          <td height="25" bgcolor="#FFFFFF"><input name="min_passlen" type="text" id="min_passlen" value="<?=$r[min_passlen]?>" size="6">
            ~ 
            <input name="max_passlen" type="text" id="max_passlen" value="<?=$r[max_passlen]?>" size="6">
            个字节</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">会员邮箱唯一性检查:</td>
          <td height="25" bgcolor="#FFFFFF"><input name="regemailonly" type="radio" value="1"<?=$r[regemailonly]==1?' checked':''?>>
            开启 
            <input name="regemailonly" type="radio" value="0"<?=$r[regemailonly]==0?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">同一IP注册间隔限制:</td>
          <td height="25" bgcolor="#FFFFFF"><input name="regretime" type="text" id="regretime" value="<?=$r[regretime]?>" size="38">
            个小时</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">用户名保留关键字:</td>
          <td height="25" bgcolor="#FFFFFF"><input name="regclosewords" type="text" id="repnum3" value="<?=$r[regclosewords]?>" size="38"> 
            <font color="#666666">(禁止包含字符,多个用&quot;|&quot;号隔开)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">投稿功能</td>
          <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="addnews_ok" value="0"<?=$r[addnews_ok]==0?' checked':''?>>
            开启 
            <input type="radio" name="addnews_ok" value="1"<?=$r[addnews_ok]==1?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">会员空间</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="openspace" value="0"<?=$r[openspace]==0?' checked':''?>>
            开启 
            <input type="radio" name="openspace" value="1"<?=$r[openspace]==1?' checked':''?>>
            关闭 </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">会员登陆验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="loginkey_ok" value="1"<?=$r[loginkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="loginkey_ok" value="0"<?=$r[loginkey_ok]==0?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">会员注册验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="regkey_ok" value="1"<?=$r[regkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="regkey_ok" value="0"<?=$r[regkey_ok]==0?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">会员列表查看权限</td>
          <td height="25" bgcolor="#FFFFFF"><select name="memberlistlevel" id="memberlistlevel">
              <option value=0>游客</option>
              <?=$memberlistlevel?>
            </select></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">查看会员资料权限</td>
          <td height="25" bgcolor="#FFFFFF"><select name="showinfolevel" id="showinfolevel">
              <option value=0>游客</option>
              <?=$showinfolevel?>
            </select></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">会员注册审核方式</td>
          <td height="25" bgcolor="#FFFFFF"><input name="regacttype" type="radio" value="0"<?=$r[regacttype]==0?' checked':''?>>
            无 
            <input name="regacttype" type="radio" value="1"<?=$r[regacttype]==1?' checked':''?>>
            邮件激活</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">激活帐号链接有效期</td>
          <td height="25" bgcolor="#FFFFFF"><input name="acttime" type="text" id="acttime" value="<?=$r[acttime]?>" size="38">
            小时</td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">帐号激活邮件内容<br> <br>
            <font color="#666666">[!--pageurl--]:激活地址 <br>
            [!--username--]:用户名<br>
            [!--email--]:邮箱地址<br>
            [!--date--]:发送时间<br>
            [!--sitename--]:网站名称<br>
            [!--news.url--]:网站地址</font></td>
          <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td>标题： 
                  <input name="acttitle" type="text" id="acttitle" value="<?=$r[acttitle]?>" size="38"></td>
              </tr>
              <tr> 
                <td><textarea name="acttext" cols="80" rows="12" style="WIDTH: 100%" id="acttext"><?=htmlspecialchars($r[acttext])?></textarea></td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF">开启取回密码功能</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="opengetpass" value="1"<?=$r[opengetpass]==1?' checked':''?>>
            开启
            <input type="radio" name="opengetpass" value="0"<?=$r[opengetpass]==0?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">取回密码链接有效期</td>
          <td height="25" bgcolor="#FFFFFF"><input name="getpasstime" type="text" id="getpasstime" value="<?=$r[getpasstime]?>" size="38">
            小时</td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">取回密码邮件内容<br> <br>
            <font color="#666666">[!--pageurl--]:取回地址 <br>
            [!--username--]:用户名<br>
            [!--email--]:邮箱地址<br>
            [!--date--]:发送时间<br>
            [!--sitename--]:网站名称<br>
            [!--news.url--]:网站地址 </font></td>
          <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td>标题： 
                  <input name="getpasstitle" type="text" id="getpasstitle" value="<?=$r[getpasstitle]?>" size="38"></td>
              </tr>
              <tr> 
                <td><textarea name="getpasstext" cols="80" rows="12" style="WIDTH: 100%" id="textarea"><?=htmlspecialchars($r[getpasstext])?></textarea></td>
              </tr>
            </table></td>
        </tr>
      </table>
	  
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">访问控制设置</td>
        </tr>
        <tr> 
          <td width="22%" height="25" valign="top" bgcolor="#FFFFFF"> <strong>禁止 
            IP 访问列表:(前台及后台有效)</strong><br>
              每个 IP 一行，既可输入完整地址，也可只输入 IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 
              192.168.0.0～192.168.255.255 范围内的所有地址，留空为不设置 <br>
            </td>
          <td height="25" valign="top" bgcolor="#FFFFFF">
<textarea name="closeip" cols="80" rows="8" id="closeip"><?=$r[closeip]?></textarea>
          </td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF"><strong>允许 IP 访问列表:(前台及后台有效)</strong><br>
            只有当用户处于本列表中的 IP 地址时才可以访问网站，列表以外的地址访问将视为 IP 被禁止.每个 IP 一行，既可输入完整地址，也可只输入 
            IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为所有 IP 除明确禁止的以外均可访问<br></td>
          <td height="25" valign="top" bgcolor="#FFFFFF"><textarea name="openip" cols="80" rows="8" id="textarea2"><?=$r[openip]?></textarea></td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF"><strong>允许后台 IP 访问列表:(后台有效)<br>
            </strong>只有当管理员处于本列表中的 IP 地址时才可以访问后台，列表以外的地址访问将视为 IP 被禁止.每个 IP 一行，既可输入完整地址，也可只输入 
            IP 开头，例如 &quot;192.168.&quot;(不含引号) 可匹配 192.168.0.0～192.168.255.255 
            范围内的所有地址，留空为所有 IP 除明确禁止的以外均可访问<strong> </strong></td>
          <td height="25" valign="top" bgcolor="#FFFFFF"><textarea name="hopenip" cols="80" rows="8" id="textarea3"><?=$r[hopenip]?></textarea></td>
        </tr>
      </table>
	</div>
	  
    <div class="tab-page" id="file"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">文件设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "file" ) );</script>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">文件设置</td>
        </tr>
        <tr> 
          <td rowspan="2" valign="top" bgcolor="#FFFFFF">附件存放目录</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="fpath" value="0"<?=$r[fpath]==0?' checked':''?>>
            栏目目录 
            <input type="radio" name="fpath" value="1"<?=$r[fpath]==1?' checked':''?>>
            /d/file/p目录 
            <input type="radio" name="fpath" value="2"<?=$r[fpath]==2?' checked':''?>>
            /d/file目录</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><input name="filepath" type="text" id="filepath" value="<?=$r[filepath]?>" size="38"> 
            <select name="select6" onchange="document.form1.filepath.value=this.value">
              <option value="Y-m-d">选择</option>
              <option value="Y-m-d">2005-01-27</option>
              <option value="Y/m-d">2005/01-27</option>
              <option value="Y/m/d">2005/01/27</option>
              <option value="Ymd">20050127</option>
              <option value="">不设置目录</option>
            </select> <font color="#666666">(如Y-m-d，Y/m-d，Y/m/d，Ymd等形式)</font></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">后台上传附件大小</td>
          <td height="25" bgcolor="#FFFFFF"><input name="filesize" type="text" id="filesize" value="<?=$r[filesize]?>" size="38">
            KB</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">后台上传文件扩展名</td>
          <td height="25" bgcolor="#FFFFFF"><input name="filetype" type="text" id="filetype" value="<?=$filetype?>" size="38"> 
            <font color="#666666">(多个请用“|”格开，如：.gif|.jpg)</font></td>
        </tr>
        <tr> 
          <td rowspan="2" valign="top" bgcolor="#FFFFFF">前台投稿附件设置</td>
          <td height="25" bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="qaddtran" type="checkbox" value="1"<?=$r[qaddtran]==1?' checked':''?>>
                  开启上传图片,最大图片： 
                  <input name="qaddtransize" type="text" id="qaddtransize" value="<?=$r[qaddtransize]?>" size="6">
                  KB </td>
              </tr>
              <tr> 
                <td>图片扩展名: 
                  <input name="qaddtranimgtype" type="text" value="<?=$qaddimgtype?>" size="30"> 
                  <font color="#666666"> (多个用&quot;|&quot;格开) </font></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="qaddtranfile" type="checkbox" value="1"<?=$r[qaddtranfile]==1?' checked':''?>>
                  开启上传附件,最大附件： 
                  <input name="qaddtranfilesize" type="text" value="<?=$r[qaddtranfilesize]?>" size="6">
                  KB </td>
              </tr>
              <tr> 
                <td>附件扩展名: 
                  <input name="qaddtranfiletype" type="text" value="<?=$qaddfiletype?>" size="30"> 
                  <font color="#666666">(多个用&quot;|&quot;格开)</font></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">前台反馈附件设置</td>
          <td height="25" bgcolor="#FFFFFF"> <table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="feedbacktfile" type="checkbox" id="feedbacktfile" value="1"<?=$r[feedbacktfile]==1?' checked':''?>>
                  开启上传附件,最大附件： 
                  <input name="feedbackfilesize" type="text" value="<?=$r[feedbackfilesize]?>" size="6">
                  KB </td>
              </tr>
              <tr> 
                <td>附件扩展名: 
                  <input name="feedbackfiletype" type="text" value="<?=$feedbackfiletype?>" size="30"> 
                  <font color="#666666">(多个用&quot;|&quot;格开)</font></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td rowspan="2" valign="top" bgcolor="#FFFFFF">会员表单附件设置</td>
          <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="openmembertranimg" type="checkbox" id="openmembertranimg" value="1"<?=$r[openmembertranimg]==1?' checked':''?>>
                  开启上传图片,最大图片： 
                  <input name="memberimgsize" type="text" id="memberimgsize" value="<?=$r[memberimgsize]?>" size="6">
                  KB </td>
              </tr>
              <tr> 
                <td>图片扩展名: 
                  <input name="memberimgtype" type="text" id="memberimgtype" value="<?=$memberimgtype?>" size="30"> 
                  <font color="#666666"> (多个用&quot;|&quot;格开) </font></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="1" cellpadding="3">
              <tr> 
                <td><input name="openmembertranfile" type="checkbox" id="openmembertranfile" value="1"<?=$r[openmembertranfile]==1?' checked':''?>>
                  开启上传附件,最大附件： 
                  <input name="memberfilesize" type="text" id="memberfilesize" value="<?=$r[memberfilesize]?>" size="6">
                  KB </td>
              </tr>
              <tr> 
                <td>附件扩展名: 
                  <input name="memberfiletype" type="text" id="memberfiletype" value="<?=$memberfiletype?>" size="30"> 
                  <font color="#666666">(多个用&quot;|&quot;格开)</font></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">文件生成权限</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="filechmod" value="0"<?=$r[filechmod]==0?' checked':''?>>
            0777 
            <input type="radio" name="filechmod" value="1"<?=$r[filechmod]==1?' checked':''?>>
            不限制</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">广告JS文件前缀</td>
          <td height="25" bgcolor="#FFFFFF"><input name="adfile" type="text" id="adfile" value="<?=$r[adfile]?>" size="38"></td>
        </tr>
      </table>
		<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">备份设置</td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">数据备份存放目录</td>
          <td height="25" bgcolor="#FFFFFF">admin/ebak/ 
            <input name="bakdbpath" type="text" id="bakdbpath" value="<?=$r[bakdbpath]?>"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">压缩包存放目录</td>
          <td height="25" bgcolor="#FFFFFF">admin/ebak/ 
            <input name="bakdbzip" type="text" id="bakdbzip" value="<?=$r[bakdbzip]?>"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">备份只选择当前数据库</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ebakthisdb" type="checkbox" id="ebakthisdb" value="1"<?=$r[ebakthisdb]==1?' checked':''?>>
            是</td>
        </tr>
		<tr>
          <td height="25" bgcolor="#FFFFFF">空间不支持数据库列表</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ebakcanlistdb" type="checkbox" id="ebakcanlistdb" value="1"<?=$r[ebakcanlistdb]==1?' checked':''?>>
            是<font color="#666666">(如果空间不允许列出数据库,请打勾)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">支持MYSQL查询方式</td>
          <td height="25" bgcolor="#FFFFFF"><input name="limittype" type="checkbox" id="limittype" value="1"<?=$r[limittype]==1?' checked':''?>>
            支持</td>
        </tr>
      </table>
	</div>
	  
    <div class="tab-page" id="dojs"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">JS设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "dojs" ) );</script>
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr class="header"> 
      <td height="25" colspan="2">信息排行设置(JS)</td>
    </tr>
    <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">热门信息显示</td>
      <td height="25" bgcolor="#FFFFFF"><input name="hotnum" type="text" id="hotnum" value="<?=$r[hotnum]?>" size="38">
            条信息</td>
    </tr>
    <tr> 
          <td height="25" bgcolor="#FFFFFF">最新信息显示</td>
      <td height="25" bgcolor="#FFFFFF"><input name="newnum" type="text" id="newnum" value="<?=$r[newnum]?>" size="38">
            条信息</td>
    </tr>
    <tr> 
          <td height="25" bgcolor="#FFFFFF">推荐信息显示</td>
      <td height="25" bgcolor="#FFFFFF"><input name="goodnum" type="text" id="goodnum" value="<?=$r[goodnum]?>" size="38">
            条信息</td>
    </tr>
    <tr> 
          <td height="25" bgcolor="#FFFFFF">热门评论显示</td>
      <td height="25" bgcolor="#FFFFFF"><input name="hotplnum" type="text" id="hotplnum" value="<?=$r[hotplnum]?>" size="38">
            条信息</td>
    </tr>
    <tr> 
          <td height="25" bgcolor="#FFFFFF">头条信息显示</td>
      <td height="25" bgcolor="#FFFFFF"><input name="firstnum" type="text" id="firstnum" value="<?=$r[firstnum]?>" size="38">
            条信息</td>
    </tr>
  </table>
	</div>
	  
    <div class="tab-page" id="rehtml"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">分组生成</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "rehtml" ) );</script>
      <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">分组生成设置（依服务器配置设置大小）</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">每组生成间隔</td>
          <td height="25" bgcolor="#FFFFFF"><input name="realltime" type="text" id="realltime" value="<?=$r[realltime]?>" size="38">
            秒<font color="#666666">(0为连续生成)</font></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">栏目生成每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="relistnum" type="text" id="relistnum" value="<?=$r[relistnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">信息生成每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="renewsnum" type="text" id="renewsnum" value="<?=$r[renewsnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">更新相关链接每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="infolinknum" type="text" id="infolinknum" value="<?=$r[infolinknum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">生成自定义JS每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="reuserjsnum" type="text" id="reuserjsnum" value="<?=$r[reuserjsnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">生成自定义列表每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="reuserlistnum" type="text" id="reuserlistnum" value="<?=$r[reuserlistnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">自定义页面每组</td>
          <td height="25" bgcolor="#FFFFFF"> <input name="reuserpagenum" type="text" id="reuserpagenum" value="<?=$r[reuserpagenum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">投票JS每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="revotejsnum" type="text" id="revotejsnum" value="<?=$r[revotejsnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">广告JS每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="readjsnum" type="text" id="readjsnum" value="<?=$r[readjsnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">替换字段值每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="dorepnum" type="text" id="dorepnum" value="<?=$r[dorepnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">替换地址权限每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="dorepdlevelnum" type="text" id="dorepdlevelnum" value="<?=$r[dorepdlevelnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">批量删除信息每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="delnewsnum" type="text" id="delnewsnum" value="<?=$r[delnewsnum]?>" size="38">
            个</td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF">批量归档信息每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="docnewsnum" type="text" id="docnewsnum" value="<?=$r[docnewsnum]?>" size="38">
            个</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">导入栏目模板每组</td>
          <td height="25" bgcolor="#FFFFFF"><input name="loadtempnum" type="text" id="loadtempnum" value="<?=$r[loadtempnum]?>" size="38">
            个</td>
        </tr>
      </table>
  </div>
    <div class="tab-page" id="setsearch"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">搜索设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "setsearch" ) );</script>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">搜索设置</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">搜索用户组</td>
          <td height="25" bgcolor="#FFFFFF"><select name="searchgroupid" id="searchgroupid">
              <option value=0>游客</option>
              <?=$searchmembergroup?>
            </select></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">搜索关键字</td>
          <td height="25" bgcolor="#FFFFFF">在 
            <input name="min_keyboard" type="text" id="min_keyboard" value="<?=$r[min_keyboard]?>" size="6">
            个字符与 
            <input name="max_keyboard" type="text" id="max_keyboard" value="<?=$r[max_keyboard]?>" size="6">
            个字符之间</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">搜索时间间隔</td>
          <td height="25" bgcolor="#FFFFFF">在 
            <input name="searchtime" type="text" id="searchtime" value="<?=$r[searchtime]?>" size="6">
            秒</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">页面显示</td>
          <td height="25" bgcolor="#FFFFFF">每页 
            <input name="search_num" type="text" id="search_num" value="<?=$r[search_num]?>" size="6">
            显示条记录， 
            <input name="search_pagenum" type="text" id="search_pagenum" value="<?=$r[search_pagenum]?>" size="6">
            个分页链接<font color="#666666">(为0的话，系统默认25条，12个链接)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">支持公共模板变量</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="searchtempvar" value="0"<?=$r['searchtempvar']==0?' checked':''?>>
            不支持 
            <input type="radio" name="searchtempvar" value="1"<?=$r['searchtempvar']==1?' checked':''?>>
            支持<font color="#666666">(搜索模板及动态页面)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">高级搜索页扩展名</td>
          <td height="25" bgcolor="#FFFFFF"><input name="searchtype" type="text" id="searchtype" value="<?=$r[searchtype]?>" size="10"> 
            <font color="#666666"> 
            <select name="select2" onchange="document.form1.searchtype.value=this.value">
              <option value=".html">扩展名</option>
              <option value=".html">.html</option>
              <option value=".htm">.htm</option>
              <option value=".php">.php</option>
              <option value=".shtml">.shtml</option>
            </select>
            (如：.html,.htm,.xml,.php)</font></td>
        </tr>
      </table>
	</div>
	  
    <div class="tab-page" id="donews"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">信息设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "donews" ) );</script>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">信息设置</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">后台管理信息</td>
          <td height="25" bgcolor="#FFFFFF">每页显示 
            <input name="hlistinfonum" type="text" id="hlistinfonum" value="<?=$r[hlistinfonum]?>">
            个信息</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">前台结合项列表</td>
          <td height="25" bgcolor="#FFFFFF">每页显示 
            <input name="qlistinfonum" type="text" id="qlistinfonum" value="<?=$r[qlistinfonum]?>">
            个信息</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">动态列表支持标签</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="dtcanbq" value="0"<?=$r[dtcanbq]==0?' checked':''?>>
            不支持 
            <input type="radio" name="dtcanbq" value="1"<?=$r[dtcanbq]==1?' checked':''?>>
            支持</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">动态列表变量缓存</td>
          <td height="25" bgcolor="#FFFFFF"><input name="dtcachetime" type="text" id="dtcachetime" value="<?=$r[dtcachetime]?>" size="38">
            分钟</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">动态内容页支持标签</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="dtncanbq" value="0"<?=$r[dtncanbq]==0?' checked':''?>>
            不支持 
            <input type="radio" name="dtncanbq" value="1"<?=$r[dtncanbq]==1?' checked':''?>>
            支持</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">动态内容页变量缓存</td>
          <td height="25" bgcolor="#FFFFFF"><input name="dtncachetime" type="text" id="dtncachetime" value="<?=$r[dtncachetime]?>" size="38">
            分钟</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">新会员投稿限制</td>
          <td height="25" bgcolor="#FFFFFF">最新注册会员必须过 
            <input name="newaddinfotime" type="text" id="newaddinfotime" value="<?=$r[newaddinfotime]?>" size="6">
            分钟才能投稿 <font color="#666666">(0为不限制)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">重复投稿时间限制</td>
          <td height="25" bgcolor="#FFFFFF"><input name="readdinfotime" type="text" id="readdinfotime" value="<?=$r[readdinfotime]?>" size="38">
            秒</td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF">投稿信息修改时间限制：</td>
          <td height="25" bgcolor="#FFFFFF"><input name="qeditinfotime" type="text" id="qeditinfotime" value="<?=$r[qeditinfotime]?>" size="38">
            分钟<font color="#666666">(0为不限制)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">栏目导航分隔字符</td>
          <td height="25" bgcolor="#FFFFFF"><input name="classnavfh" type="text" id="navfh3" value="<?=htmlspecialchars($r[classnavfh])?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">栏目导航显示个数</td>
          <td height="25" bgcolor="#FFFFFF"><input name="classnavline" type="text" id="classnavline" value="<?=$r[classnavline]?>" size="38"> 
            <font color="#666666">(0为不限)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">所在位置导航分隔字符</td>
          <td height="25" bgcolor="#FFFFFF"><input name="navfh" type="text" id="navfh" value="<?=$r[navfh]?>" size="38"> 
            <font color="#666666">(如:“首页 &gt; 新闻”中的“&gt;”)</font></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">信息简介截取</td>
          <td height="25" bgcolor="#FFFFFF"> <input name="smalltextlen" type="text" id="smalltextlen" value="<?=$r[smalltextlen]?>" size="38">
            个字<font color="#666666">(简介为空时，截取信息内容)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">相关链接依据</td>
          <td height="25" bgcolor="#FFFFFF"><select name="newslink" id="newslink">
              <option value="0"<?=$r['newslink']==0?' selected':''?>>标题包含关键字</option>
              <option value="1"<?=$r['newslink']==1?' selected':''?>>关键字相同</option>
              <option value="2"<?=$r['newslink']==2?' selected':''?>>标题包含与关键字相同</option>
            </select> </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">信息内容关键字重复替换</td>
          <td height="25" bgcolor="#FFFFFF"><input name="repkeynum" type="text" id="repkeynum" value="<?=$r[repkeynum]?>" size="38">
            次<font color="#666666">(0为不限,效率高；限制替换次数会影响生成效率。)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">评论权限限制</td>
          <td height="25" bgcolor="#FFFFFF"><select name="plgroupid" id="plgroupid">
              <option value=0>游客</option>
              <?=$plgroup?>
            </select></td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">评论内容限制</td>
          <td height="25" bgcolor="#FFFFFF"><input name="plsize" type="text" id="plsize" value="<?=$r[plsize]?>" size="38">
            个字节<font color="#666666">(两个字节为一个汉字)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">评论时间间隔</td>
          <td height="25" bgcolor="#FFFFFF"><input name="pltime" type="text" id="pltime" value="<?=$r[pltime]?>" size="38">
            秒</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">评论验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="plkey_ok" value="1"<?=$r[plkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="plkey_ok" value="0"<?=$r[plkey_ok]==0?' checked':''?>>
            关闭 </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">评论表情每行显示</td>
          <td height="25" bgcolor="#FFFFFF"><input name="plfacenum" type="text" id="plfacenum" value="<?=$r[plfacenum]?>" size="6">
            个表情</td>
        </tr>
        <tr> 
          <td height="25" valign="top" bgcolor="#FFFFFF">评论屏蔽字符<br> <br> <font color="#666666">多个用“|”格开，如“字符1|字符2”</font> 
          </td>
          <td height="25" bgcolor="#FFFFFF"><textarea name="plclosewords" cols="80" rows="8" id="plclosewords"><?=htmlspecialchars($r[plclosewords])?></textarea></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">反馈验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="fbkey_ok" value="1"<?=$r[fbkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="fbkey_ok" value="0"<?=$r[fbkey_ok]==0?' checked':''?>>
            关闭 </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">留言验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="gbkey_ok" value="1"<?=$r[gbkey_ok]==1?' checked':''?>>
            开启 
            <input type="radio" name="gbkey_ok" value="0"<?=$r[gbkey_ok]==0?' checked':''?>>
            关闭 </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">重复留言时间限制</td>
          <td height="25" bgcolor="#FFFFFF"><input name="regbooktime" type="text" id="regbooktime" value="<?=$r[regbooktime]?>" size="38">
            秒</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">重复投票时间限制</td>
          <td height="25" bgcolor="#FFFFFF"><input name="revotetime" type="text" id="revotetime" value="<?=$r[revotetime]?>" size="38">
            秒</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">模板支持程序代码</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="candocode" value="1"<?=$r[candocode]==1?' checked':''?>>
            开启 
            <input type="radio" name="candocode" value="0"<?=$r[candocode]==0?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">防采集</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="opennotcj" value="1"<?=$r[opennotcj]==1?' checked':''?>>
            开启 
            <input type="radio" name="opennotcj" value="0"<?=$r[opennotcj]==0?' checked':''?>>
            关闭</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">内容防复制</td>
          <td height="25" bgcolor="#FFFFFF"> <input type="radio" name="opencopytext" value="1"<?=$r[opencopytext]==1?' checked':''?>>
            开启 
            <input type="radio" name="opencopytext" value="0"<?=$r[opencopytext]==0?' checked':''?>>
            关闭<font color="#666666"> (内容随机字符)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">列表分页函数(下拉)</td>
          <td height="25" bgcolor="#FFFFFF"><input name="listpagefun" type="text" id="listpagefun" value="<?=$r[listpagefun]?>" size="38"> 
            <font color="#666666"> (可加到e/class/userfun.php文件里)</font></td>
        </tr>
        <tr> 
          <td rowspan="2" valign="top" bgcolor="#FFFFFF">列表分页函数(列表)</td>
          <td height="25" bgcolor="#FFFFFF"><input name="listpagelistfun" type="text" id="listpagelistfun" value="<?=$r[listpagelistfun]?>" size="38"> 
            <font color="#666666">(可加到e/class/userfun.php文件里)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">每页显示 
            <input name="listpagelistnum" type="text" id="listpagelistnum" value="<?=$r[listpagelistnum]?>" size="6">
            个页码</td>
        </tr>
        <tr> 
          <td height="25" rowspan="2" bgcolor="#FFFFFF">内容分页函数</td>
          <td height="12" bgcolor="#FFFFFF"><input name="textpagefun" type="text" id="textpagefun" value="<?=$r[textpagefun]?>" size="38"> 
            <font color="#666666">(可加到e/class/userfun.php文件里)</font></td>
        </tr>
        <tr> 
          <td height="12" bgcolor="#FFFFFF">每页显示 
            <input name="textpagelistnum" type="text" id="textpagelistnum" value="<?=$r[textpagelistnum]?>" size="6">
            个页码</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">RSS/XML设置</td>
          <td height="25" bgcolor="#FFFFFF">显示最新 
            <input name="rssnum" type="text" id="rssnum" value="<?=$r[rssnum]?>" size="6">
            条记录，简介截取 
            <input name="rsssub" type="text" id="rsssub" value="<?=$r[rsssub]?>" size="6">
            个字</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">信息外部链接设置</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="opentitleurl" value="0"<?=$r[opentitleurl]==0?' checked':''?>>
            统计点击 
            <input type="radio" name="opentitleurl" value="1"<?=$r[opentitleurl]==1?' checked':''?>>
            显示原链接</td>
        </tr>
      </table>
	</div>
	  
    <div class="tab-page" id="doftp"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">FTP/EMAIL</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "doftp" ) );</script>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">发送邮件设置</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">邮件发送模式</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="sendmailtype" value="0"<?=$r[sendmailtype]==0?' checked':''?>>
            mail 函数发送 
            <input type="radio" name="sendmailtype" value="1"<?=$r[sendmailtype]==1?' checked':''?>>
            SMTP 模块发送</td>
        </tr>
        <tr> 
          <td height="25" colspan="2" bgcolor="#FFFFFF"><strong>SMTP 模块发送设置</strong></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">SMTP服务器</td>
          <td height="25" bgcolor="#FFFFFF"><input name="smtphost" type="text" id="smtphost" value="<?=$r[smtphost]?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">SMTP端口</td>
          <td height="25" bgcolor="#FFFFFF"><input name="smtpport" type="text" id="smtpport" value="<?=$r[smtpport]?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">发信人地址</td>
          <td height="25" bgcolor="#FFFFFF"><input name="fromemail" type="text" id="fromemail" value="<?=$r[fromemail]?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">发信人呢称</td>
          <td height="25" bgcolor="#FFFFFF"><input name="emailname" type="text" id="emailname" value="<?=$r[emailname]?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">是否需要登陆验证</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="loginemail" value="1"<?=$r[loginemail]==1?' checked':''?>>
            是 
            <input type="radio" name="loginemail" value="0"<?=$r[loginemail]==0?' checked':''?>>
            否</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">验证用户名</td>
          <td height="25" bgcolor="#FFFFFF"><input name="emailusername" type="text" id="emailusername" value="<?=$r[emailusername]?>" size="38"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">验证密码</td>
          <td height="25" bgcolor="#FFFFFF"><input name="emailpassword" type="password" id="emailpassword" value="<?=$r[emailpassword]?>" size="38"></td>
        </tr>
        <tr class="header"> 
          <td height="25" colspan="2">FTP设置(远程发布 / PHP运行于安全模式等情况下需设置以下选项)</td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">PHP运行于安全模式</td>
          <td height="25" bgcolor="#FFFFFF"><input name="phpmode" type="checkbox" id="phpmode" value="1"<?=$r[phpmode]==1?' checked':''?>>
            是</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">安装形式</td>
          <td height="25" bgcolor="#FFFFFF"><select name="install" id="select">
              <option value="0"<?=$r[install]==0?' selected':''?>>服务端</option>
              <option value="1"<?=$r[install]==1?' selected':''?>>客户端</option>
            </select> <font color="#666666">(如是远程发布，请选客户端，并且需配置FTP选项)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">启用 SSL 连接</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="ftpssl" value="1"<?=$r[ftpssl]==1?' checked':''?>>
            是 <input type="radio" name="ftpssl" value="0"<?=$r[ftpssl]==0?' checked':''?>>
            否 </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">被动模式(pasv)连接</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="ftppasv" value="1"<?=$r[ftppasv]==1?' checked':''?>>
            是 
            <input type="radio" name="ftppasv" value="0"<?=$r[ftppasv]==0?' checked':''?>>
            否 </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">FTP服务器地址</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ftphost" type="text" id="ftphost" value="<?=$r[ftphost]?>" size="38">
            端口： 
            <input name="ftpport" type="text" id="ftpport" value="<?=$r[ftpport]?>" size="4"></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">FTP用户名</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ftpusername" type="text" id="ftpusername" value="<?=$r[ftpusername]?>" size="38"> 
          </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">FTP密码</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ftppassword" type="password" size="38"> 
            <input name="changeftpp" type="checkbox" id="changeftpp" value="1">
            修改FTP密码<font color="#666666">(要修改请选择) </font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">传送模式</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="ftpmode" value="1"<?=$r[ftpmode]==1?' checked':''?>>
            ASCII <input type="radio" name="ftpmode" value="0"<?=$r[ftpmode]==0?' checked':''?>>
            二进制</td>
        </tr>
        <tr>
          <td height="25" bgcolor="#FFFFFF">FTP 传输超时时间</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ftpouttime" type="text" id="ftpouttime" value="<?=$r[ftpouttime]?>" size="38">
            秒<font color="#666666">(0为服务器默认)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">系统根目录(FTP)</td>
          <td height="25" bgcolor="#FFFFFF"><input name="ftppath" type="text" value="<?=$r[ftppath]?>" size="38">
            <font color="#666666">(目录结尾不要加斜杠“/”，空为根目录)</font></td>
        </tr>
      </table>
	</div>
	
	<div class="tab-page" id="dom"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">模型设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "dom" ) );</script>
	  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">信息投稿屏蔽设置</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td width="22%" height="25" valign="top"><strong>屏蔽字段</strong><br>
            多个用“|”格开，如“title|newstext”<br>
            <br>
            <a href="db/ListTable.php" target="_blank"><font color="#666666">[点击查看字段]</font></a></td>
          <td><textarea name="closewordsf" cols="80" rows="5"><?=$r[closewordsf]?></textarea></td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td height="25" valign="top">
<strong>屏蔽字符列表</strong><br>
              多个用“|”格开，如“字符1|字符2” 
            </td>
          <td><textarea name="closewords" cols="80" rows="8"><?=$r[closewords]?></textarea></td>
        </tr>
      </table>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">新闻/下载/电影模型设置</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">同一地址下载/观看超过</td>
          <td height="25" bgcolor="#FFFFFF"><input name="redodown" type="text" id="redodown" value="<?=$r[redodown]?>" size="38">
            个小时 将重复扣点</td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">同一信息查看超过</td>
          <td height="25" bgcolor="#FFFFFF"><input name="redoview" type="text" id="redoview" value="<?=$r[redoview]?>" size="38">
            个小时 将重复扣点</td>
        </tr>
        <tr> 
          <td width="22%" height="25" bgcolor="#FFFFFF">下载验证码</td>
          <td height="25" bgcolor="#FFFFFF"><input name="downpass" type="text" id="downpass" value="<?=$r[downpass]?>" size="38"> 
            <font color="#666666">(主要用于防盗链,请定期更新一次密码)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">开启直接下载</td>
          <td height="25" bgcolor="#FFFFFF"><input type="radio" name="opengetdown" value="1"<?=$r[opengetdown]==1?' checked':''?>>
            是 
            <input type="radio" name="opengetdown" value="0"<?=$r[opengetdown]==0?' checked':''?>>
            否</td>
        </tr>
      </table>
	  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">商城模型设置</td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td height="25">提交订单权限</td>
          <td><select name="shopddgroupid" id="shopddgroupid">
              <option value="0"<?=$r['shopddgroupid']==0?' selected':''?>>游客</option>
			  <option value="1"<?=$r['shopddgroupid']==1?' selected':''?>>会员才能提交订单</option>
            </select></td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td height="25">购物车数量限制</td>
          <td><select name="buycarnum" id="buycarnum">
              <option value="0"<?=$r['buycarnum']==0?' selected':''?>>不限</option>
              <option value="1"<?=$r['buycarnum']==1?' selected':''?>>只能选择一个商品</option>
            </select></td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td width="22%" height="25">是否提供发票</td>
          <td><input name="havefp" type="checkbox" id="havefp" value="1"<?=$r[havefp]==1?' checked':''?>>
            是,收取 
            <input name="fpnum" type="text" id="fpnum" value="<?=$r[fpnum]?>" size="6">
            % 的发票费</td>
        </tr>
      </table>
    </div>
	
	<div class="tab-page" id="doimage"> 
      <h2 class="tab">&nbsp;<font class="tabcolor">图片设置</font>&nbsp;</h2>
                    <script type="text/javascript">tb1.addTabPage( document.getElementById( "doimage" ) );</script>
	  <table width="100%" border="0" cellspacing="1" cellpadding="3" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">图片缩略图设置</td>
        </tr>
        <tr bgcolor="#FFFFFF"> 
          <td width="22%" height="25">默认值</td>
          <td>宽: 
            <input name="spicwidth" type="text" id="spicwidth" value="<?=$r[spicwidth]?>" size="6">
            ×高: 
            <input name="spicheight" type="text" id="spicheight" value="<?=$r[spicheight]?>" size="6"></td>
        </tr>
        <tr bgcolor="#FFFFFF">
          <td height="25">超出部分是否截取</td>
          <td><input type="radio" name="spickill" value="1"<?=$r['spickill']==1?' checked':''?>>
            是 
            <input type="radio" name="spickill" value="0"<?=$r['spickill']==0?' checked':''?>>
            否</td>
        </tr>
      </table>
	  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <tr class="header"> 
          <td height="25" colspan="2">图片水印设置(不想用图片水印，请留空)</td>
        </tr>
        <tr> 
          <td width="22%" height="25" valign="top" bgcolor="#FFFFFF">水印位置</td>
          <td height="25" bgcolor="#FFFFFF"> <table width="200" border="0" cellpadding="6" cellspacing="1" bgcolor="#CCCCCC">
              <tr bgcolor="#FFFFFF"> 
                <td rowspan="3"> <div align="center"> 
                    <input type="radio" name="markpos" value="0"<?=$r[markpos]==0?' checked':'';?>>
                    <br>
                    随机 </div></td>
                <td> <div align="center"> 
                    <input type="radio" name="markpos" value="1"<?=$r[markpos]==1?' checked':'';?>>
                  </div></td>
                <td> <div align="center"> 
                    <input type="radio" name="markpos" value="2"<?=$r[markpos]==2?' checked':'';?>>
                  </div></td>
                <td> <div align="center"> 
                    <input type="radio" name="markpos" value="3"<?=$r[markpos]==3?' checked':'';?>>
                  </div></td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF"> <div align="center"> 
                    <input type="radio" name="markpos" value="4"<?=$r[markpos]==4?' checked':'';?>>
                  </div></td>
                <td bgcolor="#FFFFFF"> <div align="center"> 
                    <input type="radio" name="markpos" value="5"<?=$r[markpos]==5?' checked':'';?>>
                  </div></td>
                <td bgcolor="#FFFFFF"> <div align="center"> 
                    <input type="radio" name="markpos" value="6"<?=$r[markpos]==6?' checked':'';?>>
                  </div></td>
              </tr>
              <tr> 
                <td bgcolor="#FFFFFF"> <div align="center"> 
                    <input type="radio" name="markpos" value="7"<?=$r[markpos]==7?' checked':'';?>>
                  </div></td>
                <td bgcolor="#FFFFFF"> <div align="center"> 
                    <input type="radio" name="markpos" value="8"<?=$r[markpos]==8?' checked':'';?>>
                  </div></td>
                <td bgcolor="#FFFFFF"> <div align="center"> 
                    <input type="radio" name="markpos" value="9"<?=$r[markpos]==9?' checked':'';?>>
                  </div></td>
              </tr>
            </table></td>
        </tr>
        <tr> 
          <td rowspan="4" valign="top" bgcolor="#FFFFFF">文字水印</td>
          <td height="25" bgcolor="#FFFFFF">文字内容 
            <input name="marktext" type="text" id="marktext" value="<?=$r[marktext]?>"> 
            <font color="#666666">(目前不支持中文)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">文字字体 
            <input name="markfont" type="text" id="markfont" value="<?=$r[markfont]?>"> 
            <font color="#666666">(从后台开始算，如../data就是data目录)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">文字颜色 
            <input name="markfontcolor" type="text" id="markfontcolor" value="<?=$r[markfontcolor]?>"> 
            <a onclick="foreColor();"><img src="../data/images/color.gif" width="21" height="21" align="absbottom"></a> 
          </td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">文字大小 
            <input name="markfontsize" type="text" value="<?=$r[markfontsize]?>"> 
            <font color="#666666">(1~5之间的数字)</font> </td>
        </tr>
        <tr> 
          <td rowspan="3" valign="top" bgcolor="#FFFFFF">图片水印</td>
          <td height="25" bgcolor="#FFFFFF"> 图片文件 
            <input name="markimg" type="text" id="markimg" value="<?=$r[markimg]?>"> 
            <font color="#666666">(从后台开始算，如../data就是data目录)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">图片质量 
            <input name="jpgquality" type="text" id="jpgquality" value="<?=$r[jpgquality]?>"> 
            <font color="#666666">(该值决定 jpg 格式图片的质量，范围从 0 到 100)</font></td>
        </tr>
        <tr> 
          <td height="25" bgcolor="#FFFFFF">水印透明度 
            <input name="markpct" type="text" id="markpct" value="<?=$r[markpct]?>"> 
            <font color="#666666">(该值决定图片水印清晰度，其值范围从 0 到 100)</font></td>
        </tr>
      </table>
	</div>
	
	
	</div>
	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
      <tr> 
        <td height="25" bgcolor="#FFFFFF"> <div align="center">
            <input type="submit" name="Submit" value=" 设置 ">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input type="reset" name="Submit2" value=" 重置 ">
          </div></td>
      </tr>
    </table>
</form>
</body>
</html>
