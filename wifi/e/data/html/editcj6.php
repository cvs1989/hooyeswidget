<?php
if(!defined('InEmpireCMS'))
{
	exit();
}
?><tr><td bgcolor=ffffff>商品名称</td><td bgcolor=ffffff><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#DBEAF5">
<tr> 
  <td height="25" bgcolor="#FFFFFF">
<?=$tts?"<select name='ttid'><option value='0'>标题分类</option>$tts</select>":""?>
	<input type=text name=title value="<?=htmlspecialchars(stripSlashes($r[title]))?>" size="60"> 
	<input type="button" name="button" value="图文" onclick="document.add.title.value=document.add.title.value+'(图文)';"> 
  </td>
</tr>
<tr> 
  <td height="25" bgcolor="#FFFFFF">属性: 
	<input name="titlefont[b]" type="checkbox" value="b"<?=$titlefontb?>>粗体
	<input name="titlefont[i]" type="checkbox" value="i"<?=$titlefonti?>>斜体
	<input name="titlefont[s]" type="checkbox" value="s"<?=$titlefonts?>>删除线
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;颜色: <input name="titlecolor" type="text" value="<?=stripSlashes($r[titlecolor])?>" size="10"><a onclick="foreColor();"><img src="../data/images/color.gif" width="21" height="21" align="absbottom"></a>
  </td>
</tr>
</table></td></tr><tr><td bgcolor=ffffff>发布时间</td><td bgcolor=ffffff><input name="newstime" type="text" value="<?=$r[newstime]?>"><input type=button name=button value="设为当前时间" onclick="document.add.newstime.value='<?=$todaytime?>'"></td></tr><tr><td bgcolor=ffffff>商品编号</td><td bgcolor=ffffff><input name="productno" type="text" size=60 id="productno" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[productno]))?>">
</td></tr><tr><td bgcolor=ffffff>品牌</td><td bgcolor=ffffff><input name="pbrand" type="text" size=60 id="pbrand" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[pbrand]))?>">
</td></tr><tr><td bgcolor=ffffff>简单描述</td><td bgcolor=ffffff><textarea name="intro" cols="80" rows="10" id="intro"><?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[intro]))?></textarea>
</td></tr><tr><td bgcolor=ffffff>计量单位</td><td bgcolor=ffffff><input name="unit" type="text" size=60 id="unit" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[unit]))?>">
</td></tr><tr><td bgcolor=ffffff>单位重量</td><td bgcolor=ffffff><input name="weight" type="text" size=60 id="weight" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[weight]))?>">
</td></tr><tr><td bgcolor=ffffff>市场价格</td><td bgcolor=ffffff><input name="tprice" type="text" size=60 id="tprice" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[tprice]))?>">
</td></tr><tr><td bgcolor=ffffff>购买价格</td><td bgcolor=ffffff><input name="price" type="text" size=60 id="price" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[price]))?>">
</td></tr><tr><td bgcolor=ffffff>积分购买</td><td bgcolor=ffffff><input name="buyfen" type="text" size=60 id="buyfen" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[buyfen]))?>">
</td></tr><tr><td bgcolor=ffffff>库存</td><td bgcolor=ffffff><input name="pmaxnum" type="text" size=60 id="pmaxnum" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[pmaxnum]))?>">
</td></tr><tr><td bgcolor=ffffff>商品缩略片</td><td bgcolor=ffffff>
<input name="titlepic" type="text" id="titlepic" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[titlepic]))?>" size="45">
<a onclick="window.open('ecmseditor/FileMain.php?type=1&classid=<?=$classid?>&filepass=<?=$filepass?>&doing=1&field=titlepic','','width=700,height=550,scrollbars=yes');" title="选择已上传的图片"><img src="../data/images/changeimg.gif" border="0" align="absbottom"></a> 
</td></tr><tr><td bgcolor=ffffff>商品大图</td><td bgcolor=ffffff>
<input name="productpic" type="text" id="productpic" value="<?=$ecmsfirstpost==1?"":htmlspecialchars(stripSlashes($r[productpic]))?>" size="45">
<a onclick="window.open('ecmseditor/FileMain.php?type=1&classid=<?=$classid?>&filepass=<?=$filepass?>&doing=1&field=productpic','','width=700,height=550,scrollbars=yes');" title="选择已上传的图片"><img src="../data/images/changeimg.gif" border="0" align="absbottom"></a> 
</td></tr><tr><td bgcolor=ffffff>商品介绍</td><td bgcolor=ffffff>
<?=ECMS_ShowEditorVar("newstext",$ecmsfirstpost==1?"":stripSlashes($r[newstext]),"Default","","300","100%")?>
<table width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#DBEAF5">
          <tr> 
            <td bgcolor="#FFFFFF"> <input name="dokey" type="checkbox" value="1"<?=$r[dokey]==1?' checked':''?>>
              关键字替换&nbsp;&nbsp; <input name="copyimg" type="checkbox" id="copyimg" value="1">
      远程保存图片(
      <input name="mark" type="checkbox" id="mark" value="1">
      <a href="SetEnews.php" target="_blank">加水印</a>)&nbsp;&nbsp; 
      <input name="copyflash" type="checkbox" id="copyflash" value="1">
      远程保存FLASH(地址前缀： 
      <input name="qz_url" type="text" id="qz_url" size="">
              )</td>
          </tr>
          <tr>
            
    <td bgcolor="#FFFFFF">自动分页 
      <input name="autopage" type="checkbox" id="autopage" value="1">
      ,每 
      <input name="autosize" type="text" id="autosize" value="5000" size="5">
      个字节为一页&nbsp;&nbsp; 取第 
      <input name="getfirsttitlepic" type="text" id="getfirsttitlepic" value="" size="1">
      张上传图为标题图片( 
      <input name="getfirsttitlespic" type="checkbox" id="getfirsttitlespic" value="1">
      缩略图: 宽 
      <input name="getfirsttitlespicw" type="text" id="getfirsttitlespicw" size="3" value="<?=$public_r[spicwidth]?>">
      *高
      <input name="getfirsttitlespich" type="text" id="getfirsttitlespich" size="3" value="<?=$public_r[spicheight]?>">
      )</td>
          </tr>
        </table>
</td></tr>