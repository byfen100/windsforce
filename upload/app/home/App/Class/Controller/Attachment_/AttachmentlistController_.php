<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   最近附件展示（包括推荐附件和推荐相片）($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class AttachmentlistController extends Controller{

	public function index(){
		$nAttachmentcategoryid=G::getGpc('cid','G');
		$nRecommend=intval(G::getGpc('recommend','G'));
		$sType=trim(G::getGpc('type','G'));
		$nPhoto=G::getGpc('photo','G');
		$nRecommend=intval(G::getGpc('recommend','G'));
		$nDialog=intval(G::getGpc('dialog'));
		$sFunction=trim(G::getGpc('function'));
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

		if($nRecommend==1){
			$arrWhere['attachment_recommend']=1;
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

		// 取得附件列表
		if($nDialog==1){
			$nEverynum=$GLOBALS['_option_']['attachment_dialogattachmentnum'];
		}else{
			$nEverynum=$GLOBALS['_option_']['attachment_attachmentnum'];
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
			$this->display('attachment+dialogattachment');
		}else{
			$this->display('attachment+attachment');
		}
	}

	public function attachment_title_(){
		if(G::getGpc('recommend','G')==1){
			if(G::getGpc('photo','G')==1){
				return Dyhb::L('推荐照片','Controller/Attachment');
			}else{
				return Dyhb::L('推荐附件','Controller/Attachment');
			}
		}else{
			return Dyhb::L('最新附件','Controller/Attachment');
		}
	}

	public function attachment_keywords_(){
		return $this->attachment_title_();
	}

	public function attachment_description_(){
		return $this->attachment_title_();
	}

}
