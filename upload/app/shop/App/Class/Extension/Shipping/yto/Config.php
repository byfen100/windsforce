<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城圆通配送接口配置文件($)*/

!defined('DYHB_PATH') && exit;

return array(

	// 配送方式代码
	'shopshipping_code'=>'yto',

	// 配送方式名字
	'shopshipping_name'=>'圆通速递',

	// 配送方式版本
	'shopshipping_version'=>'1.0',

	// 配送方式描述
	'shopshipping_description'=>'上海圆通物流（速递）有限公司经过多年的网络快速发展，在中国速递行业中一直处于领先地位。为了能更好的发展国际快件市场，加快与国际市场的接轨，强化圆通的整体实力，圆通已在东南亚、欧美、中东、北美洲、非洲等许多城市运作国际快件业务',

	// 配送方式官方网站
	'shopshipping_website'=>'http://www.yto.net.cn',

	// 不支持保价
	'shopshipping_insure'=>'0',

	// 配送方式是否支持货到付款
	'shopshipping_iscod'=>'1',

	// 插件的作者
	'shopshipping_author'=>'Dianniu Team',

	// 插件作者的官方网站
	'shopshipping_authorurl'=>'http://dianniu.net',

	// 配送接口需要的参数 */
	'shopshipping_configure'=>array(
		array('name'=>'item_fee','title'=>'单件商品费用','value'=>10,'description'=>'单件商品的配送价格'),// 单件商品的配送价格
		array('name'=>'base_fee','title'=>'首重费用','value'=>5,'description'=>'1000克以内的价格'),// 1000克以内的价格
		array('name'=>'step_fee','title'=>'续重费用','value'=>5,'description'=>'续重每1000克增加的价格'),// 续重每1000克增加的价格
	),

);
