<?php
if(!defined('InEmpireCMS'))
{
	exit();
}

$i=0;
$line=3;//每行显示图片数
$width=100;
$height=80;
$sub=23;//编号截取数
while($r=$empire->fetch($sql))
{
	$ono=$r[no];
	$r[no]=sub($r[no],0,$sub,false);
	$filesize=ChTheFilesize($r[filesize]);//文件大小
	$filetype=GetFiletype($r[filename]);//取得文件扩展名
	$i++;
	if(($i-1)%$line==0||$i==1)
	{
		$class_text.="<tr bgcolor='#DBEAF5'>";
	}
	//文件
	$fspath=ReturnFileSavePath($r[classid],$r[fpath]);
	$filepath=$r[path]?$r[path].'/':$r[path];
	$file=$fspath['fileurl'].$filepath.$r[filename];
	$button=ToReturnDoFileButton($doing,$tranfrom,$field,$file,$r[filename],$r[fileid],$filesize,$filetype,$ono,$type);
	$class_text.="<td><table width='100%' border='0' cellspacing='1' cellpadding='2'>
  <tr>
    <td><div align='center'><a href='../../ViewImg/index.html?url=".$file."' target='_blank' title='预览:".$r[filename]."'><img src='".$file."' width='".$width."' height='".$height."' border=0></a></div></td>
  </tr>
  <tr>
    <td><div align='center'><a title='".$ono."'>".$r[no]."</a></div></td>
  </tr>
  <tr>
    <td><div align='center'><input type=checkbox name=fileid[] value='$r[fileid]'>&nbsp;&nbsp;".$button."</div></td>
  </tr>
</table></td>";
	//分割
	if($i%$line==0)
	{
		$class_text.="</tr>";
	}
}
if($i<>0)
{
	$table="<table width='100%' border=0 cellpadding=3 cellspacing=1 class='tableborder'>
				<tr class='header'>
					<td>图片</td>
				</tr>
				<tr>
					<td bgcolor='#FFFFFF'><table width='100%' border=1 align=center cellpadding=2 cellspacing=1 bordercolor='#FFFFFF' bgcolor='#FFFFFF'>";
	$table1="</table></td>
				</tr>
				<tr>
					<td bgcolor='#FFFFFF'>
					&nbsp;&nbsp;".$returnpage."
					</td>
				</tr></table>";
	$ys=$line-$i%$line;
	$p=0;
	for($j=0;$j<$ys&&$ys!=$line;$j++)
	{
		$p=1;
		$class_text.="<td>&nbsp;</td>";
	}
	if($p==1)
	{
		$class_text.="</tr>";
	}
}
$text=$table.$class_text.$table1;
echo"$text";
?>
  <table width="100%" border="0" cellspacing="1" cellpadding="3">
    <tr>
      <td height="25">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="getmark" type="checkbox" id="getmark" value="1">
        <a href="../SetEnews.php" target="_blank">加水印</a>,
        <input name="getsmall" type="checkbox" id="getsmall" value="1">
        生成缩略图:缩图宽度: 
        <input name="width" type="text" id="width" value="105" size="6">
        * 高度: 
        <input name="height" type="text" id="height" value="118" size="6">
        <input type="submit" name="Submit" value="操作选中图片">
        &nbsp;&nbsp;<input type="submit" name="Submit3" value="删除选中" onclick="document.dofile.enews.value='TDelFile_all';"><input type="checkbox" name="chkall" value="on" onclick="CheckAll(this.form)">全选 </td>
    </tr>
  </table>