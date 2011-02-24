INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`) VALUES (21, 2, '万能表单', 'form_', 'form', '', '', '', 0, '', '', 0);


DROP TABLE IF EXISTS `qb_form_config`;
CREATE TABLE `qb_form_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) TYPE=MyISAM;

#
# 导出表中的数据 `qb_form_config`
#

INSERT INTO `qb_form_config` VALUES ('module_id', '21', '');
INSERT INTO `qb_form_config` VALUES ('module_pre', 'form_', '');
INSERT INTO `qb_form_config` VALUES ('Info_webOpen', '1', '');
INSERT INTO `qb_form_config` VALUES ('Info_webname', '万能表单', '');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content`
#

DROP TABLE IF EXISTS `qb_form_content`;
CREATE TABLE `qb_form_content` (
  `id` mediumint(7) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `mid` smallint(4) NOT NULL default '0',
  `hits` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` varchar(10) NOT NULL default '',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `titlecolor` varchar(15) NOT NULL default '',
  `yz` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `hits` (`hits`,`yz`),
  KEY `list` (`list`,`yz`)
) TYPE=MyISAM AUTO_INCREMENT=32 ;

#
# 导出表中的数据 `qb_form_content`
#

INSERT INTO `qb_form_content` VALUES (22, '', 7, 0, 1237208241, '1237208241', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (26, '', 6, 7, 1237250809, '1237250809', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (27, '', 3, 1, 1237260673, '1237260673', 1, 'admin', '', 1, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (30, '', 2, 7, 1237269830, '1237269830', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (29, '', 2, 2, 1237268864, '1237268864', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (25, '', 3, 2, 1237214289, '1237214289', 1, 'admin', '', 1, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (24, '', 6, 3, 1237213169, '1237213169', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (31, '', 2, 6, 1239780761, '1239780761', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (18, '', 3, 0, 1236936110, '1236936110', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (19, '', 5, 0, 1236939584, '1236939584', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (20, '', 6, 0, 1237174883, '1237174883', 1, 'admin', '', 0, '192.168.0.99');
INSERT INTO `qb_form_content` VALUES (23, '', 7, 0, 1237208253, '1237208253', 1, 'admin', '', 0, '192.168.0.99');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content_1`
#

DROP TABLE IF EXISTS `qb_form_content_1`;
CREATE TABLE `qb_form_content_1` (
  `id` mediumint(7) unsigned NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `truename` varchar(20) NOT NULL default '',
  `sex` int(1) NOT NULL default '0',
  `oicq` varchar(10) NOT NULL default '',
  `mobphone` varchar(11) NOT NULL default '',
  `interest` mediumtext NOT NULL,
  `introduce` mediumtext NOT NULL,
  `sortname` varchar(40) NOT NULL default '',
  `webtime` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM;

#
# 导出表中的数据 `qb_form_content_1`
#


# --------------------------------------------------------

#
# 表的结构 `qb_form_content_2`
#

DROP TABLE IF EXISTS `qb_form_content_2`;
CREATE TABLE `qb_form_content_2` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `workplace` varchar(100) NOT NULL default '',
  `nums` varchar(10) NOT NULL default '',
  `jobrequire` mediumtext NOT NULL,
  `workwhere` varchar(50) NOT NULL default '',
  `wage` varchar(30) NOT NULL default '',
  `asksex` int(1) NOT NULL default '0',
  `schoo_age` varchar(20) NOT NULL default '',
  `wageyear` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=32 ;

#
# 导出表中的数据 `qb_form_content_2`
#

INSERT INTO `qb_form_content_2` VALUES (29, 1, 'JAVA程序员', '5', '独立开发程序', '深圳', '800元/月', 2, '高中', '两年以上');
INSERT INTO `qb_form_content_2` VALUES (30, 1, '市场总监', '2', '负责产品的销售.', '广州', '8000元/月', 0, '本科', '三年以上');
INSERT INTO `qb_form_content_2` VALUES (31, 1, '销售经理', '8', '负责我公司的产品销售.', '广州市', '3000', 0, '大专', '一年以上');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content_3`
#

DROP TABLE IF EXISTS `qb_form_content_3`;
CREATE TABLE `qb_form_content_3` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `advicetype` varchar(30) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `truename` varchar(15) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `mobphone` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

#
# 导出表中的数据 `qb_form_content_3`
#

INSERT INTO `qb_form_content_3` VALUES (18, 1, '售后客服', '', '222223', '65223@qq.com', '133444444443');
INSERT INTO `qb_form_content_3` VALUES (25, 1, '售后客服', 'hhhhhhhhhhhhhhhhhh', '222223', '65223@qq.com', '13377777777');
INSERT INTO `qb_form_content_3` VALUES (27, 1, '售后客服', '192.168.0.99/55 all righ\nts reserved \n京ICP备05047353号 \nPowered by PHP168', '222223', '65223@qq.com', '13377777777');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content_4`
#

DROP TABLE IF EXISTS `qb_form_content_4`;
CREATE TABLE `qb_form_content_4` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `truename` varchar(15) NOT NULL default '',
  `sex` int(1) NOT NULL default '0',
  `age` int(2) NOT NULL default '0',
  `mobphone` varchar(25) NOT NULL default '',
  `metier` varchar(30) NOT NULL default '',
  `my_song` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_form_content_4`
#


# --------------------------------------------------------

#
# 表的结构 `qb_form_content_5`
#

DROP TABLE IF EXISTS `qb_form_content_5`;
CREATE TABLE `qb_form_content_5` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `content` mediumtext NOT NULL,
  `bday` varchar(25) NOT NULL default '',
  `school_age` varchar(20) NOT NULL default '',
  `native` varchar(30) NOT NULL default '',
  `specialty` varchar(40) NOT NULL default '',
  `skill` varchar(50) NOT NULL default '',
  `sport` varchar(80) NOT NULL default '',
  `height` int(3) NOT NULL default '0',
  `truename` varchar(15) NOT NULL default '',
  `oicq` varchar(10) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `mobphone` varchar(11) NOT NULL default '',
  `address` varchar(150) NOT NULL default '',
  `telephone` varchar(15) NOT NULL default '',
  `idcard` varchar(18) NOT NULL default '',
  `cp_title` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=20 ;

#
# 导出表中的数据 `qb_form_content_5`
#

INSERT INTO `qb_form_content_5` VALUES (19, 1, '555555555555555', '0000-00-00', '大专', '三项式', '', '', '', 0, '222223', '444444', '65223@qq.com', '13355555555', '3', '一直在fgsgfd3', '44444444444444443', '');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content_6`
#

DROP TABLE IF EXISTS `qb_form_content_6`;
CREATE TABLE `qb_form_content_6` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `workposition` varchar(50) NOT NULL default '',
  `experience` mediumtext NOT NULL,
  `workyear` int(2) NOT NULL default '0',
  `truename` varchar(15) NOT NULL default '',
  `schoo_age` varchar(15) NOT NULL default '',
  `myage` int(2) NOT NULL default '0',
  `graduateschool` varchar(40) NOT NULL default '',
  `specialty` varchar(50) NOT NULL default '',
  `skill` varchar(50) NOT NULL default '',
  `sex` int(1) NOT NULL default '0',
  `telephone` varchar(25) NOT NULL default '',
  `wage` varchar(20) NOT NULL default '',
  `address` varchar(255) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `oicq` varchar(11) NOT NULL default '',
  `worktime` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=27 ;

#
# 导出表中的数据 `qb_form_content_6`
#

INSERT INTO `qb_form_content_6` VALUES (20, 1, '程序员', '55555555555\nkkkkkkkkkkkkkkkkkkkkkk', 5, '222223', '高中', 56, '', '', '', 2, '6767', '面议', '3', '65223@qq.com', '444444', '');
INSERT INTO `qb_form_content_6` VALUES (24, 1, 'C语言工程师', '4444444444444', 4, '222223', '大专', 4, '', '', '', 2, '090-89766543', '面议', '3', '65223@qq.com', '444444', '1周内');
INSERT INTO `qb_form_content_6` VALUES (26, 1, 'C语言工程师', 'rrrrrrrrrrrrrrrrrrrrrrrrrrr', 4, '222223', '大专', 4, '', '', '', 1, '090-89766543', '1000元-2000元', '3', '65223@qq.com', '444444', '1周内');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content_7`
#

DROP TABLE IF EXISTS `qb_form_content_7`;
CREATE TABLE `qb_form_content_7` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `product` varchar(50) NOT NULL default '',
  `paymoney` varchar(15) NOT NULL default '',
  `paytime` varchar(15) NOT NULL default '',
  `paytype` varchar(25) NOT NULL default '',
  `sendbank` varchar(30) NOT NULL default '',
  `receivebank` varchar(30) NOT NULL default '',
  `truename` varchar(15) NOT NULL default '',
  `oicq` varchar(11) NOT NULL default '',
  `telephone` varchar(30) NOT NULL default '',
  `mobphone` varchar(11) NOT NULL default '',
  `address` varchar(150) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=24 ;

#
# 导出表中的数据 `qb_form_content_7`
#

INSERT INTO `qb_form_content_7` VALUES (6, 1, '整站系统(文章+核心)', '6655', '', '网上转帐', '', '', '222223', '444444', '3333333', '13333333333', '3trewtre');
INSERT INTO `qb_form_content_7` VALUES (7, 1, '整站系统(文章+核心)', '23', '2009-03-03', '在线支付', 'fff', 'eee', '222223', '444444', '333', '13344444444', '3');
INSERT INTO `qb_form_content_7` VALUES (8, 1, '整站系统(文章+核心)', '5', '', '在线支付', '', '', '222223', '444444', '一直在fgsgfd3', '13355555555', '3');
INSERT INTO `qb_form_content_7` VALUES (9, 1, '整站系统(文章+核心)', '0.01', '2009-03-13', '在线支付', 'e', 's', '222223', '444444', '一直在fgsgfd3', '13355555555', '3');
INSERT INTO `qb_form_content_7` VALUES (10, 1, '整站系统(文章+核心)', '1', '2009-03-13', '在线支付', 'e', 's', '222223', '444444', '一直在fgsgfd3', '13355555555', '3');
INSERT INTO `qb_form_content_7` VALUES (11, 1, '整站系统(文章+核心)', '0.01', '2009-03-13', 'olpay', 'e', 's', '222223', '444444', '一直在fgsgfd3', '13355555555', '3');
INSERT INTO `qb_form_content_7` VALUES (12, 1, '整站系统(文章+核心)/分类信息系统/商城系统', '54', '2009-03-03', '网上转帐', 't', 't', '222223', '444444', '一直在fgsgfd3', '13355555555', '3');
INSERT INTO `qb_form_content_7` VALUES (13, 1, '1/2/商城系统', '4', '', 'olpay', '', '', '222223', '444444', '一直在fgsgfd3', '13344444444', '3');
INSERT INTO `qb_form_content_7` VALUES (22, 1, '1', '78', '', 'olpay', '', '', '222223', '444444', '一直在fgsgfd3', '13377777777', '3');
INSERT INTO `qb_form_content_7` VALUES (23, 1, '1', '78', '', '网上转帐', '', '', '222223', '444444', '一直在fgsgfd3', '13377777777', '3');

# --------------------------------------------------------

#
# 表的结构 `qb_form_content_8`
#

DROP TABLE IF EXISTS `qb_form_content_8`;
CREATE TABLE `qb_form_content_8` (
  `id` mediumint(7) NOT NULL auto_increment,
  `uid` mediumint(7) NOT NULL default '0',
  `roomtype` varchar(30) NOT NULL default '',
  `roomnum` int(3) NOT NULL default '0',
  `numday` int(3) NOT NULL default '0',
  `intotime` varchar(30) NOT NULL default '',
  `truename` varchar(30) NOT NULL default '',
  `telephone` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# 导出表中的数据 `qb_form_content_8`
#


# --------------------------------------------------------

#
# 表的结构 `qb_form_module`
#

DROP TABLE IF EXISTS `qb_form_module`;
CREATE TABLE `qb_form_module` (
  `id` smallint(4) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `list` smallint(4) NOT NULL default '0',
  `style` varchar(50) NOT NULL default '',
  `config` mediumtext NOT NULL,
  `allowpost` varchar(255) NOT NULL default '',
  `endtime` int(10) NOT NULL default '0',
  `about` text NOT NULL,
  `usetitle` tinyint(1) NOT NULL default '0',
  `repeatpost` tinyint(1) NOT NULL default '0',
  `statename` varchar(30) NOT NULL default '',
  `allowview` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# 导出表中的数据 `qb_form_module`
#

INSERT INTO `qb_form_module` VALUES (1, '版主申请', 0, '', 'a:3:{s:8:"field_db";a:8:{s:8:"sortname";a:14:{s:5:"title";s:18:"申请哪个栏目的版主";s:10:"field_name";s:8:"sortname";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:40;s:9:"form_type";s:8:"checkbox";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:38:"新闻频道\r\n下载频道\r\n图片频道\r\n视频频道";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:9:"orderlist";s:2:"11";s:9:"allowview";N;}s:7:"webtime";a:15:{s:5:"title";s:16:"每天上网几个小时";s:10:"field_name";s:7:"webtime";s:10:"field_type";s:3:"int";s:10:"field_leng";i:10;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"4";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:4:"小时";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"10";s:9:"allowview";N;}s:8:"truename";a:15:{s:5:"title";s:8:"真实姓名";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:20;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"7";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"9";s:9:"allowview";N;}s:3:"sex";a:15:{s:5:"title";s:4:"性别";s:10:"field_name";s:3:"sex";s:10:"field_type";s:3:"int";s:10:"field_leng";i:1;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:18:"1|男\r\n2|女\r\n0|保密";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"8";s:9:"allowview";N;}s:4:"oicq";a:15:{s:5:"title";s:6:"联系QQ";s:10:"field_name";s:4:"oicq";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:10;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:8:"mobphone";a:14:{s:5:"title";s:8:"手机号码";s:10:"field_name";s:8:"mobphone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:11;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"11";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:6;s:9:"allowview";N;}s:8:"interest";a:14:{s:5:"title";s:8:"兴趣爱好";s:10:"field_name";s:8:"interest";s:10:"field_type";s:10:"mediumtext";s:10:"field_leng";i:0;s:9:"form_type";s:8:"textarea";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:5;s:9:"allowview";N;}s:9:"introduce";a:14:{s:5:"title";s:8:"自我介绍";s:10:"field_name";s:9:"introduce";s:10:"field_type";s:10:"mediumtext";s:10:"field_leng";i:0;s:9:"form_type";s:8:"textarea";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:4;s:9:"allowview";N;}}s:7:"is_html";a:1:{s:7:"content";s:4:"内容";}s:11:"listshow_db";a:2:{s:8:"truename";s:8:"真实姓名";s:3:"sex";s:4:"性别";}}', '3,4,8,9', 0, '<p><strong>如果你有激情,有梦想,就来申请做我们的版主吧!</strong></p>', 0, 0, '审批', '');
INSERT INTO `qb_form_module` VALUES (2, '招聘表单', 0, '', 'a:3:{s:8:"field_db";a:8:{s:9:"workplace";a:15:{s:5:"title";s:8:"职位名称";s:10:"field_name";s:9:"workplace";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:100;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"30";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"10";s:9:"allowview";N;}s:4:"nums";a:15:{s:5:"title";s:8:"招聘人数";s:10:"field_name";s:4:"nums";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:10;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"4";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"人";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"9";s:9:"allowview";N;}s:10:"jobrequire";a:15:{s:5:"title";s:14:"职位描述及要求";s:10:"field_name";s:10:"jobrequire";s:10:"field_type";s:10:"mediumtext";s:10:"field_leng";i:0;s:9:"form_type";s:8:"textarea";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:8;s:9:"allowview";N;}s:8:"wageyear";a:15:{s:5:"title";s:12:"工作经验要求";s:10:"field_name";s:8:"wageyear";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:12;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:40:"应届毕业生\r\n一年以上\r\n两年以上\r\n三年以上";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:9:"workwhere";a:15:{s:5:"title";s:8:"工作地点";s:10:"field_name";s:9:"workwhere";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:6;s:9:"allowview";N;}s:4:"wage";a:15:{s:5:"title";s:8:"薪水待遇";s:10:"field_name";s:4:"wage";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:5;s:9:"allowview";N;}s:6:"asksex";a:15:{s:5:"title";s:8:"性别要求";s:10:"field_name";s:6:"asksex";s:10:"field_type";s:3:"int";s:10:"field_leng";i:1;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:1:"1";s:8:"form_set";s:18:"1|男\r\n2|女\r\n0|不限";s:10:"form_value";s:1:"0";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"4";s:9:"allowview";N;}s:9:"schoo_age";a:15:{s:5:"title";s:8:"学历要求";s:10:"field_name";s:9:"schoo_age";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:20;s:9:"form_type";s:6:"select";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:46:"小学\r\n中学\r\n中专\r\n高中\r\n大专\r\n本科\r\n硕士\r\n博士";s:10:"form_value";s:0:"";s:10:"form_units";s:4:"以上";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"3";s:9:"allowview";N;}}s:7:"is_html";a:1:{s:7:"content";s:4:"内容";}s:11:"listshow_db";a:5:{s:8:"wageyear";s:12:"工作经验要求";s:9:"workplace";s:8:"职位名称";s:4:"nums";s:8:"招聘人数";s:6:"asksex";s:8:"性别要求";s:9:"schoo_age";s:8:"学历要求";}}', '', 0, '', 0, 1, '审核', '');
INSERT INTO `qb_form_module` VALUES (3, '投诉建议', 0, '', 'a:3:{s:8:"field_db";a:5:{s:10:"advicetype";a:15:{s:5:"title";s:8:"投诉类型";s:10:"field_name";s:10:"advicetype";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:34:"售前客服\r\n售后客服\r\n产品质量\r\n其它";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"10";s:9:"allowview";N;}s:8:"mobphone";a:15:{s:5:"title";s:8:"你的电话";s:10:"field_name";s:8:"mobphone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:25;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"15";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"8";s:9:"allowview";N;}s:5:"email";a:14:{s:5:"title";s:8:"你的邮箱";s:10:"field_name";s:5:"email";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"15";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:7:"content";a:15:{s:5:"title";s:8:"投诉内容";s:10:"field_name";s:7:"content";s:10:"field_type";s:10:"mediumtext";s:10:"field_leng";i:0;s:9:"form_type";s:8:"textarea";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"6";s:9:"allowview";N;}s:8:"truename";a:15:{s:5:"title";s:8:"你的称呼";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:1:"8";s:9:"allowview";N;}}s:7:"is_html";a:0:{}s:11:"listshow_db";a:2:{s:10:"advicetype";s:8:"投诉类型";s:8:"truename";s:8:"你的称呼";}}', '', 0, '', 0, 1, '处理', '3,4');
INSERT INTO `qb_form_module` VALUES (4, '活动报名表', 0, '', 'a:3:{s:8:"field_db";a:6:{s:8:"truename";a:15:{s:5:"title";s:4:"姓名";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:10;s:9:"allowview";N;}s:3:"sex";a:14:{s:5:"title";s:4:"性别";s:10:"field_name";s:3:"sex";s:10:"field_type";s:3:"int";s:10:"field_leng";i:1;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:18:"1|男\r\n2|女\r\n0|保密";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:9;s:9:"allowview";N;}s:3:"age";a:15:{s:5:"title";s:4:"年龄";s:10:"field_name";s:3:"age";s:10:"field_type";s:3:"int";s:10:"field_leng";i:2;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"2";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"岁";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:8;s:9:"allowview";N;}s:8:"mobphone";a:14:{s:5:"title";s:8:"联系电话";s:10:"field_name";s:8:"mobphone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:25;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"12";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:7:"my_song";a:15:{s:5:"title";s:8:"参赛歌曲";s:10:"field_name";s:7:"my_song";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"30";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:6;s:9:"allowview";N;}s:6:"metier";a:15:{s:5:"title";s:4:"职业";s:10:"field_name";s:6:"metier";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:6:"select";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:32:"程序员\r\n销售\r\n文员\r\n工程师\r\n其它";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:5;s:9:"allowview";N;}}s:7:"is_html";a:2:{s:7:"content";s:4:"内容";s:5:"my_88";s:13:"我的字段my_88";}s:11:"listshow_db";a:4:{s:8:"truename";s:4:"姓名";s:3:"age";s:4:"年龄";s:6:"metier";s:4:"职业";s:7:"my_song";s:8:"参赛歌曲";}}', '', 0, '<p>&nbsp;&nbsp; 为感谢广大会员对本网站的支持，特地举行第一届歌唱比赛，欢迎大家踊跃报名参加。具体事宜如下：</p>\r\n<p><strong>报名时间：</strong>从即日起——9月4号晚上8：00截止</p>\r\n<p><strong>节目时间：</strong>9月5号（星期一）晚上8：30——11：00</p>\r\n<p><strong>报名要求：</strong>须是注册会员，有语音设备能在聊天室说话。在比赛当天须用论坛注册名字登陆聊天室.</p>\r\n<p><strong>参赛曲目：</strong>自选。可清唱 可带音乐演唱</p>\r\n<p><strong>评委：</strong>人称鸡顺（召集评委两名，限有道者 有智者 智者 仁者 尊者或以上级别会员）</p>\r\n<p><strong>评选方式：</strong>在比赛结束后，由所有会员在此贴中投票，每人限投2票，投票时间限6天，既从9月5日11：01——9月11日晚8：00止。最后由评委统计票数后，评选出票数最多的前三名。</p>\r\n<p><strong>奖励：</strong>对于获得前三名的歌手，将给予不同程度的魅力和积分奖励。</p>\r\n<p>至于报名人数方面暂时不做要求，将根据大家报名顺序，和聊天室时间，为大家排演唱顺序，将于9月4日晚上10：00以前在该贴公布和发短信通知的形式参加此次比赛的选手名单，和演唱顺序。</p>\r\n<p><strong>主持：</strong>玉箫 嘉宾</p>', 0, 1, '审核', '');
INSERT INTO `qb_form_module` VALUES (5, '员工资料登记表', 0, '', 'a:3:{s:8:"field_db";a:16:{s:8:"cp_title";a:15:{s:5:"title";s:8:"公司职位";s:10:"field_name";s:8:"cp_title";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"15";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:20;s:9:"allowview";N;}s:8:"truename";a:15:{s:5:"title";s:4:"姓名";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:19;s:9:"allowview";N;}s:4:"bday";a:14:{s:5:"title";s:8:"出生日期";s:10:"field_name";s:4:"bday";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:25;s:9:"form_type";s:4:"time";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:18;s:9:"allowview";N;}s:6:"height";a:15:{s:5:"title";s:4:"身高";s:10:"field_name";s:6:"height";s:10:"field_type";s:3:"int";s:10:"field_leng";i:3;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"3";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"CM";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:17;s:9:"allowview";N;}s:6:"native";a:15:{s:5:"title";s:4:"籍贯";s:10:"field_name";s:6:"native";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:16;s:9:"allowview";N;}s:10:"school_age";a:14:{s:5:"title";s:4:"学历";s:10:"field_name";s:10:"school_age";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:20;s:9:"form_type";s:6:"select";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:45:"初中\r\n中专\r\n高中\r\n大专\r\n本科\r\n硕士\r\n博士\r\nMBA";s:10:"form_value";s:4:"大专";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:15;s:9:"allowview";N;}s:9:"specialty";a:15:{s:5:"title";s:4:"专业";s:10:"field_name";s:9:"specialty";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:40;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:14;s:9:"allowview";N;}s:4:"oicq";a:15:{s:5:"title";s:6:"QQ号码";s:10:"field_name";s:4:"oicq";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:10;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:13;s:9:"allowview";N;}s:5:"email";a:15:{s:5:"title";s:4:"邮箱";s:10:"field_name";s:5:"email";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"15";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:12;s:9:"allowview";N;}s:8:"mobphone";a:15:{s:5:"title";s:8:"手机号码";s:10:"field_name";s:8:"mobphone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:11;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"11";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:11;s:9:"allowview";N;}s:5:"skill";a:15:{s:5:"title";s:4:"特长";s:10:"field_name";s:5:"skill";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:10;s:9:"allowview";N;}s:6:"idcard";a:15:{s:5:"title";s:10:"身份证号码";s:10:"field_name";s:6:"idcard";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:18;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"18";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:9;s:9:"allowview";N;}s:5:"sport";a:15:{s:5:"title";s:8:"体育爱好";s:10:"field_name";s:5:"sport";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:80;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:8;s:9:"allowview";N;}s:9:"telephone";a:15:{s:5:"title";s:8:"家庭电话";s:10:"field_name";s:9:"telephone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"12";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:7:"address";a:15:{s:5:"title";s:8:"家庭地址";s:10:"field_name";s:7:"address";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:150;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"30";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:6;s:9:"allowview";N;}s:7:"content";a:15:{s:5:"title";s:8:"自我评价";s:10:"field_name";s:7:"content";s:10:"field_type";s:10:"mediumtext";s:10:"field_leng";i:0;s:9:"form_type";s:8:"textarea";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:5;s:9:"allowview";N;}}s:7:"is_html";a:0:{}s:11:"listshow_db";a:2:{s:6:"native";s:4:"籍贯";s:8:"truename";s:4:"姓名";}}', '', 0, '', 0, 0, '审核', '');
INSERT INTO `qb_form_module` VALUES (6, '求职表单', 0, '', 'a:3:{s:8:"field_db";a:16:{s:12:"workposition";a:15:{s:5:"title";s:8:"求职岗位";s:10:"field_name";s:12:"workposition";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"30";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"21";s:9:"allowview";N;}s:8:"truename";a:15:{s:5:"title";s:4:"姓名";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:20;s:9:"allowview";N;}s:3:"sex";a:15:{s:5:"title";s:4:"性别";s:10:"field_name";s:3:"sex";s:10:"field_type";s:3:"int";s:10:"field_leng";i:1;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:18:"1|男\r\n2|女\r\n0|保密";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:19;s:9:"allowview";N;}s:5:"myage";a:15:{s:5:"title";s:4:"年龄";s:10:"field_name";s:5:"myage";s:10:"field_type";s:3:"int";s:10:"field_leng";i:2;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"2";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"岁";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:18;s:9:"allowview";N;}s:9:"schoo_age";a:15:{s:5:"title";s:4:"学历";s:10:"field_name";s:9:"schoo_age";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:6:"select";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:45:"初中\r\n中专\r\n高中\r\n大专\r\n本科\r\n硕士\r\n博士\r\nMBA";s:10:"form_value";s:4:"大专";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:17;s:9:"allowview";N;}s:14:"graduateschool";a:15:{s:5:"title";s:8:"毕业学校";s:10:"field_name";s:14:"graduateschool";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:40;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"15";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:16;s:9:"allowview";N;}s:9:"specialty";a:15:{s:5:"title";s:4:"专业";s:10:"field_name";s:9:"specialty";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:15;s:9:"allowview";N;}s:5:"skill";a:15:{s:5:"title";s:4:"特长";s:10:"field_name";s:5:"skill";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:14;s:9:"allowview";N;}s:8:"workyear";a:15:{s:5:"title";s:8:"工作年限";s:10:"field_name";s:8:"workyear";s:10:"field_type";s:3:"int";s:10:"field_leng";i:2;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"2";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"年";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:13;s:9:"allowview";N;}s:10:"experience";a:15:{s:5:"title";s:8:"工作经验";s:10:"field_name";s:10:"experience";s:10:"field_type";s:10:"mediumtext";s:10:"field_leng";i:2;s:9:"form_type";s:8:"textarea";s:15:"field_inputleng";s:1:"2";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:12;s:9:"allowview";N;}s:4:"wage";a:15:{s:5:"title";s:8:"期望月薪";s:10:"field_name";s:4:"wage";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:20;s:9:"form_type";s:6:"select";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:73:"面议\r\n1000元以下\r\n1000元-2000元\r\n2000元-3000元\r\n3000元-4000元\r\n4000元以上";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:11;s:9:"allowview";N;}s:7:"address";a:15:{s:5:"title";s:10:"当前居住地";s:10:"field_name";s:7:"address";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:255;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"70";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:10;s:9:"allowview";N;}s:9:"telephone";a:15:{s:5:"title";s:8:"联系电话";s:10:"field_name";s:9:"telephone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:25;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:9;s:9:"allowview";N;}s:5:"email";a:15:{s:5:"title";s:8:"联系邮箱";s:10:"field_name";s:5:"email";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:8;s:9:"allowview";N;}s:4:"oicq";a:15:{s:5:"title";s:6:"QQ号码";s:10:"field_name";s:4:"oicq";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:11;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"9";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:8:"worktime";a:15:{s:5:"title";s:8:"到岗日期";s:10:"field_name";s:8:"worktime";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:34:"随时\r\n1周内\r\n2周内\r\n3周内\r\n1个月内";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:6;s:9:"allowview";N;}}s:7:"is_html";a:1:{s:7:"content";s:4:"内容";}s:11:"listshow_db";a:7:{s:6:"my_537";s:8:"工作经验";s:6:"my_425";s:8:"工作年限";s:8:"truename";s:4:"姓名";s:5:"myage";s:4:"年龄";s:8:"workyear";s:8:"工作年限";s:3:"sex";s:4:"性别";s:12:"workposition";s:8:"求职岗位";}}', '', 0, '', 0, 1, '录用', '');
INSERT INTO `qb_form_module` VALUES (7, '产品订单', 0, '', 'a:3:{s:8:"field_db";a:11:{s:7:"product";a:15:{s:5:"title";s:8:"产品名称";s:10:"field_name";s:7:"product";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:50;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"40";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"30";s:9:"allowview";N;}s:7:"paytype";a:15:{s:5:"title";s:8:"付款方式";s:10:"field_name";s:7:"paytype";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:25;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:70:"olpay|在线支付\r\n网上转帐\r\nATM机/银行柜台转帐汇款\r\n货到付款\r\n其它方式\r\n";s:10:"form_value";s:5:"olpay";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"29";s:9:"allowview";N;}s:7:"paytime";a:14:{s:5:"title";s:8:"付款日期";s:10:"field_name";s:7:"paytime";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"time";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:28;s:9:"allowview";N;}s:11:"receivebank";a:14:{s:5:"title";s:12:"款项转入银行";s:10:"field_name";s:11:"receivebank";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:27;s:9:"allowview";N;}s:8:"sendbank";a:14:{s:5:"title";s:12:"款项转出银行";s:10:"field_name";s:8:"sendbank";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"20";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:26;s:9:"allowview";N;}s:8:"paymoney";a:15:{s:5:"title";s:8:"支付金额";s:10:"field_name";s:8:"paymoney";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"元";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"25";s:9:"allowview";N;}s:8:"truename";a:15:{s:5:"title";s:10:"联系人姓名";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:15;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"10";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"24";s:9:"allowview";N;}s:4:"oicq";a:14:{s:5:"title";s:8:"联系人QQ";s:10:"field_name";s:4:"oicq";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:11;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"11";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"23";s:9:"allowview";N;}s:9:"telephone";a:14:{s:5:"title";s:10:"联系人电话";s:10:"field_name";s:9:"telephone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"15";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"22";s:9:"allowview";N;}s:8:"mobphone";a:14:{s:5:"title";s:10:"联系人手机";s:10:"field_name";s:8:"mobphone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:11;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"11";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:21;s:9:"allowview";N;}s:7:"address";a:14:{s:5:"title";s:10:"联系人地址";s:10:"field_name";s:7:"address";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:150;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"60";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";s:2:"20";s:9:"allowview";N;}}s:7:"is_html";a:1:{s:7:"content";s:4:"内容";}s:11:"listshow_db";a:3:{s:7:"paytype";s:8:"付款方式";s:8:"truename";s:10:"联系人姓名";s:8:"paymoney";s:8:"支付金额";}}', '', 0, '', 0, 1, '付款', '');
INSERT INTO `qb_form_module` VALUES (8, '酒店房间预定', 0, '', 'a:3:{s:8:"field_db";a:6:{s:8:"roomtype";a:15:{s:5:"title";s:12:"预订房间类型";s:10:"field_name";s:8:"roomtype";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:5:"radio";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:44:"标准双人房\r\n标准单人房\r\n豪华单人房\r\n总统套房";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:10;s:9:"allowview";N;}s:7:"roomnum";a:15:{s:5:"title";s:8:"预定间数";s:10:"field_name";s:7:"roomnum";s:10:"field_type";s:3:"int";s:10:"field_leng";i:3;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"2";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"间";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:9;s:9:"allowview";N;}s:6:"numday";a:15:{s:5:"title";s:8:"入住几晚";s:10:"field_name";s:6:"numday";s:10:"field_type";s:3:"int";s:10:"field_leng";i:3;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:1:"2";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:2:"晚";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"0";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:8;s:9:"allowview";N;}s:8:"intotime";a:15:{s:5:"title";s:8:"入住时间";s:10:"field_name";s:8:"intotime";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"time";s:15:"field_inputleng";s:0:"";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:7;s:9:"allowview";N;}s:8:"truename";a:15:{s:5:"title";s:8:"顾客姓名";s:10:"field_name";s:8:"truename";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:30;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"12";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"1";s:6:"search";s:1:"0";s:9:"orderlist";i:6;s:9:"allowview";N;}s:9:"telephone";a:15:{s:5:"title";s:8:"联系电话";s:10:"field_name";s:9:"telephone";s:10:"field_type";s:7:"varchar";s:10:"field_leng";i:20;s:9:"form_type";s:4:"text";s:15:"field_inputleng";s:2:"12";s:8:"form_set";s:0:"";s:10:"form_value";s:0:"";s:10:"form_units";s:0:"";s:10:"form_title";s:0:"";s:8:"mustfill";s:1:"1";s:8:"listshow";s:1:"0";s:6:"search";s:1:"0";s:9:"orderlist";i:5;s:9:"allowview";N;}}s:7:"is_html";a:1:{s:7:"content";s:4:"内容";}s:11:"listshow_db";a:3:{s:8:"roomtype";s:12:"预订房间类型";s:7:"roomnum";s:8:"预定间数";s:8:"truename";s:8:"顾客姓名";}}', '', 0, '', 0, 1, '审核', '');

# --------------------------------------------------------

#
# 表的结构 `qb_form_reply`
#

DROP TABLE IF EXISTS `qb_form_reply`;
CREATE TABLE `qb_form_reply` (
  `rid` mediumint(7) NOT NULL auto_increment,
  `id` mediumint(7) NOT NULL default '0',
  `mid` mediumint(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`rid`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;

#
# 导出表中的数据 `qb_form_reply`
#

INSERT INTO `qb_form_reply` VALUES (6, 25, 3, 1237255555, 1, 'admin', '<p><u>yyyyyy</u></p>\r\n<p><u>yyyyyyyy</u></p><strong>\r\n<hr width="100%" color=#98fb98 SIZE=1 />\r\n</strong>', '192.168.0.99');
INSERT INTO `qb_form_reply` VALUES (10, 27, 3, 1239591974, 1, 'admin', 'ffffffffffff ', '192.168.0.99');
