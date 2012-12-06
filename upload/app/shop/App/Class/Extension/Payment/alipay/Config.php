<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   商城支付宝支付接口配置文件($)*/

!defined('DYHB_PATH') && exit;

return array(
	// 支付代码，英文唯一值
	'shoppayment_code'=>'alipay',

	// 支付名字
	'shoppayment_name'=>'支付宝',

	// 支付描述
	'shoppayment_description'=>'支付宝网站(www.alipay.com) 是国内先进的网上支付平台。<br/>支付宝收款接口：在线即可开通，<font color="red"><b>零预付，免年费</b></font>，单笔阶梯费率，无流量限制。<br/><a href="http://needforbug.dianniu.net/payment.php?type=alipay" target="_blank"><strong>立即在线申请</strong></a>',

	// 产品官方网址
	'shoppayment_website'=>'http://www.alipay.com',

	// 是否支持货到付款
	'shoppayment_iscod'=>'0',

	// 是否支持在线支付
	'shoppayment_isonline'=>'1',

	// 作者
	'shoppayment_author'=>'Dianniu Team',

	// 作者URL
	'shoppayment_authorurl'=>'http://dianniu.net',

	// 版本号
	'shoppayment_version'=>'1.0',

	// 配置信息
	'shoppayment_config'=>array(
		array('name'=>'alipay_account','title'=>'支付宝帐户','type'=>'text','value'=>''),
		array('name'=>'alipay_key','title'=>'交易安全校验码','type'=>'text','value'=>''),
		array('name'=>'alipay_partner','title'=>'合作者身份ID','type'=>'text','value'=>''),
		//array('name'=>'alipay_real_method','type'=>'select','value'=>'0'),
		//array('name'=>'alipay_virtual_method','type'=>'select','value'=>'0'),
		//array('name'=>'is_instant','type'=>'select','value'=>'0'),
		array('name'=>'alipay_pay_method','title'=>'选择接口类型','description'=>'请选择您最后一次跟支付宝签订的协议里面说明的接口类型','type'=>'select','value'=>array(),'inputoption'=>array(0=>'使用标准双接口',1=>'使用担保交易接口',2=>'使用即时到帐交易接口')),
	),

);
