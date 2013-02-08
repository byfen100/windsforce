<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   我的专辑($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class MyattachmentcategoryController extends Controller{

	public function index(){
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));
		$nFiletype=intval(G::getGpc('filetype','G'));
		
		$arrWhere=array();
		$arrWhere['user_id']=$GLOBALS['___login___']['user_id'];

		// 取得专辑列表
		if($nDialog==1){
			$nEverynum=$GLOBALS['_option_']['attachment_dialogmycategorynum'];
		}else{
			$nEverynum=$GLOBALS['_option_']['attachment_mycategorynum'];
		}

		$nTotalRecord=AttachmentcategoryModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		$arrAttachmentcategorys=AttachmentcategoryModel::F()->where($arrWhere)->order('attachmentcategory_sort DESC,create_dateline DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrAttachmentcategorys',$arrAttachmentcategorys);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);
		$this->assign('nFiletype',$nFiletype);

		if($nDialog==1){
			$this->display('attachment+dialogmyattachmentcategory');
		}else{
			$this->display('attachment+myattachmentcategory');
		}
	}

	public function my_attachmentcategory_title_(){
		return Dyhb::L('我的专辑','Controller/Attachment');
	}

	public function my_attachmentcategory_keywords_(){
		return $this->my_attachmentcategory_title_();
	}

	public function my_attachmentcategory_description_(){
		return $this->my_attachmentcategory_title_();
	}

}
