<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   头像上传后裁剪页面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UploadController extends Controller{

	public function index(){
		if(!isset($_FILES['image'])){
			$this->assign('__JumpUrl__',Dyhb::U('spaceadmin/avatar'));
			$this->E(Dyhb::L('你不能直接进入裁减界面，请先上传','Controller'));
			return;
		}

		if($_FILES['image']['error']==4){
			$this->E(Dyhb::L('你没有选择任何文件','Controller'));
			return;
		}

		if(!is_dir(dirname(WINDSFORCE_PATH.'/data/avatar/temp')) && !G::makeDir(WINDSFORCE_PATH.'/data/avatar/temp')){
			$this->E(Dyhb::L('上传目录 %s 不可写','Controller',null,WINDSFORCE_PATH.'/data/avatar/temp'));
		}

		require_once(Core_Extend::includeFile('function/Avatar_Extend'));
		$oUploadfile=Avatar_Extend::avatarTemp();
		if(!$oUploadfile->upload()){
			$this->E($oUploadfile->getErrorMessage());
		}else{
			$arrPhotoInfo=$oUploadfile->getUploadFileInfo();
		}

		$this->assign('arrPhotoInfo',reset($arrPhotoInfo));

		$this->display('spaceadmin+avatarupload');
	}

	public function avatar_upload_title_(){
		return Dyhb::L('裁剪头像','Controller');
	}

	public function avatar_upload_keywords_(){
		return $this->avatar_upload_title_();
	}

	public function avatar_upload_description_(){
		return $this->avatar_upload_title_();
	}

}
