-- WINDSFORCE 数据库数据
-- version 1.1
-- http://www.windsforce.com
--
-- 开发: Windsforce TEAM
-- 网站: http://www.windsforce.com

--
-- 数据库: 升级数据
--

-- --------------------------------------------------------

--
-- 更新表中的数据 `windsforce_node`
--

UPDATE  `#@__node` SET  `node_name` =  'home@attachment@add|normal_upload' WHERE  `windsforce_node`.`node_id` =48;

DELETE FROM `#@__node` WHERE `node_id` = 90;
DELETE FROM `#@__node` WHERE `node_id` = 91;

INSERT INTO `#@__node` (`node_id`, `node_name`, `node_title`, `node_status`, `node_remark`, `node_sort`, `node_parentid`, `node_level`, `nodegroup_id`, `create_dateline`, `update_dateline`, `node_issystem`) VALUES
(90, 'group@grouptopicadmin@movetopic_dialog|movetopic', '帖子移动', 1, '', 0, 71, 3, 0, 1367833115, 0, 1),
(91, 'group@grouptopicadmin@uptopic_dialog|uptopic', '提升下沉主题', 1, '', 0, 71, 3, 0, 1368282389, 0, 1);

-- --------------------------------------------------------

--
-- 添加表中的数据 `windsforce_option`
--

DELETE FROM `#@__option` WHERE `option_name` = 'todayusernum';
DELETE FROM `#@__option` WHERE `option_name` = 'todaytotalnum';
DELETE FROM `#@__option` WHERE `option_name` = 'todayhomefreshnum';
DELETE FROM `#@__option` WHERE `option_name` = 'todayhomefreshcommentnum';
DELETE FROM `#@__option` WHERE `option_name` = 'todayattachmentnum';

INSERT INTO `#@__option` (`option_name`, `option_value`) VALUES
('todayusernum', '0'),
('todaytotalnum', '0'),
('todayhomefreshnum', '0'),
('todayhomefreshcommentnum', '0'),
('todayattachmentnum', '0');

-- --------------------------------------------------------

--
-- 添加表中的数据 `windsforce_access`
--

DELETE FROM `#@__access` WHERE `role_id` = 1 AND `node_id` = 90 AND `access_level` = 3 AND `access_parentid` = 71 AND `access_status` = 1;
DELETE FROM `#@__access` WHERE `role_id` = 2 AND `node_id` = 91 AND `access_level` = 3 AND `access_parentid` = 71 AND `access_status` = 1;
DELETE FROM `#@__access` WHERE `role_id` = 3 AND `node_id` = 91 AND `access_level` = 3 AND `access_parentid` = 71 AND `access_status` = 1;

INSERT INTO `#@__access` (`role_id`, `node_id`, `access_level`, `access_parentid`, `access_status`) VALUES
(1, 90, 3, 71, 1),
(2, 91, 3, 71, 1),
(3, 91, 3, 71, 1);

-- --------------------------------------------------------

--
-- 更新表中的数据 `windsforce_creditrule`
--

DELETE FROM `#@__creditrule` WHERE `creditrule_id` = 27;

INSERT INTO `#@__creditrule` (`creditrule_id`, `creditrule_name`, `creditrule_action`, `creditrule_cycletype`, `creditrule_cycletime`, `creditrule_rewardnum`, `creditrule_extendcredit1`, `creditrule_extendcredit2`, `creditrule_extendcredit3`, `creditrule_extendcredit4`, `creditrule_extendcredit5`, `creditrule_extendcredit6`, `creditrule_extendcredit7`, `creditrule_extendcredit8`) VALUES
(27, '帖子被提升', 'group_uptopic', 4, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 添加表中的数据 `windsforce_groupoption`
--

DELETE FROM `#@__groupoption` WHERE `groupoption` = 'newtopic_default';

INSERT INTO `#@__groupoption` (`groupoption_name`, `groupoption_value`) VALUES
('newtopic_default', '1');
