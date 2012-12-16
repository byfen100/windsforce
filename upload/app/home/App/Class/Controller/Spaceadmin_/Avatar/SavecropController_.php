<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   头像保存($)*/

!defined('DYHB_PATH') && exit;

class SavecropController extends Controller{

	public function index(){
		require_once(Core_Extend::includeFile('function/Avatar_Extend'));
		$bResult=Avatar_Extend::saveCrop();
		if($bResult===false){
			$this->E(Dyhb::L('你的PHP 版本或者配置中不支持如下的函数 “imagecreatetruecolor”、“imagecopyresampled”等图像函数，所以创建不了头像','Controller/Spaceadmin'));
		}

		$this->assign('__JumpUrl__',Dyhb::U('spaceadmin/avatar'));

		$this->S(Dyhb::L('头像上传成功','Controller/Spaceadmin'));
	}

}
