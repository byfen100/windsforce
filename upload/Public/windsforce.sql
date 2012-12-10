-- phpMyAdmin SQL Dump
-- version 3.5.0
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 10 日 15:58
-- 服务器版本: 5.5.22
-- PHP 版本: 5.4.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `windsforce`
--

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_access`
--

CREATE TABLE IF NOT EXISTS `windsforce_access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点ID',
  `access_level` tinyint(1) NOT NULL COMMENT '级别，1（应用），2（模块），3（方法）',
  `access_parentid` smallint(6) NOT NULL COMMENT '父级ID',
  `access_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  KEY `group_id` (`role_id`),
  KEY `node_id` (`node_id`),
  KEY `access_status` (`access_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_adminctrlmenu`
--

CREATE TABLE IF NOT EXISTS `windsforce_adminctrlmenu` (
  `adminctrlmenu_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '后台菜单ID',
  `adminctrlmenu_internal` tinyint(1) NOT NULL DEFAULT '0' COMMENT '快捷菜单类型，0自定义，1内置',
  `adminctrlmenu_title` varchar(50) NOT NULL COMMENT '后台菜单标题',
  `adminctrlmenu_url` varchar(255) NOT NULL COMMENT '后台菜单网址',
  `adminctrlmenu_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '后台菜单状态',
  `adminctrlmenu_sort` tinyint(3) NOT NULL COMMENT '后台菜单排序',
  `adminctrlmenu_clicknum` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '后台菜单点击量',
  `user_id` int(10) unsigned NOT NULL COMMENT '后台菜单操作人',
  `adminctrlmenu_admin` varchar(50) NOT NULL COMMENT '操作人用户名',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '后台菜单创建时间',
  PRIMARY KEY (`adminctrlmenu_id`),
  KEY `adminctrlmenu_status` (`adminctrlmenu_status`),
  KEY `adminctrlmenu_sort` (`adminctrlmenu_sort`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_adminlog`
--

