<?php
require('../../../../class/connect.php');
$showmod=(int)$_GET['showmod'];
$type=(int)$_GET['type'];
$classid=(int)$_GET['classid'];
$filepass=(int)$_GET['filepass'];
$InstanceName=$_GET['InstanceName'];
$editor=3;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Image Properties</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<script src="../editor/dialog/common/fck_dialog_common.js" type="text/javascript"></script>
	<script src="../editor/dialog/tranpic/fck_image.js" type="text/javascript"></script>
		<script type="text/javascript">

document.write( FCKTools.GetStyleHtml( GetCommonDialogCss() ) ) ;

		</script>
<script type="text/javascript">   
function addpic(){
	var i;
	var str="";
	for(i=1;i<=document.TranMImgForm.trannum.value;i++)
	{
		str=str+"<tr><td width='8%'><div align=center>"+i+"</div></td><td width='92%'> <div align=center><input name=file[] type=file style='width:100%'></div></td></tr>";
	}
	document.getElementById("morepic").innerHTML="<table width='100%' align=center border=0 cellspacing=1 cellpadding=3>"+str+"</table>";
}

function foreColor(thedo){
  if (!Error())	return;
  var arr = showModalDialog("selcolor.html", "", "dialogWidth:18.5em; dialogHeight:17.5em; status:0");
  if (arr != null) 
  {
  	if(thedo==0)
  	{
  		document.TranMImgForm.tbcolor.value=arr;
  	}
  	else
  	{
		document.TranMImgForm.tbbordercolor.value=arr;
  	}
  }
  else 
  {
  	if(thedo==0)
	{
		document.TranMImgForm.tbcolor.focus();
	}
	else
	{
		document.TranMImgForm.tbbordercolor.focus();
	}
  }
}

function DoFile(imgstr){
	oEditor.FCK.InsertHtml(imgstr);
	window.parent.Cancel();
}
</script>
</head>
<body>
<form action="../../ecmseditor.php" method="post" enctype="multipart/form-data" target="UploadWindow" name="TranMImgForm" id="TranMImgForm">
<div id="divTranFile"> 
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr> 
      <td height="25"> <div align="center">上传图片数目： 
          <input name="trannum" type="text" id="trannum" value="8" size="6">
          <input type="button" name="Submit" value="设定" onclick="addpic()">
        </div></td>
    </tr>
    <tr> 
      <td bgcolor="#FFFFFF" id=morepic> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
          <tr> 
            <td width="8%"><div align="center">1</div></td>
            <td width="92%"> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center">2</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
          <tr> 
            <td><div align="center">3</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
		  <tr> 
            <td><div align="center">4</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
		  <tr> 
            <td><div align="center">5</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
		  <tr> 
            <td><div align="center">6</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
		  <tr> 
            <td><div align="center">7</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
		  <tr> 
            <td><div align="center">8</div></td>
            <td> <div align="center"> 
                <input name="file[]" type="file" id="file[]" style='width:100%'>
              </div></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
	
<div id="divSaveFile" style="display: none"> 
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr> 
      <td height="25"> <div align="center">远程保存图片列表(一张图片为一行，比本地上传优先)</div></td>
    </tr>
    <tr> 
      <td><div align="center"> 
          <textarea name="saveurl" style="width: 100%" rows="12" id="saveurl"></textarea>
        </div></td>
    </tr>
  </table>
  <script type="text/javascript">
				document.write( '<iframe name="UploadWindow" style="display: none" src="' + FCKTools.GetVoidUrl() + '"><\/iframe>' ) ;
			</script>
	</div>
	
<div id="divSetTran" style="display: none"> 
  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableborder">
    <tr bgcolor="#FFFFFF"> 
      <td width="112" height="25">每行/页显示</td>
      <td width="351"> <input name="line" type="text" id="line" value="1" size="6">
        个图片</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">对齐方式</td>
      <td> <input name="align" type="radio" value="left">
        居左 
        <input name="align" type="radio" value="center" checked>
        居中 
        <input name="align" type="radio" value="right">
        居右</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">图片大小</td>
      <td> <input name="width" type="text" id="width2" value="300" size="6">
        × 
        <input name="height" type="text" id="height2" value="300" size="6">
        (宽×高)</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">图片边框</td>
      <td> <input name="imgborder" type="text" id="imgborder" value="0" size="6"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">分割形式</td>
      <td> <select name="exptype" id="exptype">
          <option value="0">表格</option>
          <option value="1">分页码</option>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">生成缩略图</td>
      <td><input name="getsmall" type="checkbox" id="getsmall2" value="1">
        同时生成缩略图. 缩图宽度: 
        <input name="swidth" type="text" id="width3" value="<?=$public_r['spicwidth']?>" size="6">
        * 高度: 
        <input name="sheight" type="text" id="sheight" value="<?=$public_r['spicheight']?>" size="6"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">水印</td>
      <td><input name="getmark" type="checkbox" id="getmark2" value="1"> <a href="../../../SetEnews.php" target="_blank">加水印</a></td>
    </tr>
  </table>
</div>
	
<div id="divSetTable" style="display: none"> 
  <table width="100%" border="0" cellspacing="1" cellpadding="3" class=tableborder>
    <tr bgcolor="#FFFFFF"> 
      <td width="25%" height="25">对齐方式</td>
      <td width="75%"> <input name="tbalign" type="radio" value="left">
        居左 
        <input name="tbalign" type="radio" value="center" checked>
        居中 
        <input name="tbalign" type="radio" value="right">
        居右</td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">背景颜色</td>
      <td><input name="tbcolor" type="text" id="tbcolor" size="15"> <a onclick="foreColor(0);"><img src="images/color.gif" width="21" height="21" align="absbottom"></a> 
      </td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">表格宽度</td>
      <td><input name="tbwidth" type="text" id="tbwidth" value="100" size="6"> 
        <select name="tbwidthdw" id="tbwidthdw">
          <option value="%" selected>百分比</option>
          <option value="">像素</option>
        </select></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">单元格</td>
      <td>单元间距: 
        <input name="tbsp" type="text" id="tbsp" value="1" size="6">
        ，单元边距: 
        <input name="tbpa" type="text" id="tbpa" value="3" size="6"></td>
    </tr>
    <tr bgcolor="#FFFFFF"> 
      <td height="25">边框</td>
      <td>边框粗细: 
        <input name="tbborder" type="text" id="tbsp3" value="0" size="6">
        ，边框颜色: 
        <input name="tbbordercolor" type="text" id="tbbordercolor" size="15"> 
        <a onclick="foreColor(1);"><img src="images/color.gif" width="21" height="21" align="absbottom"></a> 
      </td>
    </tr>
  </table>
	</div>
	<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1">
    <tr>
      <td height="38"><div align="center">
          <input type="submit" name="Submit2" value=" 上 传 ">&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="reset" name="Submit3" value="取消" onclick="window.parent.Cancel();">
          <input type=hidden name=classid value="<?=$classid?>">
          <input type=hidden name=filepass value="<?=$filepass?>">
          <input type=hidden name=enews value="SaveMoreImg">
          <input type=hidden name=type value="1">
          <input type=hidden name=doing value="<?=$doing?>">
		  <input type=hidden name=tranfrom value="1">
		  <input type=hidden name=InstanceName value="<?=$InstanceName?>">
        </div></td>
    </tr>
  </table>
</form>
</body>
</html>
