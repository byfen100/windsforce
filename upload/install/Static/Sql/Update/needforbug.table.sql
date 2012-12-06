-- NEEDFORBUG 数据库表升级
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
-- 添加表的结构 `needforbug_test`
--

DROP TABLE IF EXISTS `#@__test`;
CREATE TABLE `#@__test` (
  `test_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '测试表ID',
  `test_value` varchar(250) CHARACTER SET utf8 NOT NULL COMMENT '测试表值',
  PRIMARY KEY (`test_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 更新表的结构 `needforbug_test`
--

ALTER TABLE  `#@__test` ADD  `create_dateline` INT( 10 ) NOT NULL COMMENT  '创建时间',
ADD  `update_dateline` INT( 10 ) NOT NULL COMMENT  '更新时间';
