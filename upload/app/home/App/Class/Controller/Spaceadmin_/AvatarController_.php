<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   前台个人中心管理($)*/

!defined('DYHB_PATH') && exit;

class AvatarController extends Controller{

	public function index(){
		$arrAvatarInfo=array();
		$arrAvatarInfo=Core_Extend::avatars($GLOBALS['___login___']['user_id']);
		
		$arrOptionData=$GLOBALS['_option_'];
		
		$this->assign('arrAvatarInfo',$arrAvatarInfo);
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['avatar_uploadfile_maxsize']));
		
		$this->display('spaceadmin+avatar');
	}

	public function avatar_title_(){
		return '修改头像';
	}

	public function avatar_keywords_(){
		return $this->avatar_title_();
	}

	public function avatar_description_(){
		return $this->avatar_title_();
	}

	public function upload(){
		if(!isset($_FILES['image'])){
			$this->assign('__JumpUrl__',Dyhb::U('spaceadmin/avatar'));
			$this->E(Dyhb::L('你不能直接进入裁减界面，请先上传','Controller/Spaceadmin'));
			return;
		}

		if($_FILES['image']['error']==4){
			$this->E(Dyhb::L('你没有选择任何文件','Controller/Spaceadmin'));
			return;
		}

		if(!is_dir(dirname(NEEDFORBUG_PATH.'/data/avatar/temp')) && !G::makeDir(NEEDFORBUG_PATH.'/data/avatar/temp')){
			$this->E(Dyhb::L('上传目录 %s 不可写','Controller/Spaceadmin',null,NEEDFORBUG_PATH.'/data/avatar/temp'));
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
		return '裁剪头像';
	}

	public function avatar_upload_keywords_(){
		return $this->avatar_upload_title_();
	}

	public function avatar_upload_description_(){
		return $this->avatar_upload_title_();
	}

	public function save_crop(){
		require_once(Core_Extend::includeFile('function/Avatar_Extend'));
		$bResult=Avatar_Extend::saveCrop();
		if($bResult===false){
			$this->E(Dyhb::L('你的PHP 版本或者配置中不支持如下的函数 “imagecreatetruecolor”、“imagecopyresampled”等图像函数，所以创建不了头像','Controller/Spaceadmin'));
		}

		$this->assign('__JumpUrl__',Dyhb::U('spaceadmin/avatar'));

		$this->S(Dyhb::L('头像上传成功','Controller/Spaceadmin'));
	}

}
