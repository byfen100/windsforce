<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   全站缓存更新控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入Home模型 */
Dyhb::import(WINDSFORCE_PATH.'/app/home/App/Class/Model');

/** 定义Home的语言包 */
define('__APP_ADMIN_LANG__',WINDSFORCE_PATH.'/app/home/App/Lang/Admin');

/** 导入缓存组件 */
require_once(Core_Extend::includeFile('function/Cache_Extend'));

class GlobalcacheController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$this->display();
	}

	public function cache(){
		$arrType=G::getGpc('type','P');

		if(empty($arrType)){
			$this->E('你没有选择更新任何缓存');
		}

		// 数据缓存
		if(in_array('data',$arrType)){
			Cache_Extend::updateCache('',array('style'));
		}

		// 模板缓存
		if(in_array('template',$arrType)){
			// 更新主题缓存
			Cache_Extend::updateCache('style');

			// 清理模板缓存
			if(is_dir(WINDSFORCE_PATH.'/data/~runtime/app')){
				Core_Extend::removeDir(WINDSFORCE_PATH.'/data/~runtime/app');
			}
		}

		// 数据库字段
		if(in_array('field',$arrType)){
			if(is_dir(WINDSFORCE_PATH.'/data/~runtime/cache_/field')){
				Core_Extend::removeDir(WINDSFORCE_PATH.'/data/~runtime/cache_/field');
			}
		}

		$this->S('更新缓存成功');
	}

}
