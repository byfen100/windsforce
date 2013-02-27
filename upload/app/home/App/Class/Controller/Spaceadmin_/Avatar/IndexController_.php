<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   头像上传界面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		$arrAvatarInfo=array();
		$arrAvatarInfo=Core_Extend::avatars($GLOBALS['___login___']['user_id']);
		
		$arrOptionData=$GLOBALS['_option_'];
		
		$this->assign('arrAvatarInfo',$arrAvatarInfo);
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['avatar_uploadfile_maxsize']));
		
		$this->display('spaceadmin+avatar');
	}

	public function avatar_title_(){
		return Dyhb::L('修改头像','Controller/Spaceadmin');
	}

	public function avatar_keywords_(){
		return $this->avatar_title_();
	}

}
