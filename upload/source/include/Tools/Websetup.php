<?php
/**

 //  [Websetup!] 图像界面工具
 //  +---------------------------------------------------------------------
 //
 //  “Copyright”
 //  +---------------------------------------------------------------------
 //  | (C) 2010 - 2099 http://doyouhaobaby.net All rights reserved.
 //  | This is not a free software, use is subject to license terms
 //  +---------------------------------------------------------------------
 //
 //  “About This File”
 //  +---------------------------------------------------------------------
 //  | websetup 入口文件
 //  +---------------------------------------------------------------------

*/

/** 系统应用路径定义 */
define('WEBSETUP_PATH',getcwd());

define('APP_NAME','websetup'); // 项目名字
define('APP_PATH',dirname(__FILE__).'/Tools_/Websetup/websetup'); // 项目路径

/** 加载DoYouHaoBaby框架压缩版 */
define('DYHB_THIN',true);

/** 项目运行时路径及数据库表缓存路径 */
define('APP_RUNTIME_PATH',WEBSETUP_PATH.'/~Data/websetup');
define('DB_META_CACHED_PATH',WEBSETUP_PATH.'~/Data/websetup/cache_');

/** 项目模板路径定义 */
define('APP_TEMPLATE_PATH',WEBSETUP_PATH.'/Tools_/Websetup/websetup/Theme');
define('__THEMES__','Tools_/Websetup/websetup/Theme');

/** 加载框架 */
require('../DoYouHaoBaby/~DoYouHaoBaby.php');

/** 实例化框架并且初始化 */
App::RUN();
