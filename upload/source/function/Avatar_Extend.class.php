<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   头像处理相关函数($)*/

!defined('DYHB_PATH') && exit;

class Avatar_Extend{

	static public function avatarTemp(){
		$nUploadfileMaxsize=Core_Extend::getUploadSize($GLOBALS['_option_']['avatar_uploadfile_maxsize']);
		
		$oUploadfile=new UploadFileForUploadify($nUploadfileMaxsize,array('gif','jpg','jpeg','png'),'',NEEDFORBUG_PATH.'/data/avatar');

		$oUploadfile->_sSaveRule=array('Avatar_Extend','get_temp_avatar');
		$oUploadfile->setUploadifyDataName('image');
		$oUploadfile->setUploadReplace(TRUE);
		$oUploadfile->setAutoCreateStoreDir(TRUE);

		return $oUploadfile;
	}

	static public function get_temp_avatar($sData){
		return 'temp/temp_'. $GLOBALS['___login___']['user_id'].'.'.G::getExtName($sData,2);
	}

	static public function saveCrop(){
		$sSrc=G::getGpc('temp_image');
		$sSrc=NEEDFORBUG_PATH.'/data/avatar/'.$sSrc;
		if(!is_file($sSrc)){
			Dyhb::E(Dyhb::L('无法获取裁剪图像','__COMMON_LANG__@Function/Avatar_Extend'));
		}

		$arrPhotoInfo=@getimagesize($sSrc);

		if(function_exists('imagecreatetruecolor') && function_exists('imagecopyresampled')){
			$sImageCreateFromFunc='';
			switch($arrPhotoInfo['mime']){
				case 'image/jpeg':
					$sImageCreateFromFunc=function_exists('imagecreatefromjpeg')? 'imagecreatefromjpeg':'';
					break;
				case 'image/gif':
					$sImageCreateFromFunc=function_exists('imagecreatefromgif')? 'imagecreatefromgif':'';
					break;
				case 'image/png':
					$sImageCreateFromFunc=function_exists('imagecreatefrompng')? 'imagecreatefrompng':'';
					break;
			}

			$oImgR=$sImageCreateFromFunc($sSrc);
			$oDstR=ImageCreateTrueColor(200,200);
			imagecopyresampled($oDstR,$oImgR,0,0,intval(G::getGpc('x','P')),intval(G::getGpc('y','P')),200,200,intval(G::getGpc('w','P')),intval(G::getGpc('h','P')));
			$sOutfile=NEEDFORBUG_PATH.'/data/avatar/'.G::getAvatar($GLOBALS['___login___']['user_id'],'big');
			self::createFileDir($sOutfile);
			imagejpeg($oDstR,$sOutfile,100);
			imagedestroy($oDstR);

			self::createThumbs($sOutfile,$sImageCreateFromFunc);// 创建缩略图
			self::createHigh($sSrc,$sImageCreateFromFunc);// 创建原图
			return true;
		}else{
			return false;
		}
	}

	static public function createFileDir($sDir){
		if(!is_dir(dirname($sDir)) && !G::makeDir(dirname($sDir))){
			Dyhb::E(Dyhb::L('上传目录%s不可写','__COMMON_LANG__@Function/Avatar_Extend',null,dirname($sDir)));
		}
	}

	static public function createThumbs($sOutfile){
		$arrThumbs=array(
			array($sOutfile,NEEDFORBUG_PATH.'/data/avatar/'.G::getAvatar($GLOBALS['___login___']['user_id'],'middle'),array(120,120)),
			array($sOutfile,NEEDFORBUG_PATH.'/data/avatar/'.G::getAvatar($GLOBALS['___login___']['user_id'],'small'),array(48,48)),
		);

		foreach($arrThumbs as $arrThumb){
			self::createThumb($arrThumb[0],$arrThumb[1],$arrThumb[2]);
		}
	}

	static public function createThumb($sFilename,$sThumbPath,$arrSize=array()){
		self::createFileDir($sThumbPath);
		Image::thumb($sFilename,$sThumbPath,'',$arrSize[0],$arrSize[1],true);
	}

	static public function createHigh($sSrc,$sImageCreateFromFunc){
		$oImgR=$sImageCreateFromFunc($sSrc);
		imagejpeg($oImgR,NEEDFORBUG_PATH.'/data/avatar/'.G::getAvatar($GLOBALS['___login___']['user_id'],'origin'),100);
		imagedestroy($oImgR);
		@unlink($sSrc);
	}

}