CREATE TABLE IF NOT EXISTS `windsforce_adminlog` (
  `adminlog_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '后台管理ID',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '日志所记录的操作者ID',
  `adminlog_username` varchar(50) NOT NULL COMMENT '后台管理记录用户名',
  `adminlog_info` varchar(255) NOT NULL DEFAULT '' COMMENT '管理操作内容',
  `adminlog_ip` varchar(40) NOT NULL DEFAULT '' COMMENT '登录者登录IP',
  PRIMARY KEY (`adminlog_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_adminmessage`
--

CREATE TABLE IF NOT EXISTS `windsforce_adminmessage` (
  `adminlog_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '短消息ID',
  `user_id` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '短消息发送者',
  `adminmessage_username` varchar(50) NOT NULL COMMENT '信息发送者用户名',
  `adminlog_receiverid` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '短消息接受者',
  `adminmessage_receiverusername` varchar(50) NOT NULL COMMENT '管理员消息接受者用户名',
  `create_dateline` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `adminlog_readtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '短消息阅读时间',
  `adminlog_readed` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '短消息是否已经阅读',
  `adminlog_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '短消息状态',
  `adminlog_title` varchar(150) NOT NULL DEFAULT '' COMMENT '后台短消息标题',
  `adminlog_message` text NOT NULL COMMENT '后台短消息内容',
  PRIMARY KEY (`adminlog_id`),
  KEY `user_id` (`user_id`),
  KEY `adminlog_receiverid` (`adminlog_receiverid`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_app`
--

CREATE TABLE IF NOT EXISTS `windsforce_app` (
  `app_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '应用ID',
  `app_identifier` varchar(32) NOT NULL COMMENT '应用唯一识别符',
  `app_name` varchar(32) NOT NULL COMMENT '应用名字',
  `app_version` varchar(20) NOT NULL COMMENT '应用版本',
  `app_description` varchar(255) NOT NULL COMMENT '应用描述',
  `app_url` varchar(255) NOT NULL COMMENT '应用官方网站',
  `app_email` varchar(255) NOT NULL COMMENT '应用邮件',
  `app_author` varchar(32) NOT NULL COMMENT '应用作者',
  `app_authorurl` varchar(255) NOT NULL COMMENT '应用作者主页',
  `app_isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用是否需要管理项',
  `app_isinstall` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用是否需要安装',
  `app_isuninstall` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用是否需要卸载',
  `app_issystem` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用是否为系统核心',
  `app_isappnav` tinyint(1) NOT NULL DEFAULT '0' COMMENT '应用是否需要写入前台菜单',
  `app_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用应用',
  PRIMARY KEY (`app_id`),
  UNIQUE KEY `app_identifier` (`app_identifier`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `windsforce_app`
--

INSERT INTO `windsforce_app` (`app_id`, `app_identifier`, `app_name`, `app_version`, `app_description`, `app_url`, `app_email`, `app_author`, `app_authorurl`, `app_isadmin`, `app_isinstall`, `app_isuninstall`, `app_issystem`, `app_isappnav`, `app_status`) VALUES
(1, 'home', '个人空间', '1.0', '个人空间应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', 'Dianniu Team', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1),
(2, 'group', '小组', '1.0', '群组应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', 'Dianniu Team', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1),
(3, 'wap', 'Wap手机', '1.0', '手机应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', 'Dianniu Team', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_appeal`
--

CREATE TABLE IF NOT EXISTS `windsforce_appeal` (
  `appeal_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '申诉ID',
  `user_id` int(10) NOT NULL COMMENT '申诉用户ID',
  `appeal_realname` varchar(50) NOT NULL COMMENT '申诉真实姓名',
  `appeal_address` varchar(300) NOT NULL COMMENT '申诉详细地址',
  `appeal_idnumber` varchar(32) NOT NULL COMMENT '申诉身份证号码',
  `appeal_email` varchar(150) NOT NULL COMMENT '申诉邮件地址',
  `appeal_receiptnumber` varchar(50) NOT NULL COMMENT '申诉回执号码',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `appeal_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '申诉状态',
  `appeal_progress` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申诉进度',
  `appeal_reason` text NOT NULL COMMENT '驳回理由',
  PRIMARY KEY (`appeal_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_attachment`
--

CREATE TABLE IF NOT EXISTS `windsforce_attachment` (
  `attachment_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `attachment_name` varchar(100) NOT NULL COMMENT '名字',
  `attachment_type` varchar(40) NOT NULL COMMENT '类型',
  `attachment_size` int(8) NOT NULL COMMENT '大小，单位KB',
  `attachment_key` varchar(25) NOT NULL COMMENT '上传KEY',
  `attachment_extension` varchar(20) NOT NULL COMMENT '后缀',
  `attachment_savepath` varchar(50) NOT NULL COMMENT '保存路径',
  `attachment_savename` char(50) NOT NULL COMMENT '保存名字',
  `attachment_hash` varchar(50) NOT NULL COMMENT 'HASH',
  `attachment_module` varchar(30) NOT NULL COMMENT '上传模块',
  `attachment_isthumb` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否存在缩略图',
  `attachment_thumbprefix` varchar(25) NOT NULL COMMENT '缩略图前缀',
  `attachment_thumbpath` varchar(32) NOT NULL COMMENT '缩略图路径',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `attachmentcategory_id` mediumint(8) NOT NULL COMMENT '分类ID',
  `attachment_description` varchar(500) NOT NULL COMMENT '描述',
  `attachment_alt` varchar(100) NOT NULL,
  `attachment_download` int(10) NOT NULL COMMENT '下载次数',
  `attachment_commentnum` mediumint(8) NOT NULL DEFAULT '0' COMMENT '评论数量',
  `attachment_islock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否锁定',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `attachment_username` varchar(50) NOT NULL COMMENT '用户名',
  `attachment_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  PRIMARY KEY (`attachment_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `attachment_recommend` (`attachment_recommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_attachmentcategory`
--

CREATE TABLE IF NOT EXISTS `windsforce_attachmentcategory` (
  `attachmentcategory_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '附件分类ID',
  `attachmentcategory_name` varchar(50) NOT NULL COMMENT '分类名字',
  `attachmentcategory_cover` int(10) NOT NULL DEFAULT '0' COMMENT '分类封面，可以为一个文章的图片地址或者附件库中一个图片附件的ID',
  `attachmentcategory_compositor` smallint(8) NOT NULL DEFAULT '0' COMMENT '排序',
  `attachmentcategory_description` varchar(500) NOT NULL COMMENT '专辑描述',
  `attachmentcategory_attachmentnum` int(10) NOT NULL DEFAULT '0' COMMENT '专辑中附件数量',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户',
  `attachmentcategory_username` varchar(50) NOT NULL COMMENT '用户名',
  `attachmentcategory_recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  PRIMARY KEY (`attachmentcategory_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `user_id` (`user_id`),
  KEY `attachmentcategory_compositor` (`attachmentcategory_compositor`),
  KEY `attachmentcategory_recommend` (`attachmentcategory_recommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_badword`
--

CREATE TABLE IF NOT EXISTS `windsforce_badword` (
  `badword_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '词语替换ID',
  `badword_admin` varchar(50) NOT NULL DEFAULT '' COMMENT '添加词语过滤用户',
  `badword_find` varchar(300) NOT NULL DEFAULT '' COMMENT '待查找的过滤词语',
  `badword_replacement` varchar(300) NOT NULL DEFAULT '' COMMENT '待替换的过滤词语',
  `badword_findpattern` varchar(300) NOT NULL DEFAULT '' COMMENT '查找的正则表达式',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`badword_id`),
  UNIQUE KEY `find` (`badword_find`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_creditrule`
--

CREATE TABLE IF NOT EXISTS `windsforce_creditrule` (
  `creditrule_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '积分规则ID',
  `creditrule_name` varchar(20) NOT NULL DEFAULT '' COMMENT '积分规则名字',
  `creditrule_action` varchar(20) NOT NULL DEFAULT '' COMMENT '规则action唯一KEY',
  `creditrule_cycletype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '奖励周期0:一次;1:每天;2:整点;3:间隔分钟;4:不限',
  `creditrule_cycletime` int(10) NOT NULL DEFAULT '0' COMMENT '间隔时间',
  `creditrule_rewardnum` tinyint(2) NOT NULL DEFAULT '1' COMMENT '周期内最多奖励次数',
  `creditrule_extendcredit1` int(10) NOT NULL DEFAULT '0' COMMENT '第一种积分类型',
  `creditrule_extendcredit2` int(10) NOT NULL DEFAULT '0' COMMENT '第二种积分类型',
  `creditrule_extendcredit3` int(10) NOT NULL DEFAULT '0' COMMENT '第三种积分类型',
  `creditrule_extendcredit4` int(10) NOT NULL DEFAULT '0' COMMENT '第四种积分类型',
  `creditrule_extendcredit5` int(10) NOT NULL DEFAULT '0' COMMENT '第五种积分类型',
  `creditrule_extendcredit6` int(10) NOT NULL DEFAULT '0' COMMENT '第六种积分类型',
  `creditrule_extendcredit7` int(10) NOT NULL DEFAULT '0' COMMENT '第七种积分类型',
  `creditrule_extendcredit8` int(10) NOT NULL DEFAULT '0' COMMENT '第八种积分类型',
  PRIMARY KEY (`creditrule_id`),
  KEY `creditrule_action` (`creditrule_action`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- 转存表中的数据 `windsforce_creditrule`
--

INSERT INTO `windsforce_creditrule` (`creditrule_id`, `creditrule_name`, `creditrule_action`, `creditrule_cycletype`, `creditrule_cycletime`, `creditrule_rewardnum`, `creditrule_extendcredit1`, `creditrule_extendcredit2`, `creditrule_extendcredit3`, `creditrule_extendcredit4`, `creditrule_extendcredit5`, `creditrule_extendcredit6`, `creditrule_extendcredit7`, `creditrule_extendcredit8`) VALUES
(1, '发短消息', 'sendpm', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, '访问推广', 'promotion_visit', 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(3, '注册推广', 'promotion_register', 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0),
(4, '设置头像', 'setavatar', 0, 0, 1, 5, 0, 0, 0, 0, 0, 0, 0),
(5, '每天登录', 'daylogin', 1, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_creditrulelog`
--

CREATE TABLE IF NOT EXISTS `windsforce_creditrulelog` (
  `creditrulelog_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '策略日志ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '策略日志所有者uid',
  `creditrule_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '策略ID',
  `creditrulelog_total` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '策略被执行总次数',
  `creditrulelog_cyclenum` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '周期被执行次数',
  `creditrulelog_extendcredit1` int(10) NOT NULL DEFAULT '0' COMMENT '第一种积分类型',
  `creditrulelog_extendcredit2` int(10) NOT NULL DEFAULT '0' COMMENT '第二种积分类型',
  `creditrulelog_extendcredit3` int(10) NOT NULL DEFAULT '0' COMMENT '第三种积分类型',
  `creditrulelog_extendcredit4` int(10) NOT NULL DEFAULT '0' COMMENT '第四种积分类型',
  `creditrulelog_extendcredit5` int(10) NOT NULL DEFAULT '0' COMMENT '第五种积分类型',
  `creditrulelog_extendcredit6` int(10) NOT NULL DEFAULT '0' COMMENT '第六种积分类型',
  `creditrulelog_extendcredit7` int(10) NOT NULL DEFAULT '0' COMMENT '第七种积分类型',
  `creditrulelog_extendcredit8` int(10) NOT NULL DEFAULT '0' COMMENT '第八种积分类型',
  `creditrulelog_starttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '周期开始时间',
  `update_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '策略最后执行时间',
  PRIMARY KEY (`creditrulelog_id`),
  KEY `user_id` (`user_id`),
  KEY `creditrule_id` (`creditrule_id`),
  KEY `update_dateline` (`update_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- 转存表中的数据 `windsforce_creditrulelog`
--

INSERT INTO `windsforce_creditrulelog` (`creditrulelog_id`, `user_id`, `creditrule_id`, `creditrulelog_total`, `creditrulelog_cyclenum`, `creditrulelog_extendcredit1`, `creditrulelog_extendcredit2`, `creditrulelog_extendcredit3`, `creditrulelog_extendcredit4`, `creditrulelog_extendcredit5`, `creditrulelog_extendcredit6`, `creditrulelog_extendcredit7`, `creditrulelog_extendcredit8`, `creditrulelog_starttime`, `update_dateline`) VALUES
(38, 6, 5, 10, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1352347371),
(37, 1, 5, 372, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1893477565),
(39, 17, 5, 1, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1345623288),
(40, 26, 5, 1, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1345726697),
(41, 18, 5, 2, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1347787470),
(42, 40, 5, 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1352881137),
(43, 43, 5, 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1354089728);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_feed`
--

CREATE TABLE IF NOT EXISTS `windsforce_feed` (
  `feed_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `feed_username` varchar(50) NOT NULL COMMENT '用户名',
  `feed_template` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态模板',
  `feed_data` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态数据',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`feed_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_friend`
--

CREATE TABLE IF NOT EXISTS `windsforce_friend` (
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `friend_friendid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '好友ID',
  `friend_direction` tinyint(1) NOT NULL DEFAULT '1' COMMENT '关系，1（A加B）,3（A与B彼此相加）',
  `friend_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `friend_comment` char(255) NOT NULL DEFAULT '' COMMENT '备注',
  `friend_fancomment` varchar(255) NOT NULL COMMENT '粉丝备注',
  `create_dateline` int(10) NOT NULL COMMENT '添加时间',
  `friend_username` varchar(50) NOT NULL COMMENT '用户名',
  `friend_friendusername` varchar(50) NOT NULL COMMENT '好友用户名',
  KEY `user_id` (`user_id`),
  KEY `friend_friendid` (`friend_friendid`),
  KEY `create_dateline` (`create_dateline`),
  KEY `friend_status` (`friend_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_group`
--

CREATE TABLE IF NOT EXISTS `windsforce_group` (
  `group_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '小组ID',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_name` char(32) NOT NULL DEFAULT '' COMMENT '群组名字',
  `group_nikename` char(32) NOT NULL DEFAULT '' COMMENT '小组英文名称',
  `group_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '群组排序',
  `group_description` text NOT NULL COMMENT '小组介绍',
  `group_listdescription` varchar(300) NOT NULL COMMENT '列表小组介绍',
  `group_path` char(32) NOT NULL DEFAULT '' COMMENT '图标路径',
  `group_icon` char(32) DEFAULT NULL COMMENT '小组图标',
  `group_topicnum` int(10) NOT NULL DEFAULT '0' COMMENT '帖子统计',
  `group_topictodaynum` int(10) NOT NULL DEFAULT '0' COMMENT '统计今天发帖',
  `group_usernum` int(10) NOT NULL DEFAULT '0' COMMENT '小组成员数',
  `group_topiccomment` int(10) NOT NULL DEFAULT '0' COMMENT '回帖数量',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupcategory`
--

CREATE TABLE IF NOT EXISTS `windsforce_groupcategory` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupcategoryindex`
--

CREATE TABLE IF NOT EXISTS `windsforce_groupcategoryindex` (
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `groupcategory_id` int(10) NOT NULL COMMENT '群组分类ID',
  PRIMARY KEY (`group_id`,`groupcategory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupfeed`
--

CREATE TABLE IF NOT EXISTS `windsforce_groupfeed` (
  `groupfeed_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `groupfeed_username` varchar(50) NOT NULL COMMENT '用户名',
  `groupfeed_template` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态模板',
  `groupfeed_data` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态数据',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`groupfeed_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupoption`
--

CREATE TABLE IF NOT EXISTS `windsforce_groupoption` (
  `groupoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `groupoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`groupoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_groupoption`
--

INSERT INTO `windsforce_groupoption` (`groupoption_name`, `groupoption_value`) VALUES
('group_isaudit', '0'),
('group_icon_uploadfile_maxsize', '204800'),
('group_indextopicnum', '10'),
('group_listtopicnum', '10'),
('group_hottopic_date', '604800'),
('group_hottopic_num', '10');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopic`
--

CREATE TABLE IF NOT EXISTS `windsforce_grouptopic` (
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
  PRIMARY KEY (`grouptopic_id`),
  KEY `grounptopiccategory_id` (`grouptopiccategory_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `grouptopic_status` (`grouptopic_status`),
  KEY `create_dateline` (`create_dateline`),
  KEY `grouptopic_isposts` (`grouptopic_addtodigest`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopiccategory`
--

CREATE TABLE IF NOT EXISTS `windsforce_grouptopiccategory` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_grouptopiccomment`
--

CREATE TABLE IF NOT EXISTS `windsforce_grouptopiccomment` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_groupuser`
--

CREATE TABLE IF NOT EXISTS `windsforce_groupuser` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `groupuser_isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_homefresh`
--

CREATE TABLE IF NOT EXISTS `windsforce_homefresh` (
  `homefresh_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '新鲜事ID',
  `homefresh_title` varchar(300) NOT NULL COMMENT '新鲜事标题',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
  `homefresh_username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `homefresh_from` varchar(20) NOT NULL DEFAULT '' COMMENT '来源',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `homefresh_message` text NOT NULL COMMENT '新鲜事内容',
  `homefresh_ip` varchar(20) NOT NULL DEFAULT '' COMMENT 'IP',
  `homefresh_commentnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论数量',
  `homefresh_goodnum` int(10) NOT NULL DEFAULT '0' COMMENT '赞数量',
  `homefresh_viewnum` int(10) NOT NULL DEFAULT '0' COMMENT '评论数量',
  `homefresh_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '新鲜事状态',
  PRIMARY KEY (`homefresh_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `homefresh_status` (`homefresh_status`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_homefreshcomment`
--

CREATE TABLE IF NOT EXISTS `windsforce_homefreshcomment` (
  `homefreshcomment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID，在线用户评论',
  `homefreshcomment_name` varchar(50) NOT NULL COMMENT '名字',
  `homefreshcomment_content` text NOT NULL COMMENT '内容',
  `homefreshcomment_email` varchar(300) NOT NULL COMMENT '邮件',
  `homefreshcomment_url` varchar(300) NOT NULL COMMENT 'URL',
  `homefreshcomment_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `homefreshcomment_ip` varchar(16) NOT NULL COMMENT 'IP',
  `homefreshcomment_parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `homefreshcomment_isreplymail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否邮件通知，通知给评论者',
  `homefreshcomment_ismobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为手机评论',
  `homefreshcomment_auditpass` tinyint(1) NOT NULL DEFAULT '1' COMMENT '审核是否通过',
  `homefresh_id` char(10) NOT NULL COMMENT '新鲜事ID',
  PRIMARY KEY (`homefreshcomment_id`),
  KEY `user_id` (`user_id`),
  KEY `homefresh_id` (`homefresh_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `homefreshcomment_status` (`homefreshcomment_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_homehelp`
--

CREATE TABLE IF NOT EXISTS `windsforce_homehelp` (
  `homehelp_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帮组ID',
  `homehelp_title` varchar(250) NOT NULL COMMENT '帮组标题',
  `homehelp_content` text NOT NULL COMMENT '帮助正文',
  `homehelpcategory_id` int(10) NOT NULL DEFAULT '0' COMMENT '帮组信息分类',
  `homehelp_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '帮助状态',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '发布用户',
  `homehelp_username` varchar(50) NOT NULL COMMENT '文章发布用户',
  `homehelp_updateuserid` int(10) NOT NULL DEFAULT '0' COMMENT '最新更新帮助的用户',
  `homehelp_updateusername` varchar(50) NOT NULL COMMENT '文章最后更新用户',
  `homehelp_viewnum` int(10) NOT NULL DEFAULT '0' COMMENT '帮助浏览次数',
  PRIMARY KEY (`homehelp_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `homehelp_status` (`homehelp_status`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `windsforce_homehelp`
--

INSERT INTO `windsforce_homehelp` (`homehelp_id`, `homehelp_title`, `homehelp_content`, `homehelpcategory_id`, `homehelp_status`, `create_dateline`, `update_dateline`, `user_id`, `homehelp_username`, `homehelp_updateuserid`, `homehelp_updateusername`, `homehelp_viewnum`) VALUES
(1, '欢迎来到我们的帮助宝库！', '{site_name} 欢迎你的到来，希望这里能够帮助到你！', 1, 1, 1340045081, 1340213370, 1, 'admin', 1, 'admin', 0),
(2, '我必须要注册吗？', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">这取决于管理员如何设置 {site_name} 的用户组权限选项，您甚至有可能必须在注册成正式用户后后才能浏览网站。当然，在通常情况下，您至少应该是正式用户才能发新帖和回复已有帖子。请先</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">免费注册成为我们的新用户！&nbsp;</span><br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">强烈建议您注册，这样会得到很多以游客身份无法实现的功能。</span>', 1, 1, 1340163725, 1340213377, 1, 'admin', 1, 'admin', 2),
(3, '我如何登录网站？', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">如果您已经注册成为该论坛的会员，哪么您只要通过访问页面右上的</span><a href="http://bbs.emlog.net/logging.php?action=login" target="_blank" style="word-wrap:break-word;text-decoration:none;color:#000000;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">登录</a><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">，进入登陆界面填写正确的用户名和密码，点击“登录”即可完成登陆如果您还未注册请点击这里。</span><br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">如果需要保持登录，请选择相应的 Cookie 时间，在此时间范围内您可以不必输入密码而保持上次的登录状态。</span>', 1, 1, 1340164011, 1340213385, 1, 'admin', 1, 'admin', 2),
(4, '忘记我的登录密码，怎么办？', '', 1, 1, 1340164050, 1340213393, 1, 'admin', 1, 'admin', 9),
(5, '我如何使用个性化头像', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">在</span><span style="font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">头部</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">有一个“修改头像”的选项，可以使用自定义的头像。</span>', 1, 1, 1340164159, 1340213404, 1, 'admin', 1, 'admin', 4),
(6, '我如何修改登录密码', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">在</span><span style="font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">基本信息中</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">，填写“旧密码”，“新密码”，“确认新密码”。点击“提交”，即可修改。</span>', 1, 1, 1340164237, 1343443978, 1, 'admin', 1, 'admin', 22),
(7, '我如何使用个性化签名和昵称', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">在</span><span style="font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">个人资料中</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">，有一个“昵称”和“个人签名”的选项，可以在此设置。</span>', 1, 1, 1340164280, 1343444041, 1, 'admin', 1, 'admin', 27),
(8, '我如何使用“会员”功能', '<ul>\n	<li>\n		<span style="white-space:nowrap;">须首先登录，没有用户名的请先注册；</span> \n	</li>\n	<li>\n		<span style="white-space:nowrap;">登录之后在论坛的左上方会出现一个“个人中心”的超级链接，点击这个链接之后就可进入到有关于您的信息。</span> \n	</li>\n</ul>', 2, 1, 1340164420, 1343444036, 1, 'admin', 1, 'admin', 45);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_homehelpcategory`
--

CREATE TABLE IF NOT EXISTS `windsforce_homehelpcategory` (
  `homehelpcategory_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帮助分类ID',
  `homehelpcategory_name` char(32) NOT NULL DEFAULT '' COMMENT '帮助分类名字',
  `homehelpcategory_count` int(10) NOT NULL DEFAULT '0' COMMENT '帮助个数',
  `homehelpcategory_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '帮助分类排序名字',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `create_dateline` int(10) NOT NULL COMMENT '群组创建时间',
  PRIMARY KEY (`homehelpcategory_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `homehelpcategory_sort` (`homehelpcategory_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `windsforce_homehelpcategory`
--

INSERT INTO `windsforce_homehelpcategory` (`homehelpcategory_id`, `homehelpcategory_name`, `homehelpcategory_count`, `homehelpcategory_sort`, `update_dateline`, `create_dateline`) VALUES
(1, '用户须知', 7, 0, 1340187070, 1340171834),
(2, '基本功能操作', 1, 0, 1340187040, 1340162722),
(3, '其他相关问题', 0, 0, 1340524577, 1340162735);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_homeoption`
--

CREATE TABLE IF NOT EXISTS `windsforce_homeoption` (
  `homeoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `homeoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`homeoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_homeoption`
--

INSERT INTO `windsforce_homeoption` (`homeoption_name`, `homeoption_value`) VALUES
('homehelp_list_num', '10'),
('homefresh_list_num', '15'),
('homefresh_list_substring_num', '500'),
('user_list_num', '10'),
('friend_list_num', '10'),
('my_friend_limit_num', '6'),
('pm_list_num', '5'),
('pm_list_substring_num', '200'),
('pm_single_list_num', '10'),
('homefreshcomment_list_num', '10'),
('comment_min_len', '5'),
('comment_max_len', '500'),
('comment_post_space', '0'),
('comment_banip_enable', '1'),
('comment_ban_ip', ''),
('comment_spam_enable', '1'),
('comment_spam_words', '六合彩'),
('comment_spam_url_num', '3'),
('comment_spam_content_size', '100'),
('disallowed_all_english_word', '1'),
('disallowed_spam_word_to_database', '1'),
('close_comment_feature', '0'),
('comment_repeat_check', '1'),
('audit_comment', '1'),
('seccode_comment_status', '0'),
('comment_mail_to_admin', '0'),
('comment_mail_to_author', '0'),
('homefreshcomment_limit_num', '4'),
('homefreshchildcomment_limit_num', '4'),
('homefreshchildcomment_list_num', '4'),
('homefreshcomment_substring_num', '80'),
('homefreshtitle_substring_num', '30');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_homesite`
--

CREATE TABLE IF NOT EXISTS `windsforce_homesite` (
  `homesite_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `homesite_name` char(32) NOT NULL DEFAULT '' COMMENT '键值',
  `homesite_nikename` char(32) NOT NULL COMMENT '站点信息别名',
  `homesite_content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`homesite_id`),
  UNIQUE KEY `homesite_name` (`homesite_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `windsforce_homesite`
--

INSERT INTO `windsforce_homesite` (`homesite_id`, `homesite_name`, `homesite_nikename`, `homesite_content`) VALUES
(1, 'aboutus', '关于我们', '<h3>\n	社区化电子商务\n</h3>\n{site_name} 致力于打造以社区为基础的电子商务平台。<br />\n<span style="white-space:nowrap;"></span><br />\n<h3>\n	我们的目标\n</h3>\n我们的理念：{site_description}'),
(2, 'contactus', '联系我们', '<h3>\n	联系我们\n</h3>\n<p>\n	如果您对本站有任何疑问或建议，请通过以下方式联系我们：{admin_email}\n</p>'),
(3, 'agreement', '用户协议', '<div class="hero-unit">\n	<h4>\n		用户内容知识共享\n	</h4>\n	<ul>\n		<li>\n			自由复制、发行、展览、表演、放映、广播或通过信息网络传播本作品\n		</li>\n		<li>\n			自由创作演绎作品\n		</li>\n		<li>\n			自由对本作品进行商业性使用\n		</li>\n	</ul>\n	<h4>\n		惟须遵守下列条件\n	</h4>\n	<ul>\n		<li>\n			署名－您必须按照作者或者许可人指定的方式对作品进行署名。\n		</li>\n		<li>\n			相同方式共享－如果您改变、转换本作品或者以本作品为基础进行创作，您只能采用与本协议相同的许可协议发布基于本作品的演绎作品。\n		</li>\n	</ul>\n</div>\n<h3>\n	服务条款确认与接纳\n</h3>\n<p>\n	{site_name} 拥有 {site_url}&nbsp;及其涉及到的产品、相关软件的所有权和运作权， {site_name} 享有对 {site_url} 上一切活动的监督、提示、检查、纠正及处罚等权利。用户通过注册程序阅读本服务条款并点击"同意"按钮完成注册，即表示用户与 {site_name} 已达成协议，自愿接受本服务条款的所有内容。如果用户不同意服务条款的条件，则不能获得使用 {site_name} 服务以及注册成为用户的权利。\n</p>\n<h3>\n	使用规则\n</h3>\n<ol>\n	<li>\n		用户注册成功后，{site_name} 将给予每个用户一个用户帐号及相应的密码，该用户帐号和密码由用户负<span style="white-space:nowrap;">责保管；用户应当对以其用户帐号进行的所有活动和事件负法律责任。</span> \n	</li>\n	<li>\n		用户须对在 {site_name} 的注册信息的真实性、合法性、有效性承担全部责任，用户不得冒充他人；不得利用他人的名义发布任何信息；不得恶意使用注册帐户导致其他用户误认；否则 {site_name} 有权立即停止提供服务，收回其帐号并由用户独自承担由此而产生的一切法律责任。\n	</li>\n	<li>\n		用户不得使用 {site_name} 服务发送或传播敏感信息和违反国家法律制度的信息，包括但不限于下列信息:\n		<ul>\n			<li>\n				反对宪法所确定的基本原则的；\n			</li>\n			<li>\n				危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；\n			</li>\n			<li>\n				损害国家荣誉和利益的；\n			</li>\n			<li>\n				煽动民族仇恨、民族歧视，破坏民族团结的；\n			</li>\n			<li>\n				破坏国家宗教政策，宣扬邪教和封建迷信的；\n			</li>\n			<li>\n				散布谣言，扰乱社会秩序，破坏社会稳定的；\n			</li>\n			<li>\n				散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；\n			</li>\n			<li>\n				侮辱或者诽谤他人，侵害他人合法权益的\n			</li>\n			<li>\n				含有法律、行政法规禁止的其他内容的。\n			</li>\n		</ul>\n	</li>\n	<li>\n		{site_name} 有权对用户使用 {site_name}&nbsp;的情况进行审查和监督，如用户在使用 {site_name} 时违反任何上述规定，{site_name} 或其授权的人有权要求用户改正或直接采取一切必要的措施（包括但不限于删除用户张贴的内容、暂停或终止用户使用 {site_name}&nbsp;的权利）以减轻用户不当行为造成的影响。\n	</li>\n	<li>\n		盗取他人用户帐号或利用网络通讯骚扰他人，均属于非法行为。用户不得采用测试、欺骗等任何非法手段，盗取其他用户的帐号和对他人进行骚扰。\n	</li>\n</ol>\n<h3>\n	知识产权\n</h3>\n<ol>\n	<li>\n		用户保证和声明对其所提供的作品拥有完整的合法的著作权或完整的合法的授权可以用于其在 {site_name} 上从事&gt;的活动，保证 {site_name} 使用该作品不违反国家的法律法规，也不侵犯第三方的合法权益或承担任何义务。用户应对其所提供作品因形式、内容及授权的不完善、不合法所造成的一切后果承担完全责任。\n	</li>\n	<li>\n		对于经用户本人创作并上传到 {site_name} 的文本、图片、图形等， {site_name} 保留对其网站所有内容进行实时监控的权利，并有权依其独立判断对任何违反本协议约定的作品实施删除。{site_name} 对于删除用户作品引起的任何后果或导致用户的任何损失不负任何责任。\n	</li>\n	<li>\n		因用户作品的违法或侵害第三人的合法权益而导致 {site_name} 或其关联公司对第三方承担任何性质的赔偿、补偿或罚款而遭受损失（直接的、间接的、偶然的、惩罚性的和继发的损失），用户对于 {site_name} 或其关联公司蒙受的上述损失承担全面的赔偿责任。\n	</li>\n	<li>\n		任何第三方，都可以在遵循 《<a href="http://creativecommons.org/licenses/by-sa/2.5/cn/" target="_blank" style="white-space:nowrap;">知识共享署名-相同方式共享 2.5 中国大陆许可协议</a>》 的情况下分享本站用户创造的内容。\n	</li>\n</ol>\n<h3>\n	免责声明\n</h3>\n<p>\n	<br />\n</p>\n<ul>\n	<li>\n		{site_name} 不能对用户在本社区回答问题的答案或评论的准确性及合理性进行保证。\n	</li>\n	<li>\n		若{site_name} 已经明示其网络服务提供方式发生变更并提醒用户应当注意事项，用户未按要求操作所产生的一切后果由用户自行承担。\n	</li>\n	<li>\n		用户明确同意其使用 {site_name} 网络服务所存在的风险将完全由其自己承担；因其使用 {site_name} 服务而产生的一切后果也由其自己承担，{site_name} 对用户不承担任何责任。\n	</li>\n	<li>\n		{site_name} 不保证网络服务一定能满足用户的要求，也不保证网络服务不会中断，对网络服务的及时性、安全性、准确性也都不作保证。\n	</li>\n	<li>\n		对于因不可抗力或 {site_name} 不能控制的原因造成的网络服务中断或其它缺陷，{site_name} 不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。\n	</li>\n	<li>\n		用户同意保障和维护 {site_name} 及其他用户的利益，用户在 {site_name} 发表的内容仅表明其个人的立场和观点，并不代表 {site_name} 的立场或观点。由于用户发表内容违法、不真实、不正当、侵犯第三方合法权益，或用户违反本协议项下的任何条款而给 {site_name} 或任何其他第三人造成损失，用户同意承担由此造成的损害赔偿责任。\n	</li>\n</ul>\n<p>\n	<br />\n</p>\n<h3>\n	服务条款的修改\n</h3>\n<p>\n	{site_name} 会在必要时修改服务条款，服务条款一旦发生变动，{site_name} 将会在用户进入下一步使用前的页面提示修改内容。如果您同意改动，则再一次激活"我同意"按钮。如果您不接受，则及时取消您的用户使用服务资格。 用户要继续使用 {site_name} 各项服务需要两方面的确认:\n</p>\n<ol>\n	<li>\n		首先确认 {site_name} 服务条款及其变动。\n	</li>\n	<li>\n		同意接受所有的服务条款限制。\n	</li>\n</ol>\n<h3>\n	联系我们\n</h3>\n<p>\n	如果您对此服务条款有任何疑问或建议，请通过以下方式联系我们：{admin_email}\n</p>'),
(4, 'privacy', '隐私政策', '<h3>\n	隐私政策\n</h3>\n<p>\n{site_name}（{site_url}）以此声明对本站用户隐私保护的许诺。{site_name} 的隐私声明正在不断改进中，随着 {site_name} 服务范围的扩大，会随时更新隐私声明，欢迎您随时查看隐私声明。<br />\n</p>\n<h3>\n	隐私政策\n</h3>\n<p>{site_name} 非常重视对用户隐私权的保护，承诺不会在未获得用户许可的情况下擅自将用户的个人资料信息出租或出售给任何第三方，但以下情况除外:<br />\n您同意让第三方共享资料；<br />\n<ul>\n	<li>\n		您同意公开你的个人资料，享受为您提供的产品和服务；\n	</li>\n	<li>\n		本站需要听从法庭传票、法律命令或遵循法律程序；\n	</li>\n	<li>\n		本站发现您违反了本站服务条款或本站其它使用规定。\n	</li>\n</ul>\n</p>');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_hometag`
--

CREATE TABLE IF NOT EXISTS `windsforce_hometag` (
  `hometag_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户标签',
  `hometag_name` char(32) NOT NULL DEFAULT '' COMMENT '标签名字',
  `hometag_count` int(10) NOT NULL DEFAULT '0' COMMENT '标签用户数量',
  `hometag_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可用',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`hometag_id`),
  KEY `hometag_name` (`hometag_name`),
  KEY `hometag_status` (`hometag_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_hometagindex`
--

CREATE TABLE IF NOT EXISTS `windsforce_hometagindex` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `hometag_id` int(10) NOT NULL DEFAULT '0' COMMENT '标签ID',
  PRIMARY KEY (`user_id`,`hometag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_link`
--

CREATE TABLE IF NOT EXISTS `windsforce_link` (
  `link_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '衔接ID',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `link_name` varchar(32) NOT NULL COMMENT '名字',
  `link_url` varchar(250) NOT NULL COMMENT 'URL',
  `link_description` varchar(300) NOT NULL COMMENT '描述',
  `link_logo` varchar(360) NOT NULL DEFAULT '0' COMMENT 'LOGO',
  `link_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `link_sort` smallint(6) NOT NULL COMMENT '排序',
  PRIMARY KEY (`link_id`),
  KEY `link_status` (`link_status`),
  KEY `link_sort` (`link_sort`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `windsforce_link`
--

INSERT INTO `windsforce_link` (`link_id`, `create_dateline`, `update_dateline`, `link_name`, `link_url`, `link_description`, `link_logo`, `link_status`, `link_sort`) VALUES
(1, 1355150944, 0, 'DoYouHaoBaby', 'http://doyouhaobaby.net/', 'The DoYouHaoBaby Framework', '', 1, 0),
(2, 1355151005, 0, '风之力', 'http://www.windsforce.net', '风之力APP开发框架', '', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_loginlog`
--

CREATE TABLE IF NOT EXISTS `windsforce_loginlog` (
  `loginlog_id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT '登录ID',
  `user_id` mediumint(8) NOT NULL COMMENT '用户ID',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `loginlog_user` varchar(50) NOT NULL COMMENT '登录用户',
  `loginlog_ip` varchar(40) NOT NULL COMMENT '登录IP',
  `loginlog_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '登录状态',
  `login_application` varchar(20) NOT NULL COMMENT '登录应用',
  PRIMARY KEY (`loginlog_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `loginlog_status` (`loginlog_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_mail`
--

CREATE TABLE IF NOT EXISTS `windsforce_mail` (
  `mail_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '邮件ID',
  `mail_touserid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '接受用户ID',
  `mail_fromuserid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '发送用户ID',
  `mail_tomail` varchar(100) NOT NULL COMMENT '接收者邮件地址',
  `mail_frommail` varchar(100) NOT NULL COMMENT '发送者邮件地址',
  `mail_subject` varchar(300) NOT NULL COMMENT '主题',
  `mail_message` text NOT NULL COMMENT '内容',
  `mail_charset` varchar(15) NOT NULL COMMENT '编码',
  `mail_htmlon` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否开启html',
  `mail_level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '紧急级别',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `mail_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态，是否成功',
  `mail_application` varchar(20) NOT NULL COMMENT '来源应用',
  PRIMARY KEY (`mail_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_nav`
--

CREATE TABLE IF NOT EXISTS `windsforce_nav` (
  `nav_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '导航ID',
  `nav_parentid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `nav_name` varchar(32) NOT NULL COMMENT '菜单名字，如群组',
  `nav_identifier` varchar(255) NOT NULL COMMENT 'URL唯一标识符',
  `nav_title` varchar(255) NOT NULL COMMENT '菜单标题，如Group',
  `nav_url` varchar(255) NOT NULL COMMENT '菜单URL地址',
  `nav_target` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单是否新窗口打开',
  `nav_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单类型，0内置，1自定义',
  `nav_style` varchar(55) NOT NULL COMMENT '菜单的下划线，斜体，粗体修饰',
  `nav_location` tinyint(1) NOT NULL DEFAULT '0' COMMENT '导航位置，0主导航，1头部，2底部',
  `nav_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `nav_sort` tinyint(3) NOT NULL COMMENT '菜单排序',
  `nav_color` tinyint(1) NOT NULL DEFAULT '0' COMMENT '菜单高亮，对应一些颜色值',
  `nav_icon` varchar(255) NOT NULL COMMENT '菜单图标',
  PRIMARY KEY (`nav_id`),
  KEY `nav_status` (`nav_status`),
  KEY `nav_sort` (`nav_sort`),
  KEY `nav_identifier` (`nav_identifier`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `windsforce_nav`
--

INSERT INTO `windsforce_nav` (`nav_id`, `nav_parentid`, `nav_name`, `nav_identifier`, `nav_title`, `nav_url`, `nav_target`, `nav_type`, `nav_style`, `nav_location`, `nav_status`, `nav_sort`, `nav_color`, `nav_icon`) VALUES
(2, 0, '设为首页', 'sethomepage', '', '#', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 1, 1, 0, 0, ''),
(3, 0, '加入收藏', 'setfavorite', '', '#', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 1, 1, 0, 0, ''),
(4, 0, '关于我们', 'aboutus', '', 'home://homesite/aboutus', 0, 0, '', 2, 1, 0, 0, ''),
(5, 0, '联系我们', 'contactus', '', 'home://homesite/contactus', 0, 0, '', 2, 1, 0, 0, ''),
(6, 0, '用户协议', 'agreement', '', 'home://homesite/agreement', 0, 0, '', 2, 1, 0, 0, ''),
(7, 0, '隐私声明', 'privacy', '', 'home://homesite/privacy', 0, 0, '', 2, 1, 0, 0, ''),
(1, 0, '帮助', 'help', '', 'home://homehelp/index', 0, 0, '', 2, 1, 0, 0, ''),
(8, 0, 'Wap手机', 'app_wap', 'wap', 'wap://public/index', 0, 0, '', 0, 0, 0, 0, ''),
(9, 0, '小组', 'app_group', 'group', 'group://public/index', 0, 0, '', 0, 1, 0, 0, ''),
(10, 0, '个人空间', 'app_home', 'home', 'home://public/index', 0, 0, '', 0, 0, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_node`
--

CREATE TABLE IF NOT EXISTS `windsforce_node` (
  `node_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '节点ID',
  `node_name` varchar(50) NOT NULL COMMENT '名字',
  `node_title` varchar(50) DEFAULT NULL COMMENT '别名',
  `node_status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `node_remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `node_sort` smallint(6) unsigned DEFAULT NULL COMMENT '排序',
  `node_parentid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `node_level` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '级别，1（应用），2（模块），3（方法）',
  `nodegroup_id` tinyint(3) unsigned DEFAULT '0' COMMENT '分组ID',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`node_id`),
  KEY `node_level` (`node_level`),
  KEY `node_name` (`node_name`),
  KEY `node_status` (`node_status`),
  KEY `node_parentid` (`node_parentid`),
  KEY `create_dateline` (`create_dateline`),
  KEY `nodegroup_id` (`nodegroup_id`),
  KEY `node_sort` (`node_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `windsforce_node`
--

INSERT INTO `windsforce_node` (`node_id`, `node_name`, `node_title`, `node_status`, `node_remark`, `node_sort`, `node_parentid`, `node_level`, `nodegroup_id`, `create_dateline`, `update_dateline`) VALUES
(1, 'admin', 'admin后台管理', 1, '', 1, 0, 1, 0, 0, 1338558614),
(14, 'admin@rating', '角色等级', 1, '', 5, 1, 2, 1, 1338612283, 1341116051),
(2, 'admin@role', '角色管理', 1, '', 3, 1, 2, 1, 0, 1341116051),
(3, 'admin@user', '用户管理', 1, '', 7, 1, 2, 1, 0, 1341116051),
(4, 'admin@nodegroup', '节点分组', 1, '', 2, 1, 2, 1, 0, 1341116051),
(5, 'admin@node', '节点管理', 1, '', 1, 1, 2, 1, 0, 1341116051),
(6, 'admin@option', '基本设置', 1, '', 1, 1, 2, 2, 1334071697, 1346232386),
(7, 'admin@database', '数据库', 1, '', 1, 1, 2, 3, 1334394862, 1342567736),
(10, 'admin@app', '应用管理', 1, '', 1, 1, 2, 4, 1338021590, 1338045970),
(8, 'admin@registeroption', '注册与访问控制', 1, '', 2, 1, 2, 2, 1337885123, 1346232386),
(9, 'admin@uploadoption', '上传设置', 1, '', 3, 1, 2, 2, 1337887882, 1346232386),
(11, 'admin@installapp', '安装新应用', 1, '', 2, 1, 2, 4, 1338045957, 1338045970),
(12, 'admin@nav', '导航设置', 1, '', 1, 1, 2, 5, 1338271653, 1344813625),
(13, 'admin@rolegroup', '角色分组', 1, '', 4, 1, 2, 1, 1338449972, 1341116051),
(15, 'admin@ratinggroup', '等级分组', 1, '', 6, 1, 2, 1, 1338614127, 1341116051),
(16, 'admin@secoption', '防灌水设置', 1, '', 4, 1, 2, 2, 1339690018, 1346232386),
(17, 'admin@link', '友情链接', 1, '', 1, 1, 2, 6, 1340357076, 1347204167),
(18, 'admin@programoption', '系统版权', 1, '', 11, 1, 2, 2, 1340376127, 1346232386),
(19, 'admin@district', '地区设置', 1, '', 8, 1, 2, 2, 1340471147, 1346232386),
(20, 'admin@badword', '词语过滤', 1, '', 0, 1, 2, 7, 1340648216, 1340648479),
(21, 'admin@userprofilesetting', '用户栏目', 1, '', 8, 1, 2, 1, 1341116036, 1341116051),
(22, 'admin@pmoption', '短消息', 1, '', 5, 1, 2, 2, 1342407121, 1346232386),
(23, 'admin@loginlog', '登录记录', 1, '', 2, 1, 2, 3, 1342567716, 1342567736),
(24, 'admin@pm', '短消息', 1, '', 2, 1, 2, 6, 1342567990, 1347204167),
(25, 'admin@creditoption', '积分设置', 1, '', 7, 1, 2, 2, 1343028013, 1346232386),
(26, 'admin@dateoption', '时间设置', 1, '', 9, 1, 2, 2, 1343285683, 1346232386),
(27, 'admin@styleoption', '界面设置', 1, '', 2, 1, 2, 5, 1343320477, 1344813625),
(28, 'admin@appconfigtool', '应用配置', 1, '', 1, 1, 2, 8, 1343902350, 1351241208),
(29, 'admin@style', '风格管理', 1, '', 3, 1, 2, 5, 1344225253, 1344813625),
(30, 'admin@theme', '模板管理', 1, '', 4, 1, 2, 5, 1344813607, 1344813625),
(31, 'admin@slide', '幻灯片', 1, '', 3, 1, 2, 6, 1345362756, 1347204167),
(32, 'admin@mailoption', '邮件设置', 1, '', 6, 1, 2, 2, 1345532803, 1346232386),
(33, 'admin@appeal', '申诉审核', 1, '', 4, 1, 2, 6, 1345941491, 1347204167),
(34, 'admin@languageoption', '国际化', 1, '', 10, 1, 2, 2, 1346231982, 1346232386),
(35, 'admin@sociatype', '社会化帐号', 1, '', 5, 1, 2, 6, 1347204130, 1347204167),
(36, 'admin@globalcache', '更新缓存', 1, '', 2, 1, 2, 8, 1351132990, 1351241208);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_nodegroup`
--

CREATE TABLE IF NOT EXISTS `windsforce_nodegroup` (
  `nodegroup_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '节点分组ID',
  `nodegroup_name` varchar(50) NOT NULL COMMENT '名字，英文',
  `nodegroup_title` varchar(50) NOT NULL COMMENT '别名，中文等注解',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `nodegroup_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `nodegroup_sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`nodegroup_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `nodegroup_sort` (`nodegroup_sort`),
  KEY `nodegroup_status` (`nodegroup_status`),
  KEY `nodegroup_name` (`nodegroup_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `windsforce_nodegroup`
--

INSERT INTO `windsforce_nodegroup` (`nodegroup_id`, `nodegroup_name`, `nodegroup_title`, `create_dateline`, `update_dateline`, `nodegroup_status`, `nodegroup_sort`) VALUES
(1, 'rbac', '权限', 1296454621, 1343878985, 1, 5),
(2, 'option', '设置', 1334071384, 1343878985, 1, 1),
(3, 'admin', '站长', 1334394747, 1343878985, 1, 8),
(4, 'app', '应用', 1334471579, 1343878985, 1, 4),
(5, 'ui', '界面', 1338271539, 1343878985, 1, 2),
(6, 'announce', '运营', 1340356739, 1343878985, 1, 6),
(7, 'moderate', '内容', 1340648268, 1343878985, 1, 3),
(8, 'tool', '工具', 1343878970, 1343878985, 1, 7);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_option`
--

CREATE TABLE IF NOT EXISTS `windsforce_option` (
  `option_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `option_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_option`
--

INSERT INTO `windsforce_option` (`option_name`, `option_value`) VALUES
('admin_list_num', '15'),
('site_name', 'WindsForce'),
('site_description', 'To Make Prowerful App.应用从此而造'),
('site_url', 'http://localhost/Windsforce/upload'),
('close_site', '0'),
('close_site_reason', 'update...'),
('start_gzip', '0'),
('timeoffset', 'Asia/Shanghai'),
('uploadfile_maxsize', '-1'),
('upload_store_type', 'day'),
('disallowed_register_user', ''),
('disallowed_register_email', ''),
('allowed_register_email', ''),
('audit_register', '0'),
('disallowed_register', '0'),
('icp', '蜀ICP备123456号'),
('home_description', '{site_name}是一个以社区为核心的APP开发框架。在这里你可以我们可以实现产品跨界融合，充分利用APP独立的模式来为网站提供无限动力，我们崇尚的是理念：<span class="label label-success">简单与分享</span>。'),
('admin_email', 'xiaoniuge@dianniu.net'),
('seccode_image_width_size', '160'),
('seccode_image_height_size', '60'),
('seccode_adulterate', '0'),
('seccode_ttf', '1'),
('seccode_tilt', '0'),
('seccode_color', '1'),
('seccode_size', '0'),
('seccode_shadow', '1'),
('seccode_animator', '0'),
('seccode_background', '1'),
('seccode_image_background', '1'),
('seccode_norise', '0'),
('seccode_curve', '1'),
('seccode_type', '1'),
('needforbug_program_name', 'WindsForce'),
('needforbug_program_version', '1.0'),
('needforbug_company_name', 'WindsForce Studio.'),
('needforbug_company_url', 'http://www.windsforce.com'),
('needforbug_program_url', 'http://www.windsforce.net'),
('needforbug_program_year', '2012'),
('needforbug_company_year', '2012'),
('site_year', '2012'),
('stat_code', ''),
('badword_on', '0'),
('pmsend_regdays', '5'),
('pmlimit_oneday', '100'),
('pmflood_ctrl', '10'),
('pm_status', '1'),
('pmsend_seccode', '0'),
('pm_sound_on', '1'),
('pm_sound_type', '1'),
('pm_sound_out_url', ''),
('avatar_uploadfile_maxsize', '512000'),
('extend_credit', 'a:8:{i:1;a:8:{s:9:"available";i:1;s:5:"title";s:6:"经验";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;s:5:"ratio";i:0;}i:2;a:8:{s:9:"available";i:1;s:5:"title";s:6:"金币";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;s:5:"ratio";i:0;}i:3;a:8:{s:9:"available";i:1;s:5:"title";s:6:"贡献";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;s:5:"ratio";i:0;}i:4;a:8:{s:5:"title";s:0:"";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:5:"ratio";i:0;s:9:"available";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;}i:5;a:8:{s:5:"title";s:0:"";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:5:"ratio";i:0;s:9:"available";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;}i:6;a:8:{s:5:"title";s:0:"";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:5:"ratio";i:0;s:9:"available";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;}i:7;a:8:{s:5:"title";s:0:"";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:5:"ratio";i:0;s:9:"available";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;}i:8;a:8:{s:5:"title";s:0:"";s:4:"unit";i:0;s:11:"initcredits";i:0;s:10:"lowerlimit";i:0;s:5:"ratio";i:0;s:9:"available";i:0;s:15:"allowexchangein";i:0;s:16:"allowexchangeout";i:0;}}'),
('credit_stax', '0.2'),
('exchange_mincredits', '100'),
('transfermin_credits', '1000'),
('time_format', 'Y-m-d'),
('date_convert', '1'),
('site_logo', ''),
('seccode_register_status', '0'),
('seccode_login_status', '0'),
('seccode_changepassword_status', '0'),
('seccode_changeinformation_status', '0'),
('seccode_publish_status', '0'),
('flood_ctrl', '15'),
('need_email', '0'),
('need_avatar', '0'),
('need_friendnum', ''),
('remember_time', '604800'),
('front_style_id', '1'),
('admin_theme_name', 'Default'),
('admin_theme_list_num', '6'),
('needforbug_program_company', '风之力（成都）'),
('image_max_width', '800'),
('slide_duration', '0.3'),
('slide_delay', '5'),
('mail_default', '635750556@qq.com'),
('mail_sendtype', '2'),
('mail_server', 'smtp.qq.com'),
('mail_port', '25'),
('mail_auth', '1'),
('mail_from', '635750556@qq.com'),
('mail_auth_username', '635750556'),
('mail_auth_password', 'microlog1990'),
('mail_delimiter', '1'),
('programeupdate_on', '0'),
('mail_testmessage_backup', '这是系统发出的一封用于测试邮件是否设置成功的测试邮件。\r\n{time}\r\n\r\n-----------------------------------------------------\r\n消息来源：{site_name}\r\n站点网址：{site_url}'),
('mail_testsubject_backup', '尊敬的{user_name}：{site_name}系统测试邮件发送成功'),
('mail_testmessage', '这是系统发出的一封用于测试邮件是否设置成功的测试邮件。<br />\n<br />\n2012-10-18 03:25<br />\n<br />\n<br />\n<br />\n-----------------------------------------------------<br />\n<br />\n消息来源：NeedForBug<br />\n<br />\n<p>\n	<br />\n站点网址：http://localhost/needforbug/upload\n</p>\n<br />\n<p>\n	<br />\n</p>\n<br />\n<p>\n	<br />\nadsadasd\n</p>'),
('mail_testsubject', '尊敬的admin：NeedForBug系统测试邮件发送成功222'),
('getpassword_expired', '36000'),
('style_switch_on', '1'),
('extendstyle_switch_on', '1'),
('appeal_expired', '360000'),
('language_switch_on', '1'),
('admin_language_name', 'zh-cn'),
('front_language_name', 'zh-cn'),
('upload_file_mode', '1'),
('upload_allowed_type', 'mp3|jpeg|jpg|gif|bmp|png|rmvb|wma|asf|swf|flv|zip|rar|jar|txt|mp4|wmv|wma'),
('upload_input_num', '10'),
('upload_create_thumb', '0'),
('upload_isauto', '1'),
('upload_flash_limit', '100'),
('upload_thumb_size', '500|500'),
('upload_is_watermark', '0'),
('upload_images_watertype', 'img'),
('upload_imageswater_position', '3'),
('upload_imageswater_offset', '0'),
('upload_watermark_imgurl', ''),
('upload_imageswater_text', ''),
('upload_imageswater_textcolor', '#000000'),
('upload_imageswater_textfontsize', '30'),
('upload_imageswater_textfontpath', 'framework-font'),
('upload_imageswater_textfonttype', 'FetteSteinschrift.ttf'),
('upload_loginuser_view', '1'),
('upload_attach_expirehour', '0'),
('upload_limit_leech', '1'),
('upload_notlimit_leechdomail', ''),
('upload_directto_reallypath', '0'),
('uplod_isinline', '1'),
('upload_ishide_reallypath', '0'),
('ubb_content_autoaddlink', '1'),
('ubb_content_shorturl', '1'),
('ubb_content_urlmaxlen', '50'),
('bgextend_on', '1'),
('bgextend_time', '60'),
('bgextend_repeat', 'repeat'),
('attachment_recommendcategorynum', '10'),
('attachment_recommendnum', '5'),
('attachment_myattachmentnum', '12'),
('attachment_dialogmyattachmentnum', '12'),
('attachment_mycategorynum', '9'),
('attachment_dialogmycategorynum', '9'),
('attachment_attachmentnum', '12'),
('attachment_dialogattachmentnum', '12'),
('attachment_categorynum', '9'),
('attachment_dialogcategorynum', '9'),
('attachment_showimgnum', '2'),
('rating_icontype', 'qq'),
('upload_img_ishide_reallypath', '0'),
('home_title', '新社区'),
('home_newtopic_num', '6'),
('home_hottopic_num', '10'),
('home_recommendgroup_num', '6'),
('home_newhomefresh_num', '10'),
('home_newuser_num', '12'),
('home_hottopic_date', '604800'),
('home_newhelp_num', '8'),
('home_followus_qq', '635750556'),
('home_followus_weibo', 'http://weibo.com/test'),
('home_followus_tqqcom', 'http://t.qq.com/xx'),
('home_followus_renren', 'http://page.renren.com/xxx'),
('front_dialog_style', 'simple'),
('admin_dialog_style', 'default'),
('default_app', 'home');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_pm`
--

CREATE TABLE IF NOT EXISTS `windsforce_pm` (
  `pm_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '短消息ID',
  `pm_msgfrom` varchar(50) NOT NULL DEFAULT '' COMMENT '来源',
  `pm_msgfromid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '来源用户ID',
  `pm_msgtoid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '接收ID',
  `pm_isread` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已经阅读',
  `pm_subject` varchar(75) NOT NULL DEFAULT '' COMMENT '主题',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `pm_message` text NOT NULL COMMENT '内容',
  `pm_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '删除状态',
  `pm_mystatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '我的发件箱短消息状态',
  `pm_fromapp` varchar(30) NOT NULL COMMENT '来源应用',
  `pm_type` enum('system','user') NOT NULL DEFAULT 'user' COMMENT '类型',
  PRIMARY KEY (`pm_id`),
  KEY `pm_msgfromid` (`pm_msgfromid`),
  KEY `pm_msgtoid` (`pm_msgtoid`),
  KEY `create_dateline` (`create_dateline`),
  KEY `pm_status` (`pm_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_pmsystemdelete`
--

CREATE TABLE IF NOT EXISTS `windsforce_pmsystemdelete` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pm_id` int(10) NOT NULL COMMENT '系统短消息删除状态',
  PRIMARY KEY (`user_id`,`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_pmsystemread`
--

CREATE TABLE IF NOT EXISTS `windsforce_pmsystemread` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pm_id` int(10) NOT NULL COMMENT '系统短消息阅读状态',
  PRIMARY KEY (`user_id`,`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_rating`
--

CREATE TABLE IF NOT EXISTS `windsforce_rating` (
  `rating_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '等级ID',
  `rating_name` varchar(50) NOT NULL COMMENT '名字',
  `rating_remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `rating_nikename` varchar(55) DEFAULT NULL COMMENT '等级别名',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) unsigned NOT NULL COMMENT '更新时间',
  `rating_creditstart` int(10) NOT NULL COMMENT '等级开始积分',
  `rating_creditend` int(10) NOT NULL COMMENT '等级结束积分',
  `ratinggroup_id` tinyint(3) NOT NULL COMMENT '等级分组',
  `rating_icon` varchar(35) NOT NULL COMMENT '等级图标',
  PRIMARY KEY (`rating_id`),
  KEY `rating_name` (`rating_name`),
  KEY `rating_nikename` (`rating_nikename`),
  KEY `ratinggroup_id` (`ratinggroup_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- 转存表中的数据 `windsforce_rating`
--

INSERT INTO `windsforce_rating` (`rating_id`, `rating_name`, `rating_remark`, `rating_nikename`, `create_dateline`, `update_dateline`, `rating_creditstart`, `rating_creditend`, `ratinggroup_id`, `rating_icon`) VALUES
(1, '列兵1', '', '', 1295530584, 1343975315, 0, 456, 1, '1.gif'),
(2, '列兵2', '', NULL, 1295530598, 1338883899, 457, 912, 1, '2.gif'),
(3, '三等兵', '', NULL, 1338403516, 1338883899, 913, 1824, 1, '3.gif'),
(4, '二等兵', '', NULL, 1338403530, 1338883899, 1825, 3192, 1, '4.gif'),
(5, '一等兵', '', NULL, 1338403560, 1338883899, 3193, 5016, 1, '5.gif'),
(6, '上等兵1', '', '', 1338403581, 1343585557, 5017, 7296, 1, '6.gif'),
(7, '上等兵2', '', NULL, 1338403594, 1338883899, 7297, 10032, 1, '7.gif'),
(8, '上等兵3', '', NULL, 1338403607, 1338883899, 10033, 13224, 1, '8.gif'),
(9, '上等兵4', '', NULL, 1338403619, 1338883899, 13225, 17784, 1, '9.gif'),
(10, '下士1', '', NULL, 1338403651, 1338883914, 17785, 23940, 2, '10.gif'),
(11, '下士2', '', NULL, 1338403666, 1338883914, 23941, 33060, 2, '11.gif'),
(12, '下士3', '', NULL, 1338403687, 1338883914, 33061, 43092, 2, '12.gif'),
(13, '下士4', '', NULL, 1338403899, 1338883914, 43093, 54036, 2, '13.gif'),
(14, '下士5', '', NULL, 1338403918, 1338883914, 54037, 65892, 2, '14.gif'),
(15, '下士6', '', NULL, 1338403930, 1338883914, 65893, 78660, 2, '15.gif'),
(16, '中士1', '', NULL, 1338403954, 1338883933, 78661, 92340, 2, '16.gif'),
(17, '中士2', '', NULL, 1338403968, 1338883933, 92341, 106932, 2, '17.gif'),
(18, '中士3', '', NULL, 1338403981, 1338883933, 106933, 122436, 2, '18.gif'),
(19, '中士4', '', NULL, 1338403990, 1338883933, 122437, 138852, 2, '19.gif'),
(20, '中士5', '', NULL, 1338404001, 1338883933, 138853, 156180, 2, '20.gif'),
(21, '中士6', '', NULL, 1338404013, 1338883933, 156181, 174420, 2, '21.gif'),
(22, '上士1', '', NULL, 1338404041, 1338883933, 174421, 193572, 2, '22.gif'),
(23, '上士2', '', NULL, 1338404058, 1338883933, 193573, 213636, 2, '23.gif'),
(24, '上士3', '', NULL, 1338404071, 1338883933, 213637, 234612, 2, '24.gif'),
(25, '上士4', '', NULL, 1338404082, 1338883933, 234613, 256500, 2, '25.gif'),
(26, '上士5', '', NULL, 1338404097, 1338883933, 256501, 279300, 2, '26.gif'),
(27, '上士6', '', NULL, 1338404108, 1338883933, 279301, 326724, 2, '27.gif'),
(28, '少尉1', '', NULL, 1338404133, 1338883941, 326725, 375972, 3, '28.gif'),
(29, '少尉2', '', NULL, 1338404143, 1338883941, 375973, 427044, 3, '29.gif'),
(30, '少尉3', '', NULL, 1338404220, 1338883941, 427045, 479940, 3, '30.gif'),
(31, '少尉4', '', NULL, 1338404235, 1338883951, 479941, 534660, 3, '31.gif'),
(32, '少尉5', '', NULL, 1338404246, 1338883951, 534661, 591204, 3, '32.gif'),
(33, '少尉6', '', NULL, 1338404257, 1338883951, 591205, 649572, 3, '33.gif'),
(34, '少尉7', '', NULL, 1338404267, 1338883951, 649573, 709764, 3, '34.gif'),
(35, '少尉8', '', NULL, 1338404277, 1338883951, 709765, 771780, 3, '35.gif'),
(36, '中尉1', '', NULL, 1338404291, 1338883951, 771781, 835620, 3, '36.gif'),
(37, '中尉2', '', NULL, 1338404309, 1338883951, 835621, 901284, 3, '37.gif'),
(38, '中尉3', '', NULL, 1338404319, 1338883951, 901285, 968772, 3, '38.gif'),
(39, '中尉4', '', NULL, 1338404330, 1338883951, 968773, 1038084, 3, '39.gif'),
(40, '中尉5', '', NULL, 1338404341, 1338883951, 103885, 1109220, 3, '40.gif'),
(41, '中尉6', '', NULL, 1338404353, 1338883951, 1109221, 1182180, 3, '41.gif'),
(42, '中尉7', '', NULL, 1338404367, 1338883951, 1182181, 1256964, 3, '42.gif'),
(43, '中尉8', '', NULL, 1338404376, 1338883951, 1256965, 1333572, 3, '43.gif'),
(44, '上尉1', '', NULL, 1338404398, 1338883951, 1333573, 1412004, 3, '44.gif'),
(45, '上尉2', '', NULL, 1338404409, 1338883951, 1412005, 1492260, 3, '45.gif'),
(46, '上尉3', '', NULL, 1338404419, 1338883974, 1492261, 1574340, 3, '46.gif'),
(47, '上尉4', '', NULL, 1338404430, 1338883974, 1574341, 1658244, 3, '47.gif'),
(48, '上尉5', '', NULL, 1338404445, 1338883974, 1658245, 1743927, 3, '48.gif'),
(49, '上尉6', '', NULL, 1338404456, 1338883974, 1743973, 1831524, 3, '49.gif'),
(50, '上尉7', '', NULL, 1338404465, 1338883974, 1831525, 1920900, 3, '50.gif'),
(51, '上尉8', '', NULL, 1338404474, 1338883974, 1920901, 2057700, 3, '51.gif'),
(52, '少校1', '', NULL, 1338404505, 1338883988, 2057701, 2197236, 4, '52.gif'),
(53, '少校2', '', NULL, 1338404519, 1338883988, 2197237, 2339508, 4, '53.gif'),
(54, '少校3', '', NULL, 1338404526, 1338883988, 2338509, 2484516, 4, '54.gif'),
(55, '少校4', '', NULL, 1338404531, 1338883988, 2484517, 2632260, 4, '55.gif'),
(56, '少校5', '', NULL, 1338404539, 1338883988, 2632261, 2782740, 4, '56.gif'),
(57, '少校6', '', NULL, 1338404547, 1338883988, 2782741, 2935956, 4, '57.gif'),
(58, '少校7', '', NULL, 1338404554, 1338883988, 2935957, 3091908, 4, '58.gif'),
(59, '少校8', '', NULL, 1338404569, 1338883988, 3091909, 3277044, 4, '59.gif'),
(60, '中校1', '', NULL, 1338404584, 1338883988, 3277045, 3465372, 4, '60.gif'),
(61, '中校2', '', NULL, 1338404590, 1338883996, 3465373, 3673536, 4, '61.gif'),
(62, '中校3', '', NULL, 1338404596, 1338883996, 3673573, 3885177, 4, '62.gif'),
(63, '中校4', '', NULL, 1338404604, 1338883996, 3885178, 4100295, 4, '63.gif'),
(64, '中校5', '', NULL, 1338404616, 1338883996, 4100296, 4318890, 4, '64.gif'),
(65, '中校6', '', NULL, 1338404625, 1338883996, 4318891, 4540962, 4, '65.gif'),
(66, '中校7', '', NULL, 1338404639, 1338883996, 4540963, 4766511, 4, '66.gif'),
(67, '中校8', '', NULL, 1338404657, 1338883996, 4766512, 5028198, 4, '67.gif'),
(68, '上校1', '', NULL, 1338404747, 1338883996, 5028199, 5319183, 4, '68.gif'),
(69, '上校2', '', NULL, 1338404756, 1338883996, 5139184, 5614500, 4, '69.gif'),
(70, '上校3', '', NULL, 1338404764, 1338883996, 5614501, 5914149, 4, '70.gif'),
(71, '上校4', '', NULL, 1338404773, 1338883996, 5914150, 6218130, 4, '71.gif'),
(72, '上校5', '', NULL, 1338404782, 1338883996, 6218131, 6526500, 4, '72.gif'),
(73, '上校6', '', NULL, 1338404792, 1338883996, 6526501, 6839202, 4, '73.gif'),
(74, '上校7', '', NULL, 1338404801, 1338883996, 6839203, 7156236, 4, '74.gif'),
(75, '上校8', '', NULL, 1338404809, 1338883996, 7156237, 7578036, 4, '75.gif'),
(76, '大校1', '', NULL, 1338404887, 1338884031, 7578037, 8026911, 4, '76.gif'),
(77, '大校2', '', NULL, 1338404897, 1338884031, 8026912, 8481771, 4, '77.gif'),
(78, '大校3', '', NULL, 1338404907, 1338884031, 8481772, 8964561, 4, '78.gif'),
(79, '大校4', '', NULL, 1338404944, 1338884031, 8964562, 9475851, 4, '79.gif'),
(80, '大校5', '', NULL, 1338404953, 1338884031, 9475852, 10016211, 4, '80.gif'),
(81, '大校6', '', NULL, 1338404963, 1338884031, 10016212, 10586211, 4, '81.gif'),
(82, '少将1', '', NULL, 1338404977, 1338884047, 10586212, 11186421, 5, '82.gif'),
(83, '少将2', '', NULL, 1338404986, 1338884047, 11186422, 11817411, 5, '83.gif'),
(84, '少将3', '', NULL, 1338404993, 1338884047, 11817412, 12479751, 5, '84.gif'),
(85, '少将4', '', NULL, 1338405001, 1338884047, 12479752, 13174011, 5, '85.gif'),
(86, '少将5', '', NULL, 1338405009, 1338884047, 13174012, 13900761, 5, '86.gif'),
(87, '少将6', '', NULL, 1338405017, 1338884047, 13900762, 14460571, 5, '87.gif'),
(88, '中将1', '', NULL, 1338405031, 1338884047, 14460572, 15454011, 5, '88.gif'),
(89, '中将2', '', NULL, 1338405040, 1338884047, 15454012, 16281651, 5, '89.gif'),
(90, '中将3', '', NULL, 1338405055, 1338884047, 16281652, 17144061, 5, '90.gif'),
(91, '中将4', '', NULL, 1338405065, 1338884056, 17144062, 18041811, 5, '91.gif'),
(92, '中将5', '', NULL, 1338405075, 1338884056, 18041812, 18975471, 5, '92.gif'),
(93, '中将6', '', NULL, 1338405086, 1338884056, 18975472, 19945611, 5, '93.gif'),
(94, '上将1', '', NULL, 1338405099, 1338884056, 19945612, 20952801, 5, '94.gif'),
(95, '上将2', '', NULL, 1338405108, 1338884056, 20952802, 21997611, 5, '95.gif'),
(96, '上将3', '', NULL, 1338405117, 1338884056, 21997612, 23080611, 5, '96.gif'),
(97, '上将4', '', NULL, 1338405131, 1338884056, 23080612, 24202371, 5, '97.gif'),
(98, '上将5', '', NULL, 1338405140, 1338884056, 24202372, 25363461, 5, '98.gif'),
(99, '上将6', '', NULL, 1338405149, 1338884056, 25363462, 26564451, 5, '99.gif'),
(100, '元帅', '', NULL, 1338405163, 1338884056, 26564452, 26564452, 5, '100.gif');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_ratinggroup`
--

CREATE TABLE IF NOT EXISTS `windsforce_ratinggroup` (
  `ratinggroup_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '等级分组ID',
  `ratinggroup_name` varchar(25) NOT NULL COMMENT '名字，英文',
  `ratinggroup_title` varchar(50) NOT NULL COMMENT '别名，中文等注解',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `ratinggroup_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `ratinggroup_sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`ratinggroup_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `ratinggroup_name` (`ratinggroup_name`),
  KEY `ratinggroup_status` (`ratinggroup_status`),
  KEY `ratinggroup_sort` (`ratinggroup_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `windsforce_ratinggroup`
--

INSERT INTO `windsforce_ratinggroup` (`ratinggroup_id`, `ratinggroup_name`, `ratinggroup_title`, `create_dateline`, `update_dateline`, `ratinggroup_status`, `ratinggroup_sort`) VALUES
(1, 'soldiers', '士兵', 1338469985, 0, 1, 0),
(2, 'nco', '士官', 1338470021, 0, 1, 0),
(3, 'lieutenant', '尉官', 1338470314, 1343585867, 1, 0),
(4, 'colonel', '校官', 1338470412, 0, 1, 0),
(5, 'generals', '将帅', 1338470428, 1338914433, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_role`
--

CREATE TABLE IF NOT EXISTS `windsforce_role` (
  `role_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(50) NOT NULL COMMENT '名字',
  `role_parentid` smallint(6) DEFAULT NULL COMMENT '父级ID',
  `role_status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `role_remark` varchar(300) DEFAULT NULL COMMENT '备注',
  `role_nikename` varchar(55) DEFAULT NULL COMMENT '角色别名',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) unsigned NOT NULL COMMENT '更新时间',
  `rolegroup_id` tinyint(3) NOT NULL DEFAULT '0' COMMENT '角色分组ID',
  PRIMARY KEY (`role_id`),
  KEY `role_parentid` (`role_parentid`),
  KEY `role_status` (`role_status`),
  KEY `role_name` (`role_name`),
  KEY `create_dateline` (`create_dateline`),
  KEY `rolegroup_id` (`rolegroup_id`),
  KEY `role_nikename` (`role_nikename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `windsforce_role`
--

INSERT INTO `windsforce_role` (`role_id`, `role_name`, `role_parentid`, `role_status`, `role_remark`, `role_nikename`, `create_dateline`, `update_dateline`, `rolegroup_id`) VALUES
(1, '管理员', 0, 1, '', '管理员', 1295530584, 1338614986, 2),
(2, '超级群主', 0, 1, '', '超级群主', 1295530598, 1338615068, 2),
(3, '群主', 0, 1, '', '群主', 1338403516, 1338615084, 2),
(4, '禁止发言', 0, 1, '', '禁止发言', 1338403530, 1338615129, 3),
(5, '禁止访问', 0, 1, '', '禁止访问', 1338403560, 1338615291, 3),
(6, '禁止IP', 0, 1, '', '禁止IP', 1338403581, 1338615314, 3),
(7, '游客', 0, 1, '', '游客', 1338403594, 1338615517, 3),
(8, '等待验证会员', 0, 1, '', '等待验证会员', 1338403607, 1338615543, 3),
(9, '限制会员', 0, 1, '', '限制会员', 1338403619, 1338615581, 1),
(10, '新手上路', 0, 1, '', '新手上路', 1338403651, 1338615675, 1),
(11, '注册会员', 0, 1, '', '注册会员', 1338403666, 1338615675, 1),
(12, '中级会员', 0, 1, '', '中级会员', 1338403687, 1338615658, 1),
(13, '高级会员', 0, 1, '', '高级会员', 1338403899, 1339179947, 1),
(14, '金牌会员', 0, 1, '', '金牌会员', 1338403918, 1338615739, 1),
(15, '社区元老', 0, 1, '', '社区元老', 1338403930, 1338615780, 1),
(16, '信息监察员', 0, 1, '', '信息监察员', 1338403954, 1338615905, 2),
(17, '网站编辑', 0, 1, '', '网站编辑', 1338403968, 1338615931, 2),
(18, '审核员', 0, 1, '', '审核员', 1338403981, 1338615954, 2),
(19, '实习群主', 0, 1, '', '实习群主', 1338403990, 1339403991, 2);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_rolegroup`
--

CREATE TABLE IF NOT EXISTS `windsforce_rolegroup` (
  `rolegroup_id` smallint(3) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色分组ID',
  `rolegroup_name` varchar(50) NOT NULL COMMENT '名字，英文',
  `rolegroup_title` varchar(50) NOT NULL COMMENT '别名，中文等注解',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `rolegroup_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态',
  `rolegroup_sort` smallint(3) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`rolegroup_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `rolegroup_name` (`rolegroup_name`),
  KEY `rolegroup_status` (`rolegroup_status`),
  KEY `rolegroup_sort` (`rolegroup_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `windsforce_rolegroup`
--

INSERT INTO `windsforce_rolegroup` (`rolegroup_id`, `rolegroup_name`, `rolegroup_title`, `create_dateline`, `update_dateline`, `rolegroup_status`, `rolegroup_sort`) VALUES
(1, 'usergroup', '用户组', 1338469985, 1338614736, 1, 0),
(2, 'admingroup', '管理组', 1338470021, 1338614767, 1, 0),
(3, 'specialgroup', '特殊分组', 1338470314, 1338615164, 1, 0),
(4, 'customgroup', '自定义', 1338616034, 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_session`
--

CREATE TABLE IF NOT EXISTS `windsforce_session` (
  `session_hash` varchar(6) NOT NULL COMMENT 'HASH',
  `session_auth_key` varchar(32) NOT NULL COMMENT 'AUTH_KEY',
  `user_id` mediumint(8) NOT NULL COMMENT '用户ID',
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_session`
--

INSERT INTO `windsforce_session` (`session_hash`, `session_auth_key`, `user_id`) VALUES
('9AD7ed', '039721a1f2cea6eefa82c7485f48f625', 1);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_slide`
--

CREATE TABLE IF NOT EXISTS `windsforce_slide` (
  `slide_id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '滑动幻灯片状态ID',
  `slide_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `slide_title` varchar(50) NOT NULL COMMENT '标题',
  `slide_url` varchar(325) NOT NULL COMMENT 'URL地址',
  `slide_img` varchar(325) NOT NULL COMMENT '图片地址',
  `slide_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`slide_id`),
  KEY `slide_status` (`slide_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `windsforce_slide`
--

INSERT INTO `windsforce_slide` (`slide_id`, `slide_sort`, `slide_title`, `slide_url`, `slide_img`, `slide_status`, `create_dateline`, `update_dateline`) VALUES
(1, 0, '欢迎加入', '{Dyhb::U(''home://public/register'')}', '{__PUBLIC__.''/images/common/slidebox/1.jpg''}', 1, 1345357086, 1345364471),
(2, 0, '立刻登录', '{Dyhb::U(''home://public/login'')}', '{__PUBLIC__.''/images/common/slidebox/2.jpg''}', 1, 1345357086, 0),
(3, 0, '关于我们', '{Dyhb::U(''home://homesite/aboutus'')}', '{__PUBLIC__.''/images/common/slidebox/3.jpg''}', 1, 1345357086, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_sociatype`
--

CREATE TABLE IF NOT EXISTS `windsforce_sociatype` (
  `sociatype_id` tinyint(3) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sociatype_title` varchar(35) NOT NULL COMMENT '标题',
  `sociatype_identifier` varchar(32) NOT NULL COMMENT '社会化帐号唯一标识',
  `sociatype_appid` varchar(80) NOT NULL COMMENT '应用ID',
  `sociatype_appkey` varchar(100) NOT NULL COMMENT 'KEY',
  `sociatype_callback` varchar(325) NOT NULL COMMENT '回调',
  `sociatype_scope` varchar(200) NOT NULL COMMENT '允许的权限',
  `sociatype_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`sociatype_id`),
  KEY `status` (`sociatype_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `windsforce_sociatype`
--

INSERT INTO `windsforce_sociatype` (`sociatype_id`, `sociatype_title`, `sociatype_identifier`, `sociatype_appid`, `sociatype_appkey`, `sociatype_callback`, `sociatype_scope`, `sociatype_status`, `create_dateline`) VALUES
(1, 'QQ互联', 'qq', '100303001', '2c8a05c6c7930f7bd0d481a8462c7db0', 'http://bbs.doyouhaobaby.net/index.php?app=home&c=public&a=socia_callback&vendor=qq', 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo', 1, 1345777926),
(2, '新浪微博', 'weibo', '', '', '', '', 1, 1347356728);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_sociauser`
--

CREATE TABLE IF NOT EXISTS `windsforce_sociauser` (
  `sociauser_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sociauser_appid` varchar(64) NOT NULL COMMENT '第三方应用ID',
  `sociauser_openid` char(32) NOT NULL DEFAULT '' COMMENT '用户绑定Openid值',
  `user_id` varchar(16) NOT NULL COMMENT '本站用户ID',
  `sociauser_vendor` varchar(20) NOT NULL DEFAULT '' COMMENT '第三方网站名称',
  `sociauser_keys` text NOT NULL COMMENT '密钥',
  `sociauser_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `sociauser_nikename` varchar(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `sociauser_desc` varchar(100) NOT NULL DEFAULT '' COMMENT '简介',
  `sociauser_url` varchar(100) NOT NULL DEFAULT '' COMMENT '主页',
  `sociauser_img` varchar(100) NOT NULL DEFAULT '' COMMENT '头像',
  `sociauser_img1` varchar(100) NOT NULL COMMENT '头像2',
  `sociauser_img2` varchar(100) NOT NULL COMMENT '头像3',
  `sociauser_gender` varchar(10) NOT NULL DEFAULT '' COMMENT '性别',
  `sociauser_email` varchar(30) NOT NULL DEFAULT '' COMMENT '邮箱',
  `sociauser_location` varchar(20) NOT NULL DEFAULT '' COMMENT '所在地',
  `sociauser_vip` tinyint(3) NOT NULL COMMENT 'vip',
  `sociauser_level` tinyint(3) NOT NULL DEFAULT '0' COMMENT '级别',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`sociauser_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_style`
--

CREATE TABLE IF NOT EXISTS `windsforce_style` (
  `style_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主题样式ID',
  `style_name` varchar(32) NOT NULL DEFAULT '' COMMENT '主题样式名字',
  `style_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '主题样式状态',
  `theme_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '模板ID',
  `style_extend` varchar(320) NOT NULL DEFAULT '' COMMENT '主题样式扩展',
  PRIMARY KEY (`style_id`),
  KEY `theme_id` (`theme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `windsforce_style`
--

INSERT INTO `windsforce_style` (`style_id`, `style_name`, `style_status`, `theme_id`, `style_extend`) VALUES
(1, '默认主题', 1, 1, 't1	t2	t3	t4	t5|t4');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_stylevar`
--

CREATE TABLE IF NOT EXISTS `windsforce_stylevar` (
  `stylevar_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '变量ID',
  `style_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `stylevar_variable` text NOT NULL COMMENT '变量名',
  `stylevar_substitute` text NOT NULL COMMENT '变量替换值',
  PRIMARY KEY (`stylevar_id`),
  KEY `style_id` (`style_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- 转存表中的数据 `windsforce_stylevar`
--

INSERT INTO `windsforce_stylevar` (`stylevar_id`, `style_id`, `stylevar_variable`, `stylevar_substitute`) VALUES
(1, 1, 'img_dir', ''),
(2, 1, 'style_img_dir', ''),
(3, 1, 'logo', 'logo.png'),
(4, 1, 'header_border_width', '1px'),
(5, 1, 'header_border_color', '#ebebeb'),
(6, 1, 'header_text_color', '#333333'),
(7, 1, 'footer_text_color', '#7B7B7B'),
(8, 1, 'normal_font', 'Verdana,Lucida Grande, Lucida Sans Unicode, Lucida Sans, Helvetica, Arial, sans-serif'),
(9, 1, 'normal_fontsize', '13px/18px'),
(10, 1, 'small_font', 'Verdana,Lucida Grande, Lucida Sans Unicode, Lucida Sans, Helvetica, Arial, sans-serif'),
(11, 1, 'small_fontsize', '0.83em'),
(12, 1, 'big_font', 'Verdana,Lucida Grande, Lucida Sans Unicode, Lucida Sans, Helvetica, Arial, sans-serif'),
(13, 1, 'big_fontsize', '20px'),
(14, 1, 'normal_color', '#333333'),
(15, 1, 'medium_textcolor', '#333333'),
(16, 1, 'light_textcolor', '#999999'),
(17, 1, 'link_color', '#037c1d'),
(18, 1, 'highlightlink_color', '#037c1d'),
(19, 1, 'wrap_table_width', '960px'),
(20, 1, 'wrap_table_bg', '#FFFFFF'),
(21, 1, 'wrap_border_width', '1px'),
(22, 1, 'wrap_border_color', '#FFFFFF'),
(23, 1, 'content_fontsize', '14px'),
(24, 1, 'content_big_size', '16px'),
(25, 1, 'content_width', '90%'),
(26, 1, 'content_separate_color', '#FFFFFF'),
(27, 1, 'menu_border_color', '#378C32'),
(28, 1, 'menu_text_color', '#FFFFFF'),
(29, 1, 'menu_hover_bg_color', '#378C32'),
(30, 1, 'menu_hover_text_color', '#FFFFFF'),
(31, 1, 'input_border', '#999999'),
(32, 1, 'input_border_dark_color', '#61c361'),
(33, 1, 'input_bg', '#FFFFFF'),
(34, 1, 'drop_menu_border', '#FFFFFF'),
(35, 1, 'interval_line_color', '#E6E7E1'),
(36, 1, 'common_background_color', '#f1f1f1'),
(37, 1, 'special_border', '#DEDEDE'),
(38, 1, 'special_bg', '#00AC2B'),
(39, 1, 'interleave_color', '#DEDEDE'),
(40, 1, 'noticetext_color', '#FF2B00'),
(41, 1, 'noticetext_border_color', ''),
(42, 1, 'menu_bg_color', '#52a452'),
(43, 1, 'menu_bg_img', ''),
(44, 1, 'menu_bg_extra', ''),
(45, 1, 'header_bg_color', ''),
(46, 1, 'header_bg_img', ''),
(47, 1, 'header_bg_extra', ''),
(48, 1, 'side_bg_color', '#FFFFFF'),
(49, 1, 'side_bg_img', ''),
(50, 1, 'side_bg_extra', ''),
(51, 1, 'bg_color', '#FFFFFF'),
(52, 1, 'bg_img', 'bg.png'),
(53, 1, 'bg_extra', 'repeat'),
(54, 1, 'drop_menu_bg_color', '#CCCCCC'),
(55, 1, 'drop_menu_bg_img', ''),
(56, 1, 'drop_menu_bg_extra', 'repeat-x'),
(57, 1, 'footer_bg_color', ''),
(58, 1, 'footer_bg_img', 'footer_bg.png'),
(59, 1, 'footer_bg_extra', 'repeat-x'),
(60, 1, 'float_bg_color', '#FFFFFF'),
(61, 1, 'float_bg_img', ''),
(62, 1, 'float_bg_extra', ''),
(63, 1, 'float_mask_bg_color', '#FFFFFF'),
(64, 1, 'float_mask_bg_img', ''),
(65, 1, 'float_mask_bg_extra', '');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_syscache`
--

CREATE TABLE IF NOT EXISTS `windsforce_syscache` (
  `syscache_name` varchar(32) NOT NULL COMMENT '缓存名字',
  `syscache_type` tinyint(3) unsigned NOT NULL COMMENT '缓存类型',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `syscache_data` mediumblob NOT NULL COMMENT '缓存数据',
  PRIMARY KEY (`syscache_name`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_theme`
--

CREATE TABLE IF NOT EXISTS `windsforce_theme` (
  `theme_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主题ID',
  `theme_name` varchar(32) NOT NULL DEFAULT '' COMMENT '主题名字',
  `theme_dirname` varchar(32) NOT NULL COMMENT '主题英文目录名字',
  `theme_copyright` varchar(250) NOT NULL DEFAULT '' COMMENT '主题版权',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `windsforce_theme`
--

INSERT INTO `windsforce_theme` (`theme_id`, `theme_name`, `theme_dirname`, `theme_copyright`) VALUES
(1, '默认模板套系', 'Default', '点牛（成都）');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_user`
--

CREATE TABLE IF NOT EXISTS `windsforce_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `user_name` varchar(50) CHARACTER SET ucs2 NOT NULL COMMENT '用户名',
  `user_nikename` varchar(50) DEFAULT NULL COMMENT '用户别名',
  `user_password` char(32) NOT NULL COMMENT '用户密码',
  `user_registerip` varchar(40) NOT NULL COMMENT '注册IP',
  `user_lastlogintime` int(11) DEFAULT NULL COMMENT '用户最后登录时间',
  `user_lastloginip` varchar(40) DEFAULT NULL COMMENT '用户登录IP',
  `user_logincount` int(10) DEFAULT '0' COMMENT '用户登录次数',
  `user_email` varchar(150) DEFAULT NULL COMMENT '用户Email',
  `user_remark` varchar(255) DEFAULT NULL COMMENT '用户备注',
  `user_sign` varchar(1000) NOT NULL COMMENT '用户签名',
  `create_dateline` int(10) DEFAULT NULL COMMENT '创建时间',
  `update_dateline` int(10) DEFAULT NULL COMMENT '更新时间',
  `user_status` tinyint(1) DEFAULT '0' COMMENT '用户状态',
  `user_random` char(6) NOT NULL COMMENT '用户随机码',
  `user_temppassword` varchar(255) NOT NULL COMMENT '密码重置临时密码',
  `user_extendstyle` varchar(35) NOT NULL COMMENT '用户扩展样式',
  PRIMARY KEY (`user_id`),
  KEY `user_status` (`user_status`),
  KEY `create_dateline` (`create_dateline`),
  KEY `user_email` (`user_email`),
  KEY `user_password` (`user_password`),
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `windsforce_user`
--

INSERT INTO `windsforce_user` (`user_id`, `user_name`, `user_nikename`, `user_password`, `user_registerip`, `user_lastlogintime`, `user_lastloginip`, `user_logincount`, `user_email`, `user_remark`, `user_sign`, `create_dateline`, `update_dateline`, `user_status`, `user_random`, `user_temppassword`, `user_extendstyle`) VALUES
(1, 'admin', '', 'cd5be146ca5cc5985943ef02bd61f70d', '127.0.0.1', 1355150636, '::1', 861, 'xiaoniuge@dianniu.net', '', '每一天都是新的,欢迎大家光临我们的心空间，世界之巅，唯我读准。', 1333281705, 1355151458, 1, '90ad77', '', '0');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_usercount`
--

CREATE TABLE IF NOT EXISTS `windsforce_usercount` (
  `user_id` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `usercount_extendcredit1` int(10) NOT NULL DEFAULT '0' COMMENT '第一种积分类型',
  `usercount_extendcredit2` int(10) NOT NULL DEFAULT '0' COMMENT '第二种积分类型',
  `usercount_extendcredit3` int(10) NOT NULL DEFAULT '0' COMMENT '第三种积分类型',
  `usercount_extendcredit4` int(10) NOT NULL DEFAULT '0' COMMENT '第四种积分类型',
  `usercount_extendcredit5` int(10) NOT NULL DEFAULT '0' COMMENT '第五种积分类型',
  `usercount_extendcredit6` int(10) NOT NULL DEFAULT '0' COMMENT '第六种积分类型',
  `usercount_extendcredit7` int(10) NOT NULL DEFAULT '0' COMMENT '第七种积分类型',
  `usercount_extendcredit8` int(10) NOT NULL DEFAULT '0' COMMENT '第八种积分类型',
  `usercount_friends` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '用户好友数量',
  `usercount_oltime` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '用户在线时间',
  `usercount_fans` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户粉丝数量',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_usercount`
--

INSERT INTO `windsforce_usercount` (`user_id`, `usercount_extendcredit1`, `usercount_extendcredit2`, `usercount_extendcredit3`, `usercount_extendcredit4`, `usercount_extendcredit5`, `usercount_extendcredit6`, `usercount_extendcredit7`, `usercount_extendcredit8`, `usercount_friends`, `usercount_oltime`, `usercount_fans`) VALUES
(1, 38, 706, 0, 0, 0, 0, 0, 0, 12, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_userprofile`
--

CREATE TABLE IF NOT EXISTS `windsforce_userprofile` (
  `user_id` mediumint(8) unsigned NOT NULL COMMENT '用户ID',
  `userprofile_realname` varchar(255) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `userprofile_gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `userprofile_birthyear` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '出生年份',
  `userprofile_birthmonth` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '出生月份',
  `userprofile_birthday` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '生日',
  `userprofile_constellation` varchar(255) NOT NULL DEFAULT '' COMMENT '星座',
  `userprofile_zodiac` varchar(255) NOT NULL DEFAULT '' COMMENT '生肖',
  `userprofile_telephone` varchar(255) NOT NULL DEFAULT '' COMMENT '固定电话',
  `userprofile_mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '手机',
  `userprofile_idcardtype` varchar(255) NOT NULL DEFAULT '' COMMENT '证件类型',
  `userprofile_idcard` varchar(255) NOT NULL DEFAULT '' COMMENT '证件号',
  `userprofile_address` varchar(255) NOT NULL DEFAULT '' COMMENT '邮寄地址',
  `userprofile_zipcode` varchar(255) NOT NULL DEFAULT '' COMMENT '邮编',
  `userprofile_nationality` varchar(255) NOT NULL DEFAULT '' COMMENT '国籍',
  `userprofile_birthprovince` varchar(255) NOT NULL DEFAULT '' COMMENT '出生省份',
  `userprofile_birthcity` varchar(255) NOT NULL DEFAULT '' COMMENT '出生地',
  `userprofile_birthdist` varchar(20) NOT NULL DEFAULT '' COMMENT '出生县',
  `userprofile_birthcommunity` varchar(255) NOT NULL DEFAULT '' COMMENT '出生小区',
  `userprofile_resideprovince` varchar(255) NOT NULL DEFAULT '' COMMENT '居住省份',
  `userprofile_residecity` varchar(255) NOT NULL DEFAULT '' COMMENT '居住地',
  `userprofile_residedist` varchar(20) NOT NULL DEFAULT '' COMMENT '居住县',
  `userprofile_residecommunity` varchar(255) NOT NULL DEFAULT '' COMMENT '居住小区',
  `userprofile_residesuite` varchar(255) NOT NULL DEFAULT '' COMMENT '房间',
  `userprofile_graduateschool` varchar(255) NOT NULL DEFAULT '' COMMENT '毕业学校',
  `userprofile_company` varchar(255) NOT NULL DEFAULT '' COMMENT '学历',
  `userprofile_education` varchar(255) NOT NULL DEFAULT '' COMMENT '公司',
  `userprofile_occupation` varchar(255) NOT NULL DEFAULT '' COMMENT '职业',
  `userprofile_position` varchar(255) NOT NULL DEFAULT '' COMMENT '职位',
  `userprofile_revenue` varchar(255) NOT NULL DEFAULT '' COMMENT '年收入',
  `userprofile_affectivestatus` varchar(255) NOT NULL DEFAULT '' COMMENT '情感状态',
  `userprofile_lookingfor` varchar(255) NOT NULL DEFAULT '' COMMENT '交友目的',
  `userprofile_bloodtype` varchar(255) NOT NULL DEFAULT '' COMMENT '血型',
  `userprofile_height` varchar(255) NOT NULL DEFAULT '' COMMENT '身高',
  `userprofile_weight` varchar(255) NOT NULL DEFAULT '' COMMENT '体重',
  `userprofile_alipay` varchar(255) NOT NULL DEFAULT '' COMMENT '支付宝',
  `userprofile_icq` varchar(255) NOT NULL DEFAULT '' COMMENT 'ICQ',
  `userprofile_qq` varchar(255) NOT NULL DEFAULT '' COMMENT 'QQ',
  `userprofile_yahoo` varchar(255) NOT NULL DEFAULT '' COMMENT 'YAHOO帐号',
  `userprofile_msn` varchar(255) NOT NULL DEFAULT '' COMMENT 'MSN',
  `userprofile_taobao` varchar(255) NOT NULL DEFAULT '' COMMENT '阿里旺旺',
  `userprofile_site` varchar(255) NOT NULL DEFAULT '' COMMENT '个人主页',
  `userprofile_bio` text NOT NULL COMMENT '自我介绍',
  `userprofile_interest` text NOT NULL COMMENT '兴趣爱好',
  `userprofile_google` varchar(255) NOT NULL COMMENT 'Google帐号',
  `userprofile_baidu` varchar(255) NOT NULL COMMENT '百度帐号',
  `userprofile_renren` varchar(255) NOT NULL COMMENT '人人帐号',
  `userprofile_douban` varchar(255) NOT NULL COMMENT '豆瓣帐号',
  `userprofile_facebook` varchar(255) NOT NULL COMMENT 'Facebook',
  `userprofile_twriter` varchar(255) NOT NULL COMMENT 'TWriter',
  `userprofile_dianniu` varchar(255) NOT NULL COMMENT '点牛帐号',
  `userprofile_skype` varchar(255) NOT NULL COMMENT 'Skype',
  `userprofile_weibocom` varchar(255) NOT NULL COMMENT '新浪微博',
  `userprofile_tqqcom` varchar(255) NOT NULL COMMENT '腾讯微博',
  `userprofile_diandian` varchar(255) NOT NULL COMMENT '点点网',
  `userprofile_kindergarten` varchar(255) NOT NULL COMMENT '幼儿班',
  `userprofile_primary` varchar(255) NOT NULL COMMENT '小学',
  `userprofile_juniorhighschool` varchar(255) NOT NULL COMMENT '初中',
  `userprofile_highschool` varchar(255) NOT NULL COMMENT '高中',
  `userprofile_university` varchar(255) NOT NULL COMMENT '大学',
  `userprofile_master` varchar(255) NOT NULL COMMENT '硕士',
  `userprofile_dr` varchar(255) NOT NULL COMMENT '博士',
  `userprofile_nowschool` varchar(255) NOT NULL COMMENT '当前学校',
  `userprofile_field1` text NOT NULL COMMENT '自定义字段1',
  `userprofile_field2` text NOT NULL COMMENT '自定义字段2',
  `userprofile_field3` text NOT NULL COMMENT '自定义字段3',
  `userprofile_field4` text NOT NULL COMMENT '自定义字段4',
  `userprofile_field5` text NOT NULL COMMENT '自定义字段5',
  `userprofile_field6` text NOT NULL COMMENT '自定义字段6',
  `userprofile_field7` text NOT NULL COMMENT '自定义字段7',
  `userprofile_field8` text NOT NULL COMMENT '自定义字段8',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_userprofile`
--

INSERT INTO `windsforce_userprofile` (`user_id`, `userprofile_realname`, `userprofile_gender`, `userprofile_birthyear`, `userprofile_birthmonth`, `userprofile_birthday`, `userprofile_constellation`, `userprofile_zodiac`, `userprofile_telephone`, `userprofile_mobile`, `userprofile_idcardtype`, `userprofile_idcard`, `userprofile_address`, `userprofile_zipcode`, `userprofile_nationality`, `userprofile_birthprovince`, `userprofile_birthcity`, `userprofile_birthdist`, `userprofile_birthcommunity`, `userprofile_resideprovince`, `userprofile_residecity`, `userprofile_residedist`, `userprofile_residecommunity`, `userprofile_residesuite`, `userprofile_graduateschool`, `userprofile_company`, `userprofile_education`, `userprofile_occupation`, `userprofile_position`, `userprofile_revenue`, `userprofile_affectivestatus`, `userprofile_lookingfor`, `userprofile_bloodtype`, `userprofile_height`, `userprofile_weight`, `userprofile_alipay`, `userprofile_icq`, `userprofile_qq`, `userprofile_yahoo`, `userprofile_msn`, `userprofile_taobao`, `userprofile_site`, `userprofile_bio`, `userprofile_interest`, `userprofile_google`, `userprofile_baidu`, `userprofile_renren`, `userprofile_douban`, `userprofile_facebook`, `userprofile_twriter`, `userprofile_dianniu`, `userprofile_skype`, `userprofile_weibocom`, `userprofile_tqqcom`, `userprofile_diandian`, `userprofile_kindergarten`, `userprofile_primary`, `userprofile_juniorhighschool`, `userprofile_highschool`, `userprofile_university`, `userprofile_master`, `userprofile_dr`, `userprofile_nowschool`, `userprofile_field1`, `userprofile_field2`, `userprofile_field3`, `userprofile_field4`, `userprofile_field5`, `userprofile_field6`, `userprofile_field7`, `userprofile_field8`) VALUES
(1, 'W先生', 1, 2012, 6, 14, '', '', '08188301355', '', '身份证', '', '', '', '', '', '凉山彝族自治州', '金阳县', '丝窝乡', '', '崇左市', '扶绥县', '', '', '', '', '本科', '秘书', '', '', '已婚', '交友', 'B', '', '', '', '', '63575056', '', '', '', 'http://baidu.com', '', '编程', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '博士', 'df', '我查', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_userprofilesetting`
--

CREATE TABLE IF NOT EXISTS `windsforce_userprofilesetting` (
  `userprofilesetting_id` varchar(255) NOT NULL DEFAULT '' COMMENT '个人信息字段名字',
  `userprofilesetting_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否启用属性字段',
  `userprofilesetting_title` varchar(255) NOT NULL DEFAULT '' COMMENT '个人信息标题',
  `userprofilesetting_description` varchar(255) NOT NULL DEFAULT '' COMMENT '个人信息描述',
  `userprofilesetting_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '个人信息排序',
  `userprofilesetting_showinfo` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示在个人信息中',
  `userprofilesetting_allowsearch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许搜索',
  `userprofilesetting_privacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '属性隐私 0公开，1好友可见，3保密',
  PRIMARY KEY (`userprofilesetting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `windsforce_userprofilesetting`
--

INSERT INTO `windsforce_userprofilesetting` (`userprofilesetting_id`, `userprofilesetting_status`, `userprofilesetting_title`, `userprofilesetting_description`, `userprofilesetting_sort`, `userprofilesetting_showinfo`, `userprofilesetting_allowsearch`, `userprofilesetting_privacy`) VALUES
('userprofile_realname', 1, '真实姓名', '', 0, 0, 1, 0),
('userprofile_gender', 1, '性别', '', 0, 0, 1, 0),
('userprofile_birthyear', 1, '出生年份', '', 0, 0, 1, 0),
('userprofile_birthmonth', 1, '出生月份', '', 0, 0, 0, 0),
('userprofile_birthday', 1, '生日', '', 0, 0, 0, 0),
('userprofile_constellation', 1, '星座', '星座(根据生日自动计算)', 0, 0, 0, 0),
('userprofile_zodiac', 1, '生肖', '生肖(根据生日自动计算)', 0, 0, 0, 0),
('userprofile_telephone', 1, '固定电话', '', 0, 0, 0, 0),
('userprofile_mobile', 1, '手机', '', 0, 0, 0, 0),
('userprofile_idcardtype', 1, '证件类型', '身份证 护照 驾驶证等', 0, 0, 0, 0),
('userprofile_idcard', 1, '证件号', '', 0, 0, 0, 0),
('userprofile_address', 1, '邮寄地址', '', 0, 0, 0, 0),
('userprofile_zipcode', 1, '邮编', '', 0, 0, 0, 0),
('userprofile_nationality', 1, '国籍', '', 0, 0, 0, 0),
('userprofile_birthprovince', 1, '出生省份', '', 0, 0, 0, 0),
('userprofile_birthcity', 1, '出生地', '', 0, 0, 0, 0),
('userprofile_birthdist', 1, '出生县', '出生行政区/县', 0, 0, 0, 0),
('userprofile_birthcommunity', 1, '出生小区', '', 0, 0, 0, 0),
('userprofile_resideprovince', 1, '居住省份', '', 0, 0, 0, 0),
('userprofile_residecity', 1, '居住地', '', 0, 0, 0, 0),
('userprofile_residedist', 1, '居住县', '居住行政区/县', 0, 0, 0, 0),
('userprofile_residecommunity', 1, '居住小区', '', 0, 0, 0, 0),
('userprofile_residesuite', 1, '房间', '小区、写字楼门牌号', 0, 0, 0, 0),
('userprofile_graduateschool', 1, '毕业学校', '', 0, 0, 0, 0),
('userprofile_education', 1, '学历', '', 0, 0, 0, 0),
('userprofile_company', 1, '公司', '', 0, 0, 0, 0),
('userprofile_occupation', 1, '职业', '', 0, 0, 0, 0),
('userprofile_position', 1, '职位', '', 0, 0, 0, 0),
('userprofile_revenue', 1, '年收入', '单位 元', 0, 0, 0, 0),
('userprofile_affectivestatus', 1, '情感状态', '', 0, 0, 0, 0),
('userprofile_lookingfor', 1, '交友目的', '希望在网站找到什么样的朋友', 0, 0, 0, 0),
('userprofile_bloodtype', 1, '血型', '', 0, 0, 0, 0),
('userprofile_height', 1, '身高', '单位 cm', 0, 0, 0, 0),
('userprofile_weight', 1, '体重', '单位 kg', 0, 0, 0, 0),
('userprofile_alipay', 1, '支付宝', '', 0, 0, 0, 0),
('userprofile_icq', 1, 'ICQ', '', 0, 0, 0, 0),
('userprofile_qq', 1, 'QQ', '', 0, 0, 0, 0),
('userprofile_yahoo', 1, 'YAHOO帐号', '', 0, 0, 0, 0),
('userprofile_msn', 1, 'MSN', '', 0, 0, 0, 0),
('userprofile_taobao', 1, '阿里旺旺', '', 0, 0, 0, 0),
('userprofile_site', 1, '个人主页', '', 0, 0, 0, 0),
('userprofile_bio', 1, '自我介绍', '', 0, 0, 0, 0),
('userprofile_interest', 1, '兴趣爱好', '', 0, 0, 0, 0),
('userprofile_field1', 0, '自定义字段1', '', 0, 0, 0, 0),
('userprofile_field2', 0, '自定义字段2', '', 0, 0, 0, 0),
('userprofile_field3', 0, '自定义字段3', '', 0, 0, 0, 0),
('userprofile_field4', 0, '自定义字段4', '', 0, 0, 0, 0),
('userprofile_field5', 0, '自定义字段5', '', 0, 0, 0, 0),
('userprofile_field6', 0, '自定义字段6', '', 0, 0, 0, 0),
('userprofile_field7', 0, '自定义字段7', '', 0, 0, 0, 0),
('userprofile_field8', 0, '自定义字段8', '', 0, 0, 0, 0),
('userprofile_google', 1, 'Google', '', 0, 0, 0, 0),
('userprofile_baidu', 1, '百度', '', 0, 0, 0, 0),
('userprofile_renren', 1, '人人', '', 0, 0, 0, 0),
('userprofile_douban', 1, '豆瓣', '', 0, 0, 0, 0),
('userprofile_facebook', 1, 'Facebook', '', 0, 0, 0, 0),
('userprofile_twriter', 1, 'TWriter', '', 0, 0, 0, 0),
('userprofile_dianniu', 1, '点牛', '', 0, 0, 0, 0),
('userprofile_skype', 1, 'Skype', '', 0, 0, 0, 0),
('userprofile_weibocom', 1, '新浪微博', '', 0, 0, 0, 0),
('userprofile_tqqcom', 1, '腾讯微博', '', 0, 0, 0, 0),
('userprofile_diandian', 1, '点点网', '', 0, 0, 0, 0),
('userprofile_kindergarten', 1, '幼儿园', '', 0, 0, 0, 0),
('userprofile_primary', 1, '小学', '', 0, 0, 0, 0),
('userprofile_juniorhighschool', 1, '初中', '', 0, 0, 0, 0),
('userprofile_highschool', 1, '高中', '', 0, 0, 0, 0),
('userprofile_university', 1, '大学', '', 0, 0, 0, 0),
('userprofile_master', 1, '硕士', '', 0, 0, 0, 0),
('userprofile_dr', 1, '博士', '', 0, 0, 0, 0),
('userprofile_nowschool', 1, '当前学校', '', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_userrole`
--

CREATE TABLE IF NOT EXISTS `windsforce_userrole` (
  `role_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `user_id` char(32) NOT NULL DEFAULT '' COMMENT '用户ID',
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
