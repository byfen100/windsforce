<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   首页展示($)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 取得附件列表
		$nTotalRecord=AttachmentModel::F()->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,10,G::getGpc('page','G'));
		$arrAttachments=AttachmentModel::F()->order('attachment_id DESC')->limit($oPage->returnPageStart(),10)->getAll();

		$this->assign('arrAttachments',$arrAttachments);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		// 取得推荐专辑
		$arrRecommendAttachmentcategorys=AttachmentcategoryModel::F('attachmentcategory_recommend=?',1)->order('attachmentcategory_compositor DESC')->limit(0,$GLOBALS['_option_']['attachment_recommendcategorynum'])->getAll();
		$this->assign('arrRecommendAttachmentcategorys',$arrRecommendAttachmentcategorys);

		// 取得推荐附件
		$arrRecommendAttachments=AttachmentModel::F('attachment_recommend=? AND attachment_extension IN (\'gif\',\'jpeg\',\'jpg\',\'png\',\'bmp\')',1)->order('attachment_id DESC')->limit(0,$GLOBALS['_option_']['attachment_recommendnum'])->getAll();
		$this->assign('arrRecommendAttachments',$arrRecommendAttachments);
		
		$this->display('attachment+index');
	}

}
