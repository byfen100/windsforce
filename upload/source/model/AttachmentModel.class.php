<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   文件上传模型($)*/

!defined('DYHB_PATH') && exit;

class AttachmentModel extends CommonModel{

	static public function init__(){
		return array(
			'table_name'=>'attachment',
			'props'=>array(
				'attachment_id'=>array('readonly'=>true),
				'attachmentcategory'=>array(Db::BELONGS_TO=>'AttachmentcategoryModel','source_key'=>'attachmentcategory_id','target_key'=>'attachmentcategory_id','skip_empty'=>true),
				'user'=>array(Db::BELONGS_TO=>'UserModel','source_key'=>'user_id','target_key'=>'user_id'),
			),
			'attr_protected'=>'attachment_id',
			'autofill'=>array(
				array('user_id','userId','create','callback'),
				array('attachment_username','userName','create','callback'),
			),
			'check'=>array(
				'attachmentcategory_name'=>array(
					array('require',Dyhb::L('附件专辑名不能为空','__COMMON_LANG__@Model/Attachmentcategory')),
					array('max_length',50,Dyhb::L('附件专辑名最大长度为50个字符','__COMMON_LANG__@Model/Attachmentcategory')),
				),
			),
		);
	}

	static function F(){
		$arrArgs=func_get_args();
		return ModelMeta::instance(__CLASS__)->findByArgs($arrArgs);
	}

	static function M(){
		return ModelMeta::instance(__CLASS__);
	}

	public function upload($arrUploadinfos){
		if(empty($arrUploadinfos)){
			return FALSE;
		}

		$arrUploadinfoTemps=array();
		$nUploadcategoryId=G::getGpc('attachmentcategory_id','P');
		$sUploadModule=G::getGpc('module','P');

		if($nUploadcategoryId===null){
			$nUploadcategoryId=0;
		}

		if($sUploadModule===null){
			$sUploadModule='attachment';
		}

		foreach($arrUploadinfos as $nKey=>$arrUploadinfo){
			foreach($arrUploadinfo as $sKey=>$value){
				$arrUploadinfoTemps[$nKey]['attachment_'.$sKey]=$value;
				$arrUploadinfoTemps[$nKey]['attachmentcategory_id']=$nUploadcategoryId;
				$arrUploadinfoTemps[$nKey]['attachment_module']=$sUploadModule;
			}
		}

		unset($arrUploadinfos);

		$arrUploadids=array();
		foreach($arrUploadinfoTemps as $arrUploadinfoTemp){
			if(!in_array($arrUploadinfoTemp['attachment_extension'],array('jpg','jpeg','gif','png','bmp')) || !is_file($arrUploadinfoTemp['attachment_thumbpath'].'/'.$arrUploadinfoTemp['attachment_savename'])){
				$arrUploadinfoTemp['attachment_isthumb']=0;
				$arrUploadinfoTemp['attachment_thumbpath']='';
				$arrUploadinfoTemp['attachment_thumbprefix']='';
			}
			
			$arrUploadinfoTemp['attachment_savepath']=str_replace(G::tidyPath(NEEDFORBUG_PATH.'/data/upload/attachment').'/','',G::tidyPath($arrUploadinfoTemp['attachment_savepath']));
			$arrUploadinfoTemp['attachment_thumbpath']=str_replace(G::tidyPath(NEEDFORBUG_PATH.'/data/upload/attachment/').'/','',G::tidyPath($arrUploadinfoTemp['attachment_thumbpath']));
		
			$oUpload=new self($arrUploadinfoTemp);
			$oUpload->save(0);
			if($oUpload->isError()){
				$this->setErrorMessage($oUpload->getErrorMessage());
				return FALSE;
			}
			$arrUploadids[]=$oUpload['attachment_id'];
		}

		return $arrUploadids;
	}

	protected function userId(){
		$nUserId=intval(G::getGpc('user_id'));
		return $nUserId>0?$nUserId:0;
	}

	protected function userName(){
		return G::getGpc('user_name');
	}

}
