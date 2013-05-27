<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   Ubb代码解析($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

/** 导入附件扩展函数 */
if(!Dyhb::classExists('Attachment_Extend')){
	require_once(Core_Extend::includeFile('function/Attachment_Extend'));
}

class Ubb2html{
	
	public $_sContent='';
	public $_sLoginurl='';
	public $_sRegisterurl='';
	public $_nOuter=0;
	public $_bHomefreshmessage=true;

	public function __construct($arrData=array()){
		if(isset($arrData[0])){
			$this->_sContent=$arrData[0];
		}

		$this->_sLoginurl=Core_Extend::windsforceOuter('app=home&c=public&a=login&referer='.urlencode(__SELF__));
		$this->_sRegisterurl=Core_Extend::windsforceOuter('app=home&c=public&a=register&referer='.urlencode(__SELF__));

		if(isset($arrData[1])){
			$this->_bHomefreshmessage=$arrData[1];
		}

		if(isset($arrData[2])){
			$this->_nOuter=$arrData[2];
		}
	}

	public function convert($sContent=null){
		if($sContent===null){
			$sContent=$this->_sContent;
		}

		// 解析隐藏标签
		if($GLOBALS['___login___']===false){
			$sContent=preg_replace(
				"/\[hide\](.+?)\[\/hide\]/is",
				$this->needLogin(),
				$sContent
			);
		}else{
			$sContent=str_replace(array('[hide]','[/hide]'),'',$sContent);
		}

		// 解析特殊标签
		$sContent=str_replace(array('{','}'),array('&#123;','&#125;'),$sContent);
		
		// 换行和分割线
		$arrBasicUbbSearch=array('[hr]','<br>','[br]');
		$arrBasicUbbReplace=array('<hr/>','<br/>','<br/>');
		$sContent=str_replace($arrBasicUbbSearch,$arrBasicUbbReplace,$sContent);
		
		// URL和图像标签
		$sContent=preg_replace(
			"/\[url=([^\[]*)\]\[img(align=L| align=M| align=R)?(width=[0-9]+)?(height=[0-9]+)?\]\s*(\S+?)\s*\[\/img\]\[\/url\]/ise",
			"\$this->makeimgWithurl('\\1','\\2','\\3','\\4','\\5')",
			$sContent
		);
		
		$sContent=preg_replace(
			"/\[img(align=L| align=M| align=R)?(width=[0-9]+)?(height=[0-9]+)?\]\s*(\S+?)\s*\[\/img\]/ise",
			"\$this->makeImg('\\1','\\2','\\3','\\4')",
			$sContent
		);
		
		if($GLOBALS['_option_']['ubb_content_autoaddlink']==1){
			$sContent=preg_replace("/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|rtsp|mms|callto|ed2k):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i","[autourl]\\1\\3[/autourl]",$sContent);
		}
		
		$arrRegUbbSearch=array(
			"/\[size=([^\[\<]+?)\](.+?)\[\/size\]/ie",
			"/\s*\[quote\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is",
			"/\s*\[quote=(.+?)\][\n\r]*(.+?)[\n\r]*\[\/quote\]\s*/is",
			"/\s*\[code\][\n\r]*(.+?)[\n\r]*\[\/code\]\s*/ie",
			"/\[autourl\]([^\[]*)\[\/autourl\]/ie",
			"/\[url\]([^\[]*)\[\/url\]/ie",
			"/\[url=([^\[]*)\](.+?)\[\/url\]/ie",
			"/\[email\]([^\[]*)\[\/email\]/is",
			"/\[acronym=([^\[]*)\](.+?)\[\/acronym\]/is",
			"/\[color=([a-zA-Z0-9#]+?)\](.+?)\[\/color\]/i",
			"/\[font=([^\[\<:;\(\)=&#\.\+\*\/]+?)\](.+?)\[\/font\]/i",
			"/\[p align=([^\[\<]+?)\](.+?)\[\/p\]/i",
			"/\[b\](.+?)\[\/b\]/i",
			"/\[i\](.+?)\[\/i\]/i",
			"/\[u\](.+?)\[\/u\]/i",
			"/\[blockquote\](.+?)\[\/blockquote\]/i",
			"/\[strong\](.+?)\[\/strong\]/i",
			"/\[strike\](.+?)\[\/strike\]/i",
			"/\[sup\](.+?)\[\/sup\]/i",
			"/\[sub\](.+?)\[\/sub\]/i",
			"/\s*\[php\][\n\r]*(.+?)[\n\r]*\[\/php\]\s*/ie",
			"/\[fly\](.+?)\[\/fly\]/i",
		);
		
		$arrRegUbbReplace=array(
			"\$this->makeFontsize('\\1','\\2')",
			$this->template(Dyhb::L('引用','__COMMON_LANG__@Class/Ubb2html'),"\\1"),
			$this->template(Dyhb::L('引用自','__COMMON_LANG__@Class/Ubb2html')." \\1","\\2"),
			"\$this->makeCode('\\1')",
			"\$this->makeUrl('\\1',1)",
			"\$this->makeUrl('\\1','0')",
			"\$this->makeUrl('\\1','0','\\2')",
			"<a href=\"mailto:\\1\">\\1</a>",
			"<acronym title=\"\\1\">\\2</acronym>",
			"<span style=\"color: \\1;\">\\2</span>",
			"<span style=\"font-family: \\1;\">\\2</span>",
			"<p align=\"\\1\">\\2</p>",
			"<strong>\\1</strong>",
			"<em>\\1</em>",
			"<u>\\1</u>",
			"<blockquote>\\1</blockquote>",
			"<strong>\\1</strong>",
			"<del>\\1</del>",
			"<sup>\\1</sup>",
			"<sub>\\1</sub>",
			"\$this->xhtmlHighlightString('\\1')",
			"<marquee scrollamount=\"3\" behavior=\"alternate\" width=\"90%\">\\1</marquee>",
		);
		
		$sContent=preg_replace($arrRegUbbSearch,$arrRegUbbReplace,$sContent);
		
		// 解析上传附件
		$sContent=preg_replace("/\[attachment\]\s*(\S+?)\s*\[\/attachment\]/ise","\$this->makeAttachment('\\1','{$this->_nOuter}')",$sContent);

		// 解析音乐和视频格式
		$sContent=preg_replace("/\[mp3\]\s*(\S+?)\s*\[\/mp3\]/ise","\$this->makeMp3('\\1')",$sContent);
		$sContent=preg_replace("/\[video\]\s*(\S+?)\s*\[\/video\]/ise","\$this->makeVideo('\\1')",$sContent);

		// 解析话题和@user_name
		$sContent=preg_replace("/\[TAG\]#\s*(\S+?)\s*#\[\/TAG\]/ise","\$this->makeTag('\\1')",$sContent);
		$sContent=preg_replace("/\[MESSAGE\]@\s*(\S+?)\s*\[\/MESSAGE\]/ise","\$this->makeMessage('\\1')",$sContent);

		return $sContent;
	}

	public function convertUsersign($sContent=null){
		if($sContent===null){
			$sContent=$this->_sContent;
		}

		// 解析特殊标签
		$sContent=str_replace(array('{','}'),array('&#123;','&#125;'),$sContent);

		// 换行和分割线
		$arrBasicUbbSearch=array('[hr]','[br]');
		$arrBasicUbbReplace=array('<hr/>','<br/>');
		$sContent=str_replace($arrBasicUbbSearch,$arrBasicUbbReplace,$sContent);

		// URL和图像标签
		$sContent=preg_replace(
			"/\[url=([^\[]*)\]\[img(align=L| align=M| align=R)?(width=[0-9]+)?(height=[0-9]+)?\]\s*(\S+?)\s*\[\/img\]\[\/url\]/ise",
			"\$this->makeimgWithurl('\\1','\\2','\\3','\\4','\\5')",
			$sContent
		);
		
		$sContent=preg_replace(
			"/\[img(align=L| align=M| align=R)?(width=[0-9]+)?(height=[0-9]+)?\]\s*(\S+?)\s*\[\/img\]/ise",
			"\$this->makeImg('\\1','\\2','\\3','\\4')",
			$sContent
		);

		$sContent=preg_replace("/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|rtsp|mms|callto|ed2k):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i","[autourl]\\1\\3[/autourl]",$sContent);

		$arrRegUbbSearch=array(
			"/\[autourl\]([^\[]*)\[\/autourl\]/ie",
			"/\[url\]([^\[]*)\[\/url\]/ie",
			"/\[url=([^\[]*)\](.+?)\[\/url\]/ie",
			"/\[email\]([^\[]*)\[\/email\]/is",
			"/\[color=([a-zA-Z0-9#]+?)\](.+?)\[\/color\]/i",
			"/\[b\](.+?)\[\/b\]/i",
			"/\[i\](.+?)\[\/i\]/i",
			"/\[u\](.+?)\[\/u\]/i",
			"/\[strike\](.+?)\[\/strike\]/i",
			"/\[sup\](.+?)\[\/sup\]/i",
			"/\[sub\](.+?)\[\/sub\]/i",
		);
		
		$arrRegUbbReplace=array(
			"\$this->makeUrl('\\1',1)",
			"\$this->makeUrl('\\1','0')",
			"\$this->makeUrl('\\1','0','\\2')",
			"<a href=\"mailto:\\1\">\\1</a>",
			"<span style=\"color: \\1;\">\\2</span>",
			"<strong>\\1</strong>",
			"<em>\\1</em>",
			"<u>\\1</u>",
			"<del>\\1</del>",
			"<sup>\\1</sup>",
			"<sub>\\1</sub>",
		);
		
		$sContent=preg_replace($arrRegUbbSearch,$arrRegUbbReplace,$sContent);

		return $sContent;
	}


	public function makeimgWithurl($sUrl,$sAlignCode,$sWidthCode,$sHeightCode,$sSrc){
		return $this->makeImg($sAlignCode,$sWidthCode,$sHeightCode,$sSrc,$sUrl);
	}

	public function makeTag($sTag){
		if($GLOBALS['___login___']!==FALSE){
			$sUrl='home://ucenter/index';
		}else{
			$sUrl='home://stat/explore';
		}

		return '<a href="'.Dyhb::U($sUrl,array('key'=>$sTag),true).'">#'.$sTag.'#</a>';
	}

	public function makeMessage($sMessage){
		if($this->_bHomefreshmessage===true){
			if($GLOBALS['___login___']!==FALSE){
				$sUrl='home://ucenter/index';
			}else{
				$sUrl='home://stat/explore';
			}

			$sUrl=Dyhb::U($sUrl,array('at'=>$sMessage),true);
		}else{
			$oUser=UserModel::F('user_name=?',$sMessage)->getOne();
			if(!empty($oUser['user_id'])){
				$sUrl=Dyhb::U('home://space/index?id='.$oUser['user_id'],array(),true);
			}else{
				$sUrl="javascript:void(0);";
			}
		}

		return '<a href="'.$sUrl.'"'.($this->_bHomefreshmessage===true?'':' target="_blank"').'>@'.$sMessage.'</a>';
	}

	public function makeMp3($sSrc){
		$sExtName=G::getExtName($sSrc);

		if(!in_array($sExtName,array('mp3','wma','wav'))){
			return $sSrc;
		}

		if($sExtName!='mp3'){
			$sExtName='wmp';
		}

		return call_user_func(array($this,'music'.ucfirst($sExtName)),$sSrc,$sExtName);
	}

	public function musicMp3($sSrc,$sExtName){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/mp3.gif"/> ';
		$sTitle=$sIcon.Dyhb::L('Mp3文件','__COMMON_LANG__@Class/Ubb2html').' ('.$sExtName.')';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','mp3','".$sSrc."','240','20','');\" tips='{$sTitle}'>".$sIcon.basename($sSrc)."</a><div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sContent);
	}

	public function musicWmp($sSrc,$sExtName){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/wmp.gif"/> ';
		$sTitle=$sIcon.Dyhb::L('Windows Media Player文件','__COMMON_LANG__@Class/Ubb2html').' ('.$sExtName.')';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','wmp','".$sSrc."','600','405','');\" tips='{$sTitle}'>".$sIcon.basename($sSrc)."</a><div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sContent);
	}

	public function makeVideo($sSrc){
		$sExtName=G::getExtName($sSrc);

		if(!in_array($sExtName,array('swf','asf','wmv','avi','rm','rmvb','flv','mp4'))){
			return $sSrc;
		}
		
		if(in_array($sExtName,array('asf','wmv','avi'))){
			$sExtName='wmp';
		}

		if(in_array($sExtName,array('rm','rmvb'))){
			$sExtName='qvod';
		}

		if(in_array($sExtName,array('flv','mp4'))){
			$sExtName='flv';
		}

		return call_user_func(array($this,'video'.ucfirst($sExtName)),$sSrc,$sExtName);
	}

	public function videoSwf($sSrc,$sExtName){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
		$sTitle=$sIcon.Dyhb::L('Flash Player文件','__COMMON_LANG__@Class/Ubb2html').' ('.$sExtName.')';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','swf','".$sSrc."','600','405','');\" tips='{$sTitle}'>".$sIcon.basename($sSrc)."</a><div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sContent);
	}

	public function videoWmp($sSrc,$sExtName){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/wmp.gif"/> ';
		$sTitle=$sIcon.Dyhb::L('Windows Media Player文件','__COMMON_LANG__@Class/Ubb2html').' ('.$sExtName.')';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','wmp','".$sSrc."','600','405','');\" tips='{$sTitle}'>".$sIcon.basename($sSrc)."</a><div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sContent);
	}

	public function videoQvod($sSrc,$sExtName){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/qvod.gif"/> ';
		$sTitle=$sIcon.Dyhb::L('QVOD视频播放器','__COMMON_LANG__@Class/Ubb2html').' ('.$sExtName.')';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','qvod','".$sSrc."','600','405','');\" tips='{$sTitle}'>".$sIcon.basename($sSrc)."</a><div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sContent);
	}

	public function videoFlv($sSrc,$sExtName){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
		$sTitle=$sIcon.Dyhb::L('Flash Video Player文件','__COMMON_LANG__@Class/Ubb2html').' ('.$sExtName.')';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','flv','".$sSrc."','600','405','');\" tips='{$sTitle}'>".$sIcon.basename($sSrc)."</a><div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sContent);
	}

	public function makeImg($sAlignCode,$sWidthCode,$sHeightCode,$sSrc,$sUrl=''){
		if(empty($sUrl)){
			$sUrl=$sSrc;
		}
		
		// 对齐
		$sAlign=str_replace(' align=','',strtolower($sAlignCode));
		if($sAlign=='l'){
			$sShow=' align="left"';
		}elseif($sAlign=='r'){
			$sShow=' align="right"';
		}else{
			$sShow='';
		}
		
		// 宽度&高度
		$nWidth=str_replace(' width=','',strtolower($sWidthCode));
		if(!empty($nWidth)){
			$sShow.=' width="'.$nWidth.'"';
		}
		
		$nHeight=str_replace(' height=','',strtolower($sHeightCode));
		if(!empty($nHeight)){
			$sShow.=' height="'.$nHeight.'"';
		}
		
		return "<a href=\"{$sUrl}\" target=\"_blank\"><img src=\"{$sSrc}\" class=\"content-insert-image\" alt=\"".Dyhb::L('在新窗口浏览此图片','__COMMON_LANG__@Class/Ubb2html')."\" title=\"".Dyhb::L('在新窗口浏览此图片','__COMMON_LANG__@Class/Ubb2html')."\" border=\"0\" {$sShow}/></a>";
	}
	
	public function makeFontsize($nSize,$sWord){
		$sWord=stripslashes($sWord);
		$nSizeItem=array(0,8,10,12,14,18,24,36);
		$nSize=$nSizeItem[$nSize];
		
		return "<span style=\"font-size:{$nSize}px;\">{$sWord}</span>";
	}
	
	public function makeCode($sStr){
		$sTitle=Dyhb::L('代码','__COMMON_LANG__@Class/Ubb2html');
		
		$sStr=str_replace('[autourl]','',$sStr);
		$sStr=str_replace('[/autourl]','',$sStr);
		
		return $this->template($sTitle,$sStr,'ubb_code');
	}
	
	public function makeUrl($sUrl,$nAutolink=0,$sLinkText=''){
		if($nAutolink==1){
			$sGoToRealLink=Core_Extend::windsforceOuter('go='.(substr(strtolower($sUrl),0,4)=='www.'?urlencode("http://{$sUrl}"):urlencode($sUrl)),'urlredirect.php');
		}else{
			$sGoToRealLink=substr(strtolower($sUrl),0,4)=='www.'?"http://{$sUrl}":$sUrl;
		}
		
		$sUrlLink="<a href=\"{$sGoToRealLink}\" target=\"_blank\">";
		
		if(!empty($sLinkText)){
			$sUrl=$sLinkText;
		}else{
			if($GLOBALS['_option_']['ubb_content_shorturl'] && strlen($sUrl)>$GLOBALS['_option_']['ubb_content_urlmaxlen']){
				$nHalfMax=floor($GLOBALS['_option_']['ubb_content_urlmaxlen']/2);
				$sUrl=substr($sUrl,0,$nHalfMax).'...'.substr($sUrl,0-$nHalfMax);
			}
		}
		
		$sUrlLink.=$sUrl.'</a>';
		
		return $sUrlLink;
	}
	
	public function xhtmlHighlightString($sStr){
		$sTitle=Dyhb::L('代码','__COMMON_LANG__@Class/Ubb2html');

		$sHlt=@highlight_string($sStr,true);
		if(PHP_VERSION>'5'){
			return $this->template($sTitle,$sHlt,'ubb_code');
		}
		
		$sFon=str_replace(array('<font ','</font>'),array('<span ','</span>'),$sHlt);
		$sRet=preg_replace('#color="(.*?)"#','style="color: \\1"',$sFon);
		
		return $this->template($sTitle,$sHlt,'ubb_code');
	}

	public function makeAttachment($nId){
		if(!preg_match('/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',$nId)){
			$oAttachment=$this->getAttachment($nId);
			if($oAttachment===false){
				return '';
			}
		
			$sType=Attachment_Extend::getAttachmenttype($oAttachment);
		
			return call_user_func(array($this,'attachment'.ucfirst($sType)),$oAttachment,$this->_nOuter);
		}else{
			$sType=Attachment_Extend::getAttachmenttype($nId);
			$sExtension=G::getExtName($nId,2);

			return call_user_func(array($this,'attachment'.ucfirst($sType).'_'),$nId,$sExtension);
		}
	}

	protected function attachmentTips($oAttachment){
		$sTitlemore=Dyhb::L('已下载','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_download'].')'.Dyhb::L('次','__COMMON_LANG__@Class/Ubb2html');
		$sTitlemore.=' | '.G::changeFileSize($oAttachment['attachment_size']);
		$sTitlemore.=" | Upload Time:".date('Y-m-d H:i',$oAttachment['create_dateline']);

		return $sTitlemore;
	}
	
	public function attachmentImg($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sImg=Attachment_Extend::getAttachmenturl($oAttachment);
				
				if(APP_NAME==='wap'){
					$sImg=Core_Extend::wapImage($oAttachment['attachment_id']);

					return " <img src=\"{$sImg}\" class=\"content-insert-image\" alt=\"{$oAttachment['attachment_alt']}\" title=\"".$oAttachment['attachment_name']."\" border=\"0\"> ";
				}
				
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/viewimage.gif"/> ';
				$sTitle='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.$oAttachment['attachment_name'].'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';

				$sTitlemore=Dyhb::L('在新窗口浏览此图片','__COMMON_LANG__@Class/Ubb2html');
				$sTitlemore.=' | '.$this->attachmentTips($oAttachment);
				$sContent="<a onclick=\"updateDownload('".$oAttachment['attachment_id']."');\" href=\"{$sImg}\" target=\"_blank\"><img src=\"{$sImg}\" class=\"content-insert-image\" alt=\"{$oAttachment['attachment_alt']}\" title=\"{$sTitlemore}\" border=\"0\" tips='<p>{$sIcon}{$sTitle}</p><p>{$sTitlemore}</p>'/></a>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"{$sImg}\" target=\"_blank\"><img src=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" class=\"content-insert-image\" alt=\"".Dyhb::L('在新窗口浏览此图片','__COMMON_LANG__@Class/Ubb2html')."\" title=\"".Dyhb::L('在新窗口浏览此图片','__COMMON_LANG__@Class/Ubb2html')."\" border=\"0\"/>{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentImg_($sUrl,$sExtension){
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/viewimage.gif"/> ';
		$sTitle=$sTitle.'<a href="'.$sUrl.'" target="_blank">'.$sUrl.'</a> ('.$sExtension.')';
		
		$sContent="<a href=\"{$sUrl}\" target=\"_blank\"><img src=\"{$sUrl}\" class=\"content-insert-image\" title=\"".Dyhb::L('在新窗口浏览此图片','__COMMON_LANG__@Class/Ubb2html')."\" border=\"0\" tips='{$sTitle}'></a>";
		
		return $this->template($sContent);
	}
	
	public function attachmentSwf($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.Dyhb::L('Flash Player文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';

				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','swf','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\" tips='<p>{$sTitle}</p><p>{$sTitlemore}</p>'>{$sIcon}{$oAttachment['attachment_name']}</a><div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentSwf_($sUrl,$sExtension){
		return self::videoSwf($sUrl,$sExtension);
	}

	public function attachmentWmp($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/wmp.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.Dyhb::L('Windows Media Player文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
				
				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','wmp','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\" tips='<p>{$sTitle}</p><p>{$sTitlemore}</p>'>{$sIcon}{$oAttachment['attachment_name']}</a><div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentWmp_($sUrl,$sExtension){
		return self::musicWmp($sUrl,$sExtension);
	}

	public function attachmentMp3($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/mp3.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.Dyhb::L('Mp3文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
				
				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','mp3','".Attachment_Extend::getAttachmenturl($oAttachment)."','240','20','');updateDownload('".$oAttachment['attachment_id']."');\" tips='<p>{$sTitle}</p><p>{$sTitlemore}</p>'>{$sIcon}{$oAttachment['attachment_name']}</a><div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentMp3_($sUrl,$sExtension){
		return self::musicMp3($sUrl,$sExtension);
	}

	public function attachmentQvod($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/qvod.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.Dyhb::L('QVOD视频播放器','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
				
				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','qvod','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\" tips='<p>{$sTitle}</p><p>{$sTitlemore}</p>'>{$sIcon}{$oAttachment['attachment_name']}</a><div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentQvod_($sUrl,$sExtension){
		return self::videoQvod($sUrl,$sExtension);
	}

	public function attachmentFlv($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.Dyhb::L('Flash Video Player文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
				
				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','flv','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\" tips='<p>{$sTitle}</p><p>{$sTitlemore}</p>'>{$sIcon}{$oAttachment['attachment_name']}</a><div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentFlv_($sUrl,$sExtension){
		return self::videoFlv($sUrl,$sExtension);
	}

	public function attachmentUrl($oAttachment,$nOuter=0){
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/url.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.$oAttachment['attachment_extension'].Dyhb::L('文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
				
				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a href=\"".Attachment_Extend::getAttachmenturl($oAttachment)."\" title=\"{$sTitlemore}\" target=\"_blank\" tips='<p>{$sTitle}</p><p>{$sTitlemore}</p>'>{$sIcon}{$oAttachment['attachment_name']}</a>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentUrl_($sUrl,$sExtension){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/url.gif"/> ';
		$sTitle=$sIcon.'<a href="'.$sUrl.'" target="_blank">'.$sExtension.Dyhb::L('文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$sExtension.')';
		
		$sContent="<a href=\"".$sUrl."\" target=\"_blank\" tips='{$sTitle}'>{$sIcon}{$sUrl}</a>";
		
		return $this->template($sContent);
	}

	public function attachmentDownload($oAttachment,$nOuter=0){
		if($nOuter==0){
			if(1/*$GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE*/){
				return $this->needLogin();
			}else{
				$sIcon='<img src="'.__PUBLIC__.'/images/common/media/download.gif"/> ';
				$sTitle=$sIcon.'<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.$oAttachment['attachment_extension'].Dyhb::L('下载文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$oAttachment['attachment_extension'].')';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">'.Dyhb::L('评论','__COMMON_LANG__@Class/Ubb2html').'('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
				
				$sTitlemore=$this->attachmentTips($oAttachment);
				$sContent="<a onclick=\"updateDownload('".$oAttachment['attachment_id']."');\" href=\"".Attachment_Extend::getAttachmenturl($oAttachment)."\" title=\"{$sTitlemore}\" target=\"_blank\" tips='<div class=\"attachment_tips\"><p>{$sTitle}</p><p>{$sTitlemore}</p></div>'>{$sIcon}{$oAttachment['attachment_name']}</a>";
				
				return $this->template($sContent);
			}
		}else{
			return "<a href=\"".$this->getAttachmentouterurl($oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']} ('{$oAttachment['attachment_extension']}')</a>";
		}
	}

	public function attachmentDownload_($sUrl,$sExtension){
		$sIcon='<img src="'.__PUBLIC__.'/images/common/media/download.gif"/> ';
		$sTitle=$sIcon.'<a href="'.$sUrl.'" target="_blank">'.$sExtension.Dyhb::L('下载文件','__COMMON_LANG__@Class/Ubb2html').'</a> ('.$sExtension.')';

		$sContent="<a href=\"".$sUrl."\" target=\"_blank\" tips='{$sTitle}'>{$sIcon}{$sUrl}</a>";
		
		return $this->template($sContent);
	}
	
	public function getAttachment($nId){
		if(!preg_match("/[^\d-.,]/",$nId)){
			$oAttachment=AttachmentModel::F('attachment_id=?',$nId)->query();
		}else{
			$oAttachment=AttachmentModel::F('attachment_savename=?',$nId)->query();
		}
		
		if(empty($oAttachment['attachment_id'])){
			return false;
		}
		
		return $oAttachment;
	}
	
	protected function needLogin(){
		return $this->template(
					Dyhb::L('这部分内容只能在登入之后看到。请先','__COMMON_LANG__@Class/Ubb2html').' <a onclick="ajaxLogin(\'\',\''.$this->_sRegisterurl.'\');" href="javascript:void(0);">'.Dyhb::L('注册','__COMMON_LANG__@Class/Ubb2html').'</a> '.Dyhb::L('或者','__COMMON_LANG__@Class/Ubb2html').' <a onclick="ajaxRegister(\'\',\''.$this->_sLoginurl.'\');" href="javascript:void(0);">'.Dyhb::L('登录','__COMMON_LANG__@Class/Ubb2html').'</a>',
					Dyhb::L('隐藏内容','__COMMON_LANG__@Class/Ubb2html'),
					'hide_ubb_box'
				);
	}

	protected function template($sContent,$sTitle='',$sId='common_ubb_box'){
		if(APP_NAME==='admin'){
			return <<<WINDSFORCE
				<div class="ubb_media_box {$sId}" style="overflow:hidden;width:100%;">
					<p>{$sContent}</p>
				</div>
WINDSFORCE;
		}

		if($sTitle){
			$sTitle=<<<WINDSFORCE
				<div class="ubbmediabox_title">
					{$sTitle}
				</div>
WINDSFORCE;
		}
		
		return <<<WINDSFORCE
		<div class="ubb_media_box {$sId}" style="overflow:hidden;word-wrap:break-word; word-break;break-all;">
			{$sTitle}
			<div class="ubbmediabox_content">
				{$sContent}
			</div>
		</div>
WINDSFORCE;
	}

	protected function getAttachmentouterurl($nId){
		return Dyhb::U('home://attachment/show?id='.$nId,array(),true);
	}

}
