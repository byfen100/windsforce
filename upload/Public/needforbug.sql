-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 12 月 05 日 05:13
-- 服务器版本: 5.5.22
-- PHP 版本: 5.4.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `needforbug`
--

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_access`
--

CREATE TABLE IF NOT EXISTS `needforbug_access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点ID',
  `access_level` tinyint(1) NOT NULL COMMENT '级别，1（应用），2（模块），3（方法）',
  `access_parentid` smallint(6) NOT NULL COMMENT '父级ID',
  `access_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  KEY `group_id` (`role_id`),
  KEY `node_id` (`node_id`),
  KEY `access_status` (`access_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_access`
--

INSERT INTO `needforbug_access` (`role_id`, `node_id`, `access_level`, `access_parentid`, `access_status`) VALUES
(3, 1, 1, 0, 1),
(3, 4, 2, 1, 1),
(3, 2, 2, 1, 1),
(3, 3, 2, 1, 1),
(3, 11, 3, 3, 1),
(1, 1, 1, 0, 1),
(1, 4, 2, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_adminctrlmenu`
--

CREATE TABLE IF NOT EXISTS `needforbug_adminctrlmenu` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- 转存表中的数据 `needforbug_adminctrlmenu`
--

INSERT INTO `needforbug_adminctrlmenu` (`adminctrlmenu_id`, `adminctrlmenu_internal`, `adminctrlmenu_title`, `adminctrlmenu_url`, `adminctrlmenu_status`, `adminctrlmenu_sort`, `adminctrlmenu_clicknum`, `user_id`, `adminctrlmenu_admin`, `create_dateline`) VALUES
(38, 1, '商城配置', 'c=app&a=config&id=51', 1, 0, 8, 1, 'admin', 1893477604);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_adminlog`
--

CREATE TABLE IF NOT EXISTS `needforbug_adminlog` (
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
-- 表的结构 `needforbug_adminmessage`
--

CREATE TABLE IF NOT EXISTS `needforbug_adminmessage` (
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
-- 表的结构 `needforbug_app`
--

CREATE TABLE IF NOT EXISTS `needforbug_app` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- 转存表中的数据 `needforbug_app`
--

INSERT INTO `needforbug_app` (`app_id`, `app_identifier`, `app_name`, `app_version`, `app_description`, `app_url`, `app_email`, `app_author`, `app_authorurl`, `app_isadmin`, `app_isinstall`, `app_isuninstall`, `app_issystem`, `app_isappnav`, `app_status`) VALUES
(43, 'group', '小组', '', '群组应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', '小牛哥Dyhb', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1),
(42, 'home', '个人空间', '1.0', '个人空间应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', '小牛哥Dyhb', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1),
(54, 'shop', '商城', '1.0', '商城应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', 'Dianniu Team', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_appeal`
--

CREATE TABLE IF NOT EXISTS `needforbug_appeal` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- 转存表中的数据 `needforbug_appeal`
--

INSERT INTO `needforbug_appeal` (`appeal_id`, `user_id`, `appeal_realname`, `appeal_address`, `appeal_idnumber`, `appeal_email`, `appeal_receiptnumber`, `create_dateline`, `update_dateline`, `appeal_status`, `appeal_progress`, `appeal_reason`) VALUES
(1, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '0000001544447865', 1345653039, 0, 1, 0, ''),
(2, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', 'F5Da8e44D4D5840Cac4a0CE707b40c1E', 1345653080, 0, 1, 0, ''),
(3, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '9A82AAAA16B96e136D691642AD34dfd1', 1345653087, 0, 1, 0, ''),
(4, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', 'eDf0eD0BA1F68721fE8BE60fe93F5e7A', 1345653942, 0, 1, 0, ''),
(5, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '20aEb9bCBE0121A224009A49170EAA42', 1345653956, 0, 1, 0, ''),
(6, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '99fB9C362D33178d1B0600CAbaAcA232', 1345654015, 0, 1, 0, ''),
(7, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '7FBC32747Fa63bD731766fcb3d5EBf9F', 1345654044, 0, 1, 0, ''),
(8, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '625A87C50D874D61D0cD4D4D426aF204', 1345654065, 0, 1, 0, ''),
(9, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '55f5B55BA7E4522d5FD099c096552A50', 1345654081, 0, 1, 0, ''),
(10, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '74b2A209432E1E916e60cb997DcabBeF', 1345654098, 0, 1, 0, ''),
(11, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '615976E760c30050760Bd63A56903C2c', 1345654136, 0, 1, 0, ''),
(12, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '56f3abf272dd77fE27365fcAE7EC65Ee', 1345654217, 0, 1, 0, ''),
(13, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '54885CcDcFb3823FefeAaeaa967a51dE', 1345654251, 0, 1, 0, ''),
(14, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', 'b888E04845597E4E954EE8a4AdF50997', 1345654409, 0, 1, 0, ''),
(15, 1, '小牛哥', '', '', 'xiaoniuge@dianniu.net', '45B082bC48DcC808A6DF6b67fbDFca62', 1345654412, 1347203552, 1, 2, ''),
(16, 17, '韦永明', '', '', 'kevin@dianniu.net', 'c59E75658f9fad6C6c90A9552EB261a6', 1345687426, 0, 1, 0, ''),
(17, 17, '韦永明', '', '', 'kevin@dianniu.net', 'E6Ed3971e673d43CC859Bb68B467BfF2', 1345688437, 0, 1, 0, ''),
(18, 17, '韦永明', '', '', 'kevin@dianniu.net', '467a88eDf9fCC796C7dd32C412b93CB0', 1345690274, 0, 1, 0, ''),
(19, 17, '韦永明', '', '', 'kevin@dianniu.net', '3e53E836c23bE6c6A48e20E3c17D05fb', 1345690954, 0, 1, 0, ''),
(20, 17, '韦永明', '', '', 'kevin@dianniu.net', 'D80C6F3225C8BDfe088108DdDC798444', 1345691013, 0, 1, 0, ''),
(21, 17, '韦永明', '', '', 'kevin@dianniu.net', 'Fc93df2341551d2BcfB07E5Bf0f3EE79', 1345691140, 0, 1, 0, ''),
(22, 17, '韦永明', '', '', 'kevin@dianniu.net', 'c3d3597848dEbEcFd0BB2EB2687821eA', 1345691339, 0, 1, 0, ''),
(23, 17, '韦永明', '', '', 'kevin@dianniu.net', 'c88CFf93Cb3Ef7e7cf72AEDF3872CEE6', 1345692375, 0, 1, 0, ''),
(24, 17, '韦永明', '', '', 'kevin@dianniu.net', 'B7b80DBb376334C98b51B5a4DD280DCe', 1345692429, 0, 1, 0, ''),
(25, 17, '韦永明', '', '', 'kevin@dianniu.net', '83334515eBEdE8fbBbAf9C3a0B8Dd7c2', 1345692455, 0, 1, 0, ''),
(26, 17, '韦永明', '', '', 'kevin@dianniu.net', '86F867618490f8Bd83c989a5cEaF81c1', 1345692549, 0, 1, 3, '最近有媒体报道，受传统产业出口不振、房地产宏观调控等因素影响，以前在春节前后才出现的农民工“返乡潮”，目前在多地出现；而上一次“返乡潮”是在受国际金融危机影响的2008年。\r\n\r\n 今年出现农民工“返乡潮”了吗？8月上旬，人民日报“求证”栏目记者在劳务输出大省河南、四川进行了调查。'),
(28, 17, '韦永明', '', '', 'kevin@dianniu.net', 'e44EC2108E01AE924CB3f0F06cC146B8', 1345693038, 0, 1, 0, ''),
(30, 1, '刘', '', '', 'xiaoniuge@dianniu.net', '3D0f2B6821a53bE0E3503CF6642C0951', 1345728080, 0, 1, 2, ''),
(31, 1, '刘', '', '', 'xiaoniuge@dianniu.net', 'EF240E0A26bfb0d9a6B5dd665B91ed54', 1345728099, 0, 1, 0, ''),
(32, 1, '刘', '', '', 'xiaoniuge@dianniu.net', '64b481646E4394169D690A66A1c0cc62', 1345728168, 0, 1, 0, ''),
(33, 1, '刘', '', '', 'xiaoniuge@dianniu.net', '89A4954B3EBe754fBb5AB94e6BEd7472', 1345728196, 0, 1, 3, '信息不祥'),
(34, 1, '刘', '', '', 'xiaoniuge@dianniu.net', 'd1AA2ee2208227B4B74d0Aa5302422d8', 1345728301, 1351217442, 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_attachment`
--

CREATE TABLE IF NOT EXISTS `needforbug_attachment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- 转存表中的数据 `needforbug_attachment`
--

INSERT INTO `needforbug_attachment` (`attachment_id`, `attachment_name`, `attachment_type`, `attachment_size`, `attachment_key`, `attachment_extension`, `attachment_savepath`, `attachment_savename`, `attachment_hash`, `attachment_module`, `attachment_isthumb`, `attachment_thumbprefix`, `attachment_thumbpath`, `create_dateline`, `update_dateline`, `attachmentcategory_id`, `attachment_description`, `attachment_alt`, `attachment_download`, `attachment_commentnum`, `attachment_islock`, `user_id`, `attachment_username`, `attachment_recommend`) VALUES
(1, 'logo.png', 'image/png', 10766, '0', 'png', 'day_20121005', '46be885a9b87d33833836fba7730bada.png', 'edabb478a077b7029e9f976d16ef3c38', 'attachment', 0, '', '', 1349421624, 1349421629, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(2, 'admin_logo.gif', 'image/gif', 3748, '0', 'gif', 'day_20121005', '1f266fb6645055e2a16605425057b1aa.gif', 'ecee6570f1c276ca5c369efe5b5ca54c', 'attachment', 0, '', '', 1349426159, 1349426164, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(3, '茶几3.jpg', 'image/jpeg', 895271, '0', 'jpg', 'day_20121005', '28668b08fcd8c9b52a1f7fb6b17498ea.jpg', '331dbeff93d8dd864c5c37021b170414', 'attachment', 0, '', '', 1349427260, 1349427266, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(4, '茶几4.jpg', 'image/jpeg', 844930, '0', 'jpg', 'day_20121006', '39285f65c00c5c121f55ec57274abb26.jpg', 'c04a5bd54946cae2651cb9645760b58b', 'attachment', 0, '', '', 1349490128, 1349490133, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(5, '茶几7.jpg', 'image/jpeg', 682004, '0', 'jpg', 'day_20121006', 'd6d0503b83bcee5cb18a28eda7da70a4.jpg', '3373eedd5f85876084c5f7af8bf9cca0', 'attachment', 0, '', '', 1349490291, 1349490351, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(6, '茶几4.jpg', 'image/jpeg', 844930, '0', 'jpg', 'day_20121006', 'dad3642108741f89878debdcb85b4d4b.jpg', 'c04a5bd54946cae2651cb9645760b58b', 'attachment', 0, '', '', 1349490441, 1349490455, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(7, '茶几7.jpg', 'image/jpeg', 682004, '0', 'jpg', 'day_20121006', '8c8aca988486260b92c70dae7251a47b.jpg', '3373eedd5f85876084c5f7af8bf9cca0', 'attachment', 0, '', '', 1349490465, 1349490477, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(8, '茶几6.jpg', 'image/jpeg', 670700, '1', 'jpg', 'day_20121006', 'ed069d7ddc01b97cf470973dec979ae4.jpg', '45b818b59d6b25405b9a66de5f40e62c', 'attachment', 0, '', '', 1349490465, 1349490477, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(9, '茶几7.jpg', 'image/jpeg', 682004, '0', 'jpg', 'day_20121006', '17cd801f4526af7754951fa62c14b0cc.jpg', '3373eedd5f85876084c5f7af8bf9cca0', 'attachment', 0, '', '', 1349491341, 1349491345, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(10, '茶几6.jpg', 'image/jpeg', 670700, '0', 'jpg', 'day_20121006', '038b87196dbdfaf29c8cce04b5baa0dc.jpg', '45b818b59d6b25405b9a66de5f40e62c', 'attachment', 0, '', '', 1349491358, 1349491362, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(11, '茶几8.jpg', 'image/jpeg', 737272, '1', 'jpg', 'day_20121006', '20d68b321fe6cfab5c60469e720780a2.jpg', 'e18090ff5e1ddf62c17812b31f51abf7', 'attachment', 0, '', '', 1349491358, 1349491362, 0, '', '', 1, 0, 0, 1, 'admin', 0),
(12, 'psb (1).jpg', 'application/octet-stream', 9211, '0', 'jpg', 'day_20121014', '8eeccf275e45e8becae5dce2105e37b6.jpg', '05948ed8cfa6254494adaa5e360baa01', 'attachment', 0, '', '', 1350183002, 1350183015, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(13, 'psb (2).jpg', 'application/octet-stream', 22092, '0', 'jpg', 'day_20121014', '8fa7dca14d6502b3d42f982c006fbdb4.jpg', 'f4efe3277c39ecd560408308294aa248', 'attachment', 0, '', '', 1350183002, 1350183015, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(14, 'psb (3).jpg', 'application/octet-stream', 21062, '0', 'jpg', 'day_20121014', '9f343dfb76b9688b9279d436dfb53c71.jpg', 'f10098cda8f34692bcaa9c6d2727c4fa', 'attachment', 0, '', '', 1350183003, 1350183015, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(15, 'psb (4).jpg', 'application/octet-stream', 10541, '0', 'jpg', 'day_20121014', '8aad42e6950295c61ec454f9581cb147.jpg', 'd29b3dfb5ff160dced91e228c351aa07', 'attachment', 0, '', '', 1350183003, 1350183015, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(16, 'psb (5).jpg', 'application/octet-stream', 9091, '0', 'jpg', 'day_20121014', '9daca2815b114897a1b1901ccf52f5b1.jpg', '0dbc65253ee436dcc6bfe31d8a201b12', 'attachment', 0, '', '', 1350183003, 1350183015, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(17, 'psb (6).jpg', 'application/octet-stream', 9959, '0', 'jpg', 'day_20121014', 'b1fbcdcf3152a68c215c7c6bb07e989c.jpg', '6c6d4d28ebc9f730b4adb1691cc11be1', 'attachment', 0, '', '', 1350183003, 1350183110, 2, '', '', 0, 0, 0, 1, 'admin', 1),
(18, 'psb (7).jpg', 'application/octet-stream', 10549, '0', 'jpg', 'day_20121014', 'eb56644db5b12e55181de428aa307174.jpg', 'd503cd1d96a27b3d755a43f7fb525432', 'attachment', 0, '', '', 1350183004, 1350183111, 2, '', '', 0, 0, 0, 1, 'admin', 1),
(19, 'psb (8).jpg', 'application/octet-stream', 9091, '0', 'jpg', 'day_20121014', '2b9aa6c4f75a2387096eceb8dc11a067.jpg', '0dbc65253ee436dcc6bfe31d8a201b12', 'attachment', 0, '', '', 1350183004, 1350183015, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(20, 'psb (9).jpg', 'application/octet-stream', 9959, '0', 'jpg', 'day_20121014', '9c0c9fa4ed34e5039d0ba69afbdb1093.jpg', '6c6d4d28ebc9f730b4adb1691cc11be1', 'attachment', 0, '', '', 1350183004, 1350183107, 2, '', '', 0, 0, 0, 1, 'admin', 1),
(21, 'psb (10).jpg', 'application/octet-stream', 10549, '0', 'jpg', 'day_20121014', 'ee829b9f41905cb93318bedde25fb2aa.jpg', 'd503cd1d96a27b3d755a43f7fb525432', 'attachment', 0, '', '', 1350183004, 1350183105, 2, '', '', 0, 0, 0, 1, 'admin', 1),
(22, 'psb (11).jpg', 'application/octet-stream', 125863, '0', 'jpg', 'day_20121014', '22a5adfc34f87303a83567251b914d3e.jpg', 'e8c2b100aee93be655b6b6266bb84de9', 'attachment', 0, '', '', 1350183005, 1350183098, 2, '', '', 0, 0, 0, 1, 'admin', 0),
(23, 'psb.jpg', 'application/octet-stream', 15927, '0', 'jpg', 'day_20121014', '314d13458bc6bc0a9e20753d6df175e0.jpg', 'e172a0cc790346279a9e65e0c125e0e7', 'attachment', 0, '', '', 1350183005, 1350183100, 2, '', '', 0, 0, 0, 1, 'admin', 1),
(24, '茶几5.jpg', 'application/octet-stream', 1123843, '0', 'jpg', 'day_20121016', '7e657f35c2e8a533a7bd252847b224bd.jpg', '2387e24efc31953181db538561380e4b', 'attachment', 0, '', '', 1350358803, 1350358812, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(25, '茶几6.jpg', 'application/octet-stream', 670700, '0', 'jpg', 'day_20121016', 'c943d0c3a35882714eb63a7b7376fd38.jpg', '45b818b59d6b25405b9a66de5f40e62c', 'attachment', 0, '', '', 1350358804, 1350358812, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(26, '茶几7.jpg', 'application/octet-stream', 682004, '0', 'jpg', 'day_20121016', 'e379c7a097b5a53f79fc01760820d636.jpg', '3373eedd5f85876084c5f7af8bf9cca0', 'attachment', 0, '', '', 1350358804, 1350358812, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(27, '茶几8.jpg', 'application/octet-stream', 737272, '0', 'jpg', 'day_20121016', '1edff910ea1bf98cc8902e01f1d86742.jpg', 'e18090ff5e1ddf62c17812b31f51abf7', 'attachment', 0, '', '', 1350358804, 1350358812, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(28, '茶几9.jpg', 'application/octet-stream', 658366, '0', 'jpg', 'day_20121016', 'be035631442b2ca5b38aa220e386f3be.jpg', '951c4257d0806130019e79195eefc3bb', 'attachment', 0, '', '', 1350358805, 1350358812, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(29, 'u=3910156037,3749334994&fm=25&gp=0.jpg', 'image/jpeg', 6792, '0', 'jpg', 'day_20121018', '4493c7a06c0a2925db018d7a1c90af77.jpg', '46abc7a357875022fc7e38d19f7f67f4', 'attachment', 0, '', '', 1350546280, 1350546352, 0, '', '', 1, 0, 0, 1, 'admin', 0),
(30, 'DoYouHaoBaby-2.1_Release20121023.zip', 'application/octet-stream', 1295347, '0', 'zip', 'day_20121024', '78f13af36b2b4f35c0ecd7f5ea4f3a97.zip', 'c6e9e3da35ac0cba3df24138c2caa5f6', 'attachment', 0, '', '', 1351065426, 0, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(31, '2012080616281377.jpg', 'application/octet-stream', 88345, '0', 'jpg', 'day_20121024', '71bc77ba703c9ab8463aec3b19568816.jpg', '642ee14dfe40ad322ea1b0d67d85c4b7', 'attachment', 0, '', '', 1351065534, 1351068939, 0, '', '', 1, 0, 0, 1, 'admin', 0),
(32, '茶几8.jpg', 'application/octet-stream', 737272, '0', 'jpg', 'day_20121024', '9aff36f49019cbd807ca5d96270c25a4.jpg', 'e18090ff5e1ddf62c17812b31f51abf7', 'attachment', 0, '', '', 1351070427, 0, 1, '', '', 0, 0, 0, 1, 'admin', 0),
(33, '茶几9.jpg', 'application/octet-stream', 658366, '0', 'jpg', 'day_20121024', '599d8121285b54da730ccd93d3f40267.jpg', '951c4257d0806130019e79195eefc3bb', 'attachment', 0, '', '', 1351070430, 1351071098, 1, '', '', 2, 0, 0, 1, 'admin', 1),
(34, 'DoYouHaoBaby-2.1_Release20121023.zip', 'application/octet-stream', 1295347, '0', 'zip', 'day_20121025', '27a175ca22d69cf6247fd6a6b95be8a9.zip', 'c6e9e3da35ac0cba3df24138c2caa5f6', 'attachment', 0, '', '', 1351156288, 0, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(35, 'DoYouHaoBaby-2.1_Release20121023.zip', 'application/octet-stream', 1295347, '0', 'zip', 'day_20121025', 'b428943f59ff90c324a15a28bd5f1b30.zip', 'c6e9e3da35ac0cba3df24138c2caa5f6', 'attachment', 0, '', '', 1351156558, 1351156699, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(36, '2012080616281377.jpg', 'application/octet-stream', 88345, '0', 'jpg', 'day_20121025', 'e24e257c7c3a03fd03b2d313544f877b.jpg', '642ee14dfe40ad322ea1b0d67d85c4b7', 'attachment', 0, '', '', 1351157041, 1351157072, 2, '', '', 1, 0, 0, 1, 'admin', 0),
(37, '2012080616281377.jpg', 'application/octet-stream', 88345, '0', 'jpg', 'day_20121025', 'e868e9bac107b40ea1f0f8d92d1c4e75.jpg', '642ee14dfe40ad322ea1b0d67d85c4b7', 'attachment', 0, '', '', 1351157114, 1351157119, 0, '', '', 3, 0, 0, 1, 'admin', 0),
(38, 'psu (7).jpg', 'application/octet-stream', 46581, '0', 'jpg', 'day_20121025', '040cfa4a9ab3ea39d44dce8392c5ba20.jpg', 'c880109724daaaf497403676aa1dc11a', 'attachment', 0, '', '', 1351157229, 1351157233, 1, '', '', 2, 0, 0, 1, 'admin', 0),
(39, 'psu.jpg', 'application/octet-stream', 49483, '0', 'jpg', 'day_20121025', '9ad5e804da2f52bf8a63e0fc54ea8c5b.jpg', 'b3fcebe34b44d2fae7ec4138aae5157f', 'attachment', 0, '', '', 1351157229, 1351157233, 1, '', '', 4, 0, 0, 1, 'admin', 0),
(40, 'DoYouHaoBaby-2.1_Release20121023.zip', 'application/octet-stream', 1295347, '0', 'zip', 'day_20121026', 'b7aef6474d19872d680e297ffe3c850f.zip', 'c6e9e3da35ac0cba3df24138c2caa5f6', 'attachment', 0, '', '', 1351218347, 1351218450, 0, '', '', 2, 0, 0, 1, 'admin', 0),
(41, '2012080616281377.jpg', 'image/jpeg', 88345, '0', 'jpg', 'day_20121026', 'cea5d70604fe0af4c35e1b1b81232250.jpg', '642ee14dfe40ad322ea1b0d67d85c4b7', 'attachment', 0, '', '', 1351232042, 1351232045, 0, '', '', 1, 0, 0, 1, 'admin', 0),
(42, '2012080616281377.jpg', 'image/jpeg', 88345, '0', 'jpg', 'day_20121026', 'aa99d7b6c605e989fc26cf5fcd483378.jpg', '642ee14dfe40ad322ea1b0d67d85c4b7', 'attachment', 0, '', '', 1351236947, 1351236950, 0, '', '', 1, 0, 0, 1, 'admin', 0),
(43, '231599201351234861.mp3', 'audio/mpeg', 3214174, '0', 'mp3', 'day_20121026', 'dbf08e8b9170be76e397a41d5069f964.mp3', 'ecddf0bd7d8390b2448f3371698adecf', 'attachment', 0, '', '', 1351238479, 1351238482, 0, '', '', 1, 0, 0, 1, 'admin', 0),
(44, '77609759.jpg', 'image/jpeg', 38025, '0', 'jpg', 'day_20121113', '9e333afb3f4089beb2e0be59ee06cf68.jpg', '6b79736522068ed75989fecb8b3a6b5d', 'attachment', 0, '', '', 1352790147, 1352790150, 0, '', '', 0, 0, 0, 1, 'admin', 0),
(45, '2009-08-30_205316.jpg', 'image/jpeg', 14419, '1', 'jpg', 'day_20121113', '5f436727ab40c112c7c8fcdffab6a1f2.jpg', 'e61d21beb98b1740df12b9a297fc19d3', 'attachment', 0, '', '', 1352790147, 1352790150, 0, '', '', 1, 0, 0, 1, 'admin', 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_attachmentcategory`
--

CREATE TABLE IF NOT EXISTS `needforbug_attachmentcategory` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `needforbug_attachmentcategory`
--

INSERT INTO `needforbug_attachmentcategory` (`attachmentcategory_id`, `attachmentcategory_name`, `attachmentcategory_cover`, `attachmentcategory_compositor`, `attachmentcategory_description`, `attachmentcategory_attachmentnum`, `create_dateline`, `update_dateline`, `user_id`, `attachmentcategory_username`, `attachmentcategory_recommend`) VALUES
(1, '测试专辑fsdf', 0, 0, '', 0, 1349490091, 1351157967, 1, 'admin', 1),
(2, '不错哈可以', 0, 0, '', 0, 1350182975, 1351157962, 1, 'admin', 1),
(5, 'df', 0, 0, 'ffffffff', 0, 1351158299, 0, 1, 'admin', 0),
(6, 'df', 0, 0, 'ffffffff', 0, 1351158347, 0, 1, 'admin', 0),
(7, 'df', 0, 0, 'ffffffff', 0, 1351158395, 0, 1, 'admin', 0),
(8, 'fffffffffffff', 0, 0, 'dsf', 0, 1351158404, 0, 1, 'admin', 0),
(9, '', 0, 0, '', 0, 1351158409, 0, 1, 'admin', 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_badword`
--

CREATE TABLE IF NOT EXISTS `needforbug_badword` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `needforbug_badword`
--

INSERT INTO `needforbug_badword` (`badword_id`, `badword_admin`, `badword_find`, `badword_replacement`, `badword_findpattern`, `create_dateline`, `update_dateline`) VALUES
(1, 'admin', 'toobad', '*rt', '/toobad/is', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_creditrule`
--

CREATE TABLE IF NOT EXISTS `needforbug_creditrule` (
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
-- 转存表中的数据 `needforbug_creditrule`
--

INSERT INTO `needforbug_creditrule` (`creditrule_id`, `creditrule_name`, `creditrule_action`, `creditrule_cycletype`, `creditrule_cycletime`, `creditrule_rewardnum`, `creditrule_extendcredit1`, `creditrule_extendcredit2`, `creditrule_extendcredit3`, `creditrule_extendcredit4`, `creditrule_extendcredit5`, `creditrule_extendcredit6`, `creditrule_extendcredit7`, `creditrule_extendcredit8`) VALUES
(1, '发短消息', 'sendpm', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, '访问推广', 'promotion_visit', 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0),
(3, '注册推广', 'promotion_register', 0, 0, 0, 2, 0, 0, 0, 0, 0, 0, 0),
(4, '设置头像', 'setavatar', 0, 0, 1, 5, 0, 0, 0, 0, 0, 0, 0),
(5, '每天登录', 'daylogin', 1, 0, 1, 2, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_creditrulelog`
--

CREATE TABLE IF NOT EXISTS `needforbug_creditrulelog` (
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
-- 转存表中的数据 `needforbug_creditrulelog`
--

INSERT INTO `needforbug_creditrulelog` (`creditrulelog_id`, `user_id`, `creditrule_id`, `creditrulelog_total`, `creditrulelog_cyclenum`, `creditrulelog_extendcredit1`, `creditrulelog_extendcredit2`, `creditrulelog_extendcredit3`, `creditrulelog_extendcredit4`, `creditrulelog_extendcredit5`, `creditrulelog_extendcredit6`, `creditrulelog_extendcredit7`, `creditrulelog_extendcredit8`, `creditrulelog_starttime`, `update_dateline`) VALUES
(38, 6, 5, 10, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1352347371),
(37, 1, 5, 372, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1893477565),
(39, 17, 5, 1, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1345623288),
(40, 26, 5, 1, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1345726697),
(41, 18, 5, 2, 1, 0, 2, 0, 0, 0, 0, 0, 0, 0, 1347787470),
(42, 40, 5, 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1352881137),
(43, 43, 5, 1, 1, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1354089728);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_feed`
--

CREATE TABLE IF NOT EXISTS `needforbug_feed` (
  `feed_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `feed_username` varchar(50) NOT NULL COMMENT '用户名',
  `feed_template` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态模板',
  `feed_data` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态数据',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`feed_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `needforbug_feed`
--

INSERT INTO `needforbug_feed` (`feed_id`, `user_id`, `feed_username`, `feed_template`, `feed_data`, `create_dateline`) VALUES
(5, 6, 'xiaomage', '<div class="feed_addhomefresh"><span class="feed_title">发布了一条新鲜事&nbsp;(<a href="{@homefresh_link}">查看</a>)</span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:2:{s:15:"@homefresh_link";s:20:"home://fresh@?id=218";s:17:"homefresh_message";s:33:"sdf士大夫似的都是士大夫";}', 1352347385),
(3, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">发布了一条新鲜事&nbsp;(<a href="{@homefresh_link}">查看</a>)</span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:2:{s:15:"@homefresh_link";s:20:"home://fresh@?id=216";s:17:"homefresh_message";s:15:"在测试一下";}', 1352347129),
(6, 6, 'xiaomage', '<div class="feed_addhomefresh"><span class="feed_title">发布了一条新鲜事&nbsp;(<a href="{@homefresh_link}">查看</a>)</span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:2:{s:15:"@homefresh_link";s:20:"home://fresh@?id=219";s:17:"homefresh_message";s:23:"df士大夫似的商店";}', 1352347407),
(7, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">发布了一条新鲜事&nbsp;(<a href="{@homefresh_link}">查看</a>)</span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:2:{s:15:"@homefresh_link";s:20:"home://fresh@?id=220";s:17:"homefresh_message";s:18:"不错哈，哈哈";}', 1352444868),
(8, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">发布了一条新鲜事&nbsp;<a href="{@homefresh_link}">查看</a></span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:2:{s:15:"@homefresh_link";s:20:"home://fresh@?id=221";s:17:"homefresh_message";s:34:"技术还是十分领先的,呵呵";}', 1352445908),
(9, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_link}">{homefresh_title}</a></span><div class="feed_content">{homefresh_commentmessage}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:3:{s:15:"@homefresh_link";s:17:"home://fresh@?id=";s:15:"homefresh_title";s:0:"";s:24:"homefresh_commentmessage";s:27:"提示，呵呵，可以额";}', 1352447063),
(10, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_link}">{homefresh_title}</a></span><div class="feed_content">{homefresh_commentmessage}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:3:{s:15:"@homefresh_link";s:17:"home://fresh@?id=";s:15:"homefresh_title";s:0:"";s:24:"homefresh_commentmessage";s:21:"欢迎打击假广陵";}', 1352447144),
(11, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_link}">{homefresh_title}</a></span><div class="feed_content">{homefresh_commentmessage}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:3:{s:15:"@homefresh_link";s:20:"home://fresh@?id=221";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:27:"新鲜事评论了，呵呵";}', 1352447318),
(12, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content">{homefresh_commentmessage}</div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=279";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:12:"急促加油";}', 1352447474),
(13, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=280";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:39:"亲爱的你还是那样，我非常。";}', 1352447685),
(14, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=281";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:9:"士大夫";}', 1352798542),
(15, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=282";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:18:"士大夫士大夫";}', 1352798553),
(16, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=283";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:39:"速度大大大大放松放松士大夫";}', 1352798562),
(17, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=284";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:21:"士大夫商店都是";}', 1352798572),
(18, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=285";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:33:"士大夫士大夫大师傅商店";}', 1352798583),
(19, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=286";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:57:"士大夫似的大师傅大师傅士大夫士大夫商店";}', 1352798599),
(21, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">发布了一条新鲜事&nbsp;<a href="{@homefresh_link}">查看</a></span><div class="feed_content">{homefresh_message}</div><div class="feed_action"><a href="{@homefresh_link}#comments">回复</a></div></div>', 'a:2:{s:15:"@homefresh_link";s:20:"home://fresh@?id=223";s:17:"homefresh_message";s:42:"谢谢.[attachment]45[/attachment]哈[汗]";}', 1353314356),
(22, 1, 'admin', '<div class="feed_addhomefresh"><span class="feed_title">评论了新鲜事&nbsp;<a href="{@homefresh_commentlink}">{homefresh_title}</a></span><div class="feed_content"><div class="feed_quote"><span class="feed_quoteinfo">{homefresh_commentmessage}</span></div></div><div class="feed_action"><a href="{@homefresh_commentlink}">回复</a></div></div>', 'a:3:{s:22:"@homefresh_commentlink";s:44:"home://fresh@?id=221&isolation_commentid=287";s:15:"homefresh_title";s:34:"技术还是十分领先的,呵呵";s:24:"homefresh_commentmessage";s:17:"sdfsd鼎折覆餗";}', 1354526716);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_friend`
--

CREATE TABLE IF NOT EXISTS `needforbug_friend` (
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

--
-- 转存表中的数据 `needforbug_friend`
--

INSERT INTO `needforbug_friend` (`user_id`, `friend_friendid`, `friend_direction`, `friend_status`, `friend_comment`, `friend_fancomment`, `create_dateline`, `friend_username`, `friend_friendusername`) VALUES
(6, 7, 1, 1, '', '', 1345511948, 'xiaomage', 'test'),
(6, 1, 1, 0, '', '', 1345511945, 'xiaomage', 'admin'),
(1, 13, 1, 1, '', '', 1345511770, 'admin', 'sdfsdfsdfsdfs'),
(1, 16, 1, 1, '', '', 1345511759, 'admin', 'woshihapi'),
(1, 15, 1, 1, '', '', 1345511757, 'admin', 'my'),
(1, 18, 1, 1, '', '', 1345511755, 'admin', 'weiyong2000'),
(1, 17, 1, 1, '', '', 1345511754, 'admin', 'weiyong1999'),
(1, 20, 1, 1, '', '', 1345511752, 'admin', 'xiaoxiaoxiao2'),
(1, 19, 1, 1, '', '', 1345511750, 'admin', 'xiaoxiaoxiao'),
(1, 21, 1, 1, '', '', 1345511749, 'admin', 'sdfffffff'),
(1, 22, 1, 1, '', '', 1345511747, 'admin', 'sfdddddddddddd'),
(1, 23, 1, 1, '', '', 1345511746, 'admin', 'dsfsfsfsdf'),
(1, 6, 1, 1, '', '', 1345511653, 'admin', 'xiaomage'),
(1, 11, 1, 1, '', '', 1350892973, 'admin', 'sdfsdfsdfsdfs');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_group`
--

CREATE TABLE IF NOT EXISTS `needforbug_group` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `needforbug_group`
--

INSERT INTO `needforbug_group` (`group_id`, `user_id`, `group_name`, `group_nikename`, `group_sort`, `group_description`, `group_listdescription`, `group_path`, `group_icon`, `group_topicnum`, `group_topictodaynum`, `group_usernum`, `group_topiccomment`, `group_joinway`, `group_roleleader`, `group_roleadmin`, `group_roleuser`, `create_dateline`, `group_isrecommend`, `group_isopen`, `group_isaudit`, `group_ispost`, `group_status`, `group_latestcomment`, `update_dateline`) VALUES
(1, 1, 'needforbug', 'NeedForBug', 0, '<span style="white-space:nowrap;"><strong>在您发表主题前，请先阅读以下规则</strong></span><br />\n<span style="white-space:nowrap;">1. 本版仅处理Needforbug!标准程序的安装使用/前后台故障问题，不包括程序修改、模板美化的咨询。</span><br />\n<span style="white-space:nowrap;">2. 用户的站点一旦去除或修改了Needforbug!版权信息，将不给予任何技术支持，并删帖。</span><br />\n<span style="white-space:nowrap;color:#E53333;">3. 请大家文明用语 谦虚求教，对任何语言出位 态度恶劣的用户，版主将不经过任何警告直接进行禁言处理 请注意！</span><br />\n<span style="white-space:nowrap;color:#E53333;">4. 本版只允许发布Needforbug!安装使用类问题，禁止发布与Needforbug!安装使用版块无关内容，如果发现一律删除，情节严重者禁言账号，请注意！</span><br />\n<span style="white-space:nowrap;color:#E53333;">5. 本版版主会不定时搜索关鍵字进行管理，如果发现一律删除/屏蔽，情节严重者禁言账号，请注意！</span><br />', 'NeedForBug官方开发小组，请期待', '', NULL, 31, 0, 33, 68, 0, '组长', '管理员', '成员', 1009885873, 1, 1, 1, 0, 1, 'a:5:{s:11:"commenttime";i:1353308417;s:9:"commentid";i:74;s:3:"tid";s:1:"2";s:13:"commentuserid";s:1:"1";s:12:"commenttitle";s:36:"日大臣称日历史教育有缺陷";}', 1353308417),
(6, 1, '', 'dfsdf', 0, 'dsf', 'dsffsd', '', 'day_20121108/group_43_icon.jpg', 0, 0, 0, 0, 0, '组长', '管理员', '成员', 1351138520, 1, 1, 1, 0, 1, '', 1352355990),
(2, 1, 'needforbug_resource', '资源分享', 0, '资源分享', 'sdfffffffffffffff', '', NULL, 1, 0, 0, 1, 0, '组长', '管理员', '成员', 1009885952, 1, 1, 1, 1, 1, 'a:5:{s:11:"commenttime";i:1353306473;s:9:"commentid";i:73;s:3:"tid";s:2:"35";s:13:"commentuserid";s:1:"1";s:12:"commenttitle";s:12:"DDDDDDDDDDDD";}', 1353306473),
(3, 1, 'needforbug_webmaster', '站长大本营', 0, '站长讨论区', 'fsdddddddddddddddd', '', NULL, 0, 0, 0, 0, 0, '组长', '管理员', '成员', 1009886045, 1, 1, 1, 1, 1, '', 0),
(4, 1, 'needforbug_app', 'App开发', 0, '应用开发指导小组', '盛大啊啊啊啊阿斯达', '', NULL, 0, 0, 0, 0, 0, '组长', '管理员', '成员', 1009886129, 1, 1, 1, 1, 1, '', 0),
(5, 1, 'needforbug_theme', '风格模板组', 0, '主题开发', 'sfddddddddddddd', '', NULL, 0, 0, 0, 0, 0, '组长', '管理员', '成员', 1009886255, 1, 1, 1, 1, 1, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupcategory`
--

CREATE TABLE IF NOT EXISTS `needforbug_groupcategory` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `needforbug_groupcategory`
--

INSERT INTO `needforbug_groupcategory` (`groupcategory_id`, `groupcategory_name`, `groupcategory_parentid`, `groupcategory_count`, `groupcategory_sort`, `update_dateline`, `create_dateline`) VALUES
(1, '官方小组', 0, 6, 0, 1351138520, 1009884694),
(2, 'DianNiu 动态', 1, 0, 0, 0, 1009884789),
(3, 'NeedForBug 交流与讨论', 1, 0, 0, 0, 1009884822),
(4, '科技', 0, 0, 0, 0, 1009884984),
(5, '人文', 0, 0, 0, 0, 1009884990),
(6, '娱乐', 0, 0, 0, 0, 1009885003);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupcategoryindex`
--

CREATE TABLE IF NOT EXISTS `needforbug_groupcategoryindex` (
  `group_id` int(10) NOT NULL COMMENT '群组ID',
  `groupcategory_id` int(10) NOT NULL COMMENT '群组分类ID',
  PRIMARY KEY (`group_id`,`groupcategory_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_groupcategoryindex`
--

INSERT INTO `needforbug_groupcategoryindex` (`group_id`, `groupcategory_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupfeed`
--

CREATE TABLE IF NOT EXISTS `needforbug_groupfeed` (
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
-- 表的结构 `needforbug_groupoption`
--

CREATE TABLE IF NOT EXISTS `needforbug_groupoption` (
  `groupoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `groupoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`groupoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_groupoption`
--

INSERT INTO `needforbug_groupoption` (`groupoption_name`, `groupoption_value`) VALUES
('group_isaudit', '0'),
('group_icon_uploadfile_maxsize', '204800'),
('group_indextopicnum', '10'),
('group_listtopicnum', '10'),
('group_hottopic_date', '604800'),
('group_hottopic_num', '10');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_grouptopic`
--

CREATE TABLE IF NOT EXISTS `needforbug_grouptopic` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `needforbug_grouptopic`
--

INSERT INTO `needforbug_grouptopic` (`grouptopic_id`, `grouptopiccategory_id`, `group_id`, `user_id`, `grouptopic_username`, `grouptopic_title`, `grouptopic_content`, `grouptopic_comments`, `grouptopic_views`, `grouptopic_loves`, `grouptopic_sticktopic`, `grouptopic_status`, `grouptopic_isclose`, `grouptopic_color`, `grouptopic_iscomment`, `grouptopic_addtodigest`, `grouptopic_isaudit`, `grouptopic_allownoticeauthor`, `grouptopic_ordertype`, `grouptopic_isanonymous`, `grouptopic_usesig`, `grouptopic_smileoff`, `grouptopic_parseurloff`, `grouptopic_hiddenreplies`, `grouptopic_readperm`, `grouptopic_price`, `grouptopic_latestcomment`, `grouptopic_updateusername`, `create_dateline`, `update_dateline`) VALUES
(1, 0, 1, 1, 'admin', '李稻葵称价格是协调机制 假日高速公路应涨价', '<p style="text-indent:2em;">\n	中广网北京10月1日消息(记者潘毅)据中国之声《央广新闻》报道，首个节假日高速免费，拥堵现象困扰出行车主。有专家建议：平时减免，节假日收费甚至提高收费能够有效缓解拥堵。\n</p>\n<p style="text-indent:2em;">\n	这\n两天微博上流传这么一个玩笑——中秋、国庆双节最高人气的景点在哪里，答案是高速路。昨天是中秋、国庆连休长假第一天，也是国家实行重大节假日期间7座及\n以下小型客车免费通行的第一天，高速免费不仅让驾车出行省了一笔过路费，也免去了沿途排队缴费的麻烦，但是在新政第一天便遭遇了一个严重挑战，全国多条高\n速公路出现了一个严重拥堵的情况，因此关于高速公路免费政策争议也随之而起，不少人开始质疑，拥堵之痛是否源于高速公路免费通行。\n</p>\n<p style="text-indent:2em;">\n	经济学家李稻葵(<span class="infoMblog"><a target="_blank" href="http://t.qq.com/lidaokui#pref=qqcom.keyword" class="a-tips-Article-QQ" rel="lidaokui">微博</a></span>)认为，价格是社会协调机制，选择在节假日这样的出行高峰降低价格必然会造成需求的上涨。他建议，在节假日的时候提高高速公路通行价格。李稻葵的说法得到包括郑渊洁等不少人的支持。\n</p>\n<p style="text-indent:2em;">\n	但\n是也有人认为，今天高速公路的拥堵现象得到了缓解，虽然不少地方只是缓慢通行，但是至少没有昨天那么寸步难行。今天记者联系北京杜家坎收费站负责同志了解\n到，昨天一天车流量比往常多了50%，但是今天到现在为止并未出现拥堵。出京方向虽然行驶缓慢，但是并没有一个拥堵，进京方向行驶还比较顺畅。\n</p>\n<p style="text-indent:2em;">\n	经济学家马光远认为，今天这个现象出现好转，其实有事故的解决，人工发卡这样的一些措施。他认为，今天只是个案，不能作为常态的现象。不少人由于受到昨天拥堵的影响，选择在今天错峰出行或者改日出行。\n</p>', 1, 12, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:3:{s:11:"commenttime";i:1349333517;s:9:"commentid";i:27;s:15:"commentusername";s:5:"admin";}', '', 1349151201, 1349333517),
(2, 0, 1, 1, 'admin', '日大臣称日历史教育有缺陷', '<p style="text-indent:2em;">\n	新华网东京10月1日电（记者郭一娜）新任日本文部科学大臣田中真纪子1日晚在东京表示，日本的历史教育存在缺陷，应将全面的历史事实告诉国民。\n</p>\n<p style="text-indent:2em;">\n	田中当天在首相官邸举行的新一届内阁就职记者会上说，根据文部省使用的现有教育指导纲要，历史教育中对古代史记述十分详尽，但近现代史内容却相对单薄，“我一直对此存有疑问，认为这样的教育存在问题”。\n</p>\n<p style="text-indent:2em;">\n	她认为，尽管日本国内出版社、政治家对历史教育持不同看法，但应将全面的历史事实告诉日本的下一代，由国民来作出思考和判断。否则，下一代就无法形成明确的历史观，日本人在国际社会获取话语权也就无从谈起。\n</p>\n日本首相野田佳彦当天对内阁进行了第三次改组，田中在新内阁中出任文部科学大臣。田中是日本前首相田中角荣之女，曾任日本外相、科学技术厅长官等要职。', 4, 57, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1353308417;s:9:"commentid";i:74;s:3:"tid";s:1:"2";s:13:"commentuserid";s:1:"1";}', '', 1349151313, 1349333526),
(3, 0, 1, 1, 'admin', '除ts外，你还知道哪些免费又做得不错的免费开源系统？', '除ts外，你还知道哪些免费又做得不错的免费开源系统？<br />\n或者一些设计不错的网站？<br />\n推荐一下', 10, 324, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1350402617;s:9:"commentid";i:51;s:3:"tid";s:1:"3";s:13:"commentuserid";s:1:"6";}', '', 1349155910, 1350402617),
(4, 0, 1, 1, 'admin', '', '', 10, 170, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1349156743, 0),
(5, 0, 1, 1, 'admin', '环卫工路边打点滴 网友称再不好意思乱丢垃圾测试主题限制的长度测试主题限制的长度测试主题限制的长度测试主题限制的长度测试主题限制的', '<p style="text-indent:2em;">\n	<span style="font-size:12px;">10月1日，长沙市韶山北路，刘永亮正在打点滴，点滴瓶就挂在了树干上。 图/网友“vj杨涛”</span> \n</p>\n<p style="text-indent:2em;">\n	本报长沙讯 10月1日下午4点，网友“vj杨涛”路过长沙韶山北路省委招待所附近，看到路边有位环卫工大叔坐着休息。走近时发现，大叔是在打点滴，点滴瓶就挂在了树干上。看到这一幕，“vj杨涛”很感动，随即发了条微博说：“向所有环卫工人致敬。”\n</p>\n<p style="text-indent:2em;">\n	在这位网友手机拍下的图片中，环卫工大叔看上去年纪挺大了，皮肤黝黑，眼神里流露出疲惫，穿蓝色布裤子和旧皮鞋。他手背上贴着好几条胶带，顺着滴管往树上看，是两个点滴瓶。在这片树荫下，大叔总算可以躲开烈日，稍微休息会儿。\n</p>\n<p style="text-indent:2em;">\n	虽然只是路过匆匆一瞥，网友“vj杨涛”被感动了。他拨打了<span class="infoMblog"><a class="a-tips-Article-QQ" href="http://t.qq.com/xxcbgd#pref=qqcom.keyword" rel="xxcbgd" target="_blank">潇湘晨报</a></span>热线96360：“我们在过节，大叔却在辛苦工作，还带着病，真不容易！”\n</p>\n<p style="text-indent:2em;">\n	他还记得，曾经有好几次，清早五六点路过同样地段，也能看到这位环卫工挥动笤帚的身影，“刷刷刷”的声音叫人难忘。还有一次，他大年初一凌晨5点多出门赶高铁，另一位女环卫工不小心把脏水扫到他身上，赶紧向他道歉，“她都那么辛苦了，我怎么好意思责怪呢。”\n</p>\n<p style="text-indent:2em;">\n	随后，记者联系到了图片中的大叔。他叫刘永亮，是衡阳西渡人，独自一人来长沙当环卫工差不多5年了，曾负责过五一大道等路段。实际上，他今年只有46岁，但皱纹已爬满了额头。\n</p>\n<p style="text-indent:2em;">\n	三\n四天前，刘永亮已经开始感冒、头痛，一直强忍着。到10月1日，终于他有点撑不住，到诊所开了点滴。他说，去医院看病要花好多钱，请假也会影响收入，所以\n只能边打点滴边工作。目前，他的月收入是1200元，租房子需要450元，再除去吃喝、零用，剩不下多少钱，所以一定不能请假、早退。\n</p>\n<p style="text-indent:2em;">\n	但为了多赚点，他10月1日还是打算多上一个晚班。也就是说，从中午12点到晚上12点，他一直要清扫韶山路、八一路交界处的路面垃圾，当中只有1个小时吃饭时间。\n</p>\n网友“vj杨涛”说，看到这位环卫工之后，大概谁都不好意思乱丢垃圾了，再破坏市容都会觉得惭愧。记者张莹 实习生邹晨莹', 23, 249, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1350402281;s:9:"commentid";i:50;s:3:"tid";s:1:"5";s:13:"commentuserid";s:1:"1";}', '', 1349162966, 1350402281),
(6, 4, 1, 1, 'admin', '分类测试', '的飞洒发撒的发生', 2, 6, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:3:{s:11:"commenttime";i:1349573443;s:9:"commentid";i:43;s:15:"commentusername";s:5:"admin";}', '', 1349408433, 1349573443),
(7, 3, 1, 1, 'admin', 'asdf', '<p>\n	sdfsdf\n</p>\n<p>\n	是打发斯蒂芬水电费水电费\n</p>\n<p>\n	<br />\n</p>\n<p>\n	[attachment]9[/attachment]\n</p>\n<p>\n	收到房顶上\n</p>\n<p>\n	[attachment]8[/attachment]\n</p>\n<p>\n	水电费\n</p>\n<p>\n	[attachment]6[/attachment]\n</p>\n<p>\n	水电费\n</p>\n<p>\n	[attachment]7[/attachment]\n</p>\n<p>\n	萨达速度阿斯达按时\n</p>', 2, 4, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:3:{s:11:"commenttime";i:1350208914;s:9:"commentid";i:45;s:15:"commentusername";s:5:"admin";}', '', 1350208734, 1350208914),
(8, 0, 1, 1, 'admin', '是打发斯蒂芬', '水电费收到[attachment]28[/attachment][attachment]27[/attachment][attachment]26[/attachment][attachment]25[/attachment]', 0, 7, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350358841, 0),
(9, 0, 0, 1, 'admin', 'rew', 'wwwwwwwwww', 0, 0, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350463167, 0),
(10, 0, 0, 1, 'admin', 'rew', 'wwwwwwwwww', 0, 5, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350463171, 0),
(11, 0, 1, 1, 'admin', '', 'erwwwwwww', 0, 1, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350463178, 0),
(12, 0, 1, 1, 'admin', 'dfs', 'sssssssss', 1, 4, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1350463194;s:9:"commentid";i:52;s:3:"tid";s:2:"12";s:13:"commentuserid";s:1:"1";}', '', 1350463190, 1350463194),
(13, 0, 1, 1, 'admin', 'y', 'yyyyyyyyyyyy', 0, 1, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350463354, 0),
(14, 0, 1, 1, 'admin', '67', '7777777777', 4, 8, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1350463810;s:9:"commentid";i:56;s:3:"tid";s:2:"14";s:13:"commentuserid";s:1:"1";}', '', 1350463783, 1350463810),
(15, 0, 1, 1, 'admin', 'sdf', 'ffffffffffffffff\n<pre class="prettyprint lang-php">&lt;php\necho ''Hello world'';\n?&gt;</pre>', 0, 4, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350531487, 0),
(16, 0, 1, 1, 'admin', '第三方asd', 'dssa\n<pre class="brush:php;">&lt;?php\necho ''Hello world'';\n?&gt;</pre>', 0, 3, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350532894, 0),
(17, 0, 1, 1, 'admin', 'dfs', '<p>\n	sssssssssss\n</p>\n<p>\n	<br />\n</p>\n<pre class="brush:html;">&lt;avatar&gt;\n												&lt;a href="&lt;!--{:Dyhb::U(''home://space@?id=''.$oComment[''user_id''])}--&gt;" target="_blank"&gt;\n													&lt;img src="&lt;!--{:Core_Extend::avatar($oComment[''user_id''],''middle'')}--&gt;" width="120px" alt="&lt;!--{$oComment-&gt;user-&gt;user_name}--&gt;" class="group_avatar"&gt;\n												&lt;/a&gt;\n&lt;/avatar&gt;</pre>\n<p>\n	<br />\n</p>', 0, 3, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350538883, 0),
(18, 0, 1, 1, 'admin', 'sd受到反复反复发水电费', '<p>\n	控制器代码如下：\n</p>\n<p>\n	水电费\n</p>\n<pre class="brush:php;"><!--?php\n/* [NeedForBug!] (C)Dianniu From 2010.\n   Needforbug 主页入口文件($)*/\n\n/** 是否需要安装 */\nif(!file_exists(''data/Install.lock.php'')){\n	header("location:install/index.php");\n}\n\nerror_reporting(E_ERROR|E_PARSE|E_STRICT);\n//error_reporting(E_ALL);\n//define(''NEEDFORBUG_DEBUG'',TRUE);\n\n/** Defined the version of needforbug */\ndefine(''NEEDFORBUG_SERVER_VERSION'',''1.0'');\ndefine(''NEEDFORBUG_SERVER_RELEASE'',''20120104'');\n\n/** 系统应用路径定义 */\ndefine(''NEEDFORBUG_PATH'',getcwd());\n\n/** 应用路径解析，还可以加强 */\nif(isset($_GET[''app''])){\n	$sAppName=strtolower(str_replace(array(''/'',''\\\\''),'''',strip_tags(urldecode($_GET[''app'']))));\n}else{\n	if(!empty($_SERVER[''PATH_INFO''])){\n		$arrPathinfos=explode(''/'',trim($_SERVER[''PATH_INFO''],''/''));\n\n		if(isset($arrPathinfos[1]) && $arrPathinfos[0]==''app''){\n			$sAppName=$arrPathinfos[1];\n		}else{\n			$sAppName=''home'';\n		}\n	}else{\n		$sAppName=''home'';\n	}\n}\n\n/** 项目及项目路径定义 */\ndefine(''APP_NAME'',$sAppName);\ndefine(''APP_PATH'',NEEDFORBUG_PATH.''/app/''.APP_NAME);\n\n/** 项目运行时路径及数据库表缓存路径 */\ndefine(''APP_RUNTIME_PATH'',NEEDFORBUG_PATH.''/data/~runtime/''.APP_NAME);\ndefine(''DB_META_CACHED_PATH'',NEEDFORBUG_PATH.''/data/~runtime/cache_'');\n\n/** 项目语言包路径定义 */\ndefine(''__COMMON_LANG__'',NEEDFORBUG_PATH.''/ucontent/language'');\n\n/** 项目模板路径定义 */\ndefine(''__STATICS__'',''app/''.APP_NAME.''/Static'');\ndefine(''__THEMES__'',''app/''.APP_NAME.''/Theme'');\n\n/** 项目编译锁定文件定义 */\ndefine(''APP_RUNTIME_LOCK'',NEEDFORBUG_PATH.''/source/protected/~Runtime.inc.lock'');\n\n/** 加载框架编译版本 */\n//define(''STRIP_RUNTIME_SPACE'',false);\ndefine(''DYHB_THIN'',true);\n\n/** 去掉模板空格 */\ndefine(''TMPL_STRIP_SPACE'',true);\n\n/** 载入框架 */\nrequire(NEEDFORBUG_PATH.''/source/include/DoYouHaoBaby/~DoYouHaoBaby.php'');\nApp::RUN();</pre-->\n\n\n\n\n<p>\n	<br />\n\n</p>\n</pre>', 2, 58, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1351044947;s:9:"commentid";i:61;s:3:"tid";s:2:"18";s:13:"commentuserid";s:1:"1";}', '', 1350538939, 1350552905),
(19, 0, 1, 1, 'admin', 'g', 'gggggggggggg', 1, 3, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1350539079;s:9:"commentid";i:58;s:3:"tid";s:2:"19";s:13:"commentuserid";s:1:"1";}', '', 1350539071, 1350539079),
(20, 5, 1, 1, 'admin', 'ffffff', 'fsdf\n<pre class="brush:html;">dsfsf</pre>', 1, 30, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1350613279;s:9:"commentid";i:59;s:3:"tid";s:2:"20";s:13:"commentuserid";s:1:"1";}', '', 1350545711, 1350613279),
(21, 0, 1, 1, 'admin', 'dfs', 'ssssssssss', 0, 1, 0, 1, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350548745, 0),
(22, 0, 1, 1, 'admin', 'ytyut', 'tyut', 0, 4, 0, 0, 1, 0, '', 1, 1, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1350548765, 0),
(23, 0, 1, 1, 'admin', '水电费', '水水水水死死死死死死', 2, 135, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1351045282;s:9:"commentid";i:62;s:3:"tid";s:2:"23";s:13:"commentuserid";s:1:"1";}', '', 1350552919, 0),
(24, 0, 1, 1, 'admin', 'tu', 'yu', 0, 1, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1351045339, 0),
(25, 0, 1, 1, 'admin', 'ds', 'sdffffffsdf水电费', 1, 23, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1351234441;s:9:"commentid";i:71;s:3:"tid";s:2:"25";s:13:"commentuserid";s:1:"1";}', '', 1351045849, 0),
(26, 0, 1, 1, 'admin', '金三角技术', '<p>\n	第三方的是\n</p>\n<p>\n	[attachment]40[/attachment]\n</p>', 4, 33, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1351149489;s:9:"commentid";i:66;s:3:"tid";s:2:"26";s:13:"commentuserid";N;}', 'admin', 1351046571, 1351218797),
(27, 0, 1, 1, 'admin', 'SDF', 'FFFFFFFFFFFF[attachment]41[/attachment]', 0, 5, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1351232051, 0),
(28, 0, 3, 1, 'admin', 'GH', 'KJHHHHHHHHHHH', 0, 5, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1351232371;s:9:"commentid";i:69;s:3:"tid";s:2:"28";s:13:"commentuserid";s:1:"1";}', '', 1351232314, 0),
(29, 0, 1, 1, 'admin', 'DF', 'FFFFFFFFFFFFF[attachment]39[/attachment]', 1, 14, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1351233770;s:9:"commentid";i:70;s:3:"tid";s:2:"29";s:13:"commentuserid";s:1:"1";}', '', 1351232396, 0),
(30, 0, 1, 1, 'admin', '67', '7777777777[attachment]37[/attachment]', 0, 3, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1351236958, 0),
(31, 0, 1, 1, 'admin', 'fds', 'sssssssssssss[attachment]42[/attachment]', 0, 33, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1351236978, 0),
(32, 0, 1, 1, 'admin', 'sdf', '<p>\n	ffffffffffffff\n</p>\n<p>\n	[attachment]43[/attachment]\n</p>', 0, 2, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1351238491, 0),
(33, 0, 1, 1, 'admin', '都是', '反复反复反复反复反复都是', 0, 5, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1352098009, 0),
(34, 0, 1, 1, 'admin', '士大夫', '这场灾难发生在2009年4月6日凌晨，在经历了历时半年的“小震不断”后，一场6.3级的大震降临在意大利中部拉奎拉地区，由于建筑年代久远，抗震措施差，这场并不算大的地震导致了308人死亡。<br />\n<br />\n一位劫后余生的外科医生在回忆这场悲剧时，称永远不能原谅自己在地震当夜的决定：在大震来临前的4个小时，曾发生了一次小震，在与妻子女儿争论该不该在屋外度过该夜时，医生说政府官员曾保证不会有“即时威胁”，并记起了一位科学家说过“每一次小震都将削减大震降临的机会”。医生说服了家人呆在屋里睡觉——4小时后，大震来袭，一家三口被埋于瓦砾之下，只有他一人侥幸活下来。<br />\n因“听信专家”的话导致妻女丧生的外科医生维特里尼<br />\n<br />\n这位医生后来参与了这场审判，作为指控科学家的证人。他坚称，审判并不是为了反对科学，他知道地震并不能预报。但当局不断地发出“冷静、不要担心”的信息，却不提供具体建议，这让他与其他的人失去了在地震当晚作出正确决定的机会——“这就是为什么我感到了科学的背叛。”<br />\n检察官：科学家并非不作为，而是向民众提供了不完整、不精确、自相矛盾的信息<br />\n<br />\n检察官同样指出，这场审判与“地震是否能被预测”没有任何关系——在224页的起诉书中，检察官指控的是，灾难预警委员会的6名地震专家和1名官员在地震发生前的一周，明明为地震召开了一场特别会议，但却向经历了数月“小震不断”、已陷入恐慌的民众提供了“不完整、不精确、自相矛盾的信息”，却没提供任何应对地震的建议，他们只关心如何让民众冷静下来。<br />\n<br />\n检察官称，委员会不向民众提供清晰的建议，就相当于过失杀人。一审法官正是采用了这一说法。<br />\n科学家做错了什么？<br />\n<br />\n在地震前一周，即3月31日举行的那次会议，成为科学家们被定罪的关键。在这场会议上，科学家们到底说了什么？据后来受意大利政府邀请对该场地震进行调查的美国地震专家称，这场会议下的结论没有任何问题——“没有理由认为不断发生的小震能预示一场大震”——在科学上，这毫无疑问是正确的。<br />\n<br />\n然而，在会议前后的媒体见面会上，委员会中的那位政府官员（一位水力工程师）却声称“本地的地震是‘正常的’，‘没有危险’，科学界不断向我保证，这样的现象是好事，因为连续小地震释放了地层中的能量。”——在科学上，这句话是不成立的。实际上也没有一位地震专家向这位官员提供了这样的说法。然而这个观点迅速地在镇上传播，变成了“震动越多，危险越小”。<br />\n<br />\n更要命的是，当有记者问“所以我们该享受一杯红酒时？”，该官员居然回应，“当然，当然要喝一杯卓林普乐怡诺红葡萄酒”。<br />\n<br />\n换言之，如果说这次会议误导了民众，那问题也是出在这位官员身上，而不是地震专家直接误导了民众。遗憾的是，官员传达的错误信息并未被及时纠正。<br />\n<br />\n如果说，这几位科学家做错了什么，那就是在这次会议上，只说了关于地震预测的情况，而没有在这次会议上向外界提供警告和建议，或者说“未表示忧虑”。这是否足以定“过失杀人罪”，外界有很多不同看法。', 0, 21, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, '', '', 1352098128, 0),
(35, 0, 2, 1, 'admin', 'DDDDDDDDDDDD', 'FSD', 2, 23, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 0, 0, 0, 0, 'a:4:{s:11:"commenttime";i:1353306473;s:9:"commentid";i:73;s:3:"tid";s:2:"35";s:13:"commentuserid";s:1:"1";}', '', 1353304701, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_grouptopiccategory`
--

CREATE TABLE IF NOT EXISTS `needforbug_grouptopiccategory` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `needforbug_grouptopiccategory`
--

INSERT INTO `needforbug_grouptopiccategory` (`grouptopiccategory_id`, `group_id`, `grouptopiccategory_name`, `grouptopiccategory_topicnum`, `grouptopiccategory_sort`, `create_dateline`) VALUES
(3, 1, '框架', 0, 0, 1349405390),
(4, 1, '小组', 0, 0, 1349405396),
(5, 1, 'needforbug相册', 0, 0, 1349405411);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_grouptopiccomment`
--

CREATE TABLE IF NOT EXISTS `needforbug_grouptopiccomment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- 转存表中的数据 `needforbug_grouptopiccomment`
--

INSERT INTO `needforbug_grouptopiccomment` (`grouptopiccomment_id`, `grouptopic_id`, `user_id`, `grouptopiccomment_status`, `grouptopiccomment_name`, `grouptopiccomment_content`, `grouptopiccomment_email`, `grouptopiccomment_url`, `grouptopiccoment_ip`, `create_dateline`, `update_dateline`) VALUES
(1, 2, 1, 1, '', '我来会一个帖子', '', '', '', 1349151352, 0),
(2, 3, 1, 1, '', '斯蒂芬方法', '', '', '', 1349156686, 0),
(3, 3, 1, 1, '', '的方式水水水水水水实施是是', '', '', '', 1349156731, 0),
(4, 4, 1, 1, '', '斯蒂芬而', '', '', '', 1349156780, 0),
(5, 4, 1, 1, '', '斯蒂芬', '', '', '', 1349156829, 0),
(6, 4, 1, 1, '', '斯蒂芬斯蒂芬', '', '', '', 1349156837, 0),
(7, 4, 1, 1, '', '斯蒂芬斯蒂芬斯蒂芬', '', '', '', 1349156845, 0),
(8, 4, 1, 1, '', '顶顶顶顶顶顶的地方', '', '', '', 1349156853, 0),
(9, 4, 1, 1, '', '第三方付费', '', '', '', 1349156857, 0),
(10, 4, 1, 1, '', '第三方反反复复发', '', '', '', 1349157276, 0),
(11, 4, 1, 1, '', '第三方', '', '', '', 1349157345, 0),
(12, 4, 1, 1, '', '斯蒂芬斯蒂芬第三方', '', '', '', 1349157369, 0),
(13, 3, 1, 1, '', 'dsf', '', '', '', 1349157445, 0),
(14, 3, 1, 1, '', 'dsff', '', '', '', 1349157460, 0),
(15, 3, 1, 1, '', '斯蒂芬斯蒂芬斯蒂芬速度', '', '', '', 1349157468, 0),
(16, 3, 1, 1, '', 'dsfsdf第三方', '', '', '', 1349157605, 0),
(17, 3, 1, 1, '', '第三方第三方', '', '', '', 1349157618, 0),
(18, 4, 1, 1, '', '', '', '', '', 1349159181, 0),
(19, 5, 1, 1, '', '哈哈，可以啊', '', '', '', 1349163119, 0),
(20, 5, 1, 1, '', '继续支持开发', '', '', '', 1349163131, 0),
(21, 5, 1, 1, '', '程序发展得不错，支持', '', '', '', 1349163148, 0),
(22, 2, 1, 1, '', 'bucuo', '', '', '', 1349239113, 0),
(23, 5, 1, 1, '', '今天心情不错', '', '', '', 1349239197, 0),
(24, 5, 1, 1, '', '测试点对点11', '', '', '', 1349323507, 0),
(25, 5, 1, 1, '', '测试', '', '', '', 1349333013, 0),
(26, 3, 1, 1, '', '想问问', '', '', '', 1349333315, 0),
(27, 1, 1, 1, '', '不凑了', '', '', '', 1349333517, 0),
(28, 2, 1, 1, '', '申诉', '', '', '', 1349333526, 0),
(29, 5, 1, 1, '', 'asdasd', '', '', '', 1349421554, 0),
(30, 5, 1, 1, '', '<p>\n	不错哈，哈哈\n</p>\n<p>\n	[attachment]1[/attachment]\n</p>', '', '', '', 1349421636, 0),
(31, 5, 1, 1, '', '[爱你][偷笑]', '', '', '', 1349422246, 0),
(32, 5, 1, 1, '', '[哈哈]吊死扶伤', '', '', '', 1349422703, 0),
(33, 5, 1, 1, '', '', '', '', '', 1349423342, 0),
(34, 5, 1, 1, '', '的说法是的', '', '', '', 1349424150, 0),
(35, 5, 1, 1, '', '士大夫似的水电费随碟附送', '', '', '', 1349424325, 0),
(36, 5, 1, 1, '', '第三方的是多少', '', '', '', 1349424344, 0),
(37, 5, 1, 1, '', '都是反复反复反复反复反复反复\n\n倒萨', '', '', '', 1349424361, 0),
(38, 5, 1, 1, '', '水电费', '', '', '', 1349425422, 0),
(39, 5, 1, 1, '', '<p>\n	[attachment]1[/attachment]hello world[attachment]2[/attachment]hjhj\n</p>\n<p>\n	[mo.浪漫约会][右哼哼][mo.情人节快乐]\n</p>', '', '', '', 1349426301, 0),
(40, 5, 1, 1, '', 'sdfsdf吊死扶伤收到发生的[video]http://player.youku.com/player.php/sid/XNDU2NTQ3NDMy/v.swf[/video]', '', '', '', 1349426456, 0),
(41, 5, 1, 1, '', '[attachment]3[/attachment]水电费', '', '', '', 1349427275, 0),
(42, 6, 1, 1, '', 'sdf水电费', '', '', '', 1349573438, 0),
(43, 6, 1, 1, '', '水电费', '', '', '', 1349573443, 0),
(44, 7, 1, 1, '', '赶回家', '', '', '', 1350208909, 0),
(45, 7, 1, 1, '', '建国后', '', '', '', 1350208914, 0),
(46, 3, 1, 1, '', '[attachment]23[/attachment][attachment]18[/attachment]', '', '', '', 1350274465, 0),
(47, 5, 1, 1, '', 'we随碟附送', '', '', '', 1350381274, 0),
(48, 5, 1, 1, '', '吊死扶伤', '', '', '', 1350381279, 0),
(49, 5, 1, 1, '', '[attachment]27[/attachment][attachment]28[/attachment]', '', '', '', 1350381297, 0),
(50, 5, 1, 1, '', '[mo.真乖]适当方式', '', '', '', 1350402281, 0),
(51, 3, 6, 1, '', '技术好哈。哈哈', '', '', '', 1350402617, 0),
(52, 12, 1, 1, '', 'dsf', '', '', '', 1350463194, 0),
(53, 14, 1, 1, '', '<br />', '', '', '', 1350463788, 0),
(54, 14, 1, 1, '', 'rtttttttt', '', '', '', 1350463792, 0),
(55, 14, 1, 1, '', 'tr', '', '', '', 1350463802, 0),
(56, 14, 1, 1, '', 'rt', '', '', '', 1350463810, 0),
(57, 18, 1, 1, '', '<p>\n	支持一下小尼姑额，哈哈\n</p>\n<p>\n<pre class="brush:php;">&lt;?php\n\necho ''Hello world!'';</pre>\n</p>', '', '', '', 1350539030, 0),
(58, 19, 1, 1, '', 'er', '', '', '', 1350539079, 0),
(59, 20, 1, 1, '', '<p>\n	水电费\n</p>\n<p>\n<pre class="brush:php;">&lt;?php\n/* [NeedForBug!] (C)Dianniu From 2010.\n   应用模型($)*/\n\n!defined(''DYHB_PATH'') &amp;&amp; exit;\n\nclass AppModel extends CommonModel{\n\n	static public function init__(){\n		return array(\n			''table_name''=&gt;''app'',\n			''props''=&gt;array(\n				''app_id''=&gt;array(''readonly''=&gt;true),\n			),\n			''attr_protected''=&gt;''app_id'',\n			''check''=&gt;array(\n				''app_name''=&gt;array(\n					array(''require'',Dyhb::L(''应用名不能为空'',''__COMMON_LANG__@Model/App'')),\n					array(''max_length'',32,Dyhb::L(''应用名最大长度为32个字符'',''__COMMON_LANG__@Model/App'')),\n				),\n				''app_identifier''=&gt;array(\n					array(''require'',Dyhb::L(''应用唯一识别符不能为空'',''__COMMON_LANG__@Model/App'')),\n					array(''english'',Dyhb::L(''应用唯一识别符只能为英文字符'',''__COMMON_LANG__@Model/App'')),\n					array(''max_length'',32,Dyhb::L(''应用唯一识别符最大长度为32个字符'',''__COMMON_LANG__@Model/App'')),\n				),\n			),\n		);\n	}\n\n	static function F(){\n		$arrArgs=func_get_args();\n		return ModelMeta::instance(__CLASS__)-&gt;findByArgs($arrArgs);\n	}\n\n	static function M(){\n		return ModelMeta::instance(__CLASS__);\n	}\n\n	public function filterAppindentifier(){\n		$this-&gt;app_identifier=str_replace(array(''_'',''-''),array('''',''''),$this-&gt;app_identifier);\n	}\n\n	public function safeInput(){\n		$_POST[''app_identifier'']=strtolower(G::html($_POST[''app_identifier'']));\n		$_POST[''app_name'']=G::html($_POST[''app_name'']);\n		$_POST[''app_author'']=G::html($_POST[''app_author'']);\n\n		if(isset($_POST[''app_version''])){\n			$_POST[''app_version'']=G::html($_POST[''app_version'']);\n		}\n	}\n\n}\n</pre>\n</p>', '', '', '', 1350613279, 0),
(60, 23, 1, 1, '', 'dfgdg', '', '', '', 1350812628, 0),
(61, 18, 1, 1, '', 'sdf', '', '', '', 1351044947, 0),
(62, 23, 1, 1, '', 'ry', '', '', '', 1351045282, 0),
(63, 26, 0, 1, '', '收到', '', '', '', 1351149483, 0),
(64, 26, 0, 1, '', '收到', '', '', '', 1351149485, 0),
(65, 26, 0, 1, '', '收到第三方', '', '', '', 1351149488, 0),
(66, 26, 0, 1, '', '收到第三方', '', '', '', 1351149489, 0),
(67, 28, 1, 1, '', 'GFD[attachment]39[/attachment]', '', '', '', 1351232353, 0),
(68, 28, 1, 1, '', 'GFD[attachment]39[/attachment]RR', '', '', '', 1351232363, 0),
(69, 28, 1, 1, '', 'GFD[attachment]39[/attachment]RRGFFFFFFFFFFFFFFFFF', '', '', '', 1351232371, 0),
(70, 29, 1, 1, '', '是的范德萨', '', '', '', 1351233770, 0),
(71, 25, 1, 1, '', 'dsf[attachment]40[/attachment]', '', '', '', 1351234441, 0),
(72, 35, 1, 1, '', '是打发士大夫', '', '', '', 1353306467, 0),
(73, 35, 1, 1, '', 'dsfffffffffffffffffffff', '', '', '', 1353306473, 0),
(74, 2, 1, 1, '', 'dsf', '', '', '', 1353308417, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_groupuser`
--

CREATE TABLE IF NOT EXISTS `needforbug_groupuser` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `group_id` int(10) NOT NULL DEFAULT '0' COMMENT '群组ID',
  `groupuser_isadmin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '加入时间',
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_groupuser`
--

INSERT INTO `needforbug_groupuser` (`user_id`, `group_id`, `groupuser_isadmin`, `create_dateline`) VALUES
(38, 1, 0, 1349155848),
(37, 1, 0, 1349155848),
(36, 1, 0, 1349155848),
(35, 1, 0, 1349155848),
(34, 1, 0, 1349155848),
(33, 1, 0, 1349155848),
(32, 1, 0, 1349155848),
(31, 1, 0, 1349155848),
(30, 1, 0, 1349155848),
(29, 1, 0, 1349155848),
(28, 1, 0, 1349155848),
(27, 1, 0, 1349155848),
(26, 1, 0, 1349155848),
(25, 1, 0, 1349155848),
(24, 1, 0, 1349155848),
(23, 1, 0, 1349155848),
(22, 1, 0, 1349155848),
(21, 1, 0, 1349155848),
(20, 1, 0, 1349155848),
(19, 1, 0, 1349155848),
(18, 1, 0, 1349155848),
(17, 1, 0, 1349155848),
(16, 1, 0, 1349155848),
(15, 1, 0, 1349155848),
(13, 1, 0, 1349155848),
(12, 1, 0, 1349155848),
(11, 1, 0, 1349155848),
(10, 1, 0, 1349155848),
(9, 1, 0, 1349155848),
(8, 1, 0, 1349155848),
(7, 1, 0, 1349155848),
(6, 1, 0, 1349155848),
(1, 1, 0, 1350548376),
(1, 2, 0, 1349403025),
(1, 3, 0, 1349403102),
(1, 4, 0, 1349403165),
(1, 5, 0, 1349403648);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_homefresh`
--

CREATE TABLE IF NOT EXISTS `needforbug_homefresh` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=224 ;

--
-- 转存表中的数据 `needforbug_homefresh`
--

INSERT INTO `needforbug_homefresh` (`homefresh_id`, `homefresh_title`, `user_id`, `homefresh_username`, `homefresh_from`, `create_dateline`, `homefresh_message`, `homefresh_ip`, `homefresh_commentnum`, `homefresh_goodnum`, `homefresh_viewnum`, `homefresh_status`) VALUES
(1, '', 1, '', '', 1341132710, '<span style="font-family:normal;font-size:14px;line-height:20px;text-indent:28px;white-space:normal;">香港第四任特别行政区长官梁振英在就职大会上发言：香港社会整体富裕，所有市民都应享有基本的生活保障，也应享有经济发展的得益。香港将尽快设立“扶贫委员会”，全面检视、研究和系统地处理老年、在职、跨代、新移民、少数族裔和区域性贫穷等问题；同时也会积极推动公私营医疗的双轨发展，让香港人不论贫富，都病有所医。七百万香港人是一家，他会将香港建设成宜居的城市，推动市民节能减排，加强绿色和保育意识，同时保护香港的自然美景。在非物质生活方面，政府将一如以往尊重宗教自由，进一步提升文化素质，鼓励创意。梁振英同时表示承诺继续维护公义，保障市民权益；维护法治、廉洁、自由、民主等香港核心价值，包容各种立场和意见；</span>', '', 0, 0, 0, 1),
(2, '', 1, '', '', 1341133099, '<span style="font-family:normal;font-size:14px;line-height:20px;text-indent:28px;white-space:normal;">中国保监会副主席陈文辉30日在上海表示，在整个养老保障体系建<span style="background-color:#E53333;">设过程之中，应通过运用市场机制来提高整个基本养老保险的运行效率。</span></span>', '', 0, 0, 0, 1),
(3, '', 1, '', '', 1341133120, '谢谢哈，你很好，我很爱你哈', '', 0, 0, 0, 1),
(4, '', 1, '', '', 1341196065, '我是一只猪，哈哈', '', 0, 0, 0, 1),
(5, '', 1, '', '', 1341196079, '谢谢你', '', 0, 0, 0, 1),
(6, '', 1, '', '', 1341196159, '是否顶顶顶顶顶顶顶顶', '', 0, 0, 0, 1),
(7, '', 1, '', '', 1341196163, '说得反反复复方法', '', 0, 0, 0, 1),
(8, '', 1, '', '', 1341196192, '的说法', '', 0, 0, 0, 1),
(9, '', 1, '', '', 1341196195, '是地方', '', 0, 0, 0, 1),
(10, '', 1, '', '', 1341196351, '是地方的说法', '', 0, 0, 0, 1),
(11, '', 1, '', '', 1341196394, '的方式使得反反复复', '', 0, 0, 0, 1),
(12, '', 1, '', '', 1341196505, '的方式是', '', 0, 0, 0, 1),
(13, '', 1, '', '', 1341196533, '否多算胜少算水水水水水水水', '', 0, 0, 0, 1),
(14, '', 1, '', '', 1341197091, '<img src="http://www.baidu.com/img/baidu_jgylogo3.gif" alt="" />热门的生活很好哈', '', 0, 0, 0, 1),
(15, '', 1, '', '', 1341197116, '欢迎大家光临祖国<span style="background-color:#FF9900;">的锅底哈。。谢谢。</span>', '', 0, 0, 0, 1),
(16, '', 1, '', '', 1341197725, '<strong><em><u>说得反反复复方法反反复复发</u></em></strong>', '', 0, 0, 0, 1),
(17, '', 1, '', '', 1341202032, '<span style="background-color:#E56600;"><strong>&nbsp;ffffffffffffffffff</strong></span><span style="background-color:#E56600;"><strong>f</strong></span><span style="background-color:#E56600;"><strong>f &nbsp; sdfffffffffffffffffffff</strong></span>', '', 0, 0, 0, 1),
(18, '', 1, '', '', 1341202063, '<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	6月30日21时，广州市政府召开新闻发布会通报，7月1日零时起，对全市中小客车试行总量适度调控管理，一个月内广州全市暂停办理中小客车的注册及转移登记。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	突如其来的“限牌令”，引发当地汽车市场震动，也引起市民强烈关注。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	6月30日晚，广州市政协副主席、交委主任冼伟雄在发布会上宣布，在为期一年的试行期内，全市中小客车增量配额为12万辆；为做好各项工作衔接，7月1日零时起的一个月内，广州全市暂停办理中小客车的注册及转移登记，后续各个月度平均分配增量配额；配置指标的具体办法和相关程序将于7月底前发布。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	<strong>“限牌令”当晚，车行交款柜台前排起长队</strong>\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	“我们晚上9点才知道这个消息，之前一点苗头也没有。”广东广悦汽车贸易有限公司市场经理吴思科说。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	6月30日16时，广州市500多家汽车经销商受邀参加公安交警支队、工商局、经贸局三方联合召开的会议，会上通告了限牌政策。傍晚时分开始，众多汽车经销商陆续接到厂家通知，开始采取电话、短信方式通知提醒订单车主与意向顾客；广汽丰田等广州本地知名汽车制造商及销售商等连夜召开紧急会议，商讨下半年在广州市场的销售政策。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	突如其来的“限牌令”引起市场轩然大波。当晚，记者来到位于黄埔大道的丰田4S店，平时晚上黑灯瞎火的销售大厅灯火通明。在广州赛马场汽车城，几乎所有的车行都取消了先前的优惠、折扣和促销活动，工作人员争分夺秒通知之前有买车意向的消费者赶快来交钱，刷卡柜台前排起长队。有些店铺更是简化手续，挑车后先刷卡开发票再完善购车合同……但这一切只持续到晚上12时——12时以后开具的发票一律无效。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	<strong>市民直呼太突然，“限牌令”为何搞突袭？</strong>\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	对于出台“限牌令”的原因，在新闻发布会上，冼伟雄表示，广州目前中小客车保有量和出行量持续快速增长，城市交通拥堵日趋明显，机动车排放对大气环境质量的影响日趋增大，为此广州需要实施中小客车总量调控政策。\n</p>\n<p style="font-family:Simsun, Verdana, sans-serif;font-size:14px;line-height:23px;text-align:left;white-space:normal;">\n	“限牌”并非广州独创。近年来，汽车进入家庭呈加速度增长，导致各大城市不堪重负。以车牌拍卖为治堵手段的上海，一块“沪”字头小铁皮就相当于一辆微型轿车的价钱；北京的摇号政策也导致车牌奇货可居。在广州市交通主管部门看来，出台“限牌令”似乎有足够的理由。\n</p>', '', 0, 0, 0, 1),
(19, '', 1, '', '', 1341202110, 'sdffffffffffffffffffffffffffff', '', 0, 0, 0, 1),
(20, '', 1, '', '', 1341202112, 'dfsssss', '', 0, 0, 0, 1),
(21, '', 1, '', '', 1341202114, 'dfssssssssss', '', 0, 0, 0, 1),
(22, '', 1, '', '', 1341202162, '<em><u><span style="color:#99BB00;"><strong>fwhifeeifghighihg</strong></span></u></em><span style="background-color:#FF9900;"><em><u><span style="color:#99BB00;"><strong></strong></span></u></em><em><u><span style="color:#99BB00;"><strong><a href="http://baidu.com">http://baidu.com</a></strong></span></u></em></span>', '', 0, 0, 0, 1),
(23, '', 15, '', '', 1341471675, 'dfssssssssssssss', '', 0, 2, 0, 1),
(24, '', 15, '', '', 1341471678, 'sdffffffffffffffffff', '', 0, 2, 0, 1),
(25, '', 15, '', '', 1341471737, '<strong><span style="color:#FF9900;">他是一个好人啦，我哈哈</span></strong>', '', 0, 1, 0, 1),
(26, '', 15, '', '', 1341471756, '是非颠倒点点滴滴', '', 0, 2, 0, 1),
(27, '', 15, '', '', 1341471759, '浮点数收拾收拾收拾收拾', '', 0, 1, 0, 1),
(28, '', 15, '', '', 1341471761, '打扫反反复复反反复复方法', '', 0, 1, 0, 1),
(29, '', 15, '', '', 1341471763, '说得反反复复方法发', '', 0, 2, 0, 1),
(30, '', 15, '', '', 1341471765, '达省份反反复复方法', '', 0, 2, 0, 1),
(31, '', 15, '', '', 1341471767, '都是反反复复方法', '', 0, 2, 0, 1),
(32, '', 15, '', '', 1341471769, '是地方', '', 0, 2, 0, 1),
(33, '', 16, '', '', 1342237208, 'sdfsdf', '', 0, 2, 0, 1),
(34, '', 16, '', '', 1342237216, '你是一个happ', '', 0, 2, 2, 1),
(35, '', 6, '', '', 1342237837, 'dfg', '', 0, 0, 0, 1),
(36, '', 6, '', '', 1342237843, 'dgf', '', 0, 0, 0, 1),
(37, '', 6, '', '', 1342237899, 'gfddfgfg', '', 0, 0, 0, 1),
(38, '', 6, '', '', 1342237903, 'gfd', '', 0, 0, 0, 1),
(39, '', 6, '', '', 1342237908, 'fd', '', 0, 0, 0, 1),
(40, '', 6, '', '', 1342237916, 'zni hso&nbsp;', '', 0, 0, 0, 1),
(41, '', 1, '', '', 1343005157, '你好特哦那个徐', '', 0, 0, 0, 1),
(42, '', 1, '', '', 1343005165, '粉丝顶顶顶顶顶顶顶顶顶', '', 0, 0, 0, 1),
(43, '', 1, '', '', 1343360086, 'sdfsf', '', 0, 0, 0, 1),
(44, '', 1, '', '', 1343700704, 'dsfdsf', '', 0, 0, 0, 1),
(45, '', 1, '', '', 1343700708, 'dfssssssssssssssssssssssssssssss', '', 0, 0, 0, 1),
(46, '', 1, '', '', 1343700716, 'sdffffffffffffffff', '', 0, 0, 0, 1),
(47, '', 1, '', '', 1345173322, '&nbsp;tyyyyyyyyyyy', '', 0, 0, 0, 1),
(48, '', 1, '', '', 1345772173, '人有人运', '', 0, 0, 0, 1),
(49, '', 1, '', '', 1345772178, '56756757', '', 0, 0, 0, 1),
(50, '', 1, '', '', 1345775294, 'gfgdfgd', '', 0, 0, 0, 1),
(51, '', 1, '', '', 1345775297, 'gd', '', 0, 0, 0, 1),
(52, '', 1, '', '', 1345775299, 'fdgdfg', '', 0, 0, 0, 1),
(53, '', 1, '', '', 1345775301, 'fdg', '', 0, 0, 0, 1),
(54, '', 1, '', '', 1345775655, 'yuyuyu', '', 0, 0, 2, 1),
(55, '', 1, '', '', 1345775658, 'uyyyyyyyyyyyyyyyyyyyyyy', '', 0, 0, 3, 1),
(56, '', 1, '', '', 1345775687, 'yuuyu', '', 0, 0, 0, 1),
(57, '', 1, '', '', 1345775690, 'uii', '', 0, 1, 1, 1),
(58, '', 1, '', '', 1345777026, '我非好好<br />', '', 0, 0, 0, 1),
(59, '', 1, '', '', 1346053789, '<p style="margin:20px auto 0px;padding:0px;line-height:25px;font-family:宋体, arial, sans-serif;font-size:14px;text-align:left;white-space:normal;background-color:#FFFFFF;">\n	中新网长沙8月27日电 (记者 刘柱 李俊杰)湖南省邵阳市新闻中心负责人向中新网记者透露，27日上午该市发生一起人为纵火案，目前已造成3人死亡、4人受伤。纵火嫌疑人跳楼自杀未遂，正在医院接受治疗。\n</p>\n<p style="margin:20px auto 0px;padding:0px;line-height:25px;font-family:宋体, arial, sans-serif;font-size:14px;text-align:left;white-space:normal;background-color:#FFFFFF;">\n	该负责人向记者透露，27日上午10点5分许，湖南省邵阳市自来水公司党组成员在当地清泉大厦6楼会议室开会，公司内部退休女员工石某突然向会议室内泼汽油点火，导致3人死亡、4人受伤。案发时“有多少人在开会还不清楚”。\n</p>\n<p style="margin:20px auto 0px;padding:0px;line-height:25px;font-family:宋体, arial, sans-serif;font-size:14px;text-align:left;white-space:normal;background-color:#FFFFFF;">\n	负责人介绍，纵火嫌疑人石某当场也受了伤，随后跳楼自杀未遂被警方控制，目前正在医院接受治疗。\n</p>\n<p style="margin:20px auto 0px;padding:0px;line-height:25px;font-family:宋体, arial, sans-serif;font-size:14px;text-align:left;white-space:normal;background-color:#FFFFFF;">\n	据了解，死者和伤者具体身份尚未公布。媒体此前报道称，“3名党组成员死亡”。一位知情内部人士向记者透露，“死者中有一名经理。”\n</p>', '', 43, 0, 198, 1),
(60, '', 1, '', '', 1346117977, '<span style=\\"white-space:nowrap;\\">这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">一个健壮的插件机制，我认为必须具备以下特点：</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">插件的动态监听和加载（Lookup）</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">插件的动态触发</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">以上两点的实现均不影响核心程序的运行</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中的“中断保护”逻辑。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">某些钩子可能是系统事先就设计好的，比如之前我举的关于评论Spam过滤的钩子，通常它已经由核心系统开发人员设计进了评论的处理逻辑中；另外一类钩子则可能是由用户自行定制的（由第三方开发人员制定），通常存在于表现层，比如一个普通的PHP表单显示页面中。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">可能你感觉上面的话比较无聊，让人昏昏欲睡；但是要看懂下面我写的代码，理解以上的原理是必不可少的。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">下面进行PHP中插件机制的核心实现，整个机制核心分为三大块：</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">一个插件经理类：这是核心之核心。它是一个应用程序全局Global对象。它主要有三个职责：</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">负责监听已经注册了的所有插件，并实例化这些插件对象。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">负责注册所有插件。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">当钩子条件满足时，触发对应的对象方法。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">插件的功能实现：这大多由第三方开发人员完成，但需要遵循一定的规则，这个规则是插件机制所规定的，因插件机制的不同而不同，下面的显示代码你会看到这个规则。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">插件的触发：也就是钩子的触发条件。具体来说这是一小段代码，放置在你需要插件实现的地方，用于触发这个钩子。</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">原理讲了一大堆，下面看看我的实现方案：</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">插件经理PluginManager类：</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">以下为引用的内容：</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">&lt;?</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">class PluginManager</span><br />\n<span style=\\"white-space:nowrap;\\">{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp;&nbsp;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;private $_listeners = array();</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;public function __construct()</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;#这里$plugin数组包含我们获取已经由用户激活的插件信息</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; #为演示方便，我们假定$plugin中至少包含</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; #$plugin = array(</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;# &nbsp; &nbsp;\\''name\\'' =&gt; \\''插件名称\\'',</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;# &nbsp; &nbsp;\\''directory\\''=&gt;\\''插件安装目录\\''</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;#);</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;$plugins = get_active_plugins();#这个函数请自行实现</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;if($plugins)</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;foreach($plugins as $plugin)</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{//假定每个插件文件夹中包含一个actions.php文件，它是插件的具体实现</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;if (@is_file(STPATH .\\''plugins/\\''.$plugin[\\''directory\\''].\\''/actions.php\\''))</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;include_once(STPATH .\\''plugins/\\''.$plugin[\\''directory\\''].\\''/actions.php\\'');</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$class = $plugin[\\''name\\''].\\''_actions\\'';</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;if (class_exists($class))&nbsp;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;//初始化所有插件</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;new $class($this);</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;#此处做些日志记录方面的东西</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp;&nbsp;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;function register($hook, &amp;$reference, $method)</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;//获取插件要实现的方法</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;$key = get_class($reference).\\''-&gt;\\''.$method;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;//将插件的引用连同方法push进监听数组中</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;$this-&gt;_listeners[$hook][$key] = array(&amp;$reference, $method);</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;#此处做些日志记录方面的东西</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp;&nbsp;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;function trigger($hook, $data=\\''\\'')</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;$result = \\''\\'';</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;//查看要实现的钩子，是否在监听数组之中</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;if (isset($this-&gt;_listeners[$hook]) &amp;&amp; is_array($this-&gt;_listeners[$hook]) &amp;&amp; count($this-&gt;_listeners[$hook]) &gt; 0)</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;// 循环调用开始</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;foreach ($this-&gt;_listeners[$hook] as $listener)</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;// 取出插件对象的引用和方法</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$class =&amp; $listener[0];</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$method = $listener[1];</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;if(method_exists($class,$method))</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;// 动态调用插件的方法</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$result .= $class-&gt;$method($data);</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;#此处做些日志记录方面的东西</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp; &nbsp; &nbsp;return $result;</span><br />\n<span style=\\"white-space:nowrap;\\">&nbsp; &nbsp;}</span><br />\n<span style=\\"white-space:nowrap;\\">}</span><br />\n<span style=\\"white-space:nowrap;\\">?&gt;</span><br />\n<span style=\\"white-space:nowrap;\\"><br />\n</span><br />\n<span style=\\"white-space:nowrap;\\">以上代码加上注释不超过100行，就完成了整个插件机制的核心。需要再次说明的是，你必须将它设置成全局类，在所有需要用到插件的地方，优先加载。用#注释的地方是你需要自行完成的部分，包括插件的获取和日志记录等等。</span><br />', '', 0, 1, 2, 1),
(61, '', 1, '', '', 1346119013, '""sdfsdfsdfsd"//fdsfsdf', '', 0, 1, 0, 1),
(62, '', 1, '', '', 1346119133, '""sfdsfsdfsdfsdfsdfs', '', 0, 1, 0, 1),
(63, '', 1, '', '', 1346119242, 'fdsfsf"""fsdfsdf/dfssdfs"fsdfsdf', '', 0, 1, 0, 1),
(64, '', 1, '', '', 1346119288, '"sdfsdf/sdffsdfsd', '', 0, 1, 0, 1),
(65, '', 1, '', '', 1346119370, '"sdfsfdsdf://sdfsdfd"sdfsdf/sdfsdfsd"fsdfsdf"fsdfsd/fsdfsdf', '', 0, 1, 0, 1),
(66, '', 1, '', '', 1346119408, '热痰热特特eternal特锐', '', 0, 1, 1, 1),
(67, '', 1, '', '', 1346119911, '幅度更大更', '', 1, 0, 3, 1),
(68, '', 1, '', '', 1346120526, '的说法仿盛大仿盛大<br />', '', 0, 0, 0, 1),
(69, '', 1, '', '', 1346120556, '斯蒂芬斯蒂芬速度<br />', '', 0, 0, 0, 1),
(70, '', 1, '', '', 1346120571, '发斯蒂芬斯蒂芬:""fsdfsdfsdf<br />', '', 0, 0, 0, 1),
(71, '', 1, '', '', 1346120670, 'gdgdfgdfgdfg：圣大非省的“”“的分身乏术的发<br />', '', 0, 0, 0, 1),
(72, '', 1, '', '', 1346120728, '达省份圣大非省的"sdfsdf<br />', '', 0, 0, 0, 1),
(73, '', 1, '', '', 1346120968, 'dsfsd的说法斯蒂芬斯蒂芬速度福鼎市""dsfsdfsd <br />', '', 0, 0, 0, 1),
(74, '', 1, '', '', 1346121165, 'dsfs"sdf第三方斯蒂芬<br />', '', 0, 0, 0, 1),
(75, '', 1, '', '', 1346121226, '有人提议让<br />', '', 0, 0, 0, 1),
(76, '', 1, '', '', 1346121320, 'dsfsdfsfs”的风是地方是<br />', '', 0, 0, 0, 1),
(77, '', 1, '', '', 1346121391, '斯蒂芬斯蒂芬<br />', '', 0, 0, 0, 1),
(78, '', 1, '', '', 1346121402, '是否的方式的<br />', '', 0, 0, 0, 1),
(79, '', 1, '', '', 1346121444, '认同有人提议让统一<br />', '', 0, 0, 0, 1),
(80, '', 1, '', '', 1346121453, '体育局统一<br />', '', 0, 0, 0, 1),
(81, '', 1, '', '', 1346121488, '体有人提议让他<br />', '', 0, 0, 0, 1),
(82, '', 1, '', '', 1346121603, '但是方法圣大非省的仿盛大仿盛大<br />', '', 0, 0, 0, 1),
(83, '', 1, '', '', 1346121648, '第三方斯蒂芬“”“发第三方斯蒂芬福鼎市<br />', '', 0, 1, 1, 1),
(84, '', 1, '', '', 1346121662, '的说法是的”“发的说法是的<br />', '', 0, 1, 0, 1),
(85, '', 1, '', '', 1346121693, '的说法是的”“发的说法是的<br />', '', 0, 1, 3, 1),
(86, '', 1, '', '', 1346121733, '的说法是的”“发的说法是的<br />', '', 0, 2, 0, 1),
(87, '', 1, '', '', 1346121789, '发的说法是的发生的<br />', '', 0, 2, 2, 1),
(88, '', 1, '', '', 1346121889, '发的说法是的发生的达省份圣大非省的仿盛大<br />', '', 4, 3, 3, 1),
(89, '', 1, '', '', 1346121932, '发的说法是的发生的达省份圣大非省的仿盛大<br />', '', 0, 1, 0, 1),
(90, '', 1, '', '', 1346121947, '斯蒂芬斯蒂芬”s""""//dsfsdfsfd<br />', '', 0, 1, 0, 1),
(91, '', 1, '', '', 1346122200, '""//fdsfsdf"//sdfsdfsdf<br />', '', 3, 2, 1, 1),
(92, '', 1, '', '', 1346122239, '"//"//sfsdfsdfsdf<br />', '', 0, 3, 2, 1),
(93, '', 1, '', '', 1346133266, '<span style="white-space:nowrap;">这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">一个健壮的插件机制，我认为必须具备以下特点：</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">插件的动态监听和加载（Lookup）</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">插件的动态触发</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">以上两点的实现均不影响核心程序的运行</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中的“中断保护”逻辑。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">某些钩子可能是系统事先就设计好的，比如之前我举的关于评论Spam过滤的钩子，通常它已经由核心系统开发人员设计进了评论的处理逻辑中；另外一类钩子则可能是由用户自行定制的（由第三方开发人员制定），通常存在于表现层，比如一个普通的PHP表单显示页面中。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">可能你感觉上面的话比较无聊，让人昏昏欲睡；但是要看懂下面我写的代码，理解以上的原理是必不可少的。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">下面进行PHP中插件机制的核心实现，整个机制核心分为三大块：</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">一个插件经理类：这是核心之核心。它是一个应用程序全局Global对象。它主要有三个职责：</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">负责监听已经注册了的所有插件，并实例化这些插件对象。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">负责注册所有插件。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">当钩子条件满足时，触发对应的对象方法。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">插件的功能实现：这大多由第三方开发人员完成，但需要遵循一定的规则，这个规则是插件机制所规定的，因插件机制的不同而不同，下面的显示代码你会看到这个规则。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">插件的触发：也就是钩子的触发条件。具体来说这是一小段代码，放置在你需要插件实现的地方，用于触发这个钩子。</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">原理讲了一大堆，下面看看我的实现方案：</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">插件经理PluginManager类：</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">以下为引用的内容：</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">&lt;?</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">class PluginManager</span><br />\n<span style="white-space:nowrap;">{</span><br />\n<span style="white-space:nowrap;">&nbsp;&nbsp;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;private $_listeners = array();</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;public function __construct()</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;#这里$plugin数组包含我们获取已经由用户激活的插件信息</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; #为演示方便，我们假定$plugin中至少包含</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; #$plugin = array(</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;# &nbsp; &nbsp;''name'' =&gt; ''插件名称'',</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;# &nbsp; &nbsp;''directory''=&gt;''插件安装目录''</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;#);</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;$plugins = get_active_plugins();#这个函数请自行实现</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;if($plugins)</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;foreach($plugins as $plugin)</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{//假定每个插件文件夹中包含一个actions.php文件，它是插件的具体实现</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;if (@is_file(STPATH .''plugins/''.$plugin[''directory''].''/actions.php''))</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;include_once(STPATH .''plugins/''.$plugin[''directory''].''/actions.php'');</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$class = $plugin[''name''].''_actions'';</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;if (class_exists($class))&nbsp;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;//初始化所有插件</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;new $class($this);</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;#此处做些日志记录方面的东西</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;</span><br />\n<span style="white-space:nowrap;">&nbsp;&nbsp;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;function register($hook, &amp;$reference, $method)</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;//获取插件要实现的方法</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;$key = get_class($reference).''-&gt;''.$method;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;//将插件的引用连同方法push进监听数组中</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;$this-&gt;_listeners[$hook][$key] = array(&amp;$reference, $method);</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;#此处做些日志记录方面的东西</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp;&nbsp;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;function trigger($hook, $data='''')</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;$result = '''';</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;//查看要实现的钩子，是否在监听数组之中</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;if (isset($this-&gt;_listeners[$hook]) &amp;&amp; is_array($this-&gt;_listeners[$hook]) &amp;&amp; count($this-&gt;_listeners[$hook]) &gt; 0)</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;// 循环调用开始</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;foreach ($this-&gt;_listeners[$hook] as $listener)</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;// 取出插件对象的引用和方法</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$class =&amp; $listener[0];</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$method = $listener[1];</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;if(method_exists($class,$method))</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;// 动态调用插件的方法</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;$result .= $class-&gt;$method($data);</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;#此处做些日志记录方面的东西</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp; &nbsp; &nbsp;return $result;</span><br />\n<span style="white-space:nowrap;">&nbsp; &nbsp;}</span><br />\n<span style="white-space:nowrap;">}</span><br />\n<span style="white-space:nowrap;">?&gt;</span><br />\n<span style="white-space:nowrap;"><br />\n</span><br />\n<span style="white-space:nowrap;">以上代码加上注释不超过100行，就完成了整个插件机制的核心。需要再次说明的是，你必须将它设置成全局类，在所有需要用到插件的地方，优先加载。用#注释的地方是你需要自行完成的部分，包括插件的获取和日志记录等等。</span><br />', '', 15, 2, 13, 1),
(94, '', 1, '', '', 1346138060, '哈哈&nbsp;&nbsp;&nbsp;&nbsp;<br />', '', 0, 3, 8, 1),
(95, '你很挂啊', 1, '', '', 1346138533, '小品呢国有过来<br />', '', 4, 2, 3, 1),
(96, '', 1, '', '', 1346138598, '<p>\n	<img src="http://www.baidu.com/img/baidu_sylogo1.gif" alt="" /> \n</p>\n<p>\n	这里只能加载外部图片\n</p>', '', 20, 8, 54, 1),
(97, '', 1, '', '', 1346384558, '测试', '0.0.0.0', 7, 2, 3, 1),
(98, '', 1, '', '', 1346660599, '大法师的法师<br />', '0.0.0.0', 5, 0, 1, 1),
(99, '', 1, '', '', 1346663140, '测<br />', '0.0.0.0', 0, 0, 0, 1),
(100, '', 1, '', '', 1346664655, '个好法国恢复<br />', '0.0.0.0', 0, 0, 0, 1),
(101, '', 1, '', '', 1346664666, '发顺丰<br />', '0.0.0.0', 0, 0, 0, 1),
(102, '', 1, '', '', 1346664675, '是打发士大夫<br />', '0.0.0.0', 0, 0, 0, 1),
(103, '', 1, '', '', 1346664732, '犹太人<br />', '0.0.0.0', 0, 0, 0, 1),
(104, '', 1, '', '', 1346664802, '有人同意<br />', '0.0.0.0', 0, 0, 0, 1),
(105, '', 1, '', '', 1346665184, '飞的打发打发', '0.0.0.0', 0, 0, 0, 1),
(106, '', 1, '', '', 1346665240, '分隔符<br />', '0.0.0.0', 0, 0, 0, 1),
(107, '', 1, '', '', 1346665270, '发士大夫<br />', '0.0.0.0', 0, 0, 0, 1),
(108, '', 1, '', '', 1346665288, 'dsf<br />', '0.0.0.0', 0, 0, 0, 1),
(109, '', 1, '', '', 1346665325, '''&lt;div id="homefreshcommentdiv_''+data.homefresh_id+''" onclick="commentForm(''''+data.homefresh_id+'''');" class="homefreshcomment_div"&gt;&lt;!--&lt;lang package=''__COMMON_LANG__@Template/Homefresh''&gt;--&gt;我也来说一句&lt;!--&lt;/lang&gt;--&gt;&lt;/div&gt;''+<br />\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;''&lt;div id="homefreshcommentform_''+data.homefresh_id+''" class="homefreshcomment_form"&gt;&lt;/div&gt;''+<br />', '0.0.0.0', 0, 0, 0, 1),
(110, '', 1, '', '', 1346665352, '''&lt;a href="javascript:void(0);" onclick="goodnum(''''+data.homefresh_id+'''');"&gt;&lt;!--&lt;lang package=''__COMMON_LANG__@Template/Homefresh''&gt;--&gt;赞&lt;!--&lt;/lang&gt;--&gt;(&lt;span id="goodnum_''+data.homefresh_id+''"&gt;''+data.homefresh_goodnum+''&lt;/span&gt;)&lt;/a&gt;''+<br />\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;''&lt;span class="pipe"&gt;|&lt;/span&gt;''+<br />', '0.0.0.0', 0, 0, 0, 1),
(111, '', 1, '', '', 1346665373, 'sdf<br />', '0.0.0.0', 0, 0, 0, 1),
(112, '', 1, '', '', 1346665389, 'dsf<br />', '0.0.0.0', 0, 0, 0, 1),
(113, '', 1, '', '', 1346665430, '士大夫似的<br />', '0.0.0.0', 0, 0, 0, 1),
(114, '', 1, '', '', 1346665460, 'dsfs飞<br />', '0.0.0.0', 0, 0, 0, 1),
(115, '', 1, '', '', 1346665480, '发士大夫<br />', '0.0.0.0', 1, 0, 0, 1),
(116, '', 1, '', '', 1346665561, '大哥大<br />', '0.0.0.0', 0, 0, 0, 1),
(117, '', 1, '', '', 1346665574, 'dsf<br />', '0.0.0.0', 0, 0, 0, 1),
(118, '', 1, '', '', 1346665592, 'dsf<br />', '0.0.0.0', 0, 0, 0, 1),
(119, '', 1, '', '', 1346665617, '是打发士大夫是否<br />', '0.0.0.0', 0, 0, 0, 1),
(120, '', 1, '', '', 1346665624, '我很好<br />', '0.0.0.0', 22, 0, 18, 1),
(121, '', 1, '', '', 1346665688, '是打发士大夫<br />', '0.0.0.0', 4, 0, 1, 1),
(122, '', 1, '', '', 1346665747, '速度发生的发生收到<br />', '0.0.0.0', 8, 0, 9, 1),
(123, '', 1, '', '', 1346665864, '是打发士大夫<br />', '0.0.0.0', 9, 0, 1, 1),
(124, '', 1, '', '', 1346739368, 'fsd ddfds', '0.0.0.0', 0, 0, 0, 1),
(125, '', 1, '', '', 1346751575, '我是一条测试评论的数据，请稍后，看看是否正确<br />', '0.0.0.0', 34, 0, 157, 1),
(126, '', 1, '', '', 1346853987, 'test<br />', '0.0.0.0', 14, 0, 365, 1),
(127, '', 1, '', '', 1346921443, '这是用来测试新鲜事评论审核的', '0.0.0.0', 0, 0, 9, 1),
(128, '', 1, '', '', 1346922438, 'ajax查看', '0.0.0.0', 0, 0, 1, 1),
(129, '', 1, '', '', 1346979476, '评论审核功能开发', '0.0.0.0', 2, 0, 50, 1),
(130, '', 1, '', '', 1348369902, 'tg', '0.0.0.0', 0, 0, 0, 1),
(131, '', 1, '', '', 1348369916, 'fg', '0.0.0.0', 0, 0, 0, 1),
(132, '', 1, '', '', 1348370001, 'dd<br />', '0.0.0.0', 0, 0, 0, 1),
(133, '', 1, '', '', 1348370145, 'try', '0.0.0.0', 0, 0, 0, 1),
(134, '', 1, '', '', 1348370149, 'trytrytry', '0.0.0.0', 0, 0, 0, 1),
(135, '', 1, '', '', 1348370204, 'ryt<br />', '0.0.0.0', 0, 0, 1, 1),
(136, '', 1, '', '', 1348645068, '[upload]123[/upload]yrty', '0.0.0.0', 0, 0, 0, 1),
(137, '', 1, '', '', 1348718956, '<p>\n	[attachment]29[/attachment][attachment]28[/attachment][attachment]27[/attachment][attachment]25[/attachment][attachment]24[/attachment]\n</p>\n<p>\n	可以了哈。哈哈\n</p>\n<br />', '0.0.0.0', 0, 0, 1, 1),
(138, '', 1, '', '', 1348796369, '[hide]xxx[/hide]<br />', '0.0.0.0', 0, 0, 9, 1),
(139, '', 1, '', '', 1348796893, '[hide]xxx[/hide]{xxx}<br />', '0.0.0.0', 0, 0, 1, 1),
(140, '', 1, '', '', 1348797025, '<p>\n	[hr]sdfsdfsdf\n</p>\n<p>\n	[br]sdfsdf\n</p>\n<p>\n	&lt;br/&gt;\n</p>', '0.0.0.0', 0, 0, 1, 1),
(141, '', 1, '', '', 1348797082, '<p>\n	<br />\n</p>\n<p>\n	[br]\n</p>', '0.0.0.0', 0, 0, 2, 1),
(142, '', 1, '', '', 1348804679, '[img]http://www.baidu.com/img/baidu_sylogo1.gif[/img]<br />', '0.0.0.0', 0, 0, 17, 1),
(143, '', 1, '', '', 1348813767, '[url=http://baidu.com][img]http://www.baidu.com/img/baidu_sylogo1.gif[/img][/url]', '0.0.0.0', 0, 0, 1, 1),
(144, '', 1, '', '', 1348814180, 'http://baidu.com<br />', '0.0.0.0', 0, 0, 0, 1),
(145, '', 1, '', '', 1348814300, 'http://baidu.com<br />', '0.0.0.0', 0, 0, 3, 1),
(146, '', 1, '', '', 1348814695, '[size=4]不错哈[/size]', '0.0.0.0', 0, 0, 4, 1),
(147, '', 1, '', '', 1348815232, '[tbl]wer[/tbl]', '0.0.0.0', 0, 0, 2, 1),
(148, '', 1, '', '', 1348815655, '<p>\n	[tbl]wer\n</p>\n<p>\n	sdf\n</p>\n<p>\n	<br />\n</p>\n<p>\n	sdfsd\n</p>\n<p>\n	f\n</p>\n<p>\n	<br />\n</p>\n<p>\n	dsfs\n</p>\n<p>\n	df\n</p>\n<p>\n	fd\n</p>\n<p>\n	sf\n</p>\n<p>\n	sdf\n</p>\n<p>\n	sdf[/tbl]\n</p>', '0.0.0.0', 0, 0, 1, 1),
(149, '', 1, '', '', 1348815716, '[tbl]wer,sdf\n	s,dfsd\n	f\n	,dsfs\n	df\n	fd,\n	sf\n	sdf,\n	sdf[/tbl]', '0.0.0.0', 0, 0, 1, 1),
(150, '', 1, '', '', 1348815888, '[tbl width=100 bgcolor=#000000 border=1]wer\n	sdf\n	sdfsd\n	f\n	dsfs\n	df\n	fd\n	sf\n	sdf\n	sdf[/tbl]', '0.0.0.0', 0, 0, 1, 1),
(151, '', 1, '', '', 1348816030, '[quote]Hello world[/quote]', '0.0.0.0', 0, 0, 14, 1),
(152, '', 1, '', '', 1348816658, '[quote=黑]Hello world[/quote]', '0.0.0.0', 0, 0, 1, 1),
(153, '', 1, '', '', 1348816687, '[quote=黑]Hello world[/quote]', '0.0.0.0', 0, 0, 0, 1),
(154, '', 1, '', '', 1348816773, '<p>\n	[code]class P{\n</p>\n<p>\n	<br />\n</p>\n<p>\n	}[/code]\n</p>', '0.0.0.0', 0, 0, 1, 1),
(155, '', 1, '', '', 1348816827, '[code]xx[/code]', '0.0.0.0', 0, 0, 3, 1),
(156, '', 1, '', '', 1348817886, 'http://baidu.com/sdfsdf.php<br />', '0.0.0.0', 0, 0, 1, 1),
(157, '', 1, '', '', 1348817935, 'http://localhost/needforbug/upload/admin.phps', '0.0.0.0', 0, 0, 1, 1),
(158, '', 1, '', '', 1348817956, 'https://localhost/needforbug/upload/admin.php', '0.0.0.0', 0, 0, 5, 1),
(159, '', 1, '', '', 1348818127, '[autolink]http://baidu.com[/autolink]', '0.0.0.0', 0, 0, 1, 1),
(160, '', 1, '', '', 1348818167, '[autourl]http://baidu.com[/autourl]', '0.0.0.0', 0, 0, 2, 1),
(161, '', 1, '', '', 1348818491, '<p>\n	[url=http://zend.com]日本技术[/url]\n</p>\n<p>\n	[url]http://riben.com[/url]\n</p>', '0.0.0.0', 0, 0, 1, 1),
(162, '', 1, '', '', 1348818720, '[email]log1990@126.com[/email] [php]class{echo ''Hello world'';}[/php]<br />', '0.0.0.0', 0, 0, 4, 1),
(163, '', 1, '', '', 1348819062, 'http://baidu.com', '0.0.0.0', 0, 0, 1, 1),
(164, '', 1, '', '', 1348819476, '[attachment]68[/attachment]<br />', '0.0.0.0', 0, 0, 31, 1),
(165, '', 1, '', '', 1348825456, '[attachment]5[/attachment]', '0.0.0.0', 0, 0, 2, 1),
(166, '', 1, '', '', 1348825760, '[attachment]72[/attachment]', '0.0.0.0', 0, 0, 3, 1),
(167, '', 1, '', '', 1348844778, '[attachment]1[/attachment]', '0.0.0.0', 0, 0, 2, 1),
(168, '', 1, '', '', 1348848790, '[attachment]2[/attachment]', '0.0.0.0', 0, 0, 14, 1),
(169, '', 1, '', '', 1348852082, '[attachment]3[/attachment]', '0.0.0.0', 0, 0, 6, 1),
(170, '', 1, '', '', 1348852783, '[attachment]4[/attachment]', '0.0.0.0', 0, 0, 4, 1),
(171, '', 1, '', '', 1348853373, '[attachment]5[/attachment]', '0.0.0.0', 0, 0, 8, 1),
(172, '', 1, '', '', 1348855175, '[attachment]6[/attachment]', '0.0.0.0', 0, 0, 9, 1),
(173, '', 1, '', '', 1348855531, '[attachment]7[/attachment]', '0.0.0.0', 0, 0, 8, 1),
(174, '', 1, '', '', 1348855943, '[attachment]8[/attachment]', '0.0.0.0', 0, 0, 4, 1),
(175, '', 1, '', '', 1348910816, '[晕][mo.情人节][可爱][生病]哈哈，可以看哈撒放斯蒂芬', '0.0.0.0', 0, 0, 1, 1),
(176, '', 1, '', '', 1348910904, '表情功能好了哈[mo.告白][生病]', '0.0.0.0', 0, 0, 0, 1),
(177, '', 1, '', '', 1348911023, '[晕]斯蒂芬', '0.0.0.0', 0, 0, 0, 1),
(178, '', 1, '', '', 1348911078, '[哈哈][mo.情人节]', '0.0.0.0', 0, 0, 0, 1),
(179, '', 1, '', '', 1348911090, '[爱你][mo.大哭]', '0.0.0.0', 0, 0, 0, 1),
(180, '', 1, '', '', 1348911119, '[mo.玉兔迎春]', '0.0.0.0', 0, 0, 0, 1),
(181, '', 1, '', '', 1348911157, '[爱你]<br />', '0.0.0.0', 0, 0, 0, 1),
(182, '', 1, '', '', 1348911205, '[mo.抓狂]', '0.0.0.0', 0, 0, 0, 1),
(183, '', 1, '', '', 1348911368, '[mo.生气]', '0.0.0.0', 0, 0, 0, 1),
(184, '', 1, '', '', 1348911582, '斯蒂芬斯蒂芬都是是[mo.浪漫约会]', '0.0.0.0', 0, 0, 0, 1),
(185, '', 1, '', '', 1348911639, '[爱你]', '0.0.0.0', 0, 0, 0, 1),
(186, '', 1, '', '', 1348911677, '[爱你]', '0.0.0.0', 0, 0, 0, 1),
(187, '', 1, '', '', 1348911689, '[mo.告白]', '0.0.0.0', 0, 0, 0, 1),
(188, '', 1, '', '', 1348911711, '[mo.举重]', '0.0.0.0', 0, 0, 0, 1);
INSERT INTO `needforbug_homefresh` (`homefresh_id`, `homefresh_title`, `user_id`, `homefresh_username`, `homefresh_from`, `create_dateline`, `homefresh_message`, `homefresh_ip`, `homefresh_commentnum`, `homefresh_goodnum`, `homefresh_viewnum`, `homefresh_status`) VALUES
(189, '', 1, '', '', 1348911809, '是对方是否上的', '0.0.0.0', 0, 0, 0, 1),
(190, '', 1, '', '', 1348911879, '[mo.浪漫约会][mo.我爱你]', '0.0.0.0', 0, 0, 1, 1),
(191, '', 1, '', '', 1348911929, '[mo.情人节][害羞]', '0.0.0.0', 0, 0, 0, 1),
(192, '', 1, '', '', 1348911959, '[mo.情人节]', '0.0.0.0', 0, 0, 0, 1),
(193, '', 1, '', '', 1348912027, 'sdf', '0.0.0.0', 0, 0, 0, 1),
(194, '', 1, '', '', 1348912033, 'sdfdsfdsf', '0.0.0.0', 0, 0, 1, 1),
(195, '', 1, '', '', 1348912044, 'sdfsdf', '0.0.0.0', 0, 0, 0, 1),
(196, '', 1, '', '', 1348912078, '[晕][mo.告白]', '0.0.0.0', 0, 0, 0, 1),
(197, '', 1, '', '', 1348912163, '[mo.情人节]', '0.0.0.0', 0, 0, 0, 1),
(198, '', 1, '', '', 1348912182, '[mo.浪漫约会]', '0.0.0.0', 0, 0, 0, 1),
(199, '', 1, '', '', 1348912218, '[mo.浪漫约会]', '0.0.0.0', 0, 0, 2, 1),
(200, '', 1, '', '', 1348929183, '#好声音那英酷似杨过##章子怡否认有染高官##好声音那英酷似杨过#', '0.0.0.0', 0, 0, 1, 1),
(201, '', 1, '', '', 1348930651, '[mp3]http://baidu.com[/mp3]', '0.0.0.0', 0, 0, 6, 1),
(202, '', 1, '', '', 1348931307, '[video]http://xxx.com/xxx.mp4[/video]', '0.0.0.0', 0, 0, 0, 1),
(203, '', 1, '', '', 1348931953, '[mp3]http://localhost/needforbug/upload/data/upload/attachment/day_20120928/8084fc0e1d0e1aa0b856985b28ab43c8.mp3[/mp3]', '0.0.0.0', 0, 0, 7, 1),
(204, '', 1, '', '', 1348933041, '[mp3]http://localhost/needforbug/upload/data/upload/attachment/day_20120929/869b621173142471734589cbced2dc4d.wma[/mp3]', '0.0.0.0', 0, 0, 2, 1),
(205, '', 1, '', '', 1348934006, '[video]http://localhost/needforbug/upload/data/upload/attachment/day_20120928/36551f28b2844a51b5be55c6013af6d7.swf[/video]', '0.0.0.0', 0, 0, 2, 1),
(206, '', 1, '', '', 1348935039, '[video]http://localhost/needforbug/upload/data/upload/attachment/day_20120928/1a2358028536b799f4b6bb67f7e1cb15.rmvb[/video]', '0.0.0.0', 0, 0, 4, 1),
(207, '', 1, '', '', 1348937973, '[video]http://localhost/needforbug/upload/data/upload/attachment/day_20120928/cf41b691478c66640c97850f3b0abb1d.mp4[/video]', '0.0.0.0', 0, 0, 4, 1),
(208, '', 1, '', '', 1348950367, '<a href="http://www.5icool.org/demo/2012/a00665/">http://www.5icool.org/demo/2012/a00665/</a>', '0.0.0.0', 0, 0, 3, 1),
(209, '', 1, '', '', 1349491373, '[attachment]2[/attachment][attachment]3[/attachment][attachment]1[/attachment]', '0.0.0.0', 0, 0, 37, 1),
(210, '', 1, '', '', 1350358870, '[attachment]26[/attachment][attachment]25[/attachment]士大夫似的', '0.0.0.0', 0, 0, 1, 1),
(211, '', 1, '', '', 1351240918, '哈哈，Hello', '0.0.0.0', 0, 0, 10, 1),
(212, '', 1, '', '', 1352344752, '是对方是否 是<br />', '0.0.0.0', 0, 0, 0, 1),
(213, '', 1, '', '', 1352344770, 'dsfsdf士大夫', '0.0.0.0', 0, 0, 0, 1),
(214, '', 1, '', '', 1352344826, '测试一下feed效果，hehe', '0.0.0.0', 0, 0, 0, 1),
(215, '', 1, '', '', 1352344947, '不错哈，哈哈哈。可以额', '0.0.0.0', 0, 0, 0, 1),
(216, '', 1, '', '', 1352347129, '在测试一下<br />', '0.0.0.0', 0, 0, 0, 1),
(217, '', 1, '', '', 1352347354, '可以啊', '0.0.0.0', 0, 0, 1, 1),
(218, '', 6, '', '', 1352347385, 'sdf士大夫似的都是士大夫', '0.0.0.0', 0, 0, 2, 1),
(219, '', 6, '', '', 1352347407, 'df士大夫似的商店', '0.0.0.0', 0, 0, 5, 1),
(220, '', 1, '', '', 1352444868, '不错哈，哈哈', '0.0.0.0', 0, 0, 4, 1),
(221, '', 1, '', '', 1352445908, '技术还是十分领先的,呵呵', '0.0.0.0', 0, 0, 77, 1),
(222, '', 1, '', '', 1353314096, '<p>\n	测试一下[attachment]45[/attachment][晕]\n</p>', '0.0.0.0', 0, 0, 0, 1),
(223, '', 1, '', '', 1353314356, '谢谢.[attachment]45[/attachment]哈[汗]', '0.0.0.0', 0, 0, 7, 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_homefreshcomment`
--

CREATE TABLE IF NOT EXISTS `needforbug_homefreshcomment` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=288 ;

--
-- 转存表中的数据 `needforbug_homefreshcomment`
--

INSERT INTO `needforbug_homefreshcomment` (`homefreshcomment_id`, `create_dateline`, `update_dateline`, `user_id`, `homefreshcomment_name`, `homefreshcomment_content`, `homefreshcomment_email`, `homefreshcomment_url`, `homefreshcomment_status`, `homefreshcomment_ip`, `homefreshcomment_parentid`, `homefreshcomment_isreplymail`, `homefreshcomment_ismobile`, `homefreshcomment_auditpass`, `homefresh_id`) VALUES
(5, 1346059866, 0, 1, '小牛哥', '哈哈，我来了', 'log1990@126.com', 'http://baidu.com', 1, '', 0, 1, 0, 1, '59'),
(6, 1346059896, 0, 1, '小马哥', '不错，多死点', 'log1990@126.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(7, 1346059907, 0, 1, '小韦', '路过', 'log1990@126.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(8, 1346064982, 0, 1, '小诸葛', '谢谢，你很不错了', '', '', 1, '', 0, 0, 0, 1, '59'),
(9, 1346065027, 0, 1, '你好', '哈哈，瓜西西的', '', '', 1, '', 0, 0, 0, 1, '59'),
(10, 1346065937, 0, 1, 'admin', '哈哈，瓜西西的', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(11, 1346065950, 0, 1, 'admin', '恩，不错了', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(12, 1346065956, 0, 1, 'admin', '清理', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(13, 1346065962, 0, 1, 'admin', '新闻好', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(14, 1346065969, 0, 1, 'admin', '友使用', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(15, 1346065976, 0, 1, 'admin', '欢迎光临', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(16, 1346065982, 0, 1, 'admin', '哈哈', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(17, 1346069163, 0, 1, 'admin', '哈哈，不错啊', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '54'),
(18, 1346071047, 0, 1, 'admin', '', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(19, 1346079177, 0, 1, 'admin', 'sdfsdfsdfsdfsdfdsf', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(20, 1346079612, 0, 1, 'admin', 'dsf', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(21, 1346080937, 0, 1, 'admin', '六合彩', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(22, 1346080942, 0, 1, 'admin', '六合彩', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(23, 1346081582, 0, 1, 'admin', '的郭德纲', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(24, 1346081599, 0, 1, 'admin', '斯蒂芬地方', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(25, 1346081872, 0, 1, 'admin', '的房贷首付', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(26, 1346081876, 0, 1, 'admin', '的房贷首付', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(27, 1346081881, 0, 1, 'admin', '的房贷首付', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(28, 1346081895, 0, 1, 'admin', '的房贷首付', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(29, 1346081899, 0, 1, 'admin', '的房贷首付', 'admin@admin.com', 'http://baidu.com', 1, '', 0, 0, 0, 1, '59'),
(30, 1346082027, 0, 1, 'admin', '你好', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(31, 1346082436, 0, 1, 'admin', '我X，好多限制啊', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(32, 1346083789, 0, 1, 'admin', '斯蒂芬斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(33, 1346084421, 0, 1, 'admin', '斯蒂芬斯蒂芬dsfsdf', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(34, 1346084512, 0, 1, 'admin', 'dsfsdfffffffff斯蒂芬斯蒂芬是', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(35, 1346084548, 0, 1, 'admin', 'dfssssssssssss斯蒂芬斯蒂芬斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(36, 1346084607, 0, 1, 'admin', 'dfssssssssssss斯蒂芬斯蒂芬斯蒂芬sdfsdfs', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(37, 1346084624, 0, 1, 'admin', 'dfssssssssssss斯蒂芬斯蒂芬斯蒂芬sdfsdfs第三方斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(38, 1346084654, 0, 1, 'admin', 'dfssssssssssss斯蒂芬斯蒂芬斯蒂芬sdfsdfs第三方斯蒂芬第三方身份', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(39, 1346084701, 0, 1, 'admin', '地方水水水水水水水水水水水水水事实上', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(40, 1346086693, 0, 1, 'admin', 'dsfffff斯蒂芬斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(41, 1346086705, 0, 1, 'admin', 'dsfsdfs是否斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(42, 1346086756, 0, 1, 'admin', '瑞特呃呃呃呃呃呃呃呃呃呃呃呃呃呃呃', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(43, 1346086864, 0, 1, 'admin', '瑞特呃呃呃呃呃呃呃呃呃呃呃呃呃呃呃dsffffffffffffffffff山东分舵斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(44, 1346087018, 0, 1, 'admin', 'fgdfg第三方斯蒂芬斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(45, 1346088487, 0, 1, 'admin', '但是反反复复反反复复反反复复发', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 1, 0, 1, '59'),
(46, 1346088591, 0, 1, 'admin', 'dsfff是对方是否第三方是上帝发', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(47, 1346120491, 0, 1, 'admin', 'DSFSFSDF"DFSFSDFre圣大非省的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '67'),
(48, 1346139035, 0, 1, 'admin', '不错的功能哈', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(49, 1346139061, 0, 1, 'admin', '呵呵，那确实啊。我也很喜欢这个功能哦', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(50, 1346140587, 0, 1, 'admin', '哈哈，bucolic', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(51, 1346141080, 0, 1, 'admin', '哈哈，bong', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(52, 1346141117, 0, 1, 'admin', '斯蒂芬斯蒂芬的说法是的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(53, 1346141121, 0, 1, 'admin', '斯蒂芬斯蒂芬的说法是的是地方的说法', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(54, 1346141125, 0, 1, 'admin', '斯蒂芬斯蒂芬的说法是的是地方的说法的说法的说法', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(55, 1346141141, 0, 1, 'admin', '斯蒂芬斯蒂芬的说法是的是地方的说法的说法的说法的说法是的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(56, 1346141230, 0, 1, 'admin', '达省份圣大非省的仿盛大', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(57, 1346141233, 0, 1, 'admin', '达省份圣大非省的仿盛大第三方斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(58, 1346141235, 0, 1, 'admin', '达省份圣大非省的仿盛大第三方斯蒂仿盛大芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(59, 1346141352, 0, 1, 'admin', '打扫反反复复反反复复方法发', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(60, 1346141364, 0, 1, 'admin', '第三方斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(61, 1346141400, 0, 1, 'admin', '达省份圣大非省的仿盛大是地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(62, 1346141414, 0, 1, 'admin', '斯蒂芬斯蒂芬是地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(63, 1346141513, 0, 1, 'admin', '斯蒂芬斯蒂芬是否但是', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(64, 1346141523, 0, 1, 'admin', '发誓地方是，god', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(65, 1346142200, 0, 1, 'admin', 'dsfdsf第三方斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(66, 1346142716, 0, 1, 'admin', '共和国和', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(67, 1346408277, 0, 1, '', '测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(68, 1346408679, 0, 1, 'admin', '而特特', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '59'),
(69, 1346408716, 0, 1, 'admin', 'v刹形成v', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(70, 1346409027, 0, 1, '', '购房合法化', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(71, 1346409079, 0, 1, '', '速度发生的发生', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(72, 1346409164, 0, 1, '', '史蒂夫速度发生的发生发送', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(73, 1346409421, 0, 1, '', '打发士大夫', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(74, 1346559099, 0, 1, '', 'cdfsdlfjdfjsd;fjds;jfs;', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(75, 1346559480, 0, 1, '', 'dsfsdfsd', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '86'),
(76, 1346559525, 0, 1, '', 'fsdfsdf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '88'),
(77, 1346559548, 0, 1, '', 'dfsdfsdf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '86'),
(78, 1346559622, 0, 1, '', 'FSDF', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '87'),
(79, 1346559684, 0, 1, '', 'dfsdfsfdsdf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '87'),
(80, 1346560928, 0, 1, '', '胜多负少', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '90'),
(81, 1346564904, 0, 1, '', '测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(82, 1346565010, 0, 1, '', '测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(83, 1346565239, 0, 1, '', '测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(84, 1346565251, 0, 1, '', '你好胜多负少你好胜多负少你好你好胜多负少胜多负少你好胜多负少你好胜多负少你好你好胜多负少胜多负少', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(85, 1346565960, 0, 1, '', '打发士大夫', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(86, 1346565968, 0, 1, '', '大法师的法师', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(87, 1346565989, 0, 1, '', '1313', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(88, 1346566554, 0, 1, '', 'ceshi', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(89, 1346566688, 0, 1, '', 'ceshi', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(90, 1346566710, 0, 1, '', 'ceshi', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(91, 1346566739, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(92, 1346569888, 0, 1, '', 'dmin:你好胜多负少\n2012-20-22dmin:你好胜多负少\n2012-20-22', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(93, 1346569908, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PH', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(94, 1346569934, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PH', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(95, 1346569977, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PH', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(96, 1346569984, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PH', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '92'),
(97, 1346569997, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(98, 1346570054, 0, 1, '', 'admin:这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中\n2012-20-22', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(99, 1346570235, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(100, 1346570401, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(101, 1346570458, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(102, 1346570480, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(103, 1346570722, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(104, 1346570791, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(105, 1346570812, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(106, 1346570840, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(107, 1346570904, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(108, 1346570944, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(109, 1346570965, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(110, 1346570996, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(111, 1346571110, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '96'),
(112, 1346571181, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也就是说插件是一种非侵入式的模块化设计，实现了核心程序与插件程序的松散耦合。一个典型的例子就是Wordpress中众多的第三方插件，比如Akimet插件用于对用户的评论进行Spam过滤。 一个健壮的插件机制，我认为必须具备以下特点： 插件的动态监听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '95'),
(113, 1346571204, 0, 1, '', '这篇文章的出发点是我对插件机制的理解，及其在PHP中的实现。此方案仅是插件机制在PHP中的实现方案之一，写下来和大家分享，欢迎大家一起讨论。 插件，亦即Plug-in，是指一类特定的功能模块（通常由第三方开发者实现），它的特点是：当你需要它的时候激活它，不需要它的时候禁用/删除它；且无论是激活还是禁用都不影响系统核心模块的运行，也', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(114, 1346571612, 0, 1, '', '<div style="position:relative;">\n				<div style="position:absolute;top:0px;margin-right:10px;"><img src="<!--{:Core_Extend::avatar(1,''small'')}-->" class="thumbnail" width="32px" height="32px"/>\n				</div>\n				<div style="position:relative;padding-left:50px;">admin:''+data.homefreshcomment_content+''<br/>2012-20-22</div>\n			</div>\n			<div class="clear"></div', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(115, 1346571628, 0, 1, '', '听和加载（Lookup） 插件的动态触发 以上两点的实现均不影响核心程序的运行 要在程序中实现插件，我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(116, 1346572180, 0, 1, '', '我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(117, 1346572196, 0, 1, '', '我们首先应该想到的就是定义不同的钩子（Hooks）；“钩子”是一个很形象的逻辑概念，你可以认为它是系统预留的插件触发条件。它的逻辑原理如下：当系统执行到某个钩子时，会判断这个钩子的条件是否满足；如果满足，会转而先去调用钩子所制定的功能，然后返回继续执行余下的程序；如果不满足，跳过即可。这有点像汇编中', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(118, 1346572990, 0, 18, '', '头像测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(119, 1346573011, 0, 18, '', '测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '94'),
(120, 1346573203, 0, 18, '', '平', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(121, 1346573230, 0, 18, '', '评论', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(122, 1346573246, 0, 18, '', '点对点', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(123, 1346573373, 0, 18, '', '测试了', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(124, 1346573420, 0, 18, '', '测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '88'),
(125, 1346573494, 0, 18, '', '大幅度', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '88'),
(126, 1346573751, 0, 18, '', '从树上', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(127, 1346574530, 0, 18, '', 'shsihs', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '92'),
(128, 1346574635, 0, 18, '', 'ceshi', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '91'),
(129, 1346574662, 0, 18, '', 'ceshi', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '83'),
(130, 1346574813, 0, 18, '', 'sdfsfs', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '84'),
(131, 1346574851, 0, 18, '', 'dfsdfs', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '84'),
(132, 1346574885, 0, 18, '', 'fsdfsd', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '84'),
(133, 1346574889, 0, 18, '', 'fsdfsdfs', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '84'),
(134, 1346575011, 0, 18, '', 'dfdf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '85'),
(135, 1346575018, 0, 18, '', 'dfsdfs', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '85'),
(136, 1346575023, 0, 18, '', 'dfsdfsdf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '85'),
(137, 1346575121, 0, 18, '', 'fdsf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '88'),
(138, 1346575137, 0, 18, '', 'dfdfd', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(139, 1346577959, 0, 18, '', 'fsdfdsf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(140, 1346577963, 0, 18, '', 'dfsdf', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '93'),
(141, 1346580766, 0, 18, '', '打发第三方', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '91'),
(142, 1346580801, 0, 18, '', '打发士大夫', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '95'),
(143, 1346581090, 0, 18, '', '东方时代', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '91'),
(144, 1346581118, 0, 1, '', '重新测试', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '97'),
(145, 1346581803, 0, 1, '', '胜多负少', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '95'),
(146, 1346581810, 0, 1, '', '大幅度', '', '', 1, '0.0.0.0', 0, 0, 0, 1, '95'),
(147, 1346660080, 0, 1, '', '', '', '', 1, '0.0.0.0', 0, 0, 0, 1, ''),
(148, 1346660138, 0, 1, '', '', '', '', 1, '0.0.0.0', 0, 0, 0, 1, ''),
(149, 1346660174, 0, 1, '', '', '', '', 1, '0.0.0.0', 0, 0, 0, 1, ''),
(150, 1346660331, 0, 1, '', '', '', '', 1, '0.0.0.0', 0, 0, 0, 1, ''),
(151, 1346660606, 0, 1, '', '', '', '', 1, '0.0.0.0', 0, 0, 0, 1, ''),
(152, 1346661002, 0, 1, 'admin', 'sdfsdf胜多负少', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '98'),
(153, 1346661013, 0, 1, 'admin', '发生的发生', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '98'),
(157, 1346665633, 0, 1, 'admin', 'sdf是对方是否', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(155, 1346661607, 0, 1, 'admin', 'alert("我在测试2");', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '98'),
(158, 1346665758, 0, 1, 'admin', '是打发士大夫', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '122'),
(159, 1346665765, 0, 1, 'admin', '所发生的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '121'),
(160, 1346665868, 0, 1, 'admin', '士大夫似的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '123'),
(161, 1346665874, 0, 1, 'admin', '士大夫似的收到', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '123'),
(162, 1346744173, 0, 1, 'admin', '是非得失', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '122'),
(163, 1346744487, 0, 1, 'admin', '是打发士大夫胜多负少', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 160, 0, 0, 1, '123'),
(164, 1346745013, 0, 1, 'admin', '我是谁是否', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '122'),
(165, 1346745022, 0, 1, 'admin', '我是新的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 158, 0, 0, 1, '122'),
(166, 1346745051, 0, 1, 'admin', '是否', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 159, 0, 0, 1, '121'),
(167, 1346745406, 0, 1, 'admin', '大大大的地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(168, 1346745413, 0, 1, 'admin', '十分大方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(169, 1346745418, 0, 1, 'admin', '发生的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(170, 1346745427, 0, 1, 'admin', 'sdf速度丰富的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 157, 0, 0, 1, '120'),
(171, 1346745440, 0, 1, 'admin', '是打发士大夫sdf', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 157, 0, 0, 1, '120'),
(172, 1346745497, 0, 1, 'admin', '是打发士大夫士大夫似的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 158, 0, 0, 1, '122'),
(173, 1346745515, 0, 1, 'admin', '速度十分大方说的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 158, 0, 0, 1, '122'),
(174, 1346745542, 0, 1, 'admin', '是打发士大夫似的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 160, 0, 0, 1, '123'),
(175, 1346745576, 0, 1, 'admin', '是打发士大夫士大夫似的胜多负少', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 160, 0, 0, 1, '123'),
(176, 1346747533, 0, 1, 'admin', '哈哈，不凑哈', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 169, 0, 0, 1, '120'),
(177, 1346747749, 0, 1, 'admin', '测试一下哈', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(178, 1346747878, 0, 1, 'admin', '哈哈，不错额', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(179, 1346748690, 0, 1, 'admin', '写法国恢复供货', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '123'),
(180, 1346748699, 0, 1, 'admin', '是打发士大夫是打发士大夫是打发士大夫', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 179, 0, 0, 1, '123'),
(181, 1346748708, 0, 1, 'admin', '是打发士大夫是打发士大夫', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 180, 0, 0, 1, '123'),
(182, 1346748716, 0, 1, 'admin', '是打发士大夫是打发士大夫士大夫似的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 181, 0, 0, 1, '123'),
(183, 1346750608, 0, 1, 'admin', '哈哈，我靠', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '122'),
(184, 1346750620, 0, 1, 'admin', '士大夫似的胜多负少', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 183, 0, 0, 1, '122'),
(185, 1346750728, 0, 1, 'admin', '可以啦', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '121'),
(186, 1346750735, 0, 1, 'admin', '是发生的发生', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 185, 0, 0, 1, '121'),
(187, 1346751204, 0, 1, 'admin', '答复是否', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(188, 1346751232, 0, 1, 'admin', '是打发士大夫是打发士大夫是大大大大大大大大', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 187, 0, 0, 1, '120'),
(189, 1346751301, 0, 1, 'admin', '路过发生的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(190, 1346751313, 0, 1, 'admin', '谢谢你的号故事，我很喜欢', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 189, 0, 0, 1, '120'),
(191, 1346751408, 0, 1, 'admin', '师傅都是法师打发士大夫是否', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 177, 0, 0, 1, '120'),
(192, 1346751420, 0, 1, 'admin', '大范甘迪', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 177, 0, 0, 1, '120'),
(193, 1346751426, 0, 1, 'admin', '士大夫是打发士大夫', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 177, 0, 0, 1, '120'),
(194, 1346751458, 0, 1, 'admin', '我嘻嘻嘻了', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(195, 1346751465, 0, 1, 'admin', '是打发士大夫谢谢', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 194, 0, 0, 1, '120'),
(196, 1346751522, 0, 1, 'admin', '我额胜多负少发生的发生的丰富的士大夫似的发士大夫收到飞', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '120'),
(197, 1346751531, 0, 1, 'admin', '号挂你哈 发的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 196, 0, 0, 1, '120'),
(198, 1346751536, 0, 1, 'admin', '大师傅说道', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 196, 0, 0, 1, '120'),
(199, 1346751541, 0, 1, 'admin', '速度发生的发生', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 196, 0, 0, 1, '120'),
(200, 1346751607, 0, 1, 'admin', '谢谢你的宽带', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(201, 1346751630, 0, 1, 'admin', '好的哈，哈哈', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(202, 1346751674, 0, 1, 'admin', '很好的故事', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(203, 1346751927, 0, 1, 'admin', '测试一下', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(204, 1346752010, 0, 1, 'admin', '@admin 符合规划法国恢复供货', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(205, 1346753721, 0, 6, 'xiaomage', '小马哥来了哈', '635750556@qqqq.com', '', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(206, 1346753799, 0, 6, 'xiaomage', '我有来了好啊', '635750556@qqqq.com', '', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(207, 1346753821, 0, 6, 'xiaomage', '@xiaomage 可以了，这里好哈', '635750556@qqqq.com', '', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(208, 1346808392, 0, 1, 'admin', '哈哈，可以啊', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(209, 1346810354, 0, 1, 'admin', '@admin tr是地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(210, 1346810380, 0, 1, 'admin', '哈哈不错才', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(211, 1346810472, 0, 1, 'admin', '@xiaomage 大法官', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(212, 1346810481, 0, 1, 'admin', '各打各的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(213, 1346810492, 0, 1, 'admin', '@xiaomage 风格和风格和', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(214, 1346810505, 0, 1, 'admin', '@admin 好客爱啊', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 1, '125'),
(215, 1346811686, 0, 1, 'admin', '好股的哇', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 212, 0, 0, 1, '125'),
(216, 1346811773, 0, 1, 'admin', '谢谢你哈', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(217, 1346811791, 0, 1, 'admin', '我想', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(218, 1346811801, 0, 1, 'admin', '是地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 216, 0, 0, 1, '125'),
(219, 1346811856, 0, 1, 'admin', '@admin 7676的说法', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 212, 0, 0, 1, '125'),
(220, 1346811870, 0, 1, 'admin', '是的方式', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 212, 0, 0, 1, '125');
INSERT INTO `needforbug_homefreshcomment` (`homefreshcomment_id`, `create_dateline`, `update_dateline`, `user_id`, `homefreshcomment_name`, `homefreshcomment_content`, `homefreshcomment_email`, `homefreshcomment_url`, `homefreshcomment_status`, `homefreshcomment_ip`, `homefreshcomment_parentid`, `homefreshcomment_isreplymail`, `homefreshcomment_ismobile`, `homefreshcomment_auditpass`, `homefresh_id`) VALUES
(221, 1346811878, 0, 1, 'admin', '第三方斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(222, 1346811882, 0, 1, 'admin', '的说法是的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(223, 1346811890, 0, 1, 'admin', '的说法是的圣大非省的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(224, 1346811895, 0, 1, 'admin', '说得反反复复方法反反复复发', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(225, 1346811902, 0, 1, 'admin', '第三方斯蒂芬圣大非省的', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(226, 1346811917, 0, 1, 'admin', '是地方是地方但是', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(227, 1346812287, 0, 1, 'admin', 'dsf 是地方但是', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 226, 0, 0, 1, '125'),
(228, 1346812294, 0, 1, 'admin', '@admin 是地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 212, 0, 0, 1, '125'),
(229, 1346812306, 0, 1, 'admin', '是地方是地方', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 225, 0, 0, 1, '125'),
(230, 1346816070, 0, 1, 'admin', 'sfsdf斯蒂芬斯蒂芬', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '115'),
(231, 1346817768, 0, 1, 'admin', '哈哈', 'admin@admin.com', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(232, 1346818245, 0, 1, 'admin', '哈哈可以啊', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 1, 0, 1, '125'),
(233, 1346818267, 0, 1, 'admin', '我Xsss', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 232, 0, 0, 1, '125'),
(234, 1346828359, 0, 1, 'admin', 'sadasdsdf似懂非懂', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '125'),
(235, 1346853993, 0, 1, 'admin', '斯蒂芬斯蒂芬', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '126'),
(236, 1346854000, 0, 1, 'admin', '第三方斯蒂芬斯蒂芬', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 235, 0, 0, 1, '126'),
(237, 1346854007, 0, 1, 'admin', '斯蒂芬斯蒂芬斯蒂芬斯蒂芬第三方第三方但是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 235, 0, 0, 1, '126'),
(238, 1346854013, 0, 1, 'admin', '斯蒂芬速度反反复复反反复复反反复复发', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 235, 0, 0, 1, '126'),
(239, 1346854020, 0, 1, 'admin', '斯蒂芬斯蒂芬速度范德萨范德萨', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 235, 0, 0, 1, '126'),
(240, 1346854026, 0, 1, 'admin', '士大夫第三方斯蒂芬范德萨范德萨', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 235, 0, 0, 1, '126'),
(241, 1346854035, 0, 1, 'admin', '第三方斯蒂芬发生的范德萨', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 235, 0, 0, 1, '126'),
(242, 1346854048, 0, 1, 'admin', '第三方但是斯蒂芬都是范德萨斯蒂芬斯蒂芬速度', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '126'),
(243, 1346855462, 0, 1, 'admin', '第三方身份第三方', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '126'),
(244, 1346855468, 0, 1, 'admin', '斯蒂芬斯蒂芬斯蒂芬是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 243, 0, 0, 1, '126'),
(245, 1346855474, 0, 1, 'admin', '斯蒂芬斯蒂芬速度范德萨', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 243, 0, 0, 1, '126'),
(246, 1346855479, 0, 1, 'admin', '第三方斯蒂芬上的斯蒂芬速度', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 243, 0, 0, 1, '126'),
(247, 1346855483, 0, 1, 'admin', '第三方杀毒方式地方第三方斯蒂芬斯蒂芬', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 243, 0, 0, 1, '126'),
(248, 1346855487, 0, 1, 'admin', '第三方杀毒发生的房贷首付斯蒂芬斯蒂芬', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 243, 0, 0, 1, '126'),
(249, 1346921473, 0, 1, 'admin', '发布评论哈。哈哈', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '127'),
(250, 1346922451, 0, 1, 'admin', '测试ixai', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '127'),
(251, 1346922482, 0, 1, 'admin', 'yess但是方式', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '128'),
(252, 1346922515, 0, 1, 'admin', '我差啊啊啊啊', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '128'),
(253, 1346922542, 0, 1, 'admin', '哈哈，呵呵', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '128'),
(254, 1346922881, 0, 1, 'admin', '是发斯蒂芬斯蒂芬', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '125'),
(255, 1346922952, 0, 1, 'admin', '你好啊', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '126'),
(256, 1346923214, 0, 1, 'admin', '是发斯蒂芬斯蒂芬时代仿盛大', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '120'),
(257, 1346923217, 0, 1, 'admin', '达省份圣大非省的', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '120'),
(258, 1346923225, 0, 1, 'admin', '@admin 是地方但是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 157, 0, 0, 0, '120'),
(259, 1346923230, 0, 1, 'admin', '@admin 是地方是地方是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 157, 0, 0, 0, '120'),
(260, 1346923239, 0, 1, 'admin', '是地方是地方时代', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 157, 0, 0, 0, '120'),
(261, 1346923243, 0, 1, 'admin', '圣大非省的发', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 157, 0, 0, 0, '120'),
(262, 1346924136, 0, 1, 'admin', '斯蒂芬斯蒂芬是否但是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 0, '125'),
(263, 1346924144, 0, 1, 'admin', '圣大非省的否但是否但是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 0, '125'),
(264, 1346924150, 0, 1, 'admin', '是地方圣大非省的发', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 0, '125'),
(265, 1346924157, 0, 1, 'admin', '誓地方是地方', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 0, '125'),
(266, 1346924162, 0, 1, 'admin', '圣大非省的否但是反对', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 0, '125'),
(267, 1346924168, 0, 1, 'admin', '的说法是地方', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 200, 0, 0, 0, '125'),
(268, 1346979500, 1346982219, 1, 'admin', '我是来评论审核的哈', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '129'),
(269, 1346979507, 1346982223, 1, 'admin', '不过的，程序', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '129'),
(270, 1346979584, 0, 1, 'admin', '我是一条自评论', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 268, 0, 0, 0, '129'),
(271, 1346980446, 1346982150, 1, 'admin', '的说法', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 1, '129'),
(272, 1346982392, 0, 1, 'admin', '斯蒂芬斯蒂芬', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '34'),
(273, 1351665926, 0, 1, 'admin', 'sdf士大夫', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 104, 0, 0, 0, '97'),
(274, 1351666010, 0, 1, 'admin', 'sdf士大夫sdf', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 104, 0, 0, 0, '97'),
(275, 1352447063, 0, 1, 'admin', '提示，呵呵，可以额', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221'),
(276, 1352447144, 0, 1, 'admin', '欢迎打击假广陵', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221'),
(277, 1352447232, 0, 1, 'admin', '换来事实上', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221'),
(278, 1352447318, 0, 1, 'admin', '新鲜事评论了，呵呵', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221'),
(279, 1352447474, 0, 1, 'admin', '急促加油', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221'),
(280, 1352447685, 0, 1, 'admin', '亲爱的你还是那样，我非常。', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221'),
(281, 1352798542, 0, 1, 'admin', '士大夫', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 280, 0, 0, 0, '221'),
(282, 1352798553, 0, 1, 'admin', '士大夫士大夫', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 280, 0, 0, 0, '221'),
(283, 1352798562, 0, 1, 'admin', '速度大大大大放松放松士大夫', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 280, 0, 0, 0, '221'),
(284, 1352798572, 0, 1, 'admin', '士大夫商店都是', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 280, 0, 0, 0, '221'),
(285, 1352798583, 0, 1, 'admin', '士大夫士大夫大师傅商店', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 280, 0, 0, 0, '221'),
(286, 1352798599, 0, 1, 'admin', '士大夫似的大师傅大师傅士大夫士大夫商店', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 280, 0, 0, 0, '221'),
(287, 1354526716, 0, 1, 'admin', 'sdfsd鼎折覆餗', 'xiaoniuge@dianniu.net', 'http://baidu.com', 1, '0.0.0.0', 0, 0, 0, 0, '221');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_homehelp`
--

CREATE TABLE IF NOT EXISTS `needforbug_homehelp` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `needforbug_homehelp`
--

INSERT INTO `needforbug_homehelp` (`homehelp_id`, `homehelp_title`, `homehelp_content`, `homehelpcategory_id`, `homehelp_status`, `create_dateline`, `update_dateline`, `user_id`, `homehelp_username`, `homehelp_updateuserid`, `homehelp_updateusername`, `homehelp_viewnum`) VALUES
(1, '欢迎来到我们的帮助宝库！', '{site_name} 欢迎你的到来，希望这里能够帮助到你！', 1, 1, 1340045081, 1340213370, 1, 'admin', 1, 'admin', 0),
(2, '我必须要注册吗？', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">这取决于管理员如何设置 {site_name} 的用户组权限选项，您甚至有可能必须在注册成正式用户后后才能浏览网站。当然，在通常情况下，您至少应该是正式用户才能发新帖和回复已有帖子。请先</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">免费注册成为我们的新用户！&nbsp;</span><br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">强烈建议您注册，这样会得到很多以游客身份无法实现的功能。</span>', 1, 1, 1340163725, 1340213377, 1, 'admin', 1, 'admin', 2),
(3, '我如何登录网站？', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">如果您已经注册成为该论坛的会员，哪么您只要通过访问页面右上的</span><a href="http://bbs.emlog.net/logging.php?action=login" target="_blank" style="word-wrap:break-word;text-decoration:none;color:#000000;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">登录</a><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">，进入登陆界面填写正确的用户名和密码，点击“登录”即可完成登陆如果您还未注册请点击这里。</span><br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<br style="word-wrap:break-word;color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;" />\n<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">如果需要保持登录，请选择相应的 Cookie 时间，在此时间范围内您可以不必输入密码而保持上次的登录状态。</span>', 1, 1, 1340164011, 1340213385, 1, 'admin', 1, 'admin', 2),
(4, '忘记我的登录密码，怎么办？', '', 1, 1, 1340164050, 1340213393, 1, 'admin', 1, 'admin', 9),
(5, '我如何使用个性化头像', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">在</span><span style="font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">头部</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">有一个“修改头像”的选项，可以使用自定义的头像。</span>', 1, 1, 1340164159, 1340213404, 1, 'admin', 1, 'admin', 4),
(6, '我如何修改登录密码', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">在</span><span style="font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">基本信息中</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">，填写“旧密码”，“新密码”，“确认新密码”。点击“提交”，即可修改。</span>', 1, 1, 1340164237, 1343443978, 1, 'admin', 1, 'admin', 22),
(7, '我如何使用个性化签名和昵称', '<span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">在</span><span style="font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">个人资料中</span><span style="color:#444444;font-family:Verdana, Helvetica, Arial, sans-serif;font-size:14px;line-height:19px;text-align:left;white-space:normal;background-color:#FFFFFF;">，有一个“昵称”和“个人签名”的选项，可以在此设置。</span>', 1, 1, 1340164280, 1343444041, 1, 'admin', 1, 'admin', 27),
(8, '我如何使用“会员”功能', '<ul>\n	<li>\n		<span style="white-space:nowrap;">须首先登录，没有用户名的请先注册；</span> \n	</li>\n	<li>\n		<span style="white-space:nowrap;">登录之后在论坛的左上方会出现一个“个人中心”的超级链接，点击这个链接之后就可进入到有关于您的信息。</span> \n	</li>\n</ul>', 2, 1, 1340164420, 1343444036, 1, 'admin', 1, 'admin', 45),
(20, 'test', '{site_name}的方式硕鼠硕鼠搜索的说法', 1, 1, 1343295512, 1345171983, 1, 'admin', 1, 'admin', 32);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_homehelpcategory`
--

CREATE TABLE IF NOT EXISTS `needforbug_homehelpcategory` (
  `homehelpcategory_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '帮助分类ID',
  `homehelpcategory_name` char(32) NOT NULL DEFAULT '' COMMENT '帮助分类名字',
  `homehelpcategory_count` int(10) NOT NULL DEFAULT '0' COMMENT '帮助个数',
  `homehelpcategory_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '帮助分类排序名字',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `create_dateline` int(10) NOT NULL COMMENT '群组创建时间',
  PRIMARY KEY (`homehelpcategory_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `homehelpcategory_sort` (`homehelpcategory_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `needforbug_homehelpcategory`
--

INSERT INTO `needforbug_homehelpcategory` (`homehelpcategory_id`, `homehelpcategory_name`, `homehelpcategory_count`, `homehelpcategory_sort`, `update_dateline`, `create_dateline`) VALUES
(1, '用户须知', 7, 0, 1340187070, 1340171834),
(2, '基本功能操作', 1, 0, 1340187040, 1340162722),
(3, '其他相关问题', 0, 0, 1340524577, 1340162735),
(10, 'sdf', 0, 0, 0, 1353292791);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_homeoption`
--

CREATE TABLE IF NOT EXISTS `needforbug_homeoption` (
  `homeoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `homeoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`homeoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_homeoption`
--

INSERT INTO `needforbug_homeoption` (`homeoption_name`, `homeoption_value`) VALUES
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
-- 表的结构 `needforbug_homesite`
--

CREATE TABLE IF NOT EXISTS `needforbug_homesite` (
  `homesite_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `homesite_name` char(32) NOT NULL DEFAULT '' COMMENT '键值',
  `homesite_nikename` char(32) NOT NULL COMMENT '站点信息别名',
  `homesite_content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`homesite_id`),
  UNIQUE KEY `homesite_name` (`homesite_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `needforbug_homesite`
--

INSERT INTO `needforbug_homesite` (`homesite_id`, `homesite_name`, `homesite_nikename`, `homesite_content`) VALUES
(1, 'aboutus', '关于我们', '<h3>\n	社区化电子商务\n</h3>\n{site_name} 致力于打造以社区为基础的电子商务平台。<br />\n<span style="white-space:nowrap;"></span><br />\n<h3>\n	我们的目标\n</h3>\n我们的理念：{site_description}'),
(2, 'contactus', '联系我们', '<h3>\n	联系我们\n</h3>\n<p>\n	如果您对本站有任何疑问或建议，请通过以下方式联系我们：{admin_email}\n</p>'),
(3, 'agreement', '用户协议', '<div class="hero-unit">\n	<h4>\n		用户内容知识共享\n	</h4>\n	<ul>\n		<li>\n			自由复制、发行、展览、表演、放映、广播或通过信息网络传播本作品\n		</li>\n		<li>\n			自由创作演绎作品\n		</li>\n		<li>\n			自由对本作品进行商业性使用\n		</li>\n	</ul>\n	<h4>\n		惟须遵守下列条件\n	</h4>\n	<ul>\n		<li>\n			署名－您必须按照作者或者许可人指定的方式对作品进行署名。\n		</li>\n		<li>\n			相同方式共享－如果您改变、转换本作品或者以本作品为基础进行创作，您只能采用与本协议相同的许可协议发布基于本作品的演绎作品。\n		</li>\n	</ul>\n</div>\n<h3>\n	服务条款确认与接纳\n</h3>\n<p>\n	{site_name} 拥有 {site_url}&nbsp;及其涉及到的产品、相关软件的所有权和运作权， {site_name} 享有对 {site_url} 上一切活动的监督、提示、检查、纠正及处罚等权利。用户通过注册程序阅读本服务条款并点击"同意"按钮完成注册，即表示用户与 {site_name} 已达成协议，自愿接受本服务条款的所有内容。如果用户不同意服务条款的条件，则不能获得使用 {site_name} 服务以及注册成为用户的权利。\n</p>\n<h3>\n	使用规则\n</h3>\n<ol>\n	<li>\n		用户注册成功后，{site_name} 将给予每个用户一个用户帐号及相应的密码，该用户帐号和密码由用户负<span style="white-space:nowrap;">责保管；用户应当对以其用户帐号进行的所有活动和事件负法律责任。</span> \n	</li>\n	<li>\n		用户须对在 {site_name} 的注册信息的真实性、合法性、有效性承担全部责任，用户不得冒充他人；不得利用他人的名义发布任何信息；不得恶意使用注册帐户导致其他用户误认；否则 {site_name} 有权立即停止提供服务，收回其帐号并由用户独自承担由此而产生的一切法律责任。\n	</li>\n	<li>\n		用户不得使用 {site_name} 服务发送或传播敏感信息和违反国家法律制度的信息，包括但不限于下列信息:\n		<ul>\n			<li>\n				反对宪法所确定的基本原则的；\n			</li>\n			<li>\n				危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；\n			</li>\n			<li>\n				损害国家荣誉和利益的；\n			</li>\n			<li>\n				煽动民族仇恨、民族歧视，破坏民族团结的；\n			</li>\n			<li>\n				破坏国家宗教政策，宣扬邪教和封建迷信的；\n			</li>\n			<li>\n				散布谣言，扰乱社会秩序，破坏社会稳定的；\n			</li>\n			<li>\n				散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；\n			</li>\n			<li>\n				侮辱或者诽谤他人，侵害他人合法权益的\n			</li>\n			<li>\n				含有法律、行政法规禁止的其他内容的。\n			</li>\n		</ul>\n	</li>\n	<li>\n		{site_name} 有权对用户使用 {site_name}&nbsp;的情况进行审查和监督，如用户在使用 {site_name} 时违反任何上述规定，{site_name} 或其授权的人有权要求用户改正或直接采取一切必要的措施（包括但不限于删除用户张贴的内容、暂停或终止用户使用 {site_name}&nbsp;的权利）以减轻用户不当行为造成的影响。\n	</li>\n	<li>\n		盗取他人用户帐号或利用网络通讯骚扰他人，均属于非法行为。用户不得采用测试、欺骗等任何非法手段，盗取其他用户的帐号和对他人进行骚扰。\n	</li>\n</ol>\n<h3>\n	知识产权\n</h3>\n<ol>\n	<li>\n		用户保证和声明对其所提供的作品拥有完整的合法的著作权或完整的合法的授权可以用于其在 {site_name} 上从事&gt;的活动，保证 {site_name} 使用该作品不违反国家的法律法规，也不侵犯第三方的合法权益或承担任何义务。用户应对其所提供作品因形式、内容及授权的不完善、不合法所造成的一切后果承担完全责任。\n	</li>\n	<li>\n		对于经用户本人创作并上传到 {site_name} 的文本、图片、图形等， {site_name} 保留对其网站所有内容进行实时监控的权利，并有权依其独立判断对任何违反本协议约定的作品实施删除。{site_name} 对于删除用户作品引起的任何后果或导致用户的任何损失不负任何责任。\n	</li>\n	<li>\n		因用户作品的违法或侵害第三人的合法权益而导致 {site_name} 或其关联公司对第三方承担任何性质的赔偿、补偿或罚款而遭受损失（直接的、间接的、偶然的、惩罚性的和继发的损失），用户对于 {site_name} 或其关联公司蒙受的上述损失承担全面的赔偿责任。\n	</li>\n	<li>\n		任何第三方，都可以在遵循 《<a href="http://creativecommons.org/licenses/by-sa/2.5/cn/" target="_blank" style="white-space:nowrap;">知识共享署名-相同方式共享 2.5 中国大陆许可协议</a>》 的情况下分享本站用户创造的内容。\n	</li>\n</ol>\n<h3>\n	免责声明\n</h3>\n<p>\n	<br />\n</p>\n<ul>\n	<li>\n		{site_name} 不能对用户在本社区回答问题的答案或评论的准确性及合理性进行保证。\n	</li>\n	<li>\n		若{site_name} 已经明示其网络服务提供方式发生变更并提醒用户应当注意事项，用户未按要求操作所产生的一切后果由用户自行承担。\n	</li>\n	<li>\n		用户明确同意其使用 {site_name} 网络服务所存在的风险将完全由其自己承担；因其使用 {site_name} 服务而产生的一切后果也由其自己承担，{site_name} 对用户不承担任何责任。\n	</li>\n	<li>\n		{site_name} 不保证网络服务一定能满足用户的要求，也不保证网络服务不会中断，对网络服务的及时性、安全性、准确性也都不作保证。\n	</li>\n	<li>\n		对于因不可抗力或 {site_name} 不能控制的原因造成的网络服务中断或其它缺陷，{site_name} 不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。\n	</li>\n	<li>\n		用户同意保障和维护 {site_name} 及其他用户的利益，用户在 {site_name} 发表的内容仅表明其个人的立场和观点，并不代表 {site_name} 的立场或观点。由于用户发表内容违法、不真实、不正当、侵犯第三方合法权益，或用户违反本协议项下的任何条款而给 {site_name} 或任何其他第三人造成损失，用户同意承担由此造成的损害赔偿责任。\n	</li>\n</ul>\n<p>\n	<br />\n</p>\n<h3>\n	服务条款的修改\n</h3>\n<p>\n	{site_name} 会在必要时修改服务条款，服务条款一旦发生变动，{site_name} 将会在用户进入下一步使用前的页面提示修改内容。如果您同意改动，则再一次激活"我同意"按钮。如果您不接受，则及时取消您的用户使用服务资格。 用户要继续使用 {site_name} 各项服务需要两方面的确认:\n</p>\n<ol>\n	<li>\n		首先确认 {site_name} 服务条款及其变动。\n	</li>\n	<li>\n		同意接受所有的服务条款限制。\n	</li>\n</ol>\n<h3>\n	联系我们\n</h3>\n<p>\n	如果您对此服务条款有任何疑问或建议，请通过以下方式联系我们：{admin_email}\n</p>'),
(4, 'privacy', '隐私政策', '<h3>\n	隐私政策\n</h3>\n<p>\n{site_name}（{site_url}）以此声明对本站用户隐私保护的许诺。{site_name} 的隐私声明正在不断改进中，随着 {site_name} 服务范围的扩大，会随时更新隐私声明，欢迎您随时查看隐私声明。<br />\n</p>\n<h3>\n	隐私政策\n</h3>\n<p>{site_name} 非常重视对用户隐私权的保护，承诺不会在未获得用户许可的情况下擅自将用户的个人资料信息出租或出售给任何第三方，但以下情况除外:<br />\n您同意让第三方共享资料；<br />\n<ul>\n	<li>\n		您同意公开你的个人资料，享受为您提供的产品和服务；\n	</li>\n	<li>\n		本站需要听从法庭传票、法律命令或遵循法律程序；\n	</li>\n	<li>\n		本站发现您违反了本站服务条款或本站其它使用规定。\n	</li>\n</ul>\n</p>');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_hometag`
--

CREATE TABLE IF NOT EXISTS `needforbug_hometag` (
  `hometag_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '用户标签',
  `hometag_name` char(32) NOT NULL DEFAULT '' COMMENT '标签名字',
  `hometag_count` int(10) NOT NULL DEFAULT '0' COMMENT '标签用户数量',
  `hometag_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否可用',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`hometag_id`),
  KEY `hometag_name` (`hometag_name`),
  KEY `hometag_status` (`hometag_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- 转存表中的数据 `needforbug_hometag`
--

INSERT INTO `needforbug_hometag` (`hometag_id`, `hometag_name`, `hometag_count`, `hometag_status`, `create_dateline`) VALUES
(9, '90后', 1, 0, 1339432495),
(11, 'dsf', 1, 1, 1339688768),
(12, 'sdf', 1, 1, 1339688771),
(10, 'IT', 1, 1, 1339432495),
(13, '67', 1, 1, 1345777926);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_hometagindex`
--

CREATE TABLE IF NOT EXISTS `needforbug_hometagindex` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `hometag_id` int(10) NOT NULL DEFAULT '0' COMMENT '标签ID',
  PRIMARY KEY (`user_id`,`hometag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_hometagindex`
--

INSERT INTO `needforbug_hometagindex` (`user_id`, `hometag_id`) VALUES
(1, 9),
(1, 10),
(1, 11);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_link`
--

CREATE TABLE IF NOT EXISTS `needforbug_link` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `needforbug_link`
--

INSERT INTO `needforbug_link` (`link_id`, `create_dateline`, `update_dateline`, `link_name`, `link_url`, `link_description`, `link_logo`, `link_status`, `link_sort`) VALUES
(5, 1340369066, 1343901367, '水军', '探索网络水军', '探索网络水军', '', 1, 0),
(4, 1340369020, 1343812340, 'vps主机', 'vps主机', 'Linux主机提供商', '', 1, 0),
(6, 1340369108, 1343901624, 'A5源码', 'http://admin5.com', '下载最新源码', '', 1, 0),
(8, 1340369511, 1352960174, 'Canphp框架', '\\/canphp.com', 'Canphp管网', '', 1, 0),
(10, 1340369589, 0, '听我网', 'http://www.tvery.com/', '', '', 1, 0),
(11, 1340369604, 1343815858, '百度', 'http://baidu.com', '', '', 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_loginlog`
--

CREATE TABLE IF NOT EXISTS `needforbug_loginlog` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=648 ;

--
-- 转存表中的数据 `needforbug_loginlog`
--

INSERT INTO `needforbug_loginlog` (`loginlog_id`, `user_id`, `create_dateline`, `update_dateline`, `loginlog_user`, `loginlog_ip`, `loginlog_status`, `login_application`) VALUES
(1, 1, 1343569273, 0, 'admin', '0.0.0.0', 1, 'admin'),
(2, 1, 1343576039, 0, 'admin', '0.0.0.0', 1, 'admin'),
(3, 1, 1343610414, 0, 'admin', '0.0.0.0', 1, 'admin'),
(4, 1, 1343617888, 0, 'admin', '0.0.0.0', 1, 'admin'),
(5, 1, 1343619071, 0, 'admin', '0.0.0.0', 1, 'admin'),
(6, 1, 1343636185, 0, 'admin', '0.0.0.0', 1, 'admin'),
(7, 1, 1343636470, 0, 'admin', '0.0.0.0', 1, 'admin'),
(8, 1, 1343636973, 0, 'admin', '0.0.0.0', 1, 'admin'),
(9, 1, 1343642687, 0, 'admin', '0.0.0.0', 1, 'admin'),
(10, 1, 1343644099, 0, 'admin', '0.0.0.0', 1, 'admin'),
(11, 1, 1343697317, 0, 'admin', '0.0.0.0', 1, 'admin'),
(12, 0, 1343700295, 0, 'adminsdf', '0.0.0.0', 0, 'admin'),
(13, 1, 1343700309, 0, 'admin', '0.0.0.0', 1, 'admin'),
(14, 1, 1343700649, 0, 'admin', '0.0.0.0', 1, 'admin'),
(15, 1, 1343700668, 0, 'admin', '0.0.0.0', 1, 'admin'),
(16, 0, 1343700692, 0, 'admin', '0.0.0.0', 0, 'admin'),
(17, 1, 1343700696, 0, 'admin', '0.0.0.0', 1, 'admin'),
(18, 1, 1343700999, 0, 'admin', '0.0.0.0', 1, 'admin'),
(19, 1, 1343701011, 0, 'admin', '0.0.0.0', 1, 'admin'),
(20, 1, 1343701123, 0, 'admin', '0.0.0.0', 1, 'admin'),
(21, 1, 1343701155, 0, 'admin', '0.0.0.0', 1, 'admin'),
(22, 1, 1343701223, 0, 'admin', '0.0.0.0', 1, 'admin'),
(23, 1, 1343701373, 0, 'admin', '0.0.0.0', 1, 'admin'),
(24, 1, 1343701419, 0, 'admin', '0.0.0.0', 1, 'admin'),
(25, 1, 1343701466, 0, 'admin', '0.0.0.0', 1, 'admin'),
(26, 1, 1343701808, 0, 'admin', '0.0.0.0', 1, 'admin'),
(27, 1, 1343701937, 0, 'admin', '0.0.0.0', 1, 'admin'),
(28, 1, 1343701991, 0, 'admin', '0.0.0.0', 1, 'admin'),
(29, 1, 1343702077, 0, 'admin', '0.0.0.0', 1, 'admin'),
(30, 0, 1343702164, 0, 'admin', '0.0.0.0', 0, 'admin'),
(31, 1, 1343702169, 0, 'admin', '0.0.0.0', 1, 'admin'),
(32, 0, 1343702419, 0, 'admin', '0.0.0.0', 0, 'admin'),
(33, 0, 1343702423, 0, 'admin', '0.0.0.0', 0, 'admin'),
(34, 1, 1343702428, 0, 'admin', '0.0.0.0', 1, 'admin'),
(35, 1, 1343702497, 0, 'admin', '0.0.0.0', 1, 'admin'),
(36, 1, 1343703026, 0, 'admin', '0.0.0.0', 1, 'admin'),
(37, 1, 1343703265, 0, 'admin', '0.0.0.0', 1, 'admin'),
(38, 0, 1343703402, 0, 'admin', '0.0.0.0', 0, 'admin'),
(39, 0, 1343703406, 0, 'admin', '0.0.0.0', 0, 'admin'),
(40, 1, 1343703427, 0, 'admin', '0.0.0.0', 1, 'admin'),
(41, 1, 1343703451, 0, 'admin', '0.0.0.0', 1, 'admin'),
(42, 1, 1343703514, 0, 'admin', '0.0.0.0', 1, 'admin'),
(43, 1, 1343703657, 0, 'admin', '0.0.0.0', 1, 'admin'),
(44, 0, 1343703683, 0, 'ad,om', '0.0.0.0', 0, 'admin'),
(45, 1, 1343703689, 0, 'admin', '0.0.0.0', 1, 'admin'),
(46, 1, 1343703753, 0, 'admin', '0.0.0.0', 1, 'admin'),
(47, 1, 1343703819, 0, 'admin', '0.0.0.0', 1, 'admin'),
(48, 1, 1343703921, 0, 'admin', '0.0.0.0', 1, 'admin'),
(49, 1, 1343704851, 0, 'admin', '0.0.0.0', 1, 'admin'),
(50, 1, 1343705400, 0, 'admin', '0.0.0.0', 1, 'admin'),
(51, 1, 1343705427, 0, 'admin', '0.0.0.0', 1, 'admin'),
(52, 1, 1343705448, 0, 'admin', '0.0.0.0', 1, 'admin'),
(53, 1, 1343705767, 0, 'admin', '0.0.0.0', 1, 'admin'),
(54, 1, 1343705959, 0, 'admin', '0.0.0.0', 1, 'admin'),
(55, 1, 1343706001, 0, 'admin', '0.0.0.0', 1, 'admin'),
(56, 1, 1343706026, 0, 'admin', '0.0.0.0', 1, 'admin'),
(57, 1, 1343706139, 0, 'admin', '0.0.0.0', 1, 'admin'),
(58, 1, 1343706179, 0, 'admin', '0.0.0.0', 1, 'admin'),
(59, 1, 1343706613, 0, 'admin', '0.0.0.0', 1, 'admin'),
(60, 1, 1343706762, 0, 'admin', '0.0.0.0', 1, 'admin'),
(61, 1, 1343706870, 0, 'admin', '0.0.0.0', 1, 'admin'),
(62, 1, 1343706989, 0, 'admin', '0.0.0.0', 1, 'admin'),
(63, 1, 1343707364, 0, 'admin', '0.0.0.0', 1, 'admin'),
(64, 1, 1343707582, 0, 'admin', '0.0.0.0', 1, 'admin'),
(65, 1, 1343707834, 0, 'admin', '0.0.0.0', 1, 'admin'),
(66, 1, 1343720310, 0, 'admin', '0.0.0.0', 1, 'admin'),
(67, 1, 1343720334, 0, 'admin', '0.0.0.0', 1, 'admin'),
(68, 1, 1343720471, 0, 'admin', '0.0.0.0', 1, 'admin'),
(69, 1, 1343720602, 0, 'admin', '0.0.0.0', 1, 'admin'),
(70, 1, 1343720738, 0, 'admin', '0.0.0.0', 1, 'admin'),
(71, 1, 1343720998, 0, 'admin', '0.0.0.0', 1, 'admin'),
(72, 1, 1343721086, 0, 'admin', '0.0.0.0', 1, 'admin'),
(73, 1, 1343721177, 0, 'admin', '0.0.0.0', 1, 'admin'),
(74, 1, 1343721217, 0, 'admin', '0.0.0.0', 1, 'admin'),
(75, 1, 1343722344, 0, 'admin', '0.0.0.0', 1, 'admin'),
(76, 1, 1343722598, 0, 'admin', '0.0.0.0', 1, 'admin'),
(77, 1, 1343723200, 0, 'admin', '0.0.0.0', 1, 'admin'),
(78, 1, 1343723325, 0, 'admin', '0.0.0.0', 1, 'admin'),
(79, 1, 1343723503, 0, 'admin', '0.0.0.0', 1, 'admin'),
(80, 1, 1343723727, 0, 'admin', '0.0.0.0', 1, 'admin'),
(81, 1, 1343723772, 0, 'admin', '0.0.0.0', 1, 'admin'),
(82, 1, 1343723798, 0, 'admin', '0.0.0.0', 1, 'admin'),
(83, 1, 1343723944, 0, 'admin', '0.0.0.0', 1, 'admin'),
(84, 1, 1343723964, 0, 'admin', '0.0.0.0', 1, 'admin'),
(85, 1, 1343723985, 0, 'admin', '0.0.0.0', 1, 'admin'),
(86, 1, 1343724008, 0, 'admin', '0.0.0.0', 1, 'admin'),
(87, 1, 1343724022, 0, 'admin', '0.0.0.0', 1, 'admin'),
(88, 1, 1343724057, 0, 'admin', '0.0.0.0', 1, 'admin'),
(89, 1, 1343724091, 0, 'admin', '0.0.0.0', 1, 'admin'),
(90, 1, 1343724114, 0, 'admin', '0.0.0.0', 1, 'admin'),
(91, 1, 1343724137, 0, 'admin', '0.0.0.0', 1, 'admin'),
(92, 1, 1343724211, 0, 'admin', '0.0.0.0', 1, 'admin'),
(93, 1, 1343724228, 0, 'admin', '0.0.0.0', 1, 'admin'),
(94, 0, 1343724262, 0, 'admin', '0.0.0.0', 0, 'admin'),
(95, 0, 1343724267, 0, 'admin', '0.0.0.0', 0, 'admin'),
(96, 1, 1343724273, 0, 'admin', '0.0.0.0', 1, 'admin'),
(97, 1, 1343724291, 0, 'admin', '0.0.0.0', 1, 'admin'),
(98, 0, 1343724313, 0, 'admin', '0.0.0.0', 0, 'admin'),
(99, 1, 1343724319, 0, 'admin', '0.0.0.0', 1, 'admin'),
(100, 1, 1343724351, 0, 'admin', '0.0.0.0', 1, 'admin'),
(101, 1, 1343724384, 0, 'admin', '0.0.0.0', 1, 'admin'),
(102, 1, 1343724759, 0, 'admin', '0.0.0.0', 1, 'admin'),
(103, 1, 1343726458, 0, 'admin', '0.0.0.0', 1, 'admin'),
(104, 1, 1343726694, 0, 'admin', '0.0.0.0', 1, 'admin'),
(105, 1, 1343727132, 0, 'admin', '0.0.0.0', 1, 'admin'),
(106, 1, 1343727501, 0, 'admin', '0.0.0.0', 1, 'admin'),
(107, 1, 1343727541, 0, 'admin', '0.0.0.0', 1, 'admin'),
(108, 1, 1343728328, 0, 'admin', '0.0.0.0', 1, 'admin'),
(109, 1, 1343728343, 0, 'admin', '0.0.0.0', 1, 'admin'),
(110, 1, 1343728391, 0, 'admin', '0.0.0.0', 1, 'admin'),
(111, 1, 1343728473, 0, 'admin', '0.0.0.0', 1, 'admin'),
(112, 1, 1343728675, 0, 'admin', '0.0.0.0', 1, 'admin'),
(113, 1, 1343728808, 0, 'admin', '0.0.0.0', 1, 'admin'),
(114, 1, 1343729758, 0, 'admin', '0.0.0.0', 1, 'admin'),
(115, 1, 1343730347, 0, 'admin', '0.0.0.0', 1, 'admin'),
(116, 1, 1343792771, 0, 'admin', '0.0.0.0', 1, 'admin'),
(117, 1, 1343792779, 0, 'admin', '0.0.0.0', 1, 'admin'),
(118, 1, 1343792931, 0, 'admin', '0.0.0.0', 1, 'admin'),
(119, 1, 1343792949, 0, 'admin', '0.0.0.0', 1, 'admin'),
(120, 1, 1343793388, 0, 'admin', '0.0.0.0', 1, 'admin'),
(121, 1, 1343793401, 0, 'admin', '0.0.0.0', 1, 'admin'),
(122, 1, 1343793419, 0, 'admin', '0.0.0.0', 1, 'admin'),
(123, 1, 1343802674, 0, 'admin', '0.0.0.0', 1, 'admin'),
(124, 1, 1343804402, 0, 'admin', '0.0.0.0', 1, 'admin'),
(125, 1, 1343805167, 0, 'admin', '0.0.0.0', 1, 'admin'),
(126, 1, 1343807970, 0, 'admin', '0.0.0.0', 1, 'admin'),
(127, 1, 1343808095, 0, 'admin', '0.0.0.0', 1, 'admin'),
(128, 1, 1343808633, 0, 'admin', '0.0.0.0', 1, 'admin'),
(129, 1, 1343817750, 0, 'admin', '0.0.0.0', 1, 'admin'),
(130, 1, 1343817769, 0, 'admin', '0.0.0.0', 1, 'admin'),
(131, 1, 1343867889, 0, 'admin', '0.0.0.0', 1, 'admin'),
(132, 1, 1343870071, 0, 'admin', '0.0.0.0', 1, 'admin'),
(133, 1, 1343870128, 0, 'admin', '0.0.0.0', 1, 'admin'),
(134, 1, 1343901102, 0, 'admin', '0.0.0.0', 1, 'admin'),
(135, 1, 1343901339, 0, 'admin', '0.0.0.0', 1, 'admin'),
(136, 1, 1343901387, 0, 'admin', '0.0.0.0', 1, 'admin'),
(137, 1, 1343901835, 0, 'admin', '0.0.0.0', 1, 'admin'),
(138, 1, 1343901855, 0, 'admin', '0.0.0.0', 1, 'admin'),
(139, 1, 1343901877, 0, 'admin', '0.0.0.0', 1, 'admin'),
(140, 1, 1343955767, 0, 'admin', '0.0.0.0', 1, 'admin'),
(141, 1, 1343974411, 0, 'admin', '0.0.0.0', 1, 'admin'),
(142, 1, 1343982618, 0, 'admin', '0.0.0.0', 1, 'admin'),
(143, 1, 1344126844, 0, 'admin', '0.0.0.0', 1, 'admin'),
(144, 1, 1344212684, 0, 'admin', '0.0.0.0', 1, 'admin'),
(145, 1, 1344221886, 0, 'admin', '0.0.0.0', 1, 'admin'),
(146, 1, 1344225223, 0, 'admin', '0.0.0.0', 1, 'admin'),
(147, 1, 1344232592, 0, 'admin', '0.0.0.0', 1, 'admin'),
(148, 1, 1344234370, 0, 'admin', '0.0.0.0', 1, 'admin'),
(149, 1, 1344299619, 0, 'admin', '0.0.0.0', 1, 'admin'),
(150, 1, 1344312652, 0, 'admin', '0.0.0.0', 1, 'admin'),
(151, 1, 1344321505, 0, 'admin', '0.0.0.0', 1, 'admin'),
(152, 1, 1344348548, 0, 'admin', '0.0.0.0', 1, 'admin'),
(153, 1, 1344385863, 0, 'admin', '0.0.0.0', 1, 'admin'),
(154, 1, 1344397303, 0, 'admin', '0.0.0.0', 1, 'admin'),
(155, 1, 1344423832, 0, 'admin', '0.0.0.0', 1, 'admin'),
(156, 0, 1344472652, 0, 'log1990@126.com', '0.0.0.0', 0, 'admin'),
(157, 1, 1344472664, 0, 'admin', '0.0.0.0', 1, 'admin'),
(158, 1, 1344472717, 0, 'admin@admin.com', '0.0.0.0', 1, 'admin'),
(159, 1, 1344473354, 0, 'admin', '0.0.0.0', 1, 'admin'),
(160, 1, 1344477798, 0, 'admin', '0.0.0.0', 1, 'admin'),
(161, 1, 1344478938, 0, 'admin', '0.0.0.0', 1, 'admin'),
(162, 1, 1344479136, 0, 'admin', '0.0.0.0', 1, 'admin'),
(163, 1, 1344479546, 0, 'admin', '0.0.0.0', 1, 'admin'),
(164, 1, 1344498542, 0, 'admin', '0.0.0.0', 1, 'admin'),
(165, 1, 1344526220, 0, 'admin', '0.0.0.0', 1, 'admin'),
(166, 1, 1344593014, 0, 'admin', '0.0.0.0', 1, 'admin'),
(167, 1, 1344654402, 0, 'admin', '0.0.0.0', 1, 'admin'),
(168, 1, 1344673160, 0, 'admin', '0.0.0.0', 1, 'admin'),
(169, 1, 1344688941, 0, 'admin', '0.0.0.0', 1, 'admin'),
(170, 1, 1344706550, 0, 'admin', '0.0.0.0', 1, 'admin'),
(171, 1, 1344749632, 0, 'admin', '0.0.0.0', 1, 'admin'),
(172, 1, 1344779257, 0, 'admin', '0.0.0.0', 1, 'admin'),
(173, 1, 1344812102, 0, 'admin', '0.0.0.0', 1, 'admin'),
(174, 1, 1344818565, 0, 'admin', '0.0.0.0', 1, 'admin'),
(175, 0, 1344818917, 0, 'admin', '0.0.0.0', 0, 'admin'),
(176, 1, 1344818925, 0, 'admin', '0.0.0.0', 1, 'admin'),
(177, 1, 1344819234, 0, 'admin', '0.0.0.0', 1, 'admin'),
(178, 1, 1344826033, 0, 'admin', '0.0.0.0', 1, 'admin'),
(179, 1, 1344828321, 0, 'admin@admin.com', '0.0.0.0', 1, 'admin'),
(180, 1, 1344828330, 0, 'admin', '0.0.0.0', 1, 'admin'),
(181, 1, 1344828592, 0, 'admin', '0.0.0.0', 1, 'admin'),
(182, 1, 1344838579, 0, 'admin', '0.0.0.0', 1, 'admin'),
(183, 1, 1344866606, 0, 'admin', '0.0.0.0', 1, 'admin'),
(184, 1, 1344878947, 0, 'admin', '0.0.0.0', 1, 'admin'),
(185, 1, 1344905222, 0, 'admin', '0.0.0.0', 1, 'admin'),
(186, 0, 1344911037, 0, 'sdfsdfsdf', '0.0.0.0', 0, 'admin'),
(187, 0, 1344911049, 0, 'admin', '0.0.0.0', 0, 'admin'),
(188, 1, 1344912954, 0, 'admin', '0.0.0.0', 1, 'admin'),
(189, 1, 1344926659, 0, 'admin', '0.0.0.0', 1, 'admin'),
(206, 1, 1345105352, 0, 'admin', '0.0.0.0', 1, 'admin'),
(205, 1, 1345102043, 0, 'admin', '0.0.0.0', 1, 'admin'),
(207, 1, 1345124749, 0, 'admin', '0.0.0.0', 1, 'admin'),
(208, 1, 1345158992, 0, 'admin', '0.0.0.0', 1, 'admin'),
(209, 1, 1345164531, 0, 'admin', '0.0.0.0', 1, 'admin'),
(210, 1, 1345169802, 0, 'admin', '0.0.0.0', 1, 'admin'),
(211, 1, 1345171231, 0, 'admin', '0.0.0.0', 1, 'admin'),
(212, 1, 1345174929, 0, 'admin', '0.0.0.0', 1, 'admin'),
(213, 1, 1345213716, 0, 'admin', '0.0.0.0', 1, 'admin'),
(214, 1, 1345218188, 0, 'admin', '0.0.0.0', 1, 'admin'),
(215, 1, 1345251240, 0, 'admin', '0.0.0.0', 1, 'admin'),
(216, 1, 1345252866, 0, 'admin', '0.0.0.0', 1, 'admin'),
(217, 1, 1345253115, 0, 'admin', '0.0.0.0', 1, 'admin'),
(218, 1, 1345256800, 0, 'admin', '0.0.0.0', 1, 'admin'),
(219, 1, 1345263621, 0, 'admin', '0.0.0.0', 1, 'admin'),
(220, 1, 1345264085, 0, 'admin', '0.0.0.0', 1, 'admin'),
(221, 6, 1345265282, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(222, 1, 1345265311, 0, 'admin', '0.0.0.0', 1, 'admin'),
(223, 1, 1345295573, 0, 'admin', '0.0.0.0', 1, 'admin'),
(224, 1, 1345297037, 0, 'admin', '0.0.0.0', 1, 'admin'),
(225, 1, 1345297091, 0, 'admin', '0.0.0.0', 1, 'admin'),
(226, 1, 1345297737, 0, 'admin', '0.0.0.0', 1, 'admin'),
(227, 1, 1345304362, 0, 'admin', '0.0.0.0', 1, 'admin'),
(228, 1, 1345305663, 0, 'admin', '0.0.0.0', 1, 'admin'),
(229, 1, 1345306845, 0, 'admin', '0.0.0.0', 1, 'admin'),
(230, 1, 1345306937, 0, 'admin', '0.0.0.0', 1, 'admin'),
(231, 1, 1345308553, 0, 'admin', '0.0.0.0', 1, 'admin'),
(232, 1, 1345337183, 0, 'admin', '0.0.0.0', 1, 'admin'),
(233, 1, 1345341986, 0, 'admin', '0.0.0.0', 1, 'admin'),
(234, 1, 1345343479, 0, 'admin', '0.0.0.0', 1, 'admin'),
(235, 1, 1345380904, 0, 'admin', '0.0.0.0', 1, 'admin'),
(236, 1, 1345385063, 0, 'admin', '0.0.0.0', 1, 'admin'),
(237, 1, 1345387447, 0, 'admin', '0.0.0.0', 1, 'admin'),
(238, 1, 1345424207, 0, 'admin', '0.0.0.0', 1, 'admin'),
(239, 1, 1345429848, 0, 'admin', '0.0.0.0', 1, 'admin'),
(240, 1, 1345432067, 0, 'admin', '0.0.0.0', 1, 'admin'),
(241, 1, 1345443866, 0, 'admin', '0.0.0.0', 1, 'admin'),
(242, 1, 1345447629, 0, 'admin', '0.0.0.0', 1, 'admin'),
(243, 1, 1345448495, 0, 'admin', '0.0.0.0', 1, 'admin'),
(244, 1, 1345509423, 0, 'admin', '0.0.0.0', 1, 'admin'),
(245, 0, 1345511327, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(246, 6, 1345511347, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(247, 1, 1345511430, 0, 'admin', '0.0.0.0', 1, 'admin'),
(248, 6, 1345511931, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(249, 1, 1345511978, 0, 'admin', '0.0.0.0', 1, 'admin'),
(250, 1, 1345528904, 0, 'admin', '0.0.0.0', 1, 'admin'),
(251, 1, 1345530346, 0, 'admin', '0.0.0.0', 1, 'admin'),
(252, 1, 1345538881, 0, 'admin', '0.0.0.0', 1, 'admin'),
(253, 1, 1345540778, 0, 'admin', '0.0.0.0', 1, 'admin'),
(254, 1, 1345596211, 0, 'admin', '0.0.0.0', 1, 'admin'),
(255, 1, 1345598613, 0, 'admin', '0.0.0.0', 1, 'admin'),
(256, 1, 1345601780, 0, 'admin', '0.0.0.0', 1, 'admin'),
(257, 1, 1345603434, 0, 'admin', '0.0.0.0', 1, 'admin'),
(258, 17, 1345623288, 0, 'weiyong1999', '0.0.0.0', 1, 'admin'),
(259, 0, 1345635963, 0, 'admin', '0.0.0.0', 0, 'admin'),
(260, 1, 1345635971, 0, 'admin', '0.0.0.0', 1, 'admin'),
(261, 1, 1345637155, 0, 'admin', '0.0.0.0', 1, 'admin'),
(262, 1, 1345637191, 0, 'admin', '0.0.0.0', 1, 'admin'),
(263, 1, 1345644712, 0, 'admin', '0.0.0.0', 1, 'admin'),
(264, 1, 1345707361, 0, 'admin', '0.0.0.0', 1, 'admin'),
(265, 1, 1345710814, 0, 'admin', '0.0.0.0', 1, 'admin'),
(266, 1, 1345713234, 0, 'admin', '0.0.0.0', 1, 'admin'),
(267, 1, 1345713665, 0, 'admin', '0.0.0.0', 1, 'admin'),
(268, 1, 1345719025, 0, 'admin', '0.0.0.0', 1, 'admin'),
(269, 26, 1345726697, 0, 'niu', '0.0.0.0', 1, 'admin'),
(270, 1, 1345730072, 0, 'admin', '0.0.0.0', 1, 'admin'),
(271, 1, 1345733789, 0, 'admin', '127.0.0.1', 1, 'admin'),
(272, 1, 1345768006, 0, 'admin', '0.0.0.0', 1, 'admin'),
(273, 1, 1345776735, 0, 'admin', '0.0.0.0', 1, 'admin'),
(274, 1, 1345777570, 0, 'admin', '0.0.0.0', 1, 'admin'),
(275, 1, 1345797063, 0, 'admin', '0.0.0.0', 1, 'admin'),
(276, 1, 1345817614, 0, 'admin', '0.0.0.0', 1, 'admin'),
(277, 1, 1345818468, 0, 'admin', '0.0.0.0', 1, 'admin'),
(278, 1, 1345818560, 0, 'admin', '0.0.0.0', 1, 'admin'),
(279, 1, 1345820704, 0, 'admin', '0.0.0.0', 1, 'admin'),
(280, 1, 1345821541, 0, 'admin', '0.0.0.0', 1, 'admin'),
(281, 1, 1345867441, 0, 'admin', '0.0.0.0', 1, 'admin'),
(282, 1, 1345875756, 0, 'admin', '0.0.0.0', 1, 'admin'),
(283, 1, 1345888297, 0, 'admin', '0.0.0.0', 1, 'admin'),
(284, 1, 1345888640, 0, 'admin', '0.0.0.0', 1, 'admin'),
(285, 1, 1345941276, 0, 'admin', '0.0.0.0', 1, 'admin'),
(286, 1, 1345962602, 0, 'admin', '0.0.0.0', 1, 'admin'),
(287, 1, 1346046095, 0, 'admin', '0.0.0.0', 1, 'admin'),
(288, 1, 1346062728, 0, 'admin', '0.0.0.0', 1, 'admin'),
(289, 1, 1346064930, 0, 'admin', '0.0.0.0', 1, 'admin'),
(290, 1, 1346065176, 0, 'admin', '0.0.0.0', 1, 'admin'),
(291, 1, 1346073229, 0, 'admin', '0.0.0.0', 1, 'admin'),
(292, 1, 1346089767, 0, 'admin', '0.0.0.0', 1, 'admin'),
(293, 1, 1346117945, 0, 'admin', '0.0.0.0', 1, 'admin'),
(294, 1, 1346120138, 0, 'admin', '0.0.0.0', 1, 'admin'),
(295, 1, 1346128264, 0, 'admin', '0.0.0.0', 1, 'admin'),
(296, 1, 1346128476, 0, 'admin', '0.0.0.0', 1, 'admin'),
(297, 1, 1346135663, 0, 'admin', '0.0.0.0', 1, 'admin'),
(298, 1, 1346138671, 0, 'admin', '0.0.0.0', 1, 'admin'),
(299, 1, 1346204358, 0, 'admin', '0.0.0.0', 1, 'admin'),
(300, 1, 1346205513, 0, 'admin', '0.0.0.0', 1, 'admin'),
(301, 1, 1346220810, 0, 'admin', '0.0.0.0', 1, 'admin'),
(302, 1, 1346230573, 0, 'admin', '0.0.0.0', 1, 'admin'),
(303, 1, 1346287533, 0, 'admin', '0.0.0.0', 1, 'admin'),
(304, 1, 1346291773, 0, 'admin', '0.0.0.0', 1, 'admin'),
(305, 1, 1346294107, 0, 'admin', '0.0.0.0', 1, 'admin'),
(306, 1, 1346306998, 0, 'admin', '0.0.0.0', 1, 'admin'),
(307, 0, 1346373634, 0, 'admin', '0.0.0.0', 0, 'admin'),
(308, 1, 1346373639, 0, 'admin', '0.0.0.0', 1, 'admin'),
(309, 1, 1346382255, 0, 'admin', '0.0.0.0', 1, 'admin'),
(310, 0, 1346384709, 0, 'kevin', '0.0.0.0', 0, 'admin'),
(311, 0, 1346384716, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(312, 0, 1346384721, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(313, 0, 1346384723, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(314, 0, 1346384728, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(315, 0, 1346384730, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(316, 1, 1346384741, 0, 'admin', '0.0.0.0', 1, 'admin'),
(317, 1, 1346484614, 0, 'admin', '0.0.0.0', 1, 'admin'),
(318, 1, 1346549441, 0, 'admin', '0.0.0.0', 1, 'admin'),
(319, 1, 1346550974, 0, 'admin', '0.0.0.0', 1, 'admin'),
(320, 1, 1346554431, 0, 'admin', '0.0.0.0', 1, 'admin'),
(321, 1, 1346554712, 0, 'admin', '0.0.0.0', 1, 'admin'),
(322, 1, 1346568111, 0, 'admin', '0.0.0.0', 1, 'admin'),
(323, 1, 1346568228, 0, 'admin', '0.0.0.0', 1, 'admin'),
(324, 1, 1346568228, 0, 'admin', '0.0.0.0', 1, 'admin'),
(325, 1, 1346568237, 0, 'admin', '0.0.0.0', 1, 'admin'),
(326, 0, 1346572229, 0, 'kevin', '0.0.0.0', 0, 'admin'),
(327, 0, 1346572237, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(328, 0, 1346572241, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(329, 0, 1346572255, 0, 'weiyong2000', '0.0.0.0', 0, 'admin'),
(330, 18, 1346572259, 0, 'weiyong2000', '0.0.0.0', 1, 'admin'),
(331, 1, 1346581108, 0, 'admin', '0.0.0.0', 1, 'admin'),
(332, 1, 1346658863, 0, 'admin', '0.0.0.0', 1, 'admin'),
(333, 1, 1346660313, 0, 'admin', '0.0.0.0', 1, 'admin'),
(334, 1, 1346738843, 0, 'admin', '0.0.0.0', 1, 'admin'),
(335, 1, 1346741432, 0, 'admin', '0.0.0.0', 1, 'admin'),
(336, 1, 1346741501, 0, 'admin', '0.0.0.0', 1, 'admin'),
(337, 1, 1346744375, 0, 'admin', '0.0.0.0', 1, 'admin'),
(338, 6, 1346753684, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(339, 0, 1346806736, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(340, 0, 1346806738, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(341, 1, 1346806745, 0, 'admin', '0.0.0.0', 1, 'admin'),
(342, 1, 1346808785, 0, 'admin', '0.0.0.0', 1, 'admin'),
(343, 1, 1346812270, 0, 'admin', '0.0.0.0', 1, 'admin'),
(344, 1, 1346814749, 0, 'admin', '0.0.0.0', 1, 'admin'),
(345, 1, 1346816955, 0, 'admin', '0.0.0.0', 1, 'admin'),
(346, 1, 1346827726, 0, 'admin', '0.0.0.0', 1, 'admin'),
(347, 1, 1346853764, 0, 'admin', '0.0.0.0', 1, 'admin'),
(348, 1, 1346854409, 0, 'admin', '0.0.0.0', 1, 'admin'),
(349, 1, 1346905222, 0, 'admin', '0.0.0.0', 1, 'admin'),
(350, 1, 1346912873, 0, 'admin', '0.0.0.0', 1, 'admin'),
(351, 1, 1346921390, 0, 'admin', '0.0.0.0', 1, 'admin'),
(352, 1, 1346925250, 0, 'admin', '0.0.0.0', 1, 'admin'),
(353, 1, 1346978187, 0, 'admin', '0.0.0.0', 1, 'admin'),
(354, 1, 1347001768, 0, 'admin', '0.0.0.0', 1, 'admin'),
(355, 1, 1347105352, 0, 'admin', '0.0.0.0', 1, 'admin'),
(356, 1, 1347121515, 0, 'admin', '0.0.0.0', 1, 'admin'),
(357, 1, 1347152120, 0, 'admin', '0.0.0.0', 1, 'admin'),
(358, 1, 1347156574, 0, 'admin', '0.0.0.0', 1, 'admin'),
(359, 1, 1347156592, 0, 'admin', '0.0.0.0', 1, 'admin'),
(360, 1, 1347156617, 0, 'admin', '0.0.0.0', 1, 'admin'),
(361, 0, 1347181428, 0, '小牛哥Dyhbsdfsdfsdf', '0.0.0.0', 0, 'home'),
(362, 0, 1347181553, 0, '小牛哥Dyhbsdfsdfsdf', '0.0.0.0', 0, 'home'),
(363, 30, 1347181948, 0, '小牛哥Dyhbsdfsdfsdf', '0.0.0.0', 1, 'home'),
(364, 30, 1347182081, 0, '小牛哥Dyhbsdfsdfsdf', '0.0.0.0', 1, 'home'),
(365, 30, 1347182117, 0, '小牛哥Dyhbsdfsdfsdf', '0.0.0.0', 1, 'home'),
(366, 34, 1347182731, 0, '小牛sdf', '0.0.0.0', 1, 'home'),
(367, 37, 1347183679, 0, '小牛哥Dyhbxxxxxxsfdfsdfsdf', '0.0.0.0', 1, 'home'),
(368, 38, 1347185328, 0, '小牛哥D斯蒂芬斯蒂芬是yhb', '0.0.0.0', 1, 'home'),
(369, 38, 1347185362, 0, '小牛哥D斯蒂芬斯蒂芬是yhb', '0.0.0.0', 1, 'home'),
(370, 38, 1347185667, 0, '小牛哥D斯蒂芬斯蒂芬是yhb', '0.0.0.0', 1, 'home'),
(371, 38, 1347185807, 0, '小牛哥D斯蒂芬斯蒂芬是yhb', '0.0.0.0', 1, 'home'),
(372, 38, 1347195550, 0, '小牛哥D斯蒂芬斯蒂芬是yhb', '0.0.0.0', 1, 'home'),
(373, 1, 1347195793, 0, 'admin', '0.0.0.0', 1, 'admin'),
(374, 1, 1347196999, 0, 'admin', '0.0.0.0', 1, 'admin'),
(375, 1, 1347197117, 0, 'admin', '0.0.0.0', 1, 'admin'),
(376, 1, 1347197800, 0, 'admin', '0.0.0.0', 1, 'admin'),
(377, 1, 1347197918, 0, 'admin', '0.0.0.0', 1, 'admin'),
(378, 1, 1347198010, 0, 'admin', '0.0.0.0', 1, 'admin'),
(379, 1, 1347198060, 0, 'admin', '0.0.0.0', 1, 'admin'),
(380, 1, 1347198835, 0, 'admin', '0.0.0.0', 1, 'admin'),
(381, 1, 1347200395, 0, 'admin', '0.0.0.0', 1, 'admin'),
(382, 0, 1347203494, 0, 'admin', '0.0.0.0', 0, 'admin'),
(383, 1, 1347203498, 0, 'admin', '0.0.0.0', 1, 'admin'),
(384, 0, 1347203567, 0, 'admin', '0.0.0.0', 0, 'admin'),
(385, 0, 1347203575, 0, 'admin', '0.0.0.0', 0, 'admin'),
(386, 0, 1347203580, 0, 'admin', '0.0.0.0', 0, 'admin'),
(387, 0, 1347203593, 0, 'admin', '0.0.0.0', 0, 'admin'),
(388, 0, 1347203602, 0, 'admin', '0.0.0.0', 0, 'admin'),
(389, 1, 1347203652, 0, 'admin', '0.0.0.0', 1, 'admin'),
(390, 1, 1347207143, 0, 'admin', '0.0.0.0', 1, 'admin'),
(391, 1, 1347207689, 0, 'admin', '0.0.0.0', 1, 'home'),
(392, 1, 1347207705, 0, 'admin', '0.0.0.0', 1, 'admin'),
(393, 1, 1347208432, 0, 'admin', '0.0.0.0', 1, 'admin'),
(394, 1, 1347243053, 0, 'admin', '0.0.0.0', 1, 'admin'),
(395, 1, 1347243489, 0, 'admin', '0.0.0.0', 1, 'admin'),
(396, 1, 1347245821, 0, 'admin', '0.0.0.0', 1, 'admin'),
(397, 0, 1347249449, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(398, 1, 1347249458, 0, 'admin', '0.0.0.0', 1, 'admin'),
(399, 1, 1347257409, 0, 'admin', '0.0.0.0', 1, 'admin'),
(400, 1, 1347345921, 0, 'admin', '0.0.0.0', 1, 'admin'),
(401, 1, 1347356633, 0, 'admin', '0.0.0.0', 1, 'admin'),
(402, 1, 1347412183, 0, 'admin', '0.0.0.0', 1, 'admin'),
(403, 1, 1347415184, 0, 'admin', '0.0.0.0', 1, 'admin'),
(404, 1, 1347415486, 0, 'admin', '0.0.0.0', 1, 'admin'),
(405, 1, 1347416349, 0, 'admin', '0.0.0.0', 1, 'admin'),
(406, 1, 1347417503, 0, 'admin', '0.0.0.0', 1, 'admin'),
(407, 1, 1347417880, 0, 'admin', '0.0.0.0', 1, 'admin'),
(408, 1, 1347418047, 0, 'admin', '0.0.0.0', 1, 'admin'),
(409, 1, 1347418083, 0, 'admin', '0.0.0.0', 1, 'admin'),
(410, 1, 1347521863, 0, 'admin', '0.0.0.0', 1, 'admin'),
(411, 1, 1347524671, 0, 'admin', '0.0.0.0', 1, 'admin'),
(412, 1, 1347587135, 0, 'admin', '0.0.0.0', 1, 'admin'),
(413, 1, 1347587648, 0, 'admin', '0.0.0.0', 1, 'admin'),
(414, 1, 1347594751, 0, 'admin', '0.0.0.0', 1, 'admin'),
(415, 1, 1347605056, 0, 'admin', '0.0.0.0', 1, 'admin'),
(416, 1, 1347760033, 0, 'admin', '0.0.0.0', 1, 'admin'),
(417, 1, 1347760245, 0, 'admin', '0.0.0.0', 1, 'admin'),
(418, 1, 1347781737, 0, 'admin', '0.0.0.0', 1, 'admin'),
(419, 0, 1347787457, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(420, 0, 1347787462, 0, 'weiyong1999', '0.0.0.0', 0, 'admin'),
(421, 18, 1347787470, 0, 'weiyong2000', '0.0.0.0', 1, 'admin'),
(422, 1, 1347787817, 0, 'admin', '0.0.0.0', 1, 'admin'),
(423, 1, 1347974115, 0, 'admin', '0.0.0.0', 1, 'admin'),
(424, 1, 1347981076, 0, 'admin', '0.0.0.0', 1, 'admin'),
(425, 1, 1348014868, 0, 'admin', '0.0.0.0', 1, 'admin'),
(426, 1, 1348019572, 0, 'admin', '0.0.0.0', 1, 'admin'),
(427, 1, 1348019785, 0, 'admin', '0.0.0.0', 1, 'admin'),
(428, 0, 1348098954, 0, 'admin', '0.0.0.0', 0, 'admin'),
(429, 1, 1348098959, 0, 'admin', '0.0.0.0', 1, 'admin'),
(430, 1, 1348099617, 0, 'admin', '0.0.0.0', 1, 'admin'),
(431, 1, 1348102689, 0, 'admin', '0.0.0.0', 1, 'admin'),
(432, 1, 1348121514, 0, 'admin', '0.0.0.0', 1, 'admin'),
(433, 1, 1348128454, 0, 'admin', '0.0.0.0', 1, 'admin'),
(434, 1, 1348138423, 0, 'admin', '0.0.0.0', 1, 'admin'),
(435, 1, 1348139478, 0, 'admin', '0.0.0.0', 1, 'admin'),
(436, 1, 1348153878, 0, 'admin', '0.0.0.0', 1, 'admin'),
(437, 1, 1348154129, 0, 'admin', '0.0.0.0', 1, 'admin'),
(438, 1, 1348189731, 0, 'admin', '0.0.0.0', 1, 'admin'),
(439, 1, 1348190105, 0, 'admin', '0.0.0.0', 1, 'admin'),
(440, 1, 1348211698, 0, 'admin', '0.0.0.0', 1, 'admin'),
(441, 6, 1348215417, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(442, 1, 1348215698, 0, 'admin', '0.0.0.0', 1, 'admin'),
(443, 1, 1348361304, 0, 'admin', '0.0.0.0', 1, 'admin'),
(444, 1, 1348450722, 0, 'admin', '0.0.0.0', 1, 'admin'),
(445, 1, 1348481871, 0, 'admin', '0.0.0.0', 1, 'admin'),
(446, 1, 1348482024, 0, 'admin', '0.0.0.0', 1, 'admin'),
(447, 1, 1348535123, 0, 'admin', '0.0.0.0', 1, 'admin'),
(448, 1, 1348542457, 0, 'admin', '0.0.0.0', 1, 'admin'),
(449, 1, 1348542495, 0, 'admin', '0.0.0.0', 1, 'admin'),
(450, 6, 1348542903, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(451, 1, 1348543439, 0, 'admin', '0.0.0.0', 1, 'admin'),
(452, 6, 1348554819, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(453, 6, 1348554821, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(454, 1, 1348621947, 0, 'admin', '0.0.0.0', 1, 'admin'),
(455, 1, 1348640449, 0, 'admin', '0.0.0.0', 1, 'admin'),
(456, 1, 1348645568, 0, 'admin', '0.0.0.0', 1, 'admin'),
(457, 1, 1348647380, 0, 'admin', '0.0.0.0', 1, 'admin'),
(458, 1, 1348706347, 0, 'admin', '0.0.0.0', 1, 'admin'),
(459, 1, 1348793347, 0, 'admin', '0.0.0.0', 1, 'admin'),
(460, 1, 1348793969, 0, 'admin', '0.0.0.0', 1, 'admin'),
(461, 1, 1348816740, 0, 'admin', '0.0.0.0', 1, 'admin'),
(462, 1, 1348844358, 0, 'admin', '0.0.0.0', 1, 'admin'),
(463, 1, 1348844676, 0, 'admin', '0.0.0.0', 1, 'admin'),
(464, 1, 1348890143, 0, 'admin', '0.0.0.0', 1, 'admin'),
(465, 1, 1348904098, 0, 'admin', '0.0.0.0', 1, 'admin'),
(466, 1, 1348907766, 0, 'admin', '0.0.0.0', 1, 'admin'),
(467, 0, 1348911844, 0, 'admin', '0.0.0.0', 0, 'admin'),
(468, 1, 1348911848, 0, 'admin', '0.0.0.0', 1, 'admin'),
(469, 1, 1348925477, 0, 'admin', '0.0.0.0', 1, 'admin'),
(470, 1, 1348925738, 0, 'admin', '0.0.0.0', 1, 'admin'),
(471, 1, 1348928672, 0, 'admin', '0.0.0.0', 1, 'admin'),
(472, 1, 1348938244, 0, 'admin', '0.0.0.0', 1, 'admin'),
(473, 1, 1348940541, 0, 'admin', '0.0.0.0', 1, 'admin'),
(474, 1, 1348941847, 0, 'admin', '0.0.0.0', 1, 'admin'),
(475, 1, 1348946022, 0, 'admin', '0.0.0.0', 1, 'admin'),
(476, 1, 1348946201, 0, 'admin', '0.0.0.0', 1, 'admin'),
(477, 0, 1348950345, 0, 'xiaomage', '0.0.0.0', 0, 'admin'),
(478, 0, 1348950357, 0, 'admin', '0.0.0.0', 0, 'admin'),
(479, 1, 1348950361, 0, 'admin', '0.0.0.0', 1, 'admin'),
(480, 0, 1009884305, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(481, 0, 1009884340, 0, 'admin', '0.0.0.0', 0, 'admin'),
(482, 1, 1009884348, 0, 'admin', '0.0.0.0', 1, 'admin'),
(483, 1, 1009911418, 0, 'admin', '0.0.0.0', 1, 'admin'),
(484, 1, 1349148574, 0, 'admin', '0.0.0.0', 1, 'admin'),
(485, 1, 1349148792, 0, 'admin', '0.0.0.0', 1, 'admin'),
(486, 0, 1349157427, 0, 'admin', '0.0.0.0', 0, 'admin'),
(487, 0, 1349157435, 0, 'admin', '0.0.0.0', 0, 'admin'),
(488, 1, 1349157439, 0, 'admin', '0.0.0.0', 1, 'admin'),
(489, 1, 1349157757, 0, 'admin', '0.0.0.0', 1, 'admin'),
(490, 1, 1349230694, 0, 'admin', '0.0.0.0', 1, 'admin'),
(491, 1, 1349311444, 0, 'admin', '0.0.0.0', 1, 'admin'),
(492, 1, 1349397041, 0, 'admin', '0.0.0.0', 1, 'admin'),
(493, 1, 1349400186, 0, 'admin', '0.0.0.0', 1, 'admin'),
(494, 1, 1349421122, 0, 'admin', '0.0.0.0', 1, 'admin'),
(495, 0, 1349489577, 0, 'admin', '0.0.0.0', 0, 'admin'),
(496, 1, 1349489582, 0, 'admin', '0.0.0.0', 1, 'admin'),
(497, 1, 1349572754, 0, 'admin', '0.0.0.0', 1, 'admin'),
(498, 1, 1349829964, 0, 'admin', '0.0.0.0', 1, 'admin'),
(499, 1, 1349850664, 0, 'admin', '0.0.0.0', 1, 'admin'),
(500, 1, 1349861023, 0, 'admin', '0.0.0.0', 1, 'admin'),
(501, 1, 1349921855, 0, 'admin', '0.0.0.0', 1, 'admin'),
(502, 0, 1349924791, 0, 'admin', '0.0.0.0', 0, 'admin'),
(503, 0, 1349924795, 0, 'admin', '0.0.0.0', 0, 'admin'),
(504, 1, 1349924799, 0, 'admin', '0.0.0.0', 1, 'admin'),
(505, 1, 1349926623, 0, 'admin', '0.0.0.0', 1, 'admin'),
(506, 0, 1349927064, 0, 'admin', '0.0.0.0', 0, 'admin'),
(507, 1, 1349927068, 0, 'admin', '0.0.0.0', 1, 'admin'),
(508, 1, 1349939964, 0, 'admin', '0.0.0.0', 1, 'admin'),
(509, 6, 1349946767, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(510, 1, 1349949677, 0, 'admin', '0.0.0.0', 1, 'admin'),
(511, 0, 1350006115, 0, 'admin', '0.0.0.0', 0, 'admin'),
(512, 1, 1350006120, 0, 'admin', '0.0.0.0', 1, 'admin'),
(513, 1, 1350199886, 0, 'admin', '0.0.0.0', 1, 'admin'),
(514, 1, 1350265554, 0, 'admin', '0.0.0.0', 1, 'admin'),
(515, 1, 1350285301, 0, 'admin', '0.0.0.0', 1, 'admin'),
(591, 1, 1352511341, 0, 'admin', '0.0.0.0', 1, 'admin'),
(517, 1, 1350400401, 0, 'admin', '0.0.0.0', 1, 'admin'),
(518, 6, 1350402593, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(519, 0, 1350403058, 0, 'admin', '0.0.0.0', 0, 'admin'),
(520, 1, 1350403063, 0, 'admin', '0.0.0.0', 1, 'admin'),
(521, 1, 1350437162, 0, 'admin', '0.0.0.0', 1, 'admin'),
(522, 1, 1350461603, 0, 'admin', '0.0.0.0', 1, 'admin'),
(523, 1, 1350466735, 0, 'admin', '0.0.0.0', 1, 'admin'),
(524, 1, 1350529588, 0, 'admin', '0.0.0.0', 1, 'admin'),
(525, 1, 1350541473, 0, 'admin', '0.0.0.0', 1, 'admin'),
(526, 1, 1350541588, 0, 'admin', '0.0.0.0', 1, 'admin'),
(527, 1, 1350545612, 0, 'admin', '0.0.0.0', 1, 'admin'),
(528, 1, 1350611400, 0, 'admin', '0.0.0.0', 1, 'admin'),
(529, 1, 1350611546, 0, 'admin', '0.0.0.0', 1, 'admin'),
(530, 1, 1350639050, 0, 'admin', '0.0.0.0', 1, 'admin'),
(531, 1, 1350788079, 0, 'admin', '0.0.0.0', 1, 'admin'),
(532, 0, 1350870027, 0, 'admin', '0.0.0.0', 0, 'admin'),
(533, 0, 1350870034, 0, 'admin', '0.0.0.0', 0, 'admin'),
(534, 1, 1350870037, 0, 'admin', '0.0.0.0', 1, 'admin'),
(535, 1, 1350889102, 0, 'admin', '0.0.0.0', 1, 'admin'),
(536, 1, 1350964157, 0, 'admin', '0.0.0.0', 1, 'admin'),
(537, 1, 1351043498, 0, 'admin', '0.0.0.0', 1, 'admin'),
(538, 0, 1351045097, 0, 'admin', '0.0.0.0', 0, 'admin'),
(539, 1, 1351045102, 0, 'admin', '0.0.0.0', 1, 'admin'),
(540, 1, 1351063196, 0, 'admin', '0.0.0.0', 1, 'admin'),
(541, 1, 1351067356, 0, 'admin', '0.0.0.0', 1, 'admin'),
(542, 1, 1351128487, 0, 'admin', '0.0.0.0', 1, 'admin'),
(543, 1, 1351138454, 0, 'admin', '0.0.0.0', 1, 'admin'),
(544, 1, 1351151094, 0, 'admin', '0.0.0.0', 1, 'admin'),
(545, 1, 1351151358, 0, 'admin', '0.0.0.0', 1, 'admin'),
(546, 0, 1351153263, 0, 'admin', '0.0.0.0', 0, 'admin'),
(547, 1, 1351153268, 0, 'admin', '0.0.0.0', 1, 'admin'),
(548, 1, 1351154837, 0, 'admin', '0.0.0.0', 1, 'admin'),
(549, 1, 1351157465, 0, 'admin', '0.0.0.0', 1, 'admin'),
(550, 1, 1351217362, 0, 'admin', '0.0.0.0', 1, 'admin'),
(551, 0, 1351238108, 0, 'admin', '0.0.0.0', 0, 'admin'),
(552, 0, 1351238114, 0, 'admin', '0.0.0.0', 0, 'admin'),
(553, 1, 1351238118, 0, 'admin', '0.0.0.0', 1, 'admin'),
(554, 1, 1351238442, 0, 'admin', '0.0.0.0', 1, 'admin'),
(555, 1, 1351240297, 0, 'admin', '0.0.0.0', 1, 'admin'),
(556, 0, 1351391855, 0, 'admin', '0.0.0.0', 0, 'admin'),
(557, 1, 1351391859, 0, 'admin', '0.0.0.0', 1, 'admin'),
(558, 1, 1351395745, 0, 'admin', '0.0.0.0', 1, 'admin'),
(559, 1, 1351504809, 0, 'admin', '0.0.0.0', 1, 'admin'),
(560, 0, 1351557106, 0, 'admin', '0.0.0.0', 0, 'admin'),
(561, 0, 1351557111, 0, 'admin', '0.0.0.0', 0, 'admin'),
(562, 1, 1351557117, 0, 'admin', '0.0.0.0', 1, 'admin'),
(563, 0, 1351580731, 0, 'admin', '0.0.0.0', 0, 'admin'),
(564, 0, 1351580739, 0, 'admin', '0.0.0.0', 0, 'admin'),
(565, 1, 1351580745, 0, 'admin', '0.0.0.0', 1, 'admin'),
(566, 1, 1351664770, 0, 'admin', '0.0.0.0', 1, 'admin'),
(567, 0, 1351665792, 0, 'admin', '0.0.0.0', 0, 'admin'),
(568, 1, 1351665797, 0, 'admin', '0.0.0.0', 1, 'admin'),
(569, 1, 1351666224, 0, 'admin', '0.0.0.0', 1, 'admin'),
(570, 0, 1351671142, 0, 'xiaonigue', '0.0.0.0', 0, 'admin'),
(571, 0, 1351671147, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(572, 0, 1351671155, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(573, 0, 1351671732, 0, 'xiaoniuge', '0.0.0.0', 0, 'admin'),
(574, 1, 1351671739, 0, 'admin', '0.0.0.0', 1, 'admin'),
(575, 1, 1351674135, 0, 'admin', '0.0.0.0', 1, 'admin'),
(576, 1, 1351734179, 0, 'admin', '0.0.0.0', 1, 'admin'),
(577, 1, 1351752355, 0, 'admin', '0.0.0.0', 1, 'admin'),
(578, 1, 1351820761, 0, 'admin', '0.0.0.0', 1, 'admin'),
(579, 1, 1352010471, 0, 'admin', '0.0.0.0', 1, 'admin'),
(580, 1, 1352080736, 0, 'admin', '0.0.0.0', 1, 'admin'),
(581, 1, 1352083523, 0, 'admin', '0.0.0.0', 1, 'admin'),
(582, 1, 1352093456, 0, 'admin', '0.0.0.0', 1, 'admin'),
(583, 1, 1352093529, 0, 'admin', '0.0.0.0', 1, 'admin'),
(584, 1, 1352097025, 0, 'admin', '0.0.0.0', 1, 'admin'),
(585, 1, 1352183792, 0, 'admin', '0.0.0.0', 1, 'admin'),
(586, 1, 1352250886, 0, 'admin', '0.0.0.0', 1, 'admin'),
(587, 1, 1893477565, 0, 'admin', '0.0.0.0', 1, 'admin'),
(588, 1, 1352344743, 0, 'admin', '0.0.0.0', 1, 'admin'),
(589, 6, 1352347371, 0, 'xiaomage', '0.0.0.0', 1, 'admin'),
(590, 1, 1352355570, 0, 'admin', '0.0.0.0', 1, 'admin'),
(592, 1, 1352449223, 0, 'admin', '0.0.0.0', 1, 'admin'),
(593, 1, 1352601636, 0, 'admin', '0.0.0.0', 1, 'admin'),
(594, 1, 1352620114, 0, 'admin', '0.0.0.0', 1, 'admin'),
(595, 1, 1352683973, 0, 'admin', '0.0.0.0', 1, 'admin'),
(596, 1, 1352701620, 0, 'admin', '0.0.0.0', 1, 'admin'),
(597, 1, 1352772611, 0, 'admin', '0.0.0.0', 1, 'admin'),
(598, 1, 1352858670, 0, 'admin', '0.0.0.0', 1, 'admin'),
(599, 1, 1352880095, 0, 'admin', '0.0.0.0', 1, 'admin'),
(600, 0, 1352880873, 0, 'admin', '0.0.0.0', 0, 'admin'),
(601, 1, 1352880879, 0, 'admin', '0.0.0.0', 1, 'admin'),
(602, 40, 1352881137, 0, 'sdf', '0.0.0.0', 1, 'admin'),
(603, 0, 1352960048, 0, 'admin', '0.0.0.0', 0, 'admin'),
(604, 1, 1352960054, 0, 'admin', '0.0.0.0', 1, 'admin'),
(605, 1, 1353029010, 0, 'admin', '0.0.0.0', 1, 'admin'),
(606, 1, 1353046364, 0, 'admin', '0.0.0.0', 1, 'admin'),
(607, 1, 1353047071, 0, 'admin', '0.0.0.0', 1, 'admin'),
(608, 0, 1353202680, 0, 'admin', '0.0.0.0', 0, 'admin'),
(609, 1, 1353202685, 0, 'admin', '0.0.0.0', 1, 'admin'),
(610, 1, 1353219692, 0, 'admin', '0.0.0.0', 1, 'admin'),
(611, 1, 1353292610, 0, 'admin', '0.0.0.0', 1, 'admin'),
(612, 1, 1353294422, 0, 'admin', '0.0.0.0', 1, 'admin'),
(613, 1, 1353296517, 0, 'admin', '0.0.0.0', 1, 'admin'),
(614, 1, 1353298524, 0, 'admin', '0.0.0.0', 1, 'admin'),
(615, 0, 1353311131, 0, 'admin', '0.0.0.0', 0, 'admin'),
(616, 1, 1353311135, 0, 'admin', '0.0.0.0', 1, 'admin'),
(617, 0, 1353320422, 0, 'xiaonigue', '0.0.0.0', 0, 'admin'),
(618, 1, 1353320429, 0, 'admin', '0.0.0.0', 1, 'admin'),
(619, 1, 1353396055, 0, 'admin', '0.0.0.0', 1, 'admin'),
(620, 1, 1353459332, 0, 'admin', '0.0.0.0', 1, 'admin'),
(621, 1, 1353655910, 0, 'admin', '0.0.0.0', 1, 'admin'),
(622, 1, 1353805255, 0, 'admin', '0.0.0.0', 1, 'admin'),
(623, 1, 1353898844, 0, 'admin', '0.0.0.0', 1, 'admin'),
(624, 0, 1353910533, 0, 'admin', '0.0.0.0', 0, 'admin'),
(625, 1, 1353910539, 0, 'admin', '0.0.0.0', 1, 'admin'),
(626, 0, 1353917684, 0, 'admin', '0.0.0.0', 0, 'admin'),
(627, 1, 1353917688, 0, 'admin', '0.0.0.0', 1, 'admin'),
(628, 1, 1353917918, 0, 'admin', '0.0.0.0', 1, 'admin'),
(629, 1, 1354067486, 0, 'admin', '0.0.0.0', 1, 'admin'),
(630, 1, 1354067501, 0, 'admin', '0.0.0.0', 1, 'admin'),
(631, 1, 1354083567, 0, 'admin', '0.0.0.0', 1, 'admin'),
(632, 1, 1354084118, 0, 'admin', '0.0.0.0', 1, 'admin'),
(633, 1, 1354085681, 0, 'admin', '0.0.0.0', 1, 'admin'),
(634, 1, 1354085940, 0, 'admin', '0.0.0.0', 1, 'admin'),
(635, 1, 1354086079, 0, 'admin', '0.0.0.0', 1, 'admin'),
(636, 1, 1354086215, 0, 'admin', '0.0.0.0', 1, 'admin'),
(637, 1, 1354087220, 0, 'admin', '0.0.0.0', 1, 'admin'),
(638, 43, 1354089728, 0, '大牛哥', '0.0.0.0', 1, 'admin'),
(639, 1, 1354091564, 0, 'admin', '0.0.0.0', 1, 'admin'),
(640, 1, 1354092029, 0, 'admin', '0.0.0.0', 1, 'admin'),
(641, 1, 1354431022, 0, 'admin', '0.0.0.0', 1, 'admin'),
(642, 1, 1354434819, 0, 'admin', '0.0.0.0', 1, 'admin'),
(643, 1, 1354502988, 0, 'admin', '0.0.0.0', 1, 'admin'),
(644, 1, 1354506046, 0, 'admin', '0.0.0.0', 1, 'admin'),
(645, 1, 1354518638, 0, 'admin', '0.0.0.0', 1, 'admin'),
(646, 1, 1354591432, 0, 'admin', '0.0.0.0', 1, 'admin'),
(647, 1, 1354669607, 0, 'admin', '0.0.0.0', 1, 'admin');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_mail`
--

CREATE TABLE IF NOT EXISTS `needforbug_mail` (
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
-- 表的结构 `needforbug_nav`
--

CREATE TABLE IF NOT EXISTS `needforbug_nav` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- 转存表中的数据 `needforbug_nav`
--

INSERT INTO `needforbug_nav` (`nav_id`, `nav_parentid`, `nav_name`, `nav_identifier`, `nav_title`, `nav_url`, `nav_target`, `nav_type`, `nav_style`, `nav_location`, `nav_status`, `nav_sort`, `nav_color`, `nav_icon`) VALUES
(14, 0, '小组', 'app_group', 'group', 'group://public/index', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 0, 1, 0, 0, ''),
(2, 0, '设为首页', 'sethomepage', '', '#', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 1, 1, 0, 0, ''),
(3, 0, '加入收藏', 'setfavorite', '', '#', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 1, 1, 0, 0, ''),
(4, 0, '关于我们', 'aboutus', '', 'home://homesite/aboutus', 0, 0, '', 2, 1, 0, 0, ''),
(5, 0, '联系我们', 'contactus', '', 'home://homesite/contactus', 0, 0, '', 2, 1, 0, 0, ''),
(6, 0, '用户协议', 'agreement', '', 'home://homesite/agreement', 0, 0, '', 2, 1, 0, 0, ''),
(7, 0, '隐私声明', 'privacy', '', 'home://homesite/privacy', 0, 0, '', 2, 1, 0, 0, ''),
(1, 0, '帮助', 'help', '', 'home://homehelp/index', 0, 0, '', 2, 1, 0, 0, ''),
(23, 0, '商城', 'app_shop', 'shop', 'shop://public/index', 0, 0, '', 0, 1, 0, 0, ''),
(24, 0, '个人空间', 'app_home', 'home', 'home://public/index', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 0, 1, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_node`
--

CREATE TABLE IF NOT EXISTS `needforbug_node` (
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
-- 转存表中的数据 `needforbug_node`
--

INSERT INTO `needforbug_node` (`node_id`, `node_name`, `node_title`, `node_status`, `node_remark`, `node_sort`, `node_parentid`, `node_level`, `nodegroup_id`, `create_dateline`, `update_dateline`) VALUES
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
-- 表的结构 `needforbug_nodegroup`
--

CREATE TABLE IF NOT EXISTS `needforbug_nodegroup` (
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
-- 转存表中的数据 `needforbug_nodegroup`
--

INSERT INTO `needforbug_nodegroup` (`nodegroup_id`, `nodegroup_name`, `nodegroup_title`, `create_dateline`, `update_dateline`, `nodegroup_status`, `nodegroup_sort`) VALUES
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
-- 表的结构 `needforbug_option`
--

CREATE TABLE IF NOT EXISTS `needforbug_option` (
  `option_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `option_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`option_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_option`
--

INSERT INTO `needforbug_option` (`option_name`, `option_value`) VALUES
('admin_list_num', '15'),
('site_name', 'NeedForBug'),
('site_description', 'Enjoy Online Shopping.独属于我的社区购物'),
('site_url', 'http://localhost/needforbug/upload'),
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
('home_description', '{site_name}是一个以电子商务和社区为核心的社区化电子商务（B2C）平台。在这里你可以向网友分享你的购物体验、你喜欢的商品以及心情等等，同时也可以从社区获取来自其它网友的超值的信息，我们崇尚的是理念：<span class="label label-success">简单与分享</span>。'),
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
('needforbug_program_name', 'NeedForBug'),
('needforbug_program_version', '1.0'),
('needforbug_company_name', 'Dianniu Inc.'),
('needforbug_company_url', 'http://dianniu.net'),
('needforbug_program_url', 'http://needforbug.dianniu.net'),
('needforbug_program_year', '2012'),
('needforbug_company_year', '2010-2012'),
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
('front_style_id', '35'),
('admin_theme_name', 'Default'),
('admin_theme_list_num', '6'),
('needforbug_program_company', '点牛(成都)'),
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
('default_app', 'shop');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_pm`
--

CREATE TABLE IF NOT EXISTS `needforbug_pm` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93 ;

--
-- 转存表中的数据 `needforbug_pm`
--

INSERT INTO `needforbug_pm` (`pm_id`, `pm_msgfrom`, `pm_msgfromid`, `pm_msgtoid`, `pm_isread`, `pm_subject`, `create_dateline`, `pm_message`, `pm_status`, `pm_mystatus`, `pm_fromapp`, `pm_type`) VALUES
(1, 'admin', 1, 16, 1, '', 1342430694, 'sdfffffffffffffffffffffffffffffffffff', 1, 1, 'home', 'user'),
(2, 'admin', 1, 16, 1, '', 1342430841, 'sdfffffffffffffffffffffffffffffffffff说的方法', 1, 1, 'home', 'user'),
(3, 'admin', 1, 16, 1, '', 1342430858, '的方式硕鼠硕鼠', 1, 1, 'home', 'user'),
(24, 'xiaomage', 6, 1, 1, '哈哈，欢迎来打酱油哈哈', 1342680012, '你今天有空咩有啊，我特别想见见你啊', 1, 1, 'home', 'user'),
(50, 'admin', 1, 6, 1, '', 1342688234, '哈哈，我又来了哈', 0, 1, 'home', 'user'),
(22, 'xiaomage', 6, 1, 1, '', 1342669438, '小马哥，哈哈哦啊哈哈', 1, 1, 'home', 'user'),
(11, 'admin', 1, 6, 1, '我是小牛个', 1342583612, '小马哥最近可好', 0, 1, 'home', 'user'),
(12, 'xiaomage', 6, 1, 1, '一般般', 1342584005, '现在都是马马虎虎，希望你过得可以啊哈', 1, 1, 'home', 'user'),
(13, 'xiaomage', 6, 1, 1, '一般般', 1342584023, '现在都是马马虎虎，希望你过得可以啊哈', 1, 1, 'home', 'user'),
(14, 'xiaomage', 6, 1, 1, 'fds', 1342584087, 'ssssssssss', 1, 1, 'home', 'user'),
(15, 'xiaomage', 6, 1, 1, '那可以嘛', 1342584180, '好久回家来呢？', 1, 1, 'home', 'user'),
(16, 'xiaomage', 6, 1, 1, '', 1342594530, '我是小马哥', 1, 1, 'home', 'user'),
(17, 'admin', 1, 6, 1, '', 1342595794, '发件人:xiaomage\n收件人:admin\n日期:2012-07-18 14:55:30\n\n[quote]我是小马哥[/quote]\n\nfsdf', 0, 1, 'home', 'user'),
(18, 'admin', 1, 6, 1, '', 1342598672, '呵呵', 0, 1, 'home', 'user'),
(23, 'admin', 1, 6, 1, '1234', 1342670691, '小马哥，我是来打酱油的', 0, 1, 'home', 'user'),
(21, 'admin', 1, 0, 1, '关于日本进攻的故事', 1342659275, '欢迎大家今天晚上准时收听哈。', 1, 1, 'admin', 'system'),
(20, 'admin', 1, 0, 1, '是地方', 1342603333, '反反复复反反复复反反复复反反复复反反复复反反复复飞飞飞', 1, 1, 'admin', 'system'),
(25, 'xiaomage', 6, 1, 1, '', 1342680144, '谢谢关心', 1, 1, 'home', 'user'),
(26, 'xiaomage', 6, 1, 1, '', 1342680199, '今天，我们在火车北站见面，不见不散啊。', 1, 1, 'home', 'user'),
(27, 'xiaomage', 6, 1, 1, '', 1342680277, '运气好', 1, 1, 'home', 'user'),
(28, 'xiaomage', 6, 1, 1, '', 1342681305, 'fdsssss', 1, 1, 'home', 'user'),
(29, 'xiaomage', 6, 1, 1, '', 1342681358, 'sdfffffff', 1, 1, 'home', 'user'),
(30, 'admin', 1, 6, 1, '我是小牛哥', 1342681646, '发件人:xiaomage\n收件人:admin\n日期:2012-07-19 15:02:38\n\n[quote]sdfffffff[/quote]', 0, 1, 'home', 'user'),
(31, 'xiaomage', 6, 1, 1, '', 1342681942, 'dfssssssss', 1, 1, 'home', 'user'),
(32, 'xiaomage', 6, 1, 1, '', 1342682043, 'fdssssss', 1, 1, 'home', 'user'),
(33, 'xiaomage', 6, 1, 1, '', 1342682129, 'fds', 1, 1, 'home', 'user'),
(34, 'xiaomage', 6, 1, 1, '', 1342682269, 'fdsssssssss', 1, 1, 'home', 'user'),
(35, 'xiaomage', 6, 1, 1, '', 1342682476, 'dsfsf', 1, 1, 'home', 'user'),
(36, 'xiaomage', 6, 1, 1, '', 1342682536, 'fdsssssssssss', 1, 1, 'home', 'user'),
(37, 'xiaomage', 6, 1, 1, '', 1342682633, 'fdssssss', 1, 1, 'home', 'user'),
(38, 'xiaomage', 6, 1, 1, '', 1342682658, 'dfsssssssssssssssss', 1, 1, 'home', 'user'),
(39, 'xiaomage', 6, 1, 1, '', 1342682876, 'fsddddddd', 1, 1, 'home', 'user'),
(40, 'xiaomage', 6, 1, 1, '', 1342682917, '测试一下爱', 1, 1, 'home', 'user'),
(41, 'xiaomage', 6, 1, 1, '', 1342682933, '哈哈', 1, 1, 'home', 'user'),
(42, 'xiaomage', 6, 1, 1, '', 1342683027, '你个瓜', 1, 1, 'home', 'user'),
(43, 'xiaomage', 6, 1, 1, '', 1342683092, '的方式硕鼠硕鼠搜索', 1, 1, 'home', 'user'),
(44, 'xiaomage', 6, 1, 1, '', 1342683109, 'dfssssssss', 1, 1, 'home', 'user'),
(45, 'xiaomage', 6, 1, 1, '', 1342683153, '谢谢你的爱', 1, 1, 'home', 'user'),
(46, 'xiaomage', 6, 1, 1, '', 1342683236, 'fdsssssssss', 1, 1, 'home', 'user'),
(47, 'xiaomage', 6, 1, 1, '', 1342683288, 'fsddddddd', 1, 1, 'home', 'user'),
(48, 'xiaomage', 6, 1, 1, '', 1342683324, 'fdsssssssssssssssssssssssssssssssssssssssssssssssssss', 1, 1, 'home', 'user'),
(49, 'xiaomage', 6, 1, 1, '', 1342683371, '不错哈，哈哈哈', 1, 1, 'home', 'user'),
(51, 'woshihapi', 16, 6, 1, '', 1342689220, '小马哥，别来无恙啊', 0, 1, 'home', 'user'),
(52, 'xiaomage', 6, 1, 1, '', 1342689641, '小牛哥，欢迎光临哈，哈哈', 1, 1, 'home', 'user'),
(53, 'admin', 1, 0, 1, '', 1342691166, '发送系统短消息测试一下', 1, 1, 'admin', 'system'),
(54, 'admin', 1, 0, 1, '', 1342691185, '分身乏术', 1, 1, 'admin', 'system'),
(55, 'admin', 1, 6, 1, '', 1342833602, '[pm]发件人:xiaomage\n收件人:admin\n日期:2012-07-19 17:20\n\n[quote]小牛哥，欢迎光临哈，哈哈[/quote][/pm]', 0, 1, 'home', 'user'),
(56, 'admin', 1, 6, 1, '', 1342833723, '[pm]\n发件人:xiaomage\n收件人:admin\n日期:2012-07-19 17:20\n\n[quote]小牛哥，欢迎光临哈，哈哈[/quote]\n[/pm]\n你是一个很特别的人，我非常喜欢', 0, 1, 'home', 'user'),
(57, 'admin', 1, 6, 1, '', 1342841012, '[pm]发件人:xiaomage\n收件人:admin\n日期:2012-07-19 17:20\n\n[quote]小牛哥，欢迎光临哈，哈哈[/quote][/pm]\n你好可爱啊', 0, 1, 'home', 'user'),
(58, 'admin', 1, 6, 1, '', 1342841897, '[pm]发件人:xiaomage\n收件人:admin\n日期:2012-07-19 17:20\n\n[quote]小牛哥，欢迎光临哈，哈哈[/quote][/pm]\n\n你好是地方', 0, 1, 'home', 'user'),
(59, 'admin', 1, 6, 1, '', 1342842451, '[pm]153[/pm]\n\n是地方第三方杀毒', 0, 1, 'home', 'user'),
(60, 'admin', 1, 6, 1, '', 1342842486, '[pmhe]1223[/pmhe]', 0, 1, 'home', 'user'),
(65, 'xiaomage', 6, 1, 1, '兄弟，我是来测是的', 1342853436, '哈哈，是不是啊', 1, 1, 'home', 'user'),
(61, 'admin', 1, 6, 0, '', 1342843015, '不错哈，我和好的', 0, 1, 'home', 'user'),
(62, 'admin', 1, 6, 0, '', 1342843164, 'dfssssssss', 0, 1, 'home', 'user'),
(63, 'admin', 1, 6, 1, '', 1342853043, '[pm]发件人:xiaomage\n收件人:admin\n日期:2012-07-19 17:20\n\n[quote]小牛哥，欢迎光临哈，哈哈[/quote][/pm]\n你很好啊', 0, 1, 'home', 'user'),
(64, 'xiaomage', 6, 1, 0, '', 1342853072, '[pm]发件人:admin\n收件人:xiaomage\n日期:2012-07-21 14:44\n\n[quote][pm]发件人:xiaomage\n收件人:admin\n日期:2012-07-19 17:20\n\n[quote]小牛哥，欢迎光临哈，哈哈[/quote][/pm]\n你很好啊[/quote][/pm]\ndsfffffffffffffffffff', 0, 1, 'home', 'user'),
(66, 'admin', 1, 6, 1, '', 1342854038, '[pm]65[/pm]\n你好留住', 0, 1, 'home', 'user'),
(76, 'admin', 1, 6, 1, '', 1342856107, '你真的很瓜也，哈哈 \n[hr][pm]65[/pm]\n哈哈，是不是啊', 1, 1, 'home', 'user'),
(67, 'xiaomage', 6, 1, 0, '', 1342854177, '[pm]65[/pm]\n你好留住\n[pm]66[/pm]\n呵呵，你们还不错啊。。过得这么好。呵呵', 0, 1, 'home', 'user'),
(68, 'xiaomage', 6, 1, 0, '', 1342854705, '[pm]65[/pm]\n你好留住\n[pm]66[/pm]\n呵呵，你们还不错啊。。过得这么好。呵呵\n[pm]67[/pm]\n我差啊。。。还不赖哦。。。。。', 0, 1, 'home', 'user'),
(69, 'xiaomage', 6, 1, 0, '', 1342854844, '[pm]65[/pm]\n你好留住\n[pm]66[/pm]\n呵呵，你们还不错啊。。过得这么好。呵呵\n[pm]67[/pm][br]\n\n可以，谢谢大家的光临', 0, 1, 'home', 'user'),
(70, 'xiaomage', 6, 1, 0, '', 1342854913, '[pm]65[/pm]\n你好留住\n[pm]66[/pm]\n呵呵，你们还不错啊。。过得这么好。呵呵\n[pm]67[/pm][br]\n\n可以，谢谢大家的光临\n[pm]69[/pm]\n[hr]\n\n哈哈，可以恩，来吧', 0, 1, 'home', 'user'),
(71, 'xiaomage', 6, 1, 0, '', 1342855000, '[pm]65[/pm]\n你好留住\n[pm]66[/pm]\n呵呵，你们还不错啊。。过得这么好。呵呵\n[pm]67[/pm][br]\n\n可以，谢谢大家的光临\n[pm]69[/pm]\n[hr]\n\n哈哈，可以恩，来吧\n[pm]70[/pm]\n[hr],设不了了', 0, 1, 'home', 'user'),
(72, 'xiaomage', 6, 1, 0, '', 1342855069, '[pm]65[/pm]\n你好留住\n[pm]66[/pm][hr]\n你好斯蒂芬斯蒂芬', 0, 1, 'home', 'user'),
(73, 'xiaomage', 6, 1, 0, '', 1342855096, '[pm]65[/pm]\n你好留住\n[pm]66[/pm][hr]\n你好斯蒂芬斯蒂芬\n[pm]72[/pm][hr]\n你可以啊。。。', 0, 1, 'home', 'user'),
(74, 'xiaomage', 6, 1, 0, '', 1342855564, '哈哈，路过啊。。。\n[pm]73[/pm][hr]\n[pm]65[/pm]\n你好留住\n[pm]66[/pm][hr]\n你好斯蒂芬斯蒂芬\n[pm]72[/pm][hr]\n你可以啊。。。', 0, 1, 'home', 'user'),
(75, 'xiaomage', 6, 1, 0, '', 1342855941, 'sadasd\n[hr][pm]74[/pm][hr]\n哈哈，路过啊。。。\n[pm]73[/pm][hr]\n[pm]65[/pm]\n你好留住\n[pm]66[/pm][hr]\n你好斯蒂芬斯蒂芬\n[pm]72[/pm]\n你可以啊。。。', 0, 1, 'home', 'user'),
(77, 'admin', 1, 6, 1, '', 1342856159, '我X，也是一般瓜嘛，你要不要来这里看看嘛，我了歌曲。\n[hr][pm]76[/pm]\n你真的很瓜也，哈哈 \n[hr][pm]65[/pm]\n哈哈，是不是啊', 1, 0, 'home', 'user'),
(78, 'admin', 1, 0, 1, '', 1342856499, '不错啊', 1, 0, 'home', 'user'),
(79, 'admin', 1, 6, 1, '', 1342859436, '共同短消息，哈哈\n[hr][pm]54[/pm]\n哈哈，是不是啊', 1, 0, 'home', 'user'),
(80, 'admin', 1, 0, 1, '', 1342864887, '哈哈，路过', 1, 1, 'admin', 'system'),
(81, 'admin', 1, 0, 1, '', 1342865422, '的风是地方', 1, 1, 'admin', 'system'),
(82, 'admin', 1, 0, 1, '', 1342884531, 'fdssssss', 1, 1, 'admin', 'system'),
(83, 'admin', 1, 0, 1, '', 1342886227, 'fsdsdsdsdsdsdsd', 1, 1, 'admin', 'system'),
(84, 'admin', 1, 0, 1, '', 1342888615, '阿斯达', 1, 1, 'admin', 'system'),
(85, 'xiaomage', 6, 1, 1, '小牛哥有什么事啊', 1342888878, '哈哈，欢迎您来到我们的地方 \n[hr][pm]79[/pm]\n共同短消息，哈哈\n[hr][pm]54[/pm]\n哈哈，是不是啊', 1, 1, 'home', 'user'),
(86, 'xiaomage', 6, 1, 1, '', 1342888946, '也没有什么的事，只是想起了老朋友了哈 \n[hr][pm]77[/pm]\n我X，也是一般瓜嘛，你要不要来这里看看嘛，我了歌曲。\n[hr][pm]76[/pm]\n你真的很瓜也，哈哈 \n[hr][pm]65[/pm]\n哈哈，是不是啊', 1, 1, 'home', 'user'),
(87, 'admin', 1, 0, 1, '', 1342970310, 'df', 1, 1, 'admin', 'system'),
(89, 'xiaomage', 6, 1, 1, '', 1345265300, 'dfssssssss', 0, 1, 'home', 'user'),
(90, 'admin', 1, 6, 0, 'ds', 1350412317, 'dsfffffffff', 1, 1, 'home', 'user'),
(91, 'admin', 1, 6, 0, 'dfg', 1350412349, 'dfg', 1, 1, 'home', 'user'),
(92, 'admin', 1, 6, 0, 'sdf', 1350412361, 'fdsssssssssdsf', 1, 1, 'home', 'user');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_pmsystemdelete`
--

CREATE TABLE IF NOT EXISTS `needforbug_pmsystemdelete` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pm_id` int(10) NOT NULL COMMENT '系统短消息删除状态',
  PRIMARY KEY (`user_id`,`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_pmsystemdelete`
--

INSERT INTO `needforbug_pmsystemdelete` (`user_id`, `pm_id`) VALUES
(1, 82),
(1, 87);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_pmsystemread`
--

CREATE TABLE IF NOT EXISTS `needforbug_pmsystemread` (
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `pm_id` int(10) NOT NULL COMMENT '系统短消息阅读状态',
  PRIMARY KEY (`user_id`,`pm_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_pmsystemread`
--

INSERT INTO `needforbug_pmsystemread` (`user_id`, `pm_id`) VALUES
(1, 20),
(1, 21),
(1, 53),
(1, 54),
(1, 80),
(1, 81),
(1, 83),
(1, 84),
(1, 87),
(40, 82),
(40, 83),
(40, 87);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_rating`
--

CREATE TABLE IF NOT EXISTS `needforbug_rating` (
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
-- 转存表中的数据 `needforbug_rating`
--

INSERT INTO `needforbug_rating` (`rating_id`, `rating_name`, `rating_remark`, `rating_nikename`, `create_dateline`, `update_dateline`, `rating_creditstart`, `rating_creditend`, `ratinggroup_id`, `rating_icon`) VALUES
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
-- 表的结构 `needforbug_ratinggroup`
--

CREATE TABLE IF NOT EXISTS `needforbug_ratinggroup` (
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
-- 转存表中的数据 `needforbug_ratinggroup`
--

INSERT INTO `needforbug_ratinggroup` (`ratinggroup_id`, `ratinggroup_name`, `ratinggroup_title`, `create_dateline`, `update_dateline`, `ratinggroup_status`, `ratinggroup_sort`) VALUES
(1, 'soldiers', '士兵', 1338469985, 0, 1, 0),
(2, 'nco', '士官', 1338470021, 0, 1, 0),
(3, 'lieutenant', '尉官', 1338470314, 1343585867, 1, 0),
(4, 'colonel', '校官', 1338470412, 0, 1, 0),
(5, 'generals', '将帅', 1338470428, 1338914433, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_role`
--

CREATE TABLE IF NOT EXISTS `needforbug_role` (
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
-- 转存表中的数据 `needforbug_role`
--

INSERT INTO `needforbug_role` (`role_id`, `role_name`, `role_parentid`, `role_status`, `role_remark`, `role_nikename`, `create_dateline`, `update_dateline`, `rolegroup_id`) VALUES
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
-- 表的结构 `needforbug_rolegroup`
--

CREATE TABLE IF NOT EXISTS `needforbug_rolegroup` (
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
-- 转存表中的数据 `needforbug_rolegroup`
--

INSERT INTO `needforbug_rolegroup` (`rolegroup_id`, `rolegroup_name`, `rolegroup_title`, `create_dateline`, `update_dateline`, `rolegroup_status`, `rolegroup_sort`) VALUES
(1, 'usergroup', '用户组', 1338469985, 1338614736, 1, 0),
(2, 'admingroup', '管理组', 1338470021, 1338614767, 1, 0),
(3, 'specialgroup', '特殊分组', 1338470314, 1338615164, 1, 0),
(4, 'customgroup', '自定义', 1338616034, 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_session`
--

CREATE TABLE IF NOT EXISTS `needforbug_session` (
  `session_hash` varchar(6) NOT NULL COMMENT 'HASH',
  `session_auth_key` varchar(32) NOT NULL COMMENT 'AUTH_KEY',
  `user_id` mediumint(8) NOT NULL COMMENT '用户ID',
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_session`
--

INSERT INTO `needforbug_session` (`session_hash`, `session_auth_key`, `user_id`) VALUES
('800BfB', 'e4259f3f979f4a3a1c3d0bc1dd65acf2', 1),
('800BfB', 'aed46580ad97199f6222fe9bc7a664a1', 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopaccount`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopaccount` (
  `shopaccount_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '流动资金ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shopaccount_adminuser` varchar(50) NOT NULL COMMENT '操作管理员名',
  `shopaccount_amount` decimal(10,2) NOT NULL COMMENT '资金的数目，正数为增加，负数为减少',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `shopaccount_adminremark` varchar(255) NOT NULL COMMENT '管理员的被准',
  `shopaccount_userremark` varchar(255) NOT NULL COMMENT '用户备注',
  `shopaccount_processtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '操作类型，1，退款；0，预付费',
  `shopaccount_payment` varchar(90) NOT NULL COMMENT '支付渠道的名称',
  `shopaccount_ispaid` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已经付款，０，未付；１，已付',
  PRIMARY KEY (`shopaccount_id`),
  KEY `shopaccount_ispaid` (`shopaccount_ispaid`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopaccountlog`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopaccountlog` (
  `shopaccountlog_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `shopaccountlog_usermoney` decimal(10,2) NOT NULL COMMENT '用户该笔记录的余额',
  `shopaccountlogz_frozenmoney` decimal(10,2) NOT NULL COMMENT '被冻结的资金',
  `shopaccountlog_extendcredit1` int(10) NOT NULL DEFAULT '0' COMMENT '第一种积分变更',
  `shopaccountlog_extendcredit2` int(10) NOT NULL DEFAULT '0' COMMENT '第二种积分变更',
  `shopaccountlog_extendcredit3` int(10) NOT NULL DEFAULT '0' COMMENT '第三种积分变更',
  `shopaccountlog_extendcredit4` int(10) NOT NULL DEFAULT '0' COMMENT '第四种积分变更',
  `shopaccountlog_extendcredit5` int(10) NOT NULL DEFAULT '0' COMMENT '第五种积分变更',
  `shopaccountlog_extendcredit6` int(10) NOT NULL DEFAULT '0' COMMENT '第六种积分变更',
  `shopaccountlog_extendcredit7` int(10) NOT NULL DEFAULT '0' COMMENT '第七种积分变更',
  `shopaccountlog_extendcredit8` int(10) NOT NULL DEFAULT '0' COMMENT '第八种积分变更',
  `shopaccountlog_payextendcredit` int(10) NOT NULL DEFAULT '0' COMMENT '消费积分',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '发生时间',
  `shopaccountlog_description` varchar(255) NOT NULL COMMENT '操作描述',
  `shopaccountlog_type` tinyint(3) unsigned NOT NULL COMMENT '操作类型,0为充值,1,为提现,2为管理员调节,99为其它类型',
  PRIMARY KEY (`shopaccountlog_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopaddress`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopaddress` (
  `shopaddress_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '收获地址ID',
  `shopaddress_name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shopaddress_consignee` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的名字',
  `shopaddress_email` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的email',
  `shopaddress_country` varchar(35) NOT NULL DEFAULT '0' COMMENT '收货人的国家',
  `shopaddress_province` varchar(35) NOT NULL DEFAULT '0' COMMENT '收货人的省份',
  `shopaddress_city` varchar(35) NOT NULL DEFAULT '0' COMMENT '收货人城市',
  `shopaddress_district` varchar(35) NOT NULL DEFAULT '0' COMMENT '收货人的地区',
  `shopaddress_community` varchar(35) NOT NULL COMMENT '收货人乡村',
  `shopaddress_handaddress` varchar(150) NOT NULL COMMENT '手工配送地址',
  `shopaddress_address` varchar(120) NOT NULL DEFAULT '' COMMENT '收货人的详细地址',
  `shopaddress_zipcode` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的邮编',
  `shopaddress_tel` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的电话',
  `shopaddress_mobile` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的手机号',
  `shopaddress_signbuilding` varchar(120) NOT NULL DEFAULT '' COMMENT '收货地址的标志性建筑名',
  `shopaddress_besttime` varchar(120) NOT NULL DEFAULT '' COMMENT '收货人的最佳收货时间',
  PRIMARY KEY (`shopaddress_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoparticle`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoparticle` (
  `shoparticle_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `shoparticlecategory_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '文章分类ID',
  `shoparticle_title` varchar(150) NOT NULL DEFAULT '' COMMENT '文章标题',
  `shoparticle_content` longtext NOT NULL COMMENT '文章内容',
  `shoparticle_username` varchar(30) NOT NULL DEFAULT '' COMMENT '文章用户名',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shoparticle_useremail` varchar(60) NOT NULL DEFAULT '' COMMENT '文章作者用户Email',
  `shoparticle_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '文章关键字',
  `shoparticle_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '文章状态',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shoparticle_description` varchar(255) DEFAULT NULL COMMENT '文章描述',
  `shoparticle_in` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为系统内置，内置将不能删除',
  PRIMARY KEY (`shoparticle_id`),
  KEY `shoparticlecategory_id` (`shoparticlecategory_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shoparticle_status` (`shoparticle_status`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- 转存表中的数据 `needforbug_shoparticle`
--

INSERT INTO `needforbug_shoparticle` (`shoparticle_id`, `shoparticlecategory_id`, `shoparticle_title`, `shoparticle_content`, `shoparticle_username`, `user_id`, `shoparticle_useremail`, `shoparticle_keywords`, `shoparticle_status`, `create_dateline`, `shoparticle_description`, `shoparticle_in`) VALUES
(1, 1, '购买流程', '<div class="title-bar" style="height:auto;padding:30px 0px 10px 30px;line-height:0;font-family:arial, 宋体, sans-serif;white-space:normal;background-position:0px 50%;">\r\n	<header style="margin:0px;padding:0px;">\r\n	<div>\r\n		<div class="exp-title-wrapper" style="width:505px;float:left;">\r\n			<h1 class="light-title" style="margin:0px;padding:0px;font-size:12px;vertical-align:top;">\r\n				<span class="exp-title" style="line-height:30px;font-family:''Microsoft Yahei'', arial, 宋体, sans-serif;font-size:24px;color:#333333;font-weight:normal;">淘宝网商品购买流程</span>\r\n				<ul class="exp-relate" style="margin:5px 0px 0px;padding:0px;list-style:none;font-weight:normal;">\r\n					<li style="margin:0px;padding:0px;float:left;color:#999999;height:22px;line-height:22px;">\r\n						浏览：&nbsp;<span class="browser-times" style="margin:0px 1px;">33161</span>\r\n					</li>\r\n					<li class="blank" style="margin:0px 10px;padding:0px;float:left;color:#E1E1E1;height:22px;line-height:22px;">\r\n						|\r\n					</li>\r\n					<li style="margin:0px;padding:0px;float:left;color:#999999;height:22px;line-height:22px;">\r\n						更新：&nbsp;<time>2011-04-05 10:46</time>\r\n					</li>\r\n					<li class="blank" style="margin:0px 10px;padding:0px;float:left;color:#E1E1E1;height:22px;line-height:22px;">\r\n						|\r\n					</li>\r\n					<li style="margin:0px;padding:0px;float:left;color:#999999;height:22px;line-height:22px;">\r\n						标签：&nbsp;<span class="relate-tag"><a href="http://jingyan.baidu.com/tag/642" target="_blank" style="text-decoration:initial;color:#238BA5;">淘宝</a></span>\r\n					</li>\r\n				</ul>\r\n			</h1>\r\n		</div>\r\n		<div class="title-feedback" style="float:left;">\r\n			<span id="feedback-prompt-e" class="show-prompt" style="color:#FF4200;position:absolute;width:90px;line-height:20px;"></span>\r\n			<div title="成功" id="title-feedback-suc" class="feedback-area feedback-suc-area" style="width:58px;height:30px;line-height:30px;border:1px solid #C8DCE1;cursor:pointer;background-color:#FFFFFF;color:#06728E;z-index:0;float:left;display:inline-block;box-shadow:#E5E5E5 1px 1px 1px;border-top-left-radius:4px;border-top-right-radius:0px;border-bottom-right-radius:0px;border-bottom-left-radius:4px;background-position:initial initial;background-repeat:initial initial;">\r\n				<span class="feedback-img suc-img" style="background-image:url(http://img.baidu.com/img/iknow/exp/detail_icon.png);background-attachment:scroll;background-color:transparent;margin-left:5px;margin-right:5px;width:16px;height:16px;display:inline-block;vertical-align:middle;background-position:0px 0px;background-repeat:no-repeat no-repeat;"></span>&nbsp;<span class="feedback-num" id="title-feedback-suc-num" style="vertical-align:middle;display:inline-block;height:30px;">16</span>\r\n			</div>\r\n			<div title="失败" id="title-feedback-fail" class="feedback-area feedback-fail-area" style="width:58px;height:30px;line-height:30px;border-top-width:1px;border-right-width:1px;border-style:solid solid solid none;border-top-color:#C8DCE1;border-right-color:#C8DCE1;border-bottom-width:1px;border-bottom-color:#C8DCE1;cursor:pointer;background-color:#FFFFFF;color:#06728E;z-index:0;float:left;display:inline-block;box-shadow:#E5E5E5 1px 1px 1px;border-top-left-radius:0px;border-top-right-radius:4px;border-bottom-right-radius:4px;border-bottom-left-radius:0px;background-position:initial initial;background-repeat:initial initial;">\r\n				<span class="feedback-img fail-img" style="background-image:url(http://img.baidu.com/img/iknow/exp/detail_icon.png);background-attachment:scroll;background-color:transparent;margin-left:5px;margin-right:5px;width:16px;height:16px;display:inline-block;vertical-align:middle;background-position:0px -16px;background-repeat:no-repeat no-repeat;"></span>&nbsp;<span class="feedback-num" id="title-feedback-fail-num" style="vertical-align:middle;display:inline-block;height:30px;">5</span>\r\n			</div>\r\n		</div>\r\n	</div>\r\n	<div class="clear" style="clear:both;">\r\n	</div>\r\n</header>\r\n</div>\r\n<div class="exp-content" style="font-size:14px;padding:0px 20px 5px;zoom:1;word-break:break-all;font-family:arial, 宋体, sans-serif;white-space:normal;">\r\n	<div class="brief-album-enter" style="height:78px;border:1px solid #E1E1E1;overflow:hidden;position:relative;margin-left:10px;width:494px;">\r\n		<div class="brief-album-enter-text" style="float:left;border-right-width:1px;border-right-style:solid;border-right-color:#E1E1E1;background-color:#F6F6F6;padding-top:22px;width:18px;padding-left:6px;color:#666666;line-height:18px;height:56px;overflow:hidden;">\r\n			步骤\r\n		</div>\r\n		<ul class="album-filmstrip" id="album-filmstrip" style="margin:15px 0px 0px;padding:0px 10px 0px 3px;list-style:none;float:left;clear:none;">\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=1" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://d.hiphotos.baidu.com/exp/scrop=50/sign=9c24c0f2462309f7e331ea4d023339c3/838ba61ea8d3fd1f925a775c304e251f95ca5f01.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">1</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=1" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=2" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://b.hiphotos.baidu.com/exp/scrop=50/sign=cbf0856e0cd79123e4bed32bdd096cb2/0824ab18972bd407348a069b7b899e510fb30902.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">2</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=2" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=3" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://f.hiphotos.baidu.com/exp/scrop=50/sign=8c715af7cd11728b3473cb7db8c1f6fe/21a4462309f79052c55392580cf3d7ca7bcbd50c.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">3</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=3" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=4" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://d.hiphotos.baidu.com/exp/scrop=50/sign=82ba47d0c9177f3e146abb5200f20ef7/09fa513d269759ee6e1fb5adb2fb43166d22df0d.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">4</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=4" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=5" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://d.hiphotos.baidu.com/exp/scrop=50/sign=ce5e347c2834349b705829dab9d720fe/b812c8fcc3cec3fd17707213d688d43f8794270e.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">5</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=5" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=6" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://h.hiphotos.baidu.com/exp/scrop=50/sign=74a14d38ddc451daf2a84bb4c6c06756/1b4c510fd9f9d72a0f2dc57bd42a2834349bbb0f.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">6</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=6" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=7" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://f.hiphotos.baidu.com/exp/scrop=50/sign=5c5158cfa9014c081d656ffa7a463738/b58f8c5494eef01f8f5ccda3e0fe9925bc317d08.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">7</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=7" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n			<li class="thumb-item" style="margin:0px 0px 0px 7px !important;padding:0px !important;float:left !important;height:50px;width:50px;position:relative;display:inline;background-position:0px 50%;background-repeat:initial initial;">\r\n				<a href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=8" style="text-decoration:initial;color:#238BA5;"><img alt="淘宝网商品购买流程" src="http://h.hiphotos.baidu.com/exp/scrop=50/sign=f53336ded43f8794d7a10f71a2263bc4/f31fbe096b63f6240c16ec658744ebf81a4ca309.jpg" style="height:50px;width:50px;" /></a><span class="thumb-item-index" style="display:block;width:20px;height:18px;background-color:#3F9EAC;font-size:11px;font-weight:bold;position:absolute;left:0px;top:0px;opacity:0.6;text-align:center;color:#FFFFFF;">8</span><a class="thumb-item-curtain" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=8" style="text-decoration:initial;position:absolute;left:0px;top:0px;width:50px;height:50px;color:#238BA5;"></a>\r\n			</li>\r\n		</ul>\r\n		<div class="clear" style="clear:both;">\r\n		</div>\r\n	</div>\r\n	<div class="exp-content-block exp-content-block-1">\r\n		<h2 class="exp-content-head" style="margin:15px 10px 10px;padding:0px;font-size:16px;font-family:''microsoft yahei'';height:30px;line-height:30px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#DCEAED;">\r\n			简介\r\n		</h2>\r\n		<div class="exp-content-body" style="text-align:justify;">\r\n			<div class="exp-content-listblock" style="padding:0px 10px;margin-top:15px;">\r\n				<div class="content-listblock-text">\r\n					在淘宝网购买商品是支持支付宝交易的，您可放心购买，简单分以下四步（不区分境内境外）：<br />\r\n第一步：拍下宝贝<br />\r\n第二步：付款（此付款动作是把钱付到支付宝）<br />\r\n第三步：等待卖家发货<br />\r\n第四步：确认收货（此动作是在收到货没有问题的情况下，把之前支付到支付宝的钱打款给卖家）<br />\r\n具体的操作步骤如下：\r\n				</div>\r\n			</div>\r\n		</div>\r\n	</div>\r\n	<div class="exp-content-block exp-content-block-2">\r\n		<h2 class="exp-content-head" style="margin:15px 10px 10px;padding:0px;font-size:16px;font-family:''microsoft yahei'';height:30px;line-height:30px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#DCEAED;">\r\n			步骤\r\n		</h2>\r\n		<div class="exp-content-body" style="text-align:justify;">\r\n			<ol class="exp-conent-orderlist" style="margin:0px;padding:0px 10px;list-style:none;clear:both;">\r\n				<li class="exp-content-list list-item-1" style="margin:0px;padding:1px 0px 22px 35px !important;background-image:url(http://img.baidu.com/img/iknow/exp/editor/icon_list_item_1.gif);min-height:20px;height:auto;zoom:1;background-position:0px 3px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						选择购买前如对商品信息有任何疑问，请先点击通过阿里旺旺聊天工具联系卖家咨询，确认无误后，点击“立刻购买”。<br />\r\n					</div>\r\n					<div class="content-list-media">\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=1" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/73ca59106a7c28aac2ce7962.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=2" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/aa251d4f79801760aec3ab6c.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n					</div>\r\n				</li>\r\n				<li class="exp-content-list list-item-2" style="margin:0px;padding:1px 0px 22px 35px !important;background-image:url(http://img.baidu.com/img/iknow/exp/editor/icon_list_item_2.gif);min-height:20px;height:auto;zoom:1;background-position:0px 3px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						确认收货地址、购买数量、运送方式等要素，点击“确认无误，购买”。如果您选择了货到付款，点此查看如何使用<br />\r\n					</div>\r\n					<div class="content-list-media">\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=3" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/61183b2d895bc862359bf76d.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n					</div>\r\n				</li>\r\n				<li class="exp-content-list list-item-3" style="margin:0px;padding:1px 0px 22px 35px !important;background-image:url(http://img.baidu.com/img/iknow/exp/editor/icon_list_item_3.gif);min-height:20px;height:auto;zoom:1;background-position:0px 3px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						您可进入“我的淘宝”—“我是买家”—“已买到的宝贝”页面查找到对应的交易记录，交易状态显示“等待买家付款”，该状态下卖家可以修改交易价格，待交易付款金额确认无误后，点击“付款”。<br />\r\n					</div>\r\n					<div class="content-list-media">\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=4" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/6a22e8243812ec658644f96e.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n					</div>\r\n				</li>\r\n				<li class="exp-content-list list-item-4" style="margin:0px;padding:1px 0px 22px 35px !important;background-image:url(http://img.baidu.com/img/iknow/exp/editor/icon_list_item_4.gif);min-height:20px;height:auto;zoom:1;background-position:0px 3px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						进入付款页面（点此查看付款方式介绍），付款成功后，交易状态显示为“买家已付款”，需要等待卖家发货<br />\r\n					</div>\r\n					<div class="content-list-media">\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=5" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/0865b5187c58345134fa416f.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n					</div>\r\n				</li>\r\n				<li class="exp-content-list list-item-5" style="margin:0px;padding:1px 0px 22px 35px !important;background-image:url(http://img.baidu.com/img/iknow/exp/editor/icon_list_item_5.gif);min-height:20px;height:auto;zoom:1;background-position:0px 3px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						待卖家发货后，交易状态更改为“卖家已发货”，待收到货确认无误后，点击“确认收货”。<br />\r\n					</div>\r\n					<div class="content-list-media">\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=6" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/5202e5f2dd657345b17ec568.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n					</div>\r\n				</li>\r\n				<li class="exp-content-list list-item-6" style="margin:0px;padding:1px 0px 22px 35px !important;background-image:url(http://img.baidu.com/img/iknow/exp/editor/icon_list_item_6.gif);min-height:20px;height:auto;zoom:1;background-position:0px 3px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						输入支付宝账户支付密码，点击“确定”。交易状态显示为“交易成功”，说明交易已完成：<br />\r\n					</div>\r\n					<div class="content-list-media">\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=7" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/d019d2bfd9f4605c18d81f69.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n						<div class="content-list-image">\r\n							<a class="exp-image-wraper" href="http://jingyan.baidu.com/album/636f38bb1acbaed6b946107e.html?picindex=8" target="_self" style="text-decoration:initial;color:#238BA5;"><img class="exp-image-default" alt="淘宝网商品购买流程" src="http://hiphotos.baidu.com/exp/pic/item/d478a8005d85dad1e850cd6a.jpg" style="border:1.5px solid #FFFFFF;" /></a>\r\n						</div>\r\n					</div>\r\n				</li>\r\n			</ol>\r\n		</div>\r\n	</div>\r\n	<div class="exp-content-block exp-content-block-3">\r\n		<h2 class="exp-content-head" style="margin:15px 10px 10px;padding:0px;font-size:16px;font-family:''microsoft yahei'';height:30px;line-height:30px;border-bottom-width:1px;border-bottom-style:solid;border-bottom-color:#DCEAED;">\r\n			注意事项\r\n		</h2>\r\n		<div class="exp-content-body" style="text-align:justify;">\r\n			<ul class="exp-content-unorderlist" style="margin:0px;padding:0px 10px;list-style:none;clear:both;">\r\n				<li class="exp-content-list list-item-1" style="background-image:url(http://img.baidu.com/img/iknow/exp/editor/ullist.gif);background-attachment:scroll;margin:auto !important;padding:0px 0px 0px 15px !important;float:none !important;background-position:0px 4px;background-repeat:no-repeat no-repeat;">\r\n					<div class="content-list-text">\r\n						如您看中了同一卖家店铺中多件宝贝可以选择加入购物车，一起购买支付，可能还能够参加店铺促销，点此查看购物车使用图示指南，购买成功后多件宝贝会以一个订单形式展现（如图），后续交易流程同点击“立刻购买”直接购买一样\r\n					</div>\r\n				</li>\r\n			</ul>\r\n		</div>\r\n	</div>\r\n</div>', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354069414, '', 0),
(2, 1, '销售条款', '<p style="margin-top:0px;margin-bottom:10px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	以下详述www.etam.com.cn（以下简称Etam网店）为您提供的服务条款。 本协议详述您在Etam网店使用我们的服务所须遵守的条款和条件。如您有任何疑问，请及时联系我们。\r\n</p>\r\n<p style="margin-top:0px;margin-bottom:10px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	在您注册成为Etam网店会员前，必须仔细阅读本服务条款，一旦您注册成为了Etam网店会员即表示您已经阅读、同意并接受本服务 条款中包含的所有内容和条件。这些条款将有可能不时地被修正。任何修正条款的发生，我们都会在Etam网店页面上发布有关通知，所有协议内容在发布之日后 自动生效。如您不同意该修订，您必须终止您与Etam网店的会员关系。\r\n</p>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		会员资格\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<ul class="question" style="margin:0px 0px 20px;padding:0px;list-style:none;">\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">会员资格：</strong>Etam网店的会员必须是适用法律下能够签订具有法律约束力的合同的个人。在不限制前述规定的前提下，我们的服务不向18周岁以下或被临时或无限期中止权 利的Etam网店会员提供。如您不符合资格，请不要使用我们的服务。您的Etam网店帐户不得向其他方转让或出售。Etam网店保留根据其意愿中止或终止 您的帐户的权利。\r\n			</li>\r\n			<li class="last" style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:0px;border-bottom-style:none;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">注册资料：</strong>为确保交易的正常进行，会员注册时必须向Etam网店提供真实、有效的个人资料，如个人资料有任何变动，必须及时通知Etam网店更新。 Etam网店会员同意接收来自Etam网店或者Etam网店合作伙伴发出的邮件、信息。\r\n			</li>\r\n		</ul>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		隐私声明\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n			Etam网店设有适用于所有会员并纳入会员协议的隐私声明。您应当在注册时阅读并接受隐私声明以使用Etam网店，且您在作为Etam网店会员期间将继续受其规定（及Etam网店对隐私声明作出的任何修订）的约束。\r\n		</p>\r\n		<ul class="question" style="margin:0px 0px 20px;padding:0px;list-style:none;">\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">个人数据资料：</strong>包括提供姓名、用户名、出生日期、性别、地址、电子邮件地址、电话号码、线上会员等级等信息。在未经您同意及确认之前，Etam网店不会将您的资料用于其他目的。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">注册资料：</strong>为确保交易的正常进行，会员注册时必须向Etam网店提供真实、有效的个人资料，如个人资料有任何变动，必须及时通知Etam网店更新。 Etam网店会员同意接收来自Etam网店或者Etam网店合作伙伴发出的邮件、信息。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">使用非个人信息：</strong>Etam网店将通过您的IP地址来收集非个人化的信息，包括您的浏览器性质、操作系统种类、给您提供接入服务的ISP的域名等，以优化在您计算机屏幕上显示的页面。通过收集上述信息，我们亦进行客流量统计，从而改进网站的管理和服务。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">个人数据安全：</strong>Etam网店采用安全性的服务器使用控制设备保护保护您的个人数据。\r\n			</li>\r\n			<li class="last" style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:0px;border-bottom-style:none;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				<strong style="margin:0px;padding:0px;">个人数据披露和限制利用：</strong>当国家机构依照法定程序要求Etam网店披露个人资料时，Etam网店将根据执法单位的要求或为 公共安全目的提供个人资料。在此情况下披露的任何内容，Etam网店不承担任何责任。Etam网店在符合下列条件时，对个人资料进行必要范围以外利用：已 取得您的书面同意；为免除您在生命、身体或财产方面之急迫危险；为防止他人权益之重大危害；为增进公共利益，且无害于您的重大利益。\r\n			</li>\r\n		</ul>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		购买商品\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n			会员在Etam网店进行商品购买时必须遵守以下条款：\r\n		</p>\r\n		<ul class="question" style="margin:0px 0px 20px;padding:0px;list-style:none;">\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				您应就您使用Etam网店的服务及对商品购买遵守所有适用的中国法律、法规、条例和地方性法律要求。您还必须确保您遵守本服务条款和隐私声明及纳入上述文件的所有其他条款和规则的所有规定。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				如您在Etam网店购买商品，您有义务完成与Etam网店的交易，但法律或本服务条款禁止的交易除外。通过对一项商品的下订单，您同意受该商品描述所含的出售条件的约束，只要该出售条件不违反本条款或不是非法的。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				Etam网店网站上的广告，价目表和声明并不构成要约。在Etam网店向您发送电子邮件确认收到并接受了您的订单之前，您和Etam网店之间不存在合同关 系。Etam网店有权在发现了Etam网店上显现的产品及订单的明显错误或缺货的情况下，单方面撤回该信息或撤销合同。Etam网店保留对产品订购的数量 的限制权。在下订单的同时，您也同时承认了您已经达到购买这些产品的法定年龄，并将对您在订单中提供的所有信息的真实性负责。\r\n			</li>\r\n			<li class="last" style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:0px;border-bottom-style:none;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				商品价格和可获性都在网站上指明。这类信息将随时更改且不发任何通知。每一项显示的价格都包含了增值税（按照中国的标准税率）。送货费将另外结算，费用根据您选择的送货方式的不同而异。如果发生了意外情况，在确认了您的订单后，由于供应商提价，税额变化引起的价格变化，或是由于网站的错误等造成价格变 化，Etam网店会通过email或电话通知您，在Etam网店没有取消订单的情况下，让您决定是否取消订单。您所订购的商品，如果发生缺货，您有权取消订单。\r\n			</li>\r\n		</ul>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		商品质量及售后服务\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<ul class="question" style="margin:0px 0px 20px;padding:0px;list-style:none;">\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				详见售后服务条款。\r\n			</li>\r\n			<li class="last" style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:0px;border-bottom-style:none;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				商品的质量，有国家标准或专业标准的，按国家标准或专业标准执行；无前述标准的，按生产厂的企业标准执行；无生产厂企业标准的，由会员与Etam网店协商确定。如果商品质量不符合标准，会员可以要求换货或退货。\r\n			</li>\r\n		</ul>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		网站安全\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n			您同意，未经Etam网店明示书面准许，您不会为了任何目的使用任何机器人、蜘蛛软件、刷屏软件或其他自动方式进入网站。此外，您同意您将不会：\r\n		</p>\r\n		<ul class="question" style="margin:0px 0px 20px;padding:0px;list-style:none;">\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				进行任何对Etam网店网站造成或可能造成（由Etam网店自行酌情确定）不合理或不合比例的重大负荷的行为。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				未经Etam网店事先明示书面准许，对网站的任何内容（除您的个人信息以外）制作拷贝、进行复制、修改、制作衍生作品、分发或公开展示。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				干扰或试图干扰网站的正常工作或网站上进行的任何活动。\r\n			</li>\r\n			<li style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:1px;border-bottom-style:dotted;border-bottom-color:#DADADA;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				在网站中使用违反适用的法律法规下可能视为被禁止或可能被禁止的任何内容。\r\n			</li>\r\n			<li class="last" style="margin:0px 0px 5px;padding:0px 0px 5px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/red_dot_small.png);border-bottom-width:0px;border-bottom-style:none;background-position:0% 5px;background-repeat:no-repeat no-repeat;">\r\n				对网站使用包含可能破坏、改变、删除、不利影响、秘密截取、未经授权而接触或征用任何系统、数据或个人资料的任何病毒、特洛依木马、蠕虫、定时炸弹、删除蝇、复活节彩蛋、间谍软件或其他电脑程序。\r\n			</li>\r\n		</ul>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		知识产权\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n			Etam网店提供的网络服务的相关著作权、专利权、商标、商业秘密及其他任何所有权或权利，均属Etam网店所有。非经Etam网店的同意，任何个人均不 得擅自下载、复制、传输、改编、编辑，否则应负所有法律责任。Etam网店拥有向会员提供网络服务过程中所产生并储存于Etam网店服务器中的任何数据信 息的所有权。\r\n		</p>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		免责声明\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n			Etam网店有义务在技术上确保网站的正常运行，尽力避免服务中断或将中断时间限制在最短时间内，保证会员网上交易活动的顺利进行。但如因不可抗力或其他 Etam网店无法控制的原因使Etam网店崩溃或无法正常使用导致网上交易无法完成或丢失有关的信息、记录等，Etam网店不承担责任；任何会员向 Etam网店提供错误、不完整、不实信息等造成不能正常使用Etam网店服务或遭受任何其他损失，概与Etam网店无关。\r\n		</p>\r\n	</div>\r\n</div>\r\n<div class="qma" style="margin:0px 0px 25px;padding:0px;color:#333333;font-family:微软雅黑, 黑体, Arial, Helvetica, sans-serif, ����;white-space:normal;background-color:#FFFFFF;">\r\n	<div class="caption" style="margin:0px;padding:0px 0px 0px 10px;background-image:url(http://www.etam.com.cn/skin/frontend/etam/default/images/table_title.png);height:26px;line-height:26px;color:#FFFFFF;background-repeat:repeat no-repeat;">\r\n		其他\r\n	</div>\r\n	<div class="content" style="margin:0px;padding:10px 20px 20px;">\r\n		<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n			本协议在所有方面均受中华人民共和国法律管辖。Etam网店所有会员均不可撤销地受Etam网店持有人所在地的法院及上级法院的管辖。本协议的规定是可分 割的，如本协议任何规定被裁定为无效或不可执行，该规定可被删除而其余条款应予以执行。您同意，在发生并购时，本协议和所有纳入的协议可由Etam网店自 行酌情决定向第三方自动转让。标题仅为参考之用，在任何方面均不界定、限制、解释或描述该条的范围或限度。\r\n		</p>\r\n	</div>\r\n</div>', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354069484, '', 0),
(3, 1, '配送方式', '<a href="http://localhost/needforbug/upload/index.php/app/shop/shoparticle/help/id/7" target="_blank" style="color:#995600;text-decoration:initial;font-family:''Helvetica Neue'', Helvetica, Arial, sans-serif;font-size:13px;white-space:normal;-webkit-text-size-adjust:none;background-color:#FFFFFF;">\r\n<div class="helpMain-title f14 fb" style="font-size:14px;font-weight:bold;background-image:url(http://img.m18.com/web/i/help/title_bj.gif);height:32px;line-height:32px;width:700px;padding:0px 25px;color:#333333;font-family:arial;white-space:normal;clear:both;background-repeat:repeat no-repeat;">\r\n	配送方式\r\n</div>\r\n<div class="w700 p_lineheight" style="width:700px;margin:0px auto;color:#333333;font-family:arial;font-size:14px;line-height:21px;white-space:normal;">\r\n	<h4 class="mt10 fb" style="margin:10px 0px;padding:0px;">\r\n		一、麦考林的配送模式分为3种：\r\n	</h4>\r\n	<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n		1、快递---对全国大部分城市麦考林都能委托签约快递公司进行送货上门服务，时间和范围请看配送时间及配送范围。\r\n	</p>\r\n	<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n		2、EMS----针对较偏远的城市，麦考林开展了委托EMS的送货上门业务，由于地段的限制，此类配送服务的时间可能比快递稍长。\r\n	</p>\r\n	<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n		3、邮局---即平邮服务，对于部分更偏远地区，快递和EMS无法服务到的区域，麦考林采用平邮到邮局的方式解决，您可以接到通知后去邮局提货。\r\n	</p>\r\n	<h4 class="mt10 fb" style="margin:10px 0px;padding:0px;">\r\n		二、根据付费方式的不同，麦考林的配送方式也分为2种：\r\n	</h4>\r\n	<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n		1、货到付款---对于全国<span class="h" style="color:#CE0E0E !important;">1000</span>多个城市，麦考林提供货到付款的模式，方便客户进行验货后付款，更具保险性。具体参考配送时间及配送范围。\r\n	</p>\r\n	<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n		2、款到发货---对于目前麦考林还没有开发到上门收款的较远地区，我们提供网上支付后安排货运的模式，不过麦考林正在不断积极开拓，为客户提供更多的上门收款服务，您可以及时上网查询。\r\n	</p>\r\n	<h4 class="mt10 fb" style="margin:10px 0px;padding:0px;">\r\n		三、退货配送\r\n	</h4>\r\n	<p style="margin-top:0px;margin-bottom:10px;padding:0px;">\r\n		麦考林对于货到付款的区域提供免费上门换货一次的服务，对于款到发货的区域，允许客人自叫快递进行一次退货，金额为10元之内，请保留好签收的快递单以备报销之用。\r\n	</p>\r\n</div>\r\n</a>', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354069552, '', 0);
INSERT INTO `needforbug_shoparticle` (`shoparticle_id`, `shoparticlecategory_id`, `shoparticle_title`, `shoparticle_content`, `shoparticle_username`, `user_id`, `shoparticle_useremail`, `shoparticle_keywords`, `shoparticle_status`, `create_dateline`, `shoparticle_description`, `shoparticle_in`) VALUES
(4, 3, '藏家篇', '<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp; 《吉林钱币》杂志是吉林钱币学会主办的内部刊物，于2008年创刊。当《吉林钱币》第2期出版后出现了预想不到的现象: 山西、青海、河北、山东等地收藏者纷纷向吉林省钱币学会会员索取杂志；沈阳收藏市场出售该期杂志，初卖50元一本，后货源枯竭，有商家扫描原文出售；2009年9月，大连某收藏者专程来到长春寻找该期杂志，竟花100元买到一本。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;是什么原因引发洛阳纸贵?缘由《吉林钱币》第2期刊发了《东三省造宣统元宝版式初探》一文。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;2011年岁尾，记者结识了《东三省造宣统元宝版式初探》作者、吉林钱币学会会员张斌，详细了解到此文的前因后果。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;张斌介绍，在中国近代机制金银币的领域里，美国著名钱币收藏家耿爱德 (1880-1962) 所著《中国币图说汇考》(1954年初版) 堪称中国近代机制币研究不朽之作。但该书盲点之一就是清代东三省造宣统元宝银毫。不仅耿爱德，国内近现代钱币研究者也主要研究大型的银圆，而东三省造宣统元宝银毫不被关注，价格被低估。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;1992年，张斌发现了这一问题，开始收藏东三省造宣统元宝银毫，累计收藏200多种。清代东三省造宣统元宝银毫是由奉天造币分厂于宣统元年（1909年）铸造。主要由于清代制作模具的钢材质量和热处理技术与当时世界先进水平差距较大，模具容易损坏，不断修版和改版，导致版别众多，耐人寻味。如有：“三星缶宝”版式——繁体“宝”字有缶；“五星雕云”版式——普通品背面云彩无雕刻。此外，民国年间由奉天造币分厂铸造多种宣统元宝银毫。其中有一种正面中心为一朵梅花版式；一种背面“龙爪抓大珠上开口云” 版式——普通品背面不抓珠；一种“稀珠圈七齿龙”版式——普通品背面不是七齿龙。此外，张作霖执掌奉天军政大权于1916年铸有铜镀银和铅镀银的宣统元宝，近于私铸，但制作较精，外观仍然十分接近规范的银毫。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;“从收藏和娱乐的心态看，收藏东三省造宣统元宝银毫是一种很好的消遣。”张斌说，研究东三省造宣统元宝银毫，更多的源自一个藏家对自己藏品深深的挚爱。东三省造宣统元宝银毫的版式，有时候不仅仅表现在细节的清晰和美丽上，有很多时候需要收集者用灵感去捕捉，用心灵去感受，用灵魂去触摸。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;2008年，吉林省钱币学会《吉林钱币》创刊，向张斌约稿。他考虑当时东三省造宣统元宝银毫研究尚属空白，所以写成《东三省造宣统元宝版式初探》。该文中很大的篇幅对东三省造宣统元宝银毫的版式和历史进行了尝试性的探讨，在国内首次清楚科学地划分东三省造宣统元宝银毫53种版式，刊发时附多种版式图片。此前，省内外钱币收藏者一直不清楚东三省造宣统元宝版式特征和版式数量多寡。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;这一期《吉林钱币》杂志出版后，立即引起省内外钱币收藏者的高度关注。沈阳钱币研究者许先生和大连钱币研究者李先生都说:没想到，吉林省还能出这样高水平的杂志。张斌经常接到外省钱币收藏者咨询电话。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;而在收藏市场上，东三省造宣统元宝银毫的市场成交量突然之间急剧扩大，价格扶摇直上，一般品种上涨3～10倍，稀缺品种变成按品相论价，个别品种上涨30～50倍。如最著名的东三省造宣统元宝银毫“三星缶宝”版，原普通品相的只有2000元至3000元，后上涨到6万元至10万元，而精美品无人出售，差品成交价也达到3.5万元。长春市收藏市场反应最快，立刻推高东三省造宣统元宝银毫的价格。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;有些省内外收藏者和张斌开玩笑说，东三省造宣统元宝银毫价格快速上涨，都是他文章惹的祸! 因为，他的文章发表“东三省造宣统元宝银毫价格的快速涨升，并非我的本意，没想到这一天来得这么快，这么急，但我又想，只要自己经历了、思考了、懂得了，也就无悔了。”张斌说，既然东三省造宣统元宝银毫的价格一度被低估，处在一个与她极不相称的位置上，那么价格的上升只是迟早的事。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;张斌认为，他写的文章，客观上符合市场的潮流，无形之中强化了省内外收藏者有关东三省造宣统元宝银毫版式观念，提升了这种钱币的市场关注度，使其价值得到市场的重新认识和再定位。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;“五星雕云”版式宣统元宝银毫。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;“三星缶宝”版式宣统元宝银毫。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;“稀珠圈七齿龙”版式宣统元宝银毫。\r\n</p>\r\n<p style="font-family:Simsun;line-height:normal;text-indent:24px;white-space:normal;background-color:#F7FAFF;">\r\n	&nbsp;&nbsp;“龙爪抓大珠上开口云” 版式宣统元宝银毫。\r\n</p>', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354070822, '', 0),
(5, 1, '支付方式', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411025, '', 0),
(6, 1, '隐私保护', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411233, '', 0),
(7, 1, '常见问题', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411257, '', 0),
(8, 1, '意见反馈', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411272, '', 0),
(9, 3, '站点流程', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411457, '', 0),
(10, 3, '创作说明', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411469, '', 0),
(11, 4, '历程', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411504, '', 0),
(12, 4, '公司介绍', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411520, '', 0),
(13, 4, '联系我们', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411532, '', 0),
(14, 4, '关注我们', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411545, '', 0),
(15, 4, '合作伙伴', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411557, '', 0),
(16, 4, '业务原则', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411569, '', 0),
(17, 4, '奖项荣誉', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411589, '', 0),
(18, 4, '赞助与慈善', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354411600, '', 0),
(19, 5, '第九期蓓蕾培训时间通知', '<p>\r\n	2012-12-12\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<strong>在线报名 联系客服</strong>\r\n</p>', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354011426, '', 0),
(20, 5, '第十期蓓蕾培训时间通知', '', 'admin', 1, 'xiaoniuge@dianniu.net', '', 1, 1354420518, '', 0),
(25, 6, '', '重新反馈', 'admin', 0, 'xiaoniuge@dianniu.net', '', 0, 1354429449, NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoparticlecategory`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoparticlecategory` (
  `shoparticlecategory_id` smallint(6) NOT NULL AUTO_INCREMENT COMMENT '文章分类ID',
  `shoparticlecategory_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文章分类名字',
  `shoparticlecategory_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '文件分类关键字',
  `shoparticlecategory_description` varchar(255) NOT NULL DEFAULT '' COMMENT '文件分类描述',
  `shoparticlecategory_sort` smallint(6) unsigned NOT NULL DEFAULT '50' COMMENT '文章分类排序',
  `shoparticlecategory_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分类类型：1，代表普通分类  9，网站底部的分类',
  `shoparticlecategory_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分类状态',
  `shoparticlecategory_parentid` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '文件分类父级ID',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`shoparticlecategory_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shoparticlecategory_sort` (`shoparticlecategory_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `needforbug_shoparticlecategory`
--

INSERT INTO `needforbug_shoparticlecategory` (`shoparticlecategory_id`, `shoparticlecategory_name`, `shoparticlecategory_keywords`, `shoparticlecategory_description`, `shoparticlecategory_sort`, `shoparticlecategory_type`, `shoparticlecategory_status`, `shoparticlecategory_parentid`, `create_dateline`) VALUES
(1, '如何购买', '', '', 0, 9, 1, 0, 1353805312),
(2, '站点地图', '', '', 0, 9, 1, 0, 1353805364),
(3, '商城特色', '', '', 0, 9, 1, 0, 1353805377),
(4, '关于我们', '', '', 0, 9, 1, 0, 1353805393),
(5, '商城公告', '', '公告分类', 0, 1, 1, 0, 1354419136),
(6, '意见反馈', '', '用户反馈信息分类', 0, 1, 1, 0, 1354419163);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoparticlecomment`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoparticlecomment` (
  `shoparticlecomment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章评论ID',
  `shoparticle_id` mediumint(10) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `shoparticlecomment_email` varchar(60) NOT NULL DEFAULT '' COMMENT '评论人邮件',
  `shoparticlecomment_username` varchar(60) NOT NULL DEFAULT '' COMMENT '评论人用户名',
  `shoparticlecomment_content` text NOT NULL COMMENT '评论内容',
  `shoparticlecomment_commentrank` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '文章的重星级;只有1到5星;由数字代替;其中5代表5星',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `shoparticlecomment_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '评论者IP',
  `shoparticlecomment_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '评论状态',
  `shoparticlecomment_parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论父级ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论用户ID',
  PRIMARY KEY (`shoparticlecomment_id`),
  KEY `user_id` (`user_id`),
  KEY `shoparticlecomment_parentid` (`shoparticlecomment_parentid`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shoparticle_id` (`shoparticle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopattribute`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopattribute` (
  `shopattribute_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品属性ID',
  `shopgoodstype_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '商品分类ID',
  `shopattribute_name` varchar(60) NOT NULL DEFAULT '' COMMENT '商品属性名字',
  `shopattribute_inputtype` varchar(15) NOT NULL DEFAULT 'text' COMMENT '当添加商品时,该属性的类型:数字(number),字串(text),文本(textarea),开关(radio),单选选择(select),多项选择(selects),颜色(color)',
  `shopattribute_type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '属性是否多选; 0否; 1是 如果可以多选,则可以自定义属性,并且可以根据值的不同定不同的价',
  `shopattribute_values` text NOT NULL COMMENT '即选择输入,则shopattribute_name对应的值的取值就是该这字段值',
  `shopattribute_index` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '属性是否可以检索;0不需要检索; 1关键字检索2范围检索,该属性应该是如果检索的话,可以通过该属性找到有该属性的商品',
  `shopattribute_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '商品属性排序',
  `shopattribute_islink` tinyint(1) NOT NULL DEFAULT '0' COMMENT '相同属性值是否进行关联',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`shopattribute_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shopattribute_sort` (`shopattribute_sort`),
  KEY `shopgoodstype_id` (`shopgoodstype_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `needforbug_shopattribute`
--

INSERT INTO `needforbug_shopattribute` (`shopattribute_id`, `shopgoodstype_id`, `shopattribute_name`, `shopattribute_inputtype`, `shopattribute_type`, `shopattribute_values`, `shopattribute_index`, `shopattribute_sort`, `shopattribute_islink`, `create_dateline`) VALUES
(1, 2, '书画院', 'text', 0, '', 0, 0, 0, 1352017284),
(2, 2, '绘画作者', 'text', 0, '', 0, 0, 0, 1352017535),
(3, 2, '绘画宽度', 'number', 0, '', 0, 0, 0, 1352017630),
(4, 2, '绘画高度', 'number', 0, '', 0, 0, 0, 1352017926),
(5, 1, '书画院', 'text', 0, '', 0, 0, 0, 1352018488),
(6, 1, '书法作者', 'text', 0, '', 0, 0, 0, 1352018506),
(7, 1, '书法宽度', 'number', 0, '', 0, 0, 0, 1352018521),
(8, 1, '书法高度', 'number', 0, '', 0, 0, 0, 1352018529);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopattributevalue`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopattributevalue` (
  `shopattributevalue_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品属性值ID',
  `shopgoods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `shopattribute_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '商品属性ID',
  `shopattribute_value` text NOT NULL COMMENT '商品属性值',
  `shopattribute_price` varchar(255) NOT NULL DEFAULT '' COMMENT '商品属性价格',
  PRIMARY KEY (`shopattributevalue_id`),
  KEY `shopgoods_id` (`shopgoods_id`),
  KEY `shopattribute_id` (`shopattribute_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopcart`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopcart` (
  `shopcart_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '购物车ID',
  `user_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户登录ID',
  `shopgoods_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '商品的ID',
  `shopcart_goodssn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品的货号',
  `shopproduct_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '产品ID',
  `shopcart_goodsname` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名称',
  `shopcart_marketprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品的本店价,取自表市场价',
  `shopcart_goodsprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品的本店价',
  `shopcart_goodsnumber` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '商品的购买数量,在购物车时,实际库存不减少',
  `shopcart_goodsattr` text NOT NULL COMMENT '商品的扩展属性',
  `shopcart_isreal` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '来自shopgoods',
  `shopcart_parentid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '该商品的父商品ID,没有该值为0,有的话那该商品就是该id的配件',
  `shopcart_isgift` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '是否赠品,0否;其他, 是参加优惠活动的id',
  `shopcart_isshipping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '来自shopgoods',
  `shopcart_canhandsel` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '来自shopgoods',
  `shopcart_goodsattrid` varchar(255) NOT NULL DEFAULT '' COMMENT '该商品的属性',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`shopcart_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `user_id` (`user_id`),
  KEY `shopgoods_id` (`shopgoods_id`),
  KEY `shopproduct_id` (`shopproduct_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopcategory`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopcategory` (
  `shopcategory_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `shopcategory_name` varchar(90) NOT NULL DEFAULT '' COMMENT '分类名称',
  `shopcategory_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '分类的关键字,可能是为了搜索',
  `shopcategory_description` varchar(255) NOT NULL DEFAULT '' COMMENT '分类描述',
  `shopcategory_parentid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '该分类的父类ID',
  `shopcategory_sort` smallint(6) unsigned NOT NULL DEFAULT '50' COMMENT '该分类在页面显示的顺序,数字越大顺序越靠后,同数字,id在前的先显示',
  `shopcategory_template` varchar(50) NOT NULL DEFAULT '' COMMENT '该分类的单独模板文件的名字',
  `shopcategory_measureunit` varchar(15) NOT NULL DEFAULT '' COMMENT '该分类的计量单位',
  `shopcategory_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否在前台页面显示 1显示; 0不显示',
  `shopcategory_grade` tinyint(4) NOT NULL DEFAULT '0' COMMENT '分类的最高和最低价之间的价格分级,当大于1时,会根据最大最小价格区间分成区间,会在页面显示价格范围,如0-300,300-600,600-900这种; ',
  `shopcategory_filterattr` varchar(255) NOT NULL DEFAULT '0' COMMENT '如果该字段有值,则该分类将还会按照该值对应在表goods_attr的goods_attr_id所对应的属性筛选，如，楷书，隶书',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `shopcategory_goodsnum` int(10) NOT NULL DEFAULT '0' COMMENT '商品数量',
  PRIMARY KEY (`shopcategory_id`),
  KEY `shopcategory_sort` (`shopcategory_sort`),
  KEY `shopcategory_status` (`shopcategory_status`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `needforbug_shopcategory`
--

INSERT INTO `needforbug_shopcategory` (`shopcategory_id`, `shopcategory_name`, `shopcategory_keywords`, `shopcategory_description`, `shopcategory_parentid`, `shopcategory_sort`, `shopcategory_template`, `shopcategory_measureunit`, `shopcategory_status`, `shopcategory_grade`, `shopcategory_filterattr`, `create_dateline`, `shopcategory_goodsnum`) VALUES
(1, '书法', '', '', 0, 0, '', '', 1, 0, '0', 1351756484, 0),
(2, '篆书', '', '', 1, 0, '', '', 1, 0, '0', 1351756622, 0),
(3, '隶书', '', '', 1, 0, '', '', 1, 0, '0', 1351756636, 0),
(4, '楷书', '', '', 1, 0, '', '', 1, 0, '0', 1351756662, 0),
(5, '草书', '', '', 1, 0, '', '', 1, 0, '0', 1351756683, 0),
(6, '美术', '', '', 0, 0, '', '', 1, 0, '0', 1351756699, 0),
(7, '西洋画', '', '', 6, 0, '', '', 1, 0, '0', 1351756716, 0),
(8, '国画', '', '', 6, 0, '', '', 1, 0, '0', 1351756729, 0),
(9, '山水画', '', '', 8, 0, '', '', 1, 0, '0', 1351756818, 0),
(10, '写意花鸟画', '', '', 8, 0, '', '', 1, 0, '0', 1351756830, 0),
(11, '工笔画', '', '', 8, 0, '', '', 1, 0, '0', 1351756887, 0),
(12, '阿文书法', '', '', 0, 0, '', '', 1, 0, '0', 1351756928, 0),
(13, '库法体', '', '库法体是设计描绘出来的字体', 12, 0, '', '', 1, 0, '0', 1351756941, 0),
(14, '三一体', '', '三一体、波斯体基本是行草书一类', 12, 0, '', '', 1, 0, '0', 1351756951, 0),
(15, '波斯体', '', '三一体、波斯体基本是行草书一类', 12, 0, '', '', 1, 0, '0', 1351756961, 0),
(16, '公文体', '', '公文体和誊草体是誊写古兰经的字体', 12, 0, '', '', 1, 0, '0', 1351756971, 0),
(17, '誊草体', '', '公文体和誊草体是誊写古兰经的字体', 12, 0, '', '', 1, 0, '0', 1351756987, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopcategoryrecommend`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopcategoryrecommend` (
  `shopcategoryrecommend_id` smallint(5) NOT NULL COMMENT '推荐分类ID',
  `shopcategoryrecommend_type` tinyint(1) NOT NULL COMMENT '商品分类推荐类型:1精品 2新品 3热门',
  PRIMARY KEY (`shopcategoryrecommend_id`,`shopcategoryrecommend_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopcollect`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopcollect` (
  `shopcollect_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏记录ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shopgoods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `shopcollect_isattention` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否关注该收藏商品;1是;0否',
  PRIMARY KEY (`shopcollect_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shopgoods_id` (`shopgoods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopfeed`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopfeed` (
  `shopfeed_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shopfeed_username` varchar(50) NOT NULL COMMENT '用户名',
  `shopfeed_template` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态模板',
  `shopfeed_data` varchar(1024) NOT NULL DEFAULT '' COMMENT '动态数据',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`shopfeed_id`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopgoods`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopgoods` (
  `shopgoods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `shopcategory_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '商品所属商品分类id',
  `shopgoods_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品的唯一货号',
  `shopgoods_name` varchar(120) NOT NULL DEFAULT '' COMMENT '商品的名称',
  `shopgoods_views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品点击数',
  `shopgoods_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品库存数量',
  `shopgoods_weight` decimal(10,3) unsigned NOT NULL DEFAULT '0.000' COMMENT '商品的重量，以千克为单位',
  `shopgoods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场售价',
  `shopgoods_shopprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '本店售价',
  `shopgoods_promoteprice` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '促销价格',
  `shopgoods_promotestartdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销价格开始日期',
  `shopgoods_promoteenddate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '促销价格结束日期',
  `shopgoods_warnnumber` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '商品报警数量',
  `shopgoods_keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '商品关键字，放在商品页的关键字中，为搜索引擎收录用',
  `shopgoods_brief` varchar(255) NOT NULL DEFAULT '' COMMENT '商品的简短描述',
  `shopgoods_description` text NOT NULL COMMENT '商品的详细描述',
  `shopgoods_thumb` varchar(255) NOT NULL DEFAULT '' COMMENT '商品在前台显示的微缩图片，如在分类筛选时显示的小图片',
  `shopgoods_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品的实际大小图片，如进入该商品页时介绍商品属性所显示的大图片',
  `shopgoods_originalimg` varchar(255) NOT NULL DEFAULT '' COMMENT '商品的原始图片',
  `shopgoods_isreal` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物，1，是；0，否；比如虚拟卡就为0，不是实物',
  `shopgoods_isonsale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '该商品是否开放销售，1，是；0，否',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品添加时间',
  `shopgoods_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商品状态',
  `shopgoods_isbest` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是精品；0，否；1，是',
  `shopgoods_isnew` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是新品',
  `shopgoods_ishot` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否热销，0，否；1，是',
  `shopgoods_isupcoming` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否属于即将上市',
  `shopgoods_ispromote` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否特价促销；0，否；1，是',
  `update_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最近一次更新商品配置的时间',
  `shopgoodstype_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '商品所属类型id',
  `shopgoods_sellerremark` varchar(255) NOT NULL DEFAULT '' COMMENT '商品的商家备注，仅商家可见',
  `shopgoods_sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '推荐排序',
  `shopgoods_isshipping` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否免运费',
  `shopartist_id` smallint(3) NOT NULL COMMENT '商品艺术家ID',
  `shopgoods_artistdescription` text NOT NULL COMMENT '艺术作品描述',
  PRIMARY KEY (`shopgoods_id`),
  KEY `shopgoods_sn` (`shopgoods_sn`),
  KEY `shopgoodscategory_id` (`shopcategory_id`),
  KEY `update_dateline` (`update_dateline`),
  KEY `shopgoods_weight` (`shopgoods_weight`),
  KEY `shopgoods_promoteenddate` (`shopgoods_promoteenddate`),
  KEY `shopgoods_promotestartdate` (`shopgoods_promotestartdate`),
  KEY `shopgoods_number` (`shopgoods_number`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shopartist_id` (`shopartist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `needforbug_shopgoods`
--

INSERT INTO `needforbug_shopgoods` (`shopgoods_id`, `shopcategory_id`, `shopgoods_sn`, `shopgoods_name`, `shopgoods_views`, `shopgoods_number`, `shopgoods_weight`, `shopgoods_price`, `shopgoods_shopprice`, `shopgoods_promoteprice`, `shopgoods_promotestartdate`, `shopgoods_promoteenddate`, `shopgoods_warnnumber`, `shopgoods_keywords`, `shopgoods_brief`, `shopgoods_description`, `shopgoods_thumb`, `shopgoods_img`, `shopgoods_originalimg`, `shopgoods_isreal`, `shopgoods_isonsale`, `create_dateline`, `shopgoods_status`, `shopgoods_isbest`, `shopgoods_isnew`, `shopgoods_ishot`, `shopgoods_isupcoming`, `shopgoods_ispromote`, `update_dateline`, `shopgoodstype_id`, `shopgoods_sellerremark`, `shopgoods_sort`, `shopgoods_isshipping`, `shopartist_id`, `shopgoods_artistdescription`) VALUES
(9, 8, '', '西藏风景', 0, 0, 0.000, 5000.00, 5000.00, 0.00, 1354435446, 1354435446, 0, '', '西藏的行政区划分为1个地级市、6个地区，下辖1个县级市，70个县，1个县，1个县级区，2个县级办事处，899个乡，31个镇。自治区首府设在拉萨市。西藏，古为羌、戎地。唐、宋时为吐蕃地。元为宣政院所辖乌思、藏、纳里速古鲁孙等三路宣慰使司地。明代依元制，仍称乌思藏。清代分为卫（前藏）、藏（后藏）、喀木（康）、阿里四部，因在中国西境，故总称西藏。西藏一词，最早见于清康熙二年（公元1663年）。1951年和平解放西藏。1956年将原西康省昌都地区并入西藏。1965年9月9日，建立西藏自治区。藏是汉语赋予的称谓，而', '', 'day_20121202/thumb/thumb350_e48028e374c8967018c93a08ddc48fce.jpg', 'day_20121202/thumb/thumb550_e48028e374c8967018c93a08ddc48fce.jpg', 'day_20121202/e48028e374c8967018c93a08ddc48fce.jpg', 1, 1, 1354436256, 0, 1, 1, 1, 1, 0, 1354678615, 0, '', 0, 0, 0, ''),
(10, 8, '', '家乡的冬天', 0, 0, 0.000, 10000.00, 10000.00, 0.00, 1354436298, 1354436298, 0, '', '', '', 'day_20121202/thumb/thumb350_02cff35c6a9d0431227cda99d08dabd6.jpg', 'day_20121202/thumb/thumb550_02cff35c6a9d0431227cda99d08dabd6.jpg', 'day_20121202/02cff35c6a9d0431227cda99d08dabd6.jpg', 1, 1, 1354436361, 0, 1, 0, 0, 1, 0, 1354671886, 0, '', 0, 0, 0, ''),
(11, 8, '', '农家', 0, 0, 0.000, 10000.00, 10000.00, 0.00, 1354436629, 1354436629, 0, '', '', '', 'day_20121202/thumb/thumb350_f520899968ef2559cfefbc0d29283436.jpg', 'day_20121202/thumb/thumb550_f520899968ef2559cfefbc0d29283436.jpg', 'day_20121202/f520899968ef2559cfefbc0d29283436.jpg', 1, 1, 1354436675, 0, 0, 0, 0, 0, 0, 1354436700, 0, '', 0, 0, 0, ''),
(12, 8, '', '远景', 0, 0, 0.000, 8000.00, 8000.00, 0.00, 1354436794, 1354436794, 0, '', '', '', 'day_20121202/thumb/thumb350_172a96068090c86b207598c9d99a646d.jpg', 'day_20121202/thumb/thumb550_172a96068090c86b207598c9d99a646d.jpg', 'day_20121202/172a96068090c86b207598c9d99a646d.jpg', 1, 1, 1354436818, 0, 1, 1, 1, 1, 0, 1354678241, 0, '', 0, 0, 0, ''),
(13, 8, '', '白石', 0, 0, 0.000, 9000.00, 9000.00, 0.00, 1354436862, 1354436862, 0, '', '', '', 'day_20121202/thumb/thumb350_a5d52f2f8e3a37293faa20ce7d95d136.jpg', 'day_20121202/thumb/thumb550_a5d52f2f8e3a37293faa20ce7d95d136.jpg', 'day_20121202/a5d52f2f8e3a37293faa20ce7d95d136.jpg', 1, 1, 1354436896, 0, 1, 1, 1, 1, 0, 1354678228, 0, '', 0, 0, 0, ''),
(14, 4, '', '厚德载物', 0, 0, 0.000, 9000.00, 9000.00, 0.00, 1354437179, 1354437179, 0, '', '', '', 'day_20121202/thumb/thumb350_739be120fd0ec6c075043add95d60564.jpg', 'day_20121202/thumb/thumb550_739be120fd0ec6c075043add95d60564.jpg', 'day_20121202/739be120fd0ec6c075043add95d60564.jpg', 1, 1, 1354437258, 0, 1, 0, 0, 0, 0, 1354669803, 0, '', 0, 0, 0, ''),
(15, 2, '', '篆书', 0, 0, 0.000, 3000.00, 3000.00, 0.00, 1354437347, 1354437347, 0, '', '', '', 'day_20121202/thumb/thumb350_0dc949150c49024510d5b268ccad0755.jpg', 'day_20121202/thumb/thumb550_0dc949150c49024510d5b268ccad0755.jpg', 'day_20121202/0dc949150c49024510d5b268ccad0755.jpg', 1, 1, 1354437433, 0, 1, 1, 1, 1, 0, 1354678216, 0, '', 0, 0, 0, ''),
(16, 3, '', '隶书', 0, 0, 0.000, 2200.00, 2200.00, 0.00, 1354437454, 1354437454, 0, '', '', '', 'day_20121202/thumb/thumb350_955fb73f3787da921441bee851e6ee79.jpg', 'day_20121202/thumb/thumb550_955fb73f3787da921441bee851e6ee79.jpg', 'day_20121202/955fb73f3787da921441bee851e6ee79.jpg', 1, 1, 1354437544, 0, 0, 0, 0, 1, 0, 1354676447, 0, '', 0, 0, 0, ''),
(17, 4, '', '楷书', 0, 0, 0.000, 4000.00, 4000.00, 0.00, 1354437600, 1354437600, 0, '', '', '', 'day_20121202/thumb/thumb350_fdf5d139817bfbfaabfbc33435644a24.jpg', 'day_20121202/thumb/thumb550_fdf5d139817bfbfaabfbc33435644a24.jpg', 'day_20121202/fdf5d139817bfbfaabfbc33435644a24.jpg', 1, 1, 1354437628, 0, 1, 1, 1, 1, 0, 1354678204, 0, '', 0, 0, 0, ''),
(18, 5, '', '草书', 0, 0, 0.000, 15000.00, 15000.00, 0.00, 1354437773, 1354437773, 0, '', '', '', 'day_20121202/thumb/thumb350_07321445100f06e7c62406447e236311.jpg', 'day_20121202/thumb/thumb550_07321445100f06e7c62406447e236311.jpg', 'day_20121202/07321445100f06e7c62406447e236311.jpg', 1, 1, 1354437795, 0, 1, 1, 1, 1, 0, 1354678194, 0, '', 0, 0, 0, ''),
(19, 12, '', '阿文', 0, 0, 0.000, 5000.00, 5000.00, 0.00, 1354437816, 1354437816, 0, '', '', '', 'day_20121202/thumb/thumb350_d4a300fa8818ee75486a19145aef4bb7.jpg', 'day_20121202/thumb/thumb550_d4a300fa8818ee75486a19145aef4bb7.jpg', 'day_20121202/d4a300fa8818ee75486a19145aef4bb7.jpg', 1, 1, 1354437843, 0, 1, 1, 1, 1, 0, 1354678182, 0, '', 0, 0, 0, ''),
(20, 12, '', '阿文2', 0, 0, 0.000, 6000.00, 6000.00, 0.00, 1354437860, 1354437860, 0, '', '', '', 'day_20121202/thumb/thumb350_95cdf6efb8878ba9bee1856bd7ab80c1.jpg', 'day_20121202/thumb/thumb550_95cdf6efb8878ba9bee1856bd7ab80c1.jpg', 'day_20121202/95cdf6efb8878ba9bee1856bd7ab80c1.jpg', 1, 1, 1354437893, 0, 1, 1, 1, 1, 0, 1354678170, 0, '', 0, 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopgoodscomment`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopgoodscomment` (
  `shopgoodscomment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品评论ID',
  `shopgoods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `shopgoodscomment_email` varchar(300) NOT NULL DEFAULT '' COMMENT '评论人邮件',
  `shopgoodscomment_username` varchar(50) NOT NULL DEFAULT '' COMMENT '评论人用户名',
  `shopgoodscomment_url` varchar(300) NOT NULL COMMENT '商品评论URL',
  `shopgoodscomment_content` text NOT NULL COMMENT '评论内容',
  `shopgoodscomment_rank` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商品的重星级;只有1到5星;由数字代替;其中5代表5星',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `shopgoodscomment_ip` varchar(15) NOT NULL DEFAULT '' COMMENT '评论者IP',
  `shopgoodscomment_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '评论状态',
  `shopgoodscomment_parentid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论父级ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '评论用户ID',
  PRIMARY KEY (`shopgoodscomment_id`),
  KEY `user_id` (`user_id`),
  KEY `shopgoodscomment_parentid` (`shopgoodscomment_parentid`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shopgoods_id` (`shopgoods_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `needforbug_shopgoodscomment`
--

INSERT INTO `needforbug_shopgoodscomment` (`shopgoodscomment_id`, `shopgoods_id`, `shopgoodscomment_email`, `shopgoodscomment_username`, `shopgoodscomment_url`, `shopgoodscomment_content`, `shopgoodscomment_rank`, `create_dateline`, `shopgoodscomment_ip`, `shopgoodscomment_status`, `shopgoodscomment_parentid`, `user_id`) VALUES
(1, 19, '', 'admin', '', '经典的作品', 3, 1354506065, '0.0.0.0', 1, 0, 1),
(2, 19, '', 'admin', '', '不错，支持一下', 3, 1354506241, '0.0.0.0', 1, 0, 1),
(3, 19, '', 'admin', '', '不错，支持一下', 3, 1354506245, '0.0.0.0', 1, 0, 1),
(4, 19, '', 'admin', '', '不错，支持一下', 3, 1354506247, '0.0.0.0', 1, 0, 1),
(5, 19, '', 'admin', '', '不错，支持一下', 5, 1354506250, '0.0.0.0', 1, 0, 1),
(6, 19, '', 'admin', '', '很给力', 5, 1354506258, '0.0.0.0', 1, 0, 1),
(7, 19, '', 'admin', '', '垃圾', 1, 1354506265, '0.0.0.0', 1, 0, 1),
(8, 19, '', 'admin', '', '咋咋', 1, 1354506280, '0.0.0.0', 1, 0, 1),
(9, 19, '', 'admin', '', '不错，支持一下，经典', 5, 1354506287, '0.0.0.0', 1, 0, 1),
(10, 19, '', 'admin', '', '经典视角', 3, 1354506309, '0.0.0.0', 1, 0, 1),
(11, 19, '', 'admin', '', '你的作品已经被我们收录了', 3, 1354506321, '0.0.0.0', 1, 0, 1),
(12, 19, '', 'admin', '', '完美的作品', 3, 1354506329, '0.0.0.0', 1, 0, 1),
(13, 19, '', 'admin', '', '呵呵，可以啊', 3, 1354506340, '0.0.0.0', 1, 0, 1),
(14, 19, '', 'admin', '', '提交评论', 3, 1354527373, '0.0.0.0', 1, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopgoodsgallery`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopgoodsgallery` (
  `shopgoodsgallery_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品相册自动增长ID',
  `shopgoods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '对应商品ID',
  `shopgoodsgallery_url` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片大图URL',
  `shopgoodsgallery_description` varchar(255) NOT NULL DEFAULT '' COMMENT '商品图片描述',
  `shopgoodsgallery_name` varchar(100) NOT NULL COMMENT '相册图片名字',
  `shopgoodsgallery_thumburl` varchar(255) NOT NULL DEFAULT '' COMMENT '商品相册缩略图',
  `shopgoodsgallery_imgoriginal` varchar(255) NOT NULL DEFAULT '' COMMENT '商品相册原始',
  `create_dateline` int(10) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`shopgoodsgallery_id`),
  KEY `shopgoods_id` (`shopgoods_id`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopgoodstype`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopgoodstype` (
  `shopgoodstype_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品类型ID',
  `create_dateline` int(10) NOT NULL COMMENT '添加时间',
  `shopgoodstype_name` varchar(60) NOT NULL DEFAULT '' COMMENT '商品类型名字',
  `shopgoodstype_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '商品类型状态',
  PRIMARY KEY (`shopgoodstype_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shopgoodstype_status` (`shopgoodstype_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `needforbug_shopgoodstype`
--

INSERT INTO `needforbug_shopgoodstype` (`shopgoodstype_id`, `create_dateline`, `shopgoodstype_name`, `shopgoodstype_status`) VALUES
(1, 1351825446, '书法', 1),
(2, 1351825554, '美术', 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopoption`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopoption` (
  `shopoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `shopoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`shopoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_shopoption`
--

INSERT INTO `needforbug_shopoption` (`shopoption_name`, `shopoption_value`) VALUES
('shop_name', '青海书法院'),
('fixedtelephone', '028-56453879'),
('shop_description', '商城描述'),
('shop_address', '商城地址'),
('shop_serviceqq', '商城客服qq号'),
('shop_closestatus', '1'),
('shop_closereason', '商城关闭原因'),
('shop_notice', '公告222'),
('shop_servicephone', '13813564578'),
('shop_fixedtelephone', '028-23564578'),
('shopgoods_imgsize', '550|550'),
('shopgoods_thumbimgsize', '350|350'),
('shop_language', '系统语言'),
('shop_urlrewrite', 'url重写--------shop_urlrewrite (0,1,2)'),
('shop_timezone', '默认时区----'),
('shop_icp', 'icp备案证书号-----'),
('shop_creditname', '金币'),
('shop_creditchangerate', '100-1'),
('shop_creditpayrate', '100-10'),
('shop_startcommentcheck', '0'),
('shop_registercredit', '11'),
('shop_registeremailcheck', '0'),
('shop_formcachetime', '前台页面缓存存活时间--shop_formcachetime'),
('shop_startgzip', '启用gzip模式----------'),
('shop_startorderremind', '1'),
('shop_startmessageboard', '0'),
('shop_startmessagecheck', '0'),
('shop_originalimage', '商品默认图像----------shop_originalimage'),
('shop_originalstock', '1'),
('shop_commentrequest', '0'),
('shop_keysword', '阿文'),
('shop_currencyform', '￥%s元'),
('shop_goodsnamelenght', '商品名称长度--------------shop_goodsnamelenght'),
('shop_imagewidth', '图像缩略图宽度------------shop_imagewidth'),
('shop_imageheight', '图像缩略图高度------------shop_imageheight'),
('shop_commentnum', '5'),
('shop_articlenum', '5'),
('shop_articlethemelenght', '文章标题长度--------------shop_articlethemelenght'),
('shop_pagestyle', '0'),
('shop_goodsshowstyle', '0');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoporderaction`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoporderaction` (
  `shoporderaction_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单操作记录ID',
  `shoporder_id` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '操作订单ID',
  `shoporderaction_username` varchar(50) NOT NULL DEFAULT '' COMMENT '订单操作人',
  `user_id` int(10) NOT NULL COMMENT '操作人用户ID',
  `shoporderaction_orderstatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '作何操作0,未确认, 1已确认; 2已取消; 3无效; 4退货',
  `shoporderaction_shippingstatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '发货状态; 0未发货; 1已发货  2已取消  3备货中',
  `shoporderaction_paystatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态 0未付款;  1已付款中;  2已付款',
  `shoporderaction_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '操作备注',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`shoporderaction_id`),
  KEY `shoporder_id` (`shoporder_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopordergoods`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopordergoods` (
  `shopordergoods_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品订单ID',
  `shoporderinfo_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品订单详细信息',
  `shopgoods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `shopordergoods_goodsname` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名',
  `shopordergoods_goodssn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品唯一货号',
  `shopordergoods_goodsnumber` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '商品购买数量',
  `shopordergoods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品的市场售价',
  `shopordergoods_shopprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品的本店售价',
  `shopordergoods_goodsattr` text NOT NULL COMMENT '购买该商品时所选择的属性',
  `shopordergoods_sendnumber` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '当不是实物时，是否已发货，0，否；1，是',
  `shopordergoods_isreal` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是实物，0，否；1，是',
  `shopordergoods_extensioncode` varchar(30) NOT NULL DEFAULT '' COMMENT '商品的扩展属性',
  `shopordergoods_isgift` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '是否参加优惠活动，0，否；其他',
  `shopordergoods_goodsattrid` varchar(255) NOT NULL DEFAULT '' COMMENT '商品属性',
  PRIMARY KEY (`shopordergoods_id`),
  KEY `shoporderinfo_id` (`shoporderinfo_id`),
  KEY `shopgoods_id` (`shopgoods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoporderinfo`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoporderinfo` (
  `shoporderinfo_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单扩展信息ID',
  `shoporderinfo_sn` varchar(20) NOT NULL DEFAULT '' COMMENT '订单号',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `shoporderinfo_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单的状态;0未确认,1确认,2已取消,3无效,4退货',
  `shoporderinfo_shippingstatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '商品配送情况;0未发货,1已发货,2已收货,4退货',
  `shoporderinfo_paystatus` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态;0未付款;1付款中;2已付款',
  `shoporderinfo_consignee` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的姓名',
  `shoporderinfo_country` varchar(35) NOT NULL DEFAULT '' COMMENT '收货人的国家',
  `shoporderinfo_province` varchar(35) NOT NULL DEFAULT '' COMMENT '收货人的省份',
  `shoporderinfo_city` varchar(35) NOT NULL DEFAULT '' COMMENT '收货人的城市',
  `shoporderinfo_district` varchar(35) NOT NULL DEFAULT '' COMMENT '收货人的地区',
  `shoporderinfo_community` varchar(35) NOT NULL COMMENT '收货人乡村',
  `shoporderinfo_address` varchar(255) NOT NULL DEFAULT '' COMMENT '收货人的详细地址',
  `shoporderinfo_zipcode` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的邮编',
  `shoporderinfo_tel` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的电话,',
  `shoporderinfo_mobile` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的手机',
  `shoporderinfo_email` varchar(60) NOT NULL DEFAULT '' COMMENT '收货人的Email',
  `shoporderinfo_besttime` varchar(120) NOT NULL DEFAULT '' COMMENT '收获最佳时间',
  `shoporderinfo_signbuilding` varchar(120) NOT NULL DEFAULT '' COMMENT '送货人的地址的标志性建筑',
  `shoporderinfo_postscript` varchar(255) NOT NULL DEFAULT '' COMMENT '订单附言',
  `shopshipping_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '用户选择的配送方式id',
  `shoporderinfo_shippingname` varchar(120) NOT NULL DEFAULT '' COMMENT '用户选择的配送方式的名称',
  `shoppayment_id` smallint(6) NOT NULL DEFAULT '0' COMMENT '用户选择的支付方式的id',
  `shoporderinfo_paymentname` varchar(120) NOT NULL DEFAULT '' COMMENT '用户选择的支付方式名称',
  `shoporderinfo_howoos` varchar(120) NOT NULL DEFAULT '' COMMENT '缺货处理方式,等待所有商品备齐后再发,取消订单;与店主协商',
  `shoporderinfo_invpayee` varchar(120) NOT NULL DEFAULT '' COMMENT '发票抬头',
  `shoporderinfo_invcontent` varchar(120) NOT NULL DEFAULT '' COMMENT '发票内容',
  `shoporderinfo_goodsamount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品的总金额',
  `shoporderinfo_shippingfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '配送费用',
  `shoporderinfo_insurefee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '保价费用',
  `shoporderinfo_payfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付费用',
  `shoporderinfo_moneypaid` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '已付款金额',
  `shoporderinfo_surplus` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '该订单使用金额的数量,取用户设定余额,用户可用余额,订单金额中最小者',
  `shoporderinfo_orderamount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '应付款金额',
  `shoporderinfo_referer` varchar(255) NOT NULL DEFAULT '' COMMENT '订单的来源页面',
  `create_dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单生成时间',
  `shoporderinfo_confirmtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单确认时间',
  `shoporderinfo_paytime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单支付时间',
  `shoporderinfo_shippingtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单配送时间',
  `shoporderinfo_invoiceno` varchar(255) NOT NULL DEFAULT '' COMMENT '发票编号，发货时填写',
  `shoporderinfo_tobuyer` varchar(255) NOT NULL DEFAULT '' COMMENT '商家给客户的留言',
  `shoporderinfo_payremark` varchar(255) NOT NULL DEFAULT '' COMMENT '付款备注',
  `shoporderinfo_invtype` varchar(60) NOT NULL COMMENT '发票类型,用户页面选择',
  `shoporderinfo_tax` decimal(10,2) NOT NULL COMMENT '发票税额',
  `shoporderinfo_isseparate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未分成或等待分成;1已分成;2取消分成',
  `shoporderinfo_discount` decimal(10,2) NOT NULL COMMENT '订单号',
  PRIMARY KEY (`shoporderinfo_id`),
  UNIQUE KEY `shoporderinfo_sn` (`shoporderinfo_sn`),
  KEY `user_id` (`user_id`),
  KEY `create_dateline` (`create_dateline`),
  KEY `shoporderinfo_status` (`shoporderinfo_status`),
  KEY `shopshipping_id` (`shopshipping_id`),
  KEY `shoppay_id` (`shoppayment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoppaylog`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoppaylog` (
  `shoppaylog_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付记录增长ID',
  `shoporderinfo_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单记录ID',
  `shoppaylog_orderamount` decimal(10,2) unsigned NOT NULL COMMENT '支付金额',
  `shoppaylog_ordertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付类型,0订单支付,1会员预付款支付',
  `shoppaylog_ispaid` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已支付,0否;1是',
  PRIMARY KEY (`shoppaylog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shoppayment`
--

CREATE TABLE IF NOT EXISTS `needforbug_shoppayment` (
  `shoppayment_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '支付方式自增ID',
  `shoppayment_code` varchar(20) NOT NULL DEFAULT '' COMMENT '支付方式英文缩写',
  `shoppayment_name` varchar(120) NOT NULL DEFAULT '' COMMENT '支付方式名称',
  `shoppayment_fee` varchar(10) NOT NULL DEFAULT '0' COMMENT '支付费用',
  `shoppayment_description` text NOT NULL COMMENT '支付方式描述',
  `shoppayment_sort` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式在页面的显示顺序',
  `shoppayment_config` text NOT NULL COMMENT '支付方式的配置信息',
  `shoppayment_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '支付方式状态',
  `shoppayment_iscod` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否货到付款, 0否;1是',
  `shoppayment_isonline` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否在线支付;0否;1是',
  PRIMARY KEY (`shoppayment_id`),
  UNIQUE KEY `shoppay_code` (`shoppayment_code`),
  KEY `shoppay_status` (`shoppayment_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `needforbug_shoppayment`
--

INSERT INTO `needforbug_shoppayment` (`shoppayment_id`, `shoppayment_code`, `shoppayment_name`, `shoppayment_fee`, `shoppayment_description`, `shoppayment_sort`, `shoppayment_config`, `shoppayment_status`, `shoppayment_iscod`, `shoppayment_isonline`) VALUES
(4, 'alipay', '支付宝', '0', '支付宝网站(www.alipay.com) 是国内先进的网上支付平台。<br/>支付宝收款接口：在线即可开通，<font color="red"><b>零预付，免年费</b></font>，单笔阶梯费率，无流量限制。<br/><a href="/needforbug/upload/index.php?app=shop&c=misc&a=payment&type=alipay" target="_blank"><strong>立即在线申请</strong></a>', 0, 'a:4:{s:14:"alipay_account";s:4:"dsf5";s:10:"alipay_key";s:14:"ffffffffffffff";s:14:"alipay_partner";s:11:"fffffffffff";s:17:"alipay_pay_method";s:1:"0";}', 1, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_shopshipping`
--

CREATE TABLE IF NOT EXISTS `needforbug_shopshipping` (
  `shopshipping_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '配送方式ID',
  `shopshipping_code` varchar(20) NOT NULL DEFAULT '' COMMENT '配送方式代号',
  `shopshipping_name` varchar(120) NOT NULL DEFAULT '' COMMENT '配送方式名字',
  `shopshipping_description` varchar(255) NOT NULL DEFAULT '' COMMENT '配送方式描述',
  `shopshipping_insure` varchar(10) NOT NULL DEFAULT '0' COMMENT '保价费用，单位元，或者是百分数，该值直接输出为报价费',
  `shopshipping_iscod` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否支持货到付款，1，支持；0，不支持',
  `shopshipping_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '配送方式状态',
  `shopshipping_sort` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '配送方式排序',
  PRIMARY KEY (`shopshipping_id`),
  KEY `shopshipping_status` (`shopshipping_status`),
  KEY `shopshipping_sort` (`shopshipping_sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `needforbug_shopshipping`
--

INSERT INTO `needforbug_shopshipping` (`shopshipping_id`, `shopshipping_code`, `shopshipping_name`, `shopshipping_description`, `shopshipping_insure`, `shopshipping_iscod`, `shopshipping_status`, `shopshipping_sort`) VALUES
(9, 'yto', '圆通速递', '上海圆通物流（速递）有限公司经过多年的网络快速发展，在中国速递行业中一直处于领先地位。为了能更好的发展国际快件市场，加快与国际市场的接轨，强化圆通的整体实力，圆通已在东南亚、欧美、中东、北美洲、非洲等许多城市运作国际快件业务', '0', 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_slide`
--

CREATE TABLE IF NOT EXISTS `needforbug_slide` (
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
-- 转存表中的数据 `needforbug_slide`
--

INSERT INTO `needforbug_slide` (`slide_id`, `slide_sort`, `slide_title`, `slide_url`, `slide_img`, `slide_status`, `create_dateline`, `update_dateline`) VALUES
(1, 0, '欢迎加入', '{Dyhb::U(''home://public/register'')}', '{__PUBLIC__.''/images/common/slidebox/1.jpg''}', 1, 1345357086, 1345364471),
(2, 0, '立刻登录', '{Dyhb::U(''home://public/login'')}', '{__PUBLIC__.''/images/common/slidebox/2.jpg''}', 1, 1345357086, 0),
(3, 0, '关于我们', '{Dyhb::U(''home://homesite/aboutus'')}', '{__PUBLIC__.''/images/common/slidebox/3.jpg''}', 1, 1345357086, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_sociatype`
--

CREATE TABLE IF NOT EXISTS `needforbug_sociatype` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `needforbug_sociatype`
--

INSERT INTO `needforbug_sociatype` (`sociatype_id`, `sociatype_title`, `sociatype_identifier`, `sociatype_appid`, `sociatype_appkey`, `sociatype_callback`, `sociatype_scope`, `sociatype_status`, `create_dateline`) VALUES
(1, 'QQ互联', 'qq', '100303001', '2c8a05c6c7930f7bd0d481a8462c7db0', 'http://bbs.doyouhaobaby.net/index.php?app=home&c=public&a=socia_callback&vendor=qq', 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo', 1, 1345777926),
(2, '新浪微博', 'weibo', '', '', '', '', 1, 1347356728),
(3, '豆瓣', 'douban', '', '', '', '', 1, 1347415512),
(4, '人人', 'renren', '', '', '', '', 1, 1347415527);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_sociauser`
--

CREATE TABLE IF NOT EXISTS `needforbug_sociauser` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- 转存表中的数据 `needforbug_sociauser`
--

INSERT INTO `needforbug_sociauser` (`sociauser_id`, `sociauser_appid`, `sociauser_openid`, `user_id`, `sociauser_vendor`, `sociauser_keys`, `sociauser_name`, `sociauser_nikename`, `sociauser_desc`, `sociauser_url`, `sociauser_img`, `sociauser_img1`, `sociauser_img2`, `sociauser_gender`, `sociauser_email`, `sociauser_location`, `sociauser_vip`, `sociauser_level`, `create_dateline`) VALUES
(1, '100303001', 'A3A62D6B7CFB589E2D76D2E2EE787273', '1', 'qq', '2c8a05c6c7930f7bd0d481a8462c7db0', '小牛哥Dyhb', '小牛哥Dyhb', '', '', 'http://qzapp.qlogo.cn/qzapp/100303001/A3A62D6B7CFB589E2D76D2E2EE787273/30', 'http://qzapp.qlogo.cn/qzapp/100303001/A3A62D6B7CFB589E2D76D2E2EE787273/50', 'http://qzapp.qlogo.cn/qzapp/100303001/A3A62D6B7CFB589E2D76D2E2EE787273/100', '男', '', '', 0, 0, 1347242241);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_style`
--

CREATE TABLE IF NOT EXISTS `needforbug_style` (
  `style_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主题样式ID',
  `style_name` varchar(32) NOT NULL DEFAULT '' COMMENT '主题样式名字',
  `style_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '主题样式状态',
  `theme_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '模板ID',
  `style_extend` varchar(320) NOT NULL DEFAULT '' COMMENT '主题样式扩展',
  PRIMARY KEY (`style_id`),
  KEY `theme_id` (`theme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- 转存表中的数据 `needforbug_style`
--

INSERT INTO `needforbug_style` (`style_id`, `style_name`, `style_status`, `theme_id`, `style_extend`) VALUES
(1, '默认主题', 1, 1, 't1	t2	t3	t4	t5|t4'),
(35, '测试主题', 1, 35, '|');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_stylevar`
--

CREATE TABLE IF NOT EXISTS `needforbug_stylevar` (
  `stylevar_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '变量ID',
  `style_id` smallint(6) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `stylevar_variable` text NOT NULL COMMENT '变量名',
  `stylevar_substitute` text NOT NULL COMMENT '变量替换值',
  PRIMARY KEY (`stylevar_id`),
  KEY `style_id` (`style_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1965 ;

--
-- 转存表中的数据 `needforbug_stylevar`
--

INSERT INTO `needforbug_stylevar` (`stylevar_id`, `style_id`, `stylevar_variable`, `stylevar_substitute`) VALUES
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
(65, 1, 'float_mask_bg_extra', ''),
(1944, 35, 'header_bg_color', '#FFFFFF'),
(1943, 35, 'menu_bg_extra', 'repeat-x'),
(1942, 35, 'menu_bg_img', ''),
(1941, 35, 'menu_bg_color', '#FFFFFF'),
(1940, 35, 'noticetext_border_color', '#000081'),
(1939, 35, 'noticetext_color', '#FF3300'),
(1937, 35, 'special_bg', '#990000'),
(1938, 35, 'interleave_color', '#FFFFFF'),
(1936, 35, 'special_border', '#CC0000'),
(1935, 35, 'common_background_color', '#00FF00'),
(1934, 35, 'interval_line_color', '#f3f1f1'),
(1932, 35, 'input_bg', '#CCCCCC'),
(1933, 35, 'drop_menu_border', '#005600'),
(1931, 35, 'input_border_dark_color', '#666666'),
(1930, 35, 'input_border', '#999999'),
(1929, 35, 'menu_hover_text_color', '#FFFFFF'),
(1928, 35, 'menu_hover_bg_color', '#000000'),
(1927, 35, 'menu_text_color', '#000000'),
(1926, 35, 'menu_border_color', '#f3f1f1'),
(1925, 35, 'content_separate_color', '#0997F7'),
(1924, 35, 'content_width', '600px'),
(1923, 35, 'content_big_size', '16px'),
(1922, 35, 'content_fontsize', '14px'),
(1921, 35, 'wrap_border_color', '#1AE66B'),
(1920, 35, 'wrap_border_width', '0px'),
(1919, 35, 'wrap_table_bg', '#22DD48'),
(1918, 35, 'wrap_table_width', '960px'),
(1917, 35, 'highlightlink_color', '#995600'),
(1916, 35, 'link_color', '#995600'),
(1915, 35, 'light_textcolor', '#2B2B2B'),
(1914, 35, 'medium_textcolor', '#000000'),
(1913, 35, 'normal_color', '#818181'),
(1912, 35, 'big_fontsize', '20px'),
(1911, 35, 'big_font', 'Verdana,Lucida Grande, Lucida Sans Unicode, Lucida Sans, Helvetica, Arial, sans-serif'),
(1910, 35, 'small_fontsize', '0.83em'),
(1909, 35, 'small_font', 'Verdana,Lucida Grande, Lucida Sans Unicode, Lucida Sans, Helvetica, Arial, sans-serif'),
(1908, 35, 'normal_fontsize', '13px/18px'),
(1907, 35, 'normal_font', 'Verdana,Lucida Grande, Lucida Sans Unicode, Lucida Sans, Helvetica, Arial, sans-serif'),
(1906, 35, 'footer_text_color', '#565656'),
(1905, 35, 'header_text_color', '#565656'),
(1904, 35, 'header_border_color', '#FFFFFF'),
(1903, 35, 'header_border_width', '0px'),
(1902, 35, 'logo', 'logo.png'),
(1901, 35, 'style_img_dir', 'theme/Zhong/Public/Images'),
(1900, 35, 'img_dir', 'theme/Zhong/Public/Images'),
(1964, 35, 'float_mask_bg_extra', ''),
(1963, 35, 'float_mask_bg_img', ''),
(1962, 35, 'float_mask_bg_color', '#1ABDE6'),
(1961, 35, 'float_bg_extra', ''),
(1960, 35, 'float_bg_img', ''),
(1959, 35, 'float_bg_color', '#FFFFFF'),
(1958, 35, 'footer_bg_extra', 'repeat-x'),
(1957, 35, 'footer_bg_img', ''),
(1956, 35, 'footer_bg_color', '#FFFFFF'),
(1955, 35, 'drop_menu_bg_extra', 'repeat-x'),
(1954, 35, 'drop_menu_bg_img', ''),
(1953, 35, 'drop_menu_bg_color', '#CCCCCC'),
(1952, 35, 'bg_extra', 'repeat'),
(1951, 35, 'bg_img', '未标题-1.jpg'),
(1950, 35, 'bg_color', '#FFFFFF'),
(1949, 35, 'side_bg_extra', 'repeat-x'),
(1948, 35, 'side_bg_img', ''),
(1947, 35, 'side_bg_color', '#FFFFFF'),
(1946, 35, 'header_bg_extra', 'repeat-x'),
(1945, 35, 'header_bg_img', '');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_syscache`
--

CREATE TABLE IF NOT EXISTS `needforbug_syscache` (
  `syscache_name` varchar(32) NOT NULL COMMENT '缓存名字',
  `syscache_type` tinyint(3) unsigned NOT NULL COMMENT '缓存类型',
  `create_dateline` int(10) unsigned NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `syscache_data` mediumblob NOT NULL COMMENT '缓存数据',
  PRIMARY KEY (`syscache_name`),
  KEY `create_dateline` (`create_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `needforbug_syscache`
--

INSERT INTO `needforbug_syscache` (`syscache_name`, `syscache_type`, `create_dateline`, `update_dateline`, `syscache_data`) VALUES
('home_option', 1, 1346134242, 1354669600, 0x613a33323a7b733a31373a22686f6d6568656c705f6c6973745f6e756d223b733a323a223130223b733a31383a22686f6d6566726573685f6c6973745f6e756d223b733a323a223135223b733a32383a22686f6d6566726573685f6c6973745f737562737472696e675f6e756d223b733a333a22353030223b733a31333a22757365725f6c6973745f6e756d223b733a323a223130223b733a31353a22667269656e645f6c6973745f6e756d223b733a323a223130223b733a31393a226d795f667269656e645f6c696d69745f6e756d223b733a313a2236223b733a31313a22706d5f6c6973745f6e756d223b733a313a2235223b733a32313a22706d5f6c6973745f737562737472696e675f6e756d223b733a333a22323030223b733a31383a22706d5f73696e676c655f6c6973745f6e756d223b733a323a223130223b733a32353a22686f6d656672657368636f6d6d656e745f6c6973745f6e756d223b733a323a223130223b733a31353a22636f6d6d656e745f6d696e5f6c656e223b733a313a2235223b733a31353a22636f6d6d656e745f6d61785f6c656e223b733a333a22353030223b733a31383a22636f6d6d656e745f706f73745f7370616365223b733a313a2230223b733a32303a22636f6d6d656e745f62616e69705f656e61626c65223b733a313a2231223b733a31343a22636f6d6d656e745f62616e5f6970223b733a303a22223b733a31393a22636f6d6d656e745f7370616d5f656e61626c65223b733a313a2231223b733a31383a22636f6d6d656e745f7370616d5f776f726473223b733a393a22e585ade59088e5bda9223b733a32303a22636f6d6d656e745f7370616d5f75726c5f6e756d223b733a313a2233223b733a32353a22636f6d6d656e745f7370616d5f636f6e74656e745f73697a65223b733a333a22313030223b733a32373a22646973616c6c6f7765645f616c6c5f656e676c6973685f776f7264223b733a313a2231223b733a33323a22646973616c6c6f7765645f7370616d5f776f72645f746f5f6461746162617365223b733a313a2231223b733a32313a22636c6f73655f636f6d6d656e745f66656174757265223b733a313a2230223b733a32303a22636f6d6d656e745f7265706561745f636865636b223b733a313a2231223b733a31333a2261756469745f636f6d6d656e74223b733a313a2231223b733a32323a22736563636f64655f636f6d6d656e745f737461747573223b733a313a2230223b733a32313a22636f6d6d656e745f6d61696c5f746f5f61646d696e223b733a313a2230223b733a32323a22636f6d6d656e745f6d61696c5f746f5f617574686f72223b733a313a2230223b733a32363a22686f6d656672657368636f6d6d656e745f6c696d69745f6e756d223b733a313a2234223b733a33313a22686f6d6566726573686368696c64636f6d6d656e745f6c696d69745f6e756d223b733a313a2234223b733a33303a22686f6d6566726573686368696c64636f6d6d656e745f6c6973745f6e756d223b733a313a2234223b733a33303a22686f6d656672657368636f6d6d656e745f737562737472696e675f6e756d223b733a323a223830223b733a32383a22686f6d6566726573687469746c655f737562737472696e675f6e756d223b733a323a223330223b7d),
('slide', 1, 1346134242, 1354586535, 0x613a333a7b693a303b613a333a7b733a31313a22736c6964655f7469746c65223b733a31323a22e6aca2e8bf8ee58aa0e585a5223b733a393a22736c6964655f696d67223b733a35343a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f636f6d6d6f6e2f736c696465626f782f312e6a7067223b733a393a22736c6964655f75726c223b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f7075626c69632f7265676973746572223b7d693a313b613a333a7b733a31313a22736c6964655f7469746c65223b733a31323a22e7ab8be588bbe799bbe5bd95223b733a393a22736c6964655f696d67223b733a35343a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f636f6d6d6f6e2f736c696465626f782f322e6a7067223b733a393a22736c6964655f75726c223b733a35303a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f7075626c69632f6c6f67696e223b7d693a323b613a333a7b733a31313a22736c6964655f7469746c65223b733a31323a22e585b3e4ba8ee68891e4bbac223b733a393a22736c6964655f696d67223b733a35343a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f636f6d6d6f6e2f736c696465626f782f332e6a7067223b733a393a22736c6964655f75726c223b733a35343a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f686f6d65736974652f61626f75747573223b7d7d),
('link', 1, 1346134242, 1354586535, 0x613a333a7b733a31323a226c696e6b5f636f6e74656e74223b733a3531313a223c6c693e3c64697620636c6173733d22686f6d652d636f6e74656e74223e3c68353e3c6120687265663d22e68ea2e7b4a2e7bd91e7bb9ce6b0b4e5869b22207461726765743d225f626c616e6b223ee6b0b4e5869b3c2f613e3c2f68353e3c703ee68ea2e7b4a2e7bd91e7bb9ce6b0b4e5869b3c2f703e3c2f6469763e3c2f6c693e3c6c693e3c64697620636c6173733d22686f6d652d636f6e74656e74223e3c68353e3c6120687265663d22767073e4b8bbe69cba22207461726765743d225f626c616e6b223e767073e4b8bbe69cba3c2f613e3c2f68353e3c703e4c696e7578e4b8bbe69cbae68f90e4be9be595863c2f703e3c2f6469763e3c2f6c693e3c6c693e3c64697620636c6173733d22686f6d652d636f6e74656e74223e3c68353e3c6120687265663d22687474703a2f2f61646d696e352e636f6d22207461726765743d225f626c616e6b223e4135e6ba90e7a0813c2f613e3c2f68353e3c703ee4b88be8bdbde69c80e696b0e6ba90e7a0813c2f703e3c2f6469763e3c2f6c693e3c6c693e3c64697620636c6173733d22686f6d652d636f6e74656e74223e3c68353e3c6120687265663d225c2f63616e7068702e636f6d22207461726765743d225f626c616e6b223e43616e706870e6a186e69eb63c2f613e3c2f68353e3c703e43616e706870e7aea1e7bd913c2f703e3c2f6469763e3c2f6c693e223b733a393a226c696e6b5f74657874223b733a3136353a223c6c693e3c6120687265663d22687474703a2f2f7777772e74766572792e636f6d2f22207461726765743d225f626c616e6b22207469746c653d22e590ace68891e7bd91223ee590ace68891e7bd913c2f613e3c2f6c693e3c6c693e3c6120687265663d22687474703a2f2f62616964752e636f6d22207461726765743d225f626c616e6b22207469746c653d22e799bee5baa6223ee799bee5baa63c2f613e3c2f6c693e223b733a393a226c696e6b5f6c6f676f223b733a303a22223b7d),
('adminctrlmenu', 1, 1346135104, 1354669611, 0x613a313a7b693a303b613a333a7b733a31363a2261646d696e6374726c6d656e755f6964223b733a323a223338223b733a31393a2261646d696e6374726c6d656e755f7469746c65223b733a31323a22e59586e59f8ee9858de7bdae223b733a31373a2261646d696e6374726c6d656e755f75726c223b733a32303a22633d61707026613d636f6e6669672669643d3531223b7d7d),
('site', 1, 1346135104, 1354669611, 0x613a363a7b733a393a22686f6d656672657368223b733a333a22323233223b733a31363a22686f6d656672657368636f6d6d656e74223b733a333a22323831223b733a333a22617070223b733a313a2233223b733a343a2275736572223b733a323a223338223b733a393a2261646d696e75736572223b733a313a2231223b733a373a226e657775736572223b733a313a2230223b7d),
('creditrule', 1, 1346135663, 1354669607, 0x613a353a7b733a363a2273656e64706d223b613a31353a7b733a31333a2263726564697472756c655f6964223b733a313a2231223b733a31353a2263726564697472756c655f6e616d65223b733a31323a22e58f91e79fade6b688e681af223b733a31373a2263726564697472756c655f616374696f6e223b733a363a2273656e64706d223b733a32303a2263726564697472756c655f6379636c6574797065223b733a313a2231223b733a32303a2263726564697472756c655f6379636c6574696d65223b733a313a2230223b733a32303a2263726564697472756c655f7265776172646e756d223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697431223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697432223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697433223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697434223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697435223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697436223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697437223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697438223b733a313a2230223b733a32323a2263726564697472756c655f72756c656e616d65756e69223b733a33363a22254535253846253931254537253946254144254536254236253838254536253831254146223b7d733a31353a2270726f6d6f74696f6e5f7669736974223b613a31353a7b733a31333a2263726564697472756c655f6964223b733a313a2232223b733a31353a2263726564697472756c655f6e616d65223b733a31323a22e8aebfe997aee68ea8e5b9bf223b733a31373a2263726564697472756c655f616374696f6e223b733a31353a2270726f6d6f74696f6e5f7669736974223b733a32303a2263726564697472756c655f6379636c6574797065223b733a313a2231223b733a32303a2263726564697472756c655f6379636c6574696d65223b733a313a2230223b733a32303a2263726564697472756c655f7265776172646e756d223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697431223b733a313a2231223b733a32343a2263726564697472756c655f657874656e6463726564697432223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697433223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697434223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697435223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697436223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697437223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697438223b733a313a2230223b733a32323a2263726564697472756c655f72756c656e616d65756e69223b733a33363a22254538254145254246254539253937254145254536253845254138254535254239254246223b7d733a31383a2270726f6d6f74696f6e5f7265676973746572223b613a31353a7b733a31333a2263726564697472756c655f6964223b733a313a2233223b733a31353a2263726564697472756c655f6e616d65223b733a31323a22e6b3a8e5868ce68ea8e5b9bf223b733a31373a2263726564697472756c655f616374696f6e223b733a31383a2270726f6d6f74696f6e5f7265676973746572223b733a32303a2263726564697472756c655f6379636c6574797065223b733a313a2230223b733a32303a2263726564697472756c655f6379636c6574696d65223b733a313a2230223b733a32303a2263726564697472756c655f7265776172646e756d223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697431223b733a313a2232223b733a32343a2263726564697472756c655f657874656e6463726564697432223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697433223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697434223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697435223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697436223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697437223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697438223b733a313a2230223b733a32323a2263726564697472756c655f72756c656e616d65756e69223b733a33363a22254536254233254138254535253836253843254536253845254138254535254239254246223b7d733a393a22736574617661746172223b613a31353a7b733a31333a2263726564697472756c655f6964223b733a313a2234223b733a31353a2263726564697472756c655f6e616d65223b733a31323a22e8aebee7bdaee5a4b4e5838f223b733a31373a2263726564697472756c655f616374696f6e223b733a393a22736574617661746172223b733a32303a2263726564697472756c655f6379636c6574797065223b733a313a2230223b733a32303a2263726564697472756c655f6379636c6574696d65223b733a313a2230223b733a32303a2263726564697472756c655f7265776172646e756d223b733a313a2231223b733a32343a2263726564697472756c655f657874656e6463726564697431223b733a313a2235223b733a32343a2263726564697472756c655f657874656e6463726564697432223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697433223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697434223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697435223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697436223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697437223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697438223b733a313a2230223b733a32323a2263726564697472756c655f72756c656e616d65756e69223b733a33363a22254538254145254245254537254244254145254535254134254234254535253833253846223b7d733a383a226461796c6f67696e223b613a31353a7b733a31333a2263726564697472756c655f6964223b733a313a2235223b733a31353a2263726564697472756c655f6e616d65223b733a31323a22e6af8fe5a4a9e799bbe5bd95223b733a31373a2263726564697472756c655f616374696f6e223b733a383a226461796c6f67696e223b733a32303a2263726564697472756c655f6379636c6574797065223b733a313a2231223b733a32303a2263726564697472756c655f6379636c6574696d65223b733a313a2230223b733a32303a2263726564697472756c655f7265776172646e756d223b733a313a2231223b733a32343a2263726564697472756c655f657874656e6463726564697431223b733a313a2232223b733a32343a2263726564697472756c655f657874656e6463726564697432223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697433223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697434223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697435223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697436223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697437223b733a313a2230223b733a32343a2263726564697472756c655f657874656e6463726564697438223b733a313a2230223b733a32323a2263726564697472756c655f72756c656e616d65756e69223b733a33363a22254536254146253846254535254134254139254537253939254242254535254244253935223b7d7d);
INSERT INTO `needforbug_syscache` (`syscache_name`, `syscache_type`, `create_dateline`, `update_dateline`, `syscache_data`) VALUES
('userprofilesetting', 1, 1346139177, 1354586547, 0x613a36323a7b733a32303a227573657270726f66696c655f7265616c6e616d65223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f7265616c6e616d65223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e79c9fe5ae9ee5a793e5908d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2231223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f67656e646572223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f67656e646572223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e680a7e588ab223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2231223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f626972746879656172223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f626972746879656172223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e587bae7949fe5b9b4e4bbbd223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2231223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f62697274686d6f6e7468223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f62697274686d6f6e7468223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e587bae7949fe69c88e4bbbd223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32303a227573657270726f66696c655f6269727468646179223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f6269727468646179223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e7949fe697a5223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32353a227573657270726f66696c655f636f6e7374656c6c6174696f6e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32353a227573657270726f66696c655f636f6e7374656c6c6174696f6e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e6989fe5baa7223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a33323a22e6989fe5baa728e6a0b9e68daee7949fe697a5e887aae58aa8e8aea1e7ae9729223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f7a6f64696163223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f7a6f64696163223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e7949fe88296223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a33323a22e7949fe8829628e6a0b9e68daee7949fe697a5e887aae58aa8e8aea1e7ae9729223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f74656c6570686f6e65223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f74656c6570686f6e65223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e59bbae5ae9ae794b5e8af9d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f6d6f62696c65223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f6d6f62696c65223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e6898be69cba223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f69646361726474797065223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f69646361726474797065223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e8af81e4bbb6e7b1bbe59e8b223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a32393a22e8baabe4bbbde8af8120e68aa4e785a720e9a9bee9a9b6e8af81e7ad89223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f696463617264223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f696463617264223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e8af81e4bbb6e58fb7223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f61646472657373223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f61646472657373223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e982aee5af84e59cb0e59d80223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f7a6970636f6465223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f7a6970636f6465223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e982aee7bc96223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32333a227573657270726f66696c655f6e6174696f6e616c697479223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32333a227573657270726f66696c655f6e6174696f6e616c697479223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e59bbde7b18d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32353a227573657270726f66696c655f626972746870726f76696e6365223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32353a227573657270726f66696c655f626972746870726f76696e6365223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e587bae7949fe79c81e4bbbd223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f626972746863697479223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f626972746863697479223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e587bae7949fe59cb0223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f626972746864697374223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f626972746864697374223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e587bae7949fe58ebf223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a31393a22e587bae7949fe8a18ce694bfe58cba2fe58ebf223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32363a227573657270726f66696c655f6269727468636f6d6d756e697479223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32363a227573657270726f66696c655f6269727468636f6d6d756e697479223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e587bae7949fe5b08fe58cba223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32363a227573657270726f66696c655f72657369646570726f76696e6365223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32363a227573657270726f66696c655f72657369646570726f76696e6365223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e5b185e4bd8fe79c81e4bbbd223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f72657369646563697479223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f72657369646563697479223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e5b185e4bd8fe59cb0223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f72657369646564697374223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f72657369646564697374223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e5b185e4bd8fe58ebf223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a31393a22e5b185e4bd8fe8a18ce694bfe58cba2fe58ebf223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32373a227573657270726f66696c655f726573696465636f6d6d756e697479223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32373a227573657270726f66696c655f726573696465636f6d6d756e697479223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e5b185e4bd8fe5b08fe58cba223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32333a227573657270726f66696c655f7265736964657375697465223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32333a227573657270726f66696c655f7265736964657375697465223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e688bfe997b4223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a32373a22e5b08fe58cbae38081e58699e5ad97e6a5bce997a8e7898ce58fb7223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32363a227573657270726f66696c655f67726164756174657363686f6f6c223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32363a227573657270726f66696c655f67726164756174657363686f6f6c223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e6af95e4b89ae5ada6e6a0a1223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f656475636174696f6e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f656475636174696f6e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e5ada6e58e86223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f636f6d70616e79223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f636f6d70616e79223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e585ace58fb8223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f6f636375706174696f6e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f6f636375706174696f6e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e8818ce4b89a223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32303a227573657270726f66696c655f706f736974696f6e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f706f736974696f6e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e8818ce4bd8d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f726576656e7565223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f726576656e7565223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e5b9b4e694b6e585a5223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a31303a22e58d95e4bd8d20e58583223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32373a227573657270726f66696c655f616666656374697665737461747573223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32373a227573657270726f66696c655f616666656374697665737461747573223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e68385e6849fe78ab6e68081223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f6c6f6f6b696e67666f72223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f6c6f6f6b696e67666f72223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e4baa4e58f8be79baee79a84223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a33393a22e5b88ce69c9be59ca8e7bd91e7ab99e689bee588b0e4bb80e4b988e6a0b7e79a84e69c8be58f8b223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f626c6f6f6474797065223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f626c6f6f6474797065223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e8a180e59e8b223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f686569676874223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f686569676874223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e8baabe9ab98223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a393a22e58d95e4bd8d20636d223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f776569676874223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f776569676874223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e4bd93e9878d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a393a22e58d95e4bd8d206b67223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f616c69706179223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f616c69706179223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e694afe4bb98e5ae9d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31353a227573657270726f66696c655f696371223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31353a227573657270726f66696c655f696371223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a333a22494351223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31343a227573657270726f66696c655f7171223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31343a227573657270726f66696c655f7171223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a323a225151223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31373a227573657270726f66696c655f7961686f6f223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31373a227573657270726f66696c655f7961686f6f223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31313a225941484f4fe5b890e58fb7223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31353a227573657270726f66696c655f6d736e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31353a227573657270726f66696c655f6d736e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a333a224d534e223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f74616f62616f223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f74616f62616f223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e998bfe9878ce697bae697ba223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31363a227573657270726f66696c655f73697465223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31363a227573657270726f66696c655f73697465223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e4b8aae4babae4b8bbe9a1b5223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31353a227573657270726f66696c655f62696f223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31353a227573657270726f66696c655f62696f223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e887aae68891e4bb8be7bb8d223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32303a227573657270726f66696c655f696e746572657374223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f696e746572657374223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e585b4e8b6a3e788b1e5a5bd223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f676f6f676c65223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f676f6f676c65223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22476f6f676c65223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31373a227573657270726f66696c655f6261696475223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31373a227573657270726f66696c655f6261696475223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e799bee5baa6223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f72656e72656e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f72656e72656e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e4babae4baba223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f646f7562616e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f646f7562616e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e8b186e793a3223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32303a227573657270726f66696c655f66616365626f6f6b223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f66616365626f6f6b223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a383a2246616365626f6f6b223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f74777269746572223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f74777269746572223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a373a2254577269746572223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f6469616e6e6975223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f6469616e6e6975223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e782b9e7899b223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31373a227573657270726f66696c655f736b797065223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31373a227573657270726f66696c655f736b797065223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a353a22536b797065223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32303a227573657270726f66696c655f776569626f636f6d223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f776569626f636f6d223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e696b0e6b5aae5beaee58d9a223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f747171636f6d223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f747171636f6d223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e885bee8aeafe5beaee58d9a223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32303a227573657270726f66696c655f6469616e6469616e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32303a227573657270726f66696c655f6469616e6469616e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e782b9e782b9e7bd91223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32343a227573657270726f66696c655f6b696e64657267617274656e223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32343a227573657270726f66696c655f6b696e64657267617274656e223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a393a22e5b9bce584bfe59bad223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31393a227573657270726f66696c655f7072696d617279223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31393a227573657270726f66696c655f7072696d617279223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e5b08fe5ada6223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32383a227573657270726f66696c655f6a756e696f72686967687363686f6f6c223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32383a227573657270726f66696c655f6a756e696f72686967687363686f6f6c223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e5889de4b8ad223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f686967687363686f6f6c223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f686967687363686f6f6c223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e9ab98e4b8ad223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32323a227573657270726f66696c655f756e6976657273697479223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32323a227573657270726f66696c655f756e6976657273697479223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e5a4a7e5ada6223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31383a227573657270726f66696c655f6d6173746572223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31383a227573657270726f66696c655f6d6173746572223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e7a195e5a3ab223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a31343a227573657270726f66696c655f6472223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a31343a227573657270726f66696c655f6472223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a363a22e58d9ae5a3ab223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d733a32313a227573657270726f66696c655f6e6f777363686f6f6c223b613a383a7b733a32313a227573657270726f66696c6573657474696e675f6964223b733a32313a227573657270726f66696c655f6e6f777363686f6f6c223b733a32353a227573657270726f66696c6573657474696e675f737461747573223b733a313a2231223b733a32343a227573657270726f66696c6573657474696e675f7469746c65223b733a31323a22e5bd93e5898de5ada6e6a0a1223b733a33303a227573657270726f66696c6573657474696e675f6465736372697074696f6e223b733a303a22223b733a32333a227573657270726f66696c6573657474696e675f736f7274223b733a313a2230223b733a32373a227573657270726f66696c6573657474696e675f73686f77696e666f223b733a313a2230223b733a33303a227573657270726f66696c6573657474696e675f616c6c6f77736561726368223b733a313a2230223b733a32363a227573657270726f66696c6573657474696e675f70726976616379223b733a313a2230223b7d7d);
INSERT INTO `needforbug_syscache` (`syscache_name`, `syscache_type`, `create_dateline`, `update_dateline`, `syscache_data`) VALUES
('nav', 1, 1346134242, 1354669560, 0x613a333a7b733a343a226d61696e223b613a333a7b693a303b613a373a7b733a353a227469746c65223b733a363a22e5b08fe7bb84223b733a31313a226465736372697074696f6e223b733a353a2267726f7570223b733a343a226c696e6b223b733a35313a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f67726f75702f7075626c69632f696e646578223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a353a2267726f7570223b733a333a22737562223b613a303a7b7d7d693a313b613a373a7b733a353a227469746c65223b733a363a22e59586e59f8e223b733a31313a226465736372697074696f6e223b733a343a2273686f70223b733a343a226c696e6b223b733a34313a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f7075626c69632f696e646578223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a2273686f70223b733a333a22737562223b613a303a7b7d7d693a323b613a373a7b733a353a227469746c65223b733a31323a22e4b8aae4babae7a9bae997b4223b733a31313a226465736372697074696f6e223b733a343a22686f6d65223b733a343a226c696e6b223b733a35303a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f7075626c69632f696e646578223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a22686f6d65223b733a333a22737562223b613a303a7b7d7d7d733a363a22686561646572223b613a323a7b693a303b613a373a7b733a353a227469746c65223b733a31323a22e8aebee4b8bae9a696e9a1b5223b733a31313a226465736372697074696f6e223b733a31323a22e8aebee4b8bae9a696e9a1b5223b733a343a226c696e6b223b733a31383a226a6176617363726970743a766f6964283029223b733a353a227374796c65223b733a36313a22206f6e636c69636b3d22736574486f6d65706167652827687474703a2f2f6c6f63616c686f73742f6e656564666f726275672f75706c6f616427293b22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a303a22223b733a333a22737562223b613a303a7b7d7d693a313b613a373a7b733a353a227469746c65223b733a31323a22e58aa0e585a5e694b6e8978f223b733a31313a226465736372697074696f6e223b733a31323a22e58aa0e585a5e694b6e8978f223b733a343a226c696e6b223b733a33343a22687474703a2f2f6c6f63616c686f73742f6e656564666f726275672f75706c6f6164223b733a353a227374796c65223b733a36303a22206f6e636c69636b3d226164644661766f7269746528746869732e687265662c274e656564466f7242756727293b72657475726e2066616c73653b22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a303a22223b733a333a22737562223b613a303a7b7d7d7d733a363a22666f6f746572223b613a353a7b693a303b613a373a7b733a353a227469746c65223b733a31323a22e585b3e4ba8ee68891e4bbac223b733a31313a226465736372697074696f6e223b733a31323a22e585b3e4ba8ee68891e4bbac223b733a343a226c696e6b223b733a35343a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f686f6d65736974652f61626f75747573223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a22686f6d65223b733a333a22737562223b613a303a7b7d7d693a313b613a373a7b733a353a227469746c65223b733a31323a22e88194e7b3bbe68891e4bbac223b733a31313a226465736372697074696f6e223b733a31323a22e88194e7b3bbe68891e4bbac223b733a343a226c696e6b223b733a35363a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f686f6d65736974652f636f6e746163747573223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a22686f6d65223b733a333a22737562223b613a303a7b7d7d693a323b613a373a7b733a353a227469746c65223b733a31323a22e794a8e688b7e58d8fe8aeae223b733a31313a226465736372697074696f6e223b733a31323a22e794a8e688b7e58d8fe8aeae223b733a343a226c696e6b223b733a35363a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f686f6d65736974652f61677265656d656e74223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a22686f6d65223b733a333a22737562223b613a303a7b7d7d693a333b613a373a7b733a353a227469746c65223b733a31323a22e99a90e7a781e5a3b0e6988e223b733a31313a226465736372697074696f6e223b733a31323a22e99a90e7a781e5a3b0e6988e223b733a343a226c696e6b223b733a35343a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f686f6d65736974652f70726976616379223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a22686f6d65223b733a333a22737562223b613a303a7b7d7d693a343b613a373a7b733a353a227469746c65223b733a363a22e5b8aee58aa9223b733a31313a226465736372697074696f6e223b733a363a22e5b8aee58aa9223b733a343a226c696e6b223b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f6170702f686f6d652f686f6d6568656c702f696e646578223b733a353a227374796c65223b733a303a22223b733a363a22746172676574223b733a303a22223b733a333a22617070223b733a343a22686f6d65223b733a333a22737562223b613a303a7b7d7d7d7d),
('option', 1, 1346134242, 1354669560, 0x613a3135343a7b733a31343a2261646d696e5f6c6973745f6e756d223b733a323a223135223b733a393a22736974655f6e616d65223b733a31303a224e656564466f72427567223b733a31363a22736974655f6465736372697074696f6e223b733a34393a22456e6a6f79204f6e6c696e652053686f7070696e672ee78bace5b19ee4ba8ee68891e79a84e7a4bee58cbae8b4ade789a9223b733a383a22736974655f75726c223b733a33343a22687474703a2f2f6c6f63616c686f73742f6e656564666f726275672f75706c6f6164223b733a31303a22636c6f73655f73697465223b733a313a2230223b733a31373a22636c6f73655f736974655f726561736f6e223b733a393a227570646174652e2e2e223b733a31303a2273746172745f677a6970223b733a313a2230223b733a31303a2274696d656f6666736574223b733a31333a22417369612f5368616e67686169223b733a31383a2275706c6f616466696c655f6d617873697a65223b733a323a222d31223b733a31373a2275706c6f61645f73746f72655f74797065223b733a333a22646179223b733a32343a22646973616c6c6f7765645f72656769737465725f75736572223b733a303a22223b733a32353a22646973616c6c6f7765645f72656769737465725f656d61696c223b733a303a22223b733a32323a22616c6c6f7765645f72656769737465725f656d61696c223b733a303a22223b733a31343a2261756469745f7265676973746572223b733a313a2230223b733a31393a22646973616c6c6f7765645f7265676973746572223b733a313a2230223b733a333a22696370223b733a31383a22e89c80494350e5a487313233343536e58fb7223b733a31363a22686f6d655f6465736372697074696f6e223b733a3334333a227b736974655f6e616d657de698afe4b880e4b8aae4bba5e794b5e5ad90e59586e58aa1e5928ce7a4bee58cbae4b8bae6a0b8e5bf83e79a84e7a4bee58cbae58c96e794b5e5ad90e59586e58aa1efbc88423243efbc89e5b9b3e58fb0e38082e59ca8e8bf99e9878ce4bda0e58fafe4bba5e59091e7bd91e58f8be58886e4baabe4bda0e79a84e8b4ade789a9e4bd93e9aa8ce38081e4bda0e5969ce6aca2e79a84e59586e59381e4bba5e58f8ae5bf83e68385e7ad89e7ad89efbc8ce5908ce697b6e4b99fe58fafe4bba5e4bb8ee7a4bee58cbae88eb7e58f96e69da5e887aae585b6e5ae83e7bd91e58f8be79a84e8b685e580bce79a84e4bfa1e681afefbc8ce68891e4bbace5b487e5b09ae79a84e698afe79086e5bfb5efbc9a3c7370616e20636c6173733d226c6162656c206c6162656c2d73756363657373223ee7ae80e58d95e4b88ee58886e4baab3c2f7370616e3ee38082223b733a31313a2261646d696e5f656d61696c223b733a32313a227869616f6e69756765406469616e6e69752e6e6574223b733a32343a22736563636f64655f696d6167655f77696474685f73697a65223b733a333a22313630223b733a32353a22736563636f64655f696d6167655f6865696768745f73697a65223b733a323a223630223b733a31383a22736563636f64655f6164756c746572617465223b733a313a2230223b733a31313a22736563636f64655f747466223b733a313a2231223b733a31323a22736563636f64655f74696c74223b733a313a2230223b733a31333a22736563636f64655f636f6c6f72223b733a313a2231223b733a31323a22736563636f64655f73697a65223b733a313a2230223b733a31343a22736563636f64655f736861646f77223b733a313a2231223b733a31363a22736563636f64655f616e696d61746f72223b733a313a2230223b733a31383a22736563636f64655f6261636b67726f756e64223b733a313a2231223b733a32343a22736563636f64655f696d6167655f6261636b67726f756e64223b733a313a2231223b733a31343a22736563636f64655f6e6f72697365223b733a313a2230223b733a31333a22736563636f64655f6375727665223b733a313a2231223b733a31323a22736563636f64655f74797065223b733a313a2231223b733a32333a226e656564666f726275675f70726f6772616d5f6e616d65223b733a31303a224e656564466f72427567223b733a32363a226e656564666f726275675f70726f6772616d5f76657273696f6e223b733a333a22312e30223b733a32333a226e656564666f726275675f636f6d70616e795f6e616d65223b733a31323a224469616e6e697520496e632e223b733a32323a226e656564666f726275675f636f6d70616e795f75726c223b733a31383a22687474703a2f2f6469616e6e69752e6e6574223b733a32323a226e656564666f726275675f70726f6772616d5f75726c223b733a32393a22687474703a2f2f6e656564666f726275672e6469616e6e69752e6e6574223b733a32333a226e656564666f726275675f70726f6772616d5f79656172223b733a343a2232303132223b733a32333a226e656564666f726275675f636f6d70616e795f79656172223b733a393a22323031302d32303132223b733a393a22736974655f79656172223b733a343a2232303132223b733a393a22737461745f636f6465223b733a303a22223b733a31303a22626164776f72645f6f6e223b733a313a2230223b733a31343a22706d73656e645f72656764617973223b733a313a2235223b733a31343a22706d6c696d69745f6f6e65646179223b733a333a22313030223b733a31323a22706d666c6f6f645f6374726c223b733a323a223130223b733a393a22706d5f737461747573223b733a313a2231223b733a31343a22706d73656e645f736563636f6465223b733a313a2230223b733a31313a22706d5f736f756e645f6f6e223b733a313a2231223b733a31333a22706d5f736f756e645f74797065223b733a313a2231223b733a31363a22706d5f736f756e645f6f75745f75726c223b733a303a22223b733a32353a226176617461725f75706c6f616466696c655f6d617873697a65223b733a363a22353132303030223b733a31333a22657874656e645f637265646974223b733a313436343a22613a383a7b693a313b613a383a7b733a393a22617661696c61626c65223b693a313b733a353a227469746c65223b733a363a22e7bb8fe9aa8c223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b733a353a22726174696f223b693a303b7d693a323b613a383a7b733a393a22617661696c61626c65223b693a313b733a353a227469746c65223b733a363a22e98791e5b881223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b733a353a22726174696f223b693a303b7d693a333b613a383a7b733a393a22617661696c61626c65223b693a313b733a353a227469746c65223b733a363a22e8b4a1e78cae223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b733a353a22726174696f223b693a303b7d693a343b613a383a7b733a353a227469746c65223b733a303a22223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a353a22726174696f223b693a303b733a393a22617661696c61626c65223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b7d693a353b613a383a7b733a353a227469746c65223b733a303a22223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a353a22726174696f223b693a303b733a393a22617661696c61626c65223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b7d693a363b613a383a7b733a353a227469746c65223b733a303a22223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a353a22726174696f223b693a303b733a393a22617661696c61626c65223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b7d693a373b613a383a7b733a353a227469746c65223b733a303a22223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a353a22726174696f223b693a303b733a393a22617661696c61626c65223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b7d693a383b613a383a7b733a353a227469746c65223b733a303a22223b733a343a22756e6974223b693a303b733a31313a22696e697463726564697473223b693a303b733a31303a226c6f7765726c696d6974223b693a303b733a353a22726174696f223b693a303b733a393a22617661696c61626c65223b693a303b733a31353a22616c6c6f7765786368616e6765696e223b693a303b733a31363a22616c6c6f7765786368616e67656f7574223b693a303b7d7d223b733a31313a226372656469745f73746178223b733a333a22302e32223b733a31393a2265786368616e67655f6d696e63726564697473223b733a333a22313030223b733a31393a227472616e736665726d696e5f63726564697473223b733a343a2231303030223b733a31313a2274696d655f666f726d6174223b733a353a22592d6d2d64223b733a31323a22646174655f636f6e76657274223b733a313a2231223b733a393a22736974655f6c6f676f223b733a303a22223b733a32333a22736563636f64655f72656769737465725f737461747573223b733a313a2230223b733a32303a22736563636f64655f6c6f67696e5f737461747573223b733a313a2230223b733a32393a22736563636f64655f6368616e676570617373776f72645f737461747573223b733a313a2230223b733a33323a22736563636f64655f6368616e6765696e666f726d6174696f6e5f737461747573223b733a313a2230223b733a32323a22736563636f64655f7075626c6973685f737461747573223b733a313a2230223b733a31303a22666c6f6f645f6374726c223b733a323a223135223b733a31303a226e6565645f656d61696c223b733a313a2230223b733a31313a226e6565645f617661746172223b733a313a2230223b733a31343a226e6565645f667269656e646e756d223b733a303a22223b733a31333a2272656d656d6265725f74696d65223b733a363a22363034383030223b733a31343a2266726f6e745f7374796c655f6964223b733a323a223335223b733a31363a2261646d696e5f7468656d655f6e616d65223b733a373a2244656661756c74223b733a32303a2261646d696e5f7468656d655f6c6973745f6e756d223b733a313a2236223b733a32363a226e656564666f726275675f70726f6772616d5f636f6d70616e79223b733a31343a22e782b9e7899b28e68890e983bd29223b733a31353a22696d6167655f6d61785f7769647468223b733a333a22383030223b733a31343a22736c6964655f6475726174696f6e223b733a333a22302e33223b733a31313a22736c6964655f64656c6179223b733a313a2235223b733a31323a226d61696c5f64656661756c74223b733a31363a223633353735303535364071712e636f6d223b733a31333a226d61696c5f73656e6474797065223b733a313a2232223b733a31313a226d61696c5f736572766572223b733a31313a22736d74702e71712e636f6d223b733a393a226d61696c5f706f7274223b733a323a223235223b733a393a226d61696c5f61757468223b733a313a2231223b733a393a226d61696c5f66726f6d223b733a31363a223633353735303535364071712e636f6d223b733a31383a226d61696c5f617574685f757365726e616d65223b733a393a22363335373530353536223b733a31383a226d61696c5f617574685f70617373776f7264223b733a31323a226d6963726f6c6f6731393930223b733a31343a226d61696c5f64656c696d69746572223b733a313a2231223b733a31373a2270726f6772616d657570646174655f6f6e223b733a313a2230223b733a32333a226d61696c5f746573746d6573736167655f6261636b7570223b733a3230313a22e8bf99e698afe7b3bbe7bb9fe58f91e587bae79a84e4b880e5b081e794a8e4ba8ee6b58be8af95e982aee4bbb6e698afe590a6e8aebee7bdaee68890e58a9fe79a84e6b58be8af95e982aee4bbb6e380820d0a7b74696d657d0d0a0d0a2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d0d0ae6b688e681afe69da5e6ba90efbc9a7b736974655f6e616d657d0d0ae7ab99e782b9e7bd91e59d80efbc9a7b736974655f75726c7d223b733a32333a226d61696c5f746573747375626a6563745f6261636b7570223b733a36343a22e5b08ae695ace79a847b757365725f6e616d657defbc9a7b736974655f6e616d657de7b3bbe7bb9fe6b58be8af95e982aee4bbb6e58f91e98081e68890e58a9f223b733a31363a226d61696c5f746573746d657373616765223b733a3336383a22e8bf99e698afe7b3bbe7bb9fe58f91e587bae79a84e4b880e5b081e794a8e4ba8ee6b58be8af95e982aee4bbb6e698afe590a6e8aebee7bdaee68890e58a9fe79a84e6b58be8af95e982aee4bbb6e380823c6272202f3e0a3c6272202f3e0a323031322d31302d31382030333a32353c6272202f3e0a3c6272202f3e0a3c6272202f3e0a3c6272202f3e0a2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d2d3c6272202f3e0a3c6272202f3e0ae6b688e681afe69da5e6ba90efbc9a4e656564466f724275673c6272202f3e0a3c6272202f3e0a3c703e0a093c6272202f3e0ae7ab99e782b9e7bd91e59d80efbc9a687474703a2f2f6c6f63616c686f73742f6e656564666f726275672f75706c6f61640a3c2f703e0a3c6272202f3e0a3c703e0a093c6272202f3e0a3c2f703e0a3c6272202f3e0a3c703e0a093c6272202f3e0a61647361646173640a3c2f703e223b733a31363a226d61696c5f746573747375626a656374223b733a36303a22e5b08ae695ace79a8461646d696eefbc9a4e656564466f72427567e7b3bbe7bb9fe6b58be8af95e982aee4bbb6e58f91e98081e68890e58a9f323232223b733a31393a2267657470617373776f72645f65787069726564223b733a353a223336303030223b733a31353a227374796c655f7377697463685f6f6e223b733a313a2231223b733a32313a22657874656e647374796c655f7377697463685f6f6e223b733a313a2231223b733a31343a2261707065616c5f65787069726564223b733a363a22333630303030223b733a31383a226c616e67756167655f7377697463685f6f6e223b733a313a2231223b733a31393a2261646d696e5f6c616e67756167655f6e616d65223b733a353a227a682d636e223b733a31393a2266726f6e745f6c616e67756167655f6e616d65223b733a353a227a682d636e223b733a31363a2275706c6f61645f66696c655f6d6f6465223b733a313a2231223b733a31393a2275706c6f61645f616c6c6f7765645f74797065223b733a37333a226d70337c6a7065677c6a70677c6769667c626d707c706e677c726d76627c776d617c6173667c7377667c666c767c7a69707c7261727c6a61727c7478747c6d70347c776d767c776d61223b733a31363a2275706c6f61645f696e7075745f6e756d223b733a323a223130223b733a31393a2275706c6f61645f6372656174655f7468756d62223b733a313a2230223b733a31333a2275706c6f61645f69736175746f223b733a313a2231223b733a31383a2275706c6f61645f666c6173685f6c696d6974223b733a333a22313030223b733a31373a2275706c6f61645f7468756d625f73697a65223b733a373a223530307c353030223b733a31393a2275706c6f61645f69735f77617465726d61726b223b733a313a2230223b733a32333a2275706c6f61645f696d616765735f776174657274797065223b733a333a22696d67223b733a32373a2275706c6f61645f696d6167657377617465725f706f736974696f6e223b733a313a2233223b733a32353a2275706c6f61645f696d6167657377617465725f6f6666736574223b733a313a2230223b733a32333a2275706c6f61645f77617465726d61726b5f696d6775726c223b733a303a22223b733a32333a2275706c6f61645f696d6167657377617465725f74657874223b733a303a22223b733a32383a2275706c6f61645f696d6167657377617465725f74657874636f6c6f72223b733a373a2223303030303030223b733a33313a2275706c6f61645f696d6167657377617465725f74657874666f6e7473697a65223b733a323a223330223b733a33313a2275706c6f61645f696d6167657377617465725f74657874666f6e7470617468223b733a31343a226672616d65776f726b2d666f6e74223b733a33313a2275706c6f61645f696d6167657377617465725f74657874666f6e7474797065223b733a32313a224665747465537465696e736368726966742e747466223b733a32313a2275706c6f61645f6c6f67696e757365725f76696577223b733a313a2231223b733a32343a2275706c6f61645f6174746163685f657870697265686f7572223b733a313a2230223b733a31383a2275706c6f61645f6c696d69745f6c65656368223b733a313a2231223b733a32373a2275706c6f61645f6e6f746c696d69745f6c65656368646f6d61696c223b733a303a22223b733a32363a2275706c6f61645f646972656374746f5f7265616c6c7970617468223b733a313a2230223b733a31343a2275706c6f645f6973696e6c696e65223b733a313a2231223b733a32343a2275706c6f61645f6973686964655f7265616c6c7970617468223b733a313a2230223b733a32333a227562625f636f6e74656e745f6175746f6164646c696e6b223b733a313a2231223b733a32303a227562625f636f6e74656e745f73686f727475726c223b733a313a2231223b733a32313a227562625f636f6e74656e745f75726c6d61786c656e223b733a323a223530223b733a31313a226267657874656e645f6f6e223b733a313a2231223b733a31333a226267657874656e645f74696d65223b733a323a223630223b733a31353a226267657874656e645f726570656174223b733a363a22726570656174223b733a33313a226174746163686d656e745f7265636f6d6d656e6463617465676f72796e756d223b733a323a223130223b733a32333a226174746163686d656e745f7265636f6d6d656e646e756d223b733a313a2235223b733a32363a226174746163686d656e745f6d796174746163686d656e746e756d223b733a323a223132223b733a33323a226174746163686d656e745f6469616c6f676d796174746163686d656e746e756d223b733a323a223132223b733a32343a226174746163686d656e745f6d7963617465676f72796e756d223b733a313a2239223b733a33303a226174746163686d656e745f6469616c6f676d7963617465676f72796e756d223b733a313a2239223b733a32343a226174746163686d656e745f6174746163686d656e746e756d223b733a323a223132223b733a33303a226174746163686d656e745f6469616c6f676174746163686d656e746e756d223b733a323a223132223b733a32323a226174746163686d656e745f63617465676f72796e756d223b733a313a2239223b733a32383a226174746163686d656e745f6469616c6f6763617465676f72796e756d223b733a313a2239223b733a32313a226174746163686d656e745f73686f77696d676e756d223b733a313a2232223b733a31353a22726174696e675f69636f6e74797065223b733a323a227171223b733a32383a2275706c6f61645f696d675f6973686964655f7265616c6c7970617468223b733a313a2230223b733a31303a22686f6d655f7469746c65223b733a393a22e696b0e7a4bee58cba223b733a31373a22686f6d655f6e6577746f7069635f6e756d223b733a313a2236223b733a31373a22686f6d655f686f74746f7069635f6e756d223b733a323a223130223b733a32333a22686f6d655f7265636f6d6d656e6467726f75705f6e756d223b733a313a2236223b733a32313a22686f6d655f6e6577686f6d6566726573685f6e756d223b733a323a223130223b733a31363a22686f6d655f6e6577757365725f6e756d223b733a323a223132223b733a31383a22686f6d655f686f74746f7069635f64617465223b733a363a22363034383030223b733a31363a22686f6d655f6e657768656c705f6e756d223b733a313a2238223b733a31363a22686f6d655f666f6c6c6f7775735f7171223b733a393a22363335373530353536223b733a31393a22686f6d655f666f6c6c6f7775735f776569626f223b733a32313a22687474703a2f2f776569626f2e636f6d2f74657374223b733a32303a22686f6d655f666f6c6c6f7775735f747171636f6d223b733a31383a22687474703a2f2f742e71712e636f6d2f7878223b733a32303a22686f6d655f666f6c6c6f7775735f72656e72656e223b733a32363a22687474703a2f2f706167652e72656e72656e2e636f6d2f787878223b733a31383a2266726f6e745f6469616c6f675f7374796c65223b733a363a2273696d706c65223b733a31383a2261646d696e5f6469616c6f675f7374796c65223b733a373a2264656661756c74223b733a31313a2264656661756c745f617070223b733a343a2273686f70223b7d),
('lang', 1, 1346228696, 1354669560, 0x613a323a7b693a303b733a353a227a682d636e223b693a313b733a353a227a682d7477223b7d);
INSERT INTO `needforbug_syscache` (`syscache_name`, `syscache_type`, `create_dateline`, `update_dateline`, `syscache_data`) VALUES
('rating', 1, 1346581623, 1354586547, 0x613a3130303a7b693a313b613a31313a7b733a393a22726174696e675f6964223b733a313a2231223b733a31313a22726174696e675f6e616d65223b733a373a22e58897e585b531223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b733a303a22223b733a31353a226372656174655f646174656c696e65223b733a31303a2231323935353330353834223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333433393735333135223b733a31383a22726174696e675f6372656469747374617274223b733a313a2230223b733a31363a22726174696e675f637265646974656e64223b733a333a22343536223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e58897e585b531223b7d693a323b613a31313a7b733a393a22726174696e675f6964223b733a313a2232223b733a31313a22726174696e675f6e616d65223b733a373a22e58897e585b532223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231323935353330353938223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a333a22343537223b733a31363a22726174696e675f637265646974656e64223b733a333a22393132223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e58897e585b532223b7d693a333b613a31313a7b733a393a22726174696e675f6964223b733a313a2233223b733a31313a22726174696e675f6e616d65223b733a393a22e4b889e7ad89e585b5223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033353136223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a333a22393133223b733a31363a22726174696e675f637265646974656e64223b733a343a2231383234223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a393a22e4b889e7ad89e585b5223b7d693a343b613a31313a7b733a393a22726174696e675f6964223b733a313a2234223b733a31313a22726174696e675f6e616d65223b733a393a22e4ba8ce7ad89e585b5223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033353330223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a343a2231383235223b733a31363a22726174696e675f637265646974656e64223b733a343a2233313932223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a393a22e4ba8ce7ad89e585b5223b7d693a353b613a31313a7b733a393a22726174696e675f6964223b733a313a2235223b733a31313a22726174696e675f6e616d65223b733a393a22e4b880e7ad89e585b5223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033353630223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a343a2233313933223b733a31363a22726174696e675f637265646974656e64223b733a343a2235303136223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a393a22e4b880e7ad89e585b5223b7d693a363b613a31313a7b733a393a22726174696e675f6964223b733a313a2236223b733a31313a22726174696e675f6e616d65223b733a31303a22e4b88ae7ad89e585b531223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b733a303a22223b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033353831223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333433353835353537223b733a31383a22726174696e675f6372656469747374617274223b733a343a2235303137223b733a31363a22726174696e675f637265646974656e64223b733a343a2237323936223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a31303a22e4b88ae7ad89e585b531223b7d693a373b613a31313a7b733a393a22726174696e675f6964223b733a313a2237223b733a31313a22726174696e675f6e616d65223b733a31303a22e4b88ae7ad89e585b532223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033353934223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a343a2237323937223b733a31363a22726174696e675f637265646974656e64223b733a353a223130303332223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a31303a22e4b88ae7ad89e585b532223b7d693a383b613a31313a7b733a393a22726174696e675f6964223b733a313a2238223b733a31313a22726174696e675f6e616d65223b733a31303a22e4b88ae7ad89e585b533223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033363037223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a353a223130303333223b733a31363a22726174696e675f637265646974656e64223b733a353a223133323234223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a31303a22e4b88ae7ad89e585b533223b7d693a393b613a31313a7b733a393a22726174696e675f6964223b733a313a2239223b733a31313a22726174696e675f6e616d65223b733a31303a22e4b88ae7ad89e585b534223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033363139223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833383939223b733a31383a22726174696e675f6372656469747374617274223b733a353a223133323235223b733a31363a22726174696e675f637265646974656e64223b733a353a223137373834223b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31313a22726174696e675f69636f6e223b733a34383a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a31303a22e4b88ae7ad89e585b534223b7d693a31303b613a31313a7b733a393a22726174696e675f6964223b733a323a223130223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88be5a3ab31223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033363531223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393134223b733a31383a22726174696e675f6372656469747374617274223b733a353a223137373835223b733a31363a22726174696e675f637265646974656e64223b733a353a223233393430223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88be5a3ab31223b7d693a31313b613a31313a7b733a393a22726174696e675f6964223b733a323a223131223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88be5a3ab32223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033363636223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393134223b733a31383a22726174696e675f6372656469747374617274223b733a353a223233393431223b733a31363a22726174696e675f637265646974656e64223b733a353a223333303630223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88be5a3ab32223b7d693a31323b613a31313a7b733a393a22726174696e675f6964223b733a323a223132223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88be5a3ab33223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033363837223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393134223b733a31383a22726174696e675f6372656469747374617274223b733a353a223333303631223b733a31363a22726174696e675f637265646974656e64223b733a353a223433303932223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88be5a3ab33223b7d693a31333b613a31313a7b733a393a22726174696e675f6964223b733a323a223133223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88be5a3ab34223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033383939223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393134223b733a31383a22726174696e675f6372656469747374617274223b733a353a223433303933223b733a31363a22726174696e675f637265646974656e64223b733a353a223534303336223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88be5a3ab34223b7d693a31343b613a31313a7b733a393a22726174696e675f6964223b733a323a223134223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88be5a3ab35223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033393138223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393134223b733a31383a22726174696e675f6372656469747374617274223b733a353a223534303337223b733a31363a22726174696e675f637265646974656e64223b733a353a223635383932223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88be5a3ab35223b7d693a31353b613a31313a7b733a393a22726174696e675f6964223b733a323a223135223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88be5a3ab36223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033393330223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393134223b733a31383a22726174696e675f6372656469747374617274223b733a353a223635383933223b733a31363a22726174696e675f637265646974656e64223b733a353a223738363630223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88be5a3ab36223b7d693a31363b613a31313a7b733a393a22726174696e675f6964223b733a323a223136223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5a3ab31223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033393534223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a353a223738363631223b733a31363a22726174696e675f637265646974656e64223b733a353a223932333430223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5a3ab31223b7d693a31373b613a31313a7b733a393a22726174696e675f6964223b733a323a223137223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5a3ab32223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033393638223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a353a223932333431223b733a31363a22726174696e675f637265646974656e64223b733a363a22313036393332223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5a3ab32223b7d693a31383b613a31313a7b733a393a22726174696e675f6964223b733a323a223138223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5a3ab33223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033393831223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313036393333223b733a31363a22726174696e675f637265646974656e64223b733a363a22313232343336223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5a3ab33223b7d693a31393b613a31313a7b733a393a22726174696e675f6964223b733a323a223139223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5a3ab34223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343033393930223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313232343337223b733a31363a22726174696e675f637265646974656e64223b733a363a22313338383532223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f31392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5a3ab34223b7d693a32303b613a31313a7b733a393a22726174696e675f6964223b733a323a223230223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5a3ab35223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303031223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313338383533223b733a31363a22726174696e675f637265646974656e64223b733a363a22313536313830223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5a3ab35223b7d693a32313b613a31313a7b733a393a22726174696e675f6964223b733a323a223231223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5a3ab36223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303133223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313536313831223b733a31363a22726174696e675f637265646974656e64223b733a363a22313734343230223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5a3ab36223b7d693a32323b613a31313a7b733a393a22726174696e675f6964223b733a323a223232223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5a3ab31223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303431223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313734343231223b733a31363a22726174696e675f637265646974656e64223b733a363a22313933353732223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5a3ab31223b7d693a32333b613a31313a7b733a393a22726174696e675f6964223b733a323a223233223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5a3ab32223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303538223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313933353733223b733a31363a22726174696e675f637265646974656e64223b733a363a22323133363336223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5a3ab32223b7d693a32343b613a31313a7b733a393a22726174696e675f6964223b733a323a223234223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5a3ab33223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303731223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22323133363337223b733a31363a22726174696e675f637265646974656e64223b733a363a22323334363132223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5a3ab33223b7d693a32353b613a31313a7b733a393a22726174696e675f6964223b733a323a223235223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5a3ab34223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303832223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22323334363133223b733a31363a22726174696e675f637265646974656e64223b733a363a22323536353030223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5a3ab34223b7d693a32363b613a31313a7b733a393a22726174696e675f6964223b733a323a223236223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5a3ab35223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034303937223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22323536353031223b733a31363a22726174696e675f637265646974656e64223b733a363a22323739333030223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5a3ab35223b7d693a32373b613a31313a7b733a393a22726174696e675f6964223b733a323a223237223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5a3ab36223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034313038223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393333223b733a31383a22726174696e675f6372656469747374617274223b733a363a22323739333031223b733a31363a22726174696e675f637265646974656e64223b733a363a22333236373234223b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5a3ab36223b7d693a32383b613a31313a7b733a393a22726174696e675f6964223b733a323a223238223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08931223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034313333223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393431223b733a31383a22726174696e675f6372656469747374617274223b733a363a22333236373235223b733a31363a22726174696e675f637265646974656e64223b733a363a22333735393732223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08931223b7d693a32393b613a31313a7b733a393a22726174696e675f6964223b733a323a223239223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08932223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034313433223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393431223b733a31383a22726174696e675f6372656469747374617274223b733a363a22333735393733223b733a31363a22726174696e675f637265646974656e64223b733a363a22343237303434223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f32392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08932223b7d693a33303b613a31313a7b733a393a22726174696e675f6964223b733a323a223330223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08933223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323230223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393431223b733a31383a22726174696e675f6372656469747374617274223b733a363a22343237303435223b733a31363a22726174696e675f637265646974656e64223b733a363a22343739393430223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08933223b7d693a33313b613a31313a7b733a393a22726174696e675f6964223b733a323a223331223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08934223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323335223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22343739393431223b733a31363a22726174696e675f637265646974656e64223b733a363a22353334363630223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08934223b7d693a33323b613a31313a7b733a393a22726174696e675f6964223b733a323a223332223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08935223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323436223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22353334363631223b733a31363a22726174696e675f637265646974656e64223b733a363a22353931323034223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08935223b7d693a33333b613a31313a7b733a393a22726174696e675f6964223b733a323a223333223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08936223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323537223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22353931323035223b733a31363a22726174696e675f637265646974656e64223b733a363a22363439353732223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08936223b7d693a33343b613a31313a7b733a393a22726174696e675f6964223b733a323a223334223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08937223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323637223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22363439353733223b733a31363a22726174696e675f637265646974656e64223b733a363a22373039373634223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08937223b7d693a33353b613a31313a7b733a393a22726174696e675f6964223b733a323a223335223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08938223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323737223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22373039373635223b733a31363a22726174696e675f637265646974656e64223b733a363a22373731373830223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08938223b7d693a33363b613a31313a7b733a393a22726174696e675f6964223b733a323a223336223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08931223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034323931223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22373731373831223b733a31363a22726174696e675f637265646974656e64223b733a363a22383335363230223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08931223b7d693a33373b613a31313a7b733a393a22726174696e675f6964223b733a323a223337223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08932223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333039223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22383335363231223b733a31363a22726174696e675f637265646974656e64223b733a363a22393031323834223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08932223b7d693a33383b613a31313a7b733a393a22726174696e675f6964223b733a323a223338223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08933223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333139223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22393031323835223b733a31363a22726174696e675f637265646974656e64223b733a363a22393638373732223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08933223b7d693a33393b613a31313a7b733a393a22726174696e675f6964223b733a323a223339223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08934223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333330223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22393638373733223b733a31363a22726174696e675f637265646974656e64223b733a373a2231303338303834223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f33392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08934223b7d693a34303b613a31313a7b733a393a22726174696e675f6964223b733a323a223430223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08935223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333431223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a363a22313033383835223b733a31363a22726174696e675f637265646974656e64223b733a373a2231313039323230223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08935223b7d693a34313b613a31313a7b733a393a22726174696e675f6964223b733a323a223431223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08936223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333533223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231313039323231223b733a31363a22726174696e675f637265646974656e64223b733a373a2231313832313830223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08936223b7d693a34323b613a31313a7b733a393a22726174696e675f6964223b733a323a223432223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08937223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333637223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231313832313831223b733a31363a22726174696e675f637265646974656e64223b733a373a2231323536393634223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08937223b7d693a34333b613a31313a7b733a393a22726174696e675f6964223b733a323a223433223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08938223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333736223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231323536393635223b733a31363a22726174696e675f637265646974656e64223b733a373a2231333333353732223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08938223b7d693a34343b613a31313a7b733a393a22726174696e675f6964223b733a323a223434223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08931223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034333938223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231333333353733223b733a31363a22726174696e675f637265646974656e64223b733a373a2231343132303034223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08931223b7d693a34353b613a31313a7b733a393a22726174696e675f6964223b733a323a223435223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08932223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343039223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393531223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231343132303035223b733a31363a22726174696e675f637265646974656e64223b733a373a2231343932323630223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08932223b7d693a34363b613a31313a7b733a393a22726174696e675f6964223b733a323a223436223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08933223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343139223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393734223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231343932323631223b733a31363a22726174696e675f637265646974656e64223b733a373a2231353734333430223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08933223b7d693a34373b613a31313a7b733a393a22726174696e675f6964223b733a323a223437223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08934223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343330223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393734223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231353734333431223b733a31363a22726174696e675f637265646974656e64223b733a373a2231363538323434223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08934223b7d693a34383b613a31313a7b733a393a22726174696e675f6964223b733a323a223438223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08935223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343435223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393734223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231363538323435223b733a31363a22726174696e675f637265646974656e64223b733a373a2231373433393237223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08935223b7d693a34393b613a31313a7b733a393a22726174696e675f6964223b733a323a223439223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08936223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343536223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393734223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231373433393733223b733a31363a22726174696e675f637265646974656e64223b733a373a2231383331353234223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f34392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08936223b7d693a35303b613a31313a7b733a393a22726174696e675f6964223b733a323a223530223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08937223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343635223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393734223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231383331353235223b733a31363a22726174696e675f637265646974656e64223b733a373a2231393230393030223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08937223b7d693a35313b613a31313a7b733a393a22726174696e675f6964223b733a323a223531223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08938223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034343734223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393734223b733a31383a22726174696e675f6372656469747374617274223b733a373a2231393230393031223b733a31363a22726174696e675f637265646974656e64223b733a373a2232303537373030223b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08938223b7d693a35323b613a31313a7b733a393a22726174696e675f6964223b733a323a223532223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a131223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353035223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232303537373031223b733a31363a22726174696e675f637265646974656e64223b733a373a2232313937323336223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a131223b7d693a35333b613a31313a7b733a393a22726174696e675f6964223b733a323a223533223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a132223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353139223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232313937323337223b733a31363a22726174696e675f637265646974656e64223b733a373a2232333339353038223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a132223b7d693a35343b613a31313a7b733a393a22726174696e675f6964223b733a323a223534223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a133223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353236223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232333338353039223b733a31363a22726174696e675f637265646974656e64223b733a373a2232343834353136223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a133223b7d693a35353b613a31313a7b733a393a22726174696e675f6964223b733a323a223535223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a134223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353331223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232343834353137223b733a31363a22726174696e675f637265646974656e64223b733a373a2232363332323630223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a134223b7d693a35363b613a31313a7b733a393a22726174696e675f6964223b733a323a223536223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a135223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353339223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232363332323631223b733a31363a22726174696e675f637265646974656e64223b733a373a2232373832373430223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a135223b7d693a35373b613a31313a7b733a393a22726174696e675f6964223b733a323a223537223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a136223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353437223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232373832373431223b733a31363a22726174696e675f637265646974656e64223b733a373a2232393335393536223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a136223b7d693a35383b613a31313a7b733a393a22726174696e675f6964223b733a323a223538223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a137223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353534223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2232393335393537223b733a31363a22726174696e675f637265646974656e64223b733a373a2233303931393038223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a137223b7d693a35393b613a31313a7b733a393a22726174696e675f6964223b733a323a223539223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e6a0a138223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353639223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2233303931393039223b733a31363a22726174696e675f637265646974656e64223b733a373a2233323737303434223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f35392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e6a0a138223b7d693a36303b613a31313a7b733a393a22726174696e675f6964223b733a323a223630223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a131223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353834223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393838223b733a31383a22726174696e675f6372656469747374617274223b733a373a2233323737303435223b733a31363a22726174696e675f637265646974656e64223b733a373a2233343635333732223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a131223b7d693a36313b613a31313a7b733a393a22726174696e675f6964223b733a323a223631223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a132223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353930223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2233343635333733223b733a31363a22726174696e675f637265646974656e64223b733a373a2233363733353336223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a132223b7d693a36323b613a31313a7b733a393a22726174696e675f6964223b733a323a223632223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a133223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034353936223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2233363733353733223b733a31363a22726174696e675f637265646974656e64223b733a373a2233383835313737223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a133223b7d693a36333b613a31313a7b733a393a22726174696e675f6964223b733a323a223633223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a134223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034363034223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2233383835313738223b733a31363a22726174696e675f637265646974656e64223b733a373a2234313030323935223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a134223b7d693a36343b613a31313a7b733a393a22726174696e675f6964223b733a323a223634223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a135223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034363136223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2234313030323936223b733a31363a22726174696e675f637265646974656e64223b733a373a2234333138383930223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a135223b7d693a36353b613a31313a7b733a393a22726174696e675f6964223b733a323a223635223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a136223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034363235223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2234333138383931223b733a31363a22726174696e675f637265646974656e64223b733a373a2234353430393632223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a136223b7d693a36363b613a31313a7b733a393a22726174696e675f6964223b733a323a223636223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a137223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034363339223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2234353430393633223b733a31363a22726174696e675f637265646974656e64223b733a373a2234373636353131223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a137223b7d693a36373b613a31313a7b733a393a22726174696e675f6964223b733a323a223637223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade6a0a138223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034363537223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2234373636353132223b733a31363a22726174696e675f637265646974656e64223b733a373a2235303238313938223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade6a0a138223b7d693a36383b613a31313a7b733a393a22726174696e675f6964223b733a323a223638223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a131223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034373437223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2235303238313939223b733a31363a22726174696e675f637265646974656e64223b733a373a2235333139313833223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a131223b7d693a36393b613a31313a7b733a393a22726174696e675f6964223b733a323a223639223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a132223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034373536223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2235313339313834223b733a31363a22726174696e675f637265646974656e64223b733a373a2235363134353030223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f36392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a132223b7d693a37303b613a31313a7b733a393a22726174696e675f6964223b733a323a223730223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a133223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034373634223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2235363134353031223b733a31363a22726174696e675f637265646974656e64223b733a373a2235393134313439223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a133223b7d693a37313b613a31313a7b733a393a22726174696e675f6964223b733a323a223731223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a134223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034373733223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2235393134313530223b733a31363a22726174696e675f637265646974656e64223b733a373a2236323138313330223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a134223b7d693a37323b613a31313a7b733a393a22726174696e675f6964223b733a323a223732223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a135223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034373832223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2236323138313331223b733a31363a22726174696e675f637265646974656e64223b733a373a2236353236353030223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a135223b7d693a37333b613a31313a7b733a393a22726174696e675f6964223b733a323a223733223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a136223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034373932223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2236353236353031223b733a31363a22726174696e675f637265646974656e64223b733a373a2236383339323032223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a136223b7d693a37343b613a31313a7b733a393a22726174696e675f6964223b733a323a223734223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a137223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034383031223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2236383339323033223b733a31363a22726174696e675f637265646974656e64223b733a373a2237313536323336223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a137223b7d693a37353b613a31313a7b733a393a22726174696e675f6964223b733a323a223735223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae6a0a138223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034383039223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383833393936223b733a31383a22726174696e675f6372656469747374617274223b733a373a2237313536323337223b733a31363a22726174696e675f637265646974656e64223b733a373a2237353738303336223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae6a0a138223b7d693a37363b613a31313a7b733a393a22726174696e675f6964223b733a323a223736223b733a31313a22726174696e675f6e616d65223b733a373a22e5a4a7e6a0a131223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034383837223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303331223b733a31383a22726174696e675f6372656469747374617274223b733a373a2237353738303337223b733a31363a22726174696e675f637265646974656e64223b733a373a2238303236393131223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5a4a7e6a0a131223b7d693a37373b613a31313a7b733a393a22726174696e675f6964223b733a323a223737223b733a31313a22726174696e675f6e616d65223b733a373a22e5a4a7e6a0a132223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034383937223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303331223b733a31383a22726174696e675f6372656469747374617274223b733a373a2238303236393132223b733a31363a22726174696e675f637265646974656e64223b733a373a2238343831373731223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5a4a7e6a0a132223b7d693a37383b613a31313a7b733a393a22726174696e675f6964223b733a323a223738223b733a31313a22726174696e675f6e616d65223b733a373a22e5a4a7e6a0a133223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393037223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303331223b733a31383a22726174696e675f6372656469747374617274223b733a373a2238343831373732223b733a31363a22726174696e675f637265646974656e64223b733a373a2238393634353631223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5a4a7e6a0a133223b7d693a37393b613a31313a7b733a393a22726174696e675f6964223b733a323a223739223b733a31313a22726174696e675f6e616d65223b733a373a22e5a4a7e6a0a134223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393434223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303331223b733a31383a22726174696e675f6372656469747374617274223b733a373a2238393634353632223b733a31363a22726174696e675f637265646974656e64223b733a373a2239343735383531223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f37392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5a4a7e6a0a134223b7d693a38303b613a31313a7b733a393a22726174696e675f6964223b733a323a223830223b733a31313a22726174696e675f6e616d65223b733a373a22e5a4a7e6a0a135223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393533223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303331223b733a31383a22726174696e675f6372656469747374617274223b733a373a2239343735383532223b733a31363a22726174696e675f637265646974656e64223b733a383a223130303136323131223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5a4a7e6a0a135223b7d693a38313b613a31313a7b733a393a22726174696e675f6964223b733a323a223831223b733a31313a22726174696e675f6e616d65223b733a373a22e5a4a7e6a0a136223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393633223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303331223b733a31383a22726174696e675f6372656469747374617274223b733a383a223130303136323132223b733a31363a22726174696e675f637265646974656e64223b733a383a223130353836323131223b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5a4a7e6a0a136223b7d693a38323b613a31313a7b733a393a22726174696e675f6964223b733a323a223832223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08631223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393737223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223130353836323132223b733a31363a22726174696e675f637265646974656e64223b733a383a223131313836343231223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08631223b7d693a38333b613a31313a7b733a393a22726174696e675f6964223b733a323a223833223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08632223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393836223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223131313836343232223b733a31363a22726174696e675f637265646974656e64223b733a383a223131383137343131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08632223b7d693a38343b613a31313a7b733a393a22726174696e675f6964223b733a323a223834223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08633223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343034393933223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223131383137343132223b733a31363a22726174696e675f637265646974656e64223b733a383a223132343739373531223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08633223b7d693a38353b613a31313a7b733a393a22726174696e675f6964223b733a323a223835223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08634223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303031223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223132343739373532223b733a31363a22726174696e675f637265646974656e64223b733a383a223133313734303131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08634223b7d693a38363b613a31313a7b733a393a22726174696e675f6964223b733a323a223836223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08635223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303039223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223133313734303132223b733a31363a22726174696e675f637265646974656e64223b733a383a223133393030373631223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08635223b7d693a38373b613a31313a7b733a393a22726174696e675f6964223b733a323a223837223b733a31313a22726174696e675f6e616d65223b733a373a22e5b091e5b08636223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303137223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223133393030373632223b733a31363a22726174696e675f637265646974656e64223b733a383a223134343630353731223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e5b091e5b08636223b7d693a38383b613a31313a7b733a393a22726174696e675f6964223b733a323a223838223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08631223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303331223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223134343630353732223b733a31363a22726174696e675f637265646974656e64223b733a383a223135343534303131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08631223b7d693a38393b613a31313a7b733a393a22726174696e675f6964223b733a323a223839223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08632223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303430223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223135343534303132223b733a31363a22726174696e675f637265646974656e64223b733a383a223136323831363531223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f38392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08632223b7d693a39303b613a31313a7b733a393a22726174696e675f6964223b733a323a223930223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08633223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303535223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303437223b733a31383a22726174696e675f6372656469747374617274223b733a383a223136323831363532223b733a31363a22726174696e675f637265646974656e64223b733a383a223137313434303631223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08633223b7d693a39313b613a31313a7b733a393a22726174696e675f6964223b733a323a223931223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08634223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303635223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223137313434303632223b733a31363a22726174696e675f637265646974656e64223b733a383a223138303431383131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39312e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08634223b7d693a39323b613a31313a7b733a393a22726174696e675f6964223b733a323a223932223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08635223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303735223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223138303431383132223b733a31363a22726174696e675f637265646974656e64223b733a383a223138393735343731223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39322e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08635223b7d693a39333b613a31313a7b733a393a22726174696e675f6964223b733a323a223933223b733a31313a22726174696e675f6e616d65223b733a373a22e4b8ade5b08636223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303836223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223138393735343732223b733a31363a22726174696e675f637265646974656e64223b733a383a223139393435363131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39332e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b8ade5b08636223b7d693a39343b613a31313a7b733a393a22726174696e675f6964223b733a323a223934223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08631223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035303939223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223139393435363132223b733a31363a22726174696e675f637265646974656e64223b733a383a223230393532383031223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39342e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08631223b7d693a39353b613a31313a7b733a393a22726174696e675f6964223b733a323a223935223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08632223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035313038223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223230393532383032223b733a31363a22726174696e675f637265646974656e64223b733a383a223231393937363131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39352e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08632223b7d693a39363b613a31313a7b733a393a22726174696e675f6964223b733a323a223936223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08633223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035313137223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223231393937363132223b733a31363a22726174696e675f637265646974656e64223b733a383a223233303830363131223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39362e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08633223b7d693a39373b613a31313a7b733a393a22726174696e675f6964223b733a323a223937223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08634223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035313331223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223233303830363132223b733a31363a22726174696e675f637265646974656e64223b733a383a223234323032333731223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39372e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08634223b7d693a39383b613a31313a7b733a393a22726174696e675f6964223b733a323a223938223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08635223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035313430223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223234323032333732223b733a31363a22726174696e675f637265646974656e64223b733a383a223235333633343631223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39382e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08635223b7d693a39393b613a31313a7b733a393a22726174696e675f6964223b733a323a223939223b733a31313a22726174696e675f6e616d65223b733a373a22e4b88ae5b08636223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035313439223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223235333633343632223b733a31363a22726174696e675f637265646974656e64223b733a383a223236353634343531223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a34393a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f39392e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a373a22e4b88ae5b08636223b7d693a3130303b613a31313a7b733a393a22726174696e675f6964223b733a333a22313030223b733a31313a22726174696e675f6e616d65223b733a363a22e58583e5b885223b733a31333a22726174696e675f72656d61726b223b733a303a22223b733a31353a22726174696e675f6e696b656e616d65223b4e3b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343035313633223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338383834303536223b733a31383a22726174696e675f6372656469747374617274223b733a383a223236353634343532223b733a31363a22726174696e675f637265646974656e64223b733a383a223236353634343532223b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31313a22726174696e675f69636f6e223b733a35303a222f6e656564666f726275672f75706c6f61642f5075626c69632f696d616765732f726174696e672f71712f3130302e676966223b733a31393a22726174696e675f6f726967696e616c6e616d65223b733a363a22e58583e5b885223b7d7d);
INSERT INTO `needforbug_syscache` (`syscache_name`, `syscache_type`, `create_dateline`, `update_dateline`, `syscache_data`) VALUES
('ratinggroup', 1, 1346581623, 1353296317, 0x613a353a7b693a313b613a373a7b733a31343a22726174696e6767726f75705f6964223b733a313a2231223b733a31363a22726174696e6767726f75705f6e616d65223b733a383a22736f6c6469657273223b733a31373a22726174696e6767726f75705f7469746c65223b733a363a22e5a3abe585b5223b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343639393835223b733a31353a227570646174655f646174656c696e65223b733a313a2230223b733a31383a22726174696e6767726f75705f737461747573223b733a313a2231223b733a31363a22726174696e6767726f75705f736f7274223b733a313a2230223b7d693a323b613a373a7b733a31343a22726174696e6767726f75705f6964223b733a313a2232223b733a31363a22726174696e6767726f75705f6e616d65223b733a333a226e636f223b733a31373a22726174696e6767726f75705f7469746c65223b733a363a22e5a3abe5ae98223b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343730303231223b733a31353a227570646174655f646174656c696e65223b733a313a2230223b733a31383a22726174696e6767726f75705f737461747573223b733a313a2231223b733a31363a22726174696e6767726f75705f736f7274223b733a313a2230223b7d693a333b613a373a7b733a31343a22726174696e6767726f75705f6964223b733a313a2233223b733a31363a22726174696e6767726f75705f6e616d65223b733a31303a226c69657574656e616e74223b733a31373a22726174696e6767726f75705f7469746c65223b733a363a22e5b089e5ae98223b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343730333134223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333433353835383637223b733a31383a22726174696e6767726f75705f737461747573223b733a313a2231223b733a31363a22726174696e6767726f75705f736f7274223b733a313a2230223b7d693a343b613a373a7b733a31343a22726174696e6767726f75705f6964223b733a313a2234223b733a31363a22726174696e6767726f75705f6e616d65223b733a373a22636f6c6f6e656c223b733a31373a22726174696e6767726f75705f7469746c65223b733a363a22e6a0a1e5ae98223b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343730343132223b733a31353a227570646174655f646174656c696e65223b733a313a2230223b733a31383a22726174696e6767726f75705f737461747573223b733a313a2231223b733a31363a22726174696e6767726f75705f736f7274223b733a313a2230223b7d693a353b613a373a7b733a31343a22726174696e6767726f75705f6964223b733a313a2235223b733a31363a22726174696e6767726f75705f6e616d65223b733a383a2267656e6572616c73223b733a31373a22726174696e6767726f75705f7469746c65223b733a363a22e5b086e5b885223b733a31353a226372656174655f646174656c696e65223b733a31303a2231333338343730343238223b733a31353a227570646174655f646174656c696e65223b733a31303a2231333338393134343333223b733a31383a22726174696e6767726f75705f737461747573223b733a313a2231223b733a31363a22726174696e6767726f75705f736f7274223b733a313a2230223b7d7d),
('group_option', 1, 1346818592, 1354586539, 0x613a363a7b733a31333a2267726f75705f69736175646974223b733a313a2230223b733a32393a2267726f75705f69636f6e5f75706c6f616466696c655f6d617873697a65223b733a363a22323034383030223b733a31393a2267726f75705f696e646578746f7069636e756d223b733a323a223130223b733a31383a2267726f75705f6c697374746f7069636e756d223b733a323a223130223b733a31393a2267726f75705f686f74746f7069635f64617465223b733a363a22363034383030223b733a31383a2267726f75705f686f74746f7069635f6e756d223b733a323a223130223b7d),
('sociatype', 1, 1347204841, 1354669600, 0x613a343a7b733a323a227171223b613a31313a7b733a31323a22736f636961747970655f6964223b733a313a2231223b733a32303a22736f636961747970655f6964656e746966696572223b733a323a227171223b733a31353a22736f636961747970655f7469746c65223b733a383a225151e4ba92e88194223b733a31353a22736f636961747970655f6170706964223b733a393a22313030333033303031223b733a31363a22736f636961747970655f6170706b6579223b733a33323a223263386130356336633739333066376264306434383161383436326337646230223b733a31383a22736f636961747970655f63616c6c6261636b223b733a38323a22687474703a2f2f6262732e646f796f7568616f626162792e6e65742f696e6465782e7068703f6170703d686f6d6526633d7075626c696326613d736f6369615f63616c6c6261636b2676656e646f723d7171223b733a31353a22736f636961747970655f73636f7065223b733a38383a226765745f757365725f696e666f2c6164645f73686172652c6c6973745f616c62756d2c6164645f616c62756d2c75706c6f61645f7069632c6164645f746f7069632c6164645f6f6e655f626c6f672c6164645f776569626f223b733a31343a22736f636961747970655f6c6f676f223b733a37393a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f71712f71715f6c6f67696e2e676966223b733a31343a22736f636961747970655f69636f6e223b733a37333a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f71712f71712e676966223b733a31343a22736f636961747970655f62696e64223b733a37383a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f71712f71715f62696e642e676966223b733a31393a22736f636961747970655f62696e64736d616c6c223b733a38343a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f71712f71715f62696e645f736d616c6c2e676966223b7d733a353a22776569626f223b613a31313a7b733a31323a22736f636961747970655f6964223b733a313a2232223b733a32303a22736f636961747970655f6964656e746966696572223b733a353a22776569626f223b733a31353a22736f636961747970655f7469746c65223b733a31323a22e696b0e6b5aae5beaee58d9a223b733a31353a22736f636961747970655f6170706964223b733a303a22223b733a31363a22736f636961747970655f6170706b6579223b733a303a22223b733a31383a22736f636961747970655f63616c6c6261636b223b733a303a22223b733a31353a22736f636961747970655f73636f7065223b733a303a22223b733a31343a22736f636961747970655f6c6f676f223b733a38353a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f776569626f2f776569626f5f6c6f67696e2e676966223b733a31343a22736f636961747970655f69636f6e223b733a37393a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f776569626f2f776569626f2e676966223b733a31343a22736f636961747970655f62696e64223b733a38343a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f776569626f2f776569626f5f62696e642e676966223b733a31393a22736f636961747970655f62696e64736d616c6c223b733a39303a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f776569626f2f776569626f5f62696e645f736d616c6c2e676966223b7d733a363a22646f7562616e223b613a31313a7b733a31323a22736f636961747970655f6964223b733a313a2233223b733a32303a22736f636961747970655f6964656e746966696572223b733a363a22646f7562616e223b733a31353a22736f636961747970655f7469746c65223b733a363a22e8b186e793a3223b733a31353a22736f636961747970655f6170706964223b733a303a22223b733a31363a22736f636961747970655f6170706b6579223b733a303a22223b733a31383a22736f636961747970655f63616c6c6261636b223b733a303a22223b733a31353a22736f636961747970655f73636f7065223b733a303a22223b733a31343a22736f636961747970655f6c6f676f223b733a38373a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f646f7562616e2f646f7562616e5f6c6f67696e2e676966223b733a31343a22736f636961747970655f69636f6e223b733a38313a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f646f7562616e2f646f7562616e2e676966223b733a31343a22736f636961747970655f62696e64223b733a38363a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f646f7562616e2f646f7562616e5f62696e642e676966223b733a31393a22736f636961747970655f62696e64736d616c6c223b733a39323a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f646f7562616e2f646f7562616e5f62696e645f736d616c6c2e676966223b7d733a363a2272656e72656e223b613a31313a7b733a31323a22736f636961747970655f6964223b733a313a2234223b733a32303a22736f636961747970655f6964656e746966696572223b733a363a2272656e72656e223b733a31353a22736f636961747970655f7469746c65223b733a363a22e4babae4baba223b733a31353a22736f636961747970655f6170706964223b733a303a22223b733a31363a22736f636961747970655f6170706b6579223b733a303a22223b733a31383a22736f636961747970655f63616c6c6261636b223b733a303a22223b733a31353a22736f636961747970655f73636f7065223b733a303a22223b733a31343a22736f636961747970655f6c6f676f223b733a38373a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f72656e72656e2f72656e72656e5f6c6f67696e2e676966223b733a31343a22736f636961747970655f69636f6e223b733a38313a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f72656e72656e2f72656e72656e2e676966223b733a31343a22736f636961747970655f62696e64223b733a38363a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f72656e72656e2f72656e72656e5f62696e642e676966223b733a31393a22736f636961747970655f62696e64736d616c6c223b733a39323a222f6e656564666f726275672f75706c6f61642f736f757263652f657874656e73696f6e2f736f6369616c697a6174696f6e2f7374617469632f696d616765732f72656e72656e2f72656e72656e5f62696e645f736d616c6c2e676966223b7d7d),
('badword', 1, 1351134911, 1351826098, 0x613a313a7b693a313b613a323a7b733a353a227265676578223b733a31303a222f746f6f6261642f6973223b733a353a2276616c7565223b733a333a222a7274223b7d7d),
('shop_option', 1, 1351495755, 1354669560, 0x613a34303a7b733a393a2273686f705f6e616d65223b733a31353a22e99d92e6b5b7e4b9a6e6b395e999a2223b733a31343a22666978656474656c6570686f6e65223b733a31323a223032382d3536343533383739223b733a31363a2273686f705f6465736372697074696f6e223b733a31323a22e59586e59f8ee68f8fe8bfb0223b733a31323a2273686f705f61646472657373223b733a31323a22e59586e59f8ee59cb0e59d80223b733a31343a2273686f705f736572766963657171223b733a31373a22e59586e59f8ee5aea2e69c8d7171e58fb7223b733a31363a2273686f705f636c6f7365737461747573223b733a313a2231223b733a31363a2273686f705f636c6f7365726561736f6e223b733a31383a22e59586e59f8ee585b3e997ade58e9fe59ba0223b733a31313a2273686f705f6e6f74696365223b733a393a22e585ace5918a323232223b733a31373a2273686f705f7365727669636570686f6e65223b733a31313a223133383133353634353738223b733a31393a2273686f705f666978656474656c6570686f6e65223b733a31323a223032382d3233353634353738223b733a31373a2273686f70676f6f64735f696d6773697a65223b733a373a223535307c353530223b733a32323a2273686f70676f6f64735f7468756d62696d6773697a65223b733a373a223335307c333530223b733a31333a2273686f705f6c616e6775616765223b733a31323a22e7b3bbe7bb9fe8afade8a880223b733a31353a2273686f705f75726c72657772697465223b733a34303a2275726ce9878de586992d2d2d2d2d2d2d2d73686f705f75726c726577726974652028302c312c3229223b733a31333a2273686f705f74696d657a6f6e65223b733a31363a22e9bb98e8aea4e697b6e58cba2d2d2d2d223b733a383a2273686f705f696370223b733a32333a22696370e5a487e6a188e8af81e4b9a6e58fb72d2d2d2d2d223b733a31353a2273686f705f6372656469746e616d65223b733a363a22e98791e5b881223b733a32313a2273686f705f6372656469746368616e676572617465223b733a353a223130302d31223b733a31383a2273686f705f63726564697470617972617465223b733a363a223130302d3130223b733a32323a2273686f705f7374617274636f6d6d656e74636865636b223b733a313a2230223b733a31393a2273686f705f7265676973746572637265646974223b733a323a223131223b733a32333a2273686f705f7265676973746572656d61696c636865636b223b733a313a2230223b733a31383a2273686f705f666f726d636163686574696d65223b733a35303a22e5898de58fb0e9a1b5e99da2e7bc93e5ad98e5ad98e6b4bbe697b6e997b42d2d73686f705f666f726d636163686574696d65223b733a31343a2273686f705f7374617274677a6970223b733a32363a22e590afe794a8677a6970e6a8a1e5bc8f2d2d2d2d2d2d2d2d2d2d223b733a32313a2273686f705f73746172746f7264657272656d696e64223b733a313a2231223b733a32323a2273686f705f73746172746d657373616765626f617264223b733a313a2230223b733a32323a2273686f705f73746172746d657373616765636865636b223b733a313a2230223b733a31383a2273686f705f6f726967696e616c696d616765223b733a34363a22e59586e59381e9bb98e8aea4e59bbee5838f2d2d2d2d2d2d2d2d2d2d73686f705f6f726967696e616c696d616765223b733a31383a2273686f705f6f726967696e616c73746f636b223b733a313a2231223b733a31393a2273686f705f636f6d6d656e7472657175657374223b733a313a2230223b733a31333a2273686f705f6b657973776f7264223b733a363a22e998bfe69687223b733a31373a2273686f705f63757272656e6379666f726d223b733a383a22efbfa52573e58583223b733a32303a2273686f705f676f6f64736e616d656c656e676874223b733a35323a22e59586e59381e5908de7a7b0e995bfe5baa62d2d2d2d2d2d2d2d2d2d2d2d2d2d73686f705f676f6f64736e616d656c656e676874223b733a31353a2273686f705f696d6167657769647468223b733a34383a22e59bbee5838fe7bca9e795a5e59bbee5aebde5baa62d2d2d2d2d2d2d2d2d2d2d2d73686f705f696d6167657769647468223b733a31363a2273686f705f696d616765686569676874223b733a34393a22e59bbee5838fe7bca9e795a5e59bbee9ab98e5baa62d2d2d2d2d2d2d2d2d2d2d2d73686f705f696d616765686569676874223b733a31353a2273686f705f636f6d6d656e746e756d223b733a313a2235223b733a31353a2273686f705f61727469636c656e756d223b733a313a2235223b733a32333a2273686f705f61727469636c657468656d656c656e676874223b733a35353a22e69687e7aba0e6a087e9a298e995bfe5baa62d2d2d2d2d2d2d2d2d2d2d2d2d2d73686f705f61727469636c657468656d656c656e676874223b733a31343a2273686f705f706167657374796c65223b733a313a2230223b733a31393a2273686f705f676f6f647373686f777374796c65223b733a313a2230223b7d),
('shop_article', 1, 1354074709, 1354680213, 0x613a343a7b693a313b613a383a7b693a313b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f31223b693a313b733a31323a22e8b4ade4b9b0e6b581e7a88b223b7d693a323b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f32223b693a313b733a31323a22e99480e594aee69da1e6acbe223b7d693a333b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f33223b693a313b733a31323a22e9858de98081e696b9e5bc8f223b7d693a343b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f35223b693a313b733a31323a22e694afe4bb98e696b9e5bc8f223b7d693a353b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f36223b693a313b733a31323a22e99a90e7a781e4bf9de68aa4223b7d693a363b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f37223b693a313b733a31323a22e5b8b8e8a781e997aee9a298223b7d693a373b613a323a7b693a303b733a35353a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f666565646261636b2f7469642f38223b693a313b733a31323a22e6848fe8a781e58f8de9a688223b7d693a303b733a31323a22e5a682e4bd95e8b4ade4b9b0223b7d693a323b613a313a7b693a303b733a31323a22e7ab99e782b9e59cb0e59bbe223b7d693a333b613a343a7b693a313b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f34223b693a313b733a393a22e8978fe5aeb6e7af87223b7d693a323b613a323a7b693a303b733a35323a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f39223b693a313b733a31323a22e7ab99e782b9e6b581e7a88b223b7d693a333b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3130223b693a313b733a31323a22e5889be4bd9ce8afb4e6988e223b7d693a303b733a31323a22e59586e59f8ee789b9e889b2223b7d693a343b613a393a7b693a313b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3131223b693a313b733a363a22e58e86e7a88b223b7d693a323b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3132223b693a313b733a31323a22e585ace58fb8e4bb8be7bb8d223b7d693a333b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3133223b693a313b733a31323a22e88194e7b3bbe68891e4bbac223b7d693a343b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3134223b693a313b733a31323a22e585b3e6b3a8e68891e4bbac223b7d693a353b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3135223b693a313b733a31323a22e59088e4bd9ce4bc99e4bcb4223b7d693a363b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3136223b693a313b733a31323a22e4b89ae58aa1e58e9fe58899223b7d693a373b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3137223b693a313b733a31323a22e5a596e9a1b9e88da3e8aa89223b7d693a383b613a323a7b693a303b733a35333a222f6e656564666f726275672f75706c6f61642f696e6465782e7068702f73686f7061727469636c652f696e6465782f7469642f3138223b693a313b733a31353a22e8b59ee58aa9e4b88ee68588e59684223b7d693a303b733a31323a22e585b3e4ba8ee68891e4bbac223b7d7d);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_theme`
--

CREATE TABLE IF NOT EXISTS `needforbug_theme` (
  `theme_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主题ID',
  `theme_name` varchar(32) NOT NULL DEFAULT '' COMMENT '主题名字',
  `theme_dirname` varchar(32) NOT NULL COMMENT '主题英文目录名字',
  `theme_copyright` varchar(250) NOT NULL DEFAULT '' COMMENT '主题版权',
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `needforbug_theme`
--

INSERT INTO `needforbug_theme` (`theme_id`, `theme_name`, `theme_dirname`, `theme_copyright`) VALUES
(1, '默认模板套系', 'Default', '点牛（成都）'),
(35, '默认模板套系', 'Zhong', '点牛（成都）');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_user`
--

CREATE TABLE IF NOT EXISTS `needforbug_user` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- 转存表中的数据 `needforbug_user`
--

INSERT INTO `needforbug_user` (`user_id`, `user_name`, `user_nikename`, `user_password`, `user_registerip`, `user_lastlogintime`, `user_lastloginip`, `user_logincount`, `user_email`, `user_remark`, `user_sign`, `create_dateline`, `update_dateline`, `user_status`, `user_random`, `user_temppassword`, `user_extendstyle`) VALUES
(1, 'admin', '', '97b9ffa27763590819e300183bc968cf', '127.0.0.1', 1354669607, '::1', 856, 'xiaoniuge@dianniu.net', '', '每一天都是新的,欢迎大家光临我们的心空间，世界之巅，唯我读准。', 1333281705, 1354669607, 1, 'aC6aBb', '', '0'),
(6, 'xiaomage', '小马哥', '5f88f0998ea9017b28290a9232b9a7ec', '::1', 1352347371, '::1', 23, '635750556@qqqq.com', '', '', 1338557883, 1352347371, 1, 'e64d5F', '', 't5'),
(7, 'test', '123456', '3e50575fc4fd27791d9fd242961a4f3d', '::1', NULL, NULL, 0, 'log1990@1262.com', NULL, '', 1340625011, 1340625011, 1, '845f16', '', ''),
(8, 'test2', 'dsfsdf', '150160def89ecc78ba8bcbdf1426c534', '::1', NULL, NULL, 0, 'lgo11@ss.com', NULL, '', 1340625061, 1340625061, 1, '020Be2', '', ''),
(9, 'testssdfdsdf', '12', 'ff1e6cccb1deeafcb23595bcd055cbc3', '::1', 1340625140, '::1', 1, 'lgoxx@x.com', NULL, '', 1340625112, 1340625180, 1, '703c59', '', ''),
(10, 'xiaoxiaoxiao', '小小', '8752aa796dd63a7bc6f8f45477da00ef', '::1', NULL, NULL, 0, 'xx@x.com', '', '', 1340625360, NULL, 1, 'BAc0F6', '', ''),
(11, 'sdfsdfsdfsdfs', 'dsfsd', 'ed6725f7ea9f058f0f8d6e79e68c3fbb', '::1', NULL, NULL, 0, '63575055dsfsdf6@qq.com', '', '', 1340625424, NULL, 1, '76a47E', '', ''),
(12, 'sdfsdfsdfsdfs', 'dsfsd', '123456', '', NULL, NULL, 0, '63575055dsfsdf6@qq.com', '', '', 1340625692, NULL, 1, '', '', ''),
(13, 'sdfsdfsdfsdfs', 'dsfsd', '066a7a72a3a87e39d910decd0b7b407b', '', NULL, NULL, 0, '63575055dsfsdf6@qq.com', '', '', 1340625774, 1340625774, 1, 'DC70e6', '', ''),
(15, 'my', '西瓜', '1a69f983f3fa51ed592dea270ae2b0a7', '::1', 1341476459, '::1', 2, 'xxx@00.com', NULL, '<strong><span style="color:#FF9900;">他是一个好人啦，我哈哈</span></strong>', 1341471659, 1341476459, 1, '1ddc7a', '', ''),
(16, 'woshihapi', '哈啤', 'c7a559dd18033f1038895a552969842a', '::1', 1342689181, '::1', 4, 'xxx@ss.com', NULL, '', 1342230126, 1342689181, 1, '81778f', '', ''),
(17, 'weiyong1999', '1999', 'a742708e7dda03388bbf08b00440b41d', '::1', 1345623288, '::1', 1, '394578420@qq.com', NULL, '', 1342745610, 1345716856, 1, '15D1c1', 'faecc00b2fdd5b752f39db174cc7617c', ''),
(18, 'weiyong2000', '2000', '6e943d35a60c9b4ff79b46eda27f2f25', '::1', 1347787470, '::1', 2, '495223424@qq.com', NULL, '', 1342745637, 1347787470, 1, '15f14f', '', ''),
(19, 'xiaoxiaoxiao', '小马哥', '5b2364ee7b25a846c6102cb7c5d20667', '', NULL, NULL, 0, 'xx@qq.com', '', '', 1342961158, 1342961158, 1, 'd3C180', '', ''),
(20, 'xiaoxiaoxiao2', '123456', 'b114077d5fba485236f845346ef6dd0e', '::1', NULL, NULL, 0, 'dsf@ss.com', '', '', 1342961274, 1342961274, 1, 'ceEB7F', '', ''),
(21, 'sdfffffff', '', '6af0c89160f6de542f25c35eeb0ba5dc', '::1', NULL, NULL, 0, 'sxxxxxxxxxxxxx@qq.com', NULL, '', 1343357688, 1343357688, 1, '625a46', '', ''),
(22, 'sfdddddddddddd', '', 'ccb8148229d02a7f369d32d339adce44', '::1', NULL, NULL, 0, 'xiaomage2ss@xx.com', '', '', 1343357776, 1343589078, 1, 'C6fbAF', '', ''),
(23, 'dsfsfsfsdf', '', 'e36af24812a4ca0244a5a5c9247175ec', '::1', NULL, NULL, 0, 'sdfsf2q@xx.com', NULL, '', 1343357862, 1343357862, 1, 'e98877', '', ''),
(24, 'andyma', 'andyma', 'a10a87b6d31460921e7ea633d82f006a', '::1', NULL, NULL, 0, 'andy871201529@qq.com', NULL, '', 1345637021, 1345638698, 1, 'c4cE10', '97365858680b900955d97348d9d38c79', ''),
(25, 'weiyong199', '', 'd9a22224af5cbeb00cf0043e63ff3655', '::1', NULL, NULL, 0, 'kevin@dianniu.net', NULL, '', 1345716965, 1345717070, 1, '86768A', '613dd9563e0f8fa622ad522df85ae73e', ''),
(26, 'niu', '', 'dc0e99cea3eaab972eb490ddde125881', '::1', 1345726697, '::1', 1, 'xiaoniuge@dianniu.net', NULL, '', 1345726531, 1345726697, 1, '03A63d', '', ''),
(27, '小牛哥Dyhb', '小牛哥Dyhb', 'e7fec0b0688dc21badfba90d0bef563f', '::1', NULL, NULL, 0, 'xxxsdfs@dssf.com', NULL, '', 1347177536, 1347177536, 1, '918CC4', '', ''),
(28, '小牛哥Dyhb2', '小牛哥Dyhb2', 'eee1cc0493c16f485943d65ff714ba55', '::1', NULL, NULL, 0, 'sfsdfsdfsdf@xxx.com', NULL, '', 1347178113, 1347178113, 1, '432Ebd', '', ''),
(29, '小牛哥Dyhbsdf', '小牛哥Dyhb', 'a02d5a7da9ef1416a9ec310ac37d0d12', '::1', NULL, NULL, 0, 'xx@dsdfs.com', NULL, '', 1347178148, 1347178148, 1, 'fc3470', '', ''),
(30, '小牛哥Dyhbsdfsdfsdf', '小牛哥Dyhbsdfsdfsdf', 'ff5ca453a12f3792596c04d6c16e9956', '::1', 1347182117, '::1', 3, 'sdfsf@xxx.com', NULL, '', 1347178191, 1347182117, 1, '9754c5', '', ''),
(31, '小牛哥Dy', '小牛哥Dyhb', '2e0cb437e43e2b10a01fc2553d05247f', '::1', NULL, NULL, 0, 'xxx@xx.com', NULL, '', 1347182178, 1347182178, 1, '7bECC1', '', ''),
(32, '小牛', '小牛哥Dyhb', 'e0042cccc4d85c1e1e110f23f410d8e4', '::1', NULL, NULL, 0, 'zfsdf@dsdf.com', NULL, '', 1347182278, 1347182278, 1, '46427A', '', ''),
(33, '小牛yhb', '小牛哥Dyhb', '4a5c2b9386356f1b3d9236c66f8fbc39', '::1', NULL, NULL, 0, 'sfsfs@sss.com', NULL, '', 1347182510, 1347182510, 1, '1B304A', '', ''),
(34, '小牛sdf', '小牛哥Dsdfsyhb', '144e7078e2c79eb183c69bffe0b12411', '::1', 1347182731, '::1', 1, 'sdfs@xx.com', NULL, '', 1347182567, 1347182731, 1, 'A2933A', '', ''),
(35, '小牛sdfrt', '小牛哥Dsdfsyhb', '96734245b4ac143a6fdea78adf524e4b', '::1', NULL, NULL, 0, 'sdfs@xx.comt', NULL, '', 1347182679, 1347182679, 1, '7d008B', '', ''),
(36, '小牛哥Dyhbxxxxxx', '小牛哥Dyhbxxxx', '7c0d27675c905bef471a16a5758e7c87', '::1', NULL, NULL, 0, 'sdfsdf@dsfdsf.com', NULL, '', 1347182870, 1347182870, 1, '816713', '', ''),
(37, '小牛哥Dyhbxxxxxxsfdfsdfsdf', '小牛哥Dyhbxxxxsdfsdf', '11469ee3b0acb55d9df7082d957457dc', '::1', 1347183679, '::1', 1, 'sdfsdf@dsfdsf.comfdsfsdf', NULL, '', 1347183561, 1347183679, 1, 'a897c8', '', ''),
(38, '小牛哥D斯蒂芬斯蒂芬是yhb', '小牛哥Dsdf yhb', '0411e1d7865870e27dc5c24ac861c301', '::1', 1347195550, '::1', 5, 'sdfsf@sxxx.com', NULL, '', 1347185327, 1347195550, 1, 'dA57c6', '', ''),
(39, '我是XX', 'xx通知', '51dc4e53fafcd46cbd70159514085e3a', '::1', NULL, NULL, 0, 'xxxx@sss.com', NULL, '', 1352881022, 1352881022, 1, 'F52c2c', '', ''),
(40, 'sdf', 'SDF通知', '36f31620def7db65973a1294144d4b2b', '::1', 1352881137, '::1', 1, 'xxxx@222sss.com', NULL, '', 1352881117, 1352881137, 1, '86D440', '', ''),
(41, 'heeeeeee', '123456', '4e699d784890582261c05d64531a5254', '::1', NULL, NULL, 0, 'xxx@xxx.com', NULL, '', 1354089501, 1354089501, 1, 'E44812', '', ''),
(42, 'dsfsdfsfdsfssdfsdf', '123456', '0220f165f1bca0910dbb851b1d70a851', '::1', NULL, NULL, 0, 'sxxxx@xx.com', NULL, '', 1354089638, 1354089638, 1, '43d886', '', ''),
(43, '大牛哥', '南派三叔', '108fe3fb627b70272228ec31327518b5', '::1', 1354089728, '::1', 1, 'xxx@dian.com', NULL, '', 1354089718, 1354089728, 1, '156472', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_usercount`
--

CREATE TABLE IF NOT EXISTS `needforbug_usercount` (
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
-- 转存表中的数据 `needforbug_usercount`
--

INSERT INTO `needforbug_usercount` (`user_id`, `usercount_extendcredit1`, `usercount_extendcredit2`, `usercount_extendcredit3`, `usercount_extendcredit4`, `usercount_extendcredit5`, `usercount_extendcredit6`, `usercount_extendcredit7`, `usercount_extendcredit8`, `usercount_friends`, `usercount_oltime`, `usercount_fans`) VALUES
(1, 38, 706, 0, 0, 0, 0, 0, 0, 12, 0, 0),
(6, 2, 18, 0, 0, 0, 0, 0, 0, 1, 0, 1),
(7, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(8, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2),
(9, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2),
(11, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(16, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(15, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(17, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(18, 0, 4, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(19, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(20, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(24, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(25, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(30, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(31, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(32, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(35, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(39, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(40, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(41, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(42, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(43, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_userprofile`
--

CREATE TABLE IF NOT EXISTS `needforbug_userprofile` (
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
-- 转存表中的数据 `needforbug_userprofile`
--

INSERT INTO `needforbug_userprofile` (`user_id`, `userprofile_realname`, `userprofile_gender`, `userprofile_birthyear`, `userprofile_birthmonth`, `userprofile_birthday`, `userprofile_constellation`, `userprofile_zodiac`, `userprofile_telephone`, `userprofile_mobile`, `userprofile_idcardtype`, `userprofile_idcard`, `userprofile_address`, `userprofile_zipcode`, `userprofile_nationality`, `userprofile_birthprovince`, `userprofile_birthcity`, `userprofile_birthdist`, `userprofile_birthcommunity`, `userprofile_resideprovince`, `userprofile_residecity`, `userprofile_residedist`, `userprofile_residecommunity`, `userprofile_residesuite`, `userprofile_graduateschool`, `userprofile_company`, `userprofile_education`, `userprofile_occupation`, `userprofile_position`, `userprofile_revenue`, `userprofile_affectivestatus`, `userprofile_lookingfor`, `userprofile_bloodtype`, `userprofile_height`, `userprofile_weight`, `userprofile_alipay`, `userprofile_icq`, `userprofile_qq`, `userprofile_yahoo`, `userprofile_msn`, `userprofile_taobao`, `userprofile_site`, `userprofile_bio`, `userprofile_interest`, `userprofile_google`, `userprofile_baidu`, `userprofile_renren`, `userprofile_douban`, `userprofile_facebook`, `userprofile_twriter`, `userprofile_dianniu`, `userprofile_skype`, `userprofile_weibocom`, `userprofile_tqqcom`, `userprofile_diandian`, `userprofile_kindergarten`, `userprofile_primary`, `userprofile_juniorhighschool`, `userprofile_highschool`, `userprofile_university`, `userprofile_master`, `userprofile_dr`, `userprofile_nowschool`, `userprofile_field1`, `userprofile_field2`, `userprofile_field3`, `userprofile_field4`, `userprofile_field5`, `userprofile_field6`, `userprofile_field7`, `userprofile_field8`) VALUES
(1, '牛B', 1, 2011, 6, 14, '', '', '08188301355', '', '身份证', '', '', '', '', '四川省', '凉山彝族自治州', '金阳县', '丝窝乡', '广西壮族自治区', '崇左市', '扶绥县', '', '', 'dfs', '', '本科', '秘书', '', '', '已婚', '找老婆', 'B', '', '', '', '', '63575056', '', '', '', 'http://baidu.com', '<span style="background-color:#E56600;">我来自于四川地区，我是个happydsffffffffffff</span>', '<span style="color:#006600;background-color:#60D978;"><strong>机会多的哈</strong></span>', '', '', '', '', '', '', '', '', '', '', '', 'FDSF', '', '', '', '', '', '', '博士', 'df', '我查', '', '', '', '', '', ''),
(6, 'dfdsf', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'A', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(7, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, 'dsfsdf', 0, 2012, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'A', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(15, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(16, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(18, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(19, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(21, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(22, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(23, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(24, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(28, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(29, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(30, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(31, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(32, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(33, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(34, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(35, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(36, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(37, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(38, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(39, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(40, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(41, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(42, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(43, '', 0, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_userprofilesetting`
--

CREATE TABLE IF NOT EXISTS `needforbug_userprofilesetting` (
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
-- 转存表中的数据 `needforbug_userprofilesetting`
--

INSERT INTO `needforbug_userprofilesetting` (`userprofilesetting_id`, `userprofilesetting_status`, `userprofilesetting_title`, `userprofilesetting_description`, `userprofilesetting_sort`, `userprofilesetting_showinfo`, `userprofilesetting_allowsearch`, `userprofilesetting_privacy`) VALUES
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
-- 表的结构 `needforbug_userrole`
--

CREATE TABLE IF NOT EXISTS `needforbug_userrole` (
  `role_id` mediumint(9) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `user_id` char(32) NOT NULL DEFAULT '' COMMENT '用户ID',
  PRIMARY KEY (`role_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
