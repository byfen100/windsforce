-- WINDSFORCE 数据库数据
-- version 1.0
-- http://www.windsforce.com
--
-- 开发: Windsforce Studio
-- 网站: http://www.windsforce.com

--
-- 数据库: 升级数据
--

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_test`
--

INSERT INTO `#@__test` (`test_id`, `test_value`, `create_dateline`, `update_dateline`) VALUES
(1, '测试值', 1340369066, 1340369066),
(2, '测试值2', 1340369066, 1340369066);

-- --------------------------------------------------------

--
-- 删除表中的数据 `windsforce_test`
--

DELETE FROM `#@__test` WHERE `test_id`=1;
