INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`) VALUES (35, 2, '求购模型', 'buy_', 'buy', '', '', 'a:7:{s:12:"list_PhpName";s:18:"list.php?&fid=$fid";s:12:"show_PhpName";s:29:"bencandy.php?&fid=$fid&id=$id";s:8:"MakeHtml";N;s:14:"list_HtmlName1";N;s:14:"show_HtmlName1";N;s:14:"list_HtmlName2";N;s:14:"show_HtmlName2";N;}', 99, '', '', 0);


INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_adpic', 'pic', 0, 'a:4:{s:6:"imgurl";s:32:"label/1_20101019101005_utjpw.gif";s:7:"imglink";s:1:"#";s:5:"width";s:3:"730";s:6:"height";s:2:"80";}', 'a:3:{s:5:"div_w";s:3:"730";s:5:"div_h";s:2:"80";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 1287461438, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c1', 'Info_buy_', 1, 'a:29:{s:13:"tplpart_1code";s:434:"<div class="list">\r\n                	<a href="$url"><img src="$picurl" onerror="this.src=\'$webdb[www_url]/images/default/nopic.jpg\'" width="100" height="75"/></a>\r\n                    <a href="$url" target="_blank">$title</a>\r\n                    <span style="line-height:20px;">单价:<font color="#FF0000">{$price}</font>元/{$my_units}<br>\r\n最小起订 <font color="#FF0000">{$order_min}</font> {$my_units}</span>\r\n                </div>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:7:"typefid";N;s:9:"noReadMid";i:1;s:6:"wninfo";s:4:"buy_";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"p";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"6";s:3:"sql";s:128:"(SELECT A.*,B.* FROM qb_buy_content A LEFT JOIN qb_buy_content_1 B ON A.id=B.id  WHERE A.ispic=1 ) ORDER BY A.list DESC LIMIT 6 ";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"20";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c2', 'code', 0, '求购资讯', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c3', 'code', 0, '<a href="/do/" target="_blank">更多&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c4', 'article', 1, 'a:32:{s:13:"tplpart_1code";s:70:" <div class="list l$i"><a href="$url" target="_blank">$title</a></div>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:7:"artcile";s:13:"RollStyleType";s:0:"";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:7:"newhour";s:2:"24";s:7:"hothits";s:3:"100";s:7:"amodule";s:1:"0";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:5:"stype";s:1:"4";s:2:"yz";s:1:"1";s:7:"hidefid";N;s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"9";s:3:"sql";s:102:" SELECT A.*,A.aid AS id FROM qb_article A  WHERE A.yz=1  AND A.mid=\'0\'   ORDER BY A.list DESC LIMIT 9 ";s:4:"sql2";N;s:7:"colspan";s:1:"1";s:11:"content_num";s:2:"80";s:12:"content_num2";s:3:"120";s:8:"titlenum";s:2:"36";s:9:"titlenum2";s:2:"40";s:10:"titleflood";s:1:"0";s:10:"c_rolltype";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 1287462677, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c5', 'code', 0, '品牌展示', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c6', 'code', 0, '<a href="/brand/" target="_blank">更多&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c7', 'Info_brand_', 1, 'a:28:{s:13:"tplpart_1code";s:231:"<div class="listpinpai"> <a href="$url" target="_blank"><img src="$picurl" onerror="this.src=\'$webdb[www_url]/images/default/nopic.jpg\'" width="90" height="40"/></a> \r\n              <a href="$url" target="_blank">$title</a> </div> ";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:6:"wninfo";s:6:"brand_";s:9:"noReadMid";i:1;s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"p";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"4";s:3:"sql";s:123:"SELECT * FROM qb_brand_content A LEFT JOIN qb_brand_content_1 B ON A.id=B.id  WHERE A.ispic=1  ORDER BY A.list DESC LIMIT 4";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"20";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 1287462849, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n1', 'Info_buy_', 1, 'a:29:{s:13:"tplpart_1code";s:373:"<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n                          <tr>\r\n                            <td class="t"> <a href="$url" target="_blank">$title</a></td>                            \r\n                    <td class="d"><font color="#FF0000">{$price}</font>元/{$my_units}</td>\r\n                          </tr>\r\n                        </table>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:7:"typefid";N;s:9:"noReadMid";i:1;s:6:"wninfo";s:4:"buy_";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"4";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"8";s:3:"sql";s:120:"(SELECT A.*,B.* FROM qb_buy_content A LEFT JOIN qb_buy_content_1 B ON A.id=B.id  WHERE 1 ) ORDER BY A.list DESC LIMIT 8 ";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"30";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n10', 'Info_buy_', 1, 'a:29:{s:13:"tplpart_1code";s:162:"<div class="listr">\r\n                   <a href="$url" target="_blank">$title</a>\r\n                    <span>{$price}元/{$my_units}</span>\r\n                </div>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:7:"typefid";N;s:9:"noReadMid";i:1;s:6:"wninfo";s:4:"buy_";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"4";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"6";s:3:"sql";s:120:"(SELECT A.*,B.* FROM qb_buy_content A LEFT JOIN qb_buy_content_1 B ON A.id=B.id  WHERE 1 ) ORDER BY A.list DESC LIMIT 6 ";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"22";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n2', 'article', 1, 'a:32:{s:13:"tplpart_1code";s:55:"<span><a href="$url" target="_blank">$title</a></span> ";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:7:"artcile";s:13:"RollStyleType";s:0:"";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:7:"newhour";s:2:"24";s:7:"hothits";s:3:"100";s:7:"amodule";s:3:"106";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:5:"stype";s:1:"4";s:2:"yz";s:1:"1";s:7:"hidefid";N;s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"6";s:3:"sql";s:104:" SELECT A.*,A.aid AS id FROM qb_article A  WHERE A.yz=1  AND A.mid=\'106\'   ORDER BY A.list DESC LIMIT 6 ";s:4:"sql2";N;s:7:"colspan";s:1:"1";s:11:"content_num";s:2:"80";s:12:"content_num2";s:3:"120";s:8:"titlenum";s:2:"18";s:9:"titlenum2";s:2:"40";s:10:"titleflood";s:1:"0";s:10:"c_rolltype";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n3', 'code', 0, '加入115钢铁网,开始做生意', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n4', 'code', 0, '热门求购', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n5', 'code', 0, '<a href="list.php?fid=1" target="_blank">更多&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n6', 'code', 0, '推荐求购信息', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n7', 'code', 0, '<a href="list.php?fid=1" target="_blank">更多&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n8', 'code', 0, '最新求购信息', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n9', 'code', 0, '<a href="list.php?fid=1" target="_blank">更多&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_roll', 'rollpic', 0, 'a:6:{s:8:"rolltype";s:1:"0";s:5:"width";s:3:"386";s:6:"height";s:3:"202";s:6:"picurl";a:2:{i:1;s:32:"label/1_20101018161044_ao7o8.jpg";i:2;s:32:"label/1_20101018161000_boy6x.jpg";}s:7:"piclink";a:2:{i:1;s:1:"#";i:2;s:1:"#";}s:6:"picalt";a:2:{i:1;s:0:"";i:2;s:0:"";}}', 'a:3:{s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_tt1ad', 'pic', 0, 'a:4:{s:6:"imgurl";s:32:"label/1_20101101161109_wqdn9.jpg";s:7:"imglink";s:1:"#";s:5:"width";s:3:"980";s:6:"height";s:2:"35";}', 'a:3:{s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'default');


# --------------------------------------------------------

#
# 表的结构 `qb_buy_collection`
#

DROP TABLE IF EXISTS `qb_buy_collection`;
CREATE TABLE `qb_buy_collection` (
  `cid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_buy_collection`
#


# --------------------------------------------------------

#
# 表的结构 `qb_buy_comments`
#

DROP TABLE IF EXISTS `qb_buy_comments`;
CREATE TABLE `qb_buy_comments` (
  `cid` mediumint(7) unsigned NOT NULL auto_increment,
  `cuid` int(7) NOT NULL default '0',
  `type` tinyint(2) NOT NULL default '0',
  `id` int(10) unsigned NOT NULL default '0',
  `fid` mediumint(7) unsigned NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `flowers` smallint(4) NOT NULL default '0',
  `egg` smallint(4) NOT NULL default '0',
  PRIMARY KEY  (`cid`),
  KEY `type` (`type`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_buy_comments`
