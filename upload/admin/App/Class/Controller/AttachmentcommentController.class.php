<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   多媒体附件评论控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入杂项函数 */
require(Core_Extend::includeFile('function/Misc_Extend'));

class AttachmentcommentController extends InitController{

	public function filter_(&$arrMap){
		$arrMap['attachmentcomment_name']=array('like',"%".G::getGpc('attachmentcomment_name')."%");

		// 附件检索
		$nAid=intval(G::getGpc('aid','G'));
		if($nAid){
			$oAttachment=AttachmentModel::F('attachment_id=?',$nAid)->getOne();

			if(!empty($oAttachment['attachment_id'])){
				$arrMap['attachment_id']=$nAid;
				$this->assign('oAttachment',$oAttachment);
			}
		}

		// 用户检索
		$nUid=intval(G::getGpc('uid','G'));
		if($nUid){
			$oUser=UserModel::F('user_id=?',$nUid)->getOne();

			if(!empty($oUser['user_id'])){
				$arrMap['user_id']=$nUid;
				$this->assign('oUser',$oUser);
			}
		}
	}

	public function add(){
		$this->E(Dyhb::L('后台无法添加附件评论','Controller/Attachmentcomment'));
	}

	public function AUpdateObject_($oModel){
		$oModel->safeInput();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);

		// 将附件评论子评论的父级ID改为当前的评论的父级ID(节点移位)
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oAttachmentcomment=AttachmentcommentModel::F('attachmentcomment_id=?',$nId)->getOne();

				if(!empty($oAttachmentcomment['attachmentcomment_id'])){
					$arrAttachmentchildcomments=AttachmentcommentModel::F('attachmentcomment_parentid=?',$nId)->getAll();

					if(is_array($arrAttachmentchildcomments)){
						foreach($arrAttachmentchildcomments as $oAttachmentchildcomment){
							$oAttachmentchildcomment->attachmentcomment_parentid=$oAttachmentcomment['attachmentcomment_parentid'];
							$oAttachmentchildcomment->save(0,'update');

							if($oAttachmentchildcomment->isError()){
								$this->E($oAttachmentchildcomment->getErrorMessage());
							}
						}
					}
				}
			}
		}
	}

	protected function aForeverdelete($sId){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		
		// 更新附件评论数量
		if(is_array($arrIds)){
			foreach($arrIds as $nId){
				$oAttachmentcomment=AttachmentcommentModel::F('attachmentcomment_id=?',$nId)->getOne();
				
				if(!empty($oAttachmentcomment['attachmentcomment_id'])){
					// 更新评论数量
					$oAttachment=Dyhb::instance('AttachmentModel');
					$oAttachment->updateAttachmentcommentnum(intval($oAttachmentcomment['attachment_id']));

					if($oAttachment->isError()){
						$oAttachment->getErrorMessage();
					}
				}
			}
		}
	}

}
