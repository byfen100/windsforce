<?php
/* [$WindsForce] (C)WindsForce TEAM Since 2012.03.17.
   模板管理控制器($Liu.XiangMin)*/

!defined('DYHB_PATH') && exit;

class ThemeController extends InitController{
	
	public function filter_(&$arrMap){
		$arrMap['theme_name']=array('like',"%".G::getGpc('theme_name')."%");
	}

	public function init__(){
		parent::init__();

		if($GLOBALS['___login___']['user_id']!=1){
			$this->E(Dyhb::L('只有用户ID为1的超级管理员才能够访问本页','Controller/Common'));
		}
	}

	public function bUpdate_($sThemeDirname=''){
		if(empty($sThemeDirname)){
			$_POST['theme_dirname']=ucfirst($_POST['theme_dirname']);
			$sThemeDirname=trim(G::getGpc('theme_dirname','P'));
		}
			
		$sThemeDirname=WINDSFORCE_PATH.'/ucontent/theme/'.$sThemeDirname;
		if(!is_dir($sThemeDirname)){
			$this->E(Dyhb::L('模板目录 %s 不存在','Controller/Theme',null,$sThemeDirname));
		}
	}
	
	public function bInsert_(){
		$this->bUpdate_();
	}

	public function bForeverdelete_(){
		$sId=G::getGpc('id','G');

		$arrIds=explode(',',$sId);
		foreach($arrIds as $nId){
			if($this->is_system_theme($nId)){
				$this->E(Dyhb::L('系统模板无法删除','Controller/Theme'));
			}
		}
	}

	public function bEdit_(){
		$nId=intval(G::getGpc('id','G'));

		if($this->is_system_theme($nId)){
			$this->E(Dyhb::L('系统模板无法编辑','Controller/Theme'));
		}
	}

	public function bInput_change_ajax_(){
		$nInputAjaxId=G::getGpc('input_ajax_id');

		if($this->is_system_theme($nInputAjaxId)){
			$this->E(Dyhb::L('系统模板无法编辑','Controller/Theme'));
		}

		$sInputAjaxField=G::getGpc('input_ajax_field');
		$sInputAjaxVal=G::getGpc('input_ajax_val');
		if($sInputAjaxField=='theme_dirname'){
			$this->bUpdate_($sInputAjaxVal);
		}
	}

	public function BInput_change_ajax_data_($arrData){
		$arrData['theme_dirname']=ucfirst($arrData['theme_dirname']);

		return $arrData;
	}

	public function is_system_theme($nId){
		if($nId==1){
			return true;
		}

		return false;
	}
	
}
