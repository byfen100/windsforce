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
-- 表的结构 `windsforce_eventoption`
--

DROP TABLE IF EXISTS `{WINDSFORCE}eventoption`;
CREATE TABLE `{WINDSFORCE}eventoption` (
  `eventoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `eventoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`eventoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
