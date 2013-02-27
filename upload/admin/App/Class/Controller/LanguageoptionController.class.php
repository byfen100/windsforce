<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   国际化语言包处理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class LanguageoptionController extends OptionController{

	public function index($sModel=null,$bDisplay=true){
		$arrOptionData=$GLOBALS['_option_'];

		$arrAdminlanguages=G::listDir(APP_PATH.'/App/Lang');

		Core_Extend::loadCache('lang');

		$this->assign('arrAdminlanguages',$arrAdminlanguages);
		$this->assign('sCurrentAdminlanguage',$GLOBALS['_option_']['admin_language_name']);
		$this->assign('arrFrontlanguages',$GLOBALS['_cache_']['lang']);
		$this->assign('sCurrentFrontlanguage',$GLOBALS['_option_']['front_language_name']);
		$this->assign('arrOptions',$arrOptionData);

		$this->display();
	}

	public function update_option(){
		$arrOptions=G::getGpc('options','P');
		$sAdminlanguage=trim($arrOptions['admin_language_name']);
		$sFrontlanguage=trim($arrOptions['front_language_name']);
		$nFrontlanguageSwitch=intval($arrOptions['language_switch_on']);

		// 修改后台语言包
		OptionModel::uploadOption('admin_language_name',strtolower($sAdminlanguage));
		Core_Extend::changeAppconfig('ADMIN_LANG_DIR',ucfirst($sAdminlanguage));
		Dyhb::cookie('admin_language',strtolower($sAdminlanguage));

		// 修改前台语言包
		OptionModel::uploadOption('front_language_name',strtolower($sFrontlanguage));
		Core_Extend::changeAppconfig('FRONT_LANG_DIR',ucfirst($sFrontlanguage));
		Dyhb::cookie('language',strtolower($sFrontlanguage));

		// 修改前台语言包是否允许切换
		OptionModel::uploadOption('language_switch_on',$nFrontlanguageSwitch);
		Core_Extend::changeAppconfig('LANG_SWITCH',$nFrontlanguageSwitch==1?true:false);

		$this->S(Dyhb::L('修改国际化语言成功','Controller/Languageoption'));
	}

}
