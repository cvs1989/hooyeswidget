INSERT INTO `qb_module` (`id`, `type`, `name`, `pre`, `dirname`, `domain`, `admindir`, `config`, `list`, `admingroup`, `adminmember`, `ifclose`) VALUES (23, 2, '投票系统', 'vote_', 'vote', '', '', '', 0, '', '', 0);


# --------------------------------------------------------

#
# 表的结构 `qb_vote_comment`
#

DROP TABLE IF EXISTS `qb_vote_comment`;
CREATE TABLE `qb_vote_comment` (
  `id` mediumint(7) unsigned NOT NULL auto_increment,
  `cid` mediumint(7) unsigned NOT NULL default '0',
  `vid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) unsigned NOT NULL default '0',
  `username` varchar(30) NOT NULL default '',
  `posttime` int(10) NOT NULL default '0',
  `content` text NOT NULL,
  `ip` varchar(15) NOT NULL default '',
  `icon` tinyint(3) NOT NULL default '0',
  `yz` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `aid` (`cid`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=23 ;

#
# 导出表中的数据 `qb_vote_comment`
#

INSERT INTO `qb_vote_comment` VALUES (11, 10, 0, 1, 'admin', 1237279209, 'rrrrrrrrrrrrrrr', '192.168.0.99', 0, 1);
INSERT INTO `qb_vote_comment` VALUES (12, 10, 0, 1, 'admin', 1237279223, 'dddddddddddddddd', '192.168.0.99', 0, 1);
INSERT INTO `qb_vote_comment` VALUES (14, 6, 0, 1, 'admin', 1239025849, '&nbsp;证&nbsp;码:', '127.0.0.1', 0, 1);
INSERT INTO `qb_vote_comment` VALUES (22, 6, 0, 1, 'admin', 1283825218, 'fdsadf', '127.0.0.1', 0, 1);
INSERT INTO `qb_vote_comment` VALUES (19, 11, 0, 1, 'admin', 1240210890, '不错呀', '192.168.0.99', 0, 1);
INSERT INTO `qb_vote_comment` VALUES (20, 11, 0, 1, 'admin', 1255082863, 'fdsa', '127.0.0.1', 0, 1);
INSERT INTO `qb_vote_comment` VALUES (21, 11, 0, 1, 'admin', 1283823884, 'll', '127.0.0.1', 0, 1);

# --------------------------------------------------------

#
# 表的结构 `qb_vote_config`
#

DROP TABLE IF EXISTS `qb_vote_config`;
CREATE TABLE `qb_vote_config` (
  `c_key` varchar(50) NOT NULL default '',
  `c_value` text NOT NULL,
  `c_descrip` text NOT NULL,
  PRIMARY KEY  (`c_key`)
) TYPE=MyISAM;

#
# 导出表中的数据 `qb_vote_config`
#

INSERT INTO `qb_vote_config` VALUES ('module_id', '23', '');
INSERT INTO `qb_vote_config` VALUES ('Info_webOpen', '1', '');
INSERT INTO `qb_vote_config` VALUES ('Info_webname', '投票系统', '');
INSERT INTO `qb_vote_config` VALUES ('module_pre', 'vote_', '');

# --------------------------------------------------------

#
# 表的结构 `qb_vote_element`
#

DROP TABLE IF EXISTS `qb_vote_element`;
CREATE TABLE `qb_vote_element` (
  `id` int(7) NOT NULL auto_increment,
  `cid` int(7) NOT NULL default '0',
  `title` varchar(50) NOT NULL default '',
  `votenum` int(7) NOT NULL default '0',
  `list` int(10) NOT NULL default '0',
  `img` varchar(100) NOT NULL default '',
  `describes` varchar(255) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=88 ;

#
# 导出表中的数据 `qb_vote_element`
#

INSERT INTO `qb_vote_element` VALUES (37, 6, '熊晓鸽', 4, 10, '', '', '');
INSERT INTO `qb_vote_element` VALUES (38, 6, '马化腾', 4, 7, '', '', '');
INSERT INTO `qb_vote_element` VALUES (39, 6, '马云', 2, 5, '', '', '');
INSERT INTO `qb_vote_element` VALUES (41, 6, '李彦宏', 6, 6, '', '', '');
INSERT INTO `qb_vote_element` VALUES (81, 11, '阿里妈妈和淘宝合并', 0, 4, 'vote/1_20090418220434_DSazk.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (80, 11, 'Chinaz之CNIDC主机网上线', 0, 9, 'vote/1_20090419090435_51j39.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (68, 6, '丁磊', 12, 9, '', '', '');
INSERT INTO `qb_vote_element` VALUES (70, 10, '百度粉丝团', 6, 0, 'vote/1_20090317160304_1cyPh.gif', '我是百度粉丝团', 'http://www.baidu.com');
INSERT INTO `qb_vote_element` VALUES (71, 10, '谷歌粉丝团', 2, 0, 'vote/1_20090317160317_NO50S.gif', '我是谷歌粉丝团', 'http://www.google.cn');
INSERT INTO `qb_vote_element` VALUES (72, 11, '博客已死，SNS当立', 1, 8, 'vote/1_20090419090425_nkqeB.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (73, 11, 'CN域名白菜到猪肉', 2, 10, 'vote/1_20090419090455_L5Iz8.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (74, 11, '九九音乐网倒闭', 1, 3, 'vote/1_20090419090414_BVe9o.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (75, 11, '番茄花园被告', 2, 6, 'vote/1_20090419090445_qQiaW.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (76, 11, '红火的全国站长大会', 2, 5, 'vote/1_20090419090445_QVf6M.jpg', '', 'http://www.admin5.com/article/20081231/124093.shtml');
INSERT INTO `qb_vote_element` VALUES (77, 11, '丁磊养猪', 0, 7, 'vote/1_20090419090459_1MkWx.jpg', '', 'http://bbs.chinaz.com/Shuiba/thread-1240750-1-1.html');
INSERT INTO `qb_vote_element` VALUES (82, 12, '中电云集', 11, 0, 'vote/1_20101109161100_eooqn.jpg', '', 'http://www.chinaccnet.com/');
INSERT INTO `qb_vote_element` VALUES (83, 12, '华夏名网', 11, 0, 'vote/1_20101109161103_nulvn.jpg', '', 'http://www.sudu.cn/');
INSERT INTO `qb_vote_element` VALUES (84, 12, '群英网络', 11, 0, 'vote/1_20101109161108_q5bre.jpg', '', 'http://www.qy.com.cn/');
INSERT INTO `qb_vote_element` VALUES (85, 12, '371数据', 12, 0, 'vote/1_20101109161112_omvjo.jpg', '', 'http://www.371.com/');
INSERT INTO `qb_vote_element` VALUES (86, 12, '中网互联', 11, 0, 'vote/1_20101109161116_zlkqh.jpg', '', 'http://www.zwidc.com/');
INSERT INTO `qb_vote_element` VALUES (87, 12, '星辉互联', 11, 0, 'vote/1_20101109161120_ylaj7.jpg', '', 'http://www.eydns.com/');

# --------------------------------------------------------

#
# 表的结构 `qb_vote_topic`
#

DROP TABLE IF EXISTS `qb_vote_topic`;
CREATE TABLE `qb_vote_topic` (
  `cid` int(7) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `about` text NOT NULL,
  `type` tinyint(4) NOT NULL default '0',
  `limittime` int(10) NOT NULL default '0',
  `limitip` tinyint(1) NOT NULL default '0',
  `ip` text NOT NULL,
  `posttime` int(10) NOT NULL default '0',
  `user` text NOT NULL,
  `begintime` int(10) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `forbidguestvote` tinyint(1) NOT NULL default '0',
  `ifcomment` tinyint(1) NOT NULL default '0',
  `tplcode` text NOT NULL,
  `votetype` tinyint(2) NOT NULL default '0',
  `aid` mediumint(7) NOT NULL default '0',
  `uid` mediumint(7) NOT NULL default '0',
  PRIMARY KEY  (`cid`)
) TYPE=MyISAM AUTO_INCREMENT=13 ;

#
# 导出表中的数据 `qb_vote_topic`
#

INSERT INTO `qb_vote_topic` VALUES (6, '互联网哪些前辈是你支持的', '互联网哪些前辈是你支持和影响到你的？', 2, 600, 0, '', 1164793927, '', 1233749543, 1265256743, 0, 1, '<div class="voteid" title="$describes">{$button}{$title}({$votenum})</div>', 0, 0, 0);
INSERT INTO `qb_vote_topic` VALUES (11, '2008年中国站长十大热门事件投票', '-------请为你觉得2008年最热门的站长事件投上一票.', 2, 500, 0, '', 1237281523, '', 1233749543, 1580789543, 0, 1, '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:118px;float:left;margin-top:4px;" class="voteid">\r\n  <tr> \r\n    <td align="center"><A HREF="$url" target="_blank" style="border:1px solid #ccc;display:block;width:100px;height:75px;"><img alt="{$title}" style="border:1px solid #fff;" src="$img" border="0" width="100" height="75"></A></td>\r\n  </tr>\r\n  <tr> \r\n    <td align="center">\r\n      <div  style="width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{$button}(<b><font color="#FF0000" >{$votenum}</font></b>)<a HREF="$url" target="_blank" title="{$title}">{$title}</a></div>\r\n    </td>\r\n  </tr>\r\n  <tr> \r\n    <td>{$describes}</td>\r\n  </tr>\r\n  <tr> \r\n    <td></td>\r\n  </tr>\r\n</table>\r\n\r\n', 1, 0, 0);
INSERT INTO `qb_vote_topic` VALUES (10, '哪个搜索引擎好?', '你喜欢使用哪个搜索引擎呢,请投上一票?', 1, 15, 0, '', 1237275830, '', 0, 0, 0, 1, '<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width:90px;float:left;">\r\n  <tr> \r\n    <td align="center" valign="middle" style="line-height:40px;"> <a href="$url" target=_blank> \r\n      <b>$title</b></a> </td>\r\n  </tr>\r\n  <tr> \r\n    <td align="center"><a href="$url" target="_blank"><img alt="$describes" src="$img" width="80" height="30" border="0"></a></td>\r\n  </tr>\r\n  <tr> \r\n    <td align="center" style="line-height:20px;"> <font color="#990000">共 <b><font color="#FF0000">$votenum</font> 票 \r\n      </b></font></td>\r\n  </tr>\r\n  <tr> \r\n    <td align="center" style="line-height:40px;"><a href="$webdb[www_url]/do/vote.php?action=vote&voteId=$id" target="_blank"><u>投一票</u></a> \r\n      <a href="$webdb[www_url]/do/vote.php?job=show&cid=$cid#postcomment" target="_blank"><u>评一评</u></a></td>\r\n  </tr>\r\n</table>', 2, 0, 0);
INSERT INTO `qb_vote_topic` VALUES (12, '请为你最喜欢的IDC投上宝贵的一票', '', 2, 0, 0, '', 1289292937, '', 0, 0, 0, 0, '<div class="listVote" title="{$describes}">\r\n                    	<div class="img"><A HREF="$url" target="_blank"><img src="$img" width="120" height="60"/></A></div>\r\n                        <div class="word">{$button} (<span>{$votenum}</span>)$title</div>\r\n                    </div>', 1, 0, 0);
