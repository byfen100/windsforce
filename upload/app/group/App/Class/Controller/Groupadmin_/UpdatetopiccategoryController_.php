<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   帖子分类编辑控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdatetopiccategoryController extends Controller{

	protected $_oGrouptopiccategory=null;

	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid'));
		$nGrouptopiccategoryid=intval(G::getGpc('cid'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}
		
		$oGrouptopiccategory=GrouptopiccategoryModel::F('grouptopiccategory_id=? AND group_id=?',$nGrouptopiccategoryid,$oGroup['group_id'])->query();
		if(empty($oGrouptopiccategory['grouptopiccategory_id'])){
			$this->E(Dyhb::L('你编辑的帖子分类不存在','Controller/Groupadmin'));
		}

		$this->_oGrouptopiccategory=$oGrouptopiccategory;

		// 取得用户是否加入了小组
		$this->get_groupuser($oGroup['group_id']);

		$this->assign('oGroup',$oGroup);
		$this->assign('oGrouptopiccategory',$oGrouptopiccategory);

		$this->display('groupadmin+updatetopiccategory');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function updatetopiccategory_title_(){
		return Dyhb::L('小组分类编辑','Controller/Groupadmin').' - '.$this->_oGrouptopiccategory['grouptopiccategory_name'];
	}

	public function updatetopiccategory_keywords_(){
		return $this->updatetopiccategory_title_();
	}

	public function updatetopiccategory_description_(){
		return $this->updatetopiccategory_title_();
	}

}