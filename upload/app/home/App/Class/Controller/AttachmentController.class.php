<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   附件管理($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

require_once(Core_Extend::includeFile('function/Attachment_Extend'));

class AttachmentController extends InitController{
		
	public function init__(){
		// 读取发送过来的COOKIE
		$sHash=trim(G::getGpc('hash'));
		$sAuth=trim(G::getGpc('auth'));
		if(!empty($sHash) && !empty($sAuth)){
			Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash',$sHash);
			Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'auth',$sAuth);
		}

		parent::init__();

		if(!in_array(ACTION_NAME,array('index','attachment','attachmentcategory','show','mp3list','get_ajaximg','fullplay_frame','playout'))){
			$this->is_login();
		}else{
			if(!Home_Extend::getVisiteallowed('attachment')){
				$this->E(Dyhb::L('你没有权限访问附件库','Controller/Attachment'));
			}
		}
	}
	
	public function index(){
		Core_Extend::doControllerAction('Attachment@Index','index');
	}

	public function add(){
		Core_Extend::doControllerAction('Attachment@Add','index');
	}

	public function dialog_add(){
		Core_Extend::doControllerAction('Attachment@Add','dialog');
	}

	public function new_attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Newattachmentcategory','index');
	}

	public function new_attachmentcategorysave(){
		Core_Extend::doControllerAction('Attachment@Newattachmentcategory','save');
	}

	public function normal_upload(){
		Core_Extend::doControllerAction('Attachment@Normalupload','index');
	}
	
	public function flash_upload(){
		Core_Extend::doControllerAction('Attachment@Flashupload','index');
	}

	public function flashinfo(){
		Core_Extend::doControllerAction('Attachment@Flashinfo','index');
	}

	public function attachmentinfo(){
		Core_Extend::doControllerAction('Attachment@Attachmentinfo','index');
	}

	public function attachmentinfo_save(){
		Core_Extend::doControllerAction('Attachment@Attachmentinfo','save');
	}

	public function my_attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Myattachmentcategory','index');
	}

	public function dialog_addattachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Dialogaddattachmentcategory','index');
	}

	public function dialog_attachmentcategorysave(){
		Core_Extend::doControllerAction('Attachment@Dialogaddattachmentcategory','save');
	}

	public function edit_attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Editattachmentcategory','index');
	}

	public function edit_attachmentcategorysave(){
		Core_Extend::doControllerAction('Attachment@Editattachmentcategory','save');
	}

	public function my_attachment(){
		Core_Extend::doControllerAction('Attachment@Myattachment','index');
	}

	public function dialog_editattachmentcategorysave(){
		Core_Extend::doControllerAction('Attachment@Editattachmentcategory','dialogsave');
	}

	public function delete_attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Deleteattachmentcategory','index');
	}

	public function edit_attachment(){
		Core_Extend::doControllerAction('Attachment@Editattachment','index');
	}

	public function edit_attachmentsave(){
		Core_Extend::doControllerAction('Attachment@Editattachment','save');
	}

	public function delete_attachment(){
		Core_Extend::doControllerAction('Attachment@Deleteattachment','index');
	}

	public function delete_attachments(){
		Core_Extend::doControllerAction('Attachment@Deleteattachment','all');
	}

	public function attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Attachmentcategory','index');
	}

	public function attachment(){
		Core_Extend::doControllerAction('Attachment@Attachmentlist','index');
	}

	public function show(){
		Core_Extend::doControllerAction('Attachment@Show','index');
	}

	public function mp3list(){
		Core_Extend::doControllerAction('Attachment@Show','mp3list');
	}

	public function get_ajaximg(){
		Core_Extend::doControllerAction('Attachment@Getajaximg','index');
	}

	public function recommend_attachment(){
		Core_Extend::doControllerAction('Attachment@Recommendattachment','index');
	}

	public function unrecommend_attachment(){
		Core_Extend::doControllerAction('Attachment@Recommendattachment','un');
	}

	public function recommend_attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Recommendattachmentcategory','index');
	}	
	
	public function unrecommend_attachmentcategory(){
		Core_Extend::doControllerAction('Attachment@Recommendattachmentcategory','un');
	}
	
	public function fullplay_frame(){
		Core_Extend::doControllerAction('Attachment@Fullplayframe','index');
	}
	
	public function playout(){
		Core_Extend::doControllerAction('Attachment@Playout','index');
	}

	public function cover(){
		Core_Extend::doControllerAction('Attachment@Cover','index');
	}

	public function uncover(){
		Core_Extend::doControllerAction('Attachment@Uncover','index');
	}

	public function add_attachmentcomment(){
		Core_Extend::doControllerAction('Attachment@Addcomment','index');
	}

	public function audit_attachmentcomment(){
		Core_Extend::doControllerAction('Attachment@Auditattachmentcomment','index');
	}

}
