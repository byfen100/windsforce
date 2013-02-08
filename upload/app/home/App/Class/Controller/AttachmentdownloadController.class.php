<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   附件下载数量更新管理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

require_once(Core_Extend::includeFile('function/Attachment_Extend'));

class AttachmentdownloadController extends InitController{
	
	public function index(){
		$nId=intval(G::getGpc('id','G'));

		if(empty($nId)){
			$this->E(Dyhb::L('没有指定更新的附件ID','Controller/Attachmentdownload'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('你请求的附件不存在','Controller/Attachmentdownload'));
		}

		$bHidereallypath=Attachment_Extend::attachmentHidereallypath($oAttachment);
		if(!$bHidereallypath){
			// 记录下载
			$bDownload=false;
			if($sAttachmentCookie=Dyhb::cookie('attachment_read')){
				$arrAttachmentIds=explode(',',$sAttachmentCookie);
				if(in_array($nId,$arrAttachmentIds)){
					$bDownload=true;
				}
			}

			if($bDownload===false){
				$oAttachment->attachment_download=$oAttachment->attachment_download+1;
				$oAttachment->setAutofill(false);
				$oAttachment->save(0,'update');

				if($oAttachment->isError()){
					$this->E($oAttachment->getErrorMessage());
				}

				$sAttachmentCookie.=empty($sAttachmentCookie)?$nId:','.$nId;
				Dyhb::cookie('attachment_read',$sAttachmentCookie,86400);
			}
		}
		
		$this->S(Dyhb::L('下载成功','Controller/Attachmentdownload'),1);
	}

}
