<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   小组头部背景设置控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class HeaderbgController extends Controller{

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
			$this->E(Dyhb::L('小组不存在或在审核中','Controller/Group'));
		}

		$this->_oGroup=$oGroup;

		// 取得用户是否加入了小组
		$this->get_groupuser($oGroup['group_id']);

		$this->assign('oGroup',$oGroup);

		// 读取系统背景
		$arrSystembgs=G::listDir(WINDSFORCE_PATH.'/app/group/Static/Images/groupbg',false,true);

		// 取得当前背景
		$sGroupHeaderbg=Group_Extend::getGroupHeaderbg($oGroup['group_headerbg']);
		$this->assign('sGroupHeaderbg',$sGroupHeaderbg);
			
		$arrOptionData=$GLOBALS['_cache_']['group_option'];
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['group_headbg_uploadfile_maxsize']));

		$this->display('groupadmin+headerbg');
	}

	protected function get_groupuser($nGroupid){
		$nGroupuser=Groupadmin_Extend::getGroupuser($nGroupid);

		$this->assign('nGroupuser',$nGroupuser);
	}

	public function headerbg_title_(){
		return Dyhb::L('头部背景设置','Controller/Groupadmin').' - '.$this->_oGroup['group_nikename'];
	}

	public function headerbg_keywords_(){
		return $this->headerbg_title_();
	}

	public function headerbg_description_(){
		return $this->headerbg_title_();
	}

}