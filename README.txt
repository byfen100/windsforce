﻿
WindsForce 社区APP讨论小组 (WindsForce APP Framework)
---------------------------------
风之力APP开发框架 (To Make Prowerful APP.天神下凡APP)

------------------------------------------------------------------
介绍

当你准备架设一个社区网站的时候，你是否曾经苦恼使用Discuz或者PHPWIND架设的网站和网上的太多数论坛同质化而缺乏吸引力。

当你苦苦寻找其他替代软件，却发现一无所获，不是功能太弱，就是用户体验不足。


前言

2012年初，在编写DoYouHaoBaby框架目录结构文档的时候《DoYouHaoBaby框架目录》，我就在想如何开发一套软件，系统所有的功能都由一个个可以独立安装和卸载的APP应用构成，我么需要什么功能就开发一个应用APP，不想用了可以随时删除，目测和PHPWIND9.0的模式差不多。

于是2012年03月份开始开发WindsForce（早期名字叫做NeedForBug）,风之力APP开发框架，立足于社区。经过一年时间的开发，终于在2013年04月26日，WindsForce发布了第一个版本1.0，上线测试后，收不到不少反馈意见，经过一个多月的开发 WindsForce-1.1 终于以完善的功能再次于2013年06月04日发布。

WindsForce-1.1.1的程序架构

说到WindsForce的架构，我们这里不得不说的是一个强大的PHP开发框架（The DoYouHaoBaby PHP Framework），这个框架是我（也就是WindsForce的作者）在2010年下半年开始开发的，10份发布的第一个版本，当时我还在大二，兴趣爱好开发。到2012的时候，实质上这个框架已经有了一个念头，各种功能已经非常齐全了。


本来是属于找工作的一年，作为一个理想主义者，全职一年开发WindsForce（没有任何收入，花了数万），希望做一个让自己满意的东西。当然，最近也在找工作了。


（一）：功能APP化

为了满足不同网站运营者，我认为网站基础功能应该单一化，比如简单来说，我要做一个论坛，你就不要把门户，微博，电子商务啥的一堆东西塞给我。随着网站的需求越来越大

我需要微博，我就安装一个微博应用。当然了，系统的内置一个应用，用于承担系统的基础功能，用户注册登陆，上传附件，短消息，提醒，动态，修改资料，头像等等。


（二）：程序编码模式MVC

WindsForce 采用的是MVC的模块思想，让网站的功能直观化。

MVC是三个单词的缩写,分别为： 模型( Model ) ,视图( View )和控制 ( Controller ) 。 MVC模式的目的就是实现Web系统的职能分工。 Model层实现系统中的业务逻辑。 View层用于与用户的交互，通常用PHP来实现。 Controller层是Model与View之间沟通的桥梁，它可以分派用户的请求并选择恰当的视图以用于显示，同时它也可以解释用户的输入并将它们映射为模型层可执行的操作。

MVC是一个设计模式，它强制性的使应用程序的输入、处理和输出分开。使用MVC应用程序被分成三个核心部件：模型、视图、控制器。它们各自处理自己的任务。

（三）：强大的模板引擎支持

依托于DoYouHaoBaby强大的模板引擎，能够让网站的主题制作变得十分地轻松，模板引擎文档《模板指南索引》。


（四）：细化的APP技术细节处理

个人空间的实现方法，home应用拥有自己的空间，比如 http://doyouhaobaby.net/space/1.html，其它应用也拥有自己的空间，例如group的http://doyouhaobaby.net/app/group/space/1.html ，那么我们该如何串联他们呢？？？


我们在home空间中有一个新的模块用来访问具有个人空间的，在具体应用中有一个返回个人空间的链接来关联他们。

类似的设计有用户个人中心，搜索，WAP等等。


WindsForce-1.1.1 功能列表

WindsForce APP Framework（风之力APP开发框架）这是最牛B之处了。

#小组+新鲜事

#内置简体中文和繁体中文语言包，轻松实现国际化

#内置5套皮肤，网站想换就换，几步就可以制作自己的皮肤，并且支持导出哦。

#短消息功能，方便交友，办事

#提醒功能，随时随地提高会员互动

#feed动态功能

#wap手机

#内置强大的评论反垃圾信息策略

#在线统计

#数百项内置开发，轻松设置网站方式

#传统与流行并重的社区展现方式

#搜索功能

#留言板

#一致的附件对话框，想插哪儿就插哪儿

#RBAC角色权限系统

#编译型模板引擎

#公告

#幻灯片

#QQ和新浪微博登陆

#找回密码和用户申诉

#邮件

#数据库备份与恢复

#文件校验

#计划任务

#缓存系统

#在线配置修改

#框架几十项可设置配置

#登陆记录，操作记录，安全随时掌握

#友情链接

#LOGO和favicon图标设置

#两款后台皮肤，可以自己制作，另一个皮肤就是技术演示，掩饰等于解释


更多自己体会啦，不说了。

------------------------------------------------------------------

产品发布页
http://doyouhaobaby.net/app/group/topic/219.html

------------------------------------------------------------------

特别感谢 

A5网站源码 http://down.admin5.com/
华夏名网   http://sudu.cn

从2010年开始，为小牛哥Dyhb提供了三年的免费空间，本软件将第一时间发布在A5源码站，其他下载站3天后发布。

------------------------------------------------------------------

http://windsforce.com && http://windsforce.net
