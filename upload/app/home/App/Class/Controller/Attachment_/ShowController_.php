<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   附件显示($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	protected $_oAttachment=null;
	
	public function index(){
		$nAttachmentid=intval(G::getGpc('id','G'));

		if(empty($nAttachmentid)){
			$this->E(Dyhb::L('你没有指定要查看的附件','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nAttachmentid)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('你要查看的文件不存在','Controller/Attachment'));
		}

		$arrOptionData=$GLOBALS['_cache_']['home_option'];

		$this->_oAttachment=$oAttachment;

		// 取得个人主页
		$oUserprofile=UserprofileModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();

		// 读取评论列表
		$arrWhere=array();
		$arrWhere['attachmentcomment_parentid']=0;
		$arrWhere['attachmentcomment_status']=1;
		$arrWhere['attachment_id']=$nAttachmentid;

		if($GLOBALS['___login___']['user_id']!=$oAttachment['user_id']){
			$arrWhere['attachmentcomment_auditpass']=1;
			$this->assign('bAuditpass',false);
		}else{
			$this->assign('bAuditpass',true);
		}

		$nTotalRecord=AttachmentcommentModel::F()->where($arrWhere)->all()->getCounts();
		$oPage=Page::RUN($nTotalRecord,$arrOptionData['homefreshcomment_list_num'],G::getGpc('page','G'));
		$arrAttachmentcommentLists=AttachmentcommentModel::F()->where($arrWhere)->all()->order('`create_dateline` DESC')->limit($oPage->returnPageStart(),$arrOptionData['homefreshcomment_list_num'])->getAll();

		$this->assign('oAttachment',$oAttachment);
		$this->assign('sUsersite',$oUserprofile['userprofile_site']);
		$this->assign('nDisplaySeccode',$GLOBALS['_cache_']['home_option']['seccode_comment_status']);
		$this->assign('nTotalAttachmentcomment',$nTotalRecord);
		$this->assign('sPageNavbar',$oPage->P('pagination','li','active'));
		$this->assign('arrAttachmentcommentLists',$arrAttachmentcommentLists);

		$this->display('attachment+show');
	}

	public function show_attachment($oAttachment){
		$sAttachmentType=Attachment_Extend::getAttachmenttype($oAttachment);

		if(in_array($sAttachmentType,array('img','swf','wmp','mp3','qvod','flv','url'))){
			if(is_callable(array($this,'show_'.$sAttachmentType))){
				call_user_func(array($this,'show_'.$sAttachmentType),$oAttachment);
			}else{
				Dyhb::E('callback not exist');
			}
		}else{
			$this->show_download($oAttachment);
		}
	}

	public function show_img($oAttachment){
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showimg');
	}

	public function show_download($oAttachment){
		$this->assign('sAttachmentIcon',__PUBLIC__.'/images/common/media/download.gif');
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showdownload');
	}

	public function show_url($oAttachment){
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showurl');
	}

	public function show_swf($oAttachment){
		$this->assign('sAttachmentIcon',__PUBLIC__.'/images/common/media/swf.gif');
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showswf');
	}
	public function show_flv($oAttachment){
		$this->assign('sAttachmentIcon',__PUBLIC__.'/images/common/media/swf.gif');
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showflv');
	}

	public function show_wmp($oAttachment){
		$this->assign('sAttachmentIcon',__PUBLIC__.'/images/common/media/wmp.gif');
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showwmp');
	}

	public function show_qvod($oAttachment){
		$this->assign('sAttachmentIcon',__PUBLIC__.'/images/common/media/qvod.gif');
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showqvod');
	}

	public function show_mp3($oAttachment){
		$this->assign('sAttachmentIcon',__PUBLIC__.'/images/common/media/mp3.gif');
		$this->assign('oAttachment',$oAttachment);

		$this->display('attachment+showmp3');
	}

	public function mp3list(){
		header("Content-Type: text/xml; charset=utf-8");
		
		$nAttachmentcategoryid=intval(G::getGpc('cid','G'));
		$nUserid=intval(G::getGpc('uid','G'));
		
		$oUser=UserModel::F('user_id=? AND user_status=1',$nUserid)->getOne();
		if(empty($oUser['user_id'])){
			return false;
		}

		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
				<playlist version=\"1\" xmlns=\"http://xspf.org/ns/0/\">
					<title>{$oUser['user_name']}".Dyhb::L('专辑','Controller/Attachment')."</title>
					<creator>Dew</creator>
					<link>{$GLOBALS['_option_']['site_url']}</link>
					<info>{$oUser['user_name']}".Dyhb::L('专辑','Controller/Attachment')."</info>
					<image></image>
					<trackList>";
		
		if($nUserid>0){
			$arrAttachments=AttachmentModel::F('user_id=? AND attachmentcategory_id=? AND attachment_extension=?',$nUserid,$nAttachmentcategoryid,'mp3')->order('attachment_id DESC')->getAll();

			if($arrAttachments){
				foreach($arrAttachments as $oAttachment){
					$sAttachmentcategory=$oAttachment['attachmentcategory_id']>0?$oAttachment->attachmentcategory->attachmentcategory_name:Dyhb::L('未分类','Controller/Attachment');
					echo "<track>
							<location>".Attachment_Extend::getAttachmenturl($oAttachment)."</location>
							<creator>{$oAttachment['attachment_username']}</creator>
							<album>{$sAttachmentcategory}</album>
							<title>{$oAttachment['attachment_name']}</title>
							<annotation>{$oAttachment['attachment_description']}</annotation>
							<duration>{$oAttachment['attachment_size']}</duration>
							<image></image>
							<info></info>
							<link></link>
						</track>";
				}
			}
		}

		echo "</trackList>
			</playlist>";
	}

	public function get_attachmentcategory_playlist($oAttachment){
		return $GLOBALS['_option_']['site_url'].'/index.php?app=home&c=attachment&a=mp3list&cid='.
			$oAttachment['attachmentcategory_id'].'&uid='.$oAttachment['user_id'];
	}

	public function show_title_(){
		return $this->_oAttachment['attachment_name'];
	}

	public function show_keywords_(){
		return $this->show_title_();
	}

	public function show_description_(){
		return $this->show_title_();
	}

}
