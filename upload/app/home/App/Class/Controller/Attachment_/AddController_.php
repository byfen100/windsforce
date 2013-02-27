<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   附件上传界面($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AddController extends Controller{

	public function index($bDialog=false){
		try{
			$arrData=array();

			$oLastattachment=AttachmentModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->order('create_dateline DESC')->getOne();
			if(!empty($oLastattachment['attachment_id'])){
				$arrData['lasttime']=$oLastattachment['create_dateline'];
			}

			Core_Extend::checkSpam($arrData);
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
		
		$nAttachmentcategoryid=intval(G::getGpc('cid','G'));

		$nUploadfileMaxsize=Core_Extend::getUploadSize($GLOBALS['_option_']['uploadfile_maxsize']);
		$nUploadFileMode=$GLOBALS['_option_']['upload_file_mode'];
		$sAllAllowType=$GLOBALS['_option_']['upload_allowed_type'];
		if(empty($sAllAllowType)){
			$sAllAllowType='*.*';
		}else{
			$arrTempAllAllowType=array();
			foreach(explode('|',$sAllAllowType) as $sValue){
				$arrTempAllAllowType[]='*.'.$sValue;
			}
			$sAllAllowType=implode(';',$arrTempAllAllowType);
		}

		$nUploadFlashLimit=intval($GLOBALS['_option_']['upload_flash_limit']);
		if($nUploadFlashLimit<0){
			$nUploadFlashLimit=0;
		}

		$nFileInputNum=$GLOBALS['_option_']['upload_input_num'];

		// 登录使用COOKIE
		$sHash=Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash');
		$sAuth=Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'auth');

		$this->assign('sHash',$sHash);
		$this->assign('sAuth',$sAuth);

		$nUploadIsauto=$GLOBALS['_option_']['upload_isauto'];
		$this->assign('nUploadIsauto',$nUploadIsauto);

		// 附件分类
		$arrAttachmentcategorys=Attachment_Extend::getAttachmentcategory();
		$this->assign('arrAttachmentcategorys',$arrAttachmentcategorys);

		// 所有允许的分类
		$arrAllowedTypes=Attachment_Extend::getAllowedtype();
		$this->assign('arrAllowedTypes',$arrAllowedTypes);

		// 是否有专辑
		if($nAttachmentcategoryid>0){
			$oTryattachmentcategory=AttachmentcategoryModel::F('attachmentcategory_id=? AND user_id=?',$nAttachmentcategoryid,$GLOBALS['___login___']['user_id'])->getOne();
			
			if(empty($oTryattachmentcategory['attachmentcategory_id'])){
				$nAttachmentcategoryid=false;
			}else{
				$bFound=false;
				foreach($arrAttachmentcategorys as $oAttachmentcategory){
					if($oAttachmentcategory['attachmentcategory_id']==$nAttachmentcategoryid){
						$bFound=true;
						break;
					}
				}

				if($bFound===false){
					$nAttachmentcategoryid=false;
				}
			}
		}else{
			$nAttachmentcategoryid=false;
		}
		$this->assign('nAttachmentcategoryid',$nAttachmentcategoryid);

		$this->assign('nUploadfileMaxsize',$nUploadfileMaxsize);
		$this->assign('nUploadFileMode',$nUploadFileMode);
		$this->assign('sAllAllowType',$sAllAllowType);
		$this->assign('nUploadFlashLimit',$nUploadFlashLimit);
		$this->assign('nFileInputNum',$nFileInputNum);

		if($bDialog===false){
			$this->display('attachment+add');
		}else{
			$this->display('attachment+dialogadd');
		}
	}

	public function dialog(){
		$sFunction=trim(G::getGpc('function','G'));
		$nFiletype=intval(G::getGpc('filetype','G'));

		$this->assign('sFunction',$sFunction);
		$this->assign('nFiletype',$nFiletype);
		$this->assign('bDialog',true);

		$this->index(true);
	}

	public function add_title_(){
		return Dyhb::L('上传附件','Controller/Attachment');
	}

	public function add_keywords_(){
		return $this->add_title_();
	}

	public function add_description_(){
		return $this->add_title_();
	}

}
