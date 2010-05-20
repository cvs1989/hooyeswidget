<?php
if(!defined('InEmpireCMS'))
{
	exit();
}

//建立数据表
$tablename=$dbtbpre."ecms_".$tbname;
$sql=$empire->query(SetCreateTable("CREATE TABLE `".$tablename."` (
  `id` int(11) NOT NULL auto_increment,
  `classid` smallint(6) NOT NULL default '0',
  `onclick` int(11) NOT NULL default '0',
  `newspath` varchar(30) NOT NULL default '',
  `keyboard` varchar(255) NOT NULL default '',
  `keyid` varchar(255) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ztid` varchar(255) NOT NULL default '',
  `checked` tinyint(1) NOT NULL default '0',
  `istop` tinyint(1) NOT NULL default '0',
  `truetime` int(10) NOT NULL default '0',
  `ismember` tinyint(1) NOT NULL default '0',
  `dokey` tinyint(1) NOT NULL default '0',
  `userfen` int(11) NOT NULL default '0',
  `isgood` tinyint(1) NOT NULL default '0',
  `titlefont` varchar(20) NOT NULL default '',
  `titleurl` varchar(200) NOT NULL default '',
  `filename` varchar(60) NOT NULL default '',
  `groupid` smallint(6) NOT NULL default '0',
  `newstempid` smallint(6) NOT NULL default '0',
  `plnum` int(11) NOT NULL default '0',
  `firsttitle` tinyint(1) NOT NULL default '0',
  `isqf` tinyint(1) NOT NULL default '0',
  `totaldown` int(11) NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `newstime` int(10) NOT NULL default '0',
  `titlepic` varchar(200) NOT NULL default '',
  `closepl` tinyint(1) NOT NULL default '0',
  `havehtml` tinyint(1) NOT NULL default '0',
  `lastdotime` int(10) NOT NULL default '0',
  `haveaddfen` tinyint(1) NOT NULL default '0',
  `infopfen` int(11) NOT NULL default '0',
  `infopfennum` int(11) NOT NULL default '0',
  `votenum` int(11) NOT NULL default '0',
  `stb` varchar(6) NOT NULL default '1',
  `copyids` varchar(255) NOT NULL default '',
  `ttid` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `checked` (`checked`),
  KEY `newstime` (`newstime`),
  KEY `truetime` (`truetime`),
  KEY `classid` (`classid`),
  KEY `ttid` (`ttid`)
  ) TYPE=MyISAM;",$phome_db_dbchar));

//副表
$tablename=$dbtbpre."ecms_".$tbname."_data_1";
$sqldata=$empire->query(SetCreateTable("CREATE TABLE `".$tablename."` (
  `id` int(11) NOT NULL default '0',
  `classid` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `classid` (`classid`)
  ) TYPE=MyISAM;",$phome_db_dbchar));

//字段表数据
$empire->query("insert into `{$dbtbpre}enewsf` values(NULL,'title','标题','text','<table width=\\\\\"100%\\\\\" border=\\\\\"0\\\\\" cellpadding=\\\\\"0\\\\\" cellspacing=\\\\\"0\\\\\" bgcolor=\\\\\"#DBEAF5\\\\\">\r\n<tr> \r\n  <td height=\\\\\"25\\\\\" bgcolor=\\\\\"#FFFFFF\\\\\">\r\n<?=\$tts?\\\\\"<select name=\\\\''ttid\\\\''><option value=\\\\''0\\\\''>标题分类</option>\$tts</select>\\\\\":\\\\\"\\\\\"?>\r\n	<input type=text name=title value=\\\\\"<?=htmlspecialchars(stripSlashes(\$r[title]))?>\\\\\" size=\\\\\"60\\\\\"> \r\n	<input type=\\\\\"button\\\\\" name=\\\\\"button\\\\\" value=\\\\\"图文\\\\\" onclick=\\\\\"document.add.title.value=document.add.title.value+\\\\''(图文)\\\\'';\\\\\"> \r\n  </td>\r\n</tr>\r\n<tr> \r\n  <td height=\\\\\"25\\\\\" bgcolor=\\\\\"#FFFFFF\\\\\">属性: \r\n	<input name=\\\\\"titlefont[b]\\\\\" type=\\\\\"checkbox\\\\\" value=\\\\\"b\\\\\"<?=\$titlefontb?>>粗体\r\n	<input name=\\\\\"titlefont[i]\\\\\" type=\\\\\"checkbox\\\\\" value=\\\\\"i\\\\\"<?=\$titlefonti?>>斜体\r\n	<input name=\\\\\"titlefont[s]\\\\\" type=\\\\\"checkbox\\\\\" value=\\\\\"s\\\\\"<?=\$titlefonts?>>删除线\r\n	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;颜色: <input name=\\\\\"titlecolor\\\\\" type=\\\\\"text\\\\\" value=\\\\\"<?=stripSlashes(\$r[titlecolor])?>\\\\\" size=\\\\\"10\\\\\"><a onclick=\\\\\"foreColor();\\\\\"><img src=\\\\\"../data/images/color.gif\\\\\" width=\\\\\"21\\\\\" height=\\\\\"21\\\\\" align=\\\\\"absbottom\\\\\"></a>\r\n  </td>\r\n</tr>\r\n</table>','标题','0','1','1','  <tr bgcolor=\"#FFFFFF\"> \r\n    <td height=\"22\" valign=\"top\"><strong>[!--enews.name--]正则：</strong><br>\r\n      (<input name=\"textfield\" type=\"text\" id=\"textfield\" value=\"[!--title--]\" size=\"20\">)</td>\r\n    <td><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">\r\n        <tr> \r\n          <td><textarea name=\"add[zz_title]\" cols=\"60\" rows=\"10\" id=\"textarea\"><?=htmlspecialchars(stripSlashes(\$r[zz_title]))?></textarea></td>\r\n        </tr>\r\n        <tr> \r\n          <td><input name=\"add[z_title]\" type=\"text\" id=\"add[z_title]\" value=\"<?=stripSlashes(\$r[z_title])?>\">\r\n            (如填写这里，将为字段的值)</td>\r\n        </tr>\r\n      </table></td>\r\n  </tr>','0','VARCHAR','200','1','$tid','$tbname','0','','0','0','0','<input name=\\\\\"title\\\\\" type=\\\\\"text\\\\\" size=\\\\\"30\\\\\" value=\\\\\"<?=DoReqValue(\$mid,\\\\''title\\\\'',stripSlashes(\$r[title]))?>\\\\\">','0','','0','60','0','0','','','','','','','0','0');");

$empire->query("insert into `{$dbtbpre}enewsf` values(NULL,'special.field','特殊属性','','<table width=\\\\\"100%\\\\\" border=\\\\\"0\\\\\" cellpadding=\\\\\"0\\\\\" cellspacing=\\\\\"0\\\\\" bgcolor=\\\\\"#DBEAF5\\\\\">\r\n  <tr>\r\n    <td height=\\\\\"25\\\\\" bgcolor=\\\\\"#FFFFFF\\\\\">信息属性: \r\n      <input name=\\\\\"isgood\\\\\" type=\\\\\"checkbox\\\\\" value=\\\\\"1\\\\\"<?=\$r[isgood]?\\\\'' checked\\\\'':\\\\''\\\\''?>>推荐\r\n	  &nbsp;&nbsp; <input name=\\\\\"checked\\\\\" type=\\\\\"checkbox\\\\\" value=\\\\\"1\\\\\"<?=\$r[checked]?\\\\'' checked\\\\'':\\\\''\\\\''?>>审核\r\n	  &nbsp;&nbsp; <input name=\\\\\"firsttitle\\\\\" type=\\\\\"checkbox\\\\\" value=\\\\\"1\\\\\"<?=\$r[firsttitle]?\\\\'' checked\\\\'':\\\\''\\\\''?>>头条\r\n	</td>\r\n  </tr>\r\n  <tr> \r\n    <td height=\\\\\"25\\\\\" bgcolor=\\\\\"#FFFFFF\\\\\">关键字&nbsp;&nbsp;&nbsp;: \r\n      <input name=\\\\\"keyboard\\\\\" type=\\\\\"text\\\\\" size=\\\\\"49\\\\\" value=\\\\\"<?=stripSlashes(\$r[keyboard])?>\\\\\">\r\n      <font color=\\\\\"#666666\\\\\">(多个请用&quot;,&quot;格开)</font></td>\r\n  </tr>\r\n  <tr> \r\n    <td height=\\\\\"25\\\\\" bgcolor=\\\\\"#FFFFFF\\\\\">外部链接: \r\n      <input name=\\\\\"titleurl\\\\\" type=\\\\\"text\\\\\" value=\\\\\"<?=stripSlashes(\$r[titleurl])?>\\\\\" size=\\\\\"49\\\\\">\r\n      <font color=\\\\\"#666666\\\\\">(填写后信息连接地址将为此链接)</font></td>\r\n  </tr>\r\n</table>','特殊属性','0','1','0','','0','','0','0','$tid','$tbname','0','','0','0','0','关键字: \r\n      <input name=\\\\\"keyboard\\\\\" type=\\\\\"text\\\\\" value=\\\\\"<?=stripSlashes(\$r[keyboard])?>\\\\\">\r\n      <font color=\\\\\"#666666\\\\\">(多个请用&quot;,&quot;格开)</font>','0','','0','','0','0','','','','','','','0','0');");

$empire->query("insert into `{$dbtbpre}enewsf` values(NULL,'titlepic','标题图片','img','<input name=\\\\\"titlepic\\\\\" type=\\\\\"text\\\\\" value=\\\\\"<?=stripSlashes(\$r[titlepic])?>\\\\\" size=\\\\\"60\\\\\">\r\n<a onclick=\\\\\"window.open(\\\\''ecmseditor/FileMain.php?type=1&classid=<?=\$classid?>&filepass=<?=\$filepass?>&doing=1&field=titlepic\\\\'',\\\\''\\\\'',\\\\''width=700,height=550,scrollbars=yes\\\\'');\\\\\" title=\\\\\"选择已上传的图片\\\\\"><img src=\\\\\"../data/images/changeimg.gif\\\\\" width=\\\\\"22\\\\\" height=\\\\\"22\\\\\" border=\\\\\"0\\\\\" align=\\\\\"absbottom\\\\\"></a>','标题图片','0','1','1','  <tr bgcolor=\"#FFFFFF\"> \r\n    <td height=\"22\" valign=\"top\"><strong>[!--enews.name--]正则：</strong><br>\r\n      ( \r\n      <input name=\"textfield\" type=\"text\" id=\"textfield\" value=\"[!--titlepic--]\" size=\"20\">\r\n      )</td>\r\n    <td><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">\r\n    <tr>\r\n      <td>附件前缀 \r\n        <input name=\"add[qz_titlepic]\" type=\"text\" id=\"add[qz_titlepic]\" value=\"<?=stripSlashes(\$r[qz_titlepic])?>\"> \r\n        <input name=\"add[save_titlepic]\" type=\"checkbox\" id=\"add[save_titlepic]\" value=\" checked\"<?=\$r[save_titlepic]?>>\r\n        远程保存 </td>\r\n    </tr>\r\n    <tr> \r\n      <td><textarea name=\"add[zz_titlepic]\" cols=\"60\" rows=\"10\" id=\"add[zz_titlepic]\"><?=htmlspecialchars(stripSlashes(\$r[zz_titlepic]))?></textarea></td>\r\n    </tr>\r\n    <tr> \r\n      <td><input name=\"add[z_titlepic]\" type=\"text\" id=\"titlepic5\" value=\"<?=stripSlashes(\$r[z_titlepic])?>\">\r\n        (如填写这里，这就是字段的值)</td>\r\n    </tr>\r\n  </table></td>\r\n  </tr>','0','VARCHAR','200','1','$tid','$tbname','0','','0','0','0','<input type=\\\\\"file\\\\\" name=\\\\\"titlepicfile\\\\\">','0','','0','60','0','0','','','','','','','0','0');");

$empire->query("insert into `{$dbtbpre}enewsf` values(NULL,'newstime','发布时间','text','<input name=\\\\\"newstime\\\\\" type=\\\\\"text\\\\\" value=\\\\\"<?=\$r[newstime]?>\\\\\"><input type=button name=button value=\\\\\"设为当前时间\\\\\" onclick=\\\\\"document.add.newstime.value=\\\\''<?=\$todaytime?>\\\\''\\\\\">','发布时间','0','1','1','  <tr bgcolor=\"#FFFFFF\"> \r\n    <td height=\"22\" valign=\"top\"><strong>[!--enews.name--]正则：</strong><br>\r\n      (<input name=\"textfield\" type=\"text\" id=\"textfield\" value=\"[!--newstime--]\" size=\"20\">)</td>\r\n    <td><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"3\">\r\n        <tr> \r\n          <td><textarea name=\"add[zz_newstime]\" cols=\"60\" rows=\"10\" id=\"textarea\"><?=htmlspecialchars(stripSlashes(\$r[zz_newstime]))?></textarea></td>\r\n        </tr>\r\n        <tr> \r\n          <td><input name=\"add[z_newstime]\" type=\"text\" id=\"add[z_newstime]\" value=\"<?=stripSlashes(\$r[z_newstime])?>\">\r\n            (如填写这里，将为字段的值)</td>\r\n        </tr>\r\n      </table></td>\r\n  </tr>','0','INT','10','1','$tid','$tbname','0','','0','0','0','<input name=\\\\\"newstime\\\\\" type=\\\\\"text\\\\\" value=\\\\\"<?=\$r[newstime]?>\\\\\"><input type=button name=button value=\\\\\"设为当前时间\\\\\" onclick=\\\\\"document.add.newstime.value=\\\\''<?=\$todaytime?>\\\\''\\\\\">','0','','0','','0','0','','','','','','','0','0');");

//采集节点附加表
$tablename=$dbtbpre."ecms_infoclass_".$tbname;
$infoclass=$empire->query(SetCreateTable("CREATE TABLE `".$tablename."` (
  `classid` int not null default '0',
  `zz_title` text NOT NULL,
  `z_title` varchar(255) NOT NULL default '',
  `qz_title` varchar(255) NOT NULL default '',
  `save_title` varchar(10) NOT NULL default '',
  `zz_titlepic` text NOT NULL,
  `z_titlepic` varchar(255) NOT NULL default '',
  `qz_titlepic` varchar(255) NOT NULL default '',
  `save_titlepic` varchar(10) NOT NULL default '',
  `zz_newstime` text NOT NULL,
  `z_newstime` varchar(255) NOT NULL default '',
  `qz_newstime` varchar(255) NOT NULL default '',
  `save_newstime` varchar(10) NOT NULL default '',
   KEY `classid` (`classid`)
  ) TYPE=MyISAM;",$phome_db_dbchar));

//采集数据临时表
$tablename=$dbtbpre."ecms_infotmp_".$tbname;
$infotmp=$empire->query(SetCreateTable("CREATE TABLE `".$tablename."` (
  `id` bigint(20) NOT NULL auto_increment,
  `classid` int NOT NULL default '0',
  `oldurl` varchar(255) NOT NULL default '',
  `checked` tinyint(1) NOT NULL default '0',
  `tmptime` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(200) NOT NULL default '',
  `newstime` datetime NOT NULL default '0000-00-00 00:00:00',
  `username` varchar(30) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `truetime` int(11) NOT NULL default '0',
  `keyboard` varchar(255) NOT NULL default '',
  `titlepic` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `classid` (`classid`)
  ) TYPE=MyISAM;",$phome_db_dbchar));
?>