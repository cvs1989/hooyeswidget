<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
require("../../data/dbcache/class.php");
require "../".LoadLang("pub/fun.php");
$link=db_connect();
$empire=new mysqlquery();
$editor=1;
//验证
$lur=is_login();
$logininid=$lur['userid'];
$loginin=$lur['username'];
$loginrnd=$lur['rnd'];
$loginlevel=$lur['groupid'];
$loginadminstyleid=$lur['adminstyleid'];

//返回按钮事件
function ToReturnDoFileButton($doing,$tranfrom,$field,$file,$filename,$fileid,$filesize,$filetype,$no,$type){
	if($doing==1)//返回地址
	{
		$button="<input type=button name=button value='选择' onclick=\"javascript:ChangeFile1(1,'".$file."');\">";
	}
	elseif($doing==2)//返回地址
	{
		$button="<input type=button name=button value='选择' onclick=\"javascript:ChangeFile1(2,'".$file."');\">";
	}
	else
	{
		if($tranfrom==1)//编辑器选择
		{
			$button="<input type=button name=button value='选择' onclick=\"javascript:EditorChangeFile('".$file."','".addslashes($filename)."','".$filetype."','".$filesize."','".addslashes($no)."');\">";
		}
		elseif($tranfrom==2)//特殊字段选择
		{
			$button="<input type=button name=button value='选择' onclick=\"javascript:SFormIdChangeFile('".addslashes($no)."','$file','$filesize','$filetype','$field');\">";
		}
		else
		{
			$button="<input type=button name=button value='插入' onclick=\"javascript:InsertFile('".$file."','".addslashes($filename)."','".$fileid."','".$filesize."','".$filetype."','','".$type."');\">";
		}
	}
	return $button;
}

$classid=(int)$_GET['classid'];
$filepass=(int)$_GET['filepass'];
$type=(int)$_GET['type'];
$doing=(int)$_GET['doing'];
$field=$_GET['field'];
$tranfrom=$_GET['tranfrom'];
$fileno=$_GET['fileno'];
if(empty($field))
{
	$field="ecms";
}
$add="";
//栏目
$searchclassid=$_GET['searchclassid'];
if($searchclassid=='all')
{
	$searchclassid=0;
	$searchvarclassid='all';
}
else
{
	$searchclassid=$searchclassid?$searchclassid:$classid;
	$searchvarclassid=$searchclassid;
}
$searchclassid=(int)$searchclassid;
if($searchclassid)
{
	$add.=" and classid='$searchclassid'";
}
//关键字
$keyboard=RepPostVar2($_GET['keyboard']);
if(!empty($keyboard))
{
	$show=$_GET['show'];
	if($show==0)//搜索全部
	{
		$add.=" and (filename like '%$keyboard%' or no like '%$keyboard%' or adduser like '%$keyboard%')";
	}
	elseif($show==1)//搜索文件名
	{
		$add.=" and filename like '%$keyboard%'";
	}
	elseif($show==2)//搜索编号
	{
		$add.=" and no like '%$keyboard%'";
	}
	else//搜索上传者
	{
		$add.=" and adduser like '%$keyboard%'";
	}
}
$search="&classid=$classid&filepass=$filepass&type=$type&doing=$doing&tranfrom=$tranfrom&field=$field&show=$show&searchclassid=$searchvarclassid&keyboard=$keyboard&fileno=$fileno";
//分页
$page=(int)$_GET['page'];
$start=0;
$line=25;//每页显示条数
if($type==1)//图片
{
	$line=12;
}
$page_line=12;//每页显示链接数
$offset=$page*$line;//总偏移量
$query="select fileid,filename,filesize,path,filetime,classid,no,fpath from {$dbtbpre}enewsfile where type='$type'".$add;
$totalquery="select count(*) as total from {$dbtbpre}enewsfile where type='$type'".$add;
$num=$empire->gettotal($totalquery);//取得总条数
$query.=" order by fileid desc limit $offset,$line";
$sql=$empire->query($query);
$returnpage=page2($num,$line,$page_line,$start,$page,$search);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择文件</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
<script>
function InsertFile(filename,fname,fileid,filesize,filetype,fileno,dotype){
	var vstr="";
	if(dotype!=undefined)
	{
		vstr=showModalDialog("infoeditor/epage/insertfile.php?ecms="+dotype+"&fname="+fname+"&fileid="+fileid+"&filesize="+filesize+"&filetype="+filetype+"&filename="+filename, "", "dialogWidth:45.5em; dialogHeight:27.5em; status:0");
		if(vstr==undefined)
		{
			return false;
		}
	}
	parent.opener.DoFile(vstr);
	parent.window.close();
}
function TInsertFile(vstr){
	parent.opener.DoFile(vstr);
	parent.window.close();
}
//选择字段
function ChangeFile1(obj,str){
<?php
if(strstr($field,'.'))
{
?>
	parent.<?=$field?>.value=str;
<?php
}
else
{
?>
	if(obj==1)
	{
		parent.opener.document.add.<?=$field?>.value=str;
	}
	else
	{
		parent.opener.document.form1.<?=$field?>.value=str;
	}
<?php
}
?>
	parent.window.close();
}
//编辑器选择
function EditorChangeFile(fileurl,filename,filetype,filesize,name){
	parent.opener.OnUploadCompleted(2,fileurl,filename,'',name,filesize);
<?php
if($type==1)
{
?>
	if(parent.opener.document.getElementById('txtAlt').value=='')
	{
		parent.opener.document.getElementById('txtAlt').value=name;
	}
<?php
}
elseif($type==0)
{
?>
	if(parent.opener.document.getElementById('fname').value=='')
	{
		parent.opener.document.getElementById('fname').value=name;
	}
	if(parent.opener.document.getElementById('filesize').value=='')
	{
		parent.opener.document.getElementById('filesize').value=filesize;
	}
<?php
}
?>
	parent.window.close();
}
//变量层选择
function SFormIdChangeFile(name,url,filesize,filetype,idvar){
	parent.opener.doSpChangeFile(name,url,filesize,filetype,idvar);
	parent.window.close();
}
//全选
function CheckAll(form){
  for(var i=0;i<form.elements.length;i++)
  {
    var e = form.elements[i];
    if (e.name != 'chkall')
       e.checked = form.chkall.checked;
    }
}

