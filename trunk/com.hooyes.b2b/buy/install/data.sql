INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`) VALUES (35, 2, '��ģ��', 'buy_', 'buy', '', '', 'a:7:{s:12:"list_PhpName";s:18:"list.php?&fid=$fid";s:12:"show_PhpName";s:29:"bencandy.php?&fid=$fid&id=$id";s:8:"MakeHtml";N;s:14:"list_HtmlName1";N;s:14:"show_HtmlName1";N;s:14:"list_HtmlName2";N;s:14:"show_HtmlName2";N;}', 99, '', '', 0);


INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_adpic', 'pic', 0, 'a:4:{s:6:"imgurl";s:32:"label/1_20101019101005_utjpw.gif";s:7:"imglink";s:1:"#";s:5:"width";s:3:"730";s:6:"height";s:2:"80";}', 'a:3:{s:5:"div_w";s:3:"730";s:5:"div_h";s:2:"80";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 1287461438, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c1', 'Info_buy_', 1, 'a:29:{s:13:"tplpart_1code";s:434:"<div class="list">\r\n                	<a href="$url"><img src="$picurl" onerror="this.src=\'$webdb[www_url]/images/default/nopic.jpg\'" width="100" height="75"/></a>\r\n                    <a href="$url" target="_blank">$title</a>\r\n                    <span style="line-height:20px;">����:<font color="#FF0000">{$price}</font>Ԫ/{$my_units}<br>\r\n��С�� <font color="#FF0000">{$order_min}</font> {$my_units}</span>\r\n                </div>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:7:"typefid";N;s:9:"noReadMid";i:1;s:6:"wninfo";s:4:"buy_";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"p";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"6";s:3:"sql";s:128:"(SELECT A.*,B.* FROM qb_buy_content A LEFT JOIN qb_buy_content_1 B ON A.id=B.id  WHERE A.ispic=1 ) ORDER BY A.list DESC LIMIT 6 ";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"20";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c2', 'code', 0, '����Ѷ', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c3', 'code', 0, '<a href="/do/" target="_blank">����&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c4', 'article', 1, 'a:32:{s:13:"tplpart_1code";s:70:" <div class="list l$i"><a href="$url" target="_blank">$title</a></div>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:7:"artcile";s:13:"RollStyleType";s:0:"";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:7:"newhour";s:2:"24";s:7:"hothits";s:3:"100";s:7:"amodule";s:1:"0";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:5:"stype";s:1:"4";s:2:"yz";s:1:"1";s:7:"hidefid";N;s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"9";s:3:"sql";s:102:" SELECT A.*,A.aid AS id FROM qb_article A  WHERE A.yz=1  AND A.mid=\'0\'   ORDER BY A.list DESC LIMIT 9 ";s:4:"sql2";N;s:7:"colspan";s:1:"1";s:11:"content_num";s:2:"80";s:12:"content_num2";s:3:"120";s:8:"titlenum";s:2:"36";s:9:"titlenum2";s:2:"40";s:10:"titleflood";s:1:"0";s:10:"c_rolltype";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 1287462677, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c5', 'code', 0, 'Ʒ��չʾ', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c6', 'code', 0, '<a href="/brand/" target="_blank">����&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_c7', 'Info_brand_', 1, 'a:28:{s:13:"tplpart_1code";s:231:"<div class="listpinpai"> <a href="$url" target="_blank"><img src="$picurl" onerror="this.src=\'$webdb[www_url]/images/default/nopic.jpg\'" width="90" height="40"/></a> \r\n              <a href="$url" target="_blank">$title</a> </div> ";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:6:"wninfo";s:6:"brand_";s:9:"noReadMid";i:1;s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"p";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"4";s:3:"sql";s:123:"SELECT * FROM qb_brand_content A LEFT JOIN qb_brand_content_1 B ON A.id=B.id  WHERE A.ispic=1  ORDER BY A.list DESC LIMIT 4";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"20";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 1287462849, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n1', 'Info_buy_', 1, 'a:29:{s:13:"tplpart_1code";s:373:"<table width="100%" border="0" cellspacing="0" cellpadding="0">\r\n                          <tr>\r\n                            <td class="t"> <a href="$url" target="_blank">$title</a></td>                            \r\n                    <td class="d"><font color="#FF0000">{$price}</font>Ԫ/{$my_units}</td>\r\n                          </tr>\r\n                        </table>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:7:"typefid";N;s:9:"noReadMid";i:1;s:6:"wninfo";s:4:"buy_";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"4";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"8";s:3:"sql";s:120:"(SELECT A.*,B.* FROM qb_buy_content A LEFT JOIN qb_buy_content_1 B ON A.id=B.id  WHERE 1 ) ORDER BY A.list DESC LIMIT 8 ";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"30";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n10', 'Info_buy_', 1, 'a:29:{s:13:"tplpart_1code";s:162:"<div class="listr">\r\n                   <a href="$url" target="_blank">$title</a>\r\n                    <span>{$price}Ԫ/{$my_units}</span>\r\n                </div>";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:2:"wn";s:7:"typefid";N;s:9:"noReadMid";i:1;s:6:"wninfo";s:4:"buy_";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:11:"content_num";s:2:"80";s:7:"newhour";s:2:"24";s:7:"hothits";s:2:"30";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:8:"moduleid";N;s:5:"stype";s:1:"4";s:2:"yz";s:3:"all";s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"6";s:3:"sql";s:120:"(SELECT A.*,B.* FROM qb_buy_content A LEFT JOIN qb_buy_content_1 B ON A.id=B.id  WHERE 1 ) ORDER BY A.list DESC LIMIT 6 ";s:7:"colspan";s:1:"1";s:8:"titlenum";s:2:"22";s:10:"titleflood";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n2', 'article', 1, 'a:32:{s:13:"tplpart_1code";s:55:"<span><a href="$url" target="_blank">$title</a></span> ";s:13:"tplpart_2code";s:0:"";s:3:"SYS";s:7:"artcile";s:13:"RollStyleType";s:0:"";s:8:"rolltype";s:10:"scrollLeft";s:8:"rolltime";s:1:"3";s:11:"roll_height";s:2:"50";s:5:"width";s:3:"250";s:6:"height";s:3:"187";s:7:"newhour";s:2:"24";s:7:"hothits";s:3:"100";s:7:"amodule";s:3:"106";s:7:"tplpath";s:0:"";s:6:"DivTpl";i:1;s:5:"fiddb";N;s:5:"stype";s:1:"4";s:2:"yz";s:1:"1";s:7:"hidefid";N;s:10:"timeformat";s:11:"Y-m-d H:i:s";s:5:"order";s:6:"A.list";s:3:"asc";s:4:"DESC";s:6:"levels";s:3:"all";s:7:"rowspan";s:1:"6";s:3:"sql";s:104:" SELECT A.*,A.aid AS id FROM qb_article A  WHERE A.yz=1  AND A.mid=\'106\'   ORDER BY A.list DESC LIMIT 6 ";s:4:"sql2";N;s:7:"colspan";s:1:"1";s:11:"content_num";s:2:"80";s:12:"content_num2";s:3:"120";s:8:"titlenum";s:2:"18";s:9:"titlenum2";s:2:"40";s:10:"titleflood";s:1:"0";s:10:"c_rolltype";s:1:"0";}', 'a:3:{s:5:"div_w";s:2:"50";s:5:"div_h";s:2:"30";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n3', 'code', 0, '����115������,��ʼ������', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n4', 'code', 0, '������', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n5', 'code', 0, '<a href="list.php?fid=1" target="_blank">����&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n6', 'code', 0, '�Ƽ�����Ϣ', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n7', 'code', 0, '<a href="list.php?fid=1" target="_blank">����&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n8', 'code', 0, '��������Ϣ', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_n9', 'code', 0, '<a href="list.php?fid=1" target="_blank">����&gt;&gt;</a>', 'a:4:{s:9:"html_edit";N;s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_roll', 'rollpic', 0, 'a:6:{s:8:"rolltype";s:1:"0";s:5:"width";s:3:"386";s:6:"height";s:3:"202";s:6:"picurl";a:2:{i:1;s:32:"label/1_20101018161044_ao7o8.jpg";i:2;s:32:"label/1_20101018161000_boy6x.jpg";}s:7:"piclink";a:2:{i:1;s:1:"#";i:2;s:1:"#";}s:6:"picalt";a:2:{i:1;s:0:"";i:2;s:0:"";}}', 'a:3:{s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'yellow');
INSERT INTO `qb_label` (`lid`, `name`, `ch`, `chtype`, `tag`, `type`, `typesystem`, `code`, `divcode`, `hide`, `js_time`, `uid`, `username`, `posttime`, `pagetype`, `module`, `fid`, `if_js`, `style`) VALUES ('', '', 0, 0, 'buy_tt1ad', 'pic', 0, 'a:4:{s:6:"imgurl";s:32:"label/1_20101101161109_wqdn9.jpg";s:7:"imglink";s:1:"#";s:5:"width";s:3:"980";s:6:"height";s:2:"35";}', 'a:3:{s:5:"div_w";s:0:"";s:5:"div_h";s:0:"";s:11:"div_bgcolor";s:0:"";}', 0, 0, 1, 'admin', 0, 0, 35, 0, 0, 'default');


# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_collection`
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
# �������е����� `qb_buy_collection`
#


# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_comments`
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
# �������е����� `qb_buy_comments`
#


# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_config`
#

DROP TABLE IF EXISTS `qb_buy_config`;
CREATE TABLE `qb_buy_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) TYPE=MyISAM;

#
# �������е����� `qb_buy_config`
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
INSERT INTO `qb_buy_config` VALUES ('Info_metakeywords', '��Ʒ', '');
INSERT INTO `qb_buy_config` VALUES ('Info_webOpen', '1', '');
INSERT INTO `qb_buy_config` VALUES ('Info_webname', '�󹺲�Ʒ', '');
INSERT INTO `qb_buy_config` VALUES ('order_send_mail', '1', '');
INSERT INTO `qb_buy_config` VALUES ('Info_ReportDB', '�Ƿ���Ϣ\r\n�����Ϣ\r\n������Ϣ', '');
INSERT INTO `qb_buy_config` VALUES ('module_pre', 'buy_', '');
INSERT INTO `qb_buy_config` VALUES ('module_id', '35', '');
INSERT INTO `qb_buy_config` VALUES ('Info_TopColor', '#FF0000', '');

# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_content`
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
# �������е����� `qb_buy_content`
#

INSERT INTO `qb_buy_content` VALUES (1, '��ͭ��ͭ��', 1, 11, '���Ƹ�', 1, 0, 1288611664, '1288611664', 1, 'admin', '', 'http://i00.c.aliimg.com/img/product/70/97/31/70973167.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611665, 0, 1, '43');
INSERT INTO `qb_buy_content` VALUES (2, '����Ǧ������˿', 1, 11, '���Ƹ�', 1, 0, 1288611793, '1288611793', 1, 'admin', '', 'http://i01.c.aliimg.com/img/product/33/21/99/33219975.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611794, 0, 1, '43');
INSERT INTO `qb_buy_content` VALUES (3, '�����߷�������-Ǧ�� ����ֱ�� ���ż���', 1, 21, '����', 1, 0, 1288611864, '1288611864', 1, 'admin', '', 'http://i02.c.aliimg.com/img/offer/19/39/46/69/19394669-2.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611865, 0, 1, '654');
INSERT INTO `qb_buy_content` VALUES (4, '��20g�ֹ� 20g��ѹ��¯�� �������͸�ʴ', 1, 33, '������', 1, 0, 1288611943, '1288611943', 1, 'admin', '', 'http://i04.c.aliimg.com/img/ibank/2010/562/548/203845265_1777718237.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288611946, 0, 1, '54');
INSERT INTO `qb_buy_content` VALUES (5, '�󹺲����Բ�� 316L�����Բ��', 1, 46, '����', 1, 0, 1288612043, '1288612043', 1, 'admin', '', 'http://i03.c.aliimg.com/img/offer/33/24/15/40/7/332415407-2.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288612044, 0, 1, '43');
INSERT INTO `qb_buy_content` VALUES (6, '�󹺷�ɽ����ֹܳ�', 1, 59, '�޷��', 2, 0, 1288612132, '1288612132', 1, 'admin', '', 'http://i00.c.aliimg.com/img/offer/56/28/57/26/7/562857267.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288680842, 0, 1, '434');
INSERT INTO `qb_buy_content` VALUES (7, '�󹺻�ͭ��', 1, 87, '����', 3, 0, 1288612333, '1288612333', 1, 'admin', '', 'http://i04.c.aliimg.com/img/offer/50/94/67/51/2/509467512.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1293511098, 0, 1, '54');
INSERT INTO `qb_buy_content` VALUES (8, '�󹺴���,������ר�ô���,������', 1, 87, '����', 1, 0, 1288612560, '1288612560', 1, 'admin', '', 'http://i01.c.aliimg.com/img/offer2/2010/075/210/91075210_0eb64d7b2064ea5f5cfc34ecfd9156af.310x310.jpg', 1, 1, 0, 0, '', '127.0.0.1', 0, 0, '', 0, 0, 1288680835, 0, 1, '434');

# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_content_1`
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
# �������е����� `qb_buy_content_1`
#

INSERT INTO `qb_buy_content_1` VALUES (1, 1, 11, 1, '<p><span style="color:#0000ff;"><span style="font-size:15pt;"><img onload=\'if(this.width>600)makesmallpic(this,600,800);\' src="http://i00.c.aliimg.com/img/product/70/97/31/70973167.jpg" width="768" height="576" /><br />\r\n<br />\r\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ͭ��ͭ���ŵ� ����й�����Ӧ�÷�Χ. ��Ʒ�㷺������·����·��������ͨѶ���ӵ���ҵ�� ��õ���չ�ԺͿɼӹ���: ���Խ�һ������(ϸ)�ӹ���ͭ�������ֹ������߼�������.�����.������. �����Եľ���Ч��: ��ʹ��ͭ��ͭ�������ͭ�ߣ��ܹ�����Ƚ�Լԭ���ϳɱ����Դ����ƣ�ͭ�����ߡ�ͭ�������Լ�����Ʒ�Ķ�����Ҳ�������ͭ���ߵ������Ʒ�� �����������Ч��ͻ���Ч�� ��ʡ����ϡȱ��ͭ��Դ,����������,�������������ʩ��,���Ṥ���Ͷ�ǿ�ȡ�</span></span></p>\r\n<p><span style="font-size:15pt;"><span style="color:#0000ff;">������˾ӵ���Ƚ����ռ����������豸��ӵ�ж���������������������0.10-��1.60���ͭ��ͭ�߸����Ʒ������ ��������ϣ������������³��ҽ������ں��������ݻ�������ͬ��չ��ս�Ի���ϵ�����ȳ���ӭ���Ա���˾��Ʒ���������������ģ�߱ؽ�Ϊ����������ҵ�����ɱ����߳�Ŭ��!</span></span></p>\r\n', '��', 43, '2010-11-30');
INSERT INTO `qb_buy_content_1` VALUES (2, 2, 11, 1, '<p>��Ǧ���ߣ�˿�����</p>\r\n<p>�� ��ͭ��Ǧ����/˿��Sn99.3CU0.7��</p>\r\n<p>�� 0.3����Ǧ����/˿��Sn99.0Ag0.3Cu0.7��</p>\r\n<p>�� ����ͭ��Ǧ����/˿��Sn96.5Ag3.0Cu0.5��</p>\r\n<p>�� ʵо����Ǧ����/˿��������������</p>\r\n<p>�� С������Ǧ����/˿����1.4%���㣩</p>\r\n<p>&nbsp;</p>\r\n<p>��Ǧ����˿/˿�����ࣺ</p>\r\n<p>�� ��Ǧ����о������/˿&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����Ǧ��ϴ������/˿</p>\r\n<p>�� ��Ǧ������/˿&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����Ǧˮ���Ժ�����/˿</p>\r\n<p>�� ��Ǧ����������/˿&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����Ǧ����ֺ�����/˿</p>\r\n<p>�� ��Ǧ����������/˿&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����Ǧ����������/˿</p>\r\n<p>�� ��±�غ�����/˿</p>\r\n<p>&nbsp;</p>\r\n<p>��Ǧ���ߣ�˿���ص㣺</p>\r\n<p>�� ���õ�ʪ���Ե������ȵ���������</p>\r\n<p>�� ���ͻ����趩�����㺬�����Ӳ��ɽ�</p>\r\n<p>�� �������ֲ�������о���޶�����������</p>\r\n<p>�� ���߾��Ȳ���������ٶȿ������</p>\r\n<p>�� ��˿�߾���С�ɣ�0.5--3.0mm���ɶ���</p>\r\n<p>&nbsp;</p>\r\n', '��', 3, '2010-11-27');
INSERT INTO `qb_buy_content_1` VALUES (3, 3, 21, 1, '<div><p>��� ���0.5-15mm ���1000mm ����2000-8000mm ͬʱ�ɰ��û��ṩ�Ĺ��������Ӧ���ֹ���Ǧ�塣 Ҳ���԰��û���Ҫ������Ǧ�Ʒ��������Ʒ���豸��</p>\r\n</div>\r\n', '��', 4, '2010-11-26');
INSERT INTO `qb_buy_content_1` VALUES (4, 4, 33, 1, '<p>Ʒ����20g��ѹ��¯��</p>\r\n<p>&nbsp;</p>\r\n<p>���ԣ��־�ǿ�ȸߡ����������͸�ʴ���������õ���֯�ȶ��ԡ�</p>\r\n<p>&nbsp;</p>\r\n<p>��;����Ҫ���������ѹ�ͳ���ѹ��¯�Ĺ������ܡ��������ܡ������ܡ��������ܵȡ�</p>\r\n<p>&nbsp;</p>\r\n<p>��Ӫ����⾶10-530mm���ں�2-70mm��</p>\r\n<p>&nbsp;</p>\r\n<p>���ȣ�4-11��</p>\r\n<p>&nbsp;</p>\r\n<p>���أ���򡢰�ͷ</p>\r\n<p>&nbsp;</p>\r\n<p>ִ�б�׼��GB/T5310-1995</p>\r\n<p>&nbsp;</p>\r\n<p>�ֻ���ǧ������</p>\r\n<p>&nbsp;</p>\r\n<p>��װ��ʽ������</p>\r\n<p>&nbsp;</p>\r\n<p>��Ӧ��λ���ĳ��������ֹ����޹�˾</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ������2005�꣬ԭ�����ĳ����д��ֹ����޹�˾��</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ����˾���꾭Ӫ��򡢰�ͷ�������ȴ�ֳ�������20g��ѹ��¯�ܣ��ֻ��࣬���ȫ���۸��Żݡ���ӭ����û���������Ǣ̸������ָ�������� </p>\r\n<p>&nbsp;</p>\r\n', '��', 45, '2010-11-19');
INSERT INTO `qb_buy_content_1` VALUES (5, 5, 46, 1, '<div><p>껺����ó���Ǿ�Ӫ����ֵ�רҵ��˾��Ŀǰ��˾���żҸۡ�ɽ��̫ԭ��̨���ǹ������¡�<br />\r\n�Ϻ��ȸ���ֳ����������õĹ����ϵ�����Ŷ����ҵ����������˾��Ӫ��ϵ�в���ֲ�Ʒ�㷺Ӧ���ڻ���<br />\r\nʯ�͡���Ȼ������������ҩ��ʳƷ��е����ˮ��ů������ͬʱ����˾����ӹ������ɰ��û�����������<br />\r\n�Ͳģ��������ӹ�����ּ�����Ʒ��<br />\r\n&nbsp;<br />\r\n&nbsp;<br />\r\n&nbsp;&nbsp;&nbsp; ����������˾���š�������һ��������һ���������ϡ���ı��չ���ľ�Ӫ��ּ���������Ĺ��ͻ��ṩ<br />\r\n�����ʵĲ�Ʒ�������ܵ��ķ��񡣱���˾֣�س�ŵ�����ֲ��ʡ����ֹ��Ĳ�Ʒ�����г���ͼ۹�Ӧ����ӭ��<br />\r\nѯ���٣�&nbsp;</p>\r\n</div>\r\n', '��', 4, '2010-11-26');
INSERT INTO `qb_buy_content_1` VALUES (6, 6, 59, 1, '<div><p>��ɽ�����к������������޹�˾<br />\r\n�ҹ�˾λ�����в��������֮�Ƶķ�ɽ����ʯ��ˮ½��ͨ��Ϊ����,��һ�Ҳ����ִ�����Ӫ����ģʽ������רҵ����ֹ�˾��<br />\r\n��˾��Ӫ���ֹ���ͺŲ���ֹܣ���Ʒ�漰200��300ϵ�С�<br />\r\n�ҹ�˾ƾ��������г����������õ�ҵ��ڱ����ۺ���ʽ�ʵ���Լ����õĿ����ϵ���ڲ������ҵ�����˹㷺��֪����,��ҵ������ʢ����</p>\r\n<p>��˾������ֲ������ۡ��ӹ�������Ϊһ��,����רӪ���ֲ���ֹܲ�,,�����ȫ,���в����߿���,<br />\r\n��ӭ������ʿ������Я�ֺ�������˾�����Żݵļ۸��ŻݵĲ��ϡ������ķ���������ͻ���Ҫ��<br />\r\n�߳ϻ�ӭ������Ͽͻ���ѯ�ݹˡ�Ը��������Ѿ��Ϻ�������ͬ��չ��&nbsp;</p>\r\n<p>רҵ�ֻ���Ӧ���ֲ����װ���ù�,��е�ṹ�͹�ҵ�ܲ�<br />\r\n�����ȫ���۸��Żݻ�ӭ������ѯ��лл����</p>\r\n</div>\r\n', '��', 43, '2010-11-27');
INSERT INTO `qb_buy_content_1` VALUES (7, 7, 87, 1, '����з����ƹ����޹�˾-���ڽ��ǡ��������١���������¸���ͷ���������ʻ������������ٽ�ͨ��ݱ�������ȫ��������ҵ�������֪����ҵ��ʼ����1995�꣬����ְ��1800�ˣ�����רҵ������Ա236�ˣ�����ռ�����1200Ķ������22��ERW����Ƶ���������ߣ�6���ȶ�п�����ߣ�12�����������ߣ�4�����������ߣ�1��ʯ�͡���Ȼ���������׹������ߡ���Ʒ��Ҫ����ʯ�͡���Ȼ����ú����ú������е���й�����������������������������¯��ũ�����衢���Ҵ����ܡ��ֹ���ܡ��������ߡ�����ҵ����Ʒִ��API\\SPEC5L��API\\SPEC5CT��ASTA53��EN10217����GB/T9711.1��GP/T9711.2��GB/T3091-2008��GB/T13793-9292��GB/14291-2006���ȹ��������±�׼���ҹ�˾��Ʒͨ��ISO9001��ISO14001��API��PED-CE���¹�AD2000-WO�����ꡢӢ�ꡢ�ձꡢŷ�ˡ����������������ϵҪ�󡣿����ֱ��20mm-426mm����150��֣�����ڸ���ֹ�10��֡�<br />\r\n���ܡ��عܣ�15*15--300*500<br />\r\n��п�ܡ�ţͷ�ơ�����Ƶ���ܡ����κ��ܣ�ֱ��4�֡�6�֡�1�硢1.2�硢1.5�硢2�硢76��89��102��108��114��121��127��133��140��152��159��165��168��180��194��203��219��245��250��273��299��306��325��351��355��377��406��426��530.����4mm-16mm���ں�0.8mm-14mm<br />\r\n�����ֹ�:245��250��273��299��325��351��377��406��426.<br />\r\nʯ����Ȼ���������׹ܣ�57��73��114.3��127��168.3��139.7��177.8��219.1��244.5��273.1.&nbsp;&nbsp;&nbsp; ��񡢱ں񡢿ھ��ɸ��ݿͻ�Ҫ������<br />\r\n', '��', 43, '2010-11-27');
INSERT INTO `qb_buy_content_1` VALUES (8, 8, 87, 1, '<div><p><span style="font-size:10pt;font-family:����;">̫�����ҹ��������к���������Ҫ���أ�Ҳ���ҹ��������ƺ�������������ҵ����<span>1955</span>�꿪ʼ�����ڣ�������ʮ������о����������Ѿ��γ�һ���׶��ص��������պͶ���;����Ʒ�֡�����Ĵ���ϵ�в�Ʒ��̫��������������ḻ���豸���������������ۺ񡢼���ֶ�����<span>,</span>��������·���Ƚ���</span></p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p><span style="font-size:small;"><font size="2"><span style="color:red;font-family:����;">��Ӫ��Ʒ</span><span><span style="font-family:Times New Roman;">:</span></span></font></span></p>\r\n<p><span style="font-size:small;"><font size="2"><span style="font-family:����;">����</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:����;">ԭ�ϴ���</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:����;">�繤����</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:����;">������</span><span><span style="font-family:Times New Roman;">,</span></span><span style="font-family:����;">������</span><span style="font-family:Times New Roman;"><span>.</span></span><span style="color:black;font-family:����;">�ߴ���ԭ�ϴ���</span><span style="color:black;"><span style="font-family:Times New Roman;">;YTO/YTO1;</span></span><span style="color:black;font-family:����;">�����ܵ�Ŵ���</span><span style="color:black;"><span style="font-family:Times New Roman;">;DT4 /DT4A;DT4E/DT4C;DT8/DT9;</span></span><span style="color:black;font-family:����;">����</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">����</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">��Բ</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">���</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">���ܻ�е�ӹ�</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">��������</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">��Ŵ�����������</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">��Ŵ��������а�</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">��������ֱ��</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">��������</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">�����Ͳ�</span><span style="color:black;"><span style="font-family:Times New Roman;">;</span></span><span style="color:black;font-family:����;">���촿��</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:����;">������ר�ô���</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:����;">��ҵ������</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:����;">��������</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:����;">����</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:����;">��о�õ�Ŵ���</span><span style="color:black;"><span style="font-family:Times New Roman;">,</span></span><span style="color:black;font-family:����;">����</span><span style="color:black;"><span style="font-family:Times New Roman;">70</span></span><span style="color:black;font-family:����;">����</span><span style="color:black;"><span style="font-family:Times New Roman;">.</span></span></font></span></p>\r\n</div>\r\n', '��', 34, '2010-11-20');

# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_content_2`
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
# �������е����� `qb_buy_content_2`
#


# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_db`
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
# �������е����� `qb_buy_db`
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
# ��Ľṹ `qb_buy_field`
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
# �������е����� `qb_buy_field`
#

INSERT INTO `qb_buy_field` VALUES (86, 1, '����Ҫ��', 'content', 'mediumtext', 0, -1, 'ieeditsimp', 600, 250, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (159, 2, '�����۸�', 'sell_price', 'varchar', 20, 18, 'text', 50, 0, '', '', '����', '', 1, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (158, 2, '��������', 'order_num', 'int', 6, 19, 'text', 50, 0, '', '', '', '', 1, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (142, 2, '����˵��', 'content', 'mediumtext', 0, 15, 'textarea', 500, 70, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '<br><select name=\'autoSelect\' onchange="changeaddContent(this);">\r\n<option value=\'\'>(���ô��֣���������д������æ��) </option>\r\n<option value=\'������һ�ݱȽ���ϸ�Ĳ�Ʒ���˵����лл��\'>������һ�ݱȽ���ϸ�Ĳ�Ʒ���˵����лл��</option> \r\n<option value=\'�������Դ˲�Ʒ�ǳ�����������\'>�������Դ˲�Ʒ�ǳ�����������</option> \r\n<option value=\'�������Դ˲�Ʒ�ж�����������\'>�������Դ˲�Ʒ�ж�����������</option> \r\n</select>\r\n<SCRIPT language="javascript">\r\n            function changeaddContent(autoSelect){\r\n			 	if (autoSelect.selectedIndex !=0){			 		\r\n			 		document.getElementById("atc_content").value = autoSelect[autoSelect.selectedIndex].value;\r\n					autoSelect.selectedIndex=0;\r\n			 	}\r\n				\r\n			 }\r\n	     </SCRIPT>');
INSERT INTO `qb_buy_field` VALUES (154, 1, '������', 'order_num', 'int', 7, 8, 'text', 30, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (153, 1, '������λ', 'my_units', 'varchar', 10, 9, 'text', 50, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (145, 2, '��ϵ�绰', 'ask_phone', 'varchar', 20, 8, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (144, 2, '��ϵ������', 'ask_username', 'varchar', 20, 9, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (146, 2, '��ϵ�ֻ�', 'ask_mobphone', 'varchar', 15, 7, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (147, 2, '��ϵ����', 'ask_email', 'varchar', 50, 6, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (148, 2, '��ϵQQ', 'ask_qq', 'varchar', 11, 5, 'text', 100, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (156, 1, '��ֹ����', 'end_day', 'varchar', 30, 5, 'time', 0, 0, '', '', '', '', 0, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (157, 2, '���۱���', 'ask_title', 'varchar', 100, 20, 'text', 300, 0, '', '', '', '', 1, 0, 0, 0, '', '', '', '', 0, '');
INSERT INTO `qb_buy_field` VALUES (161, 2, '��ϣ��', 'hope_reply', 'varchar', 25, 14, 'time', 0, 0, '', '', '֮ǰ�ظ�', '', 0, 0, 0, 0, '', '', '', '', 0, '');

# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_join`
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
# �������е����� `qb_buy_join`
#


# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_module`
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
# �������е����� `qb_buy_module`
#

INSERT INTO `qb_buy_module` VALUES (2, 0, '���۵�ģ��', 1, '', '', '', 0, 0, 'a:4:{s:4:"list";s:12:"joinlist.htm";s:4:"show";s:12:"joinshow.htm";s:4:"post";s:8:"join.htm";s:6:"search";s:0:"";}');
INSERT INTO `qb_buy_module` VALUES (1, 0, '��ģ��', 4, '', '', '', 1, 0, '');

# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_pic`
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
# �������е����� `qb_buy_pic`
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
# ��Ľṹ `qb_buy_report`
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
# �������е����� `qb_buy_report`
#


# --------------------------------------------------------

#
# ��Ľṹ `qb_buy_sort`
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
# �������е����� `qb_buy_sort`
#

INSERT INTO `qb_buy_sort` VALUES (1, 0, '�ֲ�', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (2, 0, '�ְ��', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (3, 0, '���', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (4, 0, '�к��', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (5, 0, '�ֹ�', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (6, 0, '�͸�', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (7, 0, '�ظ�', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (8, 0, '�����', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (9, 0, '¯��', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (10, 0, '����', 1, 2, 0, 1, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (11, 1, '���Ƹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (12, 1, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (13, 1, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (14, 1, 'Բ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (15, 1, '�߲�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (16, 1, '�������Ƹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (17, 1, '�������Ƹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (18, 1, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (19, 1, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (20, 1, '���߸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (21, 2, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (22, 2, '�Ȱ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (23, 2, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (24, 2, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (25, 2, '��п���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (26, 2, '��Ϳ���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (27, 2, '���ư��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (28, 2, '�ͺϽ���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (29, 2, '�������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (30, 2, '����п���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (31, 2, '��ϴ���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (32, 2, '��Ӳ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (33, 3, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (34, 3, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (35, 3, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (36, 3, '�Ͻ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (37, 3, '��ǿ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (38, 3, '�͸�ʴ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (39, 3, '���������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (40, 3, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (41, 3, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (42, 3, '���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (43, 3, '��п��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (44, 3, '��Ϳ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (45, 3, '���߸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (46, 4, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (47, 4, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (48, 4, 'ģ�߰�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (49, 4, '��¯��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (50, 4, '��Ե��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (51, 4, '����ƽ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (52, 4, '���ư�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (53, 4, '�ͺϽ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (54, 4, '�̰�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (55, 4, '̼���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (56, 4, '�Ͻ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (57, 4, '��ƽ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (58, 4, '��ĥ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (59, 5, '�޷��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (60, 5, '��п��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (61, 5, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (62, 5, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (63, 5, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (64, 5, 'ֱ���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (65, 5, 'Բ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (66, 5, '��¯��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (67, 5, '��ī��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (68, 5, '���͹�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (69, 5, '�����ù�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (70, 5, '�ṹ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (71, 5, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (72, 5, '���ּ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (73, 6, 'H�͸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (74, 6, '�۸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (75, 6, '���ָ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (76, 6, '�Ǹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (77, 6, '���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (78, 6, '���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (79, 6, '�ع�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (80, 6, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (81, 6, '���ȱ߽Ǹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (82, 6, '�͸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (83, 6, '���Ǹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (84, 6, '����Բ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (85, 6, '̼Բ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (86, 6, '�ȱ߽Ǹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (87, 7, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (88, 7, 'Բ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (89, 7, '��и�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (90, 7, '̼���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (91, 7, '���ɸ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (92, 7, '�߹���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (93, 7, 'ģ�߸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (94, 7, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (95, 7, '���ָ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (96, 7, '�Ͻ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (97, 7, '�͸�ʴ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (98, 7, '��ĥ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (99, 7, '̼����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (100, 7, '̼�ظ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (101, 7, '���ȸ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (102, 7, '�����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (103, 7, '��ģ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (104, 7, '�ṹ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (105, 8, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (106, 8, '����ֹ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (107, 8, '������߲�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (108, 8, '����ִ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (109, 8, '�����Բ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (110, 8, '����ֽǸ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (111, 8, '����ֲ۸�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (112, 8, '����ֱ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (113, 8, '�������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (114, 8, '�ź���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (115, 8, '��˿', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (116, 8, 'Ӳ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (117, 8, '���ȸ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (118, 8, '��˿', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (119, 9, '��ʯ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (120, 9, 'ú��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (121, 9, '������', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (122, 9, '�ϸ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (123, 9, '��̿', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (124, 9, 'ú̿', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (125, 9, '�ͻ����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (126, 9, '̼�ز���', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (127, 9, '��ī����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (128, 9, '�ֶ�', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (129, 9, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (130, 9, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (131, 9, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (132, 9, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (133, 9, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (134, 9, '��ĥ����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (135, 9, '���ϸ���Ʒ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (136, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (137, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (138, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (139, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (140, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (141, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (142, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (143, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (144, 10, '����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (145, 10, '��ɫ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (146, 10, 'ͭ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (147, 10, '��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (148, 10, 'п', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (149, 10, '��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (150, 10, '��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (151, 10, '��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (152, 10, 'Ǧ', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (153, 10, '��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (154, 10, 'ϡ��', 1, 3, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
INSERT INTO `qb_buy_sort` VALUES (155, 10, '�����', 1, 3, 0, 0, '', 0, 0, '', '', '', '', 'a:4:{s:4:"head";s:0:"";s:4:"foot";s:0:"";s:4:"list";s:0:"";s:8:"bencandy";s:0:"";}', '', 0, '', '', '', 0, '', '', '', '', 0, 'a:2:{s:7:"is_html";N;s:11:"field_value";N;}', 0, 0, '', 'guijinshu', 0);
