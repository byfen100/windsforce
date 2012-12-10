-- WINDSFORCE 群组数据库数据
-- version 1.0
-- http://www.doyouhaobaby.net
--
-- 开发: DianniuTeam
-- 网站: http://dianniu.net

--
-- 数据库: 群组初始化数据
--

-- --------------------------------------------------------

--
-- 转存表中的数据 `needforbug_app`
--

INSERT INTO `#@__app` (`app_id`, `app_identifier`, `app_name`, `app_version`, `app_description`, `app_url`, `app_email`, `app_author`, `app_authorurl`, `app_isadmin`, `app_isinstall`, `app_isuninstall`, `app_issystem`, `app_isappnav`, `app_status`) VALUES
(2, 'group', '小组', '', '群组应用', 'http://doyouhaobaby.net', 'admin@dianniu.net', 'DianniuTeam', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 转存表中的数据 `needforbug_groupoption`
--

INSERT INTO `#@__groupoption` (`groupoption_name`, `groupoption_value`) VALUES
('group_isaudit', '0'),
('group_icon_uploadfile_maxsize', '204800');

-- --------------------------------------------------------

--
-- 转存表中的数据 `needforbug_nav`
--

INSERT INTO `#@__nav` (`nav_id`, `nav_parentid`, `nav_name`, `nav_identifier`, `nav_title`, `nav_url`, `nav_target`, `nav_type`, `nav_style`, `nav_location`, `nav_status`, `nav_sort`, `nav_color`, `nav_icon`) VALUES
(9, 0, '小组', 'app_group', 'group', 'group://public/index', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 0, 1, 0, 0, '');
