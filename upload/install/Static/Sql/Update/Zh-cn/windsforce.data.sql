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

DELETE FROM `#@__node` WHERE `node_id` = 92;

INSERT INTO `#@__node` (`node_id`, `node_name`, `node_title`, `node_status`, `node_remark`, `node_sort`, `node_parentid`, `node_level`, `nodegroup_id`, `create_dateline`, `update_dateline`, `node_issystem`) VALUES
(92, 'admin@announcement', '网站公告', 1, '', 10, 1, 2, 6, 1370012613, 1370012720, 1);

-- --------------------------------------------------------

--
-- 添加表中的数据 `windsforce_option`
--

DELETE FROM `#@__option` WHERE `option_name` = 'wap_on';
DELETE FROM `#@__option` WHERE `option_name` = 'wap_close_reason';
DELETE FROM `#@__option` WHERE `option_name` = 'wap_baselist_num';
DELETE FROM `#@__option` WHERE `option_name` = 'wap_mobile_only';
DELETE FROM `#@__option` WHERE `option_name` = 'wap_computer_on';
DELETE FROM `#@__option` WHERE `option_name` = 'search_fulltext';
DELETE FROM `#@__option` WHERE `option_name` = 'only_login_viewsite';
DELETE FROM `#@__option` WHERE `option_name` = 'stat_header_code';
DELETE FROM `#@__option` WHERE `option_name` = 'wap_img_size'
DELETE FROM `#@__option` WHERE `option_name` = 'share_code';
DELETE FROM `#@__option` WHERE `option_name` = 'share_on';
DELETE FROM `#@__option` WHERE `option_name` = 'feed_keep_time';
DELETE FROM `#@__option` WHERE `option_name` = 'notice_keep_time';

INSERT INTO `#@__option` (`option_name`, `option_value`) VALUES
('wap_on', '1'),
('wap_close_reason', 'update...'),
('wap_baselist_num', '10'),
('wap_mobile_only', '0'),
('wap_computer_on', '0'),
('search_fulltext', '0'),
('only_login_viewsite', '0'),
('stat_header_code',  ''),
('wap_img_size',  '100|80'),
('share_code', '<!-- Baidu Button BEGIN -->\r\n<div id="bdshare" class="bdshare_t bds_tools_32 get-codes-bdshare">\r\n<a class="bds_qzone"></a>\r\n<a class="bds_tsina"></a>\r\n<a class="bds_tqq"></a>\r\n<a class="bds_renren"></a>\r\n<a class="bds_t163"></a>\r\n<span class="bds_more"></span>\r\n<a class="shareCount"></a>\r\n</div>\r\n<script type="text/javascript" id="bdshare_js" data="type=tools&uid=0" ></script>\r\n<script type="text/javascript" id="bdshell_js"></script>\r\n<script type="text/javascript">\r\ndocument.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)\r\n</script>\r\n<!-- Baidu Button END -->'),
('share_on', '1'),
('feed_keep_time', '31536000'),
('notice_keep_time', '31536000');

-- --------------------------------------------------------

--
-- 添加表中的数据 `windsforce_groupoption`
--

DELETE FROM `#@__groupoption` WHERE `groupoption` = 'allowed_creategroup';
DELETE FROM `#@__groupoption` WHERE `groupoption` = 'grouptopic_lovenum';
DELETE FROM `#@__groupoption` WHERE `groupoption` = 'group_ucenter_listtopicnum';

INSERT INTO `#@__groupoption` (`groupoption_name`, `groupoption_value`) VALUES
('allowed_creategroup', '0'),
('grouptopic_lovenum', '10'),
('group_ucenter_listtopicnum', '20');
