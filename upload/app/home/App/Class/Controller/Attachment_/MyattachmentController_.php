<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   我的附件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MyattachmentController extends Controller{

	public function index(){
		$sType=trim(G::getGpc('type','G'));
		$nAttachmentcategoryid=G::getGpc('cid','G');
		$nPhoto=G::getGpc('photo','G');
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));
		$nFiletype=intval(G::getGpc('filetype','G'));
		
		$arrWhere=array();

		if($sType){
			$arrWhere['attachment_extension']=$sType;
		}

		if($nFiletype==1){
			$nPhoto='1';
		}elseif($nFiletype==2){
			$nPhoto='0';
		}

		if($nPhoto=='1'){
			$arrWhere['attachment_extension']=array('in','gif,jpeg,jpg,png,bmp');
		}elseif($nPhoto=='0'){
			$arrWhere['attachment_extension']=array('not in','gif,jpeg,jpg,png,bmp');
		}

		if($nAttachmentcategoryid!==null){
			$arrWhere['attachmentcategory_id']=intval($nAttachmentcategoryid);

			// 取得专辑信息
			$arrAttachmetncategoryinfo=array();
			if($nAttachmentcategoryid==0){
				$nDefaultattachmentnum=AttachmentModel::F('user_id=? AND attachmentcategory_id=0',$GLOBALS['___login___']['user_id'])->all()->getCounts();
				$arrAttachmetncategoryinfo['totalnum']=$nDefaultattachmentnum;
			}elseif($nAttachmentcategoryid>0){
				$oAttachmentcategoryinfo=AttachmentcategoryModel::F('attachmentcategory_id=?',$nAttachmentcategoryid)->getOne();
				if(!empty($oAttachmentcategoryinfo['attachmentcategory_id'])){
					$arrAttachmetncategoryinfo=$oAttachmentcategoryinfo->toArray();
				}else{
					$arrAttachmetncategoryinfo=false;
				}
			}

			$this->assign('arrAttachmetncategoryinfo',$arrAttachmetncategoryinfo);
		}

		$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];

		// 取得附件列表
		if($nDialog==1){
			$nEverynum=$GLOBALS['_option_']['attachment_dialogmycategorynum'];
		}else{
			$nEverynum=$GLOBALS['_option_']['attachment_mycategorynum'];
		}

		$nTotalRecord=AttachmentModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		$arrAttachments=AttachmentModel::F()->where($arrWhere)->order('attachment_id DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		// 附件分类
		$arrAttachmentcategorys=Attachment_Extend::getAttachmentcategory();
		$this->assign('arrAttachmentcategorys',$arrAttachmentcategorys);

		// 所有允许的分类
		$arrAllowedTypes=Attachment_Extend::getAllowedtype();
		$this->assign('arrAllowedTypes',$arrAllowedTypes);

		$this->assign('arrAttachments',$arrAttachments);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nAttachmentcategoryid',$nAttachmentcategoryid);
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);
		$this->assign('nFiletype',$nFiletype);

		if($nDialog==1){
			$this->display('attachment+dialogmyattachment');
		}else{
			$this->display('attachment+myattachment');
		}
	}

	public function my_attachment_title_(){
		return Dyhb::L('我的附件','Controller');
	}

	public function my_attachment_keywords_(){
		return $this->my_attachment_title_();
	}

	public function my_attachment_description_(){
		return $this->my_attachment_title_();
	}

}
