<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   System default config($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

return array(
	// 数据库相关
	'DB_PASSWORD'=>'123456',
	'DB_PREFIX'=>'windsforce_',
	'DB_NAME'=>'windsforce',
	'DB_CACHE_FIELDS'=>TRUE,
	'DB_CACHE'=>TRUE,
	'DB_CACHE_TIME'=>86400000000,

	// 系统调试
	'APP_DEBUG'=>TRUE,
	'SHOW_RUN_TIME'=>TRUE,
	'SHOW_DB_TIMES'=>TRUE,
	'SHOW_GZIP_STATUS'=>TRUE,

	// 重要前缀
	'RBAC_DATA_PREFIX'=>'rbac_data_prefix_windsforce_',
	'COOKIE_PREFIX'=>'windsforce_',

	// RBAC
	'RBAC_ROLE_TABLE'=>'role',
	'RBAC_USERROLE_TABLE'=>'userrole',
	'RBAC_ACCESS_TABLE'=>'access',
	'RBAC_NODE_TABLE'=>'node',
	'USER_AUTH_ON'=>TRUE,
	'USER_AUTH_TYPE'=>1,
	'USER_AUTH_KEY'=>'auth_id',
	'ADMIN_USERID'=>'1',
	'ADMIN_AUTH_KEY'=>'administrator',
	'USER_AUTH_MODEL'=>'user',
	'AUTH_PWD_ENCODER'=>'md5',
	'USER_AUTH_GATEWAY'=>'home://public/login',
	'NOT_AUTH_MODULE'=>'public,api,space,misc',
	'REQUIRE_AUTH_MODULE'=>'',
	'NOT_AUTH_ACTION'=>'',
	'REQUIRE_AUTH_ACTION'=>'',
	'GUEST_AUTH_ON'=>true,
	'GUEST_AUTH_ID'=>'-1',
	'RBAC_ERROR_PAGE'=>'home://public/rbacerror',
	'RBAC_GUEST_ACCESS'=>array(
		/* home应用 */
		'home@stat@*'=>true,
		'home@apps@*'=>true,
		'home@online@*'=>true,
		'home@getpassword@*'=>true,
		'home@userappeal@*'=>true,
		'home@attachmentdownload@*'=>true,
		'home@attachmentread@*'=>true,
		'home@attachment@*'=>true,
		'home@attachment@show'=>false,
		'home@attachment@add'=>false,
		'home@attachment@normal_upload'=>false,
		'home@attachment@flash_upload'=>false,
		'home@attachment@add_attachmentcomment'=>false,
		'home@homesite@*'=>true,
		'home@homehelp@*'=>true,

		/* group应用 */
		'group@tag@*'=>true,
		'group@group@joingroup'=>true,
		'group@group@leavegroup'=>true,
		'group@group@getcategory'=>true,
		'group@grouptopic@set_grouptopicstyle'=>true,
		'group@grouptopic@set_grouptopicside'=>true,

		/* 其它应用请到各自的配置文件设定 */
	 ),
	'RBAC_USER_ACCESS'=>array(
		/* home应用 */
		'home@spaceadmin@*'=>true,
		'home@spaceadmin@transfer'=>false,
		'home@spaceadmin@dotransfer'=>false,
		'home@pm@*'=>true,
		'home@notice@*'=>true,
		'home@friend@*'=>true,
		'home@ucenter@index'=>true,
		'home@ucenter@homefreshtopic'=>true,
		'home@ucenter@audit_homefreshcomment'=>true,
		'home@ucenter@feed'=>true,
		'home@ucenter@tag'=>true,
		'home@ucenter@tags'=>true,

		/* group应用 */


		/* 其它应用请到各自的配置文件设定 */
	 ),

	// 时区
	'TIME_ZONE'=>'Asia/Shanghai',
	
	// 开启注释版模板标签风格
	'TEMPLATE_TAG_NOTE'=>TRUE,

	// 开发者中心
	'APP_DEVELOP'=>0,// 是否开启后台应用设计，仅应用开发者设置为1

	// 模板设置
	'FRONT_TPL_DIR'=>'Default',
	'ADMIN_TPL_DIR'=>'Default',
	'CACHE_LIFE_TIME'=>8640000,
	'BOOTSTRAP_ON'=>true,// 是否启用BOOTSTRAP界面库，如果你的应用不需要它，请在应用配置重写此值
	'BOOTSTRAP_VERSION'=>'2.0.3',// 界面库版本

	// 禁止空模块直接加载视图
	'NOT_ALLOWED_EMPTYCONTROL_VIEW'=>TRUE,

	// 语言包和模板COOKIE是否包含应用名字
	'COOKIE_LANG_TEMPLATE_INCLUDE_APPNAME'=>FALSE,

	// 语言包设置
	'FRONT_LANGUAGE_DIR'=>'Zh-cn',
	'ADMIN_LANGUAGE_DIR'=>'Zh-cn',
	'LANG_SWITCH'=>TRUE,//前台专用，后台自动重写为TRUE

	// 默认应用
	'DEFAULT_APP'=>'home',

	// URL模式
	'URL_MODEL'=>1,

	// 网址加上域名
	'URL_DOMAIN'=>'',

	// 计划任务 && 计划任务跟系统功能紧密相关，如没有必要请不要关闭
	'CRON_ON'=>true,
);
