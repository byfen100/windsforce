<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   我的专辑($)*/

!defined('DYHB_PATH') && exit;

class MyattachmentcategoryController extends Controller{

	public function index(){
		$nDialog=intval(G::getGpc('dialog','G'));
		$sFunction=trim(G::getGpc('function','G'));
		
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
		$arrAttachmentcategorys=AttachmentcategoryModel::F()->where($arrWhere)->order('attachmentcategory_compositor DESC,create_dateline DESC')->limit($oPage->returnPageStart(),$nEverynum)->getAll();

		$this->assign('arrAttachmentcategorys',$arrAttachmentcategorys);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('nDialog',$nDialog);
		$this->assign('sFunction',$sFunction);

		if($nDialog==1){
			$this->display('attachment+dialogmyattachmentcategory');
		}else{
			$this->display('attachment+myattachmentcategory');
		}
	}

}
