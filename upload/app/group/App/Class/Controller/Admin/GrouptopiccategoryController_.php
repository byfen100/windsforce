<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GrouptopiccategoryController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['grouptopiccategory_name']=array('like','%'.G::getGpc('grouptopiccategory_name').'%');
	}

	public function index($sModel=null,$bDisplay=true){
		parent::index('grouptopiccategory',false);

		$this->display(Admin_Extend::template('group','grouptopiccategory/index'));
	}

	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));

		parent::edit('grouptopiccategory',$nId,false);
		$this->display(Admin_Extend::template('group','grouptopiccategory/add'));
	}

	public function AInsertObject_($oModel){
		$oModel->safeInput();
	}

	public function update($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::update('grouptopiccategory',$nId);
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function foreverdelete($sModel=null,$sId=null){
		$sId=G::getGpc('value');

		parent::foreverdelete('grouptopiccategory',$sId);
	}
	
	public function aForeverdelete($sId){
		$sId=G::getGpc('value','G');

		$arrIds=explode(',',$sId);
		
		// 将帖子的分类设置为0
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				// 读取当前分类的所有帖子
				$arrGrouptopics=GrouptopicModel::F('grouptopiccategory_id=?',$nId)->getAll();
				if(is_array($arrGrouptopics)){
					foreach($arrGrouptopics as $oGrouptopic){
						$oGrouptopic->grouptopiccategory_id='0';
						$oGrouptopic->save(0,'update');
					
						if($oGrouptopic->isError()){
							$this->E($oGrouptopic->getErrorMessage());
						}
					}
				}
			}
		}
	}

	public function input_change_ajax($sName=null){
		parent::input_change_ajax('grouptopiccategory');
	}

}
