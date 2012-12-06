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
 //  | websetup 简化版模型代码模板
 //  +---------------------------------------------------------------------

*/

!defined('DYHB_PATH') && exit;

return "
	/**
	 * 返回对象的定义
	 *
	 * @access public
	 * @static
	 * @return array
	 */
	static public function init__(){
		return array(
			/**
			 * 指定该 ActiveRecord 要使用的行为插件
			 *
			 * @return string
			 */
			'behaviors'=>'rbac',

			/**
			 * 指定行为插件的配置
			 *
			 * @return array
			 */
			'behaviors_settings'=>array(
				// '插件名'=>array('选项'=>设置),
				'rbac'=>array(
				'password_prop'=>'user_password',
				'rbac_data_props'=>'user_id,user_name,user_lastlogintime,user_lastloginip,user_logincount,user_email,user_remark,create_dateline,user_registerip,update_dateline,user_status'
			),
		),

		/**
		 * 用什么数据表保存对象
		 *
		 * < 数据库表不带前缀 >
		 *
		 * @return string
		 */
		'table_name'=>'user',

		/**
		 * 指示使用哪一个表数据入口对象来负责对象属性值的存取工作
		 *
		 * <  这个值通常可以不设定，系统默认的数据库入口表为 DbTableRecordSet。
		 *    table_class 的值应该是一个类名称，例如 DbTableRecordSetUser。如果指定了这个设置，则自动忽略 table_name 设置。>
		 *
		 * @return string
		 */
		// 'table_class'=>'user',

		/**
		 * 数据库连接
		 *
		 * <  指示连接数据表时的设置，通常用于进行跨数据库访问或指定不同的数据表前缀。
		 *    table_config 的值应该是一个数组。
		 *    分部式服务器部署模式下为二维数组 。
		 *    普通模式下为一维数组 。
		 *    本项一般不用设置，遇到大型项目才需要设置 。>
		 *
		 * @return array
		 */
		 //'table_config' =>array(
		 //
		 // 'db_host'=>'localhost',
		 // 'db_port'=>3360,
		 // 'db_type'=>'mysql',
		 // 'db_user'=>'root',
		 // 'db_password'=>'',
		 // 'db_name'=>'',
		 // 'db_dsn'=>'',
		 // 'db_params'=>'',
		 // 'db_schema'=>'',
		 // 'db_prefix'=>'',
		 // 'connect'=>'',
		 // 'pk'=>'',
		 //),

		/**
		 * 指定数据表记录字段与对象属性之间的映射关系
		 *
		 * < 指示模型中各个属性、关联的设置
		 *   没有在这里指定设置的数据表字段自动成为模型的普通属性。
		 *   例如数据表中有 user_id、user_title 两个字段，而 props 中
		 *  没有指定 user_title 属性的设置，则 user_title 字段成为该模型的 user_title 属性，并且可以读写。>
		 *
		 * @return array
		 */
		'props'=>array(
			// 主键应该是只读，确保领域对象的“不变量”
			'user_id'=>array('readonly'=>true),
			// 可以在此添加其他属性的设置
			'user_name'=>array('readonly'=>true),
			// 添加对象间的关联
			// 'task'=>array(DB::HAS_MANY=>'TaskModel', 'target_key'=>'user_id'),
		),

		/**
		 * 自动捕获表单数据时可以通过映射，防止别人通过表单猜想数据库的设计
		 *
		 * @return array
		 */
		'post_map_field'=>array(
			// 表单名=>字段
			//'user_name'=>'myusername'
		),

		/**
		 * 允许使用 mass-assignment 方式赋值的属性
		 *
		 * < 指示在通过 new 方法构造对象时能够指定的属性值。这个设置是一个安全性设置。>
		 * < 如果指定了 attr_accessible，则忽略 attr_protected 的设置。>
		 * < 如：\$oUser= new UserModel(array('user_name'=>'小牛哥','user_password'=>'123456789'))
		 *   如果，我们设置了'attr_accessible'='user_name'的话，那么我们系统会抛出错误，因为user_name不允许通过new方法来实现，即构造函数传递进来数据。
		 *   一旦指定了 attr_accessible 设置，则只有指定的属性可以通过构造函数赋值，除以以外的属性都不行。
		 *   相对而言，attr_accessible 设置具有更高的安全性，可以避免因为忘记保护重要属性而导致的安全性问题。
		 *   不过一旦增加了属性，一定要记得修改 attr_accessible 设置，否则新增属性就没法通过构造函数赋值了。
		 *   支持多个字段，支持数组和字符串，实际的结果都会转化为数组 。
		 *   如 'user_name,user_password' or array('user_name','user_password')两者等效 >
		 *
		 * @return mixed
		 */
		'attr_accessible'=>'',

		/**
		 * 拒绝使用 mass-assignment 方式赋值的属性
		 *
		 * < 功能同上，如果上面是白名单的话，那么下面这个就是黑名单。
		 *   这里设置了属性，那么设置后的属性不能通过构造函数来赋值。 >
		 *
		 * @return mixed
		 */
		'attr_protected'=>'user_id',

		/**
		 * 指定在数据库中创建对象时，哪些属性的值不允许由外部提供
		 *
		 * < 在数据库中新建对象时，要忽略掉的属性。
		 *   有些时候，我们的数据库结构要求在创建新记录时不能指定某些字段的值，
		 *   这时就可以通过 create_reject 设置来指定要排除的属性（属性名会自动对应到字段名）。
		 *   这里指定的属性会在创建记录时被过滤掉，从而让数据库自行填充值 。
		 *   支持多个字段，支持数组和字符串，实际的结果都会转化为数组。
		 *   如 'user_name,user_password' or array('user_name','user_password')两者等效 >
		 *
		 * @return mixed
		 */
		'create_reject'=>'',

		/**
		 * 指定更新数据库中的对象时，哪些属性的值不允许由外部提供
		 *
		 * < 同上，只是一个为创建数据对象时，一个为更新数据对象时 >
		 *
		 * @return mixed
		 */
		'update_reject'=>'',

		/**
		 * 指定在数据库中创建或者对象时，哪些属性的值由下面指定的内容进行覆盖（自动填充）
		 *
		 * @return array
		 */
		'autofill'=>array(
			//array(填充字段,填充内容,填充条件,附加规则)
			//array('user_status',1),  // 新增的时候把user_statu字段设置为1
			//array('password','md5','create','function'/* args支持多参数 */), // 对password字段在新增的时候使md5函数处理
			// |__ 接上面的话 ___-> 字段为第一个参数，当然这里字段必须存在，额外参数作为回调方法的后续参数
			//array('user_name','getName','update','callback'), // 对name字段在新增的时候回调getName方法
		),

		/**
		 * 在保存对象时，会按照下面指定的验证规则进行验证。验证失败会发送错误，我们可以通过系统提供的方法拦截。
		 *
		 * < 除了在保存时自动验证，还可以通过对象的 模型::M()->check()方法对数组数据进行验证。
		 *   系统提供了50多个验证规则，所有验证规则可以在 DoYouHaoBaby 完全开发手册中 ~handbook\package\check\check.html中全部罗列出来。 >
		 *
		 * @return array
		 */
		'check'=>array(
			'user_name'=>array(
				array('not_empty', '用户名不能为空'),
				array('min_length', 5, '用户名不能少于 5 个字符'),
				'on_create'=>array(
					array('max_length', 15, '用户名不能超过 15 个字符'),
						array('alnum', '用户名只能由字母和数字组成'),
					)
				),
			),
		);
	}

	/**
	 * 开启一个查询，查找符合条件的对象或对象集合
	 *
	 * @static
	 * @return DbSelect
	 */
	static function F(){
		\$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs(\$arrArgs);
	}

	/**
	 * 返回当前 Model 类的元数据对象
	 *
	 * @static
	 * @return ModelMeta
	 */
	static function M(){
		return ModelMeta::instance(__CLASS__);
	}
";
