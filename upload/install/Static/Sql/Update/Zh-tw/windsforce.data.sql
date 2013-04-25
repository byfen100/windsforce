-- WINDSFORCE 数据库数据(Zh-tw - 台湾繁体中文)
-- version 1.0
-- http://www.windsforce.com
--
-- 開發: Windsforce TEAM
-- 網站: http://www.windsforce.com

--
-- 數據庫: 升級數據
--

-- --------------------------------------------------------

--
-- 轉存表中的數據 `windsforce_test`
--

INSERT INTO `#@__test` (`test_id`, `test_value`, `create_dateline`, `update_dateline`) VALUES
(1, '測試值', 1340369066, 1340369066),
(2, '測試值2', 1340369066, 1340369066);

-- --------------------------------------------------------

--
-- 刪除表中的數據 `windsforce_test`
--

DELETE FROM `#@__test` WHERE `test_id`=1;
