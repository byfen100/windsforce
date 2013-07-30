<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   安装用户控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class ServiceinstallController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['serviceinstall_domain']=array('like','%'.G::getGpc('serviceinstall_domain').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('serviceinstall',false);

		$this->display(Admin_Extend::template('service','serviceinstall/index'));
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		parent::foreverdelete('serviceinstall',$sId);
	}

}
