<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   系统核心函数文件($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class Core_Extend{

	static public function loginInformation(){
		$arrUserData=UserModel::M()->authData();
		if(UserModel::M()->isBehaviorError()){
			G::E(UserModel::M()->getBehaviorErrorMessage());
		}

		if(empty($arrUserData['user_id'])){
			$GLOBALS['___login___']=false;
		}else{
			$GLOBALS['___login___']=$arrUserData;
			
			// Email验证信息确认
			$oUser=UserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
			$GLOBALS['___login___']['user_isverify']=$oUser['user_isverify'];

			// 读取用户统计信息
			$GLOBALS['___login___']['usercount']=UsercountModel::F('user_id=?',$arrUserData['user_id'])->asArray()->getOne();

			// 如果用户使用社会化帐号直接登录
			$GLOBALS['___login___']['socia_login']=false;

			if(Dyhb::cookie('SOCIA_LOGIN')==1 && Dyhb::cookie('SOCIA_LOGIN_TYPE')){
				if(!Dyhb::classExists('SociauserModel')){
					require_once(WINDSFORCE_PATH.'/source/extension/socialization/lib/mvc/SociauserModel.class.php');
				}

				$arrSociauser=SociauserModel::F('user_id=? AND sociauser_vendor=?',$arrUserData['user_id'],Dyhb::cookie('SOCIA_LOGIN_TYPE'))->asArray()->getOne();
				if($arrSociauser){
					$arrSociauser['logo']=__ROOT__.'/source/extension/socialization/static/images/'.$arrSociauser['sociauser_vendor'].'/'.$arrSociauser['sociauser_vendor'].'.gif';
					
					$GLOBALS['___login___']['socia']=$arrSociauser;
					$GLOBALS['___login___']['socia_login']=true;
				}
			}
		}

		return $GLOBALS['___login___'];
	}

	static public function isAdmin(){
		$arrAdmins=explode(',',$GLOBALS['_commonConfig_']['ADMIN_USERID']);

		if($GLOBALS['___login___']===false){
			return false;
		}

		if(in_array($GLOBALS['___login___']['user_id'],$arrAdmins)){
			return true;
		}else{
			return false;
		}
	}

	static public function page404($oThis){
		$arrAllMethod=get_class_methods($oThis);
		if(!in_array(ACTION_NAME,$arrAllMethod)){
			$oThis->page404();
		}
	}

	static public function title($oController){
		$page=intval(G::getGpc('page','G'));
		$page=$page>1?" | ".Dyhb::L('第','__COMMON_LANG__@Function/Core_Extend')." {$page} ".Dyhb::L('页','__COMMON_LANG__@Function/Core_Extend'):'';
		
		$sTitleAction=ACTION_NAME.'_title_';
		if(method_exists($oController,$sTitleAction) && $oController->{$sTitleAction}()){
			return $oController->{$sTitleAction}().' | '.$GLOBALS['_option_']['site_name'].$page;
		}else{
			return $GLOBALS['_option_']['site_name'].$page;
		}
	}

	static public function keywords($oController){
		$page=intval(G::getGpc('page','G'));
		$page=$page>1?",".Dyhb::L('第','__COMMON_LANG__@Function/Core_Extend')." {$page} ".Dyhb::L('页','__COMMON_LANG__@Function/Core_Extend'):'';

		$sKeywordsAction=ACTION_NAME.'_keywords_';
		if(method_exists($oController,$sKeywordsAction) && $oController->{$sKeywordsAction}()){
			return $oController->{$sKeywordsAction}().','.$GLOBALS['_option_']['site_name'].$page;
		}else{
			return '';
		}
	}

	static public function description($oController){
		$page=intval(G::getGpc('page','G'));
		$page=$page>1?" | ".Dyhb::L('第','__COMMON_LANG__@Function/Core_Extend')." {$page} ".Dyhb::L('页','__COMMON_LANG__@Function/Core_Extend'):'';

		$sDescriptionAction=ACTION_NAME.'_description_';
		if(method_exists($oController,$sDescriptionAction)){
			$sDescription=trim(strip_tags($oController->{$sDescriptionAction}()));
			$sDescription=preg_replace('/\s(?=\s)/','',$sDescription);// 接着去掉两个空格以上的
			$sDescription=preg_replace('/[\n\r\t]/','',$sDescription);// 最后将非空格替换为一个空格
			
			if($sDescription){
				return G::subString($sDescription,0,300).$page;
			}else{
				return $GLOBALS['_option_']['site_name'].$page;
			}
		}else{
			return $GLOBALS['_option_']['site_name'].$page;
		}
	}

	static public function seccode(){
		$arrOption=array(
			'seccode_image_width_size'=>$GLOBALS['_option_']['seccode_image_width_size'],
			'seccode_image_height_size'=>$GLOBALS['_option_']['seccode_image_height_size'],
			'seccode_adulterate'=>$GLOBALS['_option_']['seccode_adulterate'],
			'seccode_ttf'=>$GLOBALS['_option_']['seccode_ttf'],
			'seccode_tilt'=>$GLOBALS['_option_']['seccode_tilt'],
			'seccode_background'=>$GLOBALS['_option_']['seccode_background'],
			'seccode_image_background'=>$GLOBALS['_option_']['seccode_image_background'],
			'seccode_color'=>$GLOBALS['_option_']['seccode_color'],
			'seccode_size'=>$GLOBALS['_option_']['seccode_size'],
			'seccode_shadow'=>$GLOBALS['_option_']['seccode_shadow'],
			'seccode_animator'=>$GLOBALS['_option_']['seccode_animator'],
			'seccode_norise'=>$GLOBALS['_option_']['seccode_norise'],
			'seccode_curve'=>$GLOBALS['_option_']['seccode_curve'],
		);

		if($GLOBALS['_option_']['seccode_type']==3){
			$arrOption['seccode_bitmap']=1;
		}elseif($GLOBALS['_option_']['seccode_type']==2){
			$arrOption['seccode_chinesecode']=1;
		}
		
		G::seccode($arrOption,($GLOBALS['_option_']['seccode_type']==2?1:0),($GLOBALS['_option_']['seccode_ttf']?1:2));
	}

	static public function checkSeccode($sSeccode){
		return G::checkSeccode($sSeccode);
	}
	
	static public function avatar($nUid,$sType='middle'){
		if($GLOBALS['___login___']['socia_login']===true && $GLOBALS['___login___']['user_id']==$nUid){
			if($sType=='big' || $sType=='origin'){
				return $GLOBALS['___login___']['socia']['sociauser_img2'];
			}elseif($sType=='small'){
				return $GLOBALS['___login___']['socia']['sociauser_img'];
			}else{
				return $GLOBALS['___login___']['socia']['sociauser_img1'];
			}
		}
		
		$sPath=G::getAvatar($nUid,$sType);

		return is_file(WINDSFORCE_PATH.'/data/avatar/'.$sPath)?
			__ROOT__.'/data/avatar/'.$sPath:
			__PUBLIC__.'/images/avatar/noavatar_'.($sType=='origin'?'big':$sType).'.gif';
	}

	static public function avatars($nUserId=null){
		if($nUserId===null){
			$nUserId=$GLOBALS['___login___']['user_id'];
		}

		$arrAvatarInfo=array();

		$arrAvatarInfo['exist']=is_file(WINDSFORCE_PATH.'/data/avatar/'.G::getAvatar($nUserId,'big'))?true:false;
		$arrAvatarInfo['origin']=Core_Extend::avatar($nUserId,'origin');
		$arrAvatarInfo['big']=Core_Extend::avatar($nUserId,'big');
		$arrAvatarInfo['middle']=Core_Extend::avatar($nUserId,'middle');
		$arrAvatarInfo['small']=Core_Extend::avatar($nUserId,'small');

		return $arrAvatarInfo;
	}

	static public function doControllerAction($sPath,$sAction,$oParentcontroller=null){
		require_once(Core_Extend::includeController($sPath,$sClassController));

		$oClass=new $sClassController($oParentcontroller);
		$oClass->{$sAction}();
	}
	
	static public function includeController($sPath,&$sClassController){
		$sFilepath=APP_PATH.'/App/Class/Controller/';

		$arrValue=explode('@',$sPath);
		if(!isset($arrValue[1])){
			Dyhb::E('IncludeController parameter is error');
		}
		$sFilepath.=$arrValue[0].'_/';

		$arrValue=explode('/',$arrValue[1]);
		$sClassController=array_pop($arrValue).'Controller';
		$sClass=$sClassController.'_.php';
		$sFilepath.=($arrValue?implode('/',$arrValue).'/':'').$sClass;

		if(!is_file($sFilepath)){
			Dyhb::E(sprintf('Include Controller %s failed',$sFilepath));
		}

		return $sFilepath;
	}

	static public function loadCache($CacheNames,$bForce=false){
		static $arrLoadedCache=array();

		$CacheNames=is_array($CacheNames)?$CacheNames:array($CacheNames);
		$arrCaches=array();
		foreach($CacheNames as $sCacheName){
			if(!isset($arrLoadedCache[$sCacheName]) || $bForce){
				$arrCaches[]=$sCacheName;
				$arrLoadedCache[$sCacheName]=true;
			}
		}

		if(!empty($arrCaches)){
			$arrCacheDatas=self::cacheData($arrCaches);
			foreach($arrCacheDatas as $sCacheName=>$data){
				if($sCacheName=='option'){
					$GLOBALS['_option_']=$data;
				}else{
					$GLOBALS['_cache_'][$sCacheName]=$data;
				}
			}
		}

		return true;
	}

	static public function cacheData($CacheNames){
		static $bIsFilecache=null,$bAllowMem=null;

		if(!isset($bIsFilecache)){
			$bIsFilecache=$GLOBALS['_commonConfig_']['RUNTIME_CACHE_BACKEND']=='FileCache';
			$bAllowMem=self::memory('check');
		};

		$arrData=array();
		$CacheNames=is_array($CacheNames)?$CacheNames:array($CacheNames);
		if($bAllowMem){
			$arrNew=array();
			foreach($CacheNames as $sCacheName){
				$arrData[$sCacheName]=self::memory('get',$sCacheName);
				if($arrData[$sCacheName]===null){
					$arrData[$sCacheName]=null;
					$arrNew[]=$sCacheName;
				}
			}

			if(empty($arrNew)){
				return $arrData;
			}else{
				$CacheNames=$arrNew;
			}
		}

		if($bIsFilecache){
			$arrLostCaches=array();
			foreach($CacheNames as $sCacheName){
				$arrData[$sCacheName]=Dyhb::cache($sCacheName,'',array('cache_path'=>WINDSFORCE_PATH.'/data/~runtime/cache_/data'));
				if($arrData[$sCacheName]===false){
					$arrLostCaches[]=$sCacheName;
					if(!Dyhb::classExists('Cache_Extend')){
						require_once(Core_Extend::includeFile('function/Cache_Extend'));
					}
					Cache_Extend::updateCache($sCacheName);
				}
			}

			if(!$arrLostCaches){
				return $arrData;
			}
			$CacheNames=$arrLostCaches;
			unset($arrLostCaches);
		}

		$arrSyscaches=SyscacheModel::F(array('syscache_name'=>array('in',$CacheNames)))->getAll();
		foreach($arrSyscaches as $arrSyscache){
			$arrData[$arrSyscache['syscache_name']]=$arrSyscache['syscache_type']?unserialize($arrSyscache['syscache_data']):$arrSyscache['syscache_data'];
			$bAllowMem && (self::memory('set',$arrSyscache['syscache_name'],$arrData[$arrSyscache['syscache_name']]));
			if($bIsFilecache){
				Dyhb::cache($arrSyscache['syscache_name'],$arrData[$arrSyscache['syscache_name']],array('cache_path'=>WINDSFORCE_PATH.'/data/~runtime/cache_/data'));
			}
		}

		foreach($CacheNames as $sCacheName){
			if($arrData[$sCacheName]===null){
				$arrData[$sCacheName]=null;
				$bAllowMem && (self::memory('set',$sCacheName,array()));
			}
		}

		return $arrData;
	}

	public static function saveSyscache($sCacheName,$Data){
		static $bIsFilecache=null,$bAllowMem=null;

		if(!isset($bIsFilecache)){
			$bIsFilecache=$GLOBALS['_commonConfig_']['RUNTIME_CACHE_BACKEND']=='FileCache';
			$bAllowMem=self::memory('check');
		}

		if(is_array($Data)){
			$nType=1;
			$Data=serialize($Data);
		}else{
			$nType=0;
		}

		$oSyscacheModel=new SyscacheModel();
		$oSyscacheModel->syscache_name=$sCacheName;
		$oSyscacheModel->syscache_type=$nType;
		$oSyscacheModel->syscache_data=$Data;
		$oSyscacheModel->save(0,'replace');

		$bAllowMem && self::memory('delete',$sCacheName);

		$sCachefile=WINDSFORCE_PATH.'/data/~runtime/cache_/data/~@'.$sCacheName.'.php';
		$bIsFilecache && (is_file($sCachefile) && @unlink($sCachefile));
	}

	public static function memory($sAction,$sKey='',$Value=''){
		$bMemEnable=$GLOBALS['_commonConfig_']['RUNTIME_CACHE_BACKEND']!='FileCache';

		if($sAction=='check'){
			return $bMemEnable?$GLOBALS['_commonConfig_']['RUNTIME_CACHE_BACKEND']:'';
		}elseif($bMemEnable && in_array($sAction,array('set','get','delete'))){
			switch($sAction){
				case 'set': return Dyhb::cache($sKey,$Value); break;
				case 'get': return Dyhb::cache($sKey); break;
				case 'delete': return Dyhb::cache($sKey,null); break;
			}
		}

		return null;
	}
	
	static public function appLogo($sApp,$bHtml=false){
		$sLogo='';
		
		if(is_file(WINDSFORCE_PATH.'/app/'.$sApp.'/logo.png')){
			$sLogo=__ROOT__.'/app/'.$sApp.'/logo.png';
		}else{
			$sLogo=__ROOT__.'/app/logo.png';
		}
		
		if($bHtml===true){
			return "<img src=\"{$sLogo}\"";
		}else{
			return $sLogo;
		}
	}

	static public function includeFile($sFileName,$sApp=null,$sType='.class.php'){
		if(!empty($sApp)){
			$sIncludeFile='/app/'.$sApp.'/App/Class/'.($sType=='_.php'?'Extension/':'').$sFileName;
		}else{
			$sIncludeFile='/source/'.$sFileName;
		}

		return preg_match('/^[\w\d\/_]+$/i',$sIncludeFile)?realpath(WINDSFORCE_PATH.$sIncludeFile.$sType):false;
	}
	
	static function replaceSiteVar($sString,$arrReplaces=array()){
		$arrSiteVars=array(
			'{site_name}'=>$GLOBALS['_option_']['site_name'],
			'{site_description}'=>$GLOBALS['_option_']['site_description'],
			'{site_url}'=>$GLOBALS['_option_']['site_url'],
			'{time}'=>gmdate('Y-n-j H:i',CURRENT_TIMESTAMP),
			'{user_name}'=>$GLOBALS['___login___']?$GLOBALS['___login___']['user_name']:Dyhb::L('游客','__COMMON_LANG__@Function/Core_Extend'),
			'{user_nikename}'=>$GLOBALS['___login___']?
				($GLOBALS['___login___']['user_nikename']?$GLOBALS['___login___']['user_nikename']:$GLOBALS['___login___']['user_name']):
				Dyhb::L('游客','__COMMON_LANG__@Function/Core_Extend'),
			'{admin_email}'=>$GLOBALS['_option_']['admin_email']
		);
		
		$arrReplaces=array_merge($arrSiteVars,$arrReplaces);
		
		return str_replace(array_keys($arrReplaces),array_values($arrReplaces),$sString);
	}

	static public function getEvalValue($sValue){
		$arrMatches=array();

		if(preg_match("/{\s*(\S+?)\s*\}/ise",$sValue,$arrMatches)){
			@eval('$sValue='.$arrMatches[1].';');
			return $sValue;
		}else{
			return $sValue;
		}
	}

	static public function badword($sContent){
		if(empty($sContent)){
			return '';
		}

		if(!$GLOBALS['_option_']['badword_on']){
			return $sContent;
		}

		if(!isset($GLOBALS['_cache_']['badword'])){
			if(!Dyhb::classExists('Cache_Extend')){
				require(WINDSFORCE_PATH.'/source/function/Cache_Extend.class.php');
			}
			self::loadCache('badword');
		}

		foreach($GLOBALS['_cache_']['badword'] as $arrBadword){
			$sContent=preg_replace($arrBadword['regex'],$arrBadword['value'],$sContent);
		}

		return $sContent;
	}

	static public function isPostInt($value){
		return !preg_match("/[^\d-.,]/",trim($value,'\''));
	}
	
	static public function sortBy($sField){
		$sSort=strtolower(trim(G::getGpc('sort_','G')));
		
		if(empty($sSort) || $sSort=='asc'){
			$sSort='desc';
		}else{
			$sSort='asc';
		}

		return '?order_='.$sField.'&sort_='.$sSort;
	}
	
	static public function sortField($sName){
		if(G::getGpc('order_','G')==$sName && G::getGpc('sort_','G')=='asc'){
			echo "class=\"order_desc\"";
		}

		if(G::getGpc('order_','G')==$sName && G::getGpc('sort_','G')=='desc'){
			echo "class=\"order_asc\"";
		}
	}
	
	static public function isAlreadyFriend($nUserId){
		return FriendModel::isAlreadyFriend($nUserId,$GLOBALS['___login___']['user_id']);
	}

	static public function getBeginEndDay(){
		$nYear=date("Y");
		$nMonth=date("m");
		$nDay=date("d");

		$nDayBegin=mktime(0,0,0,$nMonth,$nDay,$nYear);//当天开始时间戳
		$nDayEnd=mktime(23,59,59,$nMonth,$nDay,$nYear);//当天结束时间戳

		return array($nDayBegin,$nDayEnd);
	}

	static public function segmentUsername($sUserName){
		if(empty($sUserName)){
			return '';
		}

		$sUserName=str_replace(',',';',$sUserName);
		$arrUsers=explode(';',$sUserName);

		return $arrUsers;
	}

	static public function getUploadSize($nSize=null){
		$nReturnSize=-1;
		$nUploadmaxfilesize=intval(ini_get('upload_max_filesize'));
		$nPostmaxsize=intval(ini_get('post_max_size'));
		
		$nPhpIni=($nUploadmaxfilesize<=$nPostmaxsize?$nUploadmaxfilesize:$nPostmaxsize)*1048576;

		if(is_null($nSize)){
			$nSize=$GLOBALS['_option_']['uploadfile_maxsize'];
		}

		if($nSize==-1){
			$nReturnSize=$nPhpIni;
		}else{
			if($nSize>=$nPhpIni){
				$nReturnSize=$nPhpIni;
			}else{
				$nReturnSize=$nSize;
			}
		}

		return $nReturnSize;
	}
	
	static public function aidencode($nId){
		static $sSidAuth='';

		$sSidAuth=$sSidAuth!=''?$sSidAuth:G::authcode(Dyhb::cookie($GLOBALS['_commonConfig_']['RBAC_DATA_PREFIX'].'hash'),false);
		
		return rawurlencode(base64_encode($nId.'|'.substr(md5($nId.md5($GLOBALS['_commonConfig_']['DYHB_AUTH_KEY']).
			CURRENT_TIMESTAMP),0,8).'|'.CURRENT_TIMESTAMP.'|'.$sSidAuth));
	}

	static public function updateCreditByAction($sAction,$nUserId=0,$arrExtraSql=array(),$nCoef=1,$nUpdate=1){
		if($nUserId<1){
			return false;
		}
		
		if(!Dyhb::classExists('Credit')){
			require_once(Core_Extend::includeFile('class/Credit'));
		}

		$oCredit=Dyhb::instance('Credit');

		if($arrExtraSql){
			$oCredit->_arrExtraSql=$arrExtraSql;
		}

		return $oCredit->execRule($sAction,$nUserId,$nCoef,$nUpdate);
	}

	static public function timeFormat($nDate){
		if($GLOBALS['_option_']['date_convert']==1){
			return G::smartDate($nDate,$GLOBALS['_option_']['time_format']);
		}else{
			return date($GLOBALS['_option_']['time_format'],$nDate);
		}
	}

	static public function editorInclude(){
		$arrLangmap=array(
			'Zh-cn'=>'zh_CN',
			'Zh-tw'=>'zh_TW',
			'En-us'=>'en',
			'Ar'=>'ar',
		);

		$sLangname=LANG_NAME?LANG_NAME:'Zh-cn';

		$sPublic=__PUBLIC__;
		$sKindeditorLang=is_file(WINDSFORCE_PATH.'/Public/js/editor/kindeditor/lang/'.$arrLangmap[$sLangname].'.js')?$arrLangmap[$sLangname]:'zh_CN';

		return <<<WINDSFORCE
		<script type="text/javascript">var sEditorLang='{$sKindeditorLang}';</script>
		<script src="{$sPublic}/js/editor/kindeditor/kindeditor-min.js" type="text/javascript"></script>
WINDSFORCE;
	}

	static public function deleteAppconfig($sApp=null,$bCleanCookie=true){
		if(is_null($sApp)){
			$arrSaveDatas=array();

			$arrWhere=array();
			$arrWhere['app_status']=1;
			$arrApps=AppModel::F()->where($arrWhere)->all()->query();
			if(is_array($arrApps)){
				foreach($arrApps as $oApp){
					$arrSaveDatas[]=$oApp['app_identifier'];
				}
			}
			$arrSaveDatas[]='admin';

			foreach($arrSaveDatas as $sTheApp){
				self::deleteAppconfig($sTheApp);
			}
		}else{
			$sApp=strtolower($sApp);

			$sAppConfigcachefile=WINDSFORCE_PATH.'/data/~runtime/app/'.$sApp.'/Config.php';
			if(is_file($sAppConfigcachefile)){
				@unlink($sAppConfigcachefile);
			}

			if($bCleanCookie===true){
				Dyhb::cookie('template',null,-1);
				Dyhb::cookie('language',null,-1);
			}
		}

		return true;
	}

	static public function changeAppconfig($Data,$sValue=''){
		if(is_array($Data)){
			foreach($Data as $sKey=>$sValue){
				self::changeAppconfig($sKey,$sValue);
			}
		}else{
			$sAppGlobalconfigFile=WINDSFORCE_PATH.'/config/Config.inc.php';
			$arrAppconfig=(array)(include $sAppGlobalconfigFile);

			$arrData=array();
			$arrData[$Data]=$sValue;

			$arrAppconfig=array_merge($arrAppconfig,$arrData);

			if(!file_put_contents($sAppGlobalconfigFile,
				"<?php\n /* DoYouHaoBaby Framework Config File,Do not to modify this file! */ \n return ".
				var_export($arrAppconfig,true).
				"\n?>")
			){
				Dyhb::E(Dyhb::L('全局配置文件 %s 不可写','__COMMON_LANG__@Function/Core_Extend',null,$sAppGlobalconfigFile));
			}

			self::deleteAppconfig();
		}
	}

	static public function template($sTemplate,$sApp=null,$sTheme=null){
		if(empty($sTheme)){
			$sTemplate=TEMPLATE_NAME.'/'.$sTemplate;
		}else{
			$sTemplate=$sTheme.'/'.$sTemplate;
		}

		if(!empty($sApp)){
			$sTemplatePath=WINDSFORCE_PATH.'/app/'.$sApp.'/Theme';
		}else{
			$sTemplatePath=WINDSFORCE_PATH.'/ucontent/theme';
		}

		$sUrl=$sTemplatePath.'/'.$sTemplate.'.html';

		if(is_file($sUrl)){
			return $sUrl;
		}

		if(defined('DOYOUHAOBABY_TEMPLATE_BASE') && empty($sTheme) && ucfirst(DOYOUHAOBABY_TEMPLATE_BASE)!==TEMPLATE_NAME){// 依赖模板 兼容性分析
			$sUrlTry=str_replace('heme/'.TEMPLATE_NAME.'/','heme/'.ucfirst(DOYOUHAOBABY_TEMPLATE_BASE).'/',$sUrl);
			if(is_file($sUrlTry)){
				return $sUrlTry;
			}
		}

		if(empty($sTheme) && 'Default'!==TEMPLATE_NAME){// Default模板 兼容性分析
			$sUrlTry=str_replace('heme/'.TEMPLATE_NAME.'/','heme/Default/',$sUrl);
			if(is_file($sUrlTry)){
				return $sUrlTry;
			}
		}

		Dyhb::E(sprintf('Template File %s is not exist',$sUrl));
	}

	static public function getStylePreview($Style,$sType='',$bAdmin=false,$sTemplate=''){
		if(empty($sTemplate)){
			if(!is_object($Style)){
				$Style=StyleModel::F('style_id=?',$Style)->getOne();
			}
			
			if(empty($Style['style_id'])){
				return self::getNoneimg();
			}
	
			$oTheme=ThemeModel::F('theme_id=?',$Style['theme_id'])->getOne();
			if(empty($oTheme['theme_id'])){
				return self::getNoneimg();
			}
			
			$sTemplate=ucfirst($oTheme['theme_dirname']);
		}else{
			$sTemplate=ucfirst(strtolower($sTemplate));
		}

		if($bAdmin===false){
			$sPreviewPath='ucontent/theme';
		}else{
			$sPreviewPath='admin/Theme';
		}

		if($sType=='large'){
			$sPreview='windsforce_preview_large';
		}elseif($sType=='mini'){
			$sPreview='windsforce_preview_mini';
		}else{
			$sPreview='windsforce_preview';
		}

		foreach(array('png','gif','jpg','jpeg') as $sExt){
			if(is_file(WINDSFORCE_PATH.'/'.$sPreviewPath.'/'.$sTemplate."/{$sPreview}.{$sExt}")){
				return __ROOT__.'/'.$sPreviewPath.'/'.$sTemplate."/{$sPreview}.{$sExt}";
				continue;
			}
		}

		return self::getNoneimg();
	}

	static public function getNoneimg(){
		return __PUBLIC__.'/images/common/none.gif';
	}

	static public function promotion(){
		$oPromotion=Dyhb::instance('PromotionController');
		$oPromotion->index();
	}

	static public function online(){
		// 忽略项
		$sAtpage=APP_NAME.'@'.MODULE_NAME.'@'.ACTION_NAME;
		if(MODULE_NAME==='api' || (APP_NAME.'@'.MODULE_NAME=='home@misc' && $sAtpage!='home@misc@ubb')){
			return;
		}

		// 在线基本数据
		$arrOnline['online_ip']=G::getIp();
		$arrOnline['online_activetime']=CURRENT_TIMESTAMP;
		$arrOnline['online_atpage']=APP_NAME.'@'.MODULE_NAME.'@'.ACTION_NAME;

		// 最大在线人数检测
		$nOnlinenum=OnlineModel::F()->all()->getCounts();

		$nOnlinemostnum=intval($GLOBALS['_option_']['online_mostnum']);
		if($nOnlinemostnum>0 && $nOnlinenum>$nOnlinemostnum){
			Dyhb::E(Dyhb::L('当前在线人数 %d 超过了网站最大负载量 %d','__COMMON_LANG__@Function/Core_Extend',null,$nOnlinenum,$nOnlinemostnum));
		}

		// 数据库对象
		$oDb=Db::RUN();

		if($GLOBALS['___login___']!==false){
			$arrOnline['user_id']=$GLOBALS['___login___']['user_id'];
			$arrOnline['online_username']=$GLOBALS['___login___']['user_name'];
			
			$oOnline=OnlineModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
			if(!empty($oOnline['user_id'])){
				$oDb->query('UPDATE '.OnlineModel::F()->query()->getTablePrefix().'online SET online_ip="'.$arrOnline['online_ip'].'",online_activetime="'.$arrOnline['online_activetime'].'",online_atpage="'.$arrOnline['online_atpage'].'" WHERE user_id='.$GLOBALS['___login___']['user_id']);
			}else{
				$oOnline=new OnlineModel($arrOnline);
				$oOnline->save(0);

				if($oOnline->isError()){
					Dyhb::E($oOnline->getErrorMessage());
				}
			}
		}else{
			$arrOnline['user_id']=0;
			$arrOnline['online_username']='';

			$oOnline=OnlineModel::F('user_id=0 AND online_ip=?',$arrOnline['online_ip'])->getOne();
			if(!empty($oOnline['online_ip'])){
				$oDb->query('UPDATE '.OnlineModel::F()->query()->getTablePrefix().'online SET online_ip="'.$arrOnline['online_ip'].'",online_activetime="'.$arrOnline['online_activetime'].'",online_atpage="'.$arrOnline['online_atpage'].'" WHERE user_id=0 AND online_ip="'.$arrOnline['online_ip'].'"');
			}else{
				$oOnline=new OnlineModel($arrOnline);
				$oOnline->save(0);

				if($oOnline->isError()){
					Dyhb::E($oOnline->getErrorMessage());
				}
			}
		}

		// 清理过期在线用户数据 && 记录会员的在线时间
		$arrOnlines=OnlineModel::F('user_id>0 AND online_activetime<?',(CURRENT_TIMESTAMP-$GLOBALS['_option_']['online_keeptime']*60))->getAll();
		if(is_array($arrOnlines)){
			foreach($arrOnlines as $oValue){
				if($GLOBALS['_option_']['online_stealtholtime']==0 && $oValue['online_isstealth']==1){
					continue;
				}

				// 写入数据
				$nOnlinetime=$oValue['online_activetime']-$oValue['create_dateline'];
				$nOnlinetime=round($nOnlinetime/3600,1);

				$oUsercount=UsercountModel::F('user_id=?',$oValue['user_id'])->getOne();
				if(!empty($oUsercount['user_id'])){
					$oUsercount->usercount_oltime=$oUsercount->usercount_oltime+$nOnlinetime;
					$oUsercount->save(0,'update');

					if($oUsercount->isError()){
						Dyhb::E($oUsercount->getErrorMessage());
					}
				}
			}
		}
		
		$oDb->query('DELETE FROM '.OnlineModel::F()->query()->getTablePrefix().'online WHERE online_activetime<'.(CURRENT_TIMESTAMP-$GLOBALS['_option_']['online_keeptime']*60));
	}

	static public function initFront(){
		// 配置&菜单&登陆信息
		Core_Extend::loadCache('option');
		Core_Extend::loadCache('nav');
		Core_Extend::loginInformation();
		
		// CSS资源定义
		if(isset($GLOBALS['_commonConfig_']['_CURSCRIPT_'])){
			Core_Extend::defineCurscript($GLOBALS['_commonConfig_']['_CURSCRIPT_']);
		}
		
		// 读取当前的主题样式
		$sStyleCachepath=self::getCurstyleCachepath();
		$arrMustFile=array('style.css','common.css','style.php');

		foreach($arrMustFile as $sMustFile){
			if(!is_file($sStyleCachepath.'/'.$sMustFile)){
				if(!Dyhb::classExists('Cache_Extend')){
					require_once(Core_Extend::includeFile('function/Cache_Extend'));
				}
				Cache_Extend::updateCache('style');
			}
		}

		$GLOBALS['_style_']=(array)(include $sStyleCachepath.'/style.php');
		define('DOYOUHAOBABY_TEMPLATE_BASE',$GLOBALS['_style_']['doyouhaobaby_template_base']);

		if(defined('CURSCRIPT')){
			$sCurscript=$sStyleCachepath.'/scriptstyle_'.APP_NAME.'_'.str_replace('::','_',CURSCRIPT).'.css';
		}else{
			$sCurscript='';
		}

		if(defined('CURSCRIPT') && !is_file($sCurscript)){
			$sContent=$GLOBALS['_curscript_']='';
			$sContent=file_get_contents($sStyleCachepath.'/style.css');
			if(is_file($sStyleCachepath.'/'.APP_NAME.'_'.'style.css')){
				$sContent.=file_get_contents($sStyleCachepath.'/'.APP_NAME.'_'.'style.css');
			}
			$sContent=preg_replace("/([\n\r\t]*)\[CURSCRIPT\s*=\s*(.+?)\]([\n\r]*)(.*?)([\n\r]*)\[\/CURSCRIPT\]([\n\r\t]*)/ies","Core_Extend::cssVarTags('\\2','\\4')",$sContent);

			$sCssCurScripts=$GLOBALS['_curscript_'];
			$sCssCurScripts=preg_replace(array('/\s*([,;:\{\}])\s*/','/[\t\n\r]/','/\/\*.+?\*\//'),array('\\1','',''),$sCssCurScripts);
			$sCssCurScripts=trim(stripslashes($sCssCurScripts));
			if($sCssCurScripts==''){
				$sCssCurScripts=' ';
			}

			if(!is_dir(dirname($sCurscript))){
				$nStyleid=intval(Dyhb::cookie('style_id'));
				if($GLOBALS['___login___']!==false && $GLOBALS['___login___']['user_extendstyle']==$nStyleid){
					$oUser=UserModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
					$oUser->user_extendstyle=0;
					$oUser->save(0,'update');

					if($oUser->isError()){
						Dyhb::E($oUser->getErrorMessage());
					}
				}
				
				Dyhb::cookie('style_id',null,-1);
			}

			if(!file_put_contents($sCurscript,$sCssCurScripts)){
				Dyhb::E(Dyhb::L('无法写入缓存文件,请检查缓存目录 %s 的权限是否为0777','__COMMON_LANG__@Function/Cache_Extend',null,$sStyleCachepath));
			}
		}

		// 清除直接使用$_GET['t']带来的影响
		if(isset($_GET['t'])){
			Dyhb::cookie('template',NULL,-1);
		}
		
		// 读取语言缓存
		Core_Extend::loadCache('lang');

		// 判断应用是否启用
		Core_Extend::loadCache('app');
		if(!in_array(APP_NAME,$GLOBALS['_cache_']['app'])){
			Dyhb::E(Dyhb::L('应用 %s 尚未开启或者不存在','__COMMON_LANG__@Function/Core_Extend',null,APP_NAME));
		}

		// 访问推广
		if(!empty($_GET['fromuid'])){
			Core_Extend::promotion();
		}
		
		// 在线统计
		if($GLOBALS['_option_']['online_on']==1){
			Core_Extend::online();
		}
	}

	static public function loadCss(){
		$sStyleCachepath=self::getCurstyleCachepath();
		$sStyleCacheurl=self::getCurstyleCacheurl();
		
		$sScriptCss='';
		$sScriptCss='<link rel="stylesheet" type="text/css" href="'.$sStyleCacheurl.'/common.css?'.$GLOBALS['_style_']['verhash']."\" />";
		
		if(is_file($sStyleCachepath.'/'.APP_NAME.'_common.css')){
			$sScriptCss.='<link rel="stylesheet" type="text/css" href="'.$sStyleCacheurl.'/'.APP_NAME.'_common.css?'.$GLOBALS['_style_']['verhash']."\" />";
		}

		$sCurrentT=Dyhb::cookie('extend_style_id');
		if($sCurrentT==''){
			if($GLOBALS['___login___']!==false){
				$sCurrentT=$GLOBALS['___login___']['user_extendstyle'];
			}else{
				$sCurrentT=$GLOBALS['_style_']['_current_style_'];
			}
		}

		if(!empty($sCurrentT) && is_file($sStyleCachepath.'/t_'.$sCurrentT.'.css')){
			$sScriptCss.='<link rel="stylesheet" id="extend_style" type="text/css" href="'.$sStyleCacheurl.'/t_'.$sCurrentT.'.css?'.$GLOBALS['_style_']['verhash']."\" />";
			$GLOBALS['_extend_style_']=$sCurrentT;

			// 取得扩展背景图片
			if(is_dir(WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Style/'.$sCurrentT.'/bgextend')){
				$arrBgimgPath=array();
				$arrFiles=G::listDir(WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Style/'.$sCurrentT.'/bgextend',false,true);
				if(is_array($arrFiles)){
					foreach($arrFiles as &$sFile){
						$arrBgimgPath[]='"'.__ROOT__.'/ucontent/theme/Default/Public/Style/'.$sCurrentT.'/bgextend/'.$sFile.'"';
					}
				}
				$arrBgimgPath[]='"'.__ROOT__.'/ucontent/theme/Default/Public/Style/'.$sCurrentT.'/bgimg.jpg'.'"';

				$sScriptCss.="<script type=\"text/javascript\">";
				$sScriptCss.=
				"var globalImgbgs=[".implode(',',$arrBgimgPath)."];";
				$sScriptCss.="</script>";
			}
		}else{
			$GLOBALS['_extend_style_']='0';
			$sScriptCss.='<link rel="stylesheet" id="extend_style" type="text/css" href="'.__PUBLIC__.'/images/common/none.css?'.$GLOBALS['_style_']['verhash']."\" />";

			// 取得背景图片
			if(is_dir(WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Images/bgextend')){
				$arrBgimgPath='';
				$arrFiles=G::listDir(WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Images/bgextend',false,true);
				if(is_array($arrFiles)){
					foreach($arrFiles as &$sFile){
						$arrBgimgPath[]='"'.__ROOT__.'/ucontent/theme/Default/Public/Images/bgextend/'.$sFile.'"';
					}
				}

				$sScriptCss.="<script type=\"text/javascript\">";
				$sScriptCss.=
				"var globalImgbgs=[".implode(',',$arrBgimgPath)."];";
				$sScriptCss.="</script>";
			}
		}
		
		if(!defined('CURSCRIPT')){
			return $sScriptCss;
		}
		
		$sScriptCss.='<link rel="stylesheet" type="text/css" href="'.$sStyleCacheurl.'/scriptstyle_'.APP_NAME.'_'.str_replace('::','_',CURSCRIPT).'.css?'.$GLOBALS['_style_']['verhash']."\" />";
		
		return $sScriptCss;
	}
	
	static public function cssVarTags($sCurScript,$sContent){
		$arrCurScript=Dyhb::normalize(explode(',',trim($sCurScript)));
		
		// 应用::模块::方法
		$bGetcontent=in_array(APP_NAME.'::'.CURSCRIPT,$arrCurScript);

		// 应用::模块
		if($bGetcontent===false){
			if(strpos(CURSCRIPT,'::')){
				$arrTemp=explode('::',CURSCRIPT);
				$bGetcontent=in_array(APP_NAME.'::'.$arrTemp[0],$arrCurScript);
			}
		}

		// 公用
		if($bGetcontent===false && defined('CURSCRIPT_COMMON')){
			$arrCommonscript=explode(',',CURSCRIPT_COMMON);
			if(is_array($arrCommonscript)){
				foreach($arrCommonscript as $sValue){
					if(in_array('@'.$sValue,$arrCurScript)){
						$bGetcontent=true;
						break;
					}
				}
			}
		}

		$GLOBALS['_curscript_'].=$bGetcontent?$sContent:'';
	}

	static public function getStyleId($nId=0){
		if($nId==0){
			if(Dyhb::cookie('style_id')){
				$nId=Dyhb::cookie('style_id');
			}else{
				$nId=intval($GLOBALS['_option_']['front_style_id']);
			}
		}

		return $nId;
	}

	static public function getCurstyleCachepath($nId=0){
		$nId=self::getStyleId($nId);
		return WINDSFORCE_PATH.'/data/~runtime/style_/'.$nId;
	}

	static public function getCurstyleCacheurl($nId=0){
		$nId=self::getStyleId($nId);
		return __ROOT__."/data/~runtime/style_/".$nId;
	}

	static public function defineCurscript($arrModulecachelist){
		$arrResult=array();

		foreach($arrModulecachelist as $nKey=>$sCache){
			if(!is_int($nKey)){
				$temp=$sCache;
				$sCache=$nKey;
				$nKey=$temp.'*'.G::randString(6);
			}
			
			// 定义
			if(strpos($sCache,',')){
				foreach(explode(',',$sCache) as $nCacheKey=>$sValue){
					$arrResult[$nKey.G::randString(6)]=$sValue;
				}
			}else{
				$arrResult[$nKey]=$sCache;
			}
		}
		
		$arrResult=array_unique($arrResult);
	
		// 优先 @ucenter::index
		foreach(array(MODULE_NAME.'::'.ACTION_NAME,MODULE_NAME) as $sValue){
			$nKey=array_search($sValue,$arrResult);
			if($nKey!==false){
				if(strpos($nKey,'*')){
					$arrTemp=explode('*',$nKey);
					define('CURSCRIPT_COMMON',$arrTemp[0]);
				}

				define('CURSCRIPT',$sValue);
				break;
			}
		};
	}

	static public function thumb($sFilepath,$nWidth,$nHeight){
		if(!is_file($sFilepath)){
			$sFilepath=WINDSFORCE_PATH.'/Public/images/common/none.gif';
		}
		
		Image::thumbGd($sFilepath,$nWidth,$nHeight);
	}

	static public function ubb($sContent,$bHomefreshmessage=true,$bUsersign=false,$nOuter=0){
		if(!Dyhb::classExists('Ubb2html')){
			require_once(Core_Extend::includeFile('class/Ubb2html'));
		}

		$oUbb2html=Dyhb::instance('Ubb2html',array($sContent,$bHomefreshmessage,$nOuter));
		
		if($bUsersign===true){
			$sContent=$oUbb2html->convertUsersign();
		}else{
			$sContent=$oUbb2html->convert();
		}

		return $sContent;
	}

	static public function emotion(){
		$sLangname=LANG_NAME?LANG_NAME:'Zh-cn';

		$sPublic=__PUBLIC__;
		$sEmotionLang=is_file(WINDSFORCE_PATH.'/Public/js/emotions/language/'.$sLangname.'.js')?$sLangname:'Zh-cn';

		return <<<WINDSFORCE
		<link href="{$sPublic}/js/emotions/emoticon.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="{$sPublic}/js/emotions/language/{$sEmotionLang}.js"></script>
		<script type="text/javascript" src="{$sPublic}/js/emotions/jquery.emoticons.js"></script>
WINDSFORCE;
	}

	static public function removeDir($sDirName){
		if(!is_dir($sDirName)){
			@unlink($sDirName);
			return false;
		}

		$hHandle=@opendir($sDirName);
		while(($file=@readdir($hHandle))!==false){
			if($file!='.' && $file!='..'){
				$sDir=$sDirName.'/'.$file;
				if(is_dir($sDir)){
					self::removeDir($sDir);
				}else{
					@unlink($sDir);
				}
			}
		}

		closedir($hHandle);
		$bResult=rmdir($sDirName);

		return $bResult;
	}

	public static function isEmptydir($sDir){
		$hDir=@opendir($sDir);
		
		$nI=0;
		while($file=readdir($hDir)){
			$nI++;
		}
		closedir($hDir);

		if($nI>2){
			return false;
		}else{
			return true;
		}
	}

	public static function addFeed($sTemplate,$arrData,$nUserid=0,$sUsername=''){
		if(empty($nUserid)){
			$nUserid=$GLOBALS['___login___']['user_id'];
		}
		$nUserid=intval($nUserid);

		if(empty($sUsername)){
			$sUsername=$GLOBALS['___login___']['user_name'];
		}
		
		$oFeed=Dyhb::instance('FeedModel');
		$oFeed->addFeed($sTemplate,$arrData,$nUserid,$sUsername);

		if($oFeed->isError()){
			Dyhb::E($oFeed->getErrorMessage());
		}
	}

	public static function addNotice($sTemplate,$arrData,$nTouserid,$sType='system',$nFromid=0,$nUserid=0,$sUsername=''){
		if(empty($nUserid)){
			$nUserid=$GLOBALS['___login___']['user_id'];
		}
		$nUserid=intval($nUserid);
		
		if(empty($sUsername)){
			$sUsername=$GLOBALS['___login___']['user_name'];
		}

		$oNotice=Dyhb::instance('NoticeModel');
		$oNotice->addNotice($sTemplate,$arrData,$nTouserid,$sType,$nFromid,$nUserid,$sUsername);

		if($oNotice->isError()){
			Dyhb::E($oNotice->getErrorMessage());
		}
	}

	static public function checkSpam($arrData=array(),$bLogincheck=true){
		// 是否登录检查
		if($bLogincheck===TRUE && $GLOBALS['___login___']===FALSE){
			Dyhb::E(Dyhb::L('你没有登录，无法发布信息','__COMMON_LANG__@Function/Core_Extend').'<br/><a href="'.Dyhb::U('home://public/login').'">'.Dyhb::L('前往登录','__COMMON_LANG__@Function/Core_Extend').'</a>');
		}
		
		// 两次发表时间间隔
		$nFloodctrl=intval($GLOBALS['_option_']['flood_ctrl']);
		if($nFloodctrl>0 && isset($arrData['lasttime'])){
			$nLasttime=intval($arrData['lasttime']);

			if($nLasttime>0 && CURRENT_TIMESTAMP-$nLasttime<=$nFloodctrl){
				Dyhb::E(Dyhb::L('为防止灌水,发布信息时间间隔为 %d 秒','__COMMON_LANG__@Function/Core_Extend',null,$nFloodctrl));
			}
		}

		// 强制用户激活邮箱
		if($GLOBALS['_option_']['need_email']==1 && $GLOBALS['___login___']['user_isverify']==0){
			Dyhb::E(Dyhb::L('你只有验证邮箱 %s 后才能够发布信息','__COMMON_LANG__@Function/Core_Extend',null,$GLOBALS['___login___']['user_email']).'<br/><a href="'.Dyhb::U('home://spaceadmin/verifyemail').'">'.Dyhb::L('前往验证邮箱','__COMMON_LANG__@Function/Core_Extend').'</a>');
		}

		// 强制用户上传头像
		if($GLOBALS['_option_']['need_avatar']){
			$arrAvatarInfo=Core_Extend::avatars($GLOBALS['___login___']['user_id']);
			if(!$arrAvatarInfo['exist']){
				Dyhb::E(Dyhb::L('你只有上传头像后才能够发布信息','__COMMON_LANG__@Function/Core_Extend').'<br/><a href="'.Dyhb::U('home://spaceadmin/avatar').'">'.Dyhb::L('前往上传头像','__COMMON_LANG__@Function/Core_Extend').'</a>');
			}
		}

		// 强制用户好友个数
		$nNeedfriendnum=intval($GLOBALS['_option_']['need_friendnum']);
		if($nNeedfriendnum>0){
			$oUsercount=UsercountModel::F('user_id=?',$GLOBALS['___login___']['user_id'])->getOne();
			if(!empty($oUsercount['user_id'])){
				$nHavefriendnum=intval($oUsercount['usercount_friends']);
				if($nHavefriendnum<$nNeedfriendnum){
					Dyhb::E(Dyhb::L('你只有至少添加 %d 个好友后才能够发布信息','__COMMON_LANG__@Function/Core_Extend',null,$nNeedfriendnum).'<br/><a href="'.Dyhb::U('home://friend/search').'">'.Dyhb::L('前往添加好友','__COMMON_LANG__@Function/Core_Extend').'</a>');
				}
			}else{
				Dyhb::E(Dyhb::L('用户统计数据不存在，请联系管理员修复','__COMMON_LANG__@Function/Core_Extend').'<br/>'.Dyhb::L('管理员邮箱地址','__COMMON_LANG__@Function/Core_Extend').' '.$GLOBALS['_option_']['admin_email']);
			}
		}
	}

	static public function contentParsetag($sContent,$bUser=true,$bTag=true,$nTagmaxnum=5){
		// 初始化一些变量
		$arrReturn=array();
		$arrTags=$arrAtuserids=$arrContentsearch=$arrContentreplace=array();

		if($nTagmaxnum<1){
			$nTagmaxnum=1;
		}

		// @user_name 功能解析
		if(false!==strpos($sContent,'@')){
			if (preg_match_all('~\@([\w\d\_\-\x7f-\xff]+)(?:[\r\n\t\s ]+|[\xa1\xa1]+|[\xa3\xac]|[\xef\xbc\x8c]|[\,\.\;\[\#])~',$sContent,$arrMatch)){
				if(isset($arrMatch[1]) && is_array($arrMatch[1]) && count($arrMatch[1])){
					foreach($arrMatch[1] as $nKey=>$sValue){
						$sValue=trim($sValue);
						if('　'==substr($sValue,-2)){
							$sValue=substr($sValue,0,-2);
						}

						if($sValue && strlen($sValue)<50){
							$arrMatch[1][$nKey]=$sValue;
						}
					}

					$arrUsers=UserModel::F()->setColumns('user_id,user_name')->where(array('user_name'=>array('in',$arrMatch[1]),'user_status'=>1))->getAll();

					if(is_array($arrUsers)){
						foreach($arrUsers as $oUser){
							$sAtuser="@{$oUser['user_name']}";
							$arrContentsearch[$sAtuser]=$sAtuser;
							$arrContentreplace[$sAtuser]="[MESSAGE]@{$oUser['user_name']}[/MESSAGE] ";
							$arrAtuserids[$oUser['user_id']]=$oUser['user_id'];
						}
					}
				}
			}
		}

		// #你的话题# 功能解析
		if($bTag===true && false!==strpos($sContent,'#')){
			$arrMatch=array();
			if(preg_match_all('~\#([^\/\-\@\#\[\$\{\}\(\)\;\<\>\\\\]+?)\#~',$sContent,$arrMatch)){
				$nI=0;
				foreach($arrMatch[1] as $sValue){
					$sValue=trim($sValue);
					if (($nValuelen=strlen($sValue))<2 || $nValuelen>50){
						continue;
					}

					$arrTags[$sValue]=$sValue;
					$sTag="#{$sValue}#";
					$arrContentsearch[$sTag]=$sTag;
					$arrContentreplace[$sTag]="[TAG]#{$sValue}#[/TAG]";

					if(++$nI>=$nTagmaxnum){
						break;
					}
				}
			}
		}

		// 内容替换
		if($arrContentsearch && $arrContentreplace){
			uasort($arrContentsearch,create_function('$sA,$sB','return(strlen($sA)<strlen($sB));'));

			foreach($arrContentsearch as $sKey=>$sValue){
				if($sValue && isset($arrContentreplace[$sKey])){
					$sContent=str_replace($sValue,$arrContentreplace[$sKey],$sContent);
				}
			}
		}

		$sContent=trim($sContent);
		$arrReturn['content']=$sContent;
		$arrReturn['atuserids']=$arrAtuserids;
		$arrReturn['tags']=$arrTags;

		return $arrReturn;
	}

	static public function getLogo(){
		$sLogo=$GLOBALS['_style_']['logo'];

		if(empty($sLogo)){
			$sLogo=$GLOBALS['_option_']['site_logo'];
		}

		if(empty($sLogo)){
			$sLogo=__PUBLIC__.'/images/common/logo.png';
		}

		return $sLogo;
	}

	static public function getFavicon(){
		$sFavicon=__ROOT__.'/ucontent/theme/'.Dyhb::cookie('template').'/favicon.png';

		if(!is_file(WINDSFORCE_PATH.'/ucontent/theme/'.Dyhb::cookie('template').'/favicon.png')){
			$sFavicon=__ROOT__.'/ucontent/theme/Zh-cn/favicon.png';
		}

		if(!is_file(WINDSFORCE_PATH.'/ucontent/theme/Zh-cn/favicon.png')){
			$sFavicon=__PUBLIC__.'/images/common/favicon.png';
		}

		return $sFavicon;
	}

	static public function windsforceReferer(){
		if($GLOBALS['___login___']===false){
			Dyhb::cookie('windsforce_referer',__SELF__);
		}
	}

	static public function newData($nCreatedateline,$bReturnImg=false,$nDate=86400){
		$bIsNew=false;

		if(CURRENT_TIMESTAMP-$nCreatedateline<=$nDate){
			$bIsNew=true;
		}

		if($bReturnImg===true){
			if($bIsNew===true){
				return '<img class="new_data" src="'.__ROOT__.'/Public/images/common/new.gif" border="0" align="absmiddle" />';
			}else{
				return '';
			}
		}else{
			return $bIsNew;
		}
	}

	static public function usersign($sUsersign){
		return Core_Extend::ubb(nl2br(htmlspecialchars($sUsersign)),true,true);
	}

	static public function getUsericon($nUserid,$bReturnImage=true,$bReturnImageHtml=true){
		$sReturn=$sTitle='';

		if($nUserid>0){
			$arrAdmins=explode(',',$GLOBALS['_commonConfig_']['ADMIN_USERID']);
			
			if(in_array($nUserid,$arrAdmins)){
				$sReturn=$bReturnImage===true?__ROOT__.'/Public/images/common/usericon/online_admin.gif':3;
				$sTitle=Dyhb::L('管理员','__COMMON_LANG__@Function/Core_Extend');
			}else{
				$sReturn=$bReturnImage===true?__ROOT__.'/Public/images/common/usericon/online_member.gif':2;
				$sTitle=Dyhb::L('会员','__COMMON_LANG__@Function/Core_Extend');
			}
		}else{
			$sReturn=$bReturnImage===true?__ROOT__.'/Public/images/common/usericon/online_guest.gif':-1;
			$sTitle=Dyhb::L('游客','__COMMON_LANG__@Function/Core_Extend');
		}

		if($bReturnImage===true && $bReturnImageHtml=true){
			return "<img class=\"usericon_data\" src=\"{$sReturn}\" title=\"{$sTitle}\" border=\"0\" align=\"absmiddle\" />";
		}else{
			return $sReturn;
		}
	}
	
	static public function getUseronlineicon($nUserid,$bReturnImage=true,$bReturnImageHtml=true,$bReally=false){
		$sTitle=Dyhb::L('用户不在线','__COMMON_LANG__@Function/Core_Extend');
		
		$oOnline=OnlineModel::F('user_id=?',$nUserid)->getOne();

		if(!empty($oOnline['user_id'])){
			if($oOnline['online_isstealth']==1 && $bReally===false){
				$bOnline=false;
			}else{
				$bOnline=true;
				$sTitle=Dyhb::L('用户在线','__COMMON_LANG__@Function/Core_Extend');

				if($GLOBALS['_option_']['online_commonshowip']==1){
					if(!Dyhb::classExists('Misc_Extend')){
						require_once(Core_Extend::includeFile('function/Misc_Extend'));
					}

					$sTitle.=' | '.$oOnline['online_ip'].' '.Misc_Extend::convertIp($oOnline['online_ip']);
				}
			}
		}else{
			$bOnline=false;
		}

		$sReturn=$bReturnImage===true?__ROOT__.'/Public/images/common/onlineicon/'.($bOnline===true?'ol.gif':'not_ol.gif'):$bOnline;

		if($bReturnImage===true && $bReturnImageHtml===true){
			return "<img class=\"onlineicon_data\" src=\"{$sReturn}\" title=\"{$sTitle}\" border=\"0\" align=\"absmiddle\" />";
		}else{
			return $sReturn;
		}
	}

	static public function api($arrDatas,$sType='',$bIsArray=false){
		// 数据库读取的二维数组
		if(is_array($arrDatas) && $bIsArray===false){
			$arrTemp=array();
			if(is_array($arrDatas)){
				foreach($arrDatas as $oData){
					$arrTemp[]=$oData->toArray();
				}
			}

			$arrDatas=$arrTemp;
		}elseif(is_object($arrDatas)){// 数据库读取的一维数组
			$arrDatas=$arrDatas->toArray();
		}

		if($sType=='json'){
			header("Content-Type:text/html; charset=utf-8");
			exit(json_encode($arrGrouptopics));
		}elseif($sType=='xml'){
			header("Content-Type:text/xml; charset=utf-8");
			exit(G::xmlEncode($arrResult));
		}

		return;
	}

}
