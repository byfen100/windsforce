-- WINDSFORCE 数据库表
-- version 1.0.1
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
-- 表的结构 `windsforce_serviceoption`
--

DROP TABLE IF EXISTS `{WINDSFORCE}serviceoption`;
CREATE TABLE `{WINDSFORCE}serviceoption` (
  `serviceoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `serviceoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`serviceoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `windsforce_serviceinstall`
--

DROP TABLE IF EXISTS `{WINDSFORCE}serviceinstall`;
CREATE TABLE `{WINDSFORCE}serviceinstall` (
  `serviceinstall_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '安装信息ID',
  `serviceinstall_domain` varchar(350) CHARACTER SET utf8 NOT NULL COMMENT '安装域名',
  `serviceinstall_version` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '版本',
  `serviceinstall_release` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT '版本时间',
  `serviceinstall_bug` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT 'Bug版本',
  `serviceinstall_update` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为升级',
  `create_dateline` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `serviceinstall_ip` varchar(40) CHARACTER SET utf8 NOT NULL COMMENT '安装IP地址',
  PRIMARY KEY (`serviceinstall_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
