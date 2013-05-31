<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   首页展示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class IndexController extends Controller{

	public function index(){
		// 取得附件列表
		$nHomeattachmentlistnum=intval($GLOBALS['_option_']['attachment_homeattachmentlistnum']);
		if($nHomeattachmentlistnum<1){
			$nHomeattachmentlistnum=1;
		}
		
		$nTotalRecord=AttachmentModel::F()->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$nHomeattachmentlistnum,G::getGpc('page','G'));
		$arrAttachments=AttachmentModel::F()->order('attachment_id DESC')->limit($oPage->returnPageStart(),$nHomeattachmentlistnum)->getAll();

		$this->assign('arrAttachments',$arrAttachments);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));

		// 取得推荐专辑
		$nRecommendcategorynum=intval($GLOBALS['_option_']['attachment_recommendcategorynum']);
		if($nRecommendcategorynum<1){
			$nRecommendcategorynum=1;
		}

		$arrRecommendAttachmentcategorys=AttachmentcategoryModel::F('attachmentcategory_recommend=?',1)->order('attachmentcategory_sort DESC')->limit(0,$nRecommendcategorynum)->getAll();
		$this->assign('arrRecommendAttachmentcategorys',$arrRecommendAttachmentcategorys);

		// 取得推荐附件
		$nRecommendnum=intval($GLOBALS['_option_']['attachment_recommendnum']);
		if($nRecommendnum<1){
			$nRecommendnum=1;
		}

		$arrRecommendAttachments=AttachmentModel::F('attachment_recommend=? AND attachment_extension IN (\'gif\',\'jpeg\',\'jpg\',\'png\',\'bmp\')',1)->order('attachment_id DESC')->limit(0,$nRecommendnum)->getAll();
		$this->assign('arrRecommendAttachments',$arrRecommendAttachments);
		
		$this->display('attachment+index');
	}

	public function index_title_(){
		return Dyhb::L('我的相册','Controller');
	}

	public function index_keywords_(){
		return $this->index_title_();
	}

	public function index_description_(){
		return $this->index_title_();
	}

}
