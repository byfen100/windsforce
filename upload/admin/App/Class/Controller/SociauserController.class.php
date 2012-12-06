<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   社会化用户绑定控制器($)*/

!defined('DYHB_PATH') && exit;

/** 导入绑定数据模型 */
if(!Dyhb::classExists('SociauserModel')){
	require_once(NEEDFORBUG_PATH.'/source/extension/socialization/lib/mvc/SociauserModel.class.php');
}

class SociauserController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['sociauser_name']=array('like',"%".G::getGpc('sociauser_name')."%");
	}

	public function add(){
		$this->E('xx');
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

}