#


# --------------------------------------------------------

#
# 表的结构 `qb_buy_config`
#

DROP TABLE IF EXISTS `qb_buy_config`;
CREATE TABLE `qb_buy_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) TYPE=MyISAM;

#
# 导出表中的数据 `qb_buy_config`
#

INSERT INTO `qb_buy_config` VALUES ('sort_layout', '1,75,5#2,71,4,65#54,3#', '');
INSERT INTO `qb_buy_config` VALUES ('order_send_msg', '1', '');
INSERT INTO `qb_buy_config` VALUES ('UpdatePostTime', '1', '');
INSERT INTO `qb_buy_config` VALUES ('showNoPassComment', '0', '');
INSERT INTO `qb_buy_config` VALUES ('Info_index_cache', '', '');
INSERT INTO `qb_buy_config` VALUES ('Info_list_cache', '', '');
INSERT INTO `qb_buy_config` VALUES ('Info_ShowNoYz', '1', '');
INSERT INTO `qb_buy_config` VALUES ('Info_TopMoney', '20', '');
INSERT INTO `qb_buy_config` VALUES ('Info_TopDay', '15', '');
INSERT INTO `qb_buy_config` VALUES ('Info_TopNum', '8', '');
INSERT INTO `qb_buy_config` VALUES ('PostInfoMoney', '10', '');
INSERT INTO `qb_buy_config` VALUES ('module_close', '0', '');
INSERT INTO `qb_buy_config` VALUES ('Info_allowGuesSearch', '1', '');
INSERT INTO `qb_buy_config` VALUES ('Info_metakeywords', '产品', '');
INSERT INTO `qb_buy_config` VALUES ('Info_webOpen', '1', '');
INSERT INTO `qb_buy_config` VALUES ('Info_webname', '求购产品', '');
INSERT INTO `qb_buy_config` VALUES ('order_send_mail', '1', '');
INSERT INTO `qb_buy_config` VALUES ('Info_ReportDB', '非法信息\r\n虚假信息\r\n过期信息', '');
INSERT INTO `qb_buy_config` VALUES ('module_pre', 'buy_', '');
INSERT INTO `qb_buy_config` VALUES ('module_id', '35', '');
INSERT INTO `qb_buy_config` VALUES ('Info_TopColor', '#FF0000', '');

# --------------------------------------------------------

#
# 表的结构 `qb_buy_content`
#

DROP TABLE IF EXISTS `qb_buy_content`;
CREATE TABLE `qb_buy_content` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `fname` varchar(50) NOT NULL default '',
  `hits` mediumint(7) NOT NULL default '0',
  `comments` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` varchar(10) NOT NULL default '',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `picurl` varchar(150) NOT NULL default '',
  `ispic` tinyint(1) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  `levels` tinyint(2) NOT NULL default '0',
  `levelstime` int(10) NOT NULL default '0',
  `keywords` varchar(100) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `lastfid` mediumint(7) NOT NULL default '0',
  `money` mediumint(7) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `lastview` int(10) NOT NULL default '0',
  `city_id` mediumint(7) NOT NULL default '0',
  `picnum` smallint(4) NOT NULL default '0',
  `price` double NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `ispic` (`ispic`),
  KEY `city_id` (`city_id`),
  KEY `list` (`list`,`fid`,`city_id`,`yz`),
  KEY `hits` (`hits`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# 导出表中的数据 `qb_buy_content`
#

INSERT INTO `qb_buy_content` VALUES (1, '求购铜包铜线', 1, 11, '螺纹钢', 1, 0, 1288611664, '1288611664', 1, 'admin', '', 'http://i00.c.aliimg.com/img/product/70/97/31/70973167.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611665, 0, 1, '43');
INSERT INTO `qb_buy_content` VALUES (2, '求购无铅含银锡丝', 1, 11, '螺纹钢', 1, 0, 1288611793, '1288611793', 1, 'admin', '', 'http://i01.c.aliimg.com/img/product/33/21/99/33219975.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611794, 0, 1, '43');
INSERT INTO `qb_buy_content` VALUES (3, '求购射线防护材料-铅板 厂家直销 质优价廉', 1, 21, '带钢', 1, 0, 1288611864, '1288611864', 1, 'admin', '', 'http://i02.c.aliimg.com/img/offer/19/39/46/69/19394669-2.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611865, 0, 1, '654');
INSERT INTO `qb_buy_content` VALUES (4, '求购20g钢管 20g高压锅炉管 抗氧化耐腐蚀', 1, 33, '冷轧板', 1, 0, 1288611943, '1288611943', 1, 'admin', '', 'http://i04.c.aliimg.com/img/ibank/2010/562/548/203845265_1777718237.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611946, 0, 1, '54');
INSERT INTO `qb_buy_content` VALUES (5, '求购不锈钢圆钢 316L不锈钢圆钢', 1, 46, '船板', 1, 0, 1288612043, '1288612043', 1, 'admin', '', 'http://i03.c.aliimg.com/img/offer/33/24/15/40/7/332415407-2.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288612044, 0, 1, '43');
INSERT INTO `qb_buy_content` VALUES (6, '求购佛山不锈钢管厂', 1, 59, '无缝管', 2, 0, 1288612132, '1288612132', 1, 'admin', '', 'http://i00.c.aliimg.com/img/offer/56/28/57/26/7/562857267.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288680842, 0, 1, '434');
INSERT INTO `qb_buy_content` VALUES (7, '求购黄铜棒', 1, 87, '焊线', 3, 0, 1288612333, '1288612333', 1, 'admin', '', 'http://i04.c.aliimg.com/img/offer/50/94/67/51/2/509467512.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1293511098, 0, 1, '54');
INSERT INTO `qb_buy_content` VALUES (8, '求购纯铁,钕铁硼专用纯铁,纯铁方', 1, 87, '焊线', 1, 0, 1288612560, '1288612560', 1, 'admin', '', 'http://i01.c.aliimg.com/img/offer2/2010/075/210/91075210_0eb64d7b2064ea5f5cfc34ecfd9156af.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288680835, 0, 1, '434');

# --------------------------------------------------------

#
# 表的结构 `qb_buy_content_1`
#

DROP TABLE IF EXISTS `qb_buy_content_1`;
CREATE TABLE `qb_buy_content_1` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `my_units` varchar(10) NOT NULL default '',
  `order_num` int(7) NOT NULL default '0',
  `end_day` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`rid`),
  KEY `fid` (`fid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# 导出表中的数据 `qb_buy_content_1`
#

INSERT INTO `qb_buy_content_1` VALUES (1, 1, 11, 1, '<p><span style="color:#0000ff;"><span style="font-size:15pt;"><img onload=\'if(this.width>600)makesmallpic(this,600,800);\' src="http://i00.c.aliimg.com/img/product/70/97/31/70973167.jpg" width="768" height="576" /><br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 铜包铜线优点 ●具有广阔的应用范围. 产品广泛用于铁路、公路、电力、通讯电子等行业。 ●好的延展性和可加工性: 可以进一步地深(细)加工成铜包铝各种规格的裸线及镀锡线.漆包线.镀银线. ●明显的经济效益: ，使用铜包铜线替代纯铜线，能够大幅度节约原材料成本。以此类推，铜包铝线、铜包钢线以及各产品的镀锡线也是替代纯铜导线的理想产品。 ●显著的社会效益和环境效益 节省大量稀缺的铜资源,减轻电费重量,便于运输和网络施工,减轻工人劳动强度。</span></span></p>\r\n<p><span style="font-size:15pt;"><span style="color:#0000ff;">　本公司拥有先进工艺技术和优良设备，拥有多条生产线以满足生产φ0.10-φ1.60规格铜包铜线各类产品的需求。 我们衷心希望与国内外线缆厂家建立长期合作、互惠互利、共同发展的战略伙伴关系；并热忱欢迎您对本公司产品提出宝贵意见，恒久模具必将为降低您的企业生产成本而竭诚努力!</span></span></p>\r\n', '箱', 43, '2010-11-30');
INSERT INTO `qb_buy_content_1` VALUES (2, 2, 11, 1, '<p>无铅锡线（丝）类别：</p>\r\n<p>★ 锡铜无铅锡线/丝（Sn99.3CU0.7）</p>\r\n<p>★ 0.3银无铅锡线/丝（Sn99.0Ag0.3Cu0.7）</p>\r\n<p>★ 锡银铜无铅锡线/丝（Sn96.5Ag3.0Cu0.5）</p>\r\n<p>★ 实芯型无铅锡线/丝（不含助焊剂）</p>\r\n<p>★ 小松香无铅锡线/丝（含1.4%松香）</p>\r\n<p>&nbsp;</p>\r\n<p>无铅焊锡丝/丝的种类：</p>\r\n<p>★ 无铅松香芯焊锡线/丝&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ★无铅免洗焊锡线/丝</p>\r\n<p>★ 无铅焊锡线/丝&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ★无铅水溶性焊锡线/丝</p>\r\n<p>★ 无铅含银焊锡线/丝&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ★无铅不锈钢焊锡线/丝</p>\r\n<p>★ 无铅镀镍焊锡线/丝&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ★无铅焊铝焊锡线/丝</p>\r\n<p>★ 无卤素焊锡线/丝</p>\r\n<p>&nbsp;</p>\r\n<p>无铅锡线（丝）特点：</p>\r\n<p>★ 良好的湿润性导电率热导率易上锡</p>\r\n<p>★ 按客户所需订制松香含量焊接不飞溅</p>\r\n<p>★ 助焊剂分布均匀锡芯里无断助焊剂现象</p>\r\n<p>★ 绕线均匀不打结上锡速度快残渣少</p>\r\n<p>★ 锡丝线径大小由：0.5--3.0mm均可订做</p>\r\n<p>&nbsp;</p>\r\n', '套', 3, '2010-11-27');
INSERT INTO `qb_buy_content_1` VALUES (3, 3, 21, 1, '<div><p>规格： 厚度0.5-15mm 宽度1000mm 长度2000-8000mm 同时可按用户提供的规格，生产供应特种规格的铅板。 也可以按用户的要求制做铅制辐射防护用品及设备。</p>\r\n</div>\r\n', '箱', 4, '2010-11-26');
INSERT INTO `qb_buy_content_1` VALUES (4, 4, 33, 1, '<p>品名：20g高压锅炉管</p>\r\n<p>&nbsp;</p>\r\n<p>特性：持久强度高、抗氧化、耐腐蚀，并有良好的组织稳定性。</p>\r\n<p>&nbsp;</p>\r\n<p>用途：主要用来制造高压和超高压锅炉的过热器管、再热器管、导气管、主蒸汽管等。</p>\r\n<p>&nbsp;</p>\r\n<p>经营规格：外径10-530mm，壁厚2-70mm。</p>\r\n<p>&nbsp;</p>\r\n<p>长度：4-11米</p>\r\n<p>&nbsp;</p>\r\n<p>产地：天津、包头</p>\r\n<p>&nbsp;</p>\r\n<p>执行标准：GB/T5310-1995</p>\r\n<p>&nbsp;</p>\r\n<p>现货：千吨以上</p>\r\n<p>&nbsp;</p>\r\n<p>包装方式：打捆</p>\r\n<p>&nbsp;</p>\r\n<p>供应单位：聊城市正利钢管有限公司</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 成立于2005年，原名（聊城市中创钢管有限公司）</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 本公司常年经营天津、包头、衡阳等大钢厂生产的20g高压锅炉管，现货多，规格全，价格优惠。欢迎广大用户来人来电洽谈订购、指导工作。 </p>\r\n<p>&nbsp;</p>\r\n', '件', 45, '2010-11-19');
INSERT INTO `qb_buy_content_1` VALUES (5, 5, 46, 1, '<div><p>昊鸿钢铁贸易是经营不锈钢的专业公司。目前公司与张家港、山西太原、台湾烨耿、宝新、<br />\r\n南韩等各大钢厂建立了良好的供求关系，有着多年的业务往来。公司经营的系列不锈钢产品广泛应用于化工<br />\r\n石油、天然气、环保、制药、食品机械、供水供暖等领域。同时，公司下设加工厂，可按用户需求定做各种<br />\r\n型材，并生产加工不锈钢及其制品。<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;&nbsp;&nbsp; 多年来，公司本着“质量第一、信誉第一、服务至上、共谋发展”的经营宗旨，向国内外的广大客户提供<br />\r\n最优质的产品及热情周到的服务。本公司郑重承诺：各种材质、各种规格的产品均以市场最低价供应，欢迎垂<br />\r\n询光临！&nbsp;</p>\r\n</div>\r\n', '箱', 4, '2010-11-26');
INSERT INTO `qb_buy_content_1` VALUES (6, 6, 59, 1, '<div><p>佛山市鑫中航金属材料有限公司<br />\r\n我公司位于素有不锈钢名镇之称的佛山市澜石镇，水陆交通极为便利,是一家采用现代化经营管理模式运作的专业不锈钢公司，<br />\r\n公司经营各种规格型号不锈钢管，产品涉及200、300系列。<br />\r\n我公司凭借敏锐的市场触觉，良好的业界口碑，雄厚的资金实力以及良好的客情关系，在不锈钢行业树立了广泛的知名度,在业界享有盛誉。</p>\r\n<p>公司集不锈钢材料销售、加工与配送为一体,长期专营各种不锈钢管材,,规格齐全,并有不定尺开界,<br />\r\n欢迎各界人士与我们携手合作，公司将以优惠的价格、优惠的材料、优良的服务来满足客户的要求，<br />\r\n竭诚欢迎广大新老客户查询惠顾。愿与各界朋友精诚合作，共同发展。&nbsp;</p>\r\n<p>专业现货供应各种不锈钢装饰用管,机械结构和工业管材<br />\r\n规格齐全，价格优惠欢迎来电咨询！谢谢！！</p>\r\n</div>\r\n', '吨', 43, '2010-11-27');
INSERT INTO `qb_buy_content_1` VALUES (7, 7, 87, 1, '天津市飞龙制管有限公司-西邻津汕、京沪高速、东邻天津新港码头、滨海国际机场及丹拉高速交通快捷便利。是全国诚信企业和天津市知名企业，始建于1995年，现有职工1800人，其中专业技术人员236人，厂区占地面积1200亩，现有22条ERW、高频焊管生产线，6条热镀锌生产线，12条扩管生产线，4条方管生产线，1条石油、天然气开采用套管生产线。产品主要用于石油、天然气、煤气、煤浆、机械、托辊、建筑、电力、化工、环保、锅炉、农井建设、温室大棚框架、钢构框架、消防管线、等行业。产品执行API\\SPEC5L、API\\SPEC5CT、ASTA53、EN10217、及GB/T9711.1、GP/T9711.2、GB/T3091-2008、GB/T13793-9292、GB/14291-2006、等国内外最新标准。我公司产品通过ISO9001、ISO14001及API、PED-CE、德国AD2000-WO、国标、英标、日标、欧盟、美标等质量管理体系要求。可年产直径20mm-426mm焊管150万吨，年出口各类钢管10万吨。<br />\r\n方管、矩管；15*15--300*500<br />\r\n镀锌管【牛头牌】、高频焊管、隐形焊管：直径4分、6分、1寸、1.2寸、1.5寸、2寸、76、89、102、108、114、121、127、133、140、152、159、165、168、180、194、203、219、245、250、273、299、306、325、351、355、377、406、426、530.长度4mm-16mm，壁厚0.8mm-14mm<br />\r\n热扩钢管:245、250、273、299、325、351、377、406、426.<br />\r\n石油天然气开采用套管：57、73、114.3、127、168.3、139.7、177.8、219.1、244.5、273.1.&nbsp;&nbsp;&nbsp; 规格、壁厚、口径可根据客户要求定做。<br />\r\n', '吨', 43, '2010-11-27');
INSERT INTO `qb_buy_content_1` VALUES (8, 8, 87, 1, '<div><p><span style="font-size:10pt;font-family:宋体;">太钢是我国纯铁科研和生产的主要基地，也是我国最早研制和生产纯铁的企业。从<span>1955</span>年开始到现在，经过五十多年的研究和生产，已经形成一整套独特的生产工艺和多用途、多品种、多规格的纯铁系列产品。太钢生产纯铁经验丰富、设备精良、技术力量雄厚、检测手段完善<span>,</span>生产工艺路线先进。</span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span style="font-size:small;"><font size="2"><span style="color:red;font-family:宋体;">主营产品</span><span><span style="font-family:Times New Roman;">:</span></span></font></span></p>\r\n<p><span style="font-size:small;"><font size="2"><span style="font-family:宋体;">纯铁</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:宋体;">原料纯铁</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:宋体;">电工纯铁</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:宋体;">纯铁板</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:宋体;">纯铁棒</span><span style="font-family:Times New Roman;"><span>.</span></span><span style="color:black;font-family:宋体;">高纯度原料纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">;YTO/YTO1;</span></span><span style="color:black;font-family:宋体;">高性能电磁纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">;DT4 /DT4A;DT4E/DT4C;DT8/DT9;</span></span><span style="color:black;font-family:宋体;">方坯</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">棒材</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">盘圆</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">板材</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">精密机械加工</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">精密铸造</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">电磁纯铁冷轧薄板</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">电磁纯铁热轧中板</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">纯铁冷拉直条</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">纯铁棒材</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">纯铁锻材</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:宋体;">铸造纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:宋体;">钕铁硼专用纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:宋体;">工业纯铁材</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:宋体;">军工纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:宋体;">深冲板</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:宋体;">铁芯用电磁纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:宋体;">纯铁</span><span style="color:black;"><span style="font-family:Times New Roman;">70</span></span><span style="color:black;font-family:宋体;">方坯</span><span style="color:black;"><span style="font-family:Times New Roman;">.</span></span></font></span></p>\r\n</div>\r\n', '件', 34, '2010-11-20');

# --------------------------------------------------------

#
# 表的结构 `qb_buy_content_2`
#

DROP TABLE IF EXISTS `qb_buy_content_2`;
CREATE TABLE `qb_buy_content_2` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` int(10) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `ask_username` varchar(20) NOT NULL default '',
  `ask_phone` varchar(20) NOT NULL default '',
  `ask_mobphone` varchar(15) NOT NULL default '',
  `ask_email` varchar(50) NOT NULL default '',
  `ask_qq` varchar(11) NOT NULL default '',
  `ask_title` varchar(100) NOT NULL default '',
  `order_num` int(6) NOT NULL default '0',
  `sell_price` varchar(20) NOT NULL default '',
  `hope_reply` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`rid`),
  KEY `fid` (`fid`),
  KEY `id` (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_buy_content_2`
#


# --------------------------------------------------------

#
# 表的结构 `qb_buy_db`
#

DROP TABLE IF EXISTS `qb_buy_db`;
CREATE TABLE `qb_buy_db` (
  `id` int(10) NOT NULL auto_increment,
  `fid` mediumint(7) NOT NULL default '0',
  `city_id` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`),
  KEY `city_id` (`city_id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# 导出表中的数据 `qb_buy_db`
#

INSERT INTO `qb_buy_db` VALUES (1, 11, 0, 1);
INSERT INTO `qb_buy_db` VALUES (2, 11, 0, 1);
INSERT INTO `qb_buy_db` VALUES (3, 21, 0, 1);
INSERT INTO `qb_buy_db` VALUES (4, 33, 0, 1);
INSERT INTO `qb_buy_db` VALUES (5, 46, 0, 1);
INSERT INTO `qb_buy_db` VALUES (6, 59, 0, 1);
INSERT INTO `qb_buy_db` VALUES (7, 87, 0, 1);
INSERT INTO `qb_buy_db` VALUES (8, 87, 0, 1);

# --------------------------------------------------------

#
# 表的结构 `qb_buy_field`
#

DROP TABLE IF EXISTS `qb_buy_field`;
CREATE TABLE `qb_buy_field` (
  `id` mediumint(7) NOT NULL auto_increment,
  `mid` mediumint(5) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `field_name` varchar(30) NOT NULL default '',
  `field_type` varchar(15) NOT NULL default '',
  `field_leng` smallint(3) NOT NULL default '0',
  `orderlist` int(10) NOT NULL default '0',
  `form_type` varchar(15) NOT NULL default '',
  `field_inputwidth` smallint(3) default NULL,
  `field_inputheight` smallint(3) NOT NULL default '0',
  `form_set` text NOT NULL,
  `form_value` text NOT NULL,
  `form_units` text NOT NULL,
  `form_title` text NOT NULL,
  `mustfill` tinyint(1) NOT NULL default '0',
  `listshow` tinyint(1) NOT NULL default '0',
  `listfilter` tinyint(1) default NULL,
  `search` tinyint(1) NOT NULL default '0',
  `allowview` varchar(255) NOT NULL default '',
  `allowpost` varchar(255) NOT NULL default '',
  `js_check` text NOT NULL,
  `js_checkmsg` varchar(255) NOT NULL default '',
  `classid` mediumint(7) NOT NULL default '0',
  `form_js` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=162 ;

#
# 导出表中的数据 `qb_buy_field`
#

INSERT INTO `qb_buy_field` VALUES (86, 1, '其它要求', 'content', 'mediumtext', 0, -1, 'ieeditsimp', 600, 250, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (159, 2, '供货价格', 'sell_price', 'varchar', 20, 18, 'text', 50, 0, '', '', '单价', '', 1, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (158, 2, '供货总量', 'order_num', 'int', 6, 19, 'text', 50, 0, '', '', '', '', 1, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (142, 2, '补充说明', 'content', 'mediumtext', 0, 15, 'textarea', 500, 70, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '<br><select name=\'autoSelect\' onchange="changeaddContent(this);">\r\n<option value=\'\'>(懒得打字？“快速填写”帮您忙！) </option>\r\n<option value=\'请您发一份比较详细的产品规格说明，谢谢！\'>请您发一份比较详细的产品规格说明，谢谢！</option> \r\n<option value=\'请问您对此产品是长期有需求吗？\'>请问您对此产品是长期有需求吗？</option> \r\n<option value=\'请问您对此产品有多大的需求量？\'>请问您对此产品有多大的需求量？</option> \r\n</select>\r\n<SCRIPT language="javascript">\r\n            function changeaddContent(autoSelect){\r\n			 	if (autoSelect.selectedIndex !=0){			 		\r\n			 		document.getElementById("atc_content").value = autoSelect[autoSelect.selectedIndex].value;\r\n					autoSelect.selectedIndex=0;\r\n			 	}\r\n				\r\n			 }\r\n	     </SCRIPT>');
INSERT INTO `qb_buy_field` VALUES (154, 1, '求购数量', 'order_num', 'int', 7, 8, 'text', 30, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (153, 1, '计量单位', 'my_units', 'varchar', 10, 9, 'text', 50, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (145, 2, '联系电话', 'ask_phone', 'varchar', 20, 8, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (144, 2, '联系人姓名', 'ask_username', 'varchar', 20, 9, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (146, 2, '联系手机', 'ask_mobphone', 'varchar', 15, 7, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (147, 2, '联系邮箱', 'ask_email', 'varchar', 50, 6, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (148, 2, '联系QQ', 'ask_qq', 'varchar', 11, 5, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (156, 1, '截止日期', 'end_day', 'varchar', 30, 5, 'time', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (157, 2, '报价标题', 'ask_title', 'varchar', 100, 20, 'text', 300, 0, '', '', '', '', 1, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (161, 2, '我希望', 'hope_reply', 'varchar', 25, 14, 'time', 0, 0, '', '', '之前回复', '', 0, 0, 0, 0, '', '', '', '', 0, '');

# --------------------------------------------------------

#
# 表的结构 `qb_buy_join`
#

DROP TABLE IF EXISTS `qb_buy_join`;
CREATE TABLE `qb_buy_join` (
  `id` mediumint(7) NOT NULL auto_increment,
  `mid` smallint(4) NOT NULL default '0',
  `cid` mediumint(7) NOT NULL default '0',
  `cuid` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`),
  KEY `fid` (`fid`,`cid`),
  KEY `yz` (`yz`,`fid`,`mid`,`cid`),
  KEY `cuid` (`cuid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_buy_join`
#


# --------------------------------------------------------

#
# 表的结构 `qb_buy_module`
#

DROP TABLE IF EXISTS `qb_buy_module`;
CREATE TABLE `qb_buy_module` (
  `id` smallint(4) NOT NULL auto_increment,
  `sort_id` mediumint(5) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `list` smallint(4) NOT NULL default '0',
  `style` varchar(50) NOT NULL default '',
  `config` text NOT NULL,
  `config2` text NOT NULL,
  `comment_type` tinyint(1) NOT NULL default '0',
  `ifdp` tinyint(1) NOT NULL default '0',
  `template` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

#
# 导出表中的数据 `qb_buy_module`
#

INSERT INTO `qb_buy_module` VALUES (2, 0, '报价单模型', 1, '', '', '', 0, 0, 'a:4:{s:4:"list";s:12:"joinlist.htm";s:4:"show";s:12:"joinshow.htm";s:4:"post";s:8:"join.htm";s:6:"search";s:0:"";}');
INSERT INTO `qb_buy_module` VALUES (1, 0, '求购模型', 4, '', '', '', 1, 0, '');

# --------------------------------------------------------

#
# 表的结构 `qb_buy_pic`
#

DROP TABLE IF EXISTS `qb_buy_pic`;
CREATE TABLE `qb_buy_pic` (
  `pid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(10) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `mid` smallint(4) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `imgurl` varchar(150) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`pid`),
  KEY `id` (`id`),
  KEY `fid` (`fid`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# 导出表中的数据 `qb_buy_pic`
#

INSERT INTO `qb_buy_pic` VALUES (1, 1, 11, 0, 1, 0, 'http://i00.c.aliimg.com/img/product/70/97/31/70973167.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (2, 2, 11, 0, 1, 0, 'http://i01.c.aliimg.com/img/product/33/21/99/33219975.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (3, 3, 21, 0, 1, 0, 'http://i02.c.aliimg.com/img/offer/19/39/46/69/19394669-2.310x310.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (4, 4, 33, 0, 1, 0, 'http://i04.c.aliimg.com/img/ibank/2010/562/548/203845265_1777718237.310x310.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (5, 5, 46, 0, 1, 0, 'http://i03.c.aliimg.com/img/offer/33/24/15/40/7/332415407-2.310x310.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (6, 6, 59, 0, 1, 0, 'http://i00.c.aliimg.com/img/offer/56/28/57/26/7/562857267.310x310.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (7, 7, 87, 0, 1, 0, 'http://i04.c.aliimg.com/img/offer/50/94/67/51/2/509467512.310x310.jpg', '');
INSERT INTO `qb_buy_pic` VALUES (8, 8, 87, 0, 1, 0, 'http://i01.c.aliimg.com/img/offer2/2010/075/210/91075210_0eb64d7b2064ea5f5cfc34ecfd9156af.310x310.jpg', '');

# --------------------------------------------------------

#
# 表的结构 `qb_buy_report`
#

DROP TABLE IF EXISTS `qb_buy_report`;
CREATE TABLE `qb_buy_report` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `fid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `onlineip` varchar(15) NOT NULL default '',
  `type` tinyint(2) NOT NULL default '0',
  `content` text NOT NULL,
  `iftrue` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`rid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_buy_report`
#


# --------------------------------------------------------

#
# 表的结构 `qb_buy_sort`
#

DROP TABLE IF EXISTS `qb_buy_sort`;
CREATE TABLE `qb_buy_sort` (
  `fid` mediumint(7) unsigned NOT NULL auto_increment,
  `fup` mediumint(7) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `class` smallint(4) NOT NULL default '0',
  `sons` smallint(4) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  `admin` varchar(100) NOT NULL default '',
  `list` int(10) NOT NULL default '0',
  `listorder` tinyint(2) NOT NULL default '0',
  `passwd` varchar(32) NOT NULL default '',
  `logo` varchar(150) NOT NULL default '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL default '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL default '',
  `maxperpage` tinyint(3) NOT NULL default '0',
  `metatitle` varchar(250) NOT NULL default '',
  `metakeywords` varchar(255) NOT NULL default '',
  `metadescription` varchar(255) NOT NULL default '',
  `allowcomment` tinyint(1) NOT NULL default '0',
  `allowpost` varchar(150) NOT NULL default '',
  `allowviewtitle` varchar(150) NOT NULL default '',
  `allowviewcontent` varchar(150) NOT NULL default '',
  `allowdownload` varchar(150) NOT NULL default '',
  `forbidshow` tinyint(1) NOT NULL default '0',
  `config` mediumtext NOT NULL,
  `index_show` tinyint(1) NOT NULL default '0',
  `contents` mediumint(4) NOT NULL default '0',
  `tableid` varchar(30) NOT NULL default '',
  `dir_name` varchar(50) NOT NULL default '',
  `ifcolor` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM AUTO_INCREMENT=156 ;

#
# 导出表中的数据 `qb_buy_sort`
#

INSERT INTO `qb_buy_sort` VALUES (1, 0, '钢材', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (2, 0, '钢板卷', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (3, 0, '板材', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (4, 0, '中厚板', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (5, 0, '钢管', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (6, 0, '型钢', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (7, 0, '特钢', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (8, 0, '不锈钢', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (9, 0, '炉料', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (10, 0, '生铁', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (11, 1, '螺纹钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (12, 1, '普线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (13, 1, '高线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (14, 1, '圆钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (15, 1, '线材', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (16, 1, '二级螺纹钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (17, 1, '三级螺纹钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (18, 1, '盘螺', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (19, 1, '优线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (20, 1, '带肋钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (21, 2, '带钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (22, 2, '热板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (23, 2, '冷板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (24, 2, '不锈板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (25, 2, '镀锌板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (26, 2, '彩涂板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (27, 2, '花纹板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (28, 2, '低合金板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (29, 2, '镀锡板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (30, 2, '镀铝锌板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (31, 2, '酸洗板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (32, 2, '轧硬卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (33, 3, '冷轧板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (34, 3, '热轧板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (35, 3, '容器板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (36, 3, '合金板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (37, 3, '高强板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (38, 3, '耐腐蚀板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (39, 3, '马口铁基板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (40, 3, '镀锡板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (41, 3, '镀铝板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (42, 3, '硅钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (43, 3, '镀锌板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (44, 3, '彩涂板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (45, 3, '管线钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (46, 4, '船板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (47, 4, '大梁板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (48, 4, '模具板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (49, 4, '锅炉板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (50, 4, '翼缘板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (51, 4, '四切平板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (52, 4, '花纹板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (53, 4, '低合金板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (54, 4, '锰板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (55, 4, '碳结板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (56, 4, '合结板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (57, 4, '开平板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (58, 4, '耐磨板', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (59, 5, '无缝管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (60, 5, '镀锌管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (61, 5, '方管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (62, 5, '螺旋管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (63, 5, '焊管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (64, 5, '直缝管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (65, 5, '圆管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (66, 5, '锅炉管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (67, 5, '球墨管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (68, 5, '矩型管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (69, 5, '化肥用管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (70, 5, '结构管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (71, 5, '铸铁管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (72, 5, '脚手架', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (73, 6, 'H型钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (74, 6, '槽钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (75, 6, '工字钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (76, 6, '角钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (77, 6, '扁钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (78, 6, '轻轨', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (79, 6, '重轨', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (80, 6, '方钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (81, 6, '不等边角钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (82, 6, '型钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (83, 6, '六角钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (84, 6, '拉光圆', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (85, 6, '碳圆', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (86, 6, '等边角钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (87, 7, '焊线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (88, 7, '圆钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (89, 7, '轴承钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (90, 7, '碳结钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (91, 7, '弹簧钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (92, 7, '高工钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (93, 7, '模具钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (94, 7, '钢纹线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (95, 7, '齿轮钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (96, 7, '合结钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (97, 7, '耐腐蚀钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (98, 7, '耐磨钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (99, 7, '碳工钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (100, 7, '碳素钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (101, 7, '耐热钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (102, 7, '冷镦钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (103, 7, '工模钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (104, 7, '结构钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (105, 8, '不锈板卷', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (106, 8, '不锈钢管', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (107, 8, '不锈钢线材', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (108, 8, '不锈钢带', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (109, 8, '不锈钢圆钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (110, 8, '不锈钢角钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (111, 8, '不锈钢槽钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (112, 8, '不锈钢扁钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (113, 8, '不锈钢坯', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (114, 8, '优焊线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (115, 8, '拉丝', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (116, 8, '硬线', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (117, 8, '耐热钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (118, 8, '钢丝', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (119, 9, '矿石', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (120, 9, '煤焦', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (121, 9, '精铁粉', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (122, 9, '废钢', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (123, 9, '焦炭', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (124, 9, '煤炭', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (125, 9, '耐火材料', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (126, 9, '碳素材料', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (127, 9, '球墨铸铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (128, 9, '钢锭', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (129, 9, '硅锰', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (130, 9, '方坯', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (131, 9, '管坯', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (132, 9, '钢坯', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (133, 9, '板坯', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (134, 9, '球磨生铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (135, 9, '辅料副产品', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (136, 10, '硅铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (137, 10, '铬铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (138, 10, '钼铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (139, 10, '钒铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (140, 10, '钨铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (141, 10, '铌铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (142, 10, '钛铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (143, 10, '锰铁', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (144, 10, '镍矿', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (145, 10, '有色', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (146, 10, '铜', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (147, 10, '铝', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (148, 10, '锌', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (149, 10, '金', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (150, 10, '银', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (151, 10, '镍', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (152, 10, '铅', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (153, 10, '锡', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (154, 10, '稀土', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (155, 10, '贵金属', 1, 3, 0, 0, '', 0, 0, '', '', '', '', 'a:4:{s:4:"head";s:0:"";s:4:"foot";s:0:"";s:4:"list";s:0:"";s:8:"bencandy";s:0:"";}', '', 0, '', '', '', 0, '', '', '', '', 0, 'a:2:{s:7:"is_html";N;s:11:"field_value";N;}', 0, 0, '', 'guijinshu', 0);
