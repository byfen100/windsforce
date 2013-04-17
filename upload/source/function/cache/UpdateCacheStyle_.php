<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主题缓存($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class UpdateCacheStyle{

	public static function cache(){
		$arrData=array();

		$arrStylevars=$arrStyledata=array();
		$nDefaultStyleid=$GLOBALS['_option_']['front_style_id'];
		$arrStylevarOjbects=StylevarModel::F()->getAll();
		if(is_array($arrStylevarOjbects)){
			foreach(StylevarModel::F()->getAll() as $oStylevar){
				$arrStylevars[$oStylevar['style_id']][$oStylevar['stylevar_variable']]=$oStylevar['stylevar_substitute'];
			}
		}

		$arrCacheStyledir=$arrTheStyles=array();
		$arrCacheStyledir=G::listDir(WINDSFORCE_PATH.'/data/~runtime/style_');

		$arrStyleObjects=StyleModel::F('style_status=?',1)->asArray()->getAll();
		if(is_array($arrStyleObjects)){
			foreach($arrStyleObjects as $arrStyle){
				$arrTheStyles[]=$arrStyle['style_id'];
				
				$oTheme=ThemeModel::F('theme_id=?',$arrStyle['theme_id'])->getOne();
				$arrStyle['doyouhaobaby_template_base']=$oTheme['theme_dirname'];
				$arrStyle=array_merge($arrStyle,$arrStylevars[$arrStyle['style_id']]);
				
				$arrDataNew=array();
				$arrStyle['img_dir']=$arrStyle['img_dir']?$arrStyle['img_dir']:'theme/Default/Public/Images';
				$arrStyle['style_img_dir']=$arrStyle['style_img_dir']?$arrStyle['style_img_dir']:$arrStyle['img_dir'];
				$arrStyle['img_dir']=__ROOT__.'/ucontent/'.$arrStyle['img_dir'];
				$arrStyle['style_img_dir']=__ROOT__.'/ucontent/'.$arrStyle['style_img_dir'];

				foreach($arrStyle as $sKey=>$sStyle){
					if($sKey!='menu_hover_bg_color' && substr($sKey,-8,8)=='bg_color'){
						$sNewKey=substr($sKey,0,-8).'bg_code';
						$arrDataNew[$sNewKey]=self::setCssBackground($arrStyle,substr($sKey,0,-6));
					}
				}

				$arrStyle=array_merge($arrStyle,$arrDataNew);

				$arrStyleIcons[$arrStyle['style_id']]=$arrStyle['menu_hover_bg_color'];
				
				if(strstr($arrStyle['logo'],',')){
					$arrFlash=explode(",",$arrStyle['logo']);
					$arrFlash[0]=trim($arrFlash[0]);
					$arrFlash[0]=preg_match('/^http:\/\//i',$arrFlash[0])?$arrFlash[0]:$arrStyle['style_img_dir'].'/'.$arrFlash[0];
					$arrStyle['logo_str']="<embed src=\"".$arrFlash[0]."\" width=\"".trim($arrFlash[1])."\" height=\"".trim($arrFlash[2])."\" type=\"application/x-shockwave-flash\" wmode=\"transparent\"></embed>";
				}else{
					$arrStyle['logo']=preg_match('/^http:\/\//i',$arrStyle['logo'])?$arrStyle['logo']:$arrStyle['style_img_dir'].'/'.$arrStyle['logo'];
					$arrStyle['logo_str']="<img src=\"".$arrStyle['logo']."\" alt=\"".$GLOBALS['_option_']['site_name']."\" border=\"0\" />";
				}

				$sStyleExtendDir=WINDSFORCE_PATH.'/ucontent/theme/'.$arrStyle['doyouhaobaby_template_base'].'/Public/Style';
				!is_dir($sStyleExtendDir) && $sStyleExtendDir=WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Style';
				if(is_dir($sStyleExtendDir)){
					$arrStyleDirs=G::listDir($sStyleExtendDir);

					$arrExtendstyleData[]=array('',Dyhb::L('默认','__COMMON_LANG__@Function/Cache_Extend'),$arrStyle['menu_hover_bg_color']);
					
					foreach($arrStyleDirs as $sStyleDir){
						$sExtendStylefile=$sStyleExtendDir.'/'.$sStyleDir.'/style.css';

						if(is_file($sExtendStylefile)){
							$sContent=file_get_contents($sExtendStylefile);

							if(preg_match('/\[name\](.+?)\[\/name\]/i',$sContent,$arrResult1) &&
								preg_match('/\[iconbgcolor](.+?)\[\/iconbgcolor]/i',$sContent,$arrResult2))
							{
								$arrExtendstyleData[$sStyleDir]=array($sStyleDir,$arrResult1[1],$arrResult2[1]);
							}
						}
					}
				}

				$arrStyleExtendValue=explode('|',$arrStyle['style_extend']);
				$arrStyle['_current_style_']=$arrStyleExtendValue[1];

				$arrStyle['_style_extend_icons_']=array();
				$arrStyleExtendValue=explode("\t",$arrStyleExtendValue[0]);
				$arrStyleExtendValue[]=0;
				foreach($arrStyleExtendValue as $sStyleExtendValue){
					if(array_key_exists($sStyleExtendValue,$arrExtendstyleData)){
						$arrStyle['_style_extend_icons_'][$sStyleExtendValue]=$arrExtendstyleData[$sStyleExtendValue];
					}
				}

				$nContentWidthInt=intval($arrStyle['content_width']);
				$nContentWidthInt=$nContentWidthInt?$nContentWidthInt:600;
				$nImageMaxWidth=$GLOBALS['_option_']['image_max_width'];
				if(substr(trim($nContentWidthInt),-1,1)!='%'){
					if(substr(trim($nImageMaxWidth),-1,1)!='%'){
						$arrStyle['image_max_width']=$nImageMaxWidth>$nContentWidthInt?$nContentWidthInt:$nImageMaxWidth;
					}else{
						$arrStyle['image_max_width']=intval($nContentWidthInt*$nImageMaxWidth/100);
					}
				}else{
					if(substr(trim($nImageMaxWidth),-1,1)!='%'){
						$arrStyle['image_max_width']='%'.$nImageMaxWidth;
					}else{
						$arrStyle['image_max_width']=($nImageMaxWidth>$nContentWidthInt?$nContentWidthInt:$nImageMaxWidth).'%';
					}
				}

				$arrStyle['verhash']=G::randString(6,null,true);
				$arrStyle['__root__']=__ROOT__;

				$arrStyles[intval($arrStyle['style_id'])]=$arrStyle;
			}
		}

		if(is_array($arrCacheStyledir)){
			foreach($arrCacheStyledir as $nKey=>$sValue){
				if(!in_array($sValue,$arrTheStyles)){
					$sCurDeletedstyleDir=WINDSFORCE_PATH.'/data/~runtime/style_/'.$sValue;
					$arrCurDeletedstyleFiles=G::listDir($sCurDeletedstyleDir,true,true);
					foreach($arrCurDeletedstyleFiles as $sCurDeletedstyleFile){
						@unlink($sCurDeletedstyleFile);
					}
					@rmdir($sCurDeletedstyleDir);
				}
			}
		}

		if(is_array($arrStyles)){
			foreach($arrStyles as $arrStyle){
				$arrStyle['_style_icons_']=$arrStyleIcons;

				$sStyleIdPath=WINDSFORCE_PATH.'/data/~runtime/style_/'.intval($arrStyle['style_id']);
				if(!is_dir($sStyleIdPath)&& !G::makeDir($sStyleIdPath)){
					Dyhb::E(Dyhb::L('无法写入缓存文件,请检查缓存目录 %s 的权限是否为0777','__COMMON_LANG__@Function/Cache_Extend',null,$sStyleIdPath));
				}
				
				self::writeToCache($sStyleIdPath.'/style.php',$arrStyle);
				self::writetoCssCache($arrStyle,$sStyleIdPath);
			}
		}
	}

	private static function writeToCache($sStylePath,$arrStyle){
		if(!file_put_contents($sStylePath,
			"<?php\n /* WindsForce Style File,Do not to modify this file! */ \n return ".
				var_export($arrStyle,true).
			"\n?>")
		){
			Dyhb::E(Dyhb::L('无法写入缓存文件,请检查缓存目录 %s 的权限是否为0777','__COMMON_LANG__@Function/Cache_Extend',null,$sStylePath));
		}
	}

	private static function writetoCssCache($arrData=array(),$sStyleIdPath){
		$arrTypes=array();
		$arrTypes[]='@';

		$arrApps=AppModel::F('app_status=?',1)->getAll();
		if(is_array($arrApps)){
			foreach($arrApps as $oApp){
				$arrTypes[]=$oApp['app_identifier'];
			}
		}

		$arrCssfiles=array(
			'style'=>array('style','style_append'),
			'common'=>array('common','common_append'),
			'windsforce'=>array('windsforce','windsforce_append'),
		);

		$arrStyleExtendValue=explode('|',$arrData['style_extend']);
		$arrStyleExtendValue=explode("\t",$arrStyleExtendValue[0]);
		foreach($arrStyleExtendValue as $nStyleExtendValue=>$sStyleExtendValue){
			$arrCssfiles['t_'.$sStyleExtendValue]=array($sStyleExtendValue);
		}

		foreach($arrTypes as $sType){
			foreach($arrCssfiles as $sExtra=>$arrCssData){
				$sCssData='';

				foreach($arrCssData as $sCss){
					if($sType=='@'){
						$sCssfile=WINDSFORCE_PATH.'/ucontent/theme/'.ucfirst($arrData['doyouhaobaby_template_base']).'/Public/Css/'.$sCss.'.css';
						!is_file($sCssfile) && $sCssfile=WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Css/'.$sCss.'.css';
					}elseif(strpos($sExtra,'t_')===0){
						$sCssfile=WINDSFORCE_PATH.'/ucontent/theme/'.ucfirst($arrData['doyouhaobaby_template_base']).'/Public/Style/'.$sCss.'/style.css';
						!is_file($sCssfile) && $sCssfile=WINDSFORCE_PATH.'/ucontent/theme/Default/Public/Style/'.$sCss.'/style.css';
					}else{
						$sCssfile=WINDSFORCE_PATH.'/app/'.$sType.'/Theme/'.ucfirst($arrData['doyouhaobaby_template_base']).'/Public/Css/'.$sCss.'.css';
						!is_file($sCssfile) && $sCssfile=WINDSFORCE_PATH.'/app/'.$sType.'/Theme/Default/Public/Css/'.$sCss.'.css';
					}

					if(is_file($sCssfile)){
						$sCssData.=file_get_contents($sCssfile);
					}
				}

				if(empty($sCssData)){
					continue;
				}

				$sCssData=@preg_replace("/\{([A-Z0-9_]+)\}/e",'\$arrData[strtolower(\'\1\')]',stripslashes($sCssData));
				$sCssData=preg_replace("/<\?.+?\?>\s*/",'',$sCssData);
				$sCssData=preg_replace(array('/\s*([,;:\{\}])\s*/','/[\t\n\r]/','/\/\*.+?\*\//'),array('\\1','',''),$sCssData);

				if(!file_put_contents($sStyleIdPath.'/'.($sType!='@' && strpos($sExtra,'t_')!==0?$sType.'_':'').$sExtra.'.css',stripslashes($sCssData)) && !G::makeDir($sStyleIdPath)){
					Dyhb::E(Dyhb::L('无法写入缓存文件,请检查缓存目录 %s 的权限是否为0777','__COMMON_LANG__@Function/Cache_Extend',null,$sStyleIdPath));
				}else{
					$arrCurscriptCss=Glob($sStyleIdPath.'/scriptstyle_*.css');
					foreach($arrCurscriptCss as $sCurscriptCss){
						@unlink($sCurscriptCss);
					}
				}
			}
		}
	}

	private static function setCssBackground(&$arrStyle,$sKey){
		$sCss=$sCodeValue='';

		if(!empty($arrStyle[$sKey.'_color'])){
			$sCss.=strtolower($arrStyle[$sKey.'_color']);
			$sCodeValue=strtoupper($arrStyle[$sKey.'_color']);
		}

		if(!empty($arrStyle[$sKey.'_img'])){
			if(preg_match('/^http:\/\//i',$arrStyle[$sKey.'_img'])){
				$sCss.=' url("'.$arrStyle[$sKey.'_img'].'") ';
			}else{
				$sCss.=' url("'.$arrStyle['style_img_dir'].'/'.$arrStyle[$sKey.'_img'].'") ';
			}
		}

		if(!empty($arrStyle[$sKey.'_extra'])){
			$sCss.=' '.$arrStyle[$sKey.'_extra'];
		}

		$arrStyle[$sKey.'_color']=$sCodeValue;

		$sCss=trim($sCss);

		return $sCss?'background: '.$sCss:'';
	}

}
