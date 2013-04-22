<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   主题管理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class StyleController extends InitController{

	public $_sCurrentStyle='';
	public $_arrBrokenStyles=array();
	public $_arrOkStyles=array();
	public $_nOkStyleNums=0;
	public $_nBrokenStyleNums=0;

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}
	
	public function filter_(&$arrMap){
		$arrMap['style_name']=array('like',"%".G::getGpc('style_name')."%");
	}

	public function bIndex_(){
		$arrThemes=G::listDir(WINDSFORCE_PATH.'/ucontent/theme');
				
		$nAlreadyInstalledNums=0;
		if(!empty($arrThemes)){
			foreach($arrThemes as $sStyle){
				if($this->theme_already_installed(strtolower($sStyle))){
					$nAlreadyInstalledNums++;
				}
			}
		}

		$nNewNum=count($arrThemes)-$nAlreadyInstalledNums;

		$oCurstyle=StyleModel::F('style_status=1 AND style_id=?',$GLOBALS['_option_']['front_style_id'])->getOne();
		
		$oCurstylevarMenuhoverbgcolor=StylevarModel::F('style_id=? AND stylevar_variable=?',$oCurstyle['style_id'],'menu_hover_bg_color')->getOne();
		
		$this->assign('nNewinstalledNum',$nNewNum);
		$this->assign('nCurrentStyleid',$GLOBALS['_option_']['front_style_id']);
		$this->assign('oCurstyle',$oCurstyle);
		$this->assign('oCurstylevarMenuhoverbgcolor',$oCurstylevarMenuhoverbgcolor);
	}

	public function repaire(){
		$nId=intval(G::getGpc('id','G'));

		if(!empty($nId)){
			$oStyle=StyleModel::F('style_id=?',$nId)->getOne();
			if(!empty($oStyle)){
				$oStyle->style_status=1;
				$oStyle->save(0,'update');

				if($oStyle->isError()){
					$this->E($oStyle->getErrorMessage());
				}

				$this->update_css(false);

				$this->S(Dyhb::L('当前主题被禁用,现在成功已经恢复','Controller/Style'));
			}else{
				OptionModel::uploadOption('front_style_id',1);

				Core_Extend::changeAppconfig('FRONT_TPL_DIR','Default');

				$this->update_css(false);

				$this->S(Dyhb::L('当前主题不存在,现在成功已经恢复到默认主题','Controller/Style'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function set_style(){
		$nId=intval(G::getGpc('id','G'));

		if(!empty($nId)){
			$oStyle=StyleModel::F('style_id=?',$nId)->getOne();
			if(!empty($oStyle)){
				if(!$oStyle->style_status){
					$this->E(Dyhb::L('主题尚未开启，无法启用','Controller/Style'));
				}

				OptionModel::uploadOption('front_style_id',$nId);

				// 修改系统配置
				$sTheme='Default';
				$oTheme=ThemeModel::F('theme_id=?',$oStyle['theme_id'])->getOne();
				if(!empty($oTheme['theme_id'])){
					$sTheme=ucfirst($oTheme['theme_dirname']);
				}

				Core_Extend::changeAppconfig('FRONT_TPL_DIR',$sTheme);

				$this->update_css(false);

				$this->S(Dyhb::L('启用主题成功','Controller/Style'));
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function set_admin_style(){
		$sStyle=trim(G::getGpc('style','G'));

		if(!empty($sStyle)){
			$sStyle=ucfirst(strtolower($sStyle));

			OptionModel::uploadOption('admin_theme_name',$sStyle);

			Core_Extend::changeAppconfig('ADMIN_TPL_DIR',$sStyle);
			
			Dyhb::cookie('admin_template',null,-1);

			$this->S(Dyhb::L('启用主题成功','Controller/Style'));
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function repaire_admin(){
		OptionModel::uploadOption('admin_theme_name','Default');

		Core_Extend::changeAppconfig('ADMIN_TPL_DIR','Default');

		Dyhb::cookie('admin_template',null,-1);

		$this->S(Dyhb::L('修复主题成功','Controller/Style'));
	}

	protected function aForbid(){
		// 禁用一个主题
		if($GLOBALS['_option_']['front_style_id']==G::getGpc('id','G')){
			OptionModel::uploadOption('front_style_id',1);
			Core_Extend::changeAppconfig('FRONT_TPL_DIR','Default');
		}

		$this->update_css(false);
	}
	
	protected function aResume(){
		$this->update_css(false);
	}

	public function install(){
		$oTheme=ThemeModel::F('theme_id=?',$GLOBALS['_option_']['front_style_id'])->getOne();
		$this->_sCurrentStyle=ucfirst($oTheme['theme_dirname']);

		$this->show_Styles(WINDSFORCE_PATH.'/ucontent/theme');
		
		$nAlreadyInstalledNums=0;
		if(!empty($this->_arrOkStyles)){
			foreach($this->_arrOkStyles as $arrOkStyle){
				if($this->theme_already_installed(strtolower($arrOkStyle['Style']))){
					$nAlreadyInstalledNums++;
				}
			}
		}

		$this->assign('nAlreadyInstalledNums',$nAlreadyInstalledNums);

		$this->display();
	}
	
	public function admin(){
		$this->_sCurrentStyle=ucfirst(strtolower($GLOBALS['_option_']['admin_theme_name']));
		if(empty($this->_sCurrentStyle)){
			$this->_sCurrentStyle='Default';
		}
		$this->show_Styles(WINDSFORCE_PATH.'/admin/Theme');
		
		if(!is_dir(WINDSFORCE_PATH.'/ucontent/theme/'.$this->_sCurrentStyle)){
			$this->assign('sCurrentStyle',false);
		}else{
			$arrStyles=array();
			$arrStyles[]=WINDSFORCE_PATH.'/ucontent/theme/'.$this->_sCurrentStyle;

			require_once(Core_Extend::includeFile('class/Style'));

			$oStyle=Dyhb::instance('Style');
			$oStyle->getStyles($arrStyles);

			$arrOkStyles=$oStyle->_arrOkStyles;
		
			if(!empty($arrOkStyles)){
				$this->assign('sCurrentStyle',strtolower($this->_sCurrentStyle));
				$this->assign('arrCurrentStyle',reset($arrOkStyles));
			}else{
				$this->assign('sCurrentStyle',false);
			}
		}

		$this->display();
	}

	public function get_xml_num($sStyle,$bReturnNum=true){
		$arrXmlFiles=glob(WINDSFORCE_PATH.'/ucontent/theme/'.ucfirst($sStyle).'/*.xml');

		if(!in_array(WINDSFORCE_PATH.'/ucontent/theme/'.ucfirst($sStyle).'/windsforce_style_'.strtolower($sStyle).'.xml',$arrXmlFiles)){
			return false;
		}

		if($bReturnNum===true){
			return count($arrXmlFiles);
		}else{
			return $arrXmlFiles;
		}
	}

	public function theme_already_installed($sTheme){
		$sTheme=strtolower(trim($sTheme));

		if(empty($sTheme)){
			return false;
		}

		$oTheme=ThemeModel::F('theme_dirname=?',$sTheme)->getOne();
		if(!empty($oTheme['theme_id'])){
			return true;
		}else{
			return false;
		}
	}

	public function install_new(){
		$sStyle=ucfirst(strtolower(trim(G::getGpc('style','G'))));

		if(empty($sStyle)){
			$this->E(Dyhb::L('你没有指定要安装的主题','Controller/Style'));
		}

		if($this->theme_already_installed(strtolower($sStyle))){
			$this->E(Dyhb::L('你安装的主题已经安装过了','Controller/Style'));
		}

		$arrXmlFiles=$this->get_xml_num($sStyle,false);
		if($arrXmlFiles===false){
			$this->E(Dyhb::L('你要安装的主题 %s 的默认样式表不存在','Controller/Style',null,$sStyle));
		}

		foreach($arrXmlFiles as $sXmlFile){
			if(!is_file($sXmlFile)){
				$this->E(Dyhb::L('你要安装的主题 %s 样式表不存在','Controller/Style',null,$sXmlFile));
			}

			$this->install_a_new($sXmlFile);
		}

		$this->S(Dyhb::L('主题 %s 安装成功','Controller/Style',null,$sStyle));
	}

	public function install_a_new($sThemeXml,$arrStyleData=array(),$bIgnoreversion=false){
		if(empty($arrStyleData)){
			if(!is_file($sThemeXml)){
				$this->E(Dyhb::L('你要安装的主题 %s 样式表不存在','Controller/Style',null,$sThemeXml));
			}

			$arrStyleData=Xml::xmlUnserialize(file_get_contents($sThemeXml));
			if(empty($arrStyleData)){
				$this->E(Dyhb::L('你要安装的主题 %s 样式表可能已经损坏，系统无法读取其数据','Controller/Style',null,$sThemeXml));
			}
		}
		
		if(!is_array($arrStyleData)){
			$this->E(Dyhb::L('你要安装的主题 %s 样式表可能已经损坏，数据库不符合我们的要求','Controller/Style',null,$sThemeXml));
		}else{
			// 判断版本
			if(!isset($arrStyleData['root']['version']) || empty($arrStyleData['root']['version'])){
				$this->E(Dyhb::L('当前导入的主题配置文件版本号无法识别','Controller/Style'));
			}else{
				$nResult=strcmp($arrStyleData['root']['version'],WINDSFORCE_SERVER_VERSION);
				if($nResult>0){
					$this->E(Dyhb::L('当前导入的主题配置文件版本号为较新版本，请下载新版本程序','Controller/Style').
							'<br/>'.Dyhb::L('主题配置文件版本','Controller/Style').$arrStyleData['root']['version'].' '.Dyhb::L('主程序版本','Controller/Style').WINDSFORCE_SERVER_VERSION
						);
				}elseif($bIgnoreversion===false && $nResult<0){
					$this->E(Dyhb::L('当前导入的主题配置文件版本号为较旧版本，请导入新版本配置文件，或者选择允许导入旧版本','Controller/Style').
							'<br/>'.Dyhb::L('主题配置文件版本','Controller/Style').$arrStyleData['root']['version'].' '.Dyhb::L('主程序版本','Controller/Style').WINDSFORCE_SERVER_VERSION
					);
				}
			}
			
			$arrStyleData=$arrStyleData['root']['data'];
		}
		
		// 数据变量值验证（依靠默认的系统默认的主题变量来判断）
		if(empty($arrStyleData['data'])){
			$this->E(Dyhb::L('程序无法正常读取到主题配置变量信息','Controller/Style'));
		}
		
		$bNotExistsSomesystemvar=false;
		$arrStylevarKeys=array_keys($arrStyleData['data']);
		
		$arrCurtomStylevarList=(array)(include WINDSFORCE_PATH.'/source/common/Style.php');
		foreach($arrCurtomStylevarList as $sCurtomStylevarList){
			if(!in_array($sCurtomStylevarList,$arrStylevarKeys)){
				if($bIgnoreversion===false){
					$this->E(Dyhb::L('导入的配置文件变量数据不完整','Controller/Style'));
				}else{
					$arrStyleData['data'][$sCurtomStylevarList]='';
				}
			}
		}
		
		// 写入模板数据
		$nThemeId=isset($arrStyleData['theme_id'])?intval($arrStyleData['theme_id']):0;
		$arrSaveThemeData=array(
			'theme_name'=>$arrStyleData['theme_name'],
			'theme_dirname'=>ucfirst($arrStyleData['theme_dirname']),
			'theme_copyright'=>$arrStyleData['copyright'],
		);
		
		$oTheme=Dyhb::instance('ThemeModel');
		$nThemeId=$oTheme->saveThemeData($arrSaveThemeData,$nThemeId);

		if($oTheme->isError()){
			$this->E($oTheme->getErrorMessage());
		}

		// 写入主题数据
		$nStyleId=0;
		$arrSaveStyleData=array(
			'style_name'=>$arrStyleData['name'],
			'style_status'=>isset($arrStyleData['status'])?intval($arrStyleData['status']):0,
			'theme_id'=>$nThemeId,
			'style_extend'=>$arrStyleData['style_extend'],
		);

		$oStyle=Dyhb::instance('StyleModel');
		$nStyleId=$oStyle->saveStyleData($arrSaveStyleData,$nThemeId,$nStyleId);

		if($oStyle->isError()){
			$this->E($oStyle->getErrorMessage());
		}

		// 写入主题变量数据
		$arrSaveStylevariableData=$arrStyleData['data'];

		$oStylevar=Dyhb::instance('StylevarModel');
		$oStylevar->saveStylevarData($arrSaveStylevariableData,$nStyleId);

		if($oStylevar->isError()){
			$this->E($oStylevar->getErrorMessage());
		}

		$this->update_css(false);
	}

	public function bEdit_(){
		$arrThemes=ThemeModel::F()->getAll();
		
		$arrStylevars=StylevarModel::F('style_id=?',intval(G::getGpc('id','G')))->getAll();
		$arrCustomStylevar=$arrSystemStylevar=array();

		$arrCurtomStylevarList=(array)(include WINDSFORCE_PATH.'/source/common/Style.php');
		if(is_array($arrStylevars)){
			foreach($arrStylevars as $oStylevar){
				if(!in_array(strtolower($oStylevar['stylevar_variable']),$arrCurtomStylevarList)){
					$arrCustomStylevar[$oStylevar['stylevar_variable']]=$oStylevar['stylevar_substitute'];
				}else{
					$arrSystemStylevar[$oStylevar['stylevar_variable']]=$oStylevar['stylevar_substitute'];
				}
			}
		}

		// 系统图片目录变量
		$sImgdir=$sStyleimgdir='';
		if(!empty($arrSystemStylevar['img_dir'])){
			$sImgdir=$arrSystemStylevar['img_dir'];
		}else{
			$sImgdir='theme/Default/Public/Images';
		}

		if(!empty($arrSystemStylevar['stylevar_img_dir'])){
			$sStyleimgdir=$arrSystemStylevar['stylevar_img_dir'];
		}else{
			$sStyleimgdir=$sImgdir;
		}

		$this->assign('arrThemes',$arrThemes);
		$this->assign('arrCustomStylevar',$arrCustomStylevar);
		$this->assign('arrSystemStylevar',$arrSystemStylevar);
		$this->assign('sImgdir',$sImgdir);
		$this->assign('sStyleimgdir',$sStyleimgdir);
	}

	public function AEditObject_($oModel){
		if(!empty($oModel->style_id)){
			$this->assign('oValue',$oModel);
			$this->assign('nId',$oModel['style_id']);

			// 读取扩展配色
			$arrExtendstyle=$arrDefaultextendstyle=array();

			$oTheme=ThemeModel::F('theme_id=?',$oModel['theme_id'])->getOne();
			if(!empty($oTheme['theme_id'])){
				$sStyleExtendDir=WINDSFORCE_PATH.'/ucontent/theme/'.ucfirst($oTheme['theme_dirname']).'/Public/Style';

				if(is_dir($sStyleExtendDir)){
					$arrStyleDirs=G::listDir($sStyleExtendDir);

					$arrDefaultextendstyle[]=array('',Dyhb::L('默认','Controller/Style'));
					
					foreach($arrStyleDirs as $sStyleDir){
						$sExtendStylefile=$sStyleExtendDir.'/'.$sStyleDir.'/style.css';

						if(is_file($sExtendStylefile)){
							$sContent=file_get_contents($sExtendStylefile);

							if(preg_match('/\[name\](.+?)\[\/name\]/i',$sContent,$arrResult1) &&
								preg_match('/\[iconbgcolor](.+?)\[\/iconbgcolor]/i',$sContent,$arrResult2))
							{
								$arrExtendstyle[]=array($sStyleDir,'<em style="background:'.$arrResult2[1].'">&nbsp;&nbsp;&nbsp;&nbsp;</em> '.$arrResult1[1]);
								$arrDefaultextendstyle[]=array($sStyleDir,$arrResult1[1]);
							}
						}
					}
				}else{
					$arrExtendstyle=array();
					$arrDefaultextendstyle=array();
				}
			}

			$arrStyleExtendOption=explode('|',$oModel->style_extend);

			if(empty($arrStyleExtendOption[1])){
				$sStyleExtendcolor='';
			}else{
				$sStyleExtendcolor=$arrStyleExtendOption[1];
			}

			$arrStyleExtendcolors=explode("\t",$arrStyleExtendOption[0]);

			$this->assign('arrExtendstyle',$arrExtendstyle);
			$this->assign('arrDefaultextendstyle',$arrDefaultextendstyle);
			$this->assign('sStyleExtendcolor',$sStyleExtendcolor);
			$this->assign('arrStyleExtendcolors',$arrStyleExtendcolors);

			$nAdv=intval(G::getGpc('adv','G'));
			if($nAdv==1){
				$this->display('style+adv_diy');
			}else{
				$this->display('style+diy');
			}
			exit();
		}else{
			$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
		}
	}

	public function get_img($sImg,$sStyleimgdir){
		return $sImg?($this->check_http($sImg)?$sImg:__ROOT__.'/ucontent/'.$sStyleimgdir.'/'.$sImg):__PUBLIC__.'/images/common/none.gif';
	}

	protected function check_http($sUrl){
		$sRe='|^http://|';

		if(preg_match($sRe,$sUrl)){
			return true;
		}else{
			return false;
		}
	}

	public function diy_save(){
		$nStyleId=intval(G::getGpc('style_id','P'));

		if(!empty($nStyleId)){
			$oStyle=StyleModel::F('style_id=?',$nStyleId)->getOne();
			if(!empty($oStyle['style_id'])){
				$oStyle->style_name=trim(G::getGpc('name_new','P'));
				$oStyle->theme_id=intval(G::getGpc('theme_id_new','P'));
				
				$arrStyleextend=G::getGpc('style_extend_new','P');
				if(!is_array($arrStyleextend)){
					$arrStyleextend=array();
				}
				$sDefaultExtendstyle=trim(G::getGpc('default_extend_style_new','P'));
				if(!in_array($sDefaultExtendstyle,$arrStyleextend)){
					$arrStyleextend[]=$sDefaultExtendstyle;
				}

				$sStyleExtend=implode("\t",$arrStyleextend).'|'.$sDefaultExtendstyle;
				$oStyle->style_extend=trim($sStyleExtend);

				$oStyle->save(0,'update');

				if($oStyle->isError()){
					$this->E($oStyle->getErrorMessage());
				}

				$arrStylevars=G::getGpc('stylevar','P');

				// 删除
				$arrKeys=G::getGpc('key','P');

				if(!empty($arrKeys)){
					$arrWhere=array();
					$arrWhere['style_id']=$oStyle['style_id'];
					$arrWhere['stylevar_variable']=array('in',$arrKeys);
					foreach($arrKeys as $sKey){
						if(isset($arrStylevars[$sKey])){
							unset($arrStylevars[$sKey]);
						}
					}

					$oStylevarMeta=StylevarModel::M();
					$oStylevarMeta->deleteWhere($arrWhere);

					if($oStylevarMeta->isError()){
						$this->E($oStylevarMeta->getErrorMessage());
					}
				}

				// 新增
				$sVariableNew=strtolower(trim(G::getGpc('variable_new','P')));
				$sSubstituteNew=strtolower(trim(G::getGpc('substitute_new','P')));
				if($sVariableNew){
					// 判断是否存在
					$arrExistsStylevar=array_keys($arrStylevars);
					if(!in_array($sVariableNew,$arrExistsStylevar)){
						$oNewStylevar=new StylevarModel();
						$oNewStylevar->stylevar_variable=$sVariableNew;
						$oNewStylevar->stylevar_substitute=$sSubstituteNew;
						$oNewStylevar->save(0);

						if($oNewStylevar->isError()){
							$this->E($oNewStylevar->getErrorMessage());
						}
					}
				}

				// 更新当前主题变量
				$oStylevar=Dyhb::instance('StylevarModel');
				$oStylevar->saveStylevarData($arrStylevars,$oStyle['style_id']);

				if($oStylevar->isError()){
					$this->E($oStylevar->getErrorMessage());
				}

				$this->update_css(false);

				$this->S(Dyhb::L('主题 %s 更新成功','Controller/Style',null,$oStyle['style_name']));
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function preview(){
		$nId=intval(G::getGpc('id','G'));

		if(!is_file(WINDSFORCE_PATH.'/data/~runtime/style_/'.$nId.'/common.css')){
			$this->update_css(false);
		}

		$arrStyle=(array)(include WINDSFORCE_PATH.'/data/~runtime/style_/'.$nId.'/style.php');
		$this->assign('sStylepath',__ROOT__.'/data/~runtime/style_/'.$nId.'/common.css?'.$arrStyle['verhash']);

		$this->display();
	}

	public function export(){
		$nStyleId=intval(G::getGpc('id','G'));

		if(!empty($nStyleId)){
			$oStyle=StyleModel::F('style_id=?',$nStyleId)->getOne();
			if(!empty($oStyle['style_id'])){
				$oTheme=ThemeModel::F('theme_id=?',$oStyle['theme_id'])->getOne();
				if(empty($oTheme['theme_id'])){
					$this->E(Dyhb::L('主题 %s 的模板不存在','Controller/Style',null,$oStyle['style_name']));
				}

				$arrData=array();
				
				// 样式版权
				$arrData['title']=$GLOBALS['_option_']['windsforce_program_name'].'! Style';
				$arrData['version']=WINDSFORCE_SERVER_VERSION;
				$arrData['time']=WINDSFORCE_SERVER_RELEASE;
				$arrData['url']=$GLOBALS['_option_']['windsforce_program_url'];
				$arrData['copyright']='(C)'.$GLOBALS['_option_']['windsforce_program_year'].' '.$GLOBALS['_option_']['windsforce_program_company'];

				// 主题信息
				$arrStylevarData=array();

				$arrStylevars=StylevarModel::F('style_id=?',$oStyle['style_id'])->getAll();
				if(is_array($arrStylevars)){
					foreach($arrStylevars as $oStylevar){
						$arrStylevarData[strtolower($oStylevar['stylevar_variable'])]=trim($oStylevar['stylevar_substitute']);
					}
				}

				$arrData['data']=array(
					'name'=>htmlspecialchars($oStyle['style_name']),
					'theme_id'=>intval($oStyle['theme_id']),
					'theme_name'=>htmlspecialchars(trim($oTheme['theme_name'])),
					'theme_dirname'=>strtolower(trim($oTheme['theme_dirname'])),
					'status'=>intval($oStyle['style_status']),
					'style_id'=>intval($oStyle['style_id']),
					'style_extend'=>trim($oStyle['style_extend']),
					'doyouhaobaby_template_base'=>strtolower($oTheme['theme_dirname']),
					'directory'=>'theme/'.ucfirst($oTheme['theme_dirname']),
					'copyright'=>htmlspecialchars(trim($oTheme['theme_copyright'])),
					'data'=>array_reverse($arrStylevarData),
					'version'=>'For '.$GLOBALS['_option_']['windsforce_program_name'].'-'.WINDSFORCE_SERVER_VERSION,
				);
				
				// 保存文件
				$sPath='STYLE-windsforce_style_'.strtolower($oTheme['theme_dirname']).'_'.intval($oStyle['style_id']).'-'.date('Y_m_d_H_i_s',CURRENT_TIMESTAMP).'.xml';
				
				ob_end_clean();
			
				header('Content-Encoding: none');
				header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')?'application/octetstream':'application/octet-stream'));
				header('Content-Disposition: attachment; filename="'.$sPath.'"');
				header('Pragma: no-cache');
				header('Expires: 0');
				
				$arrData=G::stripslashes($arrData,true);
				echo Xml::xmlSerialize($arrData);

				exit;
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function copy_style(){
		$nStyleId=intval(G::getGpc('id','G'));

		if(!empty($nStyleId)){
			$oStyle=StyleModel::F('style_id=?',$nStyleId)->getOne();
			if(!empty($oStyle['style_id'])){
				$oTheme=ThemeModel::F('theme_id=?',$oStyle['theme_id'])->getOne();
				if(empty($oTheme['theme_id'])){
					$this->E(Dyhb::L('主题 %s 的模板不存在','Controller/Style',null,$oStyle['style_name']));
				}

				// 保存主题信息
				$arrStyleData=$oStyle->toArray();
				unset($arrStyleData['style_id']);
				$arrStyleData['style_name']=$arrStyleData['style_name'].'_'.G::randString(6);

				$oNewStyle=new StyleModel($arrStyleData);
				$oNewStyle->save(0);

				if($oNewStyle->isError()){
					$this->E($oNewStyle->getErrorMessage());
				}
			
				// 保存主题变量信息
				$arrStylevarData=array();

				$arrStylevars=StylevarModel::F('style_id=?',intval(G::getGpc('id','G')))->getAll();
				if(is_array($arrStylevars)){
					foreach($arrStylevars as $oStylevar){
						$arrStylevarData[strtolower($oStylevar['stylevar_variable'])]=trim($oStylevar['stylevar_substitute']);
					}
				}

				$oStylevar=Dyhb::instance('StylevarModel');
				$oStylevar->saveStylevarData($arrStylevarData,$oNewStyle['style_id']);

				if($oStylevar->isError()){
					$this->E($oStylevar->getErrorMessage());
				}

				$this->update_css(false);

				$this->S(Dyhb::L('主题 %s 拷贝成功','Controller/Style',null,$oStyle['style_name']));
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id');

		if(!empty($sId)){
			$arrIds=explode(',',$sId);
			if(in_array(1,$arrIds)){
				$this->E(Dyhb::L('系统默认主题无法删除','Controller/Style'));
			}
		}
	}

	public function aForeverdelete($sId){
		// 删除主题后清除它的变量
		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			$oStylevarMeta=StylevarModel::M();
			$oStylevarMeta->deleteWhere(array('style_id'=>$nId));

			if($oStylevarMeta->isError()){
				$this->E($oStylevarMeta->getErrorMessage());
			}

			// 删除一个主题
			if($GLOBALS['_option_']['front_style_id']==$nId){
				OptionModel::uploadOption('front_style_id',1);
				Core_Extend::changeAppconfig('FRONT_TPL_DIR','Default');
			}
		}
	}

	public function bForbid_(){
		$sId=G::getGpc('id');

		if(!empty($sId)){
			$arrIds=explode(',',$sId);
			if(in_array(1,$arrIds)){
				$this->E(Dyhb::L('系统默认主题无法禁用','Controller/Style'));
			}
		}
	}

	public function reset_style(){
		$nStyleId=intval(G::getGpc('id','G'));

		if(!empty($nStyleId)){
			$oStyle=StyleModel::F('style_id=?',$nStyleId)->getOne();
			if(!empty($oStyle['style_id'])){
				$oTheme=ThemeModel::F('theme_id=?',$oStyle['theme_id'])->getOne();
				if(empty($oTheme['theme_id'])){
					$this->E(Dyhb::L('主题 %s 的模板不存在','Controller/Style',null,$oStyle['style_name']));
				}

				$sThemeXml=WINDSFORCE_PATH.'/ucontent/theme/'.ucfirst($oTheme['theme_dirname']).'/windsforce_style_'.strtolower($oTheme['theme_dirname']).'.xml';
				if(!is_file($sThemeXml)){
					$this->E(Dyhb::L('你要安装的主题 %s 样式表不存在','Controller/Style',null,$sThemeXml));
				}

				$arrStyleData=Xml::xmlUnserialize(file_get_contents($sThemeXml));
				if(empty($arrStyleData)){
					$this->E(Dyhb::L('你要安装的主题 %s 样式表可能已经损坏，系统无法读取其数据','Controller/Style',null,$sThemeXml));
				}else{
					$arrStyleData=$arrStyleData['root']['data'];
				}

				// 更新扩展风格
				$oStyle->style_extend=$arrStyleData['style_extend'];
				$oStyle->save(0,'update');

				if($oStyle->isError()){
					$this->E($oStyle->getErrorMessage());
				}

				// 对比数据取得要删除的变量
				$arrResetStylevarData=$arrStyleData['style'];
				$arrResetStylevarkeyData=array_keys($arrResetStylevarData);
				$arrStylevarData=$arrDeletevars=array();
			
				$arrStylevars=StylevarModel::F('style_id=?',$nStyleId)->getAll();
				if(is_array($arrStylevars)){
					foreach($arrStylevars as $oStylevar){
						$arrStylevarData[strtolower($oStylevar['stylevar_variable'])]=trim($oStylevar['stylevar_substitute']);
						if(!in_array(strtolower($oStylevar['stylevar_variable']),$arrResetStylevarkeyData)){
							$arrDeletevars[]=strtolower($oStylevar['stylevar_variable']);
						}
					}
				}

				if(!empty($arrDeletevars)){
					$arrWhere=array();
					$arrWhere['style_id']=$oStyle['style_id'];
					$arrWhere['stylevar_variable']=array('in',$arrDeletevars);

					$oStylevarMeta=StylevarModel::M();
					$oStylevarMeta->deleteWhere($arrWhere);

					if($oStylevarMeta->isError()){
						$this->E($oStylevarMeta->getErrorMessage());
					}
				}

				// 更新变量 & 没有则写入新的变量
				$oStylevar=Dyhb::instance('StylevarModel');
				$oStylevar->saveStylevarData($arrResetStylevarData,$nStyleId);

				if($oStylevar->isError()){
					$this->E($oStylevar->getErrorMessage());
				}

				$this->update_css(false);
				
				$this->S(Dyhb::L('主题 %s 数据恢复成功','Controller/Style',null,$oStyle['style_name']));
			}else{
				$this->E(Dyhb::L('数据库中并不存在该项，或许它已经被删除','Controller/Common'));
			}
		}else{
			$this->E(Dyhb::L('操作项不存在','Controller/Common'));
		}
	}

	public function insert($sModel=null,$nId=null){
		// 创建新的主题
		$arrNewStyle=array(
			'style_name'=>trim(G::getGpc('style_name','G')),
			'style_status'=>0,
			'theme_id'=>1,
			'style_extend'=>'',
		);

		$oStyle=new StyleModel($arrNewStyle);
		$oStyle->save(0);

		if($oStyle->isError()){
			$this->E($oStyle->getErrorMessage());
		}

		// 初始化其主题变量
		$arrStyleData=array();

		$arrCurtomStylevarList=(array)(include WINDSFORCE_PATH.'/source/common/Style.php');
		foreach($arrCurtomStylevarList as $sCustomStylevar){
			$arrStyleData[$sCustomStylevar]='';
		}

		$oStylevar=Dyhb::instance('StylevarModel');
		$oStylevar->saveStylevarData($arrStyleData,$oStyle['style_id']);

		if($oStylevar->isError()){
			$this->E($oStylevar->getErrorMessage());
		}

		$this->update_css(false);

		$this->S(Dyhb::L('新主题 %s 创建成功','Controller/Style',null,$arrNewStyle['style_name']));
	}

	public function update_css($bDisplay=true){
		if(!Dyhb::classExists('Cache_Extend')){
			require_once(Core_Extend::includeFile('function/Cache_Extend'));
		}
		Cache_Extend::updateCache('style');

		if($bDisplay===true){
			$this->assign('__JumpUrl__',Dyhb::U('style/index'));
			$this->S(Dyhb::L('CSS 缓存更新成功','Controller/Style'));
		}
	}

	public function import(){
		$this->assign('nUploadMaxFilesize',ini_get('upload_max_filesize'));
		$this->assign('nUploadSize',Core_Extend::getUploadSize(51200));

		$this->display();
	}

	public function import_data(){
		$sImporttype=trim(G::getGpc('importtype','P'));
		$nIgnoreversion=G::getGpc('ignoreversion','P');

		if($sImporttype=='file'){
			if($_FILES['importfile']['error']==4){
				$this->E(Dyhb::L('你没有上传任何配置文件','Controller/Style'));
			}else{
				$sData=file_get_contents($_FILES['importfile']['tmp_name']);
				@unlink($_FILES['importfile']['tmp_name']);
			}
		}else{
			$sData=trim(G::getGpc('importtxt','P'));
		}
		
		$arrStyleData=Xml::xmlUnserialize($sData);
		if(empty($arrStyleData) || !isset($arrStyleData['root'])){
			$this->E(Dyhb::L('你要导入的主题样式表可能已经损坏，系统无法读取其数据','Controller/Style').
					'<div style="height: 100px; width: 500px; overflow:auto;margin-top:10px;">'.nl2br($sData).'</div>'
				);
		}

		$this->install_a_new('',$arrStyleData,($nIgnoreversion==1?true:false));

		$this->S(Dyhb::L('导入主题样式成功','Controller/Style'));
	}
	
	protected function show_Styles($sStylePath){
		$arrStyles=$this->get_styles($sStylePath);

		$bCurrentStyleIn=true;
		if($this->_sCurrentStyle && !in_array($sStylePath.'/'.$this->_sCurrentStyle,$arrStyles)){
			$arrStyles[]=$sStylePath.'/'.$this->_sCurrentStyle;
			$bCurrentStyleIn=false;
		}

		if(!Dyhb::classExists('Style')){
			require_once(Core_Extend::includeFile('class/Style'));
		}

		$oStyle=Dyhb::instance('Style');
		$oStyle->getStyles($arrStyles);

		$this->_arrOkStyles=$arrOkStyles=$oStyle->_arrOkStyles;
		$this->_arrBrokenStyles=$arrBrokenStyles=$oStyle->_arrBrokenStyles;

		if($bCurrentStyleIn===false){
			unset($arrOkStyles[$this->_sCurrentStyle]);
		}else{
			if(isset($arrOkStyles[$this->_sCurrentStyle])){
				$this->assign('arrCurrentStyle',$arrOkStyles[$this->_sCurrentStyle]);
			}
		}

		$this->assign('arrOkStyles',$arrOkStyles);
		$this->assign('arrBrokenStyles',$arrBrokenStyles);
		$this->assign('nOkStyleNums',$this->_nOkStyleNums=count($arrOkStyles));
		$this->assign('nBrokenStyleNums',$this->_nBrokenStyleNums=count($arrBrokenStyles));
		$this->assign('nOkStyleRowNums',ceil(count($arrOkStyles)/2));
	}

	protected function get_Styles($sDir){
		$nEverynum=$GLOBALS['_option_']['admin_theme_list_num'];
		$nPage=intval(G::getGpc('page','G'));

		$oPage=IoPage::RUN($sDir,$nEverynum,$nPage);
		$this->assign('sPageNavbar',$oPage->P());
		$this->assign('nPage',$nPage);

		return $oPage->getCurrentData();
	}

}
