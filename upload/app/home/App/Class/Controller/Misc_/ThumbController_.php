<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   缩略图控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ThumbController extends Controller{

	public function index(){
		$nId=intval(G::getGpc('id','G'));
		$nWidth=intval(G::getGpc('w','G'));
		$nHeight=intval(G::getGpc('h','G'));
		$nThumb=intval(G::getGpc('thumb','G'));

		$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->getOne();
		if(!empty($oAttachment['attachment_id'])){
			$sAttachmentpath=WINDSFORCE_PATH.'/data/upload/attachment/'.
				($oAttachment['attachment_isthumb'] && $nThumb==1?
				$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_thumbprefix']:
				$oAttachment['attachment_savepath'].'/').$oAttachment['attachment_savename'];
		}else{
			$sAttachmentpath='';
		}

		Core_Extend::thumb($sAttachmentpath,$nWidth,$nHeight);

		exit();
	}

}
