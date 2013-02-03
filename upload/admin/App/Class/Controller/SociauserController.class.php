<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   社会化用户绑定控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入绑定数据模型 */
if(!Dyhb::classExists('SociauserModel')){
	require_once(WINDSFORCE_PATH.'/source/extension/socialization/lib/mvc/SociauserModel.class.php');
}

class SociauserController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['sociauser_name']=array('like',"%".G::getGpc('sociauser_name')."%");
	}

	public function add(){
		$this->E(Dyhb::L('后台无法直接添加绑定数据','Controller/Sociauser'));
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

}
