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
//验证权限
CheckLevel($logininid,$loginin,$classid,"file");
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
<title>管理附件</title>
<link href="../adminstyle/<?=$loginadminstyleid?>/adminstyle.css" rel="stylesheet" type="text/css">
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
    <td width="34%">位置：<a href="FilePath.php">管理附件(目录式)</a></td>
    <td width="66%"><div align="right">
        <input type="button" name="Submit5" value="数据库式管理附件" onclick="self.location.href='ListFile.php?type=9';">
        &nbsp;&nbsp; 
        <input type="button" name="Submit52" value="上传多附件" onclick="self.location.href='TranMoreFile.php';">
      </div></td>
  </tr>
</table>
  
<br>
<table width="100%" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td height="32">当前目录：<strong>/ 
      <?=$filepath?>
      </strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[<a href="#ecms" onclick="javascript:history.go(-1);">返回上一页</a>]</td>
  </tr>
</table>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
  <form name="form1" method="post" action="../ecmsfile.php">
    <tr class="header"> 
      <td height="25"> <div align="center">选择</div></td>
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
			$filelink="'FilePath.php?filepath=".$truefile."'";
			$filename=$file;
			$img="../../data/images/dir/folder.gif";
			$checkbox="";
			$target="";
			//发布时间
			$ftime=@filemtime($pathfile);
			$filetime=date("Y-m-d H:i:s",$ftime);
			$filesize='<目录>';
			$filetype='文件夹';
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
			$checkbox="<input name='filename[]' type='checkbox' value='".$truefile."'>";
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
		}
	 ?>
    <tr bgcolor="#FFFFFF"> 
      <td width="6%" height="25"> 
        <div align="center"> 
          <?=$checkbox?>
        </div></td>
      <td width="47%" height="25"><img src="<?=$img?>" width="23" height="22"><a href=<?=$filelink?><?=$target?>> 
        <?=$filename?>
        </a></td>
      <td width="14%"> 
        <div align="right"> 
          <?=$filesize?>
        </div></td>
      <td width="10%"> 
        <div align="center"> 
          <?=$filetype?>
        </div></td>
      <td width="23%"> 
        <div align="center"> 
          <?=$filetime?>
        </div></td>
    </tr>
    <?
	}
	@closedir($hand);
	?>
    <tr bgcolor="#FFFFFF"> 
      <td height="25"> 
        <div align="center"> 
          <input type="checkbox" name="chkall" value="on" onclick="CheckAll(this.form)">
        </div></td>
      <td height="25" colspan="4"> 
        <input type="submit" name="Submit" value="删除文件"> 
        <input name="enews" type="hidden" id="enews" value="DelPathFile"> <div align="center"></div>
        <div align="center"></div>
        <div align="center"></div></td>
    </tr>
  </form>
</table>
</body>
</html>