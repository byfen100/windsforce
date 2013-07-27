<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组图标设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IconController extends Controller{

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
			
		// 取得ICON
		$sGroupIcon=Group_Extend::getGroupIcon($oGroup,true);
		$this->assign('sGroupIcon',$sGroupIcon);

		$arrOptionData=$GLOBALS['_cache_']['group_option'];
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['group_icon_uploadfile_maxsize']));

		$this->display('groupadmin+icon');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function icon_title_(){
		return Dyhb::L('小组图标设置','Controller').' - '.$this->_oGroup['group_nikename'];
	}

	public function icon_keywords_(){
		return $this->icon_title_();
	}

	public function icon_description_(){
		return $this->icon_title_();
	}

}