INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`) VALUES (18, 2, '留言本', 'guestbook_', 'guestbook', '', '', '', 0, '', '', 0);


# --------------------------------------------------------

#
# 表的结构 `qb_guestbook_config`
#

DROP TABLE IF EXISTS `qb_guestbook_config`;
CREATE TABLE `qb_guestbook_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) TYPE=MyISAM;

#
# 导出表中的数据 `qb_guestbook_config`
#

INSERT INTO `qb_guestbook_config` VALUES ('module_id', '18', '');
INSERT INTO `qb_guestbook_config` VALUES ('GuestBookNum', '20', '');
INSERT INTO `qb_guestbook_config` VALUES ('groupPassPassGuestBook', '3,4', '');
INSERT INTO `qb_guestbook_config` VALUES ('viewNoPassGuestBook', '0', '');
INSERT INTO `qb_guestbook_config` VALUES ('yzImgGuestBook', '1', '');
INSERT INTO `qb_guestbook_config` VALUES ('module_pre', 'guestbook_', '');
INSERT INTO `qb_guestbook_config` VALUES ('ifOpenGuestBook', '1', '');
INSERT INTO `qb_guestbook_config` VALUES ('Info_webname', '留言本', '');
INSERT INTO `qb_guestbook_config` VALUES ('Info_webOpen', '1', '');

# --------------------------------------------------------

#
# 表的结构 `qb_guestbook_content`
#

DROP TABLE IF EXISTS `qb_guestbook_content`;
CREATE TABLE `qb_guestbook_content` (
  `id` int(7) NOT NULL auto_increment,
  `fid` mediumint(7) NOT NULL default '0',
  `ico` tinyint(2) NOT NULL default '0',
  `email` varchar(50) NOT NULL default '',
  `oicq` varchar(11) default NULL,
  `weburl` varchar(150) NOT NULL default '',
  `blogurl` varchar(150) NOT NULL default '',
  `uid` int(7) NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `content` text NOT NULL,
  `yz` tinyint(1) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `reply` text NOT NULL,
  `mobphone` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=24 ;

#
# 导出表中的数据 `qb_guestbook_content`
#

INSERT INTO `qb_guestbook_content` VALUES (12, 0, 1, '', '', '', '', 1, 'admin', '192.168.0.99', '忘记密码强制进入网站后台的方法是:修改/data/global.php文件,查找$ForceEnter=0;把0改成1即可,强制进入网站的后台.', 1, 1240206881, 1240206881, '', '');
INSERT INTO `qb_guestbook_content` VALUES (13, 0, 1, '', '', '', '', 1, 'admin', '192.168.0.99', '普通管理员成为超级管理员的方法是,修改文件/data/admin.php,把里边的帐号更换一下即可', 1, 1240206958, 1240206958, '', '');
INSERT INTO `qb_guestbook_content` VALUES (14, 0, 1, '', '', '', '', 1, 'admin', '192.168.0.99', '整站系统的数据库配置文件是/data/mysql_config.php', 1, 1240207079, 1240207079, '', '');
INSERT INTO `qb_guestbook_content` VALUES (15, 0, 1, '', '', '', '', 1, 'admin', '192.168.0.99', '服务器默认限制上传文件大小为2M,你如果不修改服务器设置.而想在整站系统上传大于2M的文件.是不可以的.必须先修改服务器设置.一般来说服务器的PHP配置文件放在c:\\windows\\php.ini这里.', 1, 1240207216, 1240207216, '', '');
INSERT INTO `qb_guestbook_content` VALUES (16, 0, 1, '', '', '', '', 1, 'admin', '192.168.0.99', '如果服务器做了限制.就无法使用采集程序.', 1, 1240207330, 1240207330, '', '');

# --------------------------------------------------------

#
# 表的结构 `qb_guestbook_sort`
#

DROP TABLE IF EXISTS `qb_guestbook_sort`;
CREATE TABLE `qb_guestbook_sort` (
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
  `ifcolor` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`fid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

#
# 导出表中的数据 `qb_guestbook_sort`
#

INSERT INTO `qb_guestbook_sort` VALUES (1, 0, '发展建议', 0, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 0, '', '', '', '', 0, 'b:0;', 0, 0, '', '', 0);
INSERT INTO `qb_guestbook_sort` VALUES (2, 0, '意见投诉', 0, 1, 0, 0, '', 0, 0, '', '', '', '', '', '', 0, '', '', '', 1, '', '', '', '', 0, '', 0, 0, '', '', 0);
