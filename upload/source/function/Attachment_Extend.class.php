<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   附件相关函数($)*/

!defined('DYHB_PATH') && exit;

class Attachment_Extend{

	static public function getImagesize($oAttachment){
		if(in_array($oAttachment['attachment_extension'],array('gif','jpg','jpeg','png','bmp'))){
			$sAttachmentFilepath=WINDSFORCE_PATH.'/data/upload/attachment/'.(
				$oAttachment['attachment_isthumb']?
				$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_savename']:
				$oAttachment['attachment_savepath'].'/'.$oAttachment['attachment_savename']
			);

			if(!is_file($sAttachmentFilepath)){
				$sAttachmentFilepath=WINDSFORCE_PATH.'/data/upload/attachment/'.$oAttachment['attachment_savepath'].'/'.$oAttachment['attachment_savename'];

				if(!is_file($sAttachmentFilepath)){
					return false;
				}
			}

			$arrAttachmentInfo=array();
			$arrTempAttachmentInfo=@getimagesize($sAttachmentFilepath);

			if(empty($arrTempAttachmentInfo)){
				$arrAttachmentInfo[0]=0;
				$arrAttachmentInfo[1]=0;
			}else{
				$arrAttachmentInfo[0]=$arrTempAttachmentInfo[0];
				$arrAttachmentInfo[1]=$arrTempAttachmentInfo[1];
			}

			return $arrAttachmentInfo;
		}else{
			return false;
		}
	}

	static public function getAttachmenttype($oAttachment){
		$arrAttachmentTypes=array(
			'img'=>array('jpg','jpeg','gif','png','bmp'),
			'swf'=>array('swf'),
			'wmp'=>array('wma','asf','wmv','avi','wav'),
			'mp3'=>array('mp3'),
			'qvod'=>array('rm','rmvb','ra','ram'),
			'flv'=>array('flv','mp4'),
			'url'=>array('html','htm','txt'),
			'download'=>array(),
		);
		
		$sAttachmentExtension=$oAttachment['attachment_extension'];

		foreach($arrAttachmentTypes as $sKey=>$arrAttachmentType){
			if(in_array($sAttachmentExtension,$arrAttachmentType)){
				return $sKey;
			}
		}
			
		return 'download';
	}

	static public function getAttachmenturl($oAttachment,$bThumb=false,$bUrlpath=true){
		if($bUrlpath===false){
			return WINDSFORCE_PATH.'/data/upload/attachment/'.
				($oAttachment['attachment_isthumb'] && $bThumb===true?
				$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_thumbprefix']:
				$oAttachment['attachment_savepath'].'/').$oAttachment['attachment_savename'];
		}

		if(self::attachmentHidereallypath($oAttachment)){
			return $GLOBALS['_option_']['site_url'].'/attachment.php?id='.Core_Extend::aidencode($oAttachment['attachment_id']).($oAttachment['attachment_isthumb'] && $bThumb===true?'&thumb=1':'');
		}else{
			return $GLOBALS['_option_']['site_url'].'/data/upload/attachment/'.
				($oAttachment['attachment_isthumb'] && $bThumb===true?
					$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_thumbprefix']:
					$oAttachment['attachment_savepath'].'/').$oAttachment['attachment_savename'];
		}
	}

	static public function attachmentHidereallypath($oAttachment){
		return (in_array($oAttachment['attachment_extension'],array('gif','jpg','jpeg','png','bmp')) && $GLOBALS['_option_']['upload_img_ishide_reallypath']) || 
			(!in_array($oAttachment['attachment_extension'],array('gif','jpg','jpeg','png','bmp')) && $GLOBALS['_option_']['upload_ishide_reallypath']);
	}

	static public function getAttachmentPreview($oAttachment,$bUrlpath=true){
		if(empty($oAttachment['attachment_id'])){
			return '';
		}

		$sAttachmentPreview=self::getFileicon($oAttachment['attachment_extension'],false,true,$bUrlpath);
		if($sAttachmentPreview===true){
			return self::getAttachmenturl($oAttachment,true,$bUrlpath);
		}else{
			return $sAttachmentPreview;
		}
	}

