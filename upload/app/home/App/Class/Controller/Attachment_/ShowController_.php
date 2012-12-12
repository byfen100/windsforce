<?php
/* [WindsForce!] (C)WindsForce Studio start this From 2012.03.17.
   附件显示($)*/

!defined('DYHB_PATH') && exit;

class ShowController extends Controller{

	public function index(){
		$nAttachmentid=intval(G::getGpc('id','G'));

		if(empty($nAttachmentid)){
			$this->E(Dyhb::L('你没有指定要查看的附件','Controller/Attachment'));
		}

		$oAttachment=AttachmentModel::F('attachment_id=?',$nAttachmentid)->getOne();
		if(empty($oAttachment['attachment_id'])){
			$this->E(Dyhb::L('你要查看的文件不存在','Controller/Attachment'));
		}

		$this->assign('oAttachment',$oAttachment);

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

}
