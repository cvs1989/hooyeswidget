INSERT INTO `qb_hack` (`keywords`, `name`, `isclose`, `author`, `config`, `htmlcode`, `hackfile`, `hacksqltable`, `adminurl`, `about`, `class1`, `class2`, `list`, `linkname`, `isbiz`) VALUES ('template_list', '模板设置', 0, '', '', '', '', '', 'index.php?lfj=template&job=list', '', 'other', '风格/模板设置', 1, '', 0);


DROP TABLE IF EXISTS `qb_template_bak`;
CREATE TABLE `qb_template_bak` (
  `bid` int(7) NOT NULL auto_increment,
  `id` int(7) NOT NULL default '0',
  `posttime` int(10) NOT NULL default '0',
  `code` text NOT NULL,
  PRIMARY KEY  (`bid`),
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