//返回编号
function ExpStr(str,exp){
	var pos,len,ext;
	pos=str.lastIndexOf(exp)+1;
	len=str.length;
	ext=str.substring(pos,len);
	return ext;
}
function ReturnFileNo(obj){
	var filename,str,exp;
	if(obj.no.value!='')
	{
		return '';
	}
	if(obj.file.value!='')
	{
		str=obj.file.value;
	}
	else
	{
		str=obj.tranurl.value;
	}
	if(str.indexOf("\\")>=0)
	{
		exp="\\";
	}
	else
	{
		exp="/";
	}
	filename=ExpStr(str,exp);
	obj.no.value=filename;
}
</script>
</head>
<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr valign="top"> 
    <td width="68%"> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
        <form action="ecmseditor.php" target="ECUploadWindow" method="post" enctype="multipart/form-data" name="etform" onsubmit="return ReturnFileNo(document.etform);">
          <input type=hidden name=classid value="<?=$classid?>">
          <input type=hidden name=filepass value="<?=$filepass?>">
          <input type=hidden name=enews value="TranFile">
          <input type=hidden name=type value="<?=$type?>">
          <input type=hidden name=doing value="<?=$doing?>">
          <tr class="header"> 
            <td colspan="2">上传</td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td width="16%">远程保存</td>
            <td width="84%"><input name="tranurl" type="text" id="tranurl" value="http://" size="36"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td>本地上传</td>
            <td><input name="file" type="file" size="32"> </td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td>文件别名</td>
            <td><input name="no" type="text" id="no" value="<?=$_GET['fileno']?>" size="36"> 
            </td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td>图片选项</td>
            <td> <input name="getmark" type="checkbox" id="getmark" value="1"> 
              <a href="../SetEnews.php" target="_blank">加水印</a> <input name="getsmall" type="checkbox" id="getsmall" value="1">
              生成缩略图：宽度 <input name="width" type="text" id="width" value="<?=$public_r['spicwidth']?>" size="6">
              * 高度 <input name="height" type="text" id="height" value="<?=$public_r['spicheight']?>" size="6"></td>
          </tr>
          <tr bgcolor="#FFFFFF"> 
            <td>&nbsp;</td>
            <td><input type="submit" name="Submit3" value="上传"></td>
          </tr>
        </form>
      </table>
	  <script type="text/javascript">
					document.write( '<iframe name="ECUploadWindow" style="DISPLAY: none" src="images/blank.html"><\/iframe>' ) ;
	  </script>
	  </td>
  </tr>
  <tr> 
    <td> <div align="center"> </div></td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0">
  <form name="searchfile" method="get" action="file.php">
  <input type=hidden name=type value="<?=$type?>">
  <input type=hidden name=classid value="<?=$classid?>">
  <input type=hidden name=filepass value="<?=$filepass?>">
  <input type=hidden name=userid value="<?=$logininid?>">
  <input type=hidden name=username value="<?=$loginin?>">
  <input type=hidden name=rnd value="<?=$loginrnd?>">
  <input type=hidden name=doing value="<?=$doing?>">
  <input type=hidden name=tranfrom value="<?=$tranfrom?>">
  <input type=hidden name=field value="<?=$field?>">
  <input type=hidden name=fileno value="<?=$fileno?>">
    <tr> 
      <td><div align="center">搜索： 
          <input name="keyboard" type="text" id="keyboard" value="<?=$keyboard?>">
          <select name="show" id="show">
		  <option value="0">不限</option>
		  <option value="1">文件名</option>
		  <option value="2" selected>编号</option>
		  <option value="3">上传者</option>
          </select>
		  <span id="fileclassnav"></span>
          <input type="submit" name="Submit2" value="搜索">
        </div></td>
    </tr>
  </form>
</table>
<form name="dofile" method="post" action="../ecmsfile.php" onsubmit="return confirm('确认要操作?');">
<input type=hidden name=enews value="DoMarkSmallPic">
  <input type=hidden name=type value="<?=$type?>">
  <input type=hidden name=classid value="<?=$classid?>">
  <input type=hidden name=searchclassid value="<?=$searchclassid?>">
  <input type=hidden name=filepass value="<?=$filepass?>">
  <input type=hidden name=doing value="<?=$doing?>">
  <input type=hidden name=field value="<?=$field?>">
<?
if($type==1)//图片
{
	include('fileinc/editorpic.php');
}
elseif($type==2)//flash
{
	include('fileinc/editorflash.php');
}
elseif($type==3)//多媒体文件
{
	include('fileinc/editormedia.php');
}
else//附件
{
	include('fileinc/editorfile.php');
}
?>
</form>
<IFRAME frameBorder="0" id="showclassnav" name="showclassnav" scrolling="no" src="../ShowClassNav.php?ecms=4&classid=<?=$searchclassid?>" style="HEIGHT:0;VISIBILITY:inherit;WIDTH:0;Z-INDEX:1"></IFRAME>
</body>
</html>
<?
db_close();
$empire=null;
?>