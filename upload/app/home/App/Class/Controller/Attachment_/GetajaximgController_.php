<?php
/* [WindsForce!] (C)WindsForce Team Start This From 2012.03.17.
   Ajax取得图片($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class GetajaximgController extends Controller{

	public function index(){
		$nAttachmentid=intval(G::getGpc('id','G'));
		$nAttachmentcategoryid=intval(G::getGpc('cid','G'));
		$nUserid=intval(G::getGpc('uid','G'));

		if($nUserid<1){
			return array();
		}

		// 展示一定数量的照片
		$nShowimgnum=intval($GLOBALS['_option_']['attachment_showimgnum']);

		$arrAttachments=AttachmentModel::F('user_id=? AND attachmentcategory_id=? AND attachment_extension in(\'gif\',\'jpeg\',\'jpg\',\'png\',\'bmp\')',$nUserid,$nAttachmentcategoryid)->order('attachment_id DESC')->getAll();

		$nIndex=0;
		$sContent='';
		if(is_array($arrAttachments)){
			foreach($arrAttachments as $nKey=>$oAttachment){
				if($nAttachmentid==$oAttachment['attachment_id']){
					$nIndex=$nKey;
				}
			}
		}

		// 取得展示数量索引
		$nAttachmentimgstartnum=$nIndex-$nShowimgnum;
		if($nAttachmentimgstartnum<0){
			$nAttachmentimgstartnum=0;
		}

		$nAttachmentimgendnum=$nIndex+$nShowimgnum;

		$arrShowimgid=array();
		if(is_array($arrAttachments)){
			foreach($arrAttachments as $nKey=>$oAttachment){
				if($nKey>=$nAttachmentimgstartnum && $nKey<=$nAttachmentimgendnum){
					$arrShowimgid[]=$oAttachment['attachment_id'];
					
					$sContent.='<li>
							<a href="'.__ROOT__.'/data/upload/attachment/'.$oAttachment['attachment_savepath'].'/'.$oAttachment['attachment_savename'].'">
								<img height="60px" src="'.Attachment_Extend::getAttachmentPreview($oAttachment).'" title="'.$oAttachment['attachment_name'].'" alt="'.$oAttachment['attachment_alt'].'" class="image'.$oAttachment['attachment_id'].'">
							</a>
						</li>';
				}
			}
		}

		// 取得新的索引
		if(is_array($arrShowimgid)){
			foreach($arrShowimgid as $nKey=>$nId){
				if($nAttachmentid==$nId){
					$nIndex=$nKey;
				}
			}
		}

		exit(json_encode(array('index'=>$nIndex,'content'=>$sContent)));
	}

}
