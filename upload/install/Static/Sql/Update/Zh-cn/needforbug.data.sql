-- WINDSFORCE 数据库数据升级
-- version 1.0
-- http://www.doyouhaobaby.net
--
-- 开发: DianniuTeam
-- 网站: http://dianniu.net

--
-- 数据库: 升级数据
--

-- --------------------------------------------------------

--
-- 转存表中的数据 `needforbug_test`
--

INSERT INTO `#@__test` (`test_id`, `test_value`, `create_dateline`, `update_dateline`) VALUES
(1, '测试值', 1340369066, 1340369066),
(2, '测试值2', 1340369066, 1340369066);

-- --------------------------------------------------------

--
-- 删除表中的数据 `needforbug_test`
--

DELETE FROM `#@__test` WHERE `test_id`=1;
