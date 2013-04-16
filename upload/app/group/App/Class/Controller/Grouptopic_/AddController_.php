<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	protected $_oGroup=null;
	
	public function index(){
		$nGroupid=intval(G::getGpc('gid','G'));

		// 快捷发贴
		if(empty($nGroupid)){
			$oGroup=Dyhb::instance('GroupModel');
			$arrGroups=$oGroup->groupbyUserid($GLOBALS['___login___']['user_id']);

			if(!is_array($arrGroups)){
				$this->E(Dyhb::L('用户尚未加入任何小组','Controller/Grouptopic'));
			}
			
			$this->assign('arrGroups',$arrGroups);
		}else{
			// 访问权限
			$oGroup=GroupModel::F('group_id=? AND group_status=1 AND group_isaudit=1',$nGroupid)->getOne();
			if(empty($oGroup['group_id'])){
				$this->E(Dyhb::L('小组不存在或者还在审核中','Controller/Grouptopic'));
			}

			$this->_oGroup=$oGroup;
			
			$this->assign('oGroup',$oGroup);

			// 取得用户是否加入了小组
			$this->get_groupuser($oGroup['group_id']);

			if($oGroup->group_isopen==0){
				$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$nGroupid)->getOne();
				if(empty($oGroupuser['user_id'])){
					$this->E(Dyhb::L('只有该小组成员才能够访问小组','Controller/Grouptopic'));
				}
			}

			// 发贴权限
			if($oGroup->group_ispost==0){
				$oGroupuser=GroupuserModel::F('user_id=? AND group_id=?',$GLOBALS['___login___']['user_id'],$nGroupid)->getOne();
				if(empty($oGroupuser['user_id'])){
					$this->E(Dyhb::L('只有该小组成员才能发帖','Controller/Grouptopic').'&nbsp;<span id="listgroup_'.$oGroup['group_id'].'" class="commonjoinleave_group"><a href="javascript:void(0);" onclick="joinGroup('.$oGroup['group_id'].',\'listgroup_'.$oGroup['group_id'].'\');">'.Dyhb::L('我要加入','Controller/Group').'</a></span>');
				}
			}elseif($oGroup->group_ispost==1){
				$this->E(Dyhb::L('该小组目前拒绝任何人发帖','Controller/Grouptopic'));
			}
		}
		
		// 如果不是在某个小组发贴，读取一个小组
		$nLabel=0;
		if(empty($nGroupid) && isset($arrGroups[0])){
			$nGroupid=$arrGroups[0]->group_id;
			$nLabel=1;
		}

		// 小组分类
		$arrGrouptopiccategorys=array();
		$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
		$arrGrouptopiccategorys=$oGrouptopiccategory->grouptopiccategoryByGroupid($nGroupid);
		
		if($nLabel==1){
			$nGroupid='';
		}

		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);
		$this->assign('nGroupid',$nGroupid);

		$this->display('grouptopic+add');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function add_title_(){
		return Dyhb::L('发布帖子','Controller/Grouptopic').($this->_oGroup?' - '.$this->_oGroup['group_nikename']:'');
	}

	public function add_keywords_(){
		return $this->add_title_();
	}

	public function add_description_(){
		return $this->add_title_();
	}

}
