<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   头像上传控制器($)*/

!defined('DYHB_PATH') && exit;

class AvatarController extends InitController{

	public function index($sModel=null,$bDisplay=true){
		$arrAvatarInfo=array();
		$arrAvatarInfo=Core_Extend::avatars($GLOBALS['___login___']['user_id']);
		
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrAvatarInfo',$arrAvatarInfo);
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['avatar_uploadfile_maxsize']));

		$this->display();
	}

	public function upload(){
		if($_FILES['image']['error']==4){
			$this->E(Dyhb::L('你没有选择任何文件','Controller/Avatar'));
			return;
		}

		if(!is_dir(dirname(NEEDFORBUG_PATH.'/data/avatar/temp')) && !G::makeDir(NEEDFORBUG_PATH.'/data/avatar/temp')){
			$this->E(Dyhb::L('上传目录%s不可写','Controller/Avatar',null,NEEDFORBUG_PATH.'/data/avatar/temp'));
		}

		require_once(Core_Extend::includeFile('function/Avatar_Extend'));
		$oUploadfile=Avatar_Extend::avatarTemp();
		if(!$oUploadfile->upload()){
			$this->E($oUploadfile->getErrorMessage());
		}else{
			$arrPhotoInfo=$oUploadfile->getUploadFileInfo();
		}

		$this->assign('arrPhotoInfo',reset($arrPhotoInfo));

		$this->display();
	}

	public function save_crop(){
		require_once(Core_Extend::includeFile('function/Avatar_Extend'));
		$bResult=Avatar_Extend::saveCrop();
		if($bResult===false){
			$this->E(Dyhb::L('你的PHP 版本或者配置中不支持如下的函数 “imagecreatetruecolor”、“imagecopyresampled”等图像函数，所以创建不了头像','Controller/Avatar'));
		}

		$this->assign('__JumpUrl__',Dyhb::U('avatar/index'));

		$this->S(Dyhb::L('头像上传成功','Controller/Avatar'));
	}

}
