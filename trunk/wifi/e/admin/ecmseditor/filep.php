<?php
require("../../class/connect.php");
require("../../class/db_sql.php");
require("../../class/functions.php");
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

//返回按钮事件
function ToReturnDoFilepButton($doing,$tranfrom,$field,$file,$filename,$fileid,$filesize,$filetype,$no,$type){
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
$search="&classid=$classid&filepass=$filepass&type=$type&doing=$doing&tranfrom=$tranfrom&field=$field&fileno=$fileno";

//基目录
$basepath="../../../d/file";
$filepath=$_GET['filepath'];
if(strstr($filepath,".."))
{
	$filepath="";
}
$filepath=eReturnCPath($filepath,'');
$openpath=$basepath."/".$filepath;
if(!file_exists($openpath))
{
	$openpath=$basepath;
}
$hand=@opendir($openpath);
db_close();
$empire=null;
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
</script>
</head>

<body>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
  <tr> 
    <td> 当前目录：<strong>/ 
      <?=$filepath?>
      </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#ecms" onclick="javascript:history.go(-1);">返回上一页</a>]</td>
  </tr>
</table>
<br>
  
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="dofile" method="post" action="../ecmsfile.php">
    <input name="enews" type="hidden" id="enews" value="DelPathFile">
    <tr class="header">
      <td><div align="center">选择</div></td>
      <td height="25"><div align="center">文件名</div></td>
      <td><div align="center">大小</div></td>
      <td><div align="center">类型</div></td>
      <td><div align="center">修改时间</div></td>
    </tr>
    <?php
	while($file=@readdir($hand))
	{
		if(empty($filepath))
		{
			$truefile=$file;
		}
		else
		{
			$truefile=$filepath."/".$file;
		}
		if($file=="."||$file=="..")
		{
			continue;
		}
		//目录
		$pathfile=$openpath."/".$file;
		if(is_dir($pathfile))
		{
			$filelink="'filep.php?filepath=".$truefile.$search."'";
			$filename=$file;
			$img="../../data/images/dir/folder.gif";
			$target="";
			//发布时间
			$ftime=@filemtime($pathfile);
			$filetime=date("Y-m-d H:i:s",$ftime);
			$filesize='<目录>';
			$filetype='文件夹';
			$button="";
		}
		//文件
		else
		{
			$filelink="'../../../d/file/".$truefile."'";
			$filename=$file;
			$ftype=GetFiletype($file);
			$img='../../data/images/dir/'.substr($ftype,1,strlen($ftype))."_icon.gif";
			if(!file_exists($img))
			{
				$img='../../data/images/dir/unknown_icon.gif';
			}
			$target=" target='_blank'";
			//发布时间
			$ftime=@filemtime($pathfile);
			$filetime=date("Y-m-d H:i:s",$ftime);
			//文件大小
			$fsize=@filesize($pathfile);
			$filesize=ChTheFilesize($fsize);
			//文件类型
			if(strstr($tranpicturetype,','.$ftype.','))
			{
				$filetype='图片';
			}
			elseif(strstr($tranflashtype,','.$ftype.','))
			{
				$filetype='FLASH';
			}
			elseif(strstr($mediaplayertype,','.$ftype.',')||strstr($realplayertype,','.$ftype.','))
			{
				$filetype='视频';
			}
			else
			{
				$filetype='附件';
			}
			$furl=$public_r['fileurl'].$truefile;
			$button=ToReturnDoFilepButton($doing,$tranfrom,$field,$furl,$file,0,$filesize,$ftype,'',$type);
		}
	 ?>
    <tr bgcolor="#FFFFFF"> 
      <td width="9%"> 
        <div align="center">
          <?=$button?>
        </div></td>
      <td width="39%" height="25"><img src="<?=$img?>" width="23" height="22"><a href=<?=$filelink?><?=$target?>> 
        <?=$filename?>
        </a></td>
      <td width="20%"> 
        <div align="right"><?=$filesize?></div></td>
      <td width="11%"> 
        <div align="center"><?=$filetype?></div></td>
      <td width="21%"> 
        <div align="center"><?=$filetime?></div></td>
    </tr>
    <?
	}
	@closedir($hand);
	?>
  </form>
</table>
</body>
</html>