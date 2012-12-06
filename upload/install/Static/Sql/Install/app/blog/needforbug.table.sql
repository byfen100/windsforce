-- NEEDFORBUG 博客数据库表
-- version 1.0
-- http://www.doyouhaobaby.net
--
-- 开发: DianniuTeam
-- 网站: http://dianniu.net

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 博客数据库: `needforbug`
--

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_blog`
--

DROP TABLE IF EXISTS `#@__blog`;
CREATE TABLE `#@__blog` (
  `blog_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `blog_title` varchar(300) NOT NULL DEFAULT '' COMMENT '标题',
  `blog_dateline` int(10) NOT NULL COMMENT '发布时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `blog_content` longtext NOT NULL COMMENT '正文',
  `blog_from` varchar(25) NOT NULL COMMENT '来源',
  `blog_fromurl` varchar(300) NOT NULL COMMENT '来源URL',
  `blog_urlname` varchar(300) NOT NULL COMMENT '别名，用于URL优化',
  `user_id` int(10) NOT NULL DEFAULT '-1' COMMENT '用户ID',
  `blogcategory_id` int(10) NOT NULL DEFAULT '-1' COMMENT '分类ID',
  `blog_thumb` varchar(300) NOT NULL COMMENT '缩略图，可以为一个完整的图片URL，或者系统的图片附件ID',
  `blog_views` tinyint(6) unsigned NOT NULL DEFAULT '1' COMMENT '点击量',
  `blog_uploads` tinyint(6) NOT NULL DEFAULT '0' COMMENT '附件数量',
  `blog_password` varchar(50) NOT NULL COMMENT '密码',
  `blog_istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶',
  `blog_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `blog_islock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否锁定',
  `blog_ispage` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为页面',
  `blog_trackbacks` tinyint(6) NOT NULL DEFAULT '0' COMMENT '引用数量',
  `blog_allowedtrackback` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否允许引用',
  `blog_comments` tinyint(6) NOT NULL DEFAULT '0' COMMENT '评论数量',
  `blog_goods` tinyint(6) NOT NULL DEFAULT '0' COMMENT '好评数量',
  `blog_bads` tinyint(6) NOT NULL DEFAULT '0' COMMENT '差评数量',
  `blog_ismobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为手机发布',
  `blog_keyword` varchar(300) NOT NULL COMMENT 'SEO关键字',
  `blog_description` varchar(300) NOT NULL COMMENT 'SEO描述',
  `blog_excerpt` text NOT NULL COMMENT '摘要',
  `blog_gotourl` varchar(300) NOT NULL COMMENT '外部URL',
  `blog_isblank` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新窗口打开',
  `blog_ip` varchar(40) NOT NULL COMMENT '发表日志IP',
  `blog_color` char(7) NOT NULL COMMENT '标题颜色',
  `blog_type` set('c','h','p','f','s','a','b') NOT NULL COMMENT '文章类型：头条[h]推荐[c]幻灯[f]特荐[a]滚动[s]加粗[b]图片[p]',
  PRIMARY KEY (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `blog_dateline` (`blog_dateline`),
  KEY `update_dateline` (`update_dateline`),
  KEY `blog_type` (`blog_type`),
  KEY `blog_istop` (`blog_istop`),
  KEY `blog_islock` (`blog_islock`),
  KEY `blog_ispage` (`blog_ispage`),
  KEY `blogcategory_id` (`blogcategory_id`),
  KEY `blog_status` (`blog_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_blogcategory`
--

DROP TABLE IF EXISTS `#@__blogcategory`;
CREATE TABLE `#@__blogcategory` (
  `blogcategory_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志分类ID',
  `blogcategory_name` varchar(50) NOT NULL COMMENT '分类名字',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `blogcategory_urlname` varchar(50) NOT NULL COMMENT '分类别名',
  `blogcategory_compositor` tinyint(6) NOT NULL DEFAULT '0' COMMENT '分类排序',
  `blogcategory_logo` varchar(300) NOT NULL COMMENT '分类小Logo，又名ICON',
  `blogcategory_parentid` int(10) NOT NULL DEFAULT '0' COMMENT '分类父级ID',
  `blogcategory_keyword` varchar(300) NOT NULL COMMENT '分类SEO关键字',
  `blogcategory_description` varchar(300) NOT NULL COMMENT '分类SEO描述',
  `blogcategory_introduce` varchar(300) NOT NULL COMMENT '分类介绍',
  `blogcategory_extra` text NOT NULL COMMENT '分类扩展设置',
  `blogcategory_blogs` tinyint(6) NOT NULL COMMENT '日志数量',
  `blogcategory_comments` tinyint(6) NOT NULL COMMENT '评论数量',
  `blogcategory_gotourl` varchar(300) NOT NULL COMMENT '外部衔接',
  `blogcategory_color` char(7) NOT NULL COMMENT '板块颜色',
  `blogcategory_template` varchar(20) NOT NULL COMMENT '分类主题',
  PRIMARY KEY (`blogcategory_id`),
  KEY `blogcategory_parentid` (`blogcategory_parentid`),
  KEY `create_dateline` (`create_dateline`),
  KEY `update_dateline` (`update_dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_blogcomment`
--

DROP TABLE IF EXISTS `#@__blogcomment`;
CREATE TABLE `#@__blogcomment` (
  `blogcomment_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `create_dateline` int(10) NOT NULL COMMENT '创建时间',
  `update_dateline` int(10) NOT NULL COMMENT '更新时间',
  `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户ID，在线用户评论',
  `blogcomment_name` varchar(25) NOT NULL COMMENT '名字',
  `blogcomment_content` text NOT NULL COMMENT '内容',
  `blogcomment_email` varchar(300) NOT NULL COMMENT '邮件',
  `blogcomment_url` varchar(300) NOT NULL COMMENT 'URL',
  `blogcomment_isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `blogcomment_ip` varchar(16) NOT NULL COMMENT 'IP',
  `blogcomment_parentid` int(10) NOT NULL DEFAULT '0' COMMENT '父级ID',
  `blogcomment_isreplymail` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否邮件通知，通知给评论者',
  `blogcomment_ismobile` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为手机评论',
  `blog_id` int(10) NOT NULL COMMENT '关联类型的值，如日志ID，心情ID等',
  PRIMARY KEY (`blogcomment_id`),
  KEY `comment_isshow` (`blogcomment_isshow`),
  KEY `comment_relationtype` (`blog_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `needforbug_blogoption`
--

DROP TABLE IF EXISTS `#@__blogoption`;
CREATE TABLE `#@__blogoption` (
  `blogoption_name` varchar(32) NOT NULL DEFAULT '' COMMENT '名字',
  `blogoption_value` text NOT NULL COMMENT '值',
  PRIMARY KEY (`blogoption_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
