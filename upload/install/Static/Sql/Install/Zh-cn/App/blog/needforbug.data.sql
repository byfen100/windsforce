-- WINDSFORCE 博客数据库数据
-- version 1.0
-- http://www.doyouhaobaby.net
--
-- 开发: DianniuTeam
-- 网站: http://dianniu.net

--
-- 数据库: 博客初始化数据
--

-- --------------------------------------------------------

--
-- 转存表中的数据 `needforbug_app`
--

INSERT INTO `#@__app` (`app_id`, `app_identifier`, `app_name`, `app_version`, `app_description`, `app_url`, `app_email`, `app_author`, `app_authorurl`, `app_isadmin`, `app_isinstall`, `app_isuninstall`, `app_issystem`, `app_isappnav`, `app_status`) VALUES
(3, 'blog', '博客', '1.0', '博客应用', 'http://doyouhaobaby.net', 'admin@dianniu.net', 'Dianniu Team', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 转存表中的数据 `needforbug_nav`
--

INSERT INTO `#@__nav` (`nav_id`, `nav_parentid`, `nav_name`, `nav_identifier`, `nav_title`, `nav_url`, `nav_target`, `nav_type`, `nav_style`, `nav_location`, `nav_status`, `nav_sort`, `nav_color`, `nav_icon`) VALUES
(8, 0, '博客', 'app_blog', 'blog', 'blog://public/index', 0, 0, '', 0, 1, 0, 0, '');
