<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   编辑帖子控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class EditController extends Controller{

	protected $_oGrouptopic=null;
	
	public function index(){
		$nTid=intval(G::getGpc('tid','G'));

		$oGrouptopic=GrouptopicModel::F('grouptopic_id=?',$nTid)->getOne();
		if(empty($oGrouptopic['grouptopic_id'])){
			$this->E(Dyhb::L('不存在你要编辑的主题','Controller/Grouptopic'));
		}

		try{
			// 验证小组权限
			Groupadmin_Extend::checkGroup($oGrouptopic['group_id'],true);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}

		if(!Groupadmin_Extend::checkTopicedit($oGrouptopic)){
			$this->E(Dyhb::L('你没有权限编辑帖子','Controller/Grouptopic'));
		}

		if(!Groupadmin_Extend::checkTopicedit($oGrouptopic)){
			$this->E(Dyhb::L('你没有权限编辑帖子','Controller/Grouptopic'));
		}

		$this->_oGrouptopic=$oGrouptopic;
	
		// 取得小组分类
		$oGrouptopiccategory=Dyhb::instance('GrouptopiccategoryModel');
		$arrGrouptopiccategorys=$oGrouptopiccategory->grouptopiccategoryByGroupid($oGrouptopic['group_id']);
		
		$this->assign('arrGrouptopiccategorys',$arrGrouptopiccategorys);

		// 获取帖子标签
		$sTag='';

		$arrTags=GrouptopictagindexModel::F('grouptopic_id=?',$nTid)->getAll();
		if(is_array($arrTags)){
			$arrTemptag=array();
			foreach($arrTags as $oTag){
				$arrTemptag[]=$oTag['grouptopictag_id'];
			}

			// 取得标签
			$arrWhere['grouptopictag_id']=array('in',$arrTemptag);
			$arrGrouptopictags=GrouptopictagModel::F($arrWhere)->all()->get();
			if(is_array($arrGrouptopictags)){
				foreach($arrGrouptopictags as $oGrouptopictag){
					$sTag.=','.$oGrouptopictag['grouptopictag_name'];
				}
			}

			$sTag=trim($sTag,',');
		}
		
		$this->assign('sTag',$sTag);

		$this->assign('oGroup',$oGrouptopic->group);

		// 取得用户是否加入了小组
		$this->get_groupuser($oGrouptopic['group_id']);

		$this->assign('oGrouptopic',$oGrouptopic);
		$this->assign('nGroupid',$oGrouptopic['group_id']);
		$this->assign('nDisplaySeccode',$GLOBALS['_option_']['seccode_publish_status']);

		$this->display('grouptopic+add');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function edit_title_(){
		return $this->_oGrouptopic['grouptopic_title'].' - '.Dyhb::L('帖子编辑','Controller/Grouptopic');
	}

	public function edit_keywords_(){
		return $this->edit_title_();
	}

	public function edit_description_(){
		return $this->edit_title_();
	}

}
