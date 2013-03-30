-- WINDSFORCE 数据库表
-- version 1.0
-- http://www.windsforce.com
--
-- 开发: Windsforce TEAM
-- 网站: http://www.windsforce.com

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `windsforce`
--

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_group`
--

DROP TABLE IF EXISTS `#@__group`;
CREATE TABLE `#@__group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_name` char(32) NOT NULL DEFAULT '' COMMENT '群组名字',
  `group_nikename` char(32) NOT NULL DEFAULT '' COMMENT '小组英文名称',
  `group_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '群组排序',
  `group_description` text NOT NULL COMMENT '小组介绍',
  `group_listdescription` varchar(300) NOT NULL COMMENT '列表小组介绍',
  `group_path` char(32) NOT NULL DEFAULT '' COMMENT '图标路径',
  `group_icon` char(32) DEFAULT NULL COMMENT '小组图标',
  `group_totaltodaynum` int(10) NOT NULL DEFAULT '0' COMMENT '今日发帖总计',
  `group_topicnum` int(10) NOT NULL DEFAULT '0' COMMENT '帖子统计',
  `group_topictodaynum` int(10) NOT NULL DEFAULT '0' COMMENT '统计今天发帖',
  `group_usernum` int(10) NOT NULL DEFAULT '0' COMMENT '小组成员数',
  `group_topiccomment` int(10) NOT NULL DEFAULT '0' COMMENT '回帖数量',
  `group_topiccommenttodaynum` int(10) NOT NULL DEFAULT '0' COMMENT '今日回帖数量',
  `group_joinway` tinyint(1) NOT NULL DEFAULT '0' COMMENT '加入方式',
  `group_roleleader` char(32) NOT NULL DEFAULT '组长' COMMENT '组长角色名称',
  `group_roleadmin` char(32) NOT NULL DEFAULT '管理员' COMMENT '管理员角色名称',
  `group_roleuser` char(32) NOT NULL DEFAULT '成员' COMMENT '成员角色名称',
  `create_dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  `group_isrecommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `group_isopen` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否公开或者私密',
  `group_isaudit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核',
  `group_ispost` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许会员发帖',
  `group_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示,状态',
  `group_latestcomment` varchar(230) NOT NULL COMMENT '最近更新帖子',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  PRIMARY KEY (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `group_name` (`group_name`),
  KEY `group_sort` (`group_sort`),
  KEY `group_status` (`group_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupcategory`
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
  KEY `groupcategory_sort` (`groupcategory_sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupcategoryindex`
--

DROP TABLE IF EXISTS `#@__groupcategoryindex`;
CREATE TABLE `#@__groupcategoryindex` (
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `groupcategory_id` int(10) NOT NULL COMMENT '群组分类ID',
  PRIMARY KEY (`group_id`,`groupcategory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupfeed`
--

DROP TABLE IF EXISTS `#@__groupfeed`;
CREATE TABLE IF NOT EXISTS `#@__groupfeed` (
  `groupfeed_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupfeed_username` varchar(50) NOT NULL COMMENT '用户名',
  `groupfeed_template` text NOT NULL COMMENT '动态模板',
  `groupfeed_data` text NOT NULL COMMENT '动态数据',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`groupfeed_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupoption`
--

DROP TABLE IF EXISTS `#@__groupoption`;
CREATE TABLE `#@__groupoption` (
  `groupoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `groupoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`groupoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopic`
--

DROP TABLE IF EXISTS `#@__grouptopic`;
CREATE TABLE IF NOT EXISTS `#@__grouptopic` (
  `grouptopic_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主题ID',
  `grouptopiccategory_id` int(10) NOT NULL DEFAULT '0' COMMENT '帖子分类ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '发布帖子用户ID',
  `grouptopic_username` varchar(50) NOT NULL COMMENT '发布帖子用户名',
  `grouptopic_title` varchar(300) NOT NULL DEFAULT '' COMMENT '帖子标题',
  `grouptopic_content` text NOT NULL COMMENT '帖子内容',
  `grouptopic_comments` int(10) NOT NULL DEFAULT '0' COMMENT '帖子回复统计',
  `grouptopic_views` int(10) NOT NULL DEFAULT '0' COMMENT '帖子浏览数',
  `grouptopic_loves` int(10) NOT NULL DEFAULT '0' COMMENT '帖子喜欢数',
  `grouptopic_sticktopic` tinyint(1) NOT NULL DEFAULT '0' COMMENT '帖子是否置顶',
  `grouptopic_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '帖子是否显示',
  `grouptopic_isclose` int(1) NOT NULL DEFAULT '0' COMMENT '帖子是否关闭帖子',
  `grouptopic_color` char(7) NOT NULL DEFAULT '' COMMENT '帖子高亮颜色',
  `grouptopic_iscomment` tinyint(1) NOT NULL DEFAULT '0' COMMENT '帖子是否允许评论',
  `grouptopic_addtodigest` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否精华帖子',
  `grouptopic_isaudit` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0待审核1审核通过',
  `grouptopic_allownoticeauthor` tinyint(1) NOT NULL DEFAULT '0' COMMENT '接收回复通知',
  `grouptopic_ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '回帖倒序排列',
  `grouptopic_isanonymous` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用匿名发帖',
  `grouptopic_usesig` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用签名',
  `grouptopic_smileoff` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁用表情',
  `grouptopic_parseurloff` tinyint(1) NOT NULL DEFAULT '0' COMMENT '禁用链接识别',
  `grouptopic_hiddenreplies` tinyint(1) NOT NULL DEFAULT '0' COMMENT '使用匿名发帖',
  `grouptopic_readperm` tinyint(3) NOT NULL COMMENT '阅读权限',
  `grouptopic_price` tinyint(4) NOT NULL DEFAULT '0' COMMENT '帖子售价',
  `grouptopic_latestcomment` varchar(120) NOT NULL COMMENT '最后回复',
  `grouptopic_updateusername` varchar(50) NOT NULL COMMENT '最后更新用户',
  `create_dateline` int(10) DEFAULT '0' COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `grouptopic_thumb` int(10) NOT NULL DEFAULT '0' COMMENT '缩略图',
  PRIMARY KEY (`grouptopic_id`),
  KEY `grounptopiccategory_id` (`grouptopiccategory_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `grouptopic_status` (`grouptopic_status`),
  KEY `create_dateline` (`create_dateline`),
  KEY `grouptopic_isposts` (`grouptopic_addtodigest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopiccategory`
--

DROP TABLE IF EXISTS `#@__grouptopiccategory`;
CREATE TABLE IF NOT EXISTS `#@__grouptopiccategory` (
  `grouptopiccategory_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帖子分类ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '小组ID',
  `grouptopiccategory_name` char(32) NOT NULL DEFAULT '' COMMENT '帖子分类名称',
  `grouptopiccategory_topicnum` int(10) NOT NULL DEFAULT '0' COMMENT '统计帖子',
  `grouptopiccategory_sort` smallint(6) NOT NULL COMMENT '帖子分类排序',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`grouptopiccategory_id`),
  KEY `group_id` (`group_id`),
  KEY `grouptopiccategory_sort` (`grouptopiccategory_sort`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopiccomment`
--

DROP TABLE IF EXISTS `#@__grouptopiccomment`;
CREATE TABLE `#@__grouptopiccomment` (
  `grouptopiccomment_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `grouptopic_id` int(10) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `grouptopiccomment_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `grouptopiccomment_name` varchar(50) NOT NULL COMMENT '评论名字',
  `grouptopiccomment_content` text NOT NULL COMMENT '回复内容',
  `grouptopiccomment_email` varchar(300) NOT NULL COMMENT '邮件',
  `grouptopiccomment_url` varchar(300) NOT NULL COMMENT '主页',
  `grouptopiccoment_ip` varchar(16) NOT NULL COMMENT '评论IP',
  `create_dateline` int(10) DEFAULT '0' COMMENT '回复时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`grouptopiccomment_id`),
  KEY `user_id` (`user_id`),
  KEY `grouptopic_id` (`grouptopic_id`),
  KEY `grouptopiccoment_status` (`grouptopiccomment_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopictag`
--

DROP TABLE IF EXISTS `#@__grouptopictag`;
CREATE TABLE `#@__grouptopictag` (
  `grouptopictag_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帖子标签',
  `grouptopictag_name` char(32) NOT NULL DEFAULT '' COMMENT '标签名字',
  `grouptopictag_count` int(10) NOT NULL DEFAULT '0' COMMENT '标签字体数量',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`grouptopictag_id`),
  KEY `grouptopictag_name` (`grouptopictag_name`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopictagindex`
--

DROP TABLE IF EXISTS `#@__grouptopictagindex`;
CREATE TABLE `#@__grouptopictagindex` (
  `grouptopic_id` int(10) NOT NULL DEFAULT '0' COMMENT '帖子ID',
  `grouptopictag_id` int(10) NOT NULL DEFAULT '0' COMMENT '标签ID',
  PRIMARY KEY (`grouptopic_id`,`grouptopictag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupuser`
--

DROP TABLE IF EXISTS `#@__groupuser`;
CREATE TABLE `#@__groupuser` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `groupuser_isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
