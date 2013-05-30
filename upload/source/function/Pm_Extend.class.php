<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   短消息处理相关函数($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Pm_Extend{

	static public function ubb($sContent){
		// 解析 [pm]*[/pm]
		$sContent=preg_replace("/\[pm\]\s*(\S+?)\s*\[\/pm\]/ise","self::getOnePm(\"\\1\")",$sContent);

		// 过滤处理
		$sContent=str_replace(array('[hr]','<br>'),array('<hr/>','<br/>'),$sContent);
		$sContent=nl2br($sContent);

		return $sContent;
	}

	static public function getOnePm($nPmId){
		$oPm=PmModel::F('pm_id=? AND pm_status=1',$nPmId)->query();
		
		if(empty($oPm['pm_id'])){
			return '';
		}

		if(!in_array($GLOBALS['___login___']['user_id'],array($oPm['pm_msgfromid'],$oPm['pm_msgtoid']))){
			return '';
		}

		$sContent="<div class='reply-pm alert alert-success'>";

		$sContent.="------------------ ".Dyhb::L('原始短消息','__COMMON_LANG__@Common')." ------------------\r\n";

		$sContent.="<b>".Dyhb::L('发送者','__COMMON_LANG__@Common').
			"</b>   \"<a href=\"".Dyhb::U('home://space@?id='.$oPm['pm_msgfromid'])."\">{$oPm['pm_msgfrom']}</a>\";\r\n";

		$sContent.="<b>".Dyhb::L('发送时间','__COMMON_LANG__@Common').
			"</b>   ".date('Y'.Dyhb::L('年','__COMMON_LANG__@Common').
			'm'.Dyhb::L('月','__COMMON_LANG__@Common').
			'd'.Dyhb::L('日','__COMMON_LANG__@Common').
			' H:i',$oPm['create_dateline'])."\r\n";

		$sPmUrl='';
		if($oPm['pm_type']=='system'){
			$sPmUrl=Dyhb::U('home://pm/show?id='.$oPm['pm_id']);
		}elseif($oPm['pm_msgfromid']==$GLOBALS['___login___']['user_id']){
			$sPmUrl=Dyhb::U('home://pm/show?id='.$oPm['pm_id'].'&muid='.$oPm['pm_msgfromid']);
		}else{
			$sPmUrl=Dyhb::U('home://pm/show?id='.$oPm['pm_id'].'&uid='.$oPm['pm_msgtoid']);
		}
		
		$sContent.="<b>".Dyhb::L('主题','__COMMON_LANG__@Common').
			"</b>   <a href=\"{$sPmUrl}\">".
			($oPm['pm_subject']?$oPm['pm_subject']:Dyhb::L('该短消息暂时没有主题','__COMMON_LANG__@Common'))."</a>\r\n";

		if($oPm['pm_type']=='user'){
			$sContent.="<b>".Dyhb::L('收件人','__COMMON_LANG__@Common').
				"</b>   \"<a href=\"".Dyhb::U('home://space@?id='.$oPm['pm_msgtoid'])."\">".
				UserModel::getUsernameById($oPm['pm_msgtoid'])."</a>\";\r\n";
		}else{
			$sContent.="<blockquote><em>".Dyhb::L('本短消息属于系统短消息','__COMMON_LANG__@Common')."</em></blockquote>";
		}

		$sContent.="</div>";

		return $sContent;
	}

}
