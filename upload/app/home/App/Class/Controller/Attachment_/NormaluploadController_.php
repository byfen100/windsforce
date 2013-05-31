<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   普通上传逻辑处理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入附件上传函数 */
require(Core_Extend::includeFile('function/Upload_Extend'));

class NormaluploadController extends Controller{

	public function index(){
		try{
			$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id'));

			$sHashcode=G::randString(32);
			Dyhb::cookie('_upload_hashcode_',$sHashcode,3600);

			$arrUploadids=Upload_Extend::uploadNormal();
			$sUploadids=implode(',',$arrUploadids);

			// 保存home今日数据
			OptionModel::uploadOption('todayattachmentnum',$GLOBALS['_option_']['todayattachmentnum']+count($arrUploadids));
			OptionModel::uploadOption('todaytotalnum',$GLOBALS['_option_']['todaytotalnum']+count($arrUploadids));

			$this->cache_site_();

			// 更新积分
			Core_Extend::updateCreditByAction('postattachment',$GLOBALS['___login___']['user_id']);
			
			if(G::getGpc('dialog','P')==1){
				/*	这部分对于对话框来说过于麻烦，根据网友建议取消信息修改
					G::urlGoTo(Dyhb::U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid.'&dialog=1&function='.G::getGpc('function','P').(G::getGpc('filetype','P')==1?'&filetype=1':'')));
				*/

				G::urlGoTo(Dyhb::U('home://attachment/my_attachment?cid='.$nAttachmentcategoryid.'&dialog=1&function='.G::getGpc('function','P').(G::getGpc('filetype','P')==1?'&filetype=1':'')));
			}else{
				$this->assign('__JumpUrl__',Dyhb::U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid));
				$this->S(Dyhb::L('附件上传成功','Controller'));
			}
		}catch(Exception $e){
			if(G::getGpc('dialog','P')==1){
				G::urlGoTo(Dyhb::U('home://attachment/dialog_add?dialog=1&function='.G::getGpc('function','P').(G::getGpc('filetype','P')==1?'&filetype=1':'')),2,$e->getMessage());
				exit();
			}else{
				$this->E($e->getMessage());
			}
		}
	}

	protected function cache_site_(){
		// 更新附件专辑附件数量统计
		$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id'));

		if($nAttachmentcategoryid>0){
			$oAttachmentcategory=Dyhb::instance('AttachmentcategoryModel');
			$oAttachmentcategory->updateAttachmentnum($nAttachmentcategoryid);

			if($oAttachmentcategory->isError()){
				$this->E($oAttachmentcategory->getErrorMessage());
			}
		}
		
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
