<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   最新专辑（包含推荐专辑）($)*/

!defined('DYHB_PATH') && exit;

class AttachmentcategoryController extends Controller{

	public function index(){
		$nRecommend=intval(G::getGpc('recommend','G'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));

		$arrWhere=array();
		if($nRecommend==1){
			$arrWhere['attachmentcategory_recommend']=1;
		}

		// 取得专辑列表
		if($nDialog==1){
			$nEverynum=$GLOBALS['_option_']['attachment_dialogattachmentnum'];
		}else{
			$nEverynum=$GLOBALS['_option_']['attachment_attachmentnum'];
		}

		$nTotalRecord=AttachmentcategoryModel::F($arrWhere)->all(array())->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nEverynum,G::getGpc('page','G'));
		$arrAttachmentcategorys=AttachmentcategoryModel::F($arrWhere)->order('attachmentcategory_id DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrAttachmentcategorys',$arrAttachmentcategorys);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);

		if($nDialog==1){
			$this->display('attachment+dialogattachmentcategory');
		}else{
			$this->display('attachment+attachmentcategory');
		}
	}

}
