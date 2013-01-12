<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   普通上传逻辑处理($)*/

!defined('DYHB_PATH') && exit;

class NormaluploadController extends Controller{

	public function index(){
		require(Core_Extend::includeFile('function/Upload_Extend'));

		try{
			$nAttachmentcategoryid=intval(G::getGpc('attachmentcategory_id'));

			$sHashcode=G::randString(32);
			Dyhb::cookie('_upload_hashcode_',$sHashcode,3600);

			$arrUploadids=Upload_Extend::uploadNormal();
			$sUploadids=implode(',',$arrUploadids);

			$this->cache_site_();
			
			if(G::getGpc('dialog','P')==1){
				G::urlGoTo(Dyhb::U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid.'&dialog=1&function='.G::getGpc('function','P')));
			}else{
				$this->assign('__JumpUrl__',Dyhb::U('home://attachment/attachmentinfo?id='.$sUploadids.'&hash='.$sHashcode.'&cid='.$nAttachmentcategoryid));
				$this->S(Dyhb::L('附件上传成功','Controller/Attachment'));
			}
		}catch(Exception $e){
			$this->E($e->getMessage());
		}
	}

	protected function cache_site_(){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('site');
	}

}
