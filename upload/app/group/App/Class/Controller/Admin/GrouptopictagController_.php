<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组标签控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopictagController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['grouptopictag_name']=array('like','%'.G::getGpc('grouptopic_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('grouptopictag',false);

		$this->display(Admin_Extend::template('group','grouptopictag/index'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$this->E(Dyhb::L('帖子标签不允许被编辑','__APPGROUP_COMMON_LANG__@Controller'));
	}
	
	public function add(){
		$this->E(Dyhb::L('不允许添加帖子标签','__APPGROUP_COMMON_LANG__@Controller'));
	}

	public function aForeverdelete($sId){
		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			// 清理标签索引
			$oGrouptopictagindexMeta=GrouptopictagindexModel::M();
			$oGrouptopictagindexMeta->deleteWhere(array('grouptopictag_id'=>$nId));

			if($oGrouptopictagindexMeta->isError()){
				$this->E($oGrouptopictagindexMeta->getErrorMessage());
			}
		}
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		parent::foreverdelete('grouptopictag',$sId);
	}

}
