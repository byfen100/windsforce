<?php
/* [NeedForBug!] (C)Dianniu From 2010.
   Ubb代码解析($)*/

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

	public function __construct($sContent,$nOuter=0){
		$this->_sContent=$sContent;
		$this->_sLoginurl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=public&a=login';
		$this->_sRegisterurl=$GLOBALS['_option_']['site_url'].'/index.php?app=home&c=public&a=register';
		$this->_nOuter=$nOuter;
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
		
		//if($GLOBALS['_option_']['ubb_content_autoaddlink']==1){
			$sContent=preg_replace("/(?<=[^\]a-z0-9-=\"'\\/])((https?|ftp|gopher|news|telnet|rtsp|mms|callto|ed2k):\/\/|www\.)([a-z0-9\/\-_+=.~!%@?#%&;:$\\()|]+)/i","[autourl]\\1\\3[/autourl]",$sContent);
		//}
		
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
			"/\s*\[php\][\n\r]*(.+?)[\n\r]*\[\/php\]\s*/ie"
		);
		
		$arrRegUbbReplace=array(
			"\$this->makeFontsize('\\1','\\2')",
			$this->template('引用',"\\1"),
			$this->template("引用自 \\1","\\2"),
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
			"\$this->xhtmlHighlightString('\\1')"
		);
		
		$sContent=preg_replace($arrRegUbbSearch,$arrRegUbbReplace,$sContent);
		
		// 解析上传附件
		$sContent=preg_replace("/\[attachment\]\s*(\S+?)\s*\[\/attachment\]/ise","\$this->makeAttachment('\\1','{$this->_nOuter}')",$sContent);

		// 解析音乐和视频格式
		$sContent=preg_replace("/\[mp3\]\s*(\S+?)\s*\[\/mp3\]/ise","\$this->makeMp3('\\1')",$sContent);
		$sContent=preg_replace("/\[video\]\s*(\S+?)\s*\[\/video\]/ise","\$this->makeVideo('\\1')",$sContent);

		return $sContent;
	}

	public function makeimgWithurl($sUrl,$sAlignCode,$sWidthCode,$sHeightCode,$sSrc){
		return $this->makeImg($sAlignCode,$sWidthCode,$sHeightCode,$sSrc,$sUrl);
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
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/mp3.gif"/> ';
		$sTitle.='Mp3文件';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','mp3','".$sSrc."','240','20','');\">".basename($sSrc)."</a>
					<div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sTitle,$sContent);
	}

	public function musicWmp($sSrc,$sExtName){
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/wmp.gif"/> ';
		$sTitle.='Windows Media Player文件';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','wmp','".$sSrc."','600','405','');\">".basename($sSrc)."</a>
					<div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sTitle,$sContent);
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
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
		$sTitle.='Flash Player文件';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','swf','".$sSrc."','600','405','');\">".basename($sSrc)."</a>
					<div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sTitle,$sContent);
	}

	public function videoWmp($sSrc,$sExtName){
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/wmp.gif"/> ';
		$sTitle.='Windows Media Player文件';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','wmp','".$sSrc."','600','405','');\">".basename($sSrc)."</a>
					<div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sTitle,$sContent);
	}

	public function videoQvod($sSrc,$sExtName){
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/qvod.gif"/> ';
		$sTitle.='QVOD视频播放器';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','qvod','".$sSrc."','600','405','');\">".basename($sSrc)."</a>
					<div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sTitle,$sContent);
	}

	public function videoFlv($sSrc,$sExtName){
		$sTitle='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
		$sTitle.='Flash Video Player文件';

		$sId=G::randString(6);
		
		$sContent="<a href=\"javascript:playmedia('player_{$sId}','flv','".$sSrc."','600','405','');\">".basename($sSrc)."</a>
					<div id=\"player_{$sId}\" style=\"display: none;\"></div>";

		return $this->template($sTitle,$sContent);
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
		
		return "<a href=\"{$sUrl}\" target=\"_blank\"><img src=\"{$sSrc}\" class=\"content-insert-image\" alt=\"在新窗口浏览此图片\" title=\"在新窗口浏览此图片\" border=\"0\" {$sShow}/></a>";
	}
	
	public function makeFontsize($nSize,$sWord){
		$sWord=stripslashes($sWord);
		$nSizeItem=array(0,8,10,12,14,18,24,36);
		$nSize=$nSizeItem[$nSize];
		
		return "<span style=\"font-size:{$nSize}px;\">{$sWord}</span>";
	}
	
	public function makeCode($sStr){
		$sStr=str_replace('[autourl]','',$sStr);
		$sStr=str_replace('[/autourl]','',$sStr);
		
		return "<div class=\"ubb_code\">{$sStr}</div>";
	}
	
	public function makeUrl($sUrl,$nAutolink=0,$sLinkText=''){
		if($nAutolink==1){
			$sGoToRealLink=$GLOBALS['_option_']['site_url'].'/urlredirect.php?go='.(substr(strtolower($sUrl),0,4)=='www.'?urlencode("http://{$sUrl}"):urlencode($sUrl));
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
		$sHlt=highlight_string($sStr,true);
		if(PHP_VERSION>'5'){
			return "<div class=\"ubb_code\" style=\"overflow: auto;\">{$sHlt}</div>";
		}
		
		$sFon=str_replace(array('<font ','</font>'),array('<span ','</span>'),$sHlt);
		$sRet=preg_replace('#color="(.*?)"#','style="color: \\1"',$sFon);
		
		return "<div class=\"ubb_code\" style=\"overflow: auto;\">{$sRet}</div>";
	}

	public function makeAttachment($nId){
		$oAttachment=$this->getAttachment($nId);
		if($oAttachment===false){
			return '';
		}
		
		$sType=Attachment_Extend::getAttachmenttype($oAttachment);
		
		return call_user_func(array($this,'attachment'.ucfirst($sType)),$oAttachment,$this->_nOuter);
	}
	
	public function attachmentImg($oAttachment,$nOuter=0){
		$sImg=Attachment_Extend::getAttachmenturl($oAttachment);
		
		if($nOuter==0){
			if($GLOBALS['_option_']['upload_loginuser_view']==1 && $GLOBALS['___login___']===FALSE){
				return $this->needLogin();
			}else{
				$sTitle='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.$oAttachment['attachment_name'].'</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
				$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';

				$sTitlemore='';
				$sTitlemore.=' | 已下载('.$oAttachment['attachment_download'].')次';
				$sTitlemore.=' | '.G::changeFileSize($oAttachment['attachment_size']);
				$sTitlemore.=" | Upload Time:".date('Y-m-d H:i',$oAttachment['create_dateline']);
				$sContent="<a onclick=\"updateDownload('".$oAttachment['attachment_id']."');\" href=\"{$sImg}\" target=\"_blank\"><img src=\"{$sImg}\" class=\"content-insert-image\" alt=\"{$oAttachment['attachment_alt']}\" title=\"在新窗口浏览此图片{$sTitlemore}\" \" border=\"0\"></a>";
				
				return $this->template($sTitle,$sContent);
			}
		}else{
			return "<a onclick=\"updateDownload('".$oAttachment['attachment_id']."');\" href=\"{$sImg}\" target=\"_blank\"><img src=\"{$sImg}\" class=\"content-insert-image\" alt=\"在新窗口浏览此图片\" title=\"在新窗口浏览此图片\" border=\"0\"/></a>";
		}
	}
	
	public function attachmentSwf($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
			$sTitle.='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">Flash Player文件</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','swf','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\">{$oAttachment['attachment_name']}</a>
						<div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
	}

	public function attachmentWmp($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<img src="'.__PUBLIC__.'/images/common/media/wmp.gif"/> ';
			$sTitle.='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">Windows Media Player文件</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','wmp','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\">{$oAttachment['attachment_name']}</a>
						<div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
	}

	public function attachmentMp3($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<img src="'.__PUBLIC__.'/images/common/media/mp3.gif"/> ';
			$sTitle.='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">Mp3文件</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','mp3','".Attachment_Extend::getAttachmenturl($oAttachment)."','240','20','');updateDownload('".$oAttachment['attachment_id']."');\">{$oAttachment['attachment_name']}</a>
						<div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
	}

	public function attachmentQvod($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<img src="'.__PUBLIC__.'/images/common/media/qvod.gif"/> ';
			$sTitle.='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">QVOD视频播放器</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','qvod','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\">{$oAttachment['attachment_name']}</a>
						<div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
	}

	public function attachmentFlv($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<img src="'.__PUBLIC__.'/images/common/media/swf.gif"/> ';
			$sTitle.='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">Flash Video Player文件</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sContent="<a href=\"javascript:playmedia('player_{$oAttachment['attachment_id']}','flv','".Attachment_Extend::getAttachmenturl($oAttachment)."','600','405','');updateDownload('".$oAttachment['attachment_id']."');\">{$oAttachment['attachment_name']}</a>
						<div id=\"player_{$oAttachment['attachment_id']}\" style=\"display: none;\"></div>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
	}

	public function attachmentUrl($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.$oAttachment['attachment_extension'].'文件</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sTitlemore='';
			$sTitlemore.='已下载('.$oAttachment['attachment_download'].')次';
			$sTitlemore.=' | '.G::changeFileSize($oAttachment['attachment_size']);
			$sTitlemore.=" | Upload Time:".date('Y-m-d H:i',$oAttachment['create_dateline']);
			
			$sContent="<a href=\"".Attachment_Extend::getAttachmenturl($oAttachment)."\" title=\"{$sTitlemore}\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a onclick=\"updateDownload('".$oAttachment['attachment_id']."');\" href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
	}

	public function attachmentDownload($oAttachment,$nOuter=0){
		if($nOuter==0){
			$sTitle='<a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'" target="_blank">'.$oAttachment['attachment_extension'].'下载文件</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://file@?id='.$oAttachment['attachment_id']).'#comments" target="_blank">评论('.$oAttachment['attachment_commentnum'].')</a>';
			$sTitle.=' | <a href="'.Dyhb::U('home://space@?id='.$oAttachment['user_id']).'" target="_blank">'.$oAttachment['attachment_username'].'</a>';
			
			$sTitlemore='';
			$sTitlemore.='已下载('.$oAttachment['attachment_download'].')次';
			$sTitlemore.=' | '.G::changeFileSize($oAttachment['attachment_size']);
			$sTitlemore.=" | Upload Time:".date('Y-m-d H:i',$oAttachment['create_dateline']);

			$sContent="<a onclick=\"updateDownload('".$oAttachment['attachment_id']."');\" href=\"".Attachment_Extend::getAttachmenturl($oAttachment)."\" title=\"{$sTitlemore}\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
			
			return $this->template($sTitle,$sContent);
		}else{
			return "<a href=\"".Dyhb::U('home://file@?id='.$oAttachment['attachment_id'])."\" target=\"_blank\">{$oAttachment['attachment_name']}</a>";
		}
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
					'隐藏内容',
					'这部分内容只能在登入之后看到。请先 <a href="'.$this->_sRegisterurl.'">注册</a> 或者 <a href="'.$this->_sLoginurl.'">登录</a>',
					'hidebox'
				);
	}

	protected function template($sTitle,$sContent,$sId='ubb_box'){
		return <<<NEEDFORBUG
			<div class="ubb_media_box {$sId}" style="overflow:hidden;width:100%;">
				<table width="100%" class="table" style="table-layout:fixed;word-wrap:break-word; word-break;break-all;">
					<thead>
						<tr>
							<th>{$sTitle}</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="background:none;">{$sContent}</td>
						</tr>
					</tbody>
				</table>
			</div>
NEEDFORBUG;
	}

}
