<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   上传配置处理控制器($)*/

!defined('DYHB_PATH') && exit;

class UploadoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->assign('nUploadMaxFilesize',ini_get('upload_max_filesize'));
		$this->assign('nUploadSize',Core_Extend::getUploadSize());
		
		$this->display();
	}

	public function avatar(){
		$arrOptionData=$GLOBALS['_option_'];

		$this->assign('arrOptions',$arrOptionData);
		$this->assign('nUploadMaxFilesize',ini_get('upload_max_filesize'));
		$this->assign('nUploadSize',Core_Extend::getUploadSize($arrOptionData['avatar_uploadfile_maxsize']));
		
		$this->display();
	}

	public function show(){
		$arrOptionData=$GLOBALS['_option_'];
		$this->assign('arrOptions',$arrOptionData);

		$this->display();
	}

	public function ubb(){
		$this->show();
	}

	public function attachment(){
		$this->show();
	}

}
