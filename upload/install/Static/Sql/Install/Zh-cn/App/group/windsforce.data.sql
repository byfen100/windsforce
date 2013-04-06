-- WINDSFORCE 群组数据库数据
-- version 1.0
-- http://www.windsforce.com
--
-- 开发: Windsforce TEAM
-- 网站: http://www.windsforce.com

--
-- 数据库: 群组初始化数据
--

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_app`
--

INSERT INTO `#@__app` (`app_id`, `app_identifier`, `app_name`, `app_version`, `app_description`, `app_url`, `app_email`, `app_author`, `app_authorurl`, `app_isadmin`, `app_isinstall`, `app_isuninstall`, `app_issystem`, `app_isappnav`, `app_status`) VALUES
(3,'group', '小组', '1.0', '群组应用', 'http://doyouhaobaby.net', 'admin@doyouhaobaby.net', 'WindsForce Team', 'http://doyouhaobaby.net', 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_groupoption`
--

INSERT INTO `#@__groupoption` (`groupoption_name`, `groupoption_value`) VALUES
('group_isaudit', '0'),
('group_icon_uploadfile_maxsize', '204800'),
('group_indextopicnum', '10'),
('group_listtopicnum', '10'),
('group_hottopic_date', '604800'),
('group_hottopic_num', '10'),
('group_thumbtopic_num', '5'),
('groupshow_newuser_num', '6'),
('group_listusernum', '15'),
('group_grouptopicside', '1'),
('group_grouptopicstyle', '1'),
('grouptopic_listcommentnum', '10'),
('grouptopic_hotnum', '10'),
('grouptopic_newnum', '10'),
('group_homepagestyle', '1'),
('index_recommendgroupnum', '10'),
('index_newgroupnum', '10'),
('index_hotgroupnum', '10'),
('index_groupleadernum', '6'),
('newtopic_hottagnum', '10'),
('group_grouplistnum', '24');

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_group`
--

INSERT INTO `#@__group` (`group_id`, `user_id`, `group_name`, `group_nikename`, `group_sort`, `group_description`, `group_listdescription`, `group_path`, `group_icon`, `group_totaltodaynum`, `group_topicnum`, `group_topictodaynum`, `group_usernum`, `group_topiccomment`, `group_topiccommenttodaynum`, `group_joinway`, `group_roleleader`, `group_roleadmin`, `group_roleuser`, `create_dateline`, `group_isrecommend`, `group_isopen`, `group_isaudit`, `group_ispost`, `group_status`, `group_latestcomment`, `update_dateline`) VALUES
(1, 1, 'default', '默认小组', 0, '这是系统一个默认的小组。', '测试小组，你可以修改', '', NULL, 0, 1, 0, 1, 0, 0, 0, '组长', '管理员', '成员', 1355499162, 1, 1, 1, 0, 1, 'a:5:{s:11:"commenttime";i:1355499282;s:9:"commentid";i:1;s:3:"tid";s:1:"1";s:13:"commentuserid";s:1:"1";s:12:"commenttitle";s:38:"Hello world! 欢迎使用WindsForce！";}', 1355499282);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_groupcategory`
--

INSERT INTO `#@__groupcategory` (`groupcategory_id`, `groupcategory_name`, `groupcategory_parentid`, `groupcategory_count`, `groupcategory_sort`, `update_dateline`, `create_dateline`) VALUES
(1, 'WindsForce', 0, 1, 0, 1355499162, 1355499102);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_groupcategoryindex`
--

INSERT INTO `#@__groupcategoryindex` (`group_id`, `groupcategory_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_grouptopic`
--

INSERT INTO `#@__grouptopic` (`grouptopic_id`, `grouptopiccategory_id`, `group_id`, `user_id`, `grouptopic_username`, `grouptopic_title`, `grouptopic_content`, `grouptopic_comments`, `grouptopic_views`, `grouptopic_loves`, `grouptopic_sticktopic`, `grouptopic_status`, `grouptopic_isclose`, `grouptopic_color`, `grouptopic_iscomment`, `grouptopic_addtodigest`, `grouptopic_isaudit`, `grouptopic_allownoticeauthor`, `grouptopic_ordertype`, `grouptopic_isanonymous`, `grouptopic_usesign`, `grouptopic_hiddenreplies`, `grouptopic_latestcomment`, `grouptopic_updateusername`, `create_dateline`, `update_dateline`, `grouptopic_thumb`, `grouptopic_isrecommend`, `grouptopic_onlycommentview`) VALUES
(1, 0, 1, 1, 'admin', 'Hello world! 欢迎使用WindsForce！', '欢迎大家使用我们的产品，祝你愉快！', 1, 1, 0, 0, 1, 0, '', 1, 0, 1, 1, 0, 0, 1, 0, 'a:4:{s:11:"commenttime";i:1355499282;s:9:"commentid";i:1;s:3:"tid";s:1:"1";s:13:"commentuserid";s:1:"1";}', '', 1355499238, 1365070162, 0, 0, 0);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_grouptopiccomment`
--

INSERT INTO `#@__grouptopiccomment` (`grouptopiccomment_id`, `grouptopic_id`, `user_id`, `grouptopiccomment_status`, `grouptopiccomment_name`, `grouptopiccomment_content`, `grouptopiccomment_email`, `grouptopiccomment_url`, `grouptopiccoment_ip`, `create_dateline`, `update_dateline`) VALUES
(1, 1, 1, 1, '', '我希望一切都是一个美好的开始！', '', '', '', 1355499282, 0);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_groupuser`
--

INSERT INTO `#@__groupuser` (`user_id`, `group_id`, `groupuser_isadmin`, `create_dateline`) VALUES
(1, 1, 0, 1355499183);

-- --------------------------------------------------------

--
-- 转存表中的数据 `windsforce_nav`
--

INSERT INTO `#@__nav` (`nav_id`, `nav_parentid`, `nav_name`, `nav_identifier`, `nav_title`, `nav_url`, `nav_target`, `nav_type`, `nav_style`, `nav_location`, `nav_status`, `nav_sort`, `nav_color`, `nav_icon`) VALUES
(10, 0, '小组', 'app_group', 'group', 'group://public/index', 0, 0, 'a:3:{i:0;i:0;i:1;i:0;i:2;i:0;}', 0, 1, 0, 0, '');
