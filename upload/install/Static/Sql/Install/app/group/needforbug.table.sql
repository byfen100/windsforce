-- WINDSFORCE 数据库表
-- version 1.0
-- http://www.doyouhaobaby.net
--
-- 开发: DianniuTeam
-- 网站: http://dianniu.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `needforbug`
--

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_group`
--

DROP TABLE IF EXISTS `#@__group`;
CREATE TABLE `#@__group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_name` char(32) NOT NULL DEFAULT '' COMMENT '群组名字',
  `group_nikename` char(32) NOT NULL DEFAULT '' COMMENT '小组英文名称',
  `group_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '群组排序',
  `group_description` text NOT NULL COMMENT '小组介绍',
  `group_path` char(32) NOT NULL DEFAULT '' COMMENT '图标路径',
  `group_icon` char(32) DEFAULT NULL COMMENT '小组图标',
  `group_topicnum` int(10) NOT NULL DEFAULT '0' COMMENT '帖子统计',
  `group_topictodaynum` int(10) NOT NULL DEFAULT '0' COMMENT '统计今天发帖',
  `group_usernum` int(10) NOT NULL DEFAULT '0' COMMENT '小组成员数',
  `group_joinway` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式',
  `group_roleleader` char(32) NOT NULL DEFAULT '组长' COMMENT '组长角色名称',
  `group_roleadmin` char(32) NOT NULL DEFAULT '管理员' COMMENT '管理员角色名称',
  `group_roleuser` char(32) NOT NULL DEFAULT '成员' COMMENT '成员角色名称',
  `create_dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  `group_isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `group_isopen` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否公开或者私密',
  `group_isaudit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `group_ispost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许会员发帖',
  `group_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示,状态',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `group_name` (`group_name`),
  KEY `group_sort` (`group_sort`),
  KEY `group_status` (`group_status`),
  KEY `create_dateline` (`create_dateline`),
  KEY `update_dateline` (`update_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupcategory`
--

DROP TABLE IF EXISTS `#@__groupcategory`;
CREATE TABLE `#@__groupcategory` (
  `groupcategory_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '群组分类ID',
  `groupcategory_name` char(32) NOT NULL DEFAULT '' COMMENT '群组分类名字',
  `groupcategory_parentid` int(10) NOT NULL DEFAULT '0' COMMENT '群组上级分类ID',
  `groupcategory_count` int(10) NOT NULL DEFAULT '0' COMMENT '群组个数',
  `groupcategory_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '群组分类排序名字',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `create_dateline` int(10) NOT NULL COMMENT '群组创建时间',
  PRIMARY KEY (`groupcategory_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `groupcategory_parentid` (`groupcategory_parentid`),
  KEY `groupcategory_sort` (`groupcategory_sort`),
  KEY `update_dateline` (`update_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupcategoryindex`
--

DROP TABLE IF EXISTS `#@__groupcategoryindex`;
CREATE TABLE `#@__groupcategoryindex` (
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `groupcategory_id` int(10) NOT NULL COMMENT '群组分类ID',
  PRIMARY KEY (`group_id`,`groupcategory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupoption`
--

DROP TABLE IF EXISTS `#@__groupoption`;
CREATE TABLE `#@__groupoption` (
  `groupoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `groupoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`groupoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_grouptopiccategory`
--

DROP TABLE IF EXISTS `#@__grouptopiccategory`;
CREATE TABLE `#@__grouptopiccategory` (
  `grouptopiccategory_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帖子分类ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `grouptopiccategory_name` char(32) NOT NULL DEFAULT '' COMMENT '帖子分类名称',
  `grouptopiccategory_topicnum` int(10) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  `grouptopiccategory_sort` smallint(6) NOT NULL COMMENT '帖子分类排序',
  PRIMARY KEY (`grouptopiccategory_id`),
  KEY `group_id` (`group_id`),
  KEY `grouptopiccategory_sort` (`grouptopiccategory_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupuser`
--

DROP TABLE IF EXISTS `#@__groupuser`;
CREATE TABLE `#@__groupuser` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `groupuser_isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
