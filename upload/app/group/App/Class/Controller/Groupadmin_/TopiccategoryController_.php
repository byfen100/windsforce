<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组帖子分类设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class TopiccategoryController extends Controller{

	protected $_oGroup=null;

	public function index(){
		// 获取参数
		$sId=trim(G::getGpc('gid','G'));

		if(Core_Extend::isPostInt($sId)){
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}else{
			$oGroup=GroupModel::F('group_name=? AND group_status=1 AND group_isaudit=1',$sId)->getOne();
		}

		if(empty($oGroup['group_id'])){
			$this->E(Dyhb::L('小组不存在或在审核中','Controller'));
		}

		$this->_oGroup=$oGroup;

		// 取得用户是否加入了小组
		$this->get_groupuser($oGroup['group_id']);

		$this->assign('oGroup',$oGroup);

		// 取得小组帖子分类
		$arrGrouptopiccategorys=GrouptopiccategoryModel::F('group_id=?',$oGroup['group_id'])->order('grouptopiccategory_sort ASC')->getAll();
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);

		$this->display('groupadmin+topiccategory');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function topiccategory_title_(){
		return Dyhb::L('小组帖子分类设置','Controller').' - '.$this->_oGroup['group_nikename'];
	}

	public function topiccategory_keywords_(){
		return $this->topiccategory_title_();
	}

	public function topiccategory_description_(){
		return $this->topiccategory_title_();
	}

}