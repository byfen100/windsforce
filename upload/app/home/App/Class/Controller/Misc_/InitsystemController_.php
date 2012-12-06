<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   系统安装程序初始化控制器($)*/

!defined('DYHB_PATH') && exit;

class InitsystemController extends Controller{

	public function index(){
		// 更新系统缓存
		require_once(Core_Extend::includeFile('function/Cache_Extend'));
		Cache_Extend::updateCache();
	}

}
