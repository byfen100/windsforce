<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   新鲜事话题控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HomefreshtagController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['homefreshtag_name']=array('like','%'.G::getGpc('homefreshtag_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('homefreshtag',false);

		$this->display(Admin_Extend::template('home','homefreshtag/index'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$this->E(Dyhb::L('新鲜事话题不允许被编辑','__APPHOME_COMMON_LANG__@Controller'));
	}
	
	public function add(){
		$this->E(Dyhb::L('不允许添加新鲜事话题','__APPHOME_COMMON_LANG__@Controller'));
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		parent::foreverdelete('homefreshtag',$sId);
	}

	public function forbid($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::forbid('homefreshtag',$nId,true);
	}

	public function resume($sModel=null,$sId=null,$bApp=false){
		$nId=G::getGpc('value');

		parent::resume('homefreshtag',$nId,true);
	}

}
