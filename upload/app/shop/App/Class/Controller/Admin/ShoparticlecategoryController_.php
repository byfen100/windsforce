<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   商城文章分类控制器($)*/

!defined('DYHB_PATH') && exit;

class ShoparticlecategoryController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['shoparticlecategory_name']=array('like',"%".G::getGpc('shoparticlecategory_name')."%");
	}
	
	public function index($sModel=null,$bDisplay=true){
		parent::index('shoparticlecategory',false);
		
		$this->display(Admin_Extend::template('shop','shoparticlecategory/index'));
	}
	
	public function add(){
		$this->get_shoparticlecategorytree_();
		$this->display(Admin_Extend::template('shop','shoparticlecategory/add'));
	}
	
	public function get_shoparticlecategorytree_(){
		$oShoparticlecategory=Dyhb::instance('ShoparticlecategoryModel');
		$oShoparticlecategoryTree=$oShoparticlecategory->getShoparticlecategoryTree();

		$this->assign('oShoparticlecategoryTree',$oShoparticlecategoryTree);
	}
	
	public function insert($sModel=null,$nId=null){
		$nId=G::getGpc('value');
		
		parent::insert('shoparticlecategory',$nId);
	}
	
	public function AInsertObject_($oModel){
		$nId=intval(G::getGpc('shoparticlecategory_parentid'));
		
		if(!empty($nId)){
			$oArticalecategory=ShoparticlecategoryModel::F('shoparticlecategory_id=?',$nId)->getOne();
			if(!empty($oArticalecategory->shoparticlecategory_id)){
				$oModel->shoparticlecategory_type=$oArticalecategory->shoparticlecategory_type;
			}else{
				$oModel->shoparticlecategory_type=1;/*默认为普通分类*/
			}
		}
	}
	public function edit($sMode=null,$nId=null,$bDidplay=true){
		$nId=intval(G::getGpc('value','G'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('请选择你要编辑的分类','__APPSHOP_COMMON_LANG__@Controller'));
		}

		$this->get_shoparticlecategorytree_();
		
		parent::edit('shoparticlecategory',$nId,false);
		$this->display(Admin_Extend::template('shop','shoparticlecategory/add'));
	}

	public function update($sModel = NULL, $nId = NULL){
		$nId=intval(G::getGpc('value'));
		
		if(empty($nId)){
			$this->E(Dyhb::L('请选择你要更新的分类','__APPSHOP_COMMON_LANG__@Controller'));
		}

		parent::update('shoparticlecategory',$nId);
	}
	
	public function foreverdelete($sModel=null,$sId=null){
		$nId=G::getGpc('value');

		if(empty($nId)){
			$this->E(Dyhb::L('请选择你要删除的分类','__APPSHOP_COMMON_LANG__@Controller'));
		}else if($nId>0 && $nId<=6){
			$this->E(Dyhb::L('无法删除系统保留分类','__APPSHOP_COMMON_LANG__@Controller'));
		}

		parent::foreverdelete('shoparticlecategory',$nId);
	}

}
