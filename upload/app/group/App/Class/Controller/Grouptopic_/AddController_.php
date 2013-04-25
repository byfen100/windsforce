<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   添加帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	protected $_oGroup=null;
	
	public function index(){
		try{
			Core_Extend::checkSpam();
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
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

			try{
				// 验证小组权限
				Groupadmin_Extend::checkGroup($oGroup,true);
			}catch(Exception $e){
				$this->E($e->getMessage());
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
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_publish_status']);

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