	static public function getAttachmentcategoryPreview($oAttachmentcategory,$bUrlpath=true){
		if(empty($oAttachmentcategory['attachmentcategory_id'])){
			return '';
		}

		// 已设置封面
		if($oAttachmentcategory['attachmentcategory_cover']>0){
			$oAttachment=AttachmentModel::F('attachment_id=?',$oAttachmentcategory['attachmentcategory_cover'])->getOne();
			if(!empty($oAttachment['attachment_id'])){
				return self::getAttachmentPreview($oAttachment,$bUrlpath);
			}
		}

		// 尝试读取最新的附件
		$oAttachment=AttachmentModel::F('attachmentcategory_id=?',$oAttachmentcategory['attachmentcategory_id'])->order('attachment_id DESC')->getOne();
		if(!empty($oAttachment['attachment_id'])){
			return self::getAttachmentPreview($oAttachment,$bUrlpath);
		}

		// 设置默认图片
		if($bUrlpath===true){
			return __PUBLIC__.'/images/common/default_attachmentcategory.png';
		}else{
			return WINDSFORCE_PATH.'/Public/images/common/default_attachmentcategory.png';
		}
	}

	static public function getFileicon($sExtension,$bReturnImageIcon=false,$bReturnPath=true,$bUrlpath=true){
		$arrIcons=array(
			'image'=>array('gif','jpg','jpeg','bmp','png'),
			'archive'=>array('zip','z','gz','gtar','rar'),
			'audio'=>array('aif','aifc','aiff','au','kar','m3u','mid','midi',
						'mp2','mp3','mpga','ra','ram','rm','rpm','snd','wav',
						'wax','wma','aac'),
			'video'=>array('asf','asx','avi','mov','movie','mpeg','mpe','mpg',
						'mxu','qt','wm','wmv','wmx','wvx','rmvb','flv','mp4'),
			'document'=>array('doc','pdf','ppt'),
			'text'=>array('txt','ascii','mime'),
			'spreadsheet'=>array('xls','et'),
			'interactive'=>array('as','flash'),
			'code'=>array('h','c','h','cpp','dfm','pas','frm','vbs','asp','jsp','java','class','php'),
			'default'=>array(),
		);

		$sFileiconPath='';
		foreach($arrIcons as $sKey=>$arrIcon){
			if(in_array($sExtension,$arrIcon)){
				$sFileiconPath=$sKey;
				break;
			}
		}

		if(empty($sFileiconPath)){
			$sFileiconPath='default';
		}

		if($sFileiconPath=='image' && $bReturnImageIcon===false){
			return true;
		}

		if($bReturnPath===true){
			if($bUrlpath===true){
				return __PUBLIC__.'/images/crystal/'.$sFileiconPath.'.png';
			}else{
				return WINDSFORCE_PATH.'/Public/images/crystal/'.$sFileiconPath.'.png';
			}
		}else{
			return $sFileiconPath;
		}
	}

	static public function getAttachmentcategory(){
		$oAttachmentcategory=Dyhb::instance('AttachmentcategoryModel');
		return $oAttachmentcategory->getAttachmentcategoryByUserid($GLOBALS['___login___']['user_id']);
	}

	static public function getAllowedtype(){
		return explode('|',$GLOBALS['_option_']['upload_allowed_type']);
	}

	static public function getAttachmentpreviewimagesize($oAttachmentcategory){
		$sAttachmentcategorypreview=self::getAttachmentcategoryPreview($oAttachmentcategory,false);

		$arrAttachmentcategorypreview=array();
		if(is_file($sAttachmentcategorypreview)){
			$arrTempAttachmentcategorypreview=@getimagesize($sAttachmentcategorypreview);
		}else{
			$arrAttachmentcategorypreview[0]=0;
			$arrAttachmentcategorypreview[1]=0;
		}

		if(empty($arrTempAttachmentcategorypreview)){
			$arrAttachmentcategorypreview[0]=0;
			$arrAttachmentcategorypreview[1]=0;
		}else{
			$arrAttachmentcategorypreview[0]=$arrTempAttachmentcategorypreview[0];
			$arrAttachmentcategorypreview[1]=$arrTempAttachmentcategorypreview[1];
		}

		return $arrAttachmentcategorypreview;
	}

}
