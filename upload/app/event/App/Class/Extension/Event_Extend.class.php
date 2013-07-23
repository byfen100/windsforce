<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   活动相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Event_Extend{

	public static function getEventcover($oEvent){
		// 导入附件扩展函数
		if(!Dyhb::classExists('Apptheme_Extend')){
			require_once(Core_Extend::includeFile('function/Apptheme_Extend'));
		}
		
		if(!is_object($oEvent)){
			$oEvent=EventModel::F('event_status=1 AND event_id=?',$oEvent)->getOne();
		}

		if(empty($oEvent['event_id'])){
			return '';
		}

		if(empty($oEvent['event_cover'])){
			return Appt::path('cover.png');
		}else{
			$oAttachment=AttachmentModel::F('attachment_id=?',$oEvent['event_cover'])->getOne();
			if(!empty($oAttachment['attachment_id']) && in_array($oAttachment['attachment_extension'],array('png','bmp','gif','jpg','jpeg'))){
				return $GLOBALS['_option_']['site_url'].'/data/upload/attachment/'.
					($oAttachment['attachment_isthumb']?
					$oAttachment['attachment_thumbpath'].'/'.$oAttachment['attachment_thumbprefix']:
					$oAttachment['attachment_savepath'].'/').$oAttachment['attachment_savename'];
			}else{
				return Appt::path('cover.png');
			}
		}
	}

}
